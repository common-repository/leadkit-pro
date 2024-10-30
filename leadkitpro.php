<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              leadkit.pro
 * @since             1.0.0
 * @package           LeadKit PRO
 *
 * @wordpress-plugin
 * Plugin Name:       LeadKit PRO
 * Plugin URI:        leadkit.pro
 * Description:       Everything You Need To Create Amazing Lead Magnets & Complete Lead Capture Funnels!
 * Version:           1.0.0
 * Author:            LeadKit PRO
 * Author URI:        leadkit.pro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       leadkit-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$plugin_version = $plugin_data['Version'];

define( 'LKPR_PLUGIN_VERSION', $plugin_version );


// LeadKit PRO Plugin's name
if (!defined('LKPR_PLUGIN_NAME'))
    define('LKPR_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

// LeadKit PRO Plugin's directory path
if (!defined('LKPR_PLUGIN_DIR'))
    define('LKPR_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . LKPR_PLUGIN_NAME);

// LeadKit PRO Plugin's URL
if (!defined('LKPR_PLUGIN_URL'))
    define('LKPR_PLUGIN_URL', WP_PLUGIN_URL . '/' . LKPR_PLUGIN_NAME);

// LeadKit PRO API URL
if (!defined('LKPR_API_URL'))
    if (is_ssl()) {
    	define('LKPR_API_URL', 'https://app.leadkit.pro/api/v1/');
  	} else {
    	define('LKPR_API_URL', 'http://app.leadkit.pro/api/v1/');
  	}
  	

// LeadKit PRO App URL
if (!defined('LKPR_APP_URL'))
    if (is_ssl()) {
    	define('LKPR_APP_URL', 'https://app.leadkit.pro/');
    } else {
    	define('LKPR_APP_URL', 'http://app.leadkit.pro/');
    }

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-leadkitpro-activator.php
 */
function activate_leadkitpro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leadkitpro-activator.php';
	LeadKitPro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-leadkitpro-deactivator.php
 */
function deactivate_leadkitpro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leadkitpro-deactivator.php';
	LeadKitPro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_leadkitpro' );
register_deactivation_hook( __FILE__, 'deactivate_leadkitpro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-leadkitpro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_leadkitpro() {

	$plugin = new LeadKitPro();
	$plugin->lkpr_run();

}
run_leadkitpro();
