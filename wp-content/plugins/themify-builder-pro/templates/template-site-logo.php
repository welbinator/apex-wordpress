<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Site Logo
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
$fields_default = array(
    'display' => 'text',
    'url_image' => '',
    'width_image' => 100,
    'height_image' => 100,
    'link' => 'siteurl',
    'custom_url' => '',
    'html_tag' => '',
    'css' => '',
    'animation_effect' => '',
);
$fields_args = wp_parse_args($args['mod_settings'], $fields_default);
unset($args['mod_settings']);
$fields_default=null;
$mod_name = $args['mod_name'];
$element_id =$args['module_ID'];
$builder_id = $args['builder_id'];
$container_class = apply_filters('themify_builder_module_classes', array(
    'module',
    'module-' . $mod_name,
    $element_id,
    $fields_args['css']
	), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
    $container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args,array(
    'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);
$args = null;
if ('image' === $fields_args['display'] && '' !== $fields_args['url_image']) {
    $image = themify_get_image(array(
		'src'=>esc_url($fields_args['url_image']),
		'w'=>$fields_args['width_image'],
		'h'=>$fields_args['height_image'],
		'alt'=>'',
		'image_size'=>themify_builder_get('setting-global_feature_size', 'image_global_size_field'),
		'attr'=>Themify_Builder::$frontedit_active===true?array('data-w'=>'width_image', 'data-h'=>'height_image','data-name'=>'url_image'):false
	));
} else {
    $image = get_bloginfo('name');
}

$home_url = function_exists( 'themify_home_url' ) ? themify_home_url() : home_url(); /* backward compatibility, added Nov 2021 */
$url = 'siteurl' === $fields_args['link'] ? $home_url : ( 'custom' === $fields_args['link'] && '' !== $fields_args['custom_url'] ? esc_url($fields_args['custom_url']) : false);
if(Themify_Builder::$frontedit_active===false){
    $container_props['data-lazy']=1;
}
?>
<!-- Site Logo module -->
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args));?>>
    <div class="site-logo-inner">
	<?php $container_props = $container_class = null; 
	    do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
	?>
	<?php if (!empty($fields_args['html_tag'])): ?>
	    <<?php echo $fields_args['html_tag'] ?>>
	<?php endif; ?>

	<?php if ($url !== false): ?>
	    <a href="<?php echo $url ?>">
	    <?php endif; ?>

	    <?php echo $image; ?>
	    <?php if ($url !== false): ?>
	    </a>
	<?php endif; ?>
	<?php if (!empty($fields_args['html_tag'])): ?>
	    </<?php echo $fields_args['html_tag'] ?>>
	<?php endif; ?>
    </div>
</div>
<!-- /Site Logo module -->
