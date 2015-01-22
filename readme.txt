=== Facebook Login Widget ===
Contributors: currinda
Tags: event management, login widget, association management
Requires at least: 4.1.0
Tested up to: 4.1.0
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a Currinda login widget.

== Description ==

This widget allows single login via Currinda. This uses the Currinda login and checks the user has a valid
registration or membership.

== Usage ==

Install the login widget in a sidebar and the login button will login user as a Wordpress user.
This user will have the subscriber role if considered a valid (unexpired) user in the Currinda database if
it is not it will have the subscriber role removed.

Use an access control plugin such as: https://wordpress.org/plugins/wordpress-access-control/ for controlling
access only to logged in users.


You can also use PHP code in any template which will provide an object with the representation of data
described here: http://currinda.com/support/api/v0.1.html
    
    $data = CurrindaLogin::instance()->getDetails()



== Installation ==


1. Install the plugin
2. Activate the plugin through the Plugins menu
3. Go to `Settings > Currinda Login`, and follow the instructions.
4. Add the Currinda Login widget to a menu or sidebar in Appearance > Widgets
5. Now visit your site and you will see the login form section.

== Support ==

1. Please email support@currinda.com for any queries



== Changelog ==

= 0.1 =
* Initial release.

