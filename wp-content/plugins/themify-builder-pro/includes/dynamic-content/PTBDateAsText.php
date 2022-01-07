<?php
/**
 * Maps PTB's "date" field type to "text" field in Builder
 *
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBDateAsText extends Tbp_Dynamic_Item {

	function is_available() {
		return function_exists( 'run_ptb' );
	}

	function get_category() {
		return 'ptb';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'PTB Custom Fields (Date)', 'tbp' );
	}

	function get_value( $args = array() ) {
		$value = '';
		$args = wp_parse_args( $args, array(
			'show' => 'start_date',
			'date_format' => 'F j, Y',
			'custom_date_format' => '',
		) );

		if ( ! empty( $args['field'] ) ) {
			list( $post_type, $field_name ) = explode( ':', $args['field'] );
			$value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );

			// 'range' date fields
			if ( is_array( $value ) ) {
				$value = $value[ $args['show'] ];
			}
		}

		if ( ! empty( $value ) ) {
			$value = $args['date_format'] === 'custom' ? wp_date( $args['custom_date_format'], strtotime( $value ) ) : wp_date( $args['date_format'], strtotime( $value ) );
		}

		return $value;
	}

	function get_options() {
		$options = array();

		/* collect "text" field types in all post types */
		$ptb = PTB::$options->get_custom_post_types();
		foreach ( $ptb as $post_type_key => $post_type ) {
			if ( is_array( $post_type->meta_boxes ) ) {
				foreach ( $post_type->meta_boxes as $key => $field ) {
					if ( $field['type'] === 'event_date' ) {
					    $label = PTB_Utils::get_label( $post_type->plural_label );
					    $name = PTB_Utils::get_label( $field['name'] );
					    $options[ "{$post_type_key}:{$key}" ] = sprintf( '%s: %s', $label, $name );
					}
				}
			}
		}

		return array(
			array(
				'label' => __( 'Field', 'tbp' ),
				'id' => 'field',
				'type' => 'select',
				'options' => $options,
			),
			array(
				'label' => __( 'Show', 'tbp' ),
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'start_date' => __( 'Start Date', 'tbp' ),
					'end_date' => __( 'End Date', 'tbp' ),
				),
			),
			array(
				'label' => __( 'Date Format', 'tbp' ),
				'id' => 'date_format',
				'type' => 'select',
				'options' => array(
					'F j, Y' => date_i18n( 'F j, Y' ),
					'Y-m-d'  => date_i18n( 'Y-m-d' ),
					'm/d/Y'  => date_i18n( 'm/d/Y' ),
					'd/m/Y'  => date_i18n( 'd/m/Y' ),
					'custom' => __( 'Custom', 'tbp' ),
				),
				'binding' =>array(
				  'not_empty' => array( 'hide' => array( 'custom_date_format' ) ),
				  'custom' => array( 'show' => array( 'custom_date_format' ) )
				)
			),
			array(
				'label' => __( 'Custom Date Format', 'tbp' ),
				'id' => 'custom_date_format',
				'type' => 'text',
				'help' => sprintf( __( 'For information on how to format date and time see <a href="%s" target="_blank">Codex</a>.', 'tbp' ), 'https://wordpress.org/support/article/formatting-date-and-time/' )
			),
		);
	}
}