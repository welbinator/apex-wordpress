<?php

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
/**
 * Template Breadcrumbs
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
$fields_args    = wp_parse_args( $args[ 'mod_settings' ], array(
	'sep' => 'c',
	'sep_c' => '/',
	'sep_icon' => '',
	'tag' => 'nav',
	'lb_home' => '',
	'lb_archives' => '',
	'lb_404' => '',
	'hide_network' => 'no',
	'animation_effect' => '',
	'css' => '',
) );
unset( $args[ 'mod_settings' ] );
$mod_name        = $args[ 'mod_name' ];
$element_id      = $args[ 'module_ID' ];
$builder_id      = $args[ 'builder_id' ];
$container_class = apply_filters( 'themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args[ 'css' ] 
), $mod_name, $element_id, $fields_args );

if ( !empty( $fields_args[ 'global_styles' ] ) && Themify_Builder::$frontedit_active === false ) {
	$container_class[] = $fields_args[ 'global_styles' ];
}
$container_props = apply_filters( 'themify_builder_module_container_props', self::parse_animation_effect( $fields_args, array(
	'class' => implode( ' ', $container_class ) 
) ), $fields_args, $mod_name, $element_id );
$args            = null;
if ( Themify_Builder::$frontedit_active === false ) {
	$container_props[ 'data-lazy' ] = 1;
}

?>
<!-- Breadcrumbs module -->
<div <?php echo self::get_element_attributes( self::sticky_element_props( $container_props, $fields_args ) ); ?>>
<?php
$container_props = $container_class = null;
do_action( 'themify_builder_background_styling', $builder_id, array(
	'styling' => $fields_args,
	'mod_name' => $mod_name 
), $element_id, 'module' );

$separator = $fields_args['sep'] === 'c' ? $fields_args['sep_c'] : themify_get_icon( $fields_args['sep_icon'] );

$the_query = Tbp_Utils::get_actual_query();
if ( $the_query === null || $the_query->have_posts() ) {
	if ( $the_query !== null ) {
		$the_query->the_post();
	}

	TB_Breadcrumbs_Module::display( array(
		'container' => $fields_args['tag'],
		'labels' => array(
			'home' => $fields_args['lb_home'],
			'archives' => $fields_args['lb_archives'],
			'error_404' => $fields_args['lb_404'],
		),
		'network' => 'no' === $fields_args['hide_network'],
		'separator' => $separator,
	) );

	if ( $the_query !== null ) {
		wp_reset_postdata();
	}
}
?>
</div><!-- /Breadcrumbs module -->
