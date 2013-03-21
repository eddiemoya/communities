<?php 


add_action('category_rewrite_rules', 'category_post_type_endpoints');
add_action('post_rewrite_rules', 'posts_endpoint');
add_action('post_format_rewrite_rules', 'post_format_endpoint');


/**
 * Add endpoint for posttype archive for the POST post type.
 */
function post_format_endpoint($rules){

	$newrules = array();

	$newrules['videos?/?$'] = 'index.php?post_format=video';
	
	//echo "<pre>";print_r($newrules + $rules);echo "</pre>";
	return $newrules + $rules;
}


/**
 * Add endpoint for posttype archive for the POST post type.
 */
function posts_endpoint($rules){

	$newrules = array();
	$newrules['posts?/?$'] = 'index.php?post_type=post';
	$newrules['video?/?$'] = 'index.php?post_format=video';

	//echo "<pre>";print_r($newrules + $rules);echo "</pre>";
	return $newrules + $rules;
}

/**
 * Add post type endpoints for categorry archive URI's
 */
function category_post_type_endpoints($rules){

    $newrules = array();
	$newrules['category/(.+?)/(guide|question|post)s?/?$'] = 'index.php?category_name=$matches[1]&post_type=$matches[2]';
    $newrules['category/(.+?)/(guide|question|post)s?/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&post_type=$matches[2]&paged=$matches[3]';
    $newrules['category/(.+?)/videos?/?$'] = 'index.php?category_name=$matches[1]&post_format=video';
	// "<pre>";print_r($newrules + $rules);echo "</pre>";
	return $newrules + $rules;

}

