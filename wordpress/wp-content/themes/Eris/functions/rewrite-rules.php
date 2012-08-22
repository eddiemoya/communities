<?php 

add_action('rewrite_rules_array', 'posts_endpoint');

/**
 * Add endpoint for posttype archive for the POST post type.
 */
function posts_endpoint($rules){

	$newrules = array();
	$newrules['posts/?$'] = 'index.php?post_type=post';
	return $newrules + $rules;

}

