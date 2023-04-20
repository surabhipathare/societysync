<?php
/*
Plugin Name: Apartment Management System
Plugin URI: http://www.mobilewebs.net/mojoomla/extend/wordpress/apartment/
Description: Apartment Management System for wordpress plugin is ideal way to manage complete housing society or neighborhood maintenance tasks. It has different user roles like Admin, Resident members, Gatekeeper and Accountant Users.
Version: 31.0(30-12-2020)
Author: Mojoomla
Author URI: http://codecanyon.net/user/dasinfomedia
Text Domain: apartment_mgt
Domain Path: /languages/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Copyright 2015  Mojoomla  (email : sales@mojoomla.com)
*/
define( 'AMS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'AMS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'AMS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'AMS_CONTENT_URL',  content_url( ));
require_once AMS_PLUGIN_DIR . '/settings.php';
?>