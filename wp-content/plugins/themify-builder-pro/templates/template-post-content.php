<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Post Content
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
$fields_default = array(
    'css' => '',
    'animation_effect' => '',
    'content_type' => 'full',
    'more_link' => '',
    'more_text' => __('Read More','tbp')
);
$fields_args = wp_parse_args($args['mod_settings'], $fields_default);
$fields_default=null;
unset($args['mod_settings']);
$mod_name=$args['mod_name'];
$element_id =$args['module_ID'];
$builder_id=$args['builder_id'];
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']), $mod_name, $element_id, $fields_args);
if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args,array(
	'class' => implode(' ', $container_class),
)), $fields_args, $mod_name, $element_id);
$args=null;
if(Themify_Builder::$frontedit_active===false){
    $container_props['data-lazy']=1;
}
?>
<!-- Post Content module -->
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
    <?php
    $container_props=$container_class=null;
    do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	global $page, $wp_query;
	if($page>1){
		global $themify;
		$saved_themify = clone $themify;
		$themify->pro_paged = $page;
    }
    $the_query = Tbp_Utils::get_actual_query();
    if ($the_query===null || $the_query->have_posts()) {
	if($the_query!==null){
	    $the_query->the_post();
	}

	if ( is_main_query() ) {
		$in_the_loop = in_the_loop();
		$wp_query->in_the_loop = true;
	}

	self::retrieve_template('partials/content.php', $fields_args);
	if(!empty($saved_themify)){
		$themify = $saved_themify;
		$saved_themify = null;
    }
	if($the_query!==null){
	    wp_reset_postdata();
	}

	if ( isset( $in_the_loop ) ) {
		$wp_query->in_the_loop = $in_the_loop;
	}

    }
    ?>
</div>
<!-- /Post Content module -->