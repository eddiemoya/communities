<?php

//Register SK Category taxonomy
add_action( 'init', 'register_sk_category', 0 );

function register_sk_category() {
 
  $labels = array(
    'name'                => _x( 'SKCategories', 'taxonomy general name' ),
    'singular_name'       => _x( 'SKCategory', 'taxonomy singular name' ),
    'search_items'        => __( 'Search SKCategories' ),
    'all_items'           => __( 'All SKCategories' ),
    'parent_item'         => __( 'Parent SKCategory' ),
    'parent_item_colon'   => __( 'Parent SKCategory:' ),
    'edit_item'           => __( 'Edit SKCategory' ), 
    'update_item'         => __( 'Update SKCategory' ),
    'add_new_item'        => __( 'Add New SKCategory' ),
    'new_item_name'       => __( 'New SKCategory Name' ),
    'menu_name'           => __( 'SKCategory' )
  ); 	

  $args = array(
    'hierarchical'        => true,
    'labels'              => $labels,
    'show_ui'             => true,
    'show_admin_column'   => true,
    'query_var'           => true,
    'rewrite'             => array( 'slug' => 'skcategory' )
  );
  
  register_taxonomy( 'skcategory', array('post', 'guide'), $args );
  
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//If JSON API plugin is active, hook in new controllers 
if(is_plugin_active('comm-json-api/json-api.php')) {
	
	// Add a custom controller
	add_filter('json_api_controllers', 'add_my_controller');
	
	function add_my_controller($controllers) {
	  $controllers[] = 'Custom_Taxonomy';
	  return $controllers;
	}
	
	// Register the source file for our controller
	add_filter('json_api_custom_taxonomy_controller_path', 'custom_taxonomy_controller_path');
	
	function custom_taxonomy_controller_path($default_path) {
	  return get_stylesheet_directory() . '/classes/json_api_custom_taxonomy_controller.php';
	}

}