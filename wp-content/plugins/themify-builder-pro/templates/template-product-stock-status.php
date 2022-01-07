<?php
if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly
/**
 * Template Product Stock Status
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if ( ! themify_is_woocommerce_active() ) {
	return;
}
$fields_args = wp_parse_args( $args['mod_settings'], array(
	'in_stock' => __( '%stock_count% available in stock', 'tbp' ),
	'out_of_stock' => __( 'Out of stock', 'tbp' ),
	'css' => '',
	'animation_effect' => ''
) );
unset($args['mod_settings']);
$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$builder_id = $args['builder_id'];
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	'product-price',
	$element_id,
	$fields_args['css']
	), $mod_name, $element_id, $fields_args
);

if ( ! empty( $fields_args['global_styles'] ) && Themify_Builder::$frontedit_active === false ) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect( $fields_args,array(
	'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id
);
$args = null;
if ( Themify_Builder::$frontedit_active === false ) {
	$container_props['data-lazy']=1;
}
?>
<!-- Product Stock Status -->
<div <?php echo self::get_element_attributes( self::sticky_element_props( $container_props, $fields_args ) ); ?>>

<?php
do_action( 'themify_builder_background_styling', $builder_id, array( 'styling' => $fields_args, 'mod_name' => $mod_name ), $element_id, 'module' );
$the_query = Tbp_Utils::get_wc_actual_query();
if ( $the_query===null || $the_query->have_posts() ) {
	if ( $the_query !== null ) {
		$the_query->the_post();
	}

	global $product;
	if ( $product->is_in_stock() ) {
		echo '<div class="tbp_product_in_stock">' . str_replace( '%stock_count%', $product->get_stock_quantity(), $fields_args['in_stock'] ) . '</div>';
	} else {
		echo '<div class="tbp_product_out_of_stock">' . $fields_args['out_of_stock'] . '</div>';
	}

	if ( $the_query !== null ) {
		wp_reset_postdata();
	}
}
?>
</div><!-- /Product Stock Status -->