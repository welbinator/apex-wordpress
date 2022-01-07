<?php
/**
 * Term Cover Image
 */

final class TBP_Term_Images {

	public static function run() {
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ), 100 );
	}

	/**
	 * Add options to all taxonomy terms that have archives
	 */
	public static function admin_init() {
		$taxonomies = get_taxonomies( array( 'public' => true ) );
		foreach ( $taxonomies as $tax ) {
			add_filter( "themify_metabox/taxonomy/{$tax}/fields", array( __CLASS__, 'fields' ) );
		}
	}

	public static function fields( $fields ) {
		$options = array(
			array(
				'name' => 'tbp_cover',
				'title' => __( 'Builder Pro Cover Image', 'themify' ),
				'description' => __( 'This image can be used in Builder Pro archive templates.', 'tbp' ),
				'type' => 'image',
				'meta' => array()
			),
		);

		return array_merge( $fields, $options );
	}
}