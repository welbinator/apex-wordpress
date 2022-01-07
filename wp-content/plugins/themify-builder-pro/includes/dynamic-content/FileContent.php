<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_FileContent extends Tbp_Dynamic_Item {

	function get_category() {
		return 'advanced';
	}

	function get_type() {
		return array( 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'File Content', 'tbp' );
	}

	function get_value( $args = array() ) {
		$value = '';
		if ( ! empty( $args['path'] ) ) {
		    $file = trailingslashit( ABSPATH ) . $args['path'];
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		    WP_Filesystem();
		    global $wp_filesystem;
		    if ( $wp_filesystem->is_file( $file ) ) {
			    $value = $wp_filesystem->get_contents( $file );
		    }
		}

		return $value;
	}

	function get_options() {
		return array(
			array(
				'id' => 'path',
				'type' => 'text',
				'label' => __( 'File Path', 'tbp' ),
				'class' => 'large',
				'help' =>  sprintf( __( 'To display content from text file, enter the path. Path is started from: %s', 'tbp' ), trailingslashit( ABSPATH ) ),
			),
		);
	}
}
