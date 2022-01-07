<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link https://www.advancedcustomfields.com/
 */
class Themify_Builder_Plugin_Compat_acf {

	static function init() {
		add_filter( 'tbp_dynamic_query_items', array( __CLASS__, 'tbp_dynamic_query_items' ) );
	}

	/**
	 * Add ACF pro's "relational" fields as an option to Dyanmic Query
	 *
	 * @return array
	 */
	public static function tbp_dynamic_query_items( $items ) {
		$items['acf'] = 'Tbp_Dynamic_Query_acf';

		return $items;
	}
}

class Tbp_Dynamic_Query_acf {

	static function get_id() {
		return 'acf';
	}

	static function get_label() {
		return __( 'ACF Pro Relational', 'tbp' );
	}

	static function get_options() {
		return array(
			array(
				'id' => 'acf_field',
				'type' => 'select',
				'label' => __( 'ACF Relational Field', 'tbp' ),
				'options' => Tbp_Utils::get_acf_fields_by_type( [ 'relationship', 'post_object' ] ),
			),
			array(
				'id' => 'acf_ctx',
				'type' => 'select',
				'label' => __( 'ACF Context', 'tbp' ),
				'options' => array(
					'' => __( 'Current post', 'tbp' ),
					'term' => __( 'Taxonomy terms', 'tbp' ),
					'user' => __( 'Current logged-in user', 'tbp' ),
					'author' => __( 'Author of current post', 'tbp' ),
					'option' => __( 'Option', 'tbp' ),
				),
			),
		);
	}

	static function pre_get_posts( &$query, $settings ) {
		if ( empty( $settings['acf_field'] ) ) {
			return;
		}

		list( $group_id, $field_id ) = explode( ':', $settings['acf_field'] );
		$value = get_field( $field_id, Tbp_Utils::acf_get_context( $settings ) );
		$field_object = get_field_object( $field_id );
		if ( ! empty( $value ) && ! empty( $field_object ) ) {
			if ( $field_object['type'] === 'post_object' ) {
				$value = [ $value ];
			}
			if ( $field_object['return_format'] === 'object' ) {
				$value = wp_list_pluck( $value, 'ID' );
			}
			$query->set( 'posts_per_page', -1 );
			$query->set( 'post_type', 'any' );
			$query->set( 'post__in', $value );

			return true;
		}
	}
}