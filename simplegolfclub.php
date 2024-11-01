<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://simplegolfclub.com/
 * @since             1.0.0
 * @package           Simplegolfclub
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Golf Club
 * Plugin URI:        https://simplegolfclub.com/
 * Description:       Simple Golf Club provides an easy way you to coordinate golf outings for your friends or for large groups of people. Put together a team, organize groups, verify availability, create events, and keep track of scores.
 * Version:           1.7.0b
 * Author:            Matthew Linton
 * Author URI:        https://gitlab.com/mlinton/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simplegolfclub
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SGC_VERSION', '1.7.0b' );

/**
 * Text domain for this plugin
 */
define('SGC_TEXTDOMAIN', 'simplegolfclub');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sgc-activator.php
 */
function activate_simplegolfclub() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sgc-activator.php';
	Simplegolfclub_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sgc-deactivator.php
 */
function deactivate_simplegolfclub() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sgc-deactivator.php';
	Simplegolfclub_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simplegolfclub' );
register_deactivation_hook( __FILE__, 'deactivate_simplegolfclub' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sgc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simplegolfclub() {

	$plugin = new Simplegolfclub();
	$plugin->run();

}
run_simplegolfclub();
