<?php
/**
 * Provide a admin area view for the plugin settings
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://currinda.com
 * @since      1.0.0
 *
 * @package    Currinda_Auth
 * @subpackage Currinda_Auth/admin/partials
 */
?>

<div class="wrap">
    <form method="POST" action="options.php">  
        <?php settings_fields('currinda-auth'); ?>
        <?php do_settings_sections( 'currinda-auth' ); ?>
        <?php submit_button(); ?>  
    </form> 
</div>