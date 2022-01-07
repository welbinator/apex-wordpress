<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ProductAttributes extends Tbp_Dynamic_Item {

	function is_available() {
		return themify_is_woocommerce_active();
	}

	function get_category() {
		return 'wc';
	}

	function get_type() {
		return array( 'wp_editor' );
	}

	function get_label() {
		return __( 'Product Attributes', 'tbp' );
	}

	function get_value( $args = array() ) {
		ob_start();
		if ( empty( $args['post_id'] ) ) {
		    $the_query = Tbp_Utils::get_wc_actual_query();
		    if ( $the_query === null || $the_query->have_posts() ) {
				if ( $the_query !== null ) {
					$the_query->the_post();
				}
				global $product;
				if ( ! empty( $product ) ) {
					wc_display_product_attributes( $product );
				}
		    }
		    if ( $the_query !== null ) {
				wp_reset_postdata();
		    }
		} else {
		    $product = wc_get_product( $args['post_id'] );
		    if ( ! empty( $product ) ) {
				wc_display_product_attributes( $product );
		    }
		}

		return ob_get_clean();
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Product ID', 'tbp' ),
				'id' => 'post_id',
				'type' => 'number',
				'help' => __( 'Leave empty to get the data from current product in the loop.', 'tbp' ),
			),
		);
	}
}
