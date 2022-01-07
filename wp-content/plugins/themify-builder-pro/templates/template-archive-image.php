<?php
if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

/**
 * Template Archive Cover Image
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

/* spoof Image module */
$args['mod_settings']['css_image'] = 'module-' . $args['mod_name'];
$args['mod_name'] = 'image';

/* disable inline editor */
if ( isset( Themify_Builder_Component_Base::$disable_inline_edit ) ) {
	$inline_editor = Themify_Builder_Component_Base::$disable_inline_edit;
	Themify_Builder_Component_Base::$disable_inline_edit = true;
}

if ( is_category() || is_tag() || is_tax() ) {
	$cat = get_queried_object();
	$value = get_term_meta( $cat->term_id, 'tbp_cover', true );
	$args['mod_settings']['url_image'] = $value;
	$args['mod_settings']['title_image'] = single_term_title( '', false );
} else if ( Themify_Builder::$frontedit_active || is_singular( Tbp_Templates::$post_type ) ) {
	$args['mod_settings']['url_image'] = THEMIFY_BUILDER_URI . '/img/image-placeholder.jpg';
	$args['mod_settings']['title_image'] = __( 'Archive Title', 'tbp' );
}

self::retrieve_template( 'template-image.php', $args );

if ( isset( Themify_Builder_Component_Base::$disable_inline_edit ) ) {
	Themify_Builder_Component_Base::$disable_inline_edit = $inline_editor;
}