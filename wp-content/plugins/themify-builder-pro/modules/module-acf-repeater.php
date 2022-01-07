<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Add To Cart
 * Description: 
 */

class TB_ACF_Repeater_Module extends Themify_Builder_Component_Module {
	
	function __construct() {
		parent::__construct(array(
		    'name' => __('ACF Repeater', 'tbp'),
		    'slug' => 'acf-repeater',
		    'category' => array( 'general' )
		));
	}
	
	public function get_assets() {
		return array();
	}
	
	public function get_icon(){
		return 'loop';
	}
	
	public function get_options() {
		return array(
			array(
				'id'      => 'builder_content',
				'type'    => 'tbp_advanched_layout',
				'control' => false
			),
			array(
				'id' => 'key',
				'type' => 'select',
				'label' => __( 'Field Name', 'tbp' ),
				'wrap_class' => 'tb_disable_dc',
				'options' => array_merge( [ '' => '' ], Tbp_Utils::get_acf_fields_by_type( 'repeater' ) ),
			),
			array(
				'type' => 'group',
				'options' => Tbp_Dynamic_Content::get_acf_ctx_fields(),
			),
			array(
				'id' => 'display',
				'type' => 'radio',
				'label' => __('Display as', 'tbp'),
				'options' => array(
				    array('value' => 'grid','name'=>__('Grid', 'tbp')),
				    array('value' => 'slider','name'=>__('Slider', 'tbp'))
				),
				'option_js' => true
			),
			array(
				'type' => 'group',
				'options' => array(
					array(
						'id' => 'slider',
						'type' => 'slider',
						'label' => __('Slider Options', 'tbp'),
						'slider_options' => true,
					),
				),
				'wrap_class' => 'tb_group_element_slider'
			),
			array(
				'id' => 'grid_layout',
				'type' => 'layout',
				'label' => __( 'Layout', 'tbp' ),
				'mode' => 'sprite',
				'control' => array(
				    'classSelector' => '.builder-posts-wrap'
				),
				'options' => array(
					array('img' => 'list_post', 'value' => 'list-post', 'label' => __('Grid1', 'tbp')),
					array('img' => 'grid2', 'value' => 'grid2', 'label' => __('Grid 2', 'tbp')),
					array('img' => 'grid3', 'value' => 'grid3', 'label' => __('Grid 3', 'tbp')),
					array('img' => 'grid4', 'value' => 'grid4', 'label' => __('Grid 4', 'tbp')),
					array('img' => 'grid5', 'value' => 'grid5', 'label' => __('Grid 5', 'tbp')),
					array('img' => 'grid6', 'value' => 'grid6', 'label' => __('Grid 6', 'tbp')),
				),
				'wrap_class' => 'tb_group_element_grid',
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
						self::get_image('', 'b_c_g', 'bg_c', 'background-color', 'h')
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
						self::get_color_type( ['> .tbp_advanchd_archive_wrap'] ),
						self::get_font_size('', 'f_s_g'),
						self::get_line_height('', 'l_h_g'),
						self::get_letter_spacing('', 'l_s_g'),
						self::get_text_align( ['', ' .post'], 't_a_g'),
						self::get_text_transform('', 't_t_g'),
						self::get_font_style('', 'f_g', 'f_b'),
						self::get_text_shadow('', 't_sh'),
					)
					),
					'h' => array(
					'options' => array(
						self::get_font_family('', 'f_f_g', 'h'),
						self::get_color_type( ['> .tbp_advanchd_archive_wrap'], 'h'),
						self::get_font_size('', 'f_s_g', '', 'h'),
						self::get_line_height('', 'l_h_g', 'h'),
						self::get_letter_spacing('', 'l_s_g', 'h'),
						self::get_text_align( ['', ' .post'], 't_a_g', 'h'),
						self::get_text_transform('', 't_t_g', 'h'),
						self::get_font_style('', 'f_g', 'f_b', 'h'),
						self::get_text_shadow('','t_sh','h'),
					)
					)
				))
			)),
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
							'options' => count($a = self::get_blend())>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend('','bl_m_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
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
			// Height & Min Height
			! method_exists( $this, 'get_max_height' ) ? array() :
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

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
			)
		);
	}

	public function get_live_default() {
		$default['builder_content'] = [
		    [
				'cols' => [
					[
						'grid_class' => 'col-full',
						'modules' => [
							[
								'mod_name'=> 'text',
								'mod_settings' => [
									'content_text' => __( 'Double click here to edit the template, then enable Dynamic option.', 'tbp' )
								],
							],
						]
					]
				]
		    ]
		];
		return $default;
	}

	public function get_visual_type() {
		return 'ajax';
	}

	public function get_animation() {
		return false;
	}

	public function get_category() {
	    return array( 'general' );
	}
}
if ( class_exists( 'acf_pro' ) ) {
	Themify_Builder_Model::register_module( 'TB_ACF_Repeater_Module' );
}
