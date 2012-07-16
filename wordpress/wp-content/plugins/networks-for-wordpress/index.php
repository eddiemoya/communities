<?php

/**
 * Plugin Name: Networks for WordPress
 * Plugin URI: http://www.jerseyconnect.net/development/networks-for-wordpress/
 * Description: Adds a Networks panel for site admins to create and manipulate multiple networks.
 * Version: 1.1.2
 * Revision Date: 05/13/2012
 * Requires at least: WP 3.0
 * Tested up to: WP 3.4-beta4
 * License: GNU General Public License 2.0 (GPL) or later
 * Author: David Dean
 * Author URI: http://www.generalthreat.com/
 * Site Wide Only: True
 * Network: True
*/

require_once (dirname(__FILE__) . '/networks-admin-ajax.php');
require_once (dirname(__FILE__) . '/networks-functions.php');
require_once (dirname(__FILE__) . '/networks-admin.php');

if( ! defined( 'RESTRICT_MANAGEMENT_TO' ) ) {
	
	/** Enter an ID here (or in your wp-config.php file) to restrict the Networks panel to only the specified network (site ID)
	 * FALSE (or 0) will disable the feature
	 */
	define( 'RESTRICT_MANAGEMENT_TO', FALSE );
}

if(!defined('ENABLE_HOLDING_SITE')) {

	/** 
	 * true = enable the holding site; must be true to save orphaned blogs, below
	 * false = no site 0, no ability to "unassign" blogs without reassigning them
	 */
	define('ENABLE_HOLDING_SITE',TRUE);
}

if (!defined('RESCUE_ORPHANED_BLOGS')) {

	/** 
	   true = redirect blogs from deleted site to holding site instead of deleting them.  Requires holding site above.
	   false = allow blogs belonging to deleted sites to be deleted.
	 */
	define('RESCUE_ORPHANED_BLOGS',FALSE);
}

/** 
 * blog options affected by URL 
 */
$url_dependent_blog_options = array('siteurl','home','fileupload_url');

/** 
 * site / network options affected by URL 
 */
$url_dependent_site_options = array('siteurl');

/** 
 * Sitemeta options to be copied on clone 
 */
$options_to_copy = array(
	'admin_email',
	'admin_user_id',
	'allowed_themes',
	'allowedthemes',
	'banned_email_domains',
	'first_post',
	'limited_email_domains',
	'site_admins',
	'upload_filetypes',
	'welcome_email'
);

function njsl_networks_init() {
	
	global $current_site;
	if( RESTRICT_MANAGEMENT_TO && RESTRICT_MANAGEMENT_TO != $current_site->id ) {
		return;
	}
	
	$njslNetworks = new wp_Networks_Admin();
	add_action( 'wp_ajax_check_domain', 'networks_check_domain' );
}

add_action( 'init', 'njsl_networks_init' );

?>