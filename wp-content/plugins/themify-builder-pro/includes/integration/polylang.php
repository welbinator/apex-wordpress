<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link https://wordpress.org/plugins/polylang/
 */
class Themify_Builder_Plugin_Compat_polylang {

	/**
	 * Enable translation on the Template post type
	 */
	static function init() {
		add_filter( 'pll_get_post_types', array( __CLASS__, 'pll_get_post_types' ), 10, 2 );
	}

	public static function pll_get_post_types( $post_types, $programmatically_active ) {
		if ( $programmatically_active ) {
			$post_types['tbp_template'] = 'tbp_template';
		}

		return $post_types;
	}
}