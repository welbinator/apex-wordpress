<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}
$data      = FrmAppHelper::get_landing_page_upgrade_data_params();
$data_keys = array_keys( $data );
$params    = array();
foreach ( $data_keys as $key ) {
	$params[ 'data-' . $key ] = $data[ $key ];
}
$params['class'] = 'frm_show_upgrade frm_noallow';
$params['href']  = '#';
?>
<a <?php FrmAppHelper::array_to_html_params( $params, true ); ?>>
	<?php esc_html_e( 'Generate Form Page', 'formidable' ); ?><span class="frm-new-pill"><?php esc_html_e( 'NEW', 'formidable' ); ?></span>
</a>
