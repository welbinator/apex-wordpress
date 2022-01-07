<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Breadcrumbs
 * Description: 
 */

class TB_Breadcrumbs_Module extends Themify_Builder_Component_Module {

	/**
	 * Array of items belonging to the current breadcrumb trail.
	 *
	 * @access public
	 * @var    array
	 */
	public static $items = array();

	/**
	 * Arguments used to build the breadcrumb trail.
	 *
	 * @access public
	 * @var    array
	 */
	public static $args = array();

	/**
	 * Array of text labels.
	 *
	 * @access public
	 * @var    array
	 */
	public static $labels = array();

	/**
	 * Array of post types (key) and taxonomies (value) to use for single post views.
	 *
	 * @access public
	 * @var    array
	 */
	public static $post_taxonomy = array();

	function __construct() {
		parent::__construct(array(
		    'name' => __('Breadcrumbs', 'tbp'),
		    'slug' => 'breadcrumbs',
		    'category' => array( 'general', 'single', 'archive' )
		));
	}
	
	public function get_assets() {
		return array(
			'ver' => Tbp::get_version(),
			'css' => TBP_CSS_MODULES . $this->slug . '.css'
		);
	}
	
	public function get_icon(){
		return 'direction-alt';
	}
	
	public function get_options() {
		return array(
			array(
				'id' => 'tag',
				'label' => __( 'Container Tag', 'tbp'),
				'type' => 'select',
				'options' => array(
					'nav' => __( 'Nav', 'tbp'),
					'div' => __( 'Div', 'tbp')
				)
			),
            array(
                'id' => 'sep',
                'type' => 'radio',
                'label' => __('Separator', 'tbp'),
                'options' => array(
					array( 'value' => 'c', 'name' => __( 'Character', 'tbp' ) ),
					array( 'value' => 'i', 'name' => __( 'Icon', 'themify' ) )
                ),
                'option_js' => true
            ),
			array(
				'type' => 'text',
				'label' => __( 'Separator Character', 'tbp' ),
				'id' => 'sep_c',
				'wrap_class' => 'tb_group_element_c'
			),
			array(
				'id' => 'sep_icon',
				'type' => 'icon',
				'label' => __('Icon', 'tbp'),
				'wrap_class' => 'tb_group_element_i'
			),
			array(
				'id' => 'hide_network',
				'label' => __( 'Link to main site', 'tbp' ),
				'help' => __( 'Whether to link to the main site on the network (WordPress multisite only).', 'tbp' ),
				'type' => 'toggle_switch',
			),
			array(
				'type' => 'separator',
				'label' => __( 'Labels', 'tbp' )
			),
			array(
				'type' => 'text',
				'label' => __( 'Home', 'tbp' ),
				'id' => 'lb_home',
			),
			array(
				'type' => 'text',
				'label' => __( '404 Page', 'tbp' ),
				'id' => 'lb_404',
			),
			array(
				'type' => 'text',
				'label' => __( 'Archives', 'tbp' ),
				'id' => 'lb_archives',
			),
			array('type' => 'tbp_custom_css')
		);
	}

	public function get_styling() {
		$general = array(
		    //background
		    self::get_expand('bg', array(
		       self::get_tab(array(
			   'n' => array(
			       'options' => array(
				   self::get_color('', 'background_color', 'bg_c', 'background-color')
			       )
			   ),
			   'h' => array(
			       'options' => array(
				   self::get_color('', 'bg_c', 'bg_c', 'background-color', 'h')
			       )
			   )
		       ))
		   )),
		    self::get_expand('f', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_font_family(),
				    self::get_color_type(),
				    self::get_font_size(),
				    self::get_line_height(),
				    self::get_text_align(),
					self::get_text_shadow(),
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_font_family('', 'f_f', 'h'),
				    self::get_color_type('','h'),
				    self::get_font_size('', 'f_s', '', 'h'),
				    self::get_line_height('','l_h','h'),
				    self::get_text_align('', 't_a', 'h'),
					self::get_text_shadow('','t_sh','h'),
				)
			    )
			))
		    )),
			// Link
			self::get_expand('l', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color(' a', 'link_color'),
					self::get_text_decoration(' a')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color(' a', 'link_color',null, null, 'hover'),
					self::get_text_decoration(' a', 't_d', 'h')
				)
				)
			))
			)),
		    // Padding
		    self::get_expand('p', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_padding()
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_padding('', 'p', 'h')
				)
			    )
			))
		    )),
		    // Margin
		    self::get_expand('m', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_margin()
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_margin('', 'm', 'h')
				)
			    )
			))
		    )),
		    // Border
		    self::get_expand('b', array(
			self::get_tab(array(
			    'n' => array(
				'options' => array(
				    self::get_border()
				)
			    ),
			    'h' => array(
				'options' => array(
				    self::get_border('', 'b', 'h')
				)
			    )
			))
		    )),
			// Width
			self::get_expand('w', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_width('', 'w')
						)
					),
					'h' => array(
						'options' => array(
							self::get_width('', 'w', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
					self::get_tab(array(
						'n' => array(
							'options' => array(
								self::get_border_radius()
							)
						),
						'h' => array(
							'options' => array(
								self::get_border_radius('', 'r_c', 'h')
							)
						)
					))
				)
			),
			// Shadow
			self::get_expand('sh', array(
					self::get_tab(array(
						'n' => array(
							'options' => array(
								self::get_box_shadow()
							)
						),
						'h' => array(
							'options' => array(
								self::get_box_shadow('', 'sh', 'h')
							)
						)
					))
				)
			),
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
			),
		);
	}

	public function get_live_default() {
		return array(
			'sep_c' => '/',
		);
	}

	public function get_visual_type() {
		return 'ajax';
	}

	public function get_category() {
	    return array( 'general' );
	}

	public static function display( $args = array() ) {
		$defaults = array(
			'container'       => 'nav',
			'before'          => '',
			'after'           => '',
			'show_on_front'   => true,
			'network'         => false,
			'show_title'      => true,
			'labels'          => array(),
			'post_taxonomy'   => array(),
			'echo'            => true,
			'seperator'       => '',
		);

		self::$args = wp_parse_args( $args, $defaults );

		self::set_labels( self::$args['labels'] );
		self::set_post_taxonomy();

		// Let's find some items to add to the trail!
		self::add_items();

		self::trail();
	}

	/**
	 * Formats the HTML output for the breadcrumb trail.
	 *
	 * @since  0.6.0
	 * @access public
	 * @return string
	 */
	public static function trail() {

		$breadcrumb    = '';
		$item_count    = count( self::$items );
		$item_position = 0;

		if ( 0 < $item_count ) {

			$breadcrumb .= '<ul class="tbp_trail_items" itemscope itemtype="http://schema.org/BreadcrumbList">';

			$breadcrumb .= sprintf( '<meta name="numberOfItems" content="%d" />', absint( $item_count ) );
			$breadcrumb .= '<meta name="itemListOrder" content="Ascending" />';

			// Loop through the items and add them to the list.
			foreach ( self::$items as $item ) {
				++$item_position;

				// Check if the item is linked.
				preg_match( '/(<a.*?>)(.*?)(<\/a>)/i', $item, $matches );

				$item = !empty( $matches ) ? sprintf( '%s<span>%s</span>%s', $matches[1], $matches[2], $matches[3] ) : sprintf( '<span>%s</span>', $item );

				$item_class = 'tbp_trail_item';

				$separator = '';
				if ( 1 !== $item_position ) {
					$separator = '<span class="tbp_breadcrumb_sep">' . self::$args['separator'] . '</span>';
				}

				if ( 1 === $item_position && 1 < $item_count ) {
					$item_class .= ' tbp_trail_begin';
				} elseif ( $item_count === $item_position ) {
					$item_class .= ' tbp_trail_end';
				}

				$attributes = 'class="' . $item_class . '"';
				$meta = sprintf( '<meta content="%s" />', absint( $item_position ) );
				$breadcrumb .= sprintf( '<li %s>%s%s%s</li>', $attributes, $separator, $item, $meta );
			}

			// Close the unordered list.
			$breadcrumb .= '</ul>';

			$breadcrumb = sprintf(
				'<%1$s role="navigation" aria-label="%2$s" class="tbp_breadcrumb_trail">%3$s%4$s%5$s</%1$s>',
				tag_escape( self::$args['container'] ),
				esc_attr( self::$labels['aria_label'] ),
				self::$args['before'],
				$breadcrumb,
				self::$args['after']
			);
		}

		$breadcrumb = apply_filters( 'tbp_breadcrumb_trail', $breadcrumb, self::$args );

		if ( false === self::$args['echo'] )
			return $breadcrumb;

		echo $breadcrumb;
	}

	/**
	 * Sets the labels property.  Parses the inputted labels array with the defaults.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function set_labels( $args = array() ) {

		$args = array_filter( $args );

		self::$labels = wp_parse_args( $args, array(
			'aria_label'          => esc_attr_x( 'Breadcrumbs', 'breadcrumbs aria label', 'tbp' ),
			'home'                => esc_html__( 'Home',                                  'tbp' ),
			'error_404'           => esc_html__( '404 Not Found',                         'tbp' ),
			'archives'            => esc_html__( 'Archives',                              'tbp' ),
			// Translators: %s is the search query.
			'search'              => esc_html__( 'Search results for: %s',                'tbp' ),
			// Translators: %s is the page number.
			'paged'               => esc_html__( 'Page %s',                               'tbp' ),
			// Translators: %s is the page number.
			'paged_comments'      => esc_html__( 'Comment Page %s',                       'tbp' ),
			// Translators: Minute archive title. %s is the minute time format.
			'archive_minute'      => esc_html__( 'Minute %s',                             'tbp' ),
			// Translators: Weekly archive title. %s is the week date format.
			'archive_week'        => esc_html__( 'Week %s',                               'tbp' ),

			// "%s" is replaced with the translated date/time format.
			'archive_minute_hour' => '%s',
			'archive_hour'        => '%s',
			'archive_day'         => '%s',
			'archive_month'       => '%s',
			'archive_year'        => '%s',
		) );
	}

	/**
	 * Sets the `$post_taxonomy` property.  This is an array of post types (key) and taxonomies (value).
	 * The taxonomy's terms are shown on the singular post view if set.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function set_post_taxonomy() {

		$defaults = array();

		// If post permalink is set to `%postname%`, use the `category` taxonomy.
		if ( '%postname%' === trim( get_option( 'permalink_structure' ), '/' ) )
			$defaults['post'] = 'category';

		self::$post_taxonomy = apply_filters( 'tbp_breadcrumb_trail_post_taxonomy', wp_parse_args( self::$args['post_taxonomy'], $defaults ) );
	}

	/**
	 * Runs through the various WordPress conditional tags to check the current page being viewed.  Once
	 * a condition is met, a specific method is launched to add items to the `$items` array.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_items() {

		if ( is_front_page() ) {
			self::add_front_page_items();
		} else {

			self::add_network_home_link();
			self::add_site_home_link();

			if ( is_home() ) {
				self::add_blog_items();
			}

			elseif ( is_singular() ) {
				self::add_singular_items();
			}

			elseif ( is_archive() ) {

				if ( is_post_type_archive() )
					self::add_post_type_archive_items();

				elseif ( is_category() || is_tag() || is_tax() )
					self::add_term_archive_items();

				elseif ( is_author() )
					self::add_user_archive_items();

				elseif ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
					self::add_minute_hour_archive_items();

				elseif ( get_query_var( 'minute' ) )
					self::add_minute_archive_items();

				elseif ( get_query_var( 'hour' ) )
					self::add_hour_archive_items();

				elseif ( is_day() )
					self::add_day_archive_items();

				elseif ( get_query_var( 'w' ) )
					self::add_week_archive_items();

				elseif ( is_month() )
					self::add_month_archive_items();

				elseif ( is_year() )
					self::add_year_archive_items();

				else
					self::add_default_archive_items();
			}

			elseif ( is_search() ) {
				self::add_search_items();
			}

			elseif ( is_404() ) {
				self::add_404_items();
			}
		}

		self::add_paged_items();

		self::$items = array_unique( apply_filters( 'tbp_breadcrumb_trail_items', self::$items, self::$args ) );
	}

	/**
	 * Gets front items based on $wp_rewrite->front.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_rewrite_front_items() {
		global $wp_rewrite;

		if ( $wp_rewrite->front )
			self::add_path_parents( $wp_rewrite->front );
	}

	/**
	 * Adds the page/paged number to the items array.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_paged_items() {

		// If viewing a paged singular post.
		if ( is_singular() && 1 < get_query_var( 'page' ) && true === self::$args['show_title'] )
			self::$items[] = sprintf( self::$labels['paged'], number_format_i18n( absint( get_query_var( 'page' ) ) ) );

		// If viewing a singular post with paged comments.
		elseif ( is_singular() && get_option( 'page_comments' ) && 1 < get_query_var( 'cpage' ) )
			self::$items[] = sprintf( self::$labels['paged_comments'], number_format_i18n( absint( get_query_var( 'cpage' ) ) ) );

		// If viewing a paged archive-type page.
		elseif ( is_paged() && true === self::$args['show_title'] )
			self::$items[] = sprintf( self::$labels['paged'], number_format_i18n( absint( get_query_var( 'paged' ) ) ) );
	}

	/**
	 * Adds the network (all sites) home page link to the items array.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_network_home_link() {

		if ( is_multisite() && ! is_main_site() && true === self::$args['network'] )
			self::$items[] = sprintf( '<a href="%s" rel="home">%s</a>', esc_url( network_home_url() ), self::$labels['home'] );
	}

	/**
	 * Adds the current site's home page link to the items array.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_site_home_link() {

		$network = is_multisite() && !is_main_site() && true === self::$args['network'];
		$label   = $network ? get_bloginfo( 'name' ) : self::$labels['home'];
		$rel     = $network ? '' : ' rel="home"';

		self::$items[] = sprintf( '<a href="%s"%s>%s</a>', esc_url( user_trailingslashit( home_url() ) ), $rel, $label );
	}

	/**
	 * Adds items for the front page to the items array.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected static function add_front_page_items() {

		// Only show front items if the 'show_on_front' argument is set to 'true'.
		if ( true === self::$args['show_on_front'] || is_paged() || ( is_singular() && 1 < get_query_var( 'page' ) ) ) {

			// If on a paged view, add the site home link.
			if ( is_paged() )
				self::add_site_home_link();

			// If on the main front page, add the network home title.
			elseif ( true === self::$args['show_title'] )
				self::$items[] = is_multisite() && true === self::$args['network'] ? get_bloginfo( 'name' ) : self::$labels['home'];
		}
	}

	/**
	 * Adds items for the posts page (i.e., is_home()) to the items array.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_blog_items() {

		$post_id = get_queried_object_id();
		$post    = get_post( $post_id );

		// If the post has parents, add them to the trail.
		if ( 0 < $post->post_parent )
			self::add_post_parents( $post->post_parent );

		$title = get_the_title( $post_id );

		if ( is_paged() )
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $post_id ) ), $title );

		elseif ( $title && true === self::$args['show_title'] )
			self::$items[] = $title;
	}

	/**
	 * Adds singular post items to the items array.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_singular_items() {

		// Get the queried post.
		$post    = get_queried_object();
		$post_id = get_queried_object_id();

		// If the post has a parent, follow the parent trail.
		if ( 0 < $post->post_parent )
			self::add_post_parents( $post->post_parent );

		// If the post doesn't have a parent, get its hierarchy based off the post type.
		else
			self::add_post_hierarchy( $post_id );

		// Display terms for specific post type taxonomy if requested.
		if ( !empty( self::$post_taxonomy[ $post->post_type ] ) )
			self::add_post_terms( $post_id, self::$post_taxonomy[ $post->post_type ] );

		// End with the post title.
		if ( $post_title = single_post_title( '', false ) ) {

			if ( ( 1 < get_query_var( 'page' ) || is_paged() ) || ( get_option( 'page_comments' ) && 1 < absint( get_query_var( 'cpage' ) ) ) )
				self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $post_id ) ), $post_title );

			elseif ( true === self::$args['show_title'] )
				self::$items[] = $post_title;
		}
	}

	/**
	 * Adds the items to the trail items array for taxonomy term archives.
	 *
	 * @access protected
	 * @global object $wp_rewrite
	 * @return void
	 */
	protected static function add_term_archive_items() {
		global $wp_rewrite;

		$term           = get_queried_object();
		$taxonomy       = get_taxonomy( $term->taxonomy );
		$done_post_type = false;

		// If there are rewrite rules for the taxonomy.
		if ( false !== $taxonomy->rewrite ) {

			// If 'with_front' is true, add $wp_rewrite->front to the trail.
			if ( $taxonomy->rewrite['with_front'] && $wp_rewrite->front )
				self::add_rewrite_front_items();

			// Get parent pages by path if they exist.
			self::add_path_parents( $taxonomy->rewrite['slug'] );

			// Add post type archive if its 'has_archive' matches the taxonomy rewrite 'slug'.
			if ( $taxonomy->rewrite['slug'] ) {

				$slug = trim( $taxonomy->rewrite['slug'], '/' );

				// Deals with the situation if the slug has a '/' between multiple
				// strings. For example, "movies/genres" where "movies" is the post
				// type archive.
				$matches = explode( '/', $slug );

				// If matches are found for the path.
				if ( isset( $matches ) ) {

					// Reverse the array of matches to search for posts in the proper order.
					$matches = array_reverse( $matches );

					// Loop through each of the path matches.
					foreach ( $matches as $match ) {

						// If a match is found.
						$slug = $match;

						// Get public post types that match the rewrite slug.
						$post_types = self::get_post_types_by_slug( $match );

						if ( !empty( $post_types ) ) {

							$post_type_object = $post_types[0];

							// Add support for a non-standard label of 'archive_title' (special use case).
							$label = !empty( $post_type_object->labels->archive_title ) ? $post_type_object->labels->archive_title : $post_type_object->labels->name;

							// Core filter hook.
							$label = apply_filters( 'post_type_archive_title', $label, $post_type_object->name );

							// Add the post type archive link to the trail.
							self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type_object->name ) ), $label );

							$done_post_type = true;

							// Break out of the loop.
							break;
						}
					}
				}
			}
		}

		// If there's a single post type for the taxonomy, use it.
		if ( false === $done_post_type && 1 === count( $taxonomy->object_type ) && post_type_exists( $taxonomy->object_type[0] ) ) {

			// If the post type is 'post'.
			if ( 'post' === $taxonomy->object_type[0] ) {
				$post_id = get_option( 'page_for_posts' );

				if ( 'posts' !== get_option( 'show_on_front' ) && 0 < $post_id )
					self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $post_id ) ), get_the_title( $post_id ) );

			// If the post type is not 'post'.
			} else {
				$post_type_object = get_post_type_object( $taxonomy->object_type[0] );

				$label = !empty( $post_type_object->labels->archive_title ) ? $post_type_object->labels->archive_title : $post_type_object->labels->name;

				// Core filter hook.
				$label = apply_filters( 'post_type_archive_title', $label, $post_type_object->name );

				self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type_object->name ) ), $label );
			}
		}

		// If the taxonomy is hierarchical, list its parent terms.
		if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent )
			self::add_term_parents( $term->parent, $term->taxonomy );

		// Add the term name to the trail end.
		if ( is_paged() )
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $term, $term->taxonomy ) ), single_term_title( '', false ) );

		elseif ( true === self::$args['show_title'] )
			self::$items[] = single_term_title( '', false );
	}

	/**
	 * Adds the items to the trail items array for post type archives.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_post_type_archive_items() {

		// Get the post type object.
		$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );

		if ( false !== $post_type_object->rewrite ) {

			// If 'with_front' is true, add $wp_rewrite->front to the trail.
			if ( $post_type_object->rewrite['with_front'] )
				self::add_rewrite_front_items();

			// If there's a rewrite slug, check for parents.
			if ( !empty( $post_type_object->rewrite['slug'] ) )
				self::add_path_parents( $post_type_object->rewrite['slug'] );
		}

		if ( is_paged() || is_author() )
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type_object->name ) ), post_type_archive_title( '', false ) );

		elseif ( true === self::$args['show_title'] )
			self::$items[] = post_type_archive_title( '', false );

		if ( is_author() )
			self::add_user_archive_items();
	}

	/**
	 * Adds the items to the trail items array for user (author) archives.
	 *
	 * @access protected
	 * @global object $wp_rewrite
	 * @return void
	 */
	protected static function add_user_archive_items() {
		global $wp_rewrite;

		// Add $wp_rewrite->front to the trail.
		self::add_rewrite_front_items();

		$user_id = get_query_var( 'author' );

		// If $author_base exists, check for parent pages.
		if ( !empty( $wp_rewrite->author_base ) && ! is_post_type_archive() )
			self::add_path_parents( $wp_rewrite->author_base );

		// Add the author's display name to the trail end.
		if ( is_paged() )
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_author_posts_url( $user_id ) ), get_the_author_meta( 'display_name', $user_id ) );

		elseif ( true === self::$args['show_title'] )
			self::$items[] = get_the_author_meta( 'display_name', $user_id );
	}

	/**
	 * Adds the items to the trail items array for minute + hour archives.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_minute_hour_archive_items() {

		// Add $wp_rewrite->front to the trail.
		self::add_rewrite_front_items();

		// Add the minute + hour item.
		if ( true === self::$args['show_title'] )
			self::$items[] = sprintf( self::$labels['archive_minute_hour'], get_the_time( esc_html_x( 'g:i a', 'minute and hour archives time format', 'tbp' ) ) );
	}

	/**
	 * Adds the items to the trail items array for minute archives.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected static function add_minute_archive_items() {

		// Add $wp_rewrite->front to the trail.
		self::add_rewrite_front_items();

		// Add the minute item.
		if ( true === self::$args['show_title'] )
			self::$items[] = sprintf( self::$labels['archive_minute'], get_the_time( esc_html_x( 'i', 'minute archives time format', 'tbp' ) ) );
	}

	/**
	 * Adds the items to the trail items array for hour archives.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_hour_archive_items() {

		// Add $wp_rewrite->front to the trail.
		self::add_rewrite_front_items();

		// Add the hour item.
		if ( true === self::$args['show_title'] )
			self::$items[] = sprintf( self::$labels['archive_hour'], get_the_time( esc_html_x( 'g a', 'hour archives time format', 'tbp' ) ) );
	}

	/**
	 * Adds the items to the trail items array for day archives.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_day_archive_items() {

		// Add $wp_rewrite->front to the trail.
		self::add_rewrite_front_items();

		// Get year, month, and day.
		$year  = sprintf( self::$labels['archive_year'],  get_the_time( esc_html_x( 'Y', 'yearly archives date format',  'tbp' ) ) );
		$month = sprintf( self::$labels['archive_month'], get_the_time( esc_html_x( 'F', 'monthly archives date format', 'tbp' ) ) );
		$day   = sprintf( self::$labels['archive_day'],   get_the_time( esc_html_x( 'j', 'daily archives date format',   'tbp' ) ) );

		// Add the year and month items.
		self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y' ) ) ), $year );
		self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ), $month );

		// Add the day item.
		if ( is_paged() )
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_day_link( get_the_time( 'Y' ) ), get_the_time( 'm' ), get_the_time( 'd' ) ), $day );

		elseif ( true === self::$args['show_title'] )
			self::$items[] = $day;
	}

	/**
	 * Adds the items to the trail items array for week archives.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_week_archive_items() {

		// Add $wp_rewrite->front to the trail.
		self::add_rewrite_front_items();

		// Get the year and week.
		$year = sprintf( self::$labels['archive_year'],  get_the_time( esc_html_x( 'Y', 'yearly archives date format', 'tbp' ) ) );
		$week = sprintf( self::$labels['archive_week'],  get_the_time( esc_html_x( 'W', 'weekly archives date format', 'tbp' ) ) );

		// Add the year item.
		self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y' ) ) ), $year );

		// Add the week item.
		if ( is_paged() )
			self::$items[] = esc_url( get_archives_link( add_query_arg( array( 'm' => get_the_time( 'Y' ), 'w' => get_the_time( 'W' ) ), home_url() ), $week, false ) );

		elseif ( true === self::$args['show_title'] )
			self::$items[] = $week;
	}

	/**
	 * Adds the items to the trail items array for month archives.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_month_archive_items() {

		// Add $wp_rewrite->front to the trail.
		self::add_rewrite_front_items();

		// Get the year and month.
		$year  = sprintf( self::$labels['archive_year'],  get_the_time( esc_html_x( 'Y', 'yearly archives date format',  'tbp' ) ) );
		$month = sprintf( self::$labels['archive_month'], get_the_time( esc_html_x( 'F', 'monthly archives date format', 'tbp' ) ) );

		// Add the year item.
		self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y' ) ) ), $year );

		// Add the month item.
		if ( is_paged() )
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ), $month );

		elseif ( true === self::$args['show_title'] )
			self::$items[] = $month;
	}

	/**
	 * Adds the items to the trail items array for year archives.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_year_archive_items() {

		// Add $wp_rewrite->front to the trail.
		self::add_rewrite_front_items();

		// Get the year.
		$year  = sprintf( self::$labels['archive_year'],  get_the_time( esc_html_x( 'Y', 'yearly archives date format',  'tbp' ) ) );

		// Add the year item.
		if ( is_paged() )
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y' ) ) ), $year );

		elseif ( true === self::$args['show_title'] )
			self::$items[] = $year;
	}

	/**
	 * Adds the items to the trail items array for archives that don't have a more specific method
	 * defined in this class.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_default_archive_items() {

		// If this is a date-/time-based archive, add $wp_rewrite->front to the trail.
		if ( is_date() || is_time() )
			self::add_rewrite_front_items();

		if ( true === self::$args['show_title'] )
			self::$items[] = self::$labels['archives'];
	}

	/**
	 * Adds the items to the trail items array for search results.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected static function add_search_items() {

		if ( is_paged() )
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_search_link() ), sprintf( self::$labels['search'], get_search_query() ) );

		elseif ( true === self::$args['show_title'] )
			self::$items[] = sprintf( self::$labels['search'], get_search_query() );
	}

	/**
	 * Adds the items to the trail items array for 404 pages.
	 *
	 * @access protected
	 * @return void
	 */
	protected static function add_404_items() {

		if ( true === self::$args['show_title'] )
			self::$items[] = self::$labels['error_404'];
	}

	/**
	 * Adds a specific post's parents to the items array.
	 *
	 * @access protected
	 * @param  int    $post_id
	 * @return void
	 */
	protected static function add_post_parents( $post_id ) {
		$parents = array();

		while ( $post_id ) {

			$post = get_post( $post_id );

			// If we hit a page that's set as the front page, bail.
			if ( 'page' == $post->post_type && 'page' == get_option( 'show_on_front' ) && $post_id == get_option( 'page_on_front' ) )
				break;

			// Add the formatted post link to the array of parents.
			$parents[] = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $post_id ) ), get_the_title( $post_id ) );

			if ( 0 >= $post->post_parent )
				break;

			// Change the post ID to the parent post to continue looping.
			$post_id = $post->post_parent;
		}

		// Get the post hierarchy based off the final parent post.
		self::add_post_hierarchy( $post_id );

		// Display terms for specific post type taxonomy if requested.
		if ( !empty( self::$post_taxonomy[ $post->post_type ] ) )
			self::add_post_terms( $post_id, self::$post_taxonomy[ $post->post_type ] );

		self::$items = array_merge( self::$items, array_reverse( $parents ) );
	}

	/**
	 * Adds a specific post's hierarchy to the items array.  The hierarchy is determined by post type's
	 * rewrite arguments and whether it has an archive page.
	 *
	 * @access protected
	 * @param  int    $post_id
	 * @return void
	 */
	protected static function add_post_hierarchy( $post_id ) {

		// Get the post type.
		$post_type        = get_post_type( $post_id );
		$post_type_object = get_post_type_object( $post_type );

		// If this is the 'post' post type, get the rewrite front items and map the rewrite tags.
		if ( 'post' === $post_type ) {

			// Add $wp_rewrite->front to the trail.
			self::add_rewrite_front_items();

			// Map the rewrite tags.
			self::map_rewrite_tags( $post_id, get_option( 'permalink_structure' ) );
		}

		// If the post type has rewrite rules.
		elseif ( false !== $post_type_object->rewrite ) {

			// If 'with_front' is true, add $wp_rewrite->front to the trail.
			if ( $post_type_object->rewrite['with_front'] )
				self::add_rewrite_front_items();

			// If there's a path, check for parents.
			if ( !empty( $post_type_object->rewrite['slug'] ) )
				self::add_path_parents( $post_type_object->rewrite['slug'] );
		}

		// If there's an archive page, add it to the trail.
		if ( $post_type_object->has_archive ) {

			// Add support for a non-standard label of 'archive_title' (special use case).
			$label = !empty( $post_type_object->labels->archive_title ) ? $post_type_object->labels->archive_title : $post_type_object->labels->name;

			// Core filter hook.
			$label = apply_filters( 'post_type_archive_title', $label, $post_type_object->name );

			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_post_type_archive_link( $post_type ) ), $label );
		}

		// Map the rewrite tags if there's a `%` in the slug.
		if ( 'post' !== $post_type && ! empty( $post_type_object->rewrite['slug'] ) && false !== strpos( $post_type_object->rewrite['slug'], '%' ) )
			self::map_rewrite_tags( $post_id, $post_type_object->rewrite['slug'] );
	}

	/**
	 * Gets post types by slug.  This is needed because the get_post_types() static function doesn't exactly
	 * match the 'has_archive' argument when it's set as a string instead of a boolean.
	 *
	 * @access protected
	 * @param  int    $slug  The post type archive slug to search for.
	 * @return void
	 */
	protected static function get_post_types_by_slug( $slug ) {

		$return = array();

		$post_types = get_post_types( array(), 'objects' );

		foreach ( $post_types as $type ) {

			if ( $slug === $type->has_archive || ( true === $type->has_archive && $slug === $type->rewrite['slug'] ) )
				$return[] = $type;
		}

		return $return;
	}

	/**
	 * Adds a post's terms from a specific taxonomy to the items array.
	 *
	 * @access protected
	 * @param  int     $post_id  The ID of the post to get the terms for.
	 * @param  string  $taxonomy The taxonomy to get the terms from.
	 * @return void
	 */
	protected static function add_post_terms( $post_id, $taxonomy ) {

		$post_type = get_post_type( $post_id );

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( $terms && ! is_wp_error( $terms ) ) {

			// Sort the terms by ID and get the first category.
			if ( function_exists( 'wp_list_sort' ) )
				$terms = wp_list_sort( $terms, 'term_id' );
			else
				usort( $terms, '_usort_terms_by_ID' );

			$term = get_term( $terms[0], $taxonomy );

			// If the category has a parent, add the hierarchy to the trail.
			if ( 0 < $term->parent )
				self::add_term_parents( $term->parent, $taxonomy );

			// Add the category archive link to the trail.
			self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $term, $taxonomy ) ), $term->name );
		}
	}

	/**
	 * Get parent posts by path.  Currently, this method only supports getting parents of the 'page'
	 * post type.  The goal of this static function is to create a clear path back to home given what would
	 * normally be a "ghost" directory.  If any page matches the given path, it'll be added.
	 *
	 * @access protected
	 * @param  string $path The path (slug) to search for posts by.
	 * @return void
	 */
	static function add_path_parents( $path ) {

		// Trim '/' off $path in case we just got a simple '/' instead of a real path.
		$path = trim( $path, '/' );

		// If there's no path, return.
		if ( empty( $path ) )
			return;

		// Get parent post by the path.
		$post = get_page_by_path( $path );

		if ( !empty( $post ) ) {
			self::add_post_parents( $post->ID );
		}

		elseif ( is_null( $post ) ) {

			// Separate post names into separate paths by '/'.
			$path = trim( $path, '/' );
			preg_match_all( "/\/.*?\z/", $path, $matches );

			// If matches are found for the path.
			if ( isset( $matches ) ) {

				// Reverse the array of matches to search for posts in the proper order.
				$matches = array_reverse( $matches );

				// Loop through each of the path matches.
				foreach ( $matches as $match ) {

					// If a match is found.
					if ( isset( $match[0] ) ) {

						// Get the parent post by the given path.
						$path = str_replace( $match[0], '', $path );
						$post = get_page_by_path( trim( $path, '/' ) );

						// If a parent post is found, set the $post_id and break out of the loop.
						if ( !empty( $post ) && 0 < $post->ID ) {
							self::add_post_parents( $post->ID );
							break;
						}
					}
				}
			}
		}
	}

	/**
	 * Searches for term parents of hierarchical taxonomies.  This static function is similar to the WordPress
	 * static function get_category_parents() but handles any type of taxonomy.
	 *
	 * @param  int    $term_id  ID of the term to get the parents of.
	 * @param  string $taxonomy Name of the taxonomy for the given term.
	 * @return void
	 */
	static function add_term_parents( $term_id, $taxonomy ) {

		$parents = array();

		while ( $term_id ) {

			// Get the parent term.
			$term = get_term( $term_id, $taxonomy );
			$parents[] = sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $term, $taxonomy ) ), $term->name );
			$term_id = $term->parent;
		}

		// If we have parent terms, reverse the array to put them in the proper order for the trail.
		if ( !empty( $parents ) )
			self::$items = array_merge( self::$items, array_reverse( $parents ) );
	}

	/**
	 * Turns %tag% from permalink structures into usable links for the breadcrumb trail.
	 *
	 * @access protected
	 * @param  int    $post_id ID of the post whose parents we want.
	 * @param  string $path    Path of a potential parent page.
	 * @param  array  $args    Mixed arguments for the menu.
	 * @return array
	 */
	protected static function map_rewrite_tags( $post_id, $path ) {

		$post = get_post( $post_id );

		// Trim '/' from both sides of the $path.
		$path = trim( $path, '/' );

		$matches = explode( '/', $path );
		if ( is_array( $matches ) ) {
			foreach ( $matches as $match ) {

				// Trim any '/' from the $match.
				$tag = trim( $match, '/' );

				// If using the %year% tag, add a link to the yearly archive.
				if ( '%year%' == $tag )
					self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_year_link( get_the_time( 'Y', $post_id ) ) ), sprintf( self::$labels['archive_year'], get_the_time( esc_html_x( 'Y', 'yearly archives date format',  'tbp' ) ) ) );

				// If using the %monthnum% tag, add a link to the monthly archive.
				elseif ( '%monthnum%' == $tag )
					self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_month_link( get_the_time( 'Y', $post_id ), get_the_time( 'm', $post_id ) ) ), sprintf( self::$labels['archive_month'], get_the_time( esc_html_x( 'F', 'monthly archives date format', 'tbp' ) ) ) );

				// If using the %day% tag, add a link to the daily archive.
				elseif ( '%day%' == $tag )
					self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_day_link( get_the_time( 'Y', $post_id ), get_the_time( 'm', $post_id ), get_the_time( 'd', $post_id ) ) ), sprintf( self::$labels['archive_day'], get_the_time( esc_html_x( 'j', 'daily archives date format', 'tbp' ) ) ) );

				// If using the %author% tag, add a link to the post author archive.
				elseif ( '%author%' == $tag )
					self::$items[] = sprintf( '<a href="%s">%s</a>', esc_url( get_author_posts_url( $post->post_author ) ), get_the_author_meta( 'display_name', $post->post_author ) );

				// If using the %category% tag, add a link to the first category archive to match permalinks.
				elseif ( taxonomy_exists( trim( $tag, '%' ) ) ) {

					// Force override terms in this post type.
					self::$post_taxonomy[ $post->post_type ] = false;

					// Add the post categories.
					self::add_post_terms( $post_id, trim( $tag, '%' ) );
				}
			}
		}
	}
}
Themify_Builder_Model::register_module( 'TB_Breadcrumbs_Module' );