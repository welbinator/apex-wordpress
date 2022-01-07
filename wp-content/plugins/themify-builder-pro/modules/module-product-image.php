<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Product Image
 * Description: 
 */

class TB_Product_Image_Module extends Themify_Builder_Component_Module {

    function __construct() {
		parent::__construct(array(
		    'name' => __('Product Image', 'tbp'),
		    'slug' => 'product-image',
			'category' => array('product_single')
		));
    }
    
    public function get_assets() {
	return array(
	    'ver'=>Tbp::get_version(),
	    'css'=>TBP_WC_CSS_MODULES.$this->slug.'.css'
	);
    }
    
    public function get_icon(){
	return 'image';
    }
    
    public function get_options() {
	    return array(
	    	array(
			    'id' => 'image_w',
			    'type' => 'number',
			    'control'=>array(
				'event'=>'change'
			    ),
			    'label' => __('Image Width', 'tbp')
		    ),
		    array(
			    'id' => 'auto_fullwidth',
			    'type' => 'checkbox',
			    'label' => '',
			    'options' => array(array('name' => '1', 'value' => __('Auto fullwidth image', 'tbp'))),
			    'wrap_class' => 'auto_fullwidth'
		    ),
		    array(
			    'id' => 'image_h',
			    'type' => 'number',
			    'control'=>array(
				'event'=>'change'
			    ),
			    'label' => __('Image Height', 'tbp')
		    ),
			array(
				'id' => 'thumb_image',
				'type' => 'layout',
				'label' => __('Thumbnail Layout', 'tbp'),
				'mode' => 'sprite',
				'options' => array(
					array('img' => 'thumb_img_bottom', 'value' => 'thumb-img-bottom', 'label' => __('Default', 'tbp')),
					array('img' => 'thumb_img_left', 'value' => 'thumb-img-left', 'label' => __('Thumb Image Left', 'tbp')),
				)
			),
			array(
				'id' => 'thumb_image_w',
				'type' => 'number',
				'control'=>array(
					'event'=>'change'
				),
				'label' => __('Thumbnail', 'tbp'),
				'after' => __('Width', 'tbp')
			),
			array(
				'id' => 'thumb_image_h',
				'type' => 'number',
				'control'=>array(
					'event'=>'change'
				),
				'label' => '',
				'after' => __('Height', 'tbp')
			),
		    array(
			    'id' => 'appearance_image',
			    'type' => 'checkbox',
			    'label' => __('Appearance', 'tbp'),
			    'img_appearance'=>true
		    ),
		    array(
			    'id'      => 'sale_b',
			    'type'    => 'toggle_switch',
			    'label' => __( 'Sale Badge', 'tbp'),
			    'options'   => array(
				    'on'  => array( 'name' => 'yes', 'value' => 's' ),
				    'off' => array( 'name' => 'no', 'value' => 'hi' ),
			    ),
			    'binding' => array(
				    'checked' => array(
					    'show' => 'badge_pos'
				    ),
				    'not_checked' => array(
					    'hide' => 'badge_pos'
				    )
			    )
		    ),
		    array(
			    'label' => '',
			    'after' => __( 'Badge Position', 'tbp'),
			    'id' => 'badge_pos',
			    'type' => 'select',
			    'options' => array(
				    'left' => __( 'Left', 'tbp'),
				    'right'  => __( 'Right', 'tbp')
			    )
		    ),
		    array(
			    'type'    => 'fallback'
		    ),
			array(
				'id'      => 'zoom',
				'type'    => 'toggle_switch',
				'label' => __( 'Image Zoom', 'tbp'),
				'default' => 'on',
				'options'   => array(
					'on'  => array( 'name' => 'yes', 'value' => 'en' ),
					'off' => array( 'name' => 'no', 'value' => 'dis' ),
				)
			),
		    array(
			    'id'      => 'hover_image',
			    'type'    => 'toggle_switch',
			    'label' => __( 'Product Image Hover', 'tbp'),
			    'options'   => array(
				    'on'  => array( 'name' => 'yes', 'value' => 'en' ),
				    'off' => array( 'name' => 'no', 'value' => 'dis' ),
			    ),
			    'wrap_class'=>'tbp_except_single_product_template'
		    ),
		    array(
			    'type'=>'advacned_link',
			    'wrap_class' => 'tbp_except_single_product_template'
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
						self::get_image('.module img', 'b_i','bg_c','b_r','b_p')
					)
					),
					'h' => array(
					'options' => array(
						self::get_image('.module img', 'b_i','bg_c','b_r','b_p', 'h')
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_padding('.module img', 'p')
					)
					),
					'h' => array(
					'options' => array(
						self::get_padding('.module img', 'p', 'h')
					)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_margin('', 'm')
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
						self::get_border(' img', 'b')
					)
					),
					'h' => array(
					'options' => array(
						self::get_border(' img', 'b', 'h')
					)
					)
				))
			)),
			// Filter
			self::get_expand('f_l',
				array(
					self::get_tab(array(
						'n' => array(
							'options' => count($a = self::get_blend(' img','fl'))>2 ? array($a) : $a
						),
						'h' => array(
							'options' => count($a = self::get_blend(' img','fl_h','h'))>2 ? array($a + array('ishover'=>true)) : $a
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
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(' img', 'r_c', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array('.module img', '.module .woocommerce-product-gallery__wrapper'), 'sh')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array('.module img', '.module .woocommerce-product-gallery__wrapper'), 'sh', 'h')
						)
					)
				))
			)),
			// Position
			self::get_expand('po', array( self::get_css_position())),
			// Display
			self::get_expand('disp', self::get_display())
		);

		$sale_badge = array(
			// Background
			self::get_expand('bg', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_color('.module .onsale', 'b_c_s_b', 'bg_c', 'background-color')
				)
				),
				'h' => array(
				'options' => array(
					self::get_color('.module .onsale', 'b_c_s_b', 'bg_c', 'background-color', 'h')
				)
				)
			))
			)),
			// Font
			self::get_expand('f', array(
				self::get_tab(array(
					'n' => array(
					'options' => array(
						self::get_color('.module .onsale', 'f_c_s_b'),
						self::get_font_size('.module .onsale', 'f_s_s_b', ''),
					)
					),
					'h' => array(
					'options' => array(
						self::get_color('.module .onsale', 'f_c_s_b', 'h'),
						self::get_font_size('.module .onsale', 'f_s_s_b', '', 'h'),
					)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_padding('.module .onsale', 'p_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_padding('.module .onsale', 'p_s_b', 'h')
				)
				)
			))
			)),
			// Margin
			self::get_expand('m', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_margin('.module .onsale', 'm_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_margin('.module .onsale', 'm_s_b', 'h')
				)
				)
			))
			)),
			// Border
			self::get_expand('b', array(
			self::get_tab(array(
				'n' => array(
				'options' => array(
					self::get_border('.module .onsale', 'b_s_b')
				)
				),
				'h' => array(
				'options' => array(
					self::get_border('.module .onsale', 'b_s_b', 'h')
				)
				)
			))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius('.module .onsale', 'r_c_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius('.module .onsale', 'r_c_s_b', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow('.module .onsale', 'sh_s_b')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow('.module .onsale', 'sh_s_b', 'h')
						)
					)
				))
			))

		);
		

		$thumbnails = array(
			// Background
			self::get_expand('bg', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_color(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'b_c_tb', 'bg_c', 'background-color')
						)
					),
					'h' => array(
						'options' => array(
							self::get_color(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'b_c_tb', 'bg_c', 'background-color', 'h')
						)
					)
				))
			)),
			// Padding
			self::get_expand('p', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_padding(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'p_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_padding(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'p_tb', 'h')
						)
					)
				))
			)),
			// Margin
			self::get_expand('m', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_margin(array('.module div.product div.images .flex-control-thumbs li',' .product-thumbnails-carousel .tf_swiper-slide'), 'm_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_margin(array('.module div.product div.images .flex-control-thumbs li',' .product-thumbnails-carousel .tf_swiper-slide'), 'm_tb', 'h')
						)
					)
				))
			)),
			// Border
			self::get_expand('b', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'b_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'b_tb', 'h')
						)
					)
				))
			)),
			// Width
			self::get_expand('w', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_width(array('.module div.product div.images .flex-control-thumbs li',' .product-thumbnails-carousel .tf_swiper-slide'), 'w_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_width(array('.module div.product div.images .flex-control-thumbs li',' .product-thumbnails-carousel .tf_swiper-slide'), 'w_tb', 'h')
						)
					)
				))
			)),
			// Rounded Corners
			self::get_expand('r_c', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_border_radius(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'r_c_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_border_radius(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'r_c_tb', 'h')
						)
					)
				))
			)),
			// Shadow
			self::get_expand('sh', array(
				self::get_tab(array(
					'n' => array(
						'options' => array(
							self::get_box_shadow(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'sh_tb')
						)
					),
					'h' => array(
						'options' => array(
							self::get_box_shadow(array(' .flex-control-thumbs img',' .product-thumbnails-carousel img'), 'sh_tb', 'h')
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
				'tb' => array(
					'label' => __('Thumbnails', 'tbp'),
					'options' => $thumbnails
				),
				's' => array(
					'label' => __('Sale Badge', 'tbp'),
					'options' => $sale_badge
				),
			)
		);
	}

	public function get_live_default() {
		return array(
			'lightbox_w_unit' => '%',
			'lightbox_h_unit' => '%',
			'sale_b' => 'yes',
			'zoom' => 'yes'
		);
	}

	public function get_visual_type() {
		return 'ajax';
    }

    public function get_category() {
		return array( 'product' );
	}

	public static function get_product_image_thumbnail_html($args,$attachment_id,$thumbnail=false){
		$width_attr = true === $thumbnail ? 'thumb_image_w' : 'image_w';
		$height_attr = true === $thumbnail ? 'thumb_image_h' : 'image_h';
		$html = wc_get_gallery_image_html( $attachment_id, true );
		if ( $args[$width_attr] !== '' || $args[$height_attr] !== '' ) {
			if(!Themify_Builder_Model::is_img_php_disabled()){
				$src=wp_get_attachment_image_src( $attachment_id, true === $thumbnail ? array($args[$width_attr],$args[$height_attr]):'full' );
				if(!empty($src[0])){
					preg_match( '/src="([^"]+)"/', $html, $image_src );
					if(!empty($image_src[1])){
						$url = themify_get_image(array(
							'src'=>$src[0],
							'w'=>$args[$width_attr],
							'h'=>$args[$height_attr],
							'urlonly'=>true
						));

						$html=str_replace($image_src[1],$url,$html);
						$image_src=$url=null;
					}
				}
			}
			if($args[$width_attr]!==''){
				$html=preg_replace('/ width=\"([0-9]{1,})\"/',' width="'.$args[$width_attr].'"',$html);
			}
			if($args[$height_attr]!==''){
				$html=preg_replace('/ height=\"([0-9]{1,})\"/',' height="'.$args[$height_attr].'"',$html);
			}
		}
		return $html;
	}

    public static function set_image_size_gallery($html,$id){
        global $product;
        return TB_Product_Image_Module::get_product_image_thumbnail_html(array('image_w'=>$product->gallery_image_size_w,'image_h'=>$product->gallery_image_size_h),$id);
    }

    public static function set_image_size_gallery_thumbnail($size){
        remove_filter('woocommerce_gallery_thumbnail_size',array('TB_Product_Image_Module','set_image_size_gallery_thumbnail'));
        return array(
            empty($GLOBALS['product']->gallery_thumb_size_w) ? $size['width'] : $GLOBALS['product']->gallery_thumb_size_w,
            empty($GLOBALS['product']->gallery_thumb_size_h) ? $size['height'] : $GLOBALS['product']->gallery_thumb_size_h,
        );
    }

	public static function product_gallery_type($type){
		remove_filter('themify_theme_product_gallery_type',array('TB_Product_Image_Module','product_gallery_type'));
		return 'disable-zoom'===$type?'':$type;
	}

}

if ( themify_is_woocommerce_active()) {
	Themify_Builder_Model::register_module('TB_Product_Image_Module');
}
