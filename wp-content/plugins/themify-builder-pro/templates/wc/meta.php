<?php global $product; 
	$isExist=isset($args['mod_name']) && (Tbp_Utils::$isActive===true || Themify_Builder::$frontedit_active===true)?false:null;
	$isNew=method_exists('Themify_Builder_Component_Base','add_inline_edit_fields');
?>
<div class="product_meta">
    <?php do_action('woocommerce_product_meta_start'); ?>
    <?php if ($args['enable_sku'] === 'yes' && wc_product_sku_enabled() && ( ($sku = $product->get_sku()) || $product->is_type('variable') )) : ?>
        <span class="sku_wrapper">
	    <?php if ($args['sku'] !== '' && $sku): ?>
			<span<?php if($isNew===true){Themify_Builder_Component_Base::add_inline_edit_fields('sku');}?>><?php echo $args['sku']; ?></span>: 
	    <?php endif; ?>
	    <?php
		if($isExist===false){
		    $isExist=true;
		}
		echo $sku;
	    ?>
        </span>
    <?php endif; ?>
    <?php if ($args['enable_cat'] === 'yes'){
		if($args['cat'] !== ''){
			$cat='<span';
			if($isNew===true){
				$cat.=Themify_Builder_Component_Base::add_inline_edit_fields('cat',true,false,false,-1,false);
			}
			$args['cat']=$cat.'>'.$args['cat'].'</span>';
		}
	    $output = wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . $args['cat'], '</span>');
	    echo $output;
	    if($isExist===false){
		$isExist = !empty($output);
	    }
	    $output=null;
	 } 
	 if ($args['enable_tag'] === 'yes'){ 
		 if($args['tag'] !== ''){
			$cat='<span';
			if($isNew===true){
				$cat.=Themify_Builder_Component_Base::add_inline_edit_fields('tag',true,false,false,-1,false);
			}
			$args['tag']=$cat.'>'.$args['tag'].'</span>';
		}
		$output= wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . $args['tag'], '</span>');
	    echo $output;
	    if($isExist===false){
		$isExist = !empty($output);
	    }
	    $output=null;
	} ?>
    <?php do_action('woocommerce_product_meta_end'); ?>

</div>
<?php if($isExist===false):?>
    <div class="tbp_empty_module">
	<?php echo Themify_Builder_Model::get_module_name($args['mod_name']);?>
    </div>
<?php endif; $args=null;