<?php
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

/**
 * Module Name: Archive Image
 * Description: 
 */
class TB_Archive_Image_Module extends Themify_Builder_Component_Module {
	
	function __construct() {
		parent::__construct(array(
		    'name' => __( 'Archive Cover Image', 'tbp' ),
		    'slug' => 'archive-image',
		    'category' => array( 'archive', 'product_archive' )
		));
	}
	
	public function get_assets() {
		return array(
			'css' => THEMIFY_BUILDER_CSS_MODULES . 'image.css'
		);
	}

	public function get_icon(){
		return 'image';
	}

	public function get_options() {
		$options = Tbp_Utils::get_module_settings( 'image', 'options' );
		foreach ( $options as $index => $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( in_array( $option['id'], [ 'url_image', 'title_image', 'link_image', 'caption_image', 'alt_image', 'param_image', 'multi_lightbox', 'image_zoom_icon' ] ) ) {
				unset( $options[ $index ] );
			}
		}

		return $options;
	}

	public function get_styling() {
		$options = Tbp_Utils::get_module_settings( 'image', 'styling' );
		return $options;
	}

	public function get_visual_type() {
		return 'ajax';
	}
}
Themify_Builder_Model::register_module( 'TB_Archive_Image_Module' );