<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFMap extends Tbp_Dynamic_Item {

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
		return __( 'ACF (Map)', 'tbp' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$display = isset( $args['display'] ) ? $args['display'] : 'address';
			$cf_value = Tbp_Utils::acf_get_field_value( $args );
			if ( ! empty( $cf_value ) ) {
				if ( $display === 'latlng' ) {
					if ( isset( $cf_value['lat'], $cf_value['lng'] ) ) {
						$value = $cf_value['lat'] . ',' . $cf_value['lng'];
					}
				} else if ( isset( $cf_value[ $display ] ) ) {
					$value = $cf_value[ $display ];
				}
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
				'options' => Tbp_Utils::get_acf_fields_by_type( 'google_map' ),
			),
			array(
				'label' => __( 'Display', 'tbp' ),
				'id' => 'display',
				'type' => 'select',
				'options' => array(
					'address' => __( 'Address', 'tbp' ),
					'lat' => __( 'Lat', 'tbp' ),
					'lng' => __( 'Lng', 'tbp' ),
					'latlng' => __( 'Lat & Lng', 'tbp' ),
					'name' => __( 'Name', 'tbp' ),
					'city' => __( 'City', 'tbp' ),
					'state' => __( 'State', 'tbp' ),
					'country' => __( 'Country', 'tbp' ),
				)
			),
		);
	}
}