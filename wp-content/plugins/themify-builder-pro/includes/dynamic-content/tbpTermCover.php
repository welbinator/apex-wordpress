<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_tbpTermCover extends Tbp_Dynamic_Item {

	function get_category() {
		return 'general';
	}

	function get_type() {
		return array( 'image', 'url' );
	}

	function get_label() {
		return __( 'Term Cover Image', 'tbp' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( is_category() || is_tag() || is_tax() ) {
			$cat = get_queried_object();
			$value = get_term_meta( $cat->term_id, 'tbp_cover', true );
		}

		return $value;
	}

	function get_options() {
		return array();
	}
}
