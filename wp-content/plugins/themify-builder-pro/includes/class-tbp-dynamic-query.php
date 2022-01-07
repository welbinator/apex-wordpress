<?php

final class Tbp_Dynamic_Query {

	private static $field_name = 'tbpdq';

	/* temporary cache for module settings to pass on to pre_get_posts */
	private static $_current_item = null;
	private static $_current_settings = null;

	public static function run() {
		add_action( 'themify_builder_module_render_vars', array( __CLASS__, 'themify_builder_module_render_vars' ) );
		add_filter( 'themify_builder_ajax_admin_vars', array( __CLASS__, 'themify_builder_ajax_vars' ) );
		add_filter( 'themify_builder_ajax_front_vars', array( __CLASS__, 'themify_builder_ajax_vars' ) );
	}

	public static function get_items() {
		static $items = null;

		if ( $items === null ) {
			$items = apply_filters( 'tbp_dynamic_query_items', array(
				'on' => 'Tbp_Dynamic_Query_Main_Loop',
				'childs' => 'Tbp_Dynamic_Query_Childs',
				'sameauthor' => 'Tbp_Dynamic_Query_SameAuthor',
			) );
		}

		return $items;
	}

	/**
	 * Get a DQ item by ID, returns its classname
	 *
	 * @return string|null
	 */
	public static function get_item( $id ) {
		$items = self::get_items();
		if ( isset( $items[ $id ] ) ) {
			return $items[ $id ];
		}
	}

	public static function themify_builder_ajax_vars( $vars ) {
		$items = self::get_items();

		$vars['DynamicQuery'] = array(
			array(
				'id' => self::$field_name,
				'type' => 'select',
				'label' => __('Dynamic Query', 'tbp'),
				'options' => array(
					'off' => __( 'Disabled', 'tbp' ),
				),
			),
		);

		foreach ( $items as $id => $classname ) {
			$vars['DynamicQuery'][0]['options'][ $id ] = $classname::get_label();
			$options = $classname::get_options();
			if ( ! empty( $options ) ) {
				foreach ( $options as $i => $option ) {
					if ( isset( $option['id'] ) ) {
						$options[ $i ]['id'] = 'tbpdq_' . $options[ $i ]['id'];
					}
				}

				array_push( $vars['DynamicQuery'], array(
					'type' => 'group',
					'wrap_class' => 'tbpdq_settings tbpdq_settings_' . $id,
					'options' => $options,
				) );
			}
		}

		return $vars;
	}

	/**
	 * Runs just before a module is rendered, enable Dynamic Query if applicable
	 *
	 * @return array
	 */
	public static function themify_builder_module_render_vars( $vars ) {
		/**
		 * Reset the "pre_get_posts" filter
		 * This is to ensure that filter is applied only once and does not affect other modules.
		 */
		remove_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );
		self::$_current_item = self::$_current_settings = null;

		if ( ! empty( $vars['mod_settings'][ self::$field_name ] ) && self::get_item( $vars['mod_settings'][ self::$field_name ] ) ) {
			self::$_current_item = $vars['mod_settings'][ self::$field_name ];

			/* cache module settings related to the DQ */
			foreach ( $vars['mod_settings'] as $option_name => $option_value ) {
				if ( substr( $option_name, 0, 6 ) === 'tbpdq_' ) {
					self::$_current_settings[ substr( $option_name, 6 ) ] = $option_value;
				}
			}

			add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );
		}

		return $vars;
	}

	/**
	 * Replace all the query vars of the current query with global $wp_query
	 *
	 */
	public static function pre_get_posts( &$query ) {
		/**
		 * In case this is the last module in the page and there are other queries running
		 * after this, reset "pre_get_posts" again to ensure this filter runs only once.
		 */
		remove_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );

		/* setup global $post in preview mode */
		if ( ! empty( $_POST['tb_post_id'] ) ) {
			if ( $post_object = get_post( (int) $_POST['tb_post_id'] ) ) {
				setup_postdata( $GLOBALS['post'] =& $post_object );
			}
		}

		/* cache Limit, Order and Orderby, these are configured in the module and probably need to remain untouched */
		$query_vars = [];
		foreach ( [ 'posts_per_page', 'order', 'orderby' ] as $query_var ) {
			$query_vars[ $query_var ] = $query->get( $query_var );
		}

		/* reset all query vars */
		$query->query_vars = null;

		$query->set( 'ignore_sticky_posts', true );
		foreach ( $query_vars as $query_var_key => $query_var_value ) {
			$query->set( $query_var_key, $query_var_value );
		}

		$item = self::get_item( self::$_current_item );
		if ( ! $item::pre_get_posts( $query, self::$_current_settings ) ) {
			$query->set( 'post__in', [ 0 ] ); /* disable posts display */
		}

		self::$_current_item = self::$_current_settings = null;
	}
}

class Tbp_Dynamic_Query_Main_Loop {

	static function get_id() {
		return 'on';
	}

	static function get_label() {
		return __( 'Main Loop', 'tbp' );
	}

	static function get_options() {
		return array();
	}

	static function pre_get_posts( &$query ) {
		if ( ! ( is_archive() || is_home() ) ) {
			return false;
		}

		global $wp_query;

		$query->query_vars = $wp_query->query_vars;
		if ( isset( $query->query['posts_per_page'] ) ) {
			$query->query_vars['posts_per_page'] = $query->query['posts_per_page'];
		}
		if ( isset( $query->query['offset'] ) ) {
			$query->query_vars['offset'] = $query->query['offset'];
		}
		if ( isset( $query->query['paged'] ) ) {
			$query->query_vars['paged'] = $query->query['paged'];
		}
		if ( $wp_query->is_home() ) {
			$query->query_vars['ignore_sticky_posts'] = false;
		}

		/* WC quirk: on its archive pages the "post_type" is empty. */
		if ( themify_is_woocommerce_active() && Tbp_Utils::is_wc_archive() ) {
			$query->query_vars['post_type'] = 'product';
		}

		return true;
	}
}

class Tbp_Dynamic_Query_Childs {

	static function get_id() {
		return 'childs';
	}

	static function get_label() {
		return __( 'Children of current post', 'tbp' );
	}

	static function get_options() {
		return array();
	}

	static function pre_get_posts( &$query, $settings ) {
		$post_id = get_the_ID();
		$query->set( 'post_type', get_post_type( $post_id ) );
		$query->set( 'post_parent', $post_id );

		return true;
	}
}

class Tbp_Dynamic_Query_SameAuthor {

	static function get_id() {
		return 'sameauthor';
	}

	static function get_label() {
		return __( 'Posts by the current author', 'tbp' );
	}

	static function get_options() {
		return array(
            array(
				'id' => 'sameauthor_match_post_type',
				'type' => 'select',
				'label' => __( 'Match Current Post Type', 'tbp' ),
				'options' => array(
					'y' => __( 'Yes', 'tbp' ),
					'n' => __( 'No', 'tbp' ),
				),
            ),
		);
	}

	static function pre_get_posts( &$query, $settings ) {
		global $post;
		if ( empty( $post ) ) {
			return false;
		}

		if ( isset( $settings['sameauthor_match_post_type'] ) && $settings['sameauthor_match_post_type'] === 'y' ) {
			$query->set( 'post_type', get_post_type( $post->ID ) );
		} else {
			$query->set( 'post_type', 'any' );
		}
		$query->set( 'author', $post->post_author );

		return true;
	}
}