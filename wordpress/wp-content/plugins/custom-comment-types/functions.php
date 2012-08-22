<?php 

/**
 * Gets comment count by type - essentially a wrapper for get_comments with the count arugment set to true.
 * All arguments for get_comments can be passed to $args
 */
function get_custom_comment_count($type, $post_id = null, $args = array()){
	if(is_null($post_id)){
		global $post;
		$post_id = $post->ID;
	}

	$arguments = array(
		'status' => 'approved',
		'post_id' => $post_id,
		'count' => true
	);

	$comment_count = get_comments(array_merge($arguments, $args));
	return $comment_count;
}

function custom_comment_count($type, $post_id = null, $args = array()){
	echo get_custom_comment_count($type, $post_id, $args);
}