<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       jeeglo.com
 * @since      1.0.0
 *
 * @package    LeadKitPro
 * @subpackage LeadKitPro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    LeadKitPro
 * @subpackage LeadKitPro/includes
 * @author     Jeeglo <shikeb.ali@jeeglo.com>
 */
class LeadKitPro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      LeadKitPro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'LKPR_PLUGIN_VERSION' ) ) {
			$this->version = LKPR_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'leadkitpro';

		$this->load_lkpr_dependencies();
		$this->set_lkpr_locale();
		$this->define_lkpr_admin_hooks();
		$this->define_lkpr_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - LeadKitPro_Loader. Orchestrates the hooks of the plugin.
	 * - LeadKitPro_i18n. Defines internationalization functionality.
	 * - LeadKitPro_Admin. Defines all hooks for the admin area.
	 * - LeadKitPro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_lkpr_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-leadkitpro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-leadkitpro-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-leadkitpro-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-leadkitpro-public.php';

		$this->loader = new LeadKitPro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the LeadKitPro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_lkpr_locale() {

		$plugin_i18n = new LeadKitPro_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_lkpr_admin_hooks() {

		$plugin_admin = new LeadKitPro_Admin( $this->lkpr_get_plugin_name(), $this->lkpr_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'lkpr_admin_enqueue_styles' );
		// Add menu item
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'lkpr_admin_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'lkpr_admin_plugin_menu' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'lkpr_add_meta_box' );
		$this->loader->add_action( 'wp_nonce_field', $plugin_admin, 'lkpr_meta_box_callback' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'lkpr_save_campaign' );
		$this->loader->add_action( 'admin_post_lcd_verfiy_api', $plugin_admin, 'lcd_verfiy_api_key' );
		$this->loader->add_action( 'wp_ajax_refresh_campaigns', $plugin_admin, 'refresh_campaigns' );
		$this->loader->add_action( 'wp_ajax_lkpr_clear_all_cache_data', $plugin_admin, 'lkpr_clear_all_cache_data' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_lkpr_public_hooks() {

		$plugin_public = new LeadKitPro_Public( $this->lkpr_get_plugin_name(), $this->lkpr_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'lkpr_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'lkpr_public_enqueue_scripts' );
		$this->loader->add_filter( 'page_template', $plugin_public, 'show_leadkitpro_template' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function lkpr_run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function lkpr_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    LeadKitPro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_lkpr_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function lkpr_get_version() {
		return $this->version;
	}


}
