<?php 

add_action('rewrite_rules_array', 'posts_endpoint');


function posts_endpoint($rules){

	$newrules = array();
      
	$newrules['posts/?$'] = 'index.php?post_type=post';
    
	return $newrules + $rules;

}

