<?php
/**
 * Plugin Name: Currinda Auth
 * Description: Currinda authentication and authorization widget for Wordpress.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Author: Currinda
 * Author URI: http://currinda.com
 * Text Domain: currinda-auth-widget
 */

if ( ! defined( 'WPINC' ) ) {
   die;
}

/**
 * Current plugin version.
 * Refer to SemVer - https://semver.org
 * Update this as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-currinda-auth-activator.php
 */
if ( !function_exists('currinda_auth_activate') ) {
   function currinda_auth_activate() {
      require_once plugin_dir_path( __FILE__ ) . 'includes/class-currinda-auth-activator.php';
      Currinda_Auth_Activator::activate();
   }
}

register_activation_hook(__FILE__, 'currinda_auth_activate');

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-currinda-auth-eactivator.php
 */
if ( !function_exists('currinda_auth_deactivate') ) {
   function currinda_auth_deactivate() {
      require_once plugin_dir_path( __FILE__ ) . 'includes/class-currinda-auth-deactivator.php';
      Currinda_Auth_Deactivator::deactivate();
   }
}

register_deactivation_hook(__FILE__, 'currinda_auth_deactivate');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-currinda-auth.php';

/**
 * Initialization of the plugin.
 * 
 * It initializes admin settings, enqeueing assets and
 * runs the plugin.
 */
if ( !function_exists('currinda_auth_init') ) {
   function currinda_auth_init() {
      $plugin = new Currinda_Auth();
      // $plugin->init();
   }
   currinda_auth_init();
}