<?php

/**
 * Detects AJAX request, returns true, else returns false
 * 
 * @author Dan Crimmins
 * @return bool
 */
function is_ajax() {

	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		
		return true;
	}
	
	return false;
}


/**
 * @author Dan Crimmins
 * 
 * Handles 'More' ajax pagination requests
 * 
 * @returns string - HTML output
 */
function profile_paginate() {
	 
	$uid = $_POST['uid'];
	$type = $_POST['type'];
	$page = $_POST['page'];
	
	
	require_once get_template_directory() . '/classes/communities_profile.php';
	
	//User Profile object
	$user_activities = new User_Profile($uid);
	
	ob_start();
	
		//Comments
		if($type == 'answer' || $type == 'comment') {
			
			$activities = $user_activities->page($page)
											->get_user_comments_by_type($type)
											->comments;
											
																										
			include(get_template_directory() . '/parts/profile-comments.php');
		}
		
		//Posts
		if($type == 'posts' || $type == 'guides' || $type == 'question') {
			
			$activities = $user_activities->page($page)
											->get_user_posts_by_type($type)
											->posts;
											
			include(get_template_directory() . '/parts/profile-posts.php');
		}
		
		//Actions
		if($type == 'follow' || $type == 'votes') {
			
			include(get_template_directory() . '/parts/profile-actions.php');
		}
		
		if($type == 'recent') {
			
			$activities = $user_activities->page($page)
											->get_recent_activities()
											->activities;
											
			include(get_template_directory() . '/parts/profile-recent.php');
		}
		
	$output = ob_get_clean();
	
	echo $output;
	
	die;
}

add_action('wp_ajax_profile_paginate', 'profile_paginate');
add_action('wp_ajax_nopriv_profile_paginate', 'profile_paginate');