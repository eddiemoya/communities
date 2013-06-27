<?php 


add_filter('category_rewrite_rules', 'category_post_type_endpoints');
add_filter('post_rewrite_rules', 'posts_endpoint');
add_filter('post_format_rewrite_rules', 'post_format_endpoint');


add_filter('rewrite_rules_array', 'rewrite_rules');

function rewrite_rules($rules){
	//print_pre($rules);
	return $rules;
}
/**
 * Add endpoint for posttype archive for the POST post type.
 */
function post_format_endpoint($rules){

	$newrules = array();

	$newrules['(video)s?/?$'] = 'index.php?post_format=$matchs[1]';
	
	//echo "<pre>";print_r($newrules + $rules);echo "</pre>";
	return $newrules + $rules;
}


/**
 * Add endpoint for posttype archive for the POST post type.
 */
function posts_endpoint($rules){

	$newrules = array();
	$newrules['posts?/?$'] = 'index.php?post_type=post';
	//$newrules['video?/?$'] = 'index.php?post_format=video';

	//echo "<pre>";print_r($newrules + $rules);echo "</pre>";
	return $newrules + $rules;
}

/**
 * Add post type endpoints for categorry archive URI's
 */
function category_post_type_endpoints($rules){

 //    $newrules = array();
	// $newrules['category/(.+?)/(guide|question|post)s?/?$'] = 'index.php?category_name=$matches[1]&post_type=$matches[2]';
 //    $newrules['category/(.+?)/(guide|question|post)s?/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&post_type=$matches[2]&paged=$matches[3]';
 //    $newrules['category/(.+?)/videos?/?$'] = 'index.php?category_name=$matches[1]&post_format=video';
	//print_pre($newrules + $rules);
	//return $newrules + $rules;

	#The new Section Fronts plugin requires that we remove the default rewrite rules for a given taxonomy.
	#This allows the request to "fall through"  to the post types rewrite rule. The request for taxonomy is
	#then recreated.
	return array();

}

