<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRelations extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'wp_editor' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Relations)', 'tbp' );
	}

	function get_value( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'show' => 'grid',
			'columns' => 1,
			'minSlides' => 1,
			'autoHover' => 1,
			'pause' => 1,
			'pager' => 1,
			'controls' => 1,
			'orderby' => 'post__in',
			'order' => 'asc',
			'limit' => '',
			'masonry' => 0,
		) );

		if ( empty( $args['field'] ) ) {
			return '';
		}

		list( $post_type, $field_name ) = explode( ':', $args['field'] );
		$ptb = PTB::get_option()->get_options();

		if ( ! isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ] ) ) {
			return '';
		}

		$ptb_options = PTB::get_option();
		$def = $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ];
		$rel_options = PTB_Relation::get_option();
		$template = $rel_options->get_relation_template( $def['post_type'], get_post_type() );
		if ( ! $template ) {
			return '';
		}
		$themplate_layout = $ptb_options->get_post_type_template( $template['id'] );
		if ( ! isset( $themplate_layout['relation']['layout'] ) ) {
			return;
		}

		$content = '';
		$is_shortcode = PTB_Public::$shortcode;
		PTB_Public::$shortcode = true;
		$ver = PTB::get_plugin_version( WP_PLUGIN_DIR . '/themify-ptb-relation/themify-ptb-relation.php' );
		$themplate = new PTB_Form_PTT_Them( 'ptb', $ver );
		$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
		$relType = ! empty( $cf_value['relType'] ) ? (int) $cf_value['relType'] : 1;
		$ids = ! empty( $cf_value['ids'] ) ? $cf_value['ids'] : $cf_value;
		$ids = array_filter( is_array($ids)?$ids:explode( ', ', $ids ) );
		if ( empty( $ids ) ) {
			return;
		}
		$query_args = array(
			'post_type' => $def['post_type'],
			'post_status' => 'publish',
			'order' => $args['order'],
			'orderby' => $args['orderby'],
			'no_found_rows' => 1,
		);
		if ( $relType === 1 ) {
			$query_args['post__in'] = $ids;
			$query_args['posts_per_page'] = empty( $args['limit'] ) ? count( $ids ) : $args['limit'];
		} else {
			$tmp = array();
			$terms = get_terms( array(
				'include' => $ids
			) );
			foreach ( $terms as $term ) {
				$tmp[ $term->taxonomy ][] = $term->term_id;
			}
			$value = array();
			$temp = array();
			foreach ( $tmp as $k => $v ) {
				$value[] = array(
					'taxonomy' => $k,
					'field' => 'term_id',
					'terms' => $v
				);
			}
			if ( ! empty( $value ) ) {
				$value['relation'] = 'AND';
				$query_args['tax_query'] = $value;
			}
			if ( empty( $args['limit'] ) ) {
				$query_args['nopaging'] = 1;
			} else {
				$query_args['posts_per_page'] = $args['limit'];
			}
		}
		global $post;
		$old_post = clone $post;
		$query = new WP_Query;
		$rel_posts = $query->query( $query_args );
		$item_tag = $args['show'] === 'slider' ? 'li' : 'div';
		foreach ( $rel_posts as $p ) {
			$post = $p;
			setup_postdata( $post );

			$cmb_options = $post_support = $post_meta = $post_taxonomies = array();
			$ptb_options->get_post_type_data( $def['post_type'], $cmb_options, $post_support, $post_taxonomies );
			$post_meta['post_url'] = get_permalink();
			$post_meta['taxonomies'] = ! empty( $post_taxonomies ) ? wp_get_post_terms( get_the_ID(), array_values($post_taxonomies ) ) : array();
			$post_meta = array_merge( $post_meta, get_post_custom(), get_post( '', ARRAY_A ) );
			$item_class = $args['show'] === 'slider' ? 'tf_swiper-slide' : 'ptb_relation_item';
			$content .= '<' . $item_tag . ' class="' . $item_class . '">' . $themplate->display_public_themplate( $themplate_layout['relation'], $post_support, $cmb_options, $post_meta, $def['post_type'], false ) . '</' . $item_tag . '>';
		}
		PTB_Public::$shortcode = $is_shortcode;
		$post = $old_post;
		setup_postdata( $post );

		$wrap_class = 'ptb_loops_shortcode ptb_relation_posts tf_clearfix';

		if ( $args['show'] === 'slider' ) {
			if ( ! wp_script_is( 'ptb-relation' ) ) {
				wp_enqueue_script( 'ptb-relation' );
			}
			$content =
			'<div
				class="ptb_extra_post_slider tf_swiper-container"
				data-visible="' . $args['minSlides'] .'"
				data-pause_hover="' . $args['autoHover'] . '"
				data-auto="' . $args['pause'] .'"
				data-pager="' . $args['pager'] . '"
				data-slider_nav="' . $args['controls'] . '"
				data-speed="1000"
			>
				<div class="tf_swiper-wrapper">' .
					$content .
				'</div>' .
			'</div>';
		} else {
			$wrap_class .= ' ptb_relation_grid';
			$wrap_class .= ' ptb_relation_columns_' . $args['columns'];
			if ( $args['masonry'] ) {
				wp_enqueue_script( 'ptb-relation' );
				$wrap_class .= ' ptb_relation_masonry';
			}
		}

		if ( ! empty( $content ) ) {
			$content = 
				'<div class="ptb_module ptb_relation">'
					. '<div class="' . $wrap_class . '">'
						. $content
					. '</div>'
				. '</div>';
		}

		return $content;
	}

	function get_options() {
		$options = array();

		/* collect "relation" field types in all post types */
		$ptb = PTB::$options->get_custom_post_types();
		foreach ( $ptb as $post_type_key => $post_type ) {
			if ( is_array( $post_type->meta_boxes ) ) {
				foreach ( $post_type->meta_boxes as $key => $field ) {
					if ( $field['type'] === 'relation' ) {
					    $label = PTB_Utils::get_label( $post_type->plural_label );
					    $name = PTB_Utils::get_label( $field['name'] );
						$options[ "{$post_type_key}:{$key}" ] = sprintf( '%s: %s', $label, $name );
					}
				}
			}
		}

		return array(
			array(
				'label' => __( 'Field', 'tbp' ),
				'id' => 'field',
				'type' => 'select',
				'options' => $options,
			),
			array(
				'label' => __( 'Show', 'tbp' ),
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'grid' => __( 'Grid', 'tbp' ),
					'slider' => __( 'Slider', 'tbp' ),
				),
				'binding' => array(
					'grid' => array( 'show' => array( 'columns', 'masonry' ), 'hide' => array( 'minSlides', 'autoHover', 'pause', 'pager', 'controls' ) ),
					'slider' => array( 'hide' => array( 'columns', 'masonry' ), 'show' => array( 'minSlides', 'autoHover', 'pause', 'pager', 'controls' ) ),
				),
			),
			array(
				'label' => __( 'Grid Columns', 'tbp' ),
				'id' => 'columns',
				'type' => 'select',
				'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9 ),
			),
			array(
				'label' => __( 'Masonry', 'tbp' ),
				'id' => 'masonry',
				'type' => 'select',
				'options' => array( 0 => __( 'No', 'tbp' ), 1 => __( 'Yes', 'tbp' ) ),
			),
			array(
				'label' => __( 'Visible Slides', 'tbp' ),
				'id' => 'minSlides',
				'type' => 'select',
				'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7 ),
			),
			array(
				'label' => __( 'Pause On Hover', 'tbp' ),
				'id' => 'autoHover',
				'type' => 'select',
				'options' => array( 1 => __( 'Yes', 'tbp' ), 0 => __( 'No', 'tbp' ) ),
			),
			array(
				'label' => __( 'Auto Scroll', 'tbp' ),
				'id' => 'pause',
				'type' => 'select',
				'options' => array( 1 => __( '1 Second', 'tbp' ), 2 => __( '2 Seconds', 'tbp' ), 3 => __( '3 Seconds', 'tbp' ), 4 => __( '4 Seconds', 'tbp' ), 5 => __( '5 Seconds', 'tbp' ), 6 => __( '6 Seconds', 'tbp' ), 7 => __( '7 Seconds', 'tbp' ), 8 => __( '8 Seconds', 'tbp' ), 9 => __( '9 Seconds', 'tbp' ), '0' => __( 'Off', 'tbp' ) ),
			),
			array(
				'label' => __( 'Show Slider Pagination', 'tbp' ),
				'id' => 'pager',
				'type' => 'select',
				'options' => array( 1 => __( 'Yes', 'tbp' ), 0 => __( 'No', 'tbp' ) ),
			),
			array(
				'label' => __( 'Show Slider arrow buttons', 'tbp' ),
				'id' => 'controls',
				'type' => 'select',
				'options' => array( 1 => __( 'Yes', 'tbp' ), 0 => __( 'No', 'tbp' ) ),
			),
			array(
				'label' => __( 'Limit', 'tbp' ),
				'id' => 'limit',
				'type' => 'text',
				'help' => __( 'Maximum number of posts to display.', 'tbp' ),
			),
			array(
				'label' => __( 'Order', 'tbp' ),
				'id' => 'order',
				'type' => 'select',
				'options' => array(
					'asc' => __( 'Ascending', 'tbp' ),
					'desc' => __( 'Descending', 'tbp' ),
				),
			),
			array(
				'label' => __( 'Order By', 'tbp' ),
				'id' => 'orderby',
				'type' => 'select',
				'options' => array(
					'date' => __( 'Date', 'tbp' ),
					'id' => __( 'ID', 'tbp' ),
					'author' => __( 'Author', 'tbp' ),
					'title' => __( 'Title', 'tbp' ),
					'name' => __( 'Name', 'tbp' ),
					'modified' => __( 'Modified', 'tbp' ),
					'rand' => __( 'Random', 'tbp' ),
					'comment_count' => __( 'Comment Count', 'tbp' ),
					'menu_order' => __( 'Menu Order', 'tbp' ),
				),
			),
		);
	}
}