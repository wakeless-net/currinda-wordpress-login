<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://currinda.com
 * @since      1.0.0
 *
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/includes
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
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/includes
 * @author     Currinda
 */
class Currinda_Auth {

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

        $this->version = ( defined( 'PLUGIN_NAME_VERSION' ) ) ? 
            PLUGIN_NAME_VERSION : '1.0.0';

        $this->plugin_name = 'currinda-auth';
        
        $this->load_dependencies();
        $this->init_admin();

    }

    /**
     * Loads dependencies
     * 
     * Loads all dependencies required to run
     * the plugin.
     */
    public function load_dependencies() {

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-currinda-auth-admin.php';
        
    }
    
    /**
     * Initialize admin-specific functionalities
     * 
     * Register admin hooks, filters and settings
     * of the plugin.
     */
    public function init_admin() {

        $plugin_admin = new Currinda_Auth_Admin( $this->plugin_name, $this->version );

        $plugin_admin->init();
        
    }
}