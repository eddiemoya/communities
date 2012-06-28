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



    function register_questions_type() {
        $labels = array(
            'name' => _x('Questions', 'post type general name'),
            'singular_name' => _x('Questions', 'post type singular name'),
            'add_new' => _x('Add New', 'question'),
            'add_new_item' => __('Add New Question'),
            'edit_item' => __('Edit Question'),
            'new_item' => __('New Question'),
            'all_items' => __('All Questions'),
            'view_item' => __('View Question'),
            'search_items' => __('Search Questions'),
            'not_found' => __('No questions found'),
            'not_found_in_trash' => __('No questions found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => 'Questions'
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'author', 'comments')
        );
        register_post_type('question', $args);
    }
    
 add_action( 'init', 'register_questions_type' );
    
 
 
function cct_test() {
    $args = array(
        'labels' => array(
            'name' => _x('Answers', 'post type general name'),
            'singular_name' => _x('Answer', 'post type singular name'),
            'add_new' => _x('Add New', 'answer'),
            'add_new_item' => __('Add New Answer'),
            'edit_item' => __('Edit Answer'),
            'new_item' => __('New Answer'),
            'all_items' => __('All Answers'),
            'view_item' => __('View Answers'),
            'search_items' => __('Search Answers'),
            'not_found' => __('No answers found'),
            'not_found_in_trash' => __('No answers found in Trash'),
            'parent_item_colon' => 'Question:',
            'menu_name' => 'Answer'
        ),
        'parent_domain' => 'post',
        'parent_type' => 'question',
        'capability' => 'administrator',
        'menu_position' => 28
    );

    CCT_Controller_Comment_Types::register_comment_type('answer', $args);
}

add_action('init', 'cct_test');