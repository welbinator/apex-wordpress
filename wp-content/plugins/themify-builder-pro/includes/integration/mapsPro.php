<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link 
 */
class Themify_Builder_Plugin_Compat_mapsPro {

	static function init() {
		add_filter( 'tb_maps_pro_data_providers', array( __CLASS__, 'tb_maps_pro_data_providers') );
		add_filter( 'tb_select_dataset_ptb_map_fields', array( __CLASS__, 'tb_select_dataset_ptb_map_fields' ) );
	}

	/**
	 * Enable displaying Posts in the Builder Maps Pro addon
	 *
	 * @return array
	 */
	public static function tb_maps_pro_data_providers( $providers ) {
		require_once TBP_DIR. 'includes/class-tbp-maps-pro-posts-provider.php';
		$providers['posts'] = 'Tbp_Maps_Pro_Posts_Provider';
		return $providers;
	}

	/**
	 * Handles filling dynamic values for the "ptb_map_field" option in Maps Pro
	 *
	 * @return array
	 */
	public static function tb_select_dataset_ptb_map_fields( $values ) {
		return array(
			'options' => array_merge(
				array( '' => '' ),
				Tbp_Utils::get_ptb_fields_by_type( 'map' )
			),
		);
	}
}