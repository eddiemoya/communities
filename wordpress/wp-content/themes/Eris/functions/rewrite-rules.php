<?php 


//add_filter('category_rewrite_rules', 'category_post_type_endpoints');
add_filter('rewrite_rules_array', 'posts_endpoint');
add_filter('post_format_rewrite_rules', 'post_format_endpoint');

add_filter('query_vars', 	 'add_vars');

	function add_vars($qvars) {
		//$qvars[] = 'meta_key';
		$qvars[] = 'sf_filter';


		return $qvars;
	}



add_filter('rewrite_rules_array', 'rewrite_rules');

function rewrite_rules($rules){
	//$newrules['category/(.+?)/video$'] = 'index.php?post_type=category&name=$matches[1]';
//$newrules['category/(.+?)/(guide|question|post|video)/?$'] = 'index.php?post_type=category&name=$matches[1]&sf_filter=$matches[2]';
	return $rules;// + $rules;
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
	
	unset($rules['questions/?$']);

	//echo "<pre>";print_r($newrules + $rules);echo "</pre>";
	return $newrules + $rules;
}

/**
 * Add post type endpoints for categorry archive URI's
 */
function category_post_type_endpoints($rules){

    $newrules = array();
	// $newrules['category/(.+?)/(guide|question|post|video)s?/?$'] = 'index.php?post_type=category&category_name=$matches[1]&sf_filter=$matches[2]';
	// $newrules['category/(.+?)/?$'] = 'index.php?post_type=category&category_name=$matches[1]&sf_filter=category';

    //$newrules['category/(.+?)/(guide|question|post)s?/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&post_type=$matches[2]&paged=$matches[3]';
    // $newrules['category/(.+?)/videos?/?$'] = 'index.php?category_name=$matches[1]&post_format=video';
	//print_pre($newrules + $rules);
	//return $newrules + $rules;

	#The new Section Fronts plugin requires that we remove the default rewrite rules for a given taxonomy.
	#This allows the request to "fall through"  to the post types rewrite rule. The request for taxonomy is
	#then recreated.
	return $newrules;// + $rules;

}



function wptuts_add_rewrite_rules() {  
    add_rewrite_rule(  
        '^(category|post|question)/([^/]+)/?$', // String followed by a slash, followed by a date in the form '2012-04-21', followed by another slash  
        'index.php?post_type=$matches[1]&name=$matches[2]&sf_filter=$matches[1]',  
        'top'  
    );  
     add_rewrite_rule(  
        '^(category|post|question)/([^/]+)/(guide|question|post|video)s?/?$', // String followed by a slash, followed by a date in the form '2012-04-21', followed by another slash  
        'index.php?post_type=$matches[1]&name=$matches[2]&sf_filter=$matches[3]',  
        'top'  
    );
     add_rewrite_rule(  
        '((question|post)s)/?$', // String followed by a slash, followed by a date in the form '2012-04-21', followed by another slash  
        'index.php?pagename=$matches[1]&sf_filter=$matches[2]',  
        'top'  
    );   
}  
add_action( 'init', 'wptuts_add_rewrite_rules' );  



function wptuts_register_rewrite_tag() {  
    add_rewrite_tag( '%post_type%', '([0-9]{4}-[0-9]{2}-[0-9]{2})');  
}  
add_action( 'init', 'wptuts_register_rewrite_tag');  



function filter_post_links( $permalink, $post ) {  
  
    // Check if the %post_type% tag is present in the url:  
    if ( false === strpos( $permalink, '%post_type%' ) )  
        return $permalink;  
  
  
    // Replace '%post_type%'  
    $permalink = str_replace( '%post_type%', $post->post_type , $permalink );  
  
    return $permalink;  
}  
add_filter( 'post_link', 'filter_post_links' , 10, 2 );  

