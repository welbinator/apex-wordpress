<?php
/*
Plugin Name: Themify Builder
Plugin URI: https://themify.me/builder
Description: Build responsive layouts that work for desktop, tablets, and mobile using intuitive &quot;what you see is what you get&quot; drag &amp; drop framework with live edits and previews.
Version: 5.3.5 
Author: Themify
Author URI: https://themify.me
Text Domain:  themify
Domain Path:  /languages
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Disable the plugin if using a Themify theme: Builder is included in the theme.
 *
 * @return void
 */
function themify_builder_theme_check() {
	if ( file_exists( trailingslashit( get_template_directory() ) . 'themify/themify-utils.php' ) ) {
		?>
		<div class="error">
			<p><?php _e( 'You are using a Themify theme. The Builder is included in the theme framework. No need to install Builder plugin.', 'themify' ); ?></p>
		</div>
		<?php
		deactivate_plugins( plugin_basename( __FILE__ ), true );
	}
}
add_action( 'admin_notices', 'themify_builder_theme_check' );

// Hook loaded
add_action( 'after_setup_theme', 'themify_builder_themify_dependencies',1 );
add_action( 'after_setup_theme', 'themify_builder_plugin_init', 2 );
add_filter( 'plugin_row_meta', 'themify_builder_plugin_meta', 10, 2 );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'themify_builder_action_links' );


if ( ! function_exists( 'themify_builder_activate' ) ) {
	/**
	 * Plugin activation
	 *
	 * This runs only when Builder plugin is activated.
	 */
	function themify_builder_activate() {
		flush_rewrite_rules();
	}
}
register_activation_hook( __FILE__, 'themify_builder_activate' );

if(!function_exists('themify_builder_plugin_meta')){
function themify_builder_plugin_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'themify' ) . '">' . esc_html__( 'View Changelogs', 'themify' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
}
if(!function_exists('themify_builder_action_links')){
function themify_builder_action_links( $links ) {
	if ( is_plugin_active( 'themify-updater/themify-updater.php' ) ) {
		$tlinks = array(
		 '<a href="' . admin_url( 'index.php?page=themify-license' ) . '">'.__('Themify License', 'themify') .'</a>',
		 );
	} else {
		$tlinks = array(
		 '<a href="' . esc_url('https://themify.me/docs/themify-updater-documentation') . '">'. __('Themify Updater', 'themify') .'</a>',
		 );
	}
	return array_merge( $links, $tlinks );
}
}

///////////////////////////////////////////
// Version Getter
///////////////////////////////////////////
if (!function_exists('themify_builder_get')) {

    function themify_builder_get($theme_var, $builder_var = false) {
        if (themify_is_themify_theme()===true) {
            return themify_get($theme_var);
        }
        if ($builder_var === false) {
            return false;
        }
        global $post;
        $data = Themify_Builder_Model::get_builder_settings();
        if (isset($data[$builder_var]) && $data[$builder_var] !== '') {
            return $data[$builder_var];
        } else if (is_object($post) && ($val = get_post_meta($post->ID, $builder_var, true)) !== '') {
            return $val;
        }
        return null;
    }

}
if ( ! function_exists( 'themify_builder_check' ) ) {

    function themify_builder_check( $theme_var, $builder_var = false, $data_only = true ) {
		$val = themify_builder_get( $theme_var, $builder_var, $data_only );

		return $val !== null && $val !== '' && $val !== 'off';
    }

}
if(!function_exists('themify_builder_themify_dependencies')){
	/**
	 * Load themify functions
	 */
	function themify_builder_themify_dependencies(){
		if ( class_exists( 'Themify_Builder' ) ) return;

		if ( ! defined( 'THEMIFY_DIR' ) ) {
			$path = plugin_dir_path( __FILE__ ) ;
			define( 'THEMIFY_VERSION', '5.1.8' );
			define( 'THEMIFY_DIR', $path. 'themify' );
			define( 'THEMIFY_URI', plugin_dir_url( __FILE__ ) . 'themify' );
			require_once( THEMIFY_DIR . '/themify-database.php' );
			require_once THEMIFY_DIR . '/class-themify-get-image-size.php';
			require_once( THEMIFY_DIR . '/img.php' );
			require_once( THEMIFY_DIR . '/themify-utils.php' );
			require_once( THEMIFY_DIR . '/themify-hooks.php' );
			require_once( $path. 'theme-options.php' );
			if( is_admin() ) {
				require_once( THEMIFY_DIR . '/themify-wpajax.php' );
			}
                        if( ! class_exists( 'Themify_Metabox' ) ) {
                            require_once( $path. 'themify/themify-metabox/themify-metabox.php' );
                        }
		}
		require_once( THEMIFY_DIR . '/google-fonts/functions.php' );
		if( ! function_exists( 'themify_get_featured_image_link' ) ) {
			require_once( THEMIFY_DIR . '/themify-template-tags.php' );
		}

		require_once( THEMIFY_DIR . '/themify-icon-picker/themify-icon-picker.php' );
	}
}

// register additional field types used by Builder
add_action( 'themify_metabox/field/page_builder', 'themify_meta_field_page_builder', 10, 1 );
add_action( 'themify_metabox/field/fontawesome', 'themify_meta_field_fontawesome', 10, 1 );
add_action( 'themify_metabox/field/query_category', 'themify_meta_field_query_category', 10, 1 );
add_action( 'themify_metabox/field/featimgdropdown', 'themify_meta_field_featimgdropdown', 10, 1 );

if ( ! function_exists( 'themify_builder_plugin_init' ) ) :
	/**
	 * Init Plugin
	 * called after theme to avoid redeclare function error
	 */
function themify_builder_plugin_init() {
	if ( class_exists('Themify_Builder') ) return;


	/**
	 * Define builder constant
	 */
	define( 'THEMIFY_BUILDER_VERSION', THEMIFY_VERSION );
	define( 'THEMIFY_BUILDER_VERSION_KEY', 'themify_builder_version' );
	define( 'THEMIFY_BUILDER_NAME', trim( dirname( plugin_basename( __FILE__) ), '/' ) );
	define( 'THEMIFY_BUILDER_SLUG', trim( plugin_basename( __FILE__), '/' ) );

	/**
	 * Layouts Constant
	 */
	define( 'THEMIFY_BUILDER_LAYOUTS_VERSION', '1.1.1' );

	// File Path
	define( 'THEMIFY_BUILDER_DIR', dirname(__FILE__) );
	define( 'THEMIFY_BUILDER_MODULES_DIR', THEMIFY_BUILDER_DIR . '/modules' );
	define( 'THEMIFY_BUILDER_TEMPLATES_DIR', THEMIFY_BUILDER_DIR . '/templates' );
	define( 'THEMIFY_BUILDER_CLASSES_DIR', THEMIFY_BUILDER_DIR . '/classes' );
	define( 'THEMIFY_BUILDER_INCLUDES_DIR', THEMIFY_BUILDER_DIR . '/includes' );
	define( 'THEMIFY_BUILDER_LIBRARIES_DIR', THEMIFY_BUILDER_INCLUDES_DIR . '/libraries' );

	// URI Constant
	define( 'THEMIFY_BUILDER_URI', plugins_url( '' , __FILE__ ) );
	define('THEMIFY_BUILDER_CSS_MODULES', THEMIFY_BUILDER_URI . '/css/modules/');
	define('THEMIFY_BUILDER_JS_MODULES', THEMIFY_BUILDER_URI . '/js/modules/');

	// Include files
	require_once( THEMIFY_BUILDER_CLASSES_DIR . '/class-themify-builder-model.php' );
	require_once( THEMIFY_BUILDER_CLASSES_DIR . '/class-themify-builder-layouts.php' );
	require_once( THEMIFY_BUILDER_CLASSES_DIR . '/class-themify-global-styles.php' );
	require_once( THEMIFY_BUILDER_CLASSES_DIR . '/class-themify-builder.php' );
	require_once( THEMIFY_BUILDER_CLASSES_DIR . '/class-themify-builder-options.php' );
	require_once( THEMIFY_DIR . '/cache/class-themify-cache.php' );
	require_once( THEMIFY_DIR . '/class-themify-enqueue.php' ); 
	require_once( THEMIFY_DIR . '/class-themify-access-role.php' );
	require_once( THEMIFY_DIR . '/class-themify-filesystem.php' );
	require_once( THEMIFY_DIR . '/class-themify-custom-fonts.php' );

	// Load Localization
	load_plugin_textdomain( 'themify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	Themify_Access_Role::get_instance();
	Themify_Enqueue_Assets::init();

	add_action( 'init', array( 'Themify_Custom_Fonts', 'init' ) );

	if ( Themify_Builder_Model::builder_check() ) {
		
		global $ThemifyBuilder;
		do_action( 'themify_builder_before_init' );
		$ThemifyBuilder = new Themify_Builder();
		add_action( 'init', array( $ThemifyBuilder,'init' ), 0 );
	}

	// register builder options page
	if ( class_exists( 'Themify_Builder_Options' ) ) {
		new Themify_Builder_Options();
	}

    if( is_admin() ){
        require_once THEMIFY_DIR . '/themify-admin.php';
		require_once( THEMIFY_DIR . '/themify-status.php' );
		Themify_System_Status::init();
    }

	/**
	 * Load class for mobile detection if it doesn't exist yet
	 * @since 1.6.8
	 */
	if ( ! class_exists( 'Themify_Mobile_Detect' ) ) {
		require_once THEMIFY_DIR . '/class-themify-mobile-detect.php';
		global $themify_mobile_detect;
		$themify_mobile_detect = new Themify_Mobile_Detect;
	}

	add_filter('script_loader_tag', 'themify_defer_js', 11, 3);
}
endif;

if ( ! function_exists( 'themify_builder_get_plugin_version' ) ) {
	/**
	 * Return plugin version.
	 *
	 * @since 1.4.2
	 *
	 * @return string
	 */
	function themify_builder_get_plugin_version() {
		static $version=null;
		if ($version===null) {
			$data = get_file_data( __FILE__, array( 'Version' ) );
			$version = $data[0];
		}
		return $version;
	}
}

if ( ! function_exists('themify_builder_edit_module_panel') ) {
	/**
	 * Hook edit module frontend panel
	 * @param $mod_name
	 * @param $mod_settings
	 */
	function themify_builder_edit_module_panel( $mod_name, $mod_settings ) {
		do_action( 'themify_builder_edit_module_panel', $mod_name, $mod_settings );
	}
}

/**
 * Adds themify-builder-* body class, showcasing Builder plugin's version
 *
 * @since 4.5.2
 * @return array
 */
function themify_builder_plugin_body_class( $classes ) {
	$classes[] = 'themify-builder-' . str_replace( '.', '-', themify_builder_get_plugin_version() );

	return $classes;
}
add_filter( 'body_class', 'themify_builder_plugin_body_class' );