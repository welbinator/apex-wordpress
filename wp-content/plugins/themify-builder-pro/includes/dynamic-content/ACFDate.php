<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFDate extends Tbp_Dynamic_Item {

	function is_available() {
		return class_exists( 'ACF' );
	}

	function get_category() {
		return 'acf';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor', 'date' );
	}

	function get_label() {
		return __( 'ACF (Date & Time)', 'tbp' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$value = Tbp_Utils::acf_get_field_value( $args );
		}

		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Field', 'tbp' ),
				'id' => 'key',
				'type' => 'select',
				'options' => Tbp_Utils::get_acf_fields_by_type( [ 'date_picker', 'date_time_picker', 'time_picker' ] ),
			),
		);
	}
}
