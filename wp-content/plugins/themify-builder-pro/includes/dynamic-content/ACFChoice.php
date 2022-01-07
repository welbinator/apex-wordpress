<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFChoice extends Tbp_Dynamic_Item {

	function is_available() {
		return class_exists( 'ACF' );
	}

	function get_category() {
		return 'acf';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'ACF (Choice Fields)', 'tbp' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$sep = isset( $args['sep'] ) ? $args['sep'] : ',';
			$field_value = (array) Tbp_Utils::acf_get_field_value( $args );
			$value = implode( $sep, $field_value );
		}

		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Field', 'tbp' ),
				'id' => 'key',
				'type' => 'select',
				'options' => Tbp_Utils::get_acf_fields_by_type( [ 'select', 'checkbox', 'radio', 'button_group', 'true_false' ] ),
			),
			array(
				'label' => __( 'Separator', 'tbp' ),
				'id' => 'sep',
				'type' => 'text',
				'help' => __( 'Character to separate items when multiple choice is allowed.', 'tbp' ),
			),
		);
	}
}
