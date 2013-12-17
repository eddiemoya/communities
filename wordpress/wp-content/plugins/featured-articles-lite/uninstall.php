<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ){
	exit();
}
	
global $wpdb, $wp_roles;
// complete uninstall is optional so check if is set
$o = get_option('feat_art_options', array());
if( isset($o['complete_uninstall']) && $o['complete_uninstall'] == 1 ){	
	// delete custom posts and associated meta values
	$custom_posts = array('fa_slider', 'fa_slide');
	foreach( $custom_posts as $custom_post ){
		$args = array(
			'post_type'=> $custom_post,
			'post_status'=>'any',
			'posts_per_page'=>'-1'
		);
		$query = new WP_Query($args);
		$postslist = $query->posts;
		foreach( $postslist as $post ){
			wp_delete_post($post->ID, true);
		}
	}
	
	// delete meta keys that get set on post/pages	
	$meta_keys = array(
		'_fa_lite_%_featured_ord',
		'_fa_image',
		'_fa_image_autodetect',
		'_fa_cust_title',
		'_fa_cust_link',
		'_fa_cust_class',
		'_fa_cust_txt'
	);
	$condition = "meta_key LIKE '".implode("' OR meta_key LIKE '", $meta_keys )."'";	
	$sql = "DELETE FROM $wpdb->postmeta WHERE ".$condition;
	$wpdb->query($sql);
	
	// delete options
	$wp_options = array(
		'fa_lite_categories',
		'fa_lite_pages',
		'feat_art_options',
		'fa_lite_home',
		'fa_plugin_details'
	);
	foreach ($wp_options as $option){
		delete_option($option);
	}
	
	// remove capabilities
	$capabilities = array('edit_FA_slider');
	$roles = $wp_roles->get_names();
	foreach( $roles as $role=>$name ){
		$wp_roles->remove_cap($role, $capabilities[0]);		
	}
}	
?>