<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Product Stock Status
 * Description: 
 */

class TB_Product_Stock_Status_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Product Stock Status', 'tbp'),
		    'slug' => 'product-stock-status',
			'category' => array( 'product_single' )
		));
    }
    
    public function get_assets() {
		return array();
    }
    
    public function get_icon(){
		return 'ti-package';
    }
    
    public function get_options() {
	    return array(
			array(
				'id' => 'in_stock',
				'type' => 'text',
				'label' => __( 'In Stock Text', 'tbp'  ),
				'help' => __( '%stock_count% is replaced with the number of products in stock', 'tbp' ),
			),
			array(
				'id' => 'out_of_stock',
				'type' => 'text',
				'label' => __( 'Out of Stock Text', 'tbp'  ),
			),
		    array( 'type' => 'tbp_custom_css' )
	    );
	}

	public function get_styling() {
		$general = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_image()
				)
				),
				'h' => array(
				'options' => array(
					self::get_image('', 'b_i','bg_c','b_r','b_p', 'h')
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_font_family(),
					self::get_color_type(),
					self::get_font_size(),
					self::get_line_height(),
					self::get_letter_spacing(),
					self::get_text_align(),
					self::get_text_transform(),
					self::get_font_style(),
					self::get_text_decoration( '', 'text_decoration_regular' ),
					self::get_text_shadow(),
				)
				),
				'h' => array(
				'options' => array(
					self::get_font_family( '', 'f_f_h' ),
					self::get_color_type( '','', 'f_c_t_h',  'f_c_h', 'f_g_c_h' ),
					self::get_font_size( '', 'f_s', '', 'h' ),
					self::get_line_height( '', 'l_h', 'h' ),
					self::get_letter_spacing( '', 'l_s', 'h' ),
					self::get_text_align( '', 't_a', 'h' ),
					self::get_text_transform( '', 't_t', 'h' ),
					self::get_font_style( '', 'f_st', 'f_w', 'h' ),
					self::get_text_decoration( ' .tb_text_wrap', 't_d_r', 'h' ),
					self::get_text_shadow( '','t_sh','h' ),
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
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => self::get_blend()

						),
						'h' => array(
							'options' => self::get_blend('', '', 'h')
						)
					))
				)
			),
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
			// Position
			self::get_expand('po', array( self::get_css_position())),
			// Display
			self::get_expand( 'disp', self::get_display() )
		);

		$in_stock = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color(' .tbp_product_in_stock', 'is_bg', 'bg_c', 'background-color' )
				)
				),
				'h' => array(
				'options' => array(
					self::get_color( ':hover .tbp_product_in_stock', 'is_bg_h', 'bg_c', 'background-color' )
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_font_family(' .tbp_product_in_stock', 'is_f'),
					self::get_color(' .tbp_product_in_stock', 'is_c'),
					self::get_font_size(' .tbp_product_in_stock', 'is_fs'),
					self::get_line_height(' .tbp_product_in_stock', 'is_l'),
					self::get_text_transform(' .tbp_product_in_stock', 'is_t'),
					self::get_font_style(' .tbp_product_in_stock', 'is_s', 'is_s_b'),
					self::get_text_decoration(' .tbp_product_in_stock', 'is_td'),
					self::get_text_shadow(' .tbp_product_in_stock', 'is_ts')
				)
				),
				'h' => array(
				'options' => array(
					self::get_font_family(':hover .tbp_product_in_stock', 'is_f_h'),
					self::get_color(':hover .tbp_product_in_stock', 'is_c_h'),
					self::get_font_size(':hover .tbp_product_in_stock', 'is_fs_h'),
					self::get_line_height(':hover .tbp_product_in_stock', 'is_l_h'),
					self::get_text_transform(':hover .tbp_product_in_stock', 'is_t_h'),
					self::get_font_style(':hover .tbp_product_in_stock', 'is_s_h', 'is_s_b_h'),
					self::get_text_decoration(':hover .tbp_product_in_stock', 'is_td_h'),
					self::get_text_shadow(':hover .tbp_product_in_stock', 'is_ts_h')
				)
				)
			))
			)),
		);

		$out_of_stock = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color(' .tbp_product_out_of_stock', 'os_b', 'bg_c', 'background-color' )
				)
				),
				'h' => array(
				'options' => array(
					self::get_color( ':hover .tbp_product_out_of_stock', 'os_b_h', 'bg_c', 'background-color' )
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_font_family(' .tbp_product_out_of_stock', 'os_f'),
					self::get_color(' .tbp_product_out_of_stock', 'os_c'),
					self::get_font_size(' .tbp_product_out_of_stock', 'os_fs'),
					self::get_line_height(' .tbp_product_out_of_stock', 'os_l'),
					self::get_text_transform(' .tbp_product_out_of_stock', 'os_t'),
					self::get_font_style(' .tbp_product_out_of_stock', 'os_s', 'os_s_b'),
					self::get_text_decoration(' .tbp_product_out_of_stock', 'os_td'),
					self::get_text_shadow(' .tbp_product_out_of_stock', 'os_sh')
				)
				),
				'h' => array(
				'options' => array(
					self::get_font_family( ':hover .tbp_product_out_of_stock', 'os_f_h' ),
					self::get_color( ':hover .tbp_product_out_of_stock', 'os_c_h' ),
					self::get_font_size( ':hover .tbp_product_out_of_stock', 'os_fs_h' ),
					self::get_line_height( ':hover .tbp_product_out_of_stock', 'os_l_h' ),
					self::get_text_transform( ':hover .tbp_product_out_of_stock', 'os_t_h' ),
					self::get_font_style( ':hover .tbp_product_out_of_stock', 'os_s_h', 'os_s_b_h' ),
					self::get_text_decoration( ':hover .tbp_product_out_of_stock', 'os_td_h' ),
					self::get_text_shadow( ':hover .tbp_product_out_of_stock', 'os_sh_h' )
				)
				)
			))
			)),
		);

		return array(
			'type' => 'tabs',
			'options' => array(
				'g' => array(
					'options' => $general
				),
				'in_stock' => array(
					'label' => __( 'In Stock', 'tbp' ),
					'options' => $in_stock,
				),
				'out_of_stock' => array(
					'label' => __( 'Out of Stock', 'tbp' ),
					'options' => $out_of_stock,
				),
			)
		);
	}

	public function get_live_default() {
		return array(
			'in_stock' => __( '%stock_count% available in stock', 'tbp' ),
			'out_of_stock' => __( 'Out of stock', 'tbp' ),
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'product' );
	}
}

if ( themify_is_woocommerce_active() ) {
	Themify_Builder_Model::register_module( 'TB_Product_Stock_Status_Module' );
}