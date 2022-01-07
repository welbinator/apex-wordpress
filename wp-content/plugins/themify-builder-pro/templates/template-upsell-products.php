<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Upsell Products
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (themify_is_woocommerce_active()):

    $the_query = Tbp_Utils::get_wc_actual_query();
    $upsell_ids=null;
    if ($the_query===null || $the_query->have_posts()){
        if($the_query!==null){
            $the_query->the_post();
        }
        global $product;
        $upsell_ids = $product->get_upsell_ids();
        if(empty($upsell_ids) && Tbp_Utils::$isActive!==true && Themify_Builder::$frontedit_active!==true){
            return;
        }
    }
    $fields_default = array(
	'heading' =>'',
	'layout' => 'grid3',
	'css' => '',
	'animation_effect' => ''
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
    <!-- Upsell Products module -->
    <div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props=$container_class=null; 
	do_action('themify_builder_background_styling',$builder_id,array('styling'=>$fields_args,'mod_name'=>$mod_name),$element_id,'module');
    if (!empty($upsell_ids)) :
	    global $woocommerce_loop, $themify;

	    switch ($fields_args['layout']) {
		case 'grid2':
		    $col = 2;
		    break;
		case 'grid4':
		    $col = 4;
		    break;
		default:
		    $col = 3;
		    break;
	    }

	    $query_args = apply_filters(
		'woocommerce_upsell_display_args', array(
		'posts_per_page' => $col,
		'columns' => $col
	    )
	    );
	    wc_set_loop_prop('name', 'up-sells');
	    wc_set_loop_prop('columns', apply_filters('woocommerce_upsells_columns', isset($query_args['columns']) ? $query_args['columns'] : $columns ));
	    $upsells = array_slice($upsell_ids, 0, $col);
	    
	    if (!empty($upsells)) :
		?>

	        <div class="upsells products tbp_posts_wrap <?php echo $fields_args['layout']; ?> tf_clearfix<?php echo 'sidebar-none' === $themify->layout ? ' pagewidth' : ''; ?>">

	    	<?php if ($fields_args['heading'] !== ''): ?>
				<h2<?php if(method_exists('Themify_Builder_Component_Base','add_inline_edit_fields')){self::add_inline_edit_fields('heading');}?>><?php echo $fields_args['heading']; ?></h2>
			<?php endif; ?>

		    <?php woocommerce_product_loop_start();
			$isLoop=$ThemifyBuilder->in_the_loop===true;
			$ThemifyBuilder->in_the_loop = true;
			foreach ($upsells as $upsell){
				$post_object = get_post($upsell);

				setup_postdata($GLOBALS['post'] = $post_object);

				$temp_content = $themify->display_content;
				$themify->display_content='none'; // Hide the product description in Themify themes
				wc_get_template_part('content', 'product');
				$themify->display_content=$temp_content;
			} 
		    woocommerce_product_loop_end();
		    $ThemifyBuilder->in_the_loop = $isLoop;
		    ?>

	        </div>
	    <?php endif; ?>
	    <?php
	    if($the_query!==null){
		wp_reset_postdata();
	    }
	endif;
	?>
	
    <?php if(empty($upsell_ids) && (Tbp_Utils::$isActive===true || Themify_Builder::$frontedit_active===true)):?>
	<div class="tbp_empty_module">
	    <?php echo Themify_Builder_Model::get_module_name($mod_name);?>
	</div>
    <?php endif; ?>
    </div>
    <!-- /Upsell Products module -->
<?php endif; ?>
