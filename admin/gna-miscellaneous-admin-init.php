<?php
/* 
 * Inits the admin dashboard side of things.
 * Main admin file which loads all settings panels and sets up admin menus. 
 */
if (!class_exists('GNA_Miscellaneous_Admin_Init')) {
	class GNA_Miscellaneous_Admin_Init {
		var $main_menu_page;
		var $settings_menu;

		public function __construct() {
			$this->admin_includes();
			add_action('admin_menu', array(&$this, 'create_admin_menus'));

			if ( isset($_GET['page']) && (strpos($_GET['page'], GNA_MISCELLANEOUS_MENU_SLUG_PREFIX ) !== false) ) {
				add_action('admin_print_scripts', array(&$this, 'admin_menu_page_scripts'));
				add_action('admin_print_styles', array(&$this, 'admin_menu_page_styles'));
			}
		}

		public function admin_menu_page_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('postbox');
			wp_enqueue_script('dashboard');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('gna-m-script', GNA_MISCELLANEOUS_URL. '/assets/js/gna-miscellaneous.js', array(), GNA_MISCELLANEOUS_VERSION);
		}

		function admin_menu_page_styles() {
			wp_enqueue_style('dashboard');
			wp_enqueue_style('thickbox');
			wp_enqueue_style('global');
			wp_enqueue_style('wp-admin');
			wp_enqueue_style('gna-miscellaneous-admin-css', GNA_MISCELLANEOUS_URL. '/assets/css/gna-miscellaneous.css');
		}

		public function admin_includes() {
			include_once('gna-miscellaneous-admin-menu.php');
		}

		public function create_admin_menus() {
			$this->main_menu_page = add_menu_page( __('Miscellaneous', 'gna-miscellaneous'), __('Miscellaneous', 'gna-miscellaneous'), 'manage_options', 'gna-m-settings-menu', array(&$this, 'handle_settings_menu_rendering'), GNA_MISCELLANEOUS_URL . '/assets/images/gna_20x20.png' );

			add_submenu_page('gna-m-settings-menu', __('Settings', 'gna-miscellaneous'),  __('Settings', 'gna-miscellaneous'), 'manage_options', 'gna-m-settings-menu', array(&$this, 'handle_settings_menu_rendering'));

			add_action( 'admin_init', array(&$this, 'register_gna_miscellaneous_settings') );
		}

		public function register_gna_miscellaneous_settings() {
			register_setting( 'gna-miscellaneous-setting-group', 'g_miscellaneous_configs' );
		}

		public function handle_settings_menu_rendering() {
			include_once('gna-miscellaneous-admin-settings-menu.php');
			$this->settings_menu = new GNA_Miscellaneous_Settings_Menu();
		}
	}
}
