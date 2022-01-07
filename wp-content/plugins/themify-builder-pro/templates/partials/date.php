<?php if (!empty($args['val']['icon'])): ?>
    <span><?php echo themify_get_icon($args['val']['icon'])?></span>
<?php endif; ?>
<?php
$args['val'] = empty($args['val']) ? array() : $args['val'];
$format =Tbp_Utils::getDateFormat($args['val']);
$isDate=$args['type']!=='time';
$args=null;
/* default format value */
if ( empty( $format ) ) {
    $format = $isDate===true?get_option('date_format'):get_option('time_format');
}
$time = get_the_time('c');
$format=str_split( $format );
$output='';
foreach($format as $format_character){
    if ( in_array( $format_character, array( 'd', 'D', 'j', 'l', 'N', 'S', 'w', 'z' ),true ) ){
        $type='day';
    }
    elseif ( $format_character==='w'){
        $type='week';
    }
    elseif ( in_array( $format_character, array( 'F', 'm', 'M', 'n', 't' ),true ) ){
        $type='month';
    }
    elseif ( in_array( $format_character, array( 'L', 'o', 'Y', 'y' ),true) ){
        $type='year';
    }
    elseif ( in_array( $format_character, array( 'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u', 'v' ),true ) ){
        $type='time';
    }
    elseif ( in_array( $format_character, array( 'e', 'I', 'O', 'P', 'T', 'Z' ),true ) ){
        $type='timezone';
    }
    elseif ( in_array( $format_character, array( 'c', 'r', 'U' ),true ) ){
        $type='datetime';
    }else{
        $type='';
    }
    $output.=''!==$type?sprintf('<span class="tbp_post_%s">%s</span>',$type,get_the_time( $format_character )):$format_character;
}
?>
<time content="<?php echo $time?>" class="entry-date updated" datetime="<?php echo $time; ?>">
    <?php echo $output; ?>
    <?php if ( $isDate === true ) : ?>
	    <meta content="<?php echo get_the_modified_time('c') ?>">
    <?php endif; ?>
</time>
