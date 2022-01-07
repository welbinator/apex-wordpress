<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostTitle extends Tbp_Dynamic_Item {

	function get_category() {
		return 'post';
	}

	function get_type() {
		return array( 'text', 'textarea', 'wp_editor' );
	}

	function get_label() {
		return __( 'Post Title', 'tbp' );
	}

	function get_value( $args = array() ) {
		if(empty($args['post_id'])){
		    $the_query = Tbp_Utils::get_actual_query();
		    if($the_query===null || $the_query->have_posts()){
			if($the_query!==null){
			    $the_query->the_post();
			}
			$value = get_the_title();
		    }
		    if($the_query!==null){
			wp_reset_postdata();
		    }
		}
		else{
		    $value = get_the_title( $args['post_id'] );
		}
		return $value;
	}

	function get_options() {
		return array(
			array(
				'label' => __( 'Post ID', 'tbp' ),
				'id' => 'post_id',
				'type' => 'number',
				'help' => __( 'Leave empty to get the data from current post in the loop.', 'tbp' ),
			),
		);
	}
}
