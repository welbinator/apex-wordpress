<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Related Posts
 * Description: Display related posts by category/tag in single post templates
 */

class TB_Related_Posts_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Related Posts', 'tbp'),
		    'slug' => 'related-posts',
			'category' => array('single')
		));
		add_filter( 'builder_get_public_post_types', array( __CLASS__, 'get_current_post_type' ) );
    }
    
    public function get_icon(){
	return 'layout-grid2';
    }

    public function get_options() {
		$post_meta = Tbp_Utils::get_module_settings('post-meta','options');
		foreach($post_meta as $k=>$v){
			unset($post_meta[$k]['label']);
			if($v['type']==='tbp_custom_css'){
				unset($post_meta[$k]);
				break;
			}
		}
		$post_title = Tbp_Utils::get_module_settings('post-title','options');
		foreach($post_title as $k=>$v){
			if($v['type']==='tbp_custom_css'){
				unset($post_title[$k]);
				break;
			}
		}
		$post_image = Tbp_Utils::get_module_settings('featured-image','options');
		foreach($post_image as $k=>$v){
			if($v['type']==='tbp_custom_css'){
				unset($post_image[$k]);
				break;
			}
		}
		$post_content = Tbp_Utils::get_module_settings('post-content','options');
		foreach($post_content as $k=>$v){
			if($v['type']==='tbp_custom_css'){
				unset($post_content[$k]);
				break;
			}
		}
    	return array(
			array(
				'id' => 'heading',
				'type' => 'text',
				'label' => __('Heading', 'tbp'),
				'control'=>array(
				    'selector'=>'[data-name="heading"]'
				)
			),
			array(
				'type' => 'query_posts',
				'id' => 'term_type_select',
				'tax_id' => 'term_type',
			),
			array(
				'id' => 'layout_post',
				'type' => 'layout',
				'label' => __('Layout', 'tbp'),
				'mode' => 'sprite',
				'control'=>array(
					'classSelector'=>'.tbp_posts_wrap'
				),
				'options' => array(
					array('img' => 'list_post', 'value' => 'list_post', 'label' => __('List Post', 'tbp')),
					array('img' => 'grid2', 'value' => 'grid2', 'label' => __('Grid 2', 'tbp')),
					array('img' => 'grid3', 'value' => 'grid3', 'label' => __('Grid 3', 'tbp')),
					array('img' => 'grid4', 'value' => 'grid4', 'label' => __('Grid 4', 'tbp')),
					array('img' => 'grid5', 'value' => 'grid5', 'label' => __('Grid 5', 'tbp')),
					array('img' => 'grid6', 'value' => 'grid6', 'label' => __('Grid 6', 'tbp'))
				)
			),
			array(
				'id' => 'per_page',
				'type' => 'number',
				'label' => __('Number of entries', 'tbp'),
			),
			array(
				'id' => 'tab_content_archive_posts',
				'type' => 'toggleable_fields',
				'options' => array(
					'image' => array(
						'label'   => __( 'Featured Image', 'tbp'),
						'options' => $post_image
					),
					't' => array(
						'label'   => __('Title', 'tbp'),
						'options' => $post_title
					),
					'p_date' => array(
						'label'   => __( 'Post Date', 'tbp'),
						'options' => array(
							array(
								'id' => 'format',
								'type' => 'select',
								'label' => __('Date Format', 'tbp'),
								'default' => 'def',
								'options' => array(
									'F j, Y' => __( 'August 18, 2019 (F j, Y)', 'tbp'),
									'Y-m-d'  => __( '2019-08-18 (Y-m-d)', 'tbp'),
									'm/d/Y'  => __( '08/18/2019 (m/d/Y)', 'tbp'),
									'd/m/Y'  => __('18/08/2019 (d/m/Y)', 'tbp'),
									'def'    => __('Default', 'tbp'),
									'custom' => __('Custom Format', 'tbp')
								),
								'binding' => array(
									'custom' => array( 'show' => 'custom'),
									'F j, Y' => array( 'hide' => 'custom' ),
									'Y-m-d' => array( 'hide' => 'custom' ),
									'm/d/Y' => array( 'hide' => 'custom' ),
									'd/m/Y' => array( 'hide' => 'custom' ),
									'def' => array( 'hide' =>'custom')
								)
							),
							array(
								'id' => 'custom',
								'type' => 'text',
								'label' => __( 'Custom Format', 'tbp'),
								'control'=>array(
									'event'=>'change'
								),
								'help' => __( 'Enter date format in these letters: l D d j S F m M n Y y', 'tbp')
							),
							array(
								'id' => 'icon',
								'type' => 'icon',
								'label' => __('Icon', 'tbp')
							),
							array(
								'id' => 'before',
								'type' => 'text',
								'label' => __('Before Text', 'tbp')
							),
							array(
								'id' => 'after',
								'type' => 'text',
								'label' => __('After Text', 'tbp')
							)
						)
					),
					'p_meta' => array(
						'label'   => __( 'Post Meta', 'tbp'),
						'options' => $post_meta
					),
					'cont' => array(
						'label'   => __( 'Content', 'tbp'),
						'options' => $post_content
					),
					'more_l' => array(
						'label'   => __( 'More Link', 'tbp'),
						'options' => array(
							array(
								'id' => 'link_type',
								'type' => 'radio',
								'label' => __('Link', 'tbp'),
								'options' => array(
									array( 'name' => __( 'Permalink', 'tbp'), 'value' => 'permalink' ),
									array( 'name' => __( 'None', 'tbp'), 'value' => 'none' )
								)
							),
							array(
								'id' => 'link_text',
								'type' => 'text',
								'label' => __('More Text', 'tbp')
							)
						)
					)
				)
			),
			array('type' => 'tbp_custom_css')
		);
	}

	public function get_styling() {
		$general = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_image('', 'b_c_g', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_image(':hover', 'b_c_g_h', 'bg_c_h', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('', 'f_f_g'),
							self::get_color_type(array(' span', ' p', ' .tbp_post_date', '.module .tbp_title', '.module .tbp_title a')),
							self::get_font_size('', 'f_s_g'),
							self::get_line_height('', 'l_h_g'),
							self::get_letter_spacing(' .post', 'l_s_g'),
							self::get_text_align(' .post', 't_a_g'),
							self::get_text_transform('', 't_t_g'),
							self::get_font_style('', 'f_g', 'f_b'),
							self::get_text_shadow('', 't_sh'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('', 'f_f_g', 'h'),
							self::get_color_type(array(' span', ' p', ' .tbp_post_date', '.module .tbp_title', '.module .tbp_title a'), 'h'),
							self::get_font_size('', 'f_s_g', '', 'h'),
							self::get_line_height('', 'l_h_g', 'h'),
							self::get_letter_spacing(' .post', 'l_s_g', 'h'),
							self::get_text_align(' .post', 't_a_g', 'h'),
							self::get_text_transform('', 't_t_g', 'h'),
							self::get_font_style('', 'f_g', 'f_b', 'h'),
							self::get_text_shadow('','t_sh','h'),
						)
					)
				))
			)),
			// Link
			self::get_expand( 'l', array(
				self::get_tab( array(
					'n' => array(
						'options' => array(
							self::get_color( ' a:not(.post-edit-link)', 'l_c_gl' ),
							self::get_text_decoration( 'a:not(.post-edit-link)', 't_d_gl' )
						)
					),
					'h' => array(
						'options' => array(
							self::get_color( ' .post a:not(.post-edit-link):hover', 'l_c_gl_h', null, null, '' ),
							self::get_text_decoration( ' .post a:not(.post-edit-link):hover', 't_d_gl_h', '' )
						)
					)
				) )
			) ),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('', 'g_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('', 'g_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('', 'g_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('', 'g_m', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('', 'g_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('', 'g_b', 'h')
						)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(' .loops-wrapper .post'))>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' .loops-wrapper .post','bl_m_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
						)
					))
				)
			),
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
			! method_exists( $this, 'get_max_height' ) ? array() :
				// Height & Min Height
				self::get_expand('ht', array(
						self::get_height(),
						self::get_min_height(),
						self::get_max_height()
					)
				),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('', 'r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('', 'r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('', 'g_sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('', 'g_sh', 'h')
						)
					)
				))
			)),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$archive_post_container = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post', 'b_c_a_p_cn', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post', 'b_c_a_p_cn', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post', 'p_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post', 'p_cn', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .post', '', 'top', 'article'),
							self::get_heading_margin_multi_field(' .post', '', 'bottom', 'article')
						)
					),
					'h' => array(
						'options' => array(
							self::get_heading_margin_multi_field(' .post', '', 'top', 'article', 'h'),
							self::get_heading_margin_multi_field(' .post', '', 'bottom', 'article', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .post', 'b_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post', 'b_cn', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post', 'r_c_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post', 'r_c_cn', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'sh_cn')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post', 'sh_cn', 'h')
						)
					)
				))
			)),
		);

		$archive_post_title = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .tbp_title', 'b_c_a_p_t', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .tbp_title', 'b_c_a_p_t', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family('.module .tbp_title', 'f_f_a_p_t'),
							self::get_color(array('.module .tbp_title', ' .tbp_title a'), 'f_c_a_p_t'),
							self::get_font_size('.module .tbp_title', 'f_s_a_p_t'),
							self::get_line_height('.module .tbp_title', 'l_h_a_p_t'),
							self::get_letter_spacing('.module .tbp_title', 'l_s_a_p_t'),
							self::get_text_transform('.module .tbp_title', 't_t_a_p_t'),
							self::get_font_style('.module .tbp_title', 'f_sy_a_p_t', 'f_w_a_p_t'),
							self::get_text_decoration('.module .tbp_title', 't_d_a_p_t'),
							self::get_text_shadow('.module .tbp_title', 't_sh_a_p_t'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family('.module .tbp_title', 'f_f_a_p_t', 'h'),
							self::get_color(array('.module .tbp_title', ' .tbp_title a'), 'f_c_a_p_t', null, null, 'hover'),
							self::get_font_size('.module .tbp_title', 'f_s_a_p_t', '', 'h'),
							self::get_line_height('.module .tbp_title', 'l_h_a_p_t', 'h'),
							self::get_letter_spacing('.module .tbp_title', 'l_s_a_p_t', 'h'),
							self::get_text_transform('.module .tbp_title', 't_t_a_p_t', 'h'),
							self::get_font_style('.module .tbp_title', 'f_sy_a_p_t', 'f_w_a_p_t', 'h'),
							self::get_text_decoration('.module .tbp_title', 't_d_a_p_t', 'h'),
							self::get_text_shadow('.module .tbp_title', 't_sh_a_p_t','h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color('.module .tbp_title a', 'l_c'),
							self::get_text_decoration('.module .tbp_title a', 't_d_l')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color('.module .tbp_title a', 'l_c',null, null, 'h'),
							self::get_text_decoration('.module .tbp_title a', 't_d_l', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding('.module .tbp_title', 'p_a_p_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding('.module .tbp_title', 'p_a_p_t', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin('.module .tbp_title', 'm_a_p_t'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin('.module .tbp_title', 'm_a_p_t', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border('.module .tbp_title', 'b_a_p_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border('.module .tbp_title', 'b_a_p_t', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .tbp_title', 'sh_a_p_t')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .tbp_title', 'sh_a_p_t', 'h')
						)
					)
				))
			)),
		);

		$archive_featured_image = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .post-image img', 'b_c_a_f_i', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .post-image img', 'b_c_a_f_i', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .post-image img', 'p_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .post-image img', 'p_a_f_i', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .post-image', 'm_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .post-image', 'm_a_f_i', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .post-image img', 'b_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .post-image img', 'b_a_f_i', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .post-image img', 'r_c_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .post-image img', 'r_c_a_f_i', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .post-image img', 'sh_a_f_i')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .post-image img', 'sh_a_f_i', 'h')
						)
					)
				))
			))
		);

		$archive_post_meta = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_post_meta', 'b_c_a_p_m', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_post_meta', 'b_c_a_p_m', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_meta', 'f_f_a_p_m'),
							self::get_color(array(' .tbp_post_meta', ' .tbp_post_meta span', ' .tbp_post_meta a'), 'f_c_a_p_m'),
							self::get_font_size(' .tbp_post_meta', 'f_s_a_p_m'),
							self::get_line_height(' .tbp_post_meta', 'l_h_a_p_m'),
							self::get_letter_spacing(' .tbp_post_meta', 'l_s_a_p_m'),
							self::get_font_style(' .tbp_post_meta', 'f_g_a_p_m', 'f_b_a_p_m'),
							self::get_text_transform(' .tbp_post_meta', 't_t_a_p_m'),
							self::get_text_decoration(' .tbp_post_meta', 't_d_a_p_m'),
							self::get_text_shadow(' .tbp_post_meta', 't_sh_a_p_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_meta', 'f_f_a_p_m', 'h'),
							self::get_color(array(' .tbp_post_meta', ' .tbp_post_meta span', ' .tbp_post_meta a'), 'f_c_a_p_m',null,null,'hover'),
							self::get_font_size(' .tbp_post_meta', 'f_s_a_p_m', '', 'h'),
							self::get_line_height(' .tbp_post_meta', 'l_h_a_p_m', 'h'),
							self::get_letter_spacing(' .tbp_post_meta', 'l_s_a_p_m', 'h'),
							self::get_font_style(' .tbp_post_meta', 'f_g_a_p_m', 'f_b_a_p_m', 'h'),
							self::get_text_transform(' .tbp_post_meta', 't_t_a_p_m', 'h'),
							self::get_text_decoration(' .tbp_post_meta', 't_d_a_p_m', 'h'),
							self::get_text_shadow(' .tbp_post_meta', 't_sh_a_p_m','h'),
						)
					)
				))
			)),
			// Link
			self::get_expand('l', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_post_meta a', 'f_c_a_p_m_l'),
							self::get_text_decoration(' .tbp_post_meta a', 't_d_a_p_m_l'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_post_meta a', 'f_c_a_p_m_l',null,null,'hover'),
							self::get_text_decoration(' .tbp_post_meta a', 't_d_a_p_m_l', 'h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_post_meta', 'p_a_p_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_post_meta', 'p_a_p_m', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_post_meta', 'm_a_p_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_post_meta', 'm_a_p_m', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_post_meta', 'b_a_p_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_post_meta', 'b_a_p_m', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_meta', 'sh_a_p_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_meta', 'sh_a_p_m', 'h')
						)
					)
				))
			))
		);

		$archive_post_date = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tbp_post_date', 'b_c_a_p_d', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tbp_post_date', 'b_c_a_p_d', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date', 'f_f_a_p_d'),
							self::get_color(' .tbp_post_date', 'f_c_a_p_d'),
							self::get_font_size(' .tbp_post_date', 'f_s_a_p_d'),
							self::get_line_height(' .tbp_post_date', 'l_h_a_p_d'),
							self::get_letter_spacing(' .tbp_post_date', 'l_s_a_p_d'),
							self::get_text_align(' .tbp_post_date', 't_a_a_p_d'),
							self::get_text_transform(' .tbp_post_date', 't_t_a_p_d'),
							self::get_font_style(' .tbp_post_date', 'f_st_a_p_d', 'f_w_a_p_d'),
							self::get_text_decoration(' .tbp_post_date', 't_d_r_a_p_d'),
							self::get_text_shadow(' .tbp_post_date', 't_sh_a_p_d'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date', 'f_f_a_p_d', 'h'),
							self::get_color(' .tbp_post_date', 'f_c_a_p_d',null,null,'h'),
							self::get_font_size(' .tbp_post_date', 'f_s_a_p_d', '', 'h'),
							self::get_line_height(' .tbp_post_date', 'l_h_a_p_d', 'h'),
							self::get_letter_spacing(' .tbp_post_date', 'l_s_a_p_d', 'h'),
							self::get_text_align(' .tbp_post_date', 't_a_a_p_d', 'h'),
							self::get_text_transform(' .tbp_post_date', 't_t_a_p_d', 'h'),
							self::get_font_style(' .tbp_post_date', 'f_st_a_p_d', 'f_w_a_p_d', 'h'),
							self::get_text_decoration(' .tbp_post_date', 't_d_r_a_p_d', 'h'),
							self::get_text_shadow(' .tbp_post_date', 't_sh_a_p_d','h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tbp_post_date', 'p_a_p_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tbp_post_date', 'p_a_p_d', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tbp_post_date', 'm_a_p_d'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tbp_post_date', 'm_a_p_d', 'h'),
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tbp_post_date', 'b_a_p_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tbp_post_date', 'b_a_p_d', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_date', 'sh_a_p_d')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .tbp_post_date', 'sh_a_p_d', 'h')
						)
					)
				))
			)),

			// Month Font
			self::get_expand('Month Font', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_month', 'f_f_a_p_d_m'),
							self::get_color(' .tbp_post_date .tbp_post_month', 'f_c_a_p_d_m'),
							self::get_font_size(' .tbp_post_date .tbp_post_month', 'f_s_a_p_d_m'),
							self::get_line_height(' .tbp_post_date .tbp_post_month', 'l_h_a_p_d_m'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_month', 't_sh_a_p_d_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_month', 'f_f_a_p_d_m', 'h'),
							self::get_color(' .tbp_post_date .tbp_post_month', 'f_c_a_p_d_m',null,null,'h'),
							self::get_font_size(' .tbp_post_date .tbp_post_month', 'f_s_a_p_d_m', '', 'h'),
							self::get_line_height(' .tbp_post_date .tbp_post_month', 'l_h_a_p_d_m', 'h'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_month', 't_sh_a_p_d_m','h'),
						)
					)
				))
			)),

			// Day Font
			self::get_expand('Day Font', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_day', 'f_f_a_p_d_d'),
							self::get_color(' .tbp_post_date .tbp_post_day', 'f_c_a_p_d_d'),
							self::get_font_size(' .tbp_post_date .tbp_post_day', 'f_s_a_p_d_d'),
							self::get_line_height(' .tbp_post_date .tbp_post_day', 'l_h_a_p_d_d'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_day', 't_sh_a_p_d_d'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_day', 'f_f_a_p_d_d', 'h'),
							self::get_color(' .tbp_post_date .tbp_post_day', 'f_c_a_p_d_d',null,null,'h'),
							self::get_font_size(' .tbp_post_date .tbp_post_day', 'f_s_a_p_d_d', '', 'h'),
							self::get_line_height(' .tbp_post_date .tbp_post_day', 'l_h_a_p_d_d', 'h'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_day', 't_sh_a_p_d_d','h'),
						)
					)
				))
			)),
			// Year Font
			self::get_expand('Year Font', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_year', 'f_f_a_p_d_y'),
							self::get_color(' .tbp_post_date .tbp_post_year', 'f_c_a_p_d_y'),
							self::get_font_size(' .tbp_post_date .tbp_post_year', 'f_s_a_p_d_y'),
							self::get_line_height(' .tbp_post_date .tbp_post_year', 'l_h_a_p_d_y'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_year', 't_sh_a_p_d_y'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tbp_post_date .tbp_post_year', 'f_f_a_p_d_y', 'h'),
							self::get_color(' .tbp_post_date .tbp_post_year', 'f_c_a_p_d_y',null,null,'h'),
							self::get_font_size(' .tbp_post_date .tbp_post_year', 'f_s_a_p_d_y', '', 'h'),
							self::get_line_height(' .tbp_post_date .tbp_post_year', 'l_h_a_p_d_y', 'h'),
							self::get_text_shadow(' .tbp_post_date .tbp_post_year', 't_sh_a_p_d_y','h'),
						)
					)
				))
			)),

		);

		$archive_post_content = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .tb_text_wrap', 'b_c_a_p_c', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .tb_text_wrap', 'b_c_a_p_c', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .tb_text_wrap', 'f_f_a_p_c'),
							self::get_color(' .tb_text_wrap', 'f_c_a_p_c'),
							self::get_font_size(' .tb_text_wrap', 'f_s_a_p_c'),
							self::get_line_height(' .tb_text_wrap', 'l_h_a_p_c'),
							self::get_letter_spacing(' .tb_text_wrap', 'l_s_a_p_c'),
							self::get_font_style(' .tb_text_wrap', 'f_g_a_p_c', 'f_b_a_p_c'),
							self::get_text_transform(' .tb_text_wrap', 't_t_a_p_c'),
							self::get_text_align(' .tb_text_wrap', 't_a_a_p_c'),
							self::get_text_shadow(' .tb_text_wrap', 't_sh_a_p_c'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .tb_text_wrap', 'f_f_a_p_c','h'),
							self::get_color(' .tb_text_wrap', 'f_c_a_p_c', null,null, 'h'),
							self::get_font_size(' .tb_text_wrap', 'f_s_a_p_c', '', 'h'),
							self::get_line_height(' .tb_text_wrap', 'l_h_a_p_c', 'h'),
							self::get_letter_spacing(' .tb_text_wrap', 'l_s_a_p_c', 'h'),
							self::get_font_style(' .tb_text_wrap', 'f_g_a_p_c', 'f_b_a_p_c', 'h'),
							self::get_text_transform(' .tb_text_wrap', 't_t_a_p_c', 'h'),
							self::get_text_align(' .tb_text_wrap', 't_a_a_p_c', 'h'),
							self::get_text_shadow(' .tb_text_wrap', 't_sh_a_p_c','h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .tb_text_wrap', 'p_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .tb_text_wrap', 'p_a_p_c', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .tb_text_wrap', 'm_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .tb_text_wrap', 'm_a_p_c', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .tb_text_wrap', 'b_a_p_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .tb_text_wrap', 'b_a_p_c', 'h')
						)
					)
				))
			))
		);

		$read_more = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(' .read-more', 'b_c_r_m', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(' .read-more', 'b_c_r_m', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_font_family(' .read-more', 'f_f_g'),
							self::get_color('.module .read-more', 'f_c_r_m'),
							self::get_font_size(' .read-more', 'f_s_r_m'),
							self::get_line_height(' .read-more', 'l_h_r_m'),
							self::get_letter_spacing(' .read-more', 'l_s_r_m'),
							self::get_text_align(' .read-more', 't_a_r_m'),
							self::get_text_transform(' .read-more', 't_t_r_m'),
							self::get_font_style(' .read-more', 'f_st_r_m', 'f_b_r_m'),
							self::get_text_shadow(' .read-more', 't_sh_r_m'),
						)
					),
					'h' => array(
						'options' => array(
							self::get_font_family(' .read-more', 'f_f_g', 'h'),
							self::get_color('.module .read-more:hover', 'f_c_r_m_h','h'),
							self::get_font_size(' .read-more', 'f_s_r_m', '', 'h'),
							self::get_line_height(' .read-more', 'l_h_r_m', 'h'),
							self::get_letter_spacing(' .read-more', 'l_s_r_m', 'h'),
							self::get_text_align(' .read-more', 't_a_r_m', 'h'),
							self::get_text_transform(' .read-more', 't_t_r_m', 'h'),
							self::get_font_style(' .read-more', 'f_st_r_m', 'f_b_r_m', 'h'),
							self::get_text_shadow(' .read-more','t_sh_r_m','h'),
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(' .read-more', 'r_m_p')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(' .read-more', 'r_m_p', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(' .read-more', 'r_m_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(' .read-more', 'r_m_m', 'h')
						)
					)
				)),
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(' .read-more', 'r_m_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(' .read-more', 'r_m_b', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' .read-more', 'r_c_r_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' .read-more', 'r_c_r_m', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(' .read-more', 'sh_r_m')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(' .read-more', 'sh_r_m', 'h')
						)
					)
				))
			))
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'co' => array(
					'label' => __('Container', 'tbp'),
					'options' => $archive_post_container
				),
				't' => array(
					'label' => __('Title', 'tbp'),
					'options' => $archive_post_title
				),
				'f' => array(
					'label' => __('Featured Image', 'tbp'),
					'options' => $archive_featured_image
				),
				'm' => array(
					'label' => __('Meta', 'tbp'),
					'options' => $archive_post_meta
				),
				'd' => array(
					'label' => __('Date', 'tbp'),
					'options' => $archive_post_date
				),
				'c' => array(
					'label' => __('Content', 'tbp'),
					'options' => $archive_post_content
				),
				'r' => array(
					'label' => __('Read More', 'tbp'),
					'options' => $read_more
				),
			)
		);
	}

	public function get_live_default() {
		$args = array(
			'layout_post' => 'grid3',
			'order' => 'desc',
			'orderby'=>'ID',
			'pagination' => 'yes',
			'next_link'=>__('Newer Entries', 'tbp'),
			'prev_link'=>__('Older Entries', 'tbp'),
			'no_found'=>__('No Posts Found','tbp'),
			'tab_content_archive_posts' => array(
				'image' => array(
					'on' => '1',
					'val' => array()
				),
				't' => array(
					'on' => '1',
					'val' => array()
				),
				'p_date' => array(
					'on' => '1',
					'val' => array(
						'format' => 'def'
					)
				),
				'p_meta' => array(
					'on' => '1',
					'val' => array()
				),
				'cont' => array(
					'on' => '1',
					'val' => array(
						'content_type' => 'excerpt'
					)
				),
				'more_l' => array(
					'on' => '0'
				)
			)
		);
		$defaults = array('image'=>'featured-image','t'=>'post-title','p_meta'=>'post-meta');
		foreach($defaults as $k=>$v){
			$args['tab_content_archive_posts'][$k]['val'] = Tbp_Utils::get_module_settings($v);
		}
		return $args;
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'single' );
	}

	public static function get_current_post_type($types){
    	if(!empty($_POST['action']) && 'tb_get_post_types' === $_POST['action'] && !empty($_POST['just_current']) && 'true' === $_POST['just_current']){
    		$id = $_POST['id'];
			$post_type=get_post_type($id);
			if($post_type===Tbp_Templates::$post_type) {
				$condition = Tbp_Utils::get_template_conditions( $id );
				$post_type = Tbp_Utils::get_post_type( get_post_meta( $id, 'tbp_template_type', true ), $condition[0] );
			}else {
				$post_type = array($post_type);
			}
			if(!empty($post_type) && is_array($post_type)){
				$post_type_obj = get_post_type_object( $post_type[0] );
				$post_type = array($post_type[0]=>$post_type_obj->labels->singular_name);
			}
			return empty($post_type)?array('post'=>'Post'):$post_type;
		}
    	return $types;
	}

}

Themify_Builder_Model::register_module('TB_Related_Posts_Module');
