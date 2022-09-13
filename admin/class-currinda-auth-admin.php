<?php

/**
 * Currinda Auth admin-specific functionality.
 *
 * @link       http://currinda.com
 * @since      1.0.0
 *
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/admin
 */

/**
 * Currinda Auth admin-specific functionality.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/admin
 * @author     Currinda 
 */
class Currinda_Auth_Admin {

    /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $version;
    
    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

    }
    
    /**
     * Initialize admin
     * 
     * Initializes plugin options page. Registers page
     * and setting fields.
     * 
     * @return void
     */
    public function init() {

        if( is_admin() ) {
            add_action( 'admin_menu', array($this, 'create_options_page') );
            add_action( 'admin_init', array($this, 'options_page_init') );   
        }

        add_action( 'widgets_init', array($this, 'register_widget') );
        
    }

    public function register_widget() {

        require_once dirname( __FILE__ ) . '/class-currinda-auth-widget.php';

        register_widget( 'Currinda_Auth_Widget' );

    }

    /**
     * Register admin settings
     * 
     * Adds options page and renders
     * settings form. The page is under "Settings"
     * page.
     *
     * @return void
     */
    public function create_options_page() {

        add_options_page(
            'Currinda Authentication Settings', 
            'Currinda Auth', 
            'administrator', 
            $this->plugin_name, 
            array( $this, 'render_settings_form' )
        );
        
    }

    /**
     * Render admin settings form
     * 
     * @return void
     */
    public function render_settings_form() {

        require_once dirname( __FILE__ ) . '/partials/currinda-auth-settings-form.php';

    }

    /**
     * Initialize options page
     * 
     * Create and register option setting fields
     * 
     * @return void
     */
    public function options_page_init() {

        $settings = array(
            'currinda_auth_domain'                          =>  'Currinda Domain',
            'currinda_auth_app_id'                          =>  'App ID',
            'currinda_auth_app_secret'                      =>  'Secret Key',
            'currinda_auth_org_id'                          =>  'Organisation ID',
            'currinda_auth_app_role'                        =>  'Default Role',
            'currinda_auth_redirect_membership_inactive'    =>  'Redirect URL for Inactive Memberships',
            'currinda_auth_redirect_membership_expired'     =>  'Redirect URL for Expired Memberships',
            'currinda_auth_redirect_membership_outstanding' =>  'Redirect URL for Outstanding Memberships',
        );

        foreach ( $settings as $key => $value ) {
            register_setting(
                $this->plugin_name, 
                $key, 
                array( $this, 'sanitize_field' ) 
            );    
        }
        
        add_settings_section(
            'currinda_auth_section',
            'Currinda Authentication Settings',
            array( $this, 'print_section_description' ),
            $this->plugin_name
        );  

        foreach ( $settings as $key => $value ) {
            add_settings_field(
                $key,
                $value,
                array( $this, 'print_field_' . $key), 
                $this->plugin_name,
                'currinda_auth_section',
                array('key' => $key)     
            );
        }
    }

    /**
     * Print Section Description
     * 
     * @return void
     */
    public function print_section_description() {
        print 'Settings for Currinda Auth';
    }

    /**
     * Print Domain field
     */
    public function print_field_currinda_auth_domain(array $args) {

        $value = get_option( $args['key'] );

        printf(
            '<input type="text" name="' . $args['key'] . '" value="%s" placeholder="asn.currinda.com" />',
            isset( $value ) ? esc_attr( $value ) : ''
        ); 
    }

    /**
     * Print Domain field
     */
    public function print_field_currinda_auth_app_id(array $args) {

        $value = get_option( $args['key'] );

        printf(
            '<input type="text" name="' . $args['key'] . '" value="%s" placeholder="App ID" />',
            isset( $value ) ? esc_attr( $value ) : ''
        ); 
    }

    /**
     * Print Domain field
     */
    public function print_field_currinda_auth_org_id(array $args) {
        $value = get_option( $args['key'] );

        printf(
            '<input type="text" name="' . $args['key'] . '" value="%s" placeholder="Organisation ID" />',
            isset( $value ) ? esc_attr( $value ) : ''
        ); 
    }

    /**
     * Print Domain field
     */
    public function print_field_currinda_auth_app_secret(array $args) {
        $value = get_option( $args['key'] );

        printf(
            '<input type="text" name="' . $args['key'] . '" value="%s" placeholder="App Secret Key" />',
            isset( $value ) ? esc_attr( $value ) : ''
        ); 
    }

    /**
     * Print Domain field
     */
    public function print_field_currinda_auth_app_role(array $args) {

        $value = get_option( $args['key'] );
        $defaultValue = isset( $value ) ? $value : 'subscriber';
    ?>
        <select name="<?php echo $args['key'] ?>">
            <?php wp_dropdown_roles( $value ); ?>
        </select>
    <?php
    }

    /**
     * Print Domain field
     */
    public function print_field_currinda_auth_redirect_membership_inactive(array $args) {
        $value = get_option( $args['key'] );

        printf(
            '<input type="text" name="' . $args['key'] . '" value="%s" placeholder="http://asn.currinda.com" />',
            isset( $value ) ? esc_attr( $value ) : ''
        ); 
    }

    /**
     * Print Domain field
     */
    public function print_field_currinda_auth_redirect_membership_expired(array $args) {
        $value = get_option( $args['key'] );

        printf(
            '<input type="text" name="' . $args['key'] . '" value="%s" placeholder="http://asn.currinda.com" />',
            isset( $value ) ? esc_attr( $value ) : ''
        ); 
    }

    /**
     * Print Domain field
     */
    public function print_field_currinda_auth_redirect_membership_outstanding(array $args) {
        $value = get_option( $args['key'] );

        printf(
            '<input type="text" name="' . $args['key'] . '" value="%s" placeholder="http://asn.currinda.com" />',
            isset( $value ) ? esc_attr( $value ) : ''
        ); 
    }

    /**
     * Sanitize input
     * 
     * Sanitizes each field inputs by user to 
     * prevent security issues.
     * 
     * @param array $input
     */
    public function sanitize_field( $input ) {
        return $input;
    }

}