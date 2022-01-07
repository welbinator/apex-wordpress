<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tbp
 * @subpackage Tbp/admin
 * @author     Themify <themify@themify.me>
 */
final class Tbp_Admin {


	private static $currentPage=null;

	private static $builder_active=null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public static function run() {
	    add_action( 'admin_init', array( __CLASS__, 'init' ) );
		add_filter('themify_load_predesigned_templates',array('Tbp_Utils','load_predesigned_templates'),10);
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_menu' ), 11 );
		new Tbp_Templates();
		new Tbp_Themes();
	}
	
	public static function init(){
	    self::$builder_active = Themify_Builder_Model::is_front_builder_activate() && get_post_type(!empty(Themify_Builder::$builder_active_id)?Themify_Builder::$builder_active_id:get_the_ID())===Tbp_Templates::$post_type ? 'frontend' : (is_admin() && ('post-new.php' === $GLOBALS['pagenow'] || 'post.php' === $GLOBALS['pagenow']) && Themify_Builder_Model::hasAccess() ? 'backend' : false);
        if('frontend'===self::$builder_active || (isset($_REQUEST['post_type']) && $_REQUEST['post_type']===Tbp_Templates::$post_type)){
            self::$currentPage=Tbp_Templates::$post_type;
        }
		elseif(isset($_REQUEST['page']) && $_REQUEST['page']===Tbp_Themes::$post_type){
            self::$currentPage=Tbp_Themes::$post_type;
        }
		add_filter('themify_builder_ajax_admin_vars',array('Tbp_Utils','localize_predesigned_templates'));
		add_action('themify_builder_admin_enqueue', array( 'Tbp_Utils', 'load_tbp_active' ) );
	    add_filter('themify_module_categories', array('Tbp_Utils', 'module_categories'));
		add_action( 'frontend'===self::$builder_active?'wp_enqueue_scripts':'admin_enqueue_scripts', array( __CLASS__, 'register_scripts' ) );
	}

	public static function register_scripts(){
        $name = Tbp::get_plugin_name();
	    wp_register_script( $name.'-admin', themify_enque(TBP_URL. 'admin/js/tbp-admin.js'), array( 'jquery'), Tbp::get_version(), true );
	    if('frontend'===self::$builder_active){
            add_action( 'wp_footer', array( __CLASS__, 'enqueue_scripts' ) );
        }else{
            wp_localize_script( $name.'-admin', 'tbpAdminVars', array(
                'i18n' => array(
                    'import' => __( 'Import Demo', 'tbp' ),
                    'import_warning' => __( 'Warning: this will import the demo posts, pages, menus, etc. as per our demo. It may take a few minutes. You can erase demo on Pro Themes > Theme > Theme Details.', 'tbp' ),
                )
            ) );
            $screen = get_current_screen();
            if (
                ( ($screen->base === 'edit' || 'backend'===self::$builder_active) && $screen->post_type === Tbp_Templates::$post_type )
                || self::$currentPage === Tbp_Themes::$post_type
            ) {
                add_action( 'admin_footer', array( __CLASS__, 'enqueue_scripts' ) );
            }
        }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue_scripts() {
	    /**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tbp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tbp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$name=Tbp::get_plugin_name();
		$v = Tbp::get_version();
        if(false===self::$builder_active) {
            if(current_user_can('upload_files') ) {
                wp_enqueue_media();
            }
            if(false === wp_script_is( 'themify-metabox','enqueued' )){
                wp_enqueue_style( 'themify-metabox' );
                wp_enqueue_script( 'themify-metabox' );
                wp_enqueue_script( 'themify-plupload' );
                do_action( 'themify_metabox_enqueue_assets' );
            }
            wp_enqueue_script('themify-main-script', themify_enque(THEMIFY_URI . '/js/main.js'), null, THEMIFY_VERSION, true);
            wp_localize_script('themify-main-script', '_tbp_admin', self::localize_vars());
            wp_enqueue_style( 'tf-base', THEMIFY_URI . '/css/base.min.css', null, THEMIFY_VERSION );
            wp_enqueue_style( 'themify-ui' );
            themify_get_icon( 'help','ti' ); // load ti-help svg graphic
            wp_enqueue_style( $name, themify_enque(TBP_URL . 'admin/css/tbp-admin.css'), array(), $v, 'all' );
        }
		
		if ( ! wp_style_is( 'themify-icons' ) ) {
			wp_enqueue_style( 'themify-icons', themify_enque(THEMIFY_URI . '/themify-icons/themify-icons.css'), array(), THEMIFY_VERSION );
		}
		if(false!==self::$builder_active){
            wp_localize_script('tbp-admin', '_tbp_admin', self::localize_vars());
		    if('frontend'===self::$builder_active){
                Tbp_Templates::enqueue_scripts();
            }
        }
		wp_enqueue_script($name.'-admin');
		include( TBP_DIR . 'admin/partials/lightbox-tpl.php' );
        if(false===self::$builder_active) {
            // Init Pointers
            TBP_Pointers::run();    
        }
	}

	public static function localize_vars(){
        $labels = Themify_Builder::get_i18n();
        $labels['label']['browse_image'] = __('Add Image','tbp');
	    $vars = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'tb_load_nonce' => wp_create_nonce('tb_load_nonce'),
            'type'=>self::$currentPage,
            'labels'=>$labels['label']
        );
        $labels=null;
	    if(false===self::$builder_active){
	        $button=null;
            if(current_user_can( 'manage_options' )){
                wp_enqueue_script( 'themify-plupload' );
                $button = themify_get_uploader('tbp-themes-import', array(
                        'label'		=> __('Import', 'tbp'),
                        'preset'	=> false,
                        'preview'   => false,
                        'tomedia'	=> false,
                        'topost'	=> '',
                        'fields'	=> '',
                        'featured'	=> '',
                        'message'	=> '',
                        'fallback'	=> '',
                        'dragfiles' => false,
                        'confirm'	=> false,
                        'medialib'	=> false,
                        'formats'	=> 'zip,txt',
                        'type'		=> '',
                        'action'    => self::$currentPage.'_plupload',
                    )
                );
            }
            $ph_image = 'tbp_theme' === self::$currentPage ? 'theme' : 'template';
            $vars['includes_url']=includes_url();
            $vars['meta_url']=THEMIFY_METABOX_URI;
            $vars['tbAppUrl']=themify_enque(THEMIFY_BUILDER_URI . '/js/editor/themify-builder-app.js');
            $vars['constructorUrl']=themify_enque(THEMIFY_BUILDER_URI . '/js/editor/themify-constructor.js');
            $vars['builderToolbarUrl']=themify_enque(THEMIFY_BUILDER_URI . '/css/editor/toolbar.css');
            $vars['builderCombineUrl']=themify_enque(THEMIFY_BUILDER_URI . '/css/editor/combine.css');
            $vars['import_nonce']=wp_create_nonce('themify_builder_import_filethemify-builder-plupload');
            $vars['v']=THEMIFY_VERSION;
            $vars['import_btn']=$button;
            $vars['ph_image']=TBP_URL  . '/admin/img/'.$ph_image.'-placeholder.png';
        }else{
            $vars['admin_css']=themify_enque(TBP_URL . 'admin/css/tbp-admin.css');
        }
        return $vars;
    }
	
	public static function register_admin_menu() {
		    global $submenu;
			$menu_id = themify_is_themify_theme() ? 'themify' : 'themify-builder';
			if(empty($submenu[$menu_id])){
				return;
			}
			$label = '<span class="update-plugins"><span class="plugin-count" aria-hidden="true">PRO</span></span>';
		    add_submenu_page( $menu_id, esc_html__( 'Themes ', 'tbp' ), sprintf(__( '%s Themes', 'tbp' ),$label), 'edit_posts', Tbp_Themes::$post_type , array( 'Tbp_Themes', 'render_page' ) );
		    end($submenu[$menu_id]);
		    Tbp_Utils::move_array_index( $submenu[$menu_id], key($submenu[$menu_id]), 1 );
		    add_submenu_page( $menu_id, esc_html__( 'Templates', 'tbp' ), sprintf(__( '%s Templates', 'tbp' ),$label), 'edit_posts', 'edit.php?post_type='.Tbp_Templates::$post_type );
		    end($submenu[$menu_id]);
		    Tbp_Utils::move_array_index( $submenu[$menu_id], key($submenu[$menu_id]), 2 );
	}

}
