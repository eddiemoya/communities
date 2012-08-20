<?php /*
Plugin Name: Custom Comment Types
Plugin URI: http://eddiemoya.com/
Version: 0.2
Description: Don't be trapped into a single comment type, create your own!
Author: Eddie Moya
Author URI: http://eddiemoya.com/
 */

/**
 * @todo Clean up post_type in comments model - needs to handle arrays.
 * @todo Verify conditions work correctly for when the custom comments kick in.
 * @todo Have the cct controller loop through comments configured through the admin.
 * @todo Nice to have - add filtering links
 * @todo Comment Types (including builtin) filters at top of page are not respecting query string for comment_type.
 *  
 */
define(CCT_PATH, plugin_dir_path(__FILE__));

define(CCT_CONTROLLERS,     CCT_PATH . 'controllers/');
define(CCT_MODELS,          CCT_PATH . 'models/');
define(CCT_VIEWS,           CCT_PATH . 'views/');
define(CCT_LIBRARY,         CCT_PATH . 'library/');
define(CCT_ASSETS,          CCT_PATH . 'assets/');

require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php');


include (CCT_LIBRARY        . 'custom-comment-list-table.php');
include (CCT_CONTROLLERS    . 'cct-controller-plugin-settings.php');
include (CCT_CONTROLLERS    . 'cct-controller-comment-types.php');
include (CCT_MODELS         . 'cct-model-comment-type.php');


//Rather make this static, but too lazy.
$theme_options = new CCT_Controller_Plugin_Settings();

CCT_Controller_Comment_Types::init();
