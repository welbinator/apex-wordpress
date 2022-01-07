<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFImage extends Tbp_Dynamic_Item {

	function is_available() {
		return class_exists( 'ACF' );
	}

	function get_category() {
		return 'acf';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'image', 'url' );
	}

	function get_label() {
		return __( 'ACF (Image)', 'tbp' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$cf_value = Tbp_Utils::acf_get_field_value( $args );
			if ( is_string( $cf_value ) ) {
				$value = $cf_value;
			} else if ( ! empty( $cf_value['url'] ) ) {
				$value = $cf_value['url'];
			}
		}

		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Field', 'tbp' ),
				'id' => 'key',
				'type' => 'select',
				'options' => Tbp_Utils::get_acf_fields_by_type( 'image' ),
			),
		);
	}
}
