<?php

/**
 * Currinda Auth Form Widget.
 *
 * @link       http://currinda.com
 * @since      1.0.0
 *
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/admin
 */

/**
 * Currinda Auth Form Widget.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/admin
 * @author     Currinda 
 */
class Currinda_Auth_Widget extends WP_Widget
{

    /**
     * WP Error instance - collects errors from requests.
     *
     * @var [type]
     */
    protected $error;

    /**
     * Fetch and create a new Currinda User
     * instance.
     *
     * @var [type]
     */
    protected $user;

    /**
     * Initialize Widget
     * 
     * @return void
     */
    public function __construct()
    {

        parent::__construct(
            'currinda_auth_widget',
            'Currinda Auth',
            array('description' => __('Currinda Auth', 'text_domain'))
        );

        add_action('init', array($this, 'init'));
    }

    public function init()
    {
        if (isset($_POST['currinda_auth_submit'])) {

            require_once plugin_dir_path(__DIR__) . '/includes/class-currinda-auth-user.php';
            require_once plugin_dir_path(__DIR__) . '/includes/class-currinda-auth-provider.php';

            $provider = new Currinda_Auth_Provider(true);
            $membership = $provider->authenticate($email = $_POST['currinda_auth_email'], $_POST['currinda_auth_password'], get_option('currinda_auth_org_id'));

            if (!is_wp_error($membership)) {

                if (email_exists($email)) {
                    $user = get_user_by('email', $email);
                    $userId = $user->ID;
                } else {
                    $userId = wp_create_user($email, wp_generate_password('10', false), $email);
                    wp_update_user(array(
                        "ID" => $userId,
                        'user_nicename' => $membership->FirstName,
                        'display_name' => $membership->FirstName
                    ));
                    $user = new WP_User($userId);
                }

                update_user_meta($userId, 'currinda_membership', $membership);
                $this->user = new Currinda_Auth_User($membership);

                if ($this->user->check_valid_record()) {
                    $user->add_role(get_option('currinda_auth_app_role'));
                } else {
                    $user->remove_role(get_option('currinda_auth_app_role'));
                }

                if ($this->user->is_membership_expired()) {

                    if ($this->user->is_membership_overdue()) {
                    
                        $this->error = new WP_Error();
                        $this->error->add(400, "<p>Your membership fees are outstanding. <a href='" . get_option('currinda_auth_redirect_membership_outstanding') . "'>Click here to make a payment.</a></p>");
                    
                    } else {
                        $this->error = new WP_Error();
                        $this->error->add(400, "<p>The current membership has expired. <a href='" . get_option('currinda_auth_redirect_membership_expired') . "'>Click here to renew.</a></p>");
                    }
                    
                } else if ($this->user->is_membership_unapproved()) {
                    $this->error = new WP_Error();
                    $this->error->add(400, "<p>Your membership is not yet active yet. <a href='" . get_option('currinda_auth_redirect_membership_inactive') . "'>Contact the member administrator to get them to approve your account.</a></p>");
                }

                if (!is_wp_error($this->error)) {
                    wp_set_current_user($userId, $user->user_login);
                    wp_set_auth_cookie($userId, true);
                    do_action('wp_login', $user->user_login);
                }

            } else {
                $this->error = $membership;
            }
        }
    }

    /**
     * Render Form
     * 
     * Renders the auth form to the front-end.
     *
     * @return void
     */
    public function widget($args, $instance)
    {

        extract($args);


        if (!is_user_logged_in()) {

            if (is_wp_error($this->error)) {
                if ($this->error->has_errors()) {
                    foreach ($this->error->get_error_messages() as $message) {
                        echo '<div id="message" class="error">' . $message . '</div>';
                    }
                }
            }

            echo $before_widget;
            require_once plugin_dir_path(__DIR__) . '/public/partials/currinda-auth-form.php';
            echo $after_widget;
        }
    }

    /**
     * Render Widget form
     * 
     * Renders the form that will be displayed
     * when using this widget.
     *
     * @return void
     */
    public function form($instance)
    {

        $title = isset($instance['title']) ? $instance['title'] : '';
?>
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
<?php
    }

    /**
     * Updates widget
     * 
     * Saves widget changes.
     *
     * @return void
     */
    public function update($new_instance, $old_instance)
    {

        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ?
            strip_tags($new_instance['title']) : '';

        return $instance;
    }
}
