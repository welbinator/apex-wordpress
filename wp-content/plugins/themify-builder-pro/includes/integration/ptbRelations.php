<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link https://themify.me/ptb-addons/relation
 */
class Themify_Builder_Plugin_Compat_ptbRelations {

	static function init() {
		add_filter( 'tbp_dynamic_query_items', array( __CLASS__, 'tbp_dynamic_query_items' ) );
	}

	/**
	 * Add PTB Relation as an option to Dyanmic Query
	 *
	 * @return array
	 */
	public static function tbp_dynamic_query_items( $items ) {
		$items['ptbRelation'] = 'Tbp_Dynamic_Query_ptbRelation';

		return $items;
	}
}

class Tbp_Dynamic_Query_ptbRelation {

	static function get_id() {
		return 'ptbRelation';
	}

	static function get_label() {
		return __( 'PTB Relations', 'tbp' );
	}

	static function get_options() {
		return array(
			array(
				'id' => 'ptbRelation_field',
				'type' => 'select',
				'label' => __( 'PTB Relations Field', 'tbp' ),
				'options' => Tbp_Utils::get_ptb_fields_by_type( 'relation' ),
			),
		);
	}

	static function pre_get_posts( &$query, $settings ) {
		if ( empty( $settings['ptbRelation_field'] ) ) {
			return;
		}

		list( $post_type, $field_name ) = explode( ':', $settings['ptbRelation_field'] );
		$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
		$relType = ! empty( $cf_value['relType'] ) ? (int) $cf_value['relType'] : 1;
		$ids = ! empty( $cf_value['ids'] ) ? $cf_value['ids'] : $cf_value;
		$ids = array_filter( is_array( $ids ) ? $ids : explode( ', ', $ids ) );
		$ptb = PTB::get_option()->get_options();
		$ptb_options = PTB::get_option();
		$def = $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ];
		if ( empty( $ids ) ) {
			return;
		}

		$query->set( 'post_type', $def['post_type'] );
		$query->set( 'posts_per_page', -1 );

		if ( $relType === 1 ) {
			$query->set( 'post__in', $ids );
		} else {
			$tax_query = array(
				'relation' => 'AND',
			);
			$terms = get_terms( array(
				'include' => $ids
			) );
			foreach ( $terms as $term ) {
				if ( isset( $tax_query[ $term->taxonomy ] ) ) {
					$tax_query[ $term->taxonomy ]['terms'][] = $term->term_id;
				} else {
					$tax_query[ $term->taxonomy ] = array(
						'taxonomy' => $term->taxonomy,
						'field' => 'term_id',
						'terms' => array( $term->term_id ),
					);
				}
			}
			$query->set( 'tax_query', $tax_query );
		}
	}
}