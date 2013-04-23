<?php


//echo '<h2>Ratings & Reviews Test Page</h2><br><br>';

/*$rr = RR_User_Reviews::factory(35497172)
						->get();*/

/*$rr = $reviews = RR_Recent_Reviews::factory()
       								->limit(3)
       								->get()
       								->results;*/
       								

/*$rr = RR_Api_Request::factory(array('api' 	=> 'recent',
												  'type'	=> 'store',
												  'term'	=> 'sears.com'))
									->response();*/
       								

//$results = $rr->results;

//echo WP_PLUGIN_URL;

echo substr(__FILE__, 0, (stripos(__FILE__, 'wordpress/') + 10)) . 'somedir/';

/*echo '<pre>';
var_dump($rr);
echo '</pre>';*/

//part string in elem 5
/*$pieces = explode('/', 'http://www.kmart.com/shc/s/p_10151_10104_021W004989196001P');
$review_str = str_replace('p_', 'allmodreviews_', $pieces[5]);

echo $review_str;*/


/*$product = Products_Api_Request::factory(array('api' => 'detail',
												'term' 	=> '02631512000P'))
								->response();
								
								
echo '<pre>';
var_dump($product);
exit;*/

//Products_Updater::factory()->update();
/*echo '<pre>';
var_dump(get_terms('skcategory',  array(
									    'orderby'       => 'name', 
									    'order'         => 'ASC',
									    'hide_empty'    => false, 
									    'fields'        => 'all', 
									    'hierarchical'  => true, 
									    'pad_counts'    => false, 
									   
									)));
exit;*/
/*echo '<pre>';
var_dump(get_posts(array('posts_per_page' => -1,
						'post_type'		=> array('post', 'question', 'guide'))));
exit;*/

/**
 *  object(stdClass)#357 (24) {
    ["ID"]=>
    int(13677)
    ["post_author"]=>
    string(3) "184"
    ["post_date"]=>
    string(19) "2013-05-03 11:56:31"
    ["post_date_gmt"]=>
    string(19) "2013-05-03 16:56:31"
    ["post_content"]=>
    string(0) ""
    ["post_title"]=>
    string(38) "Why did your momma name you skunkbutt?"
    ["post_excerpt"]=>
    string(0) ""
    ["post_status"]=>
    string(7) "publish"
    ["comment_status"]=>
    string(4) "open"
    ["ping_status"]=>
    string(4) "open"
    ["post_password"]=>
    string(0) ""
    ["post_name"]=>
    string(37) "why-did-your-momma-name-you-skunkbutt"
    ["to_ping"]=>
    string(0) ""
    ["pinged"]=>
    string(0) ""
    ["post_modified"]=>
    string(19) "2013-05-03 11:56:31"
    ["post_modified_gmt"]=>
    string(19) "2013-05-03 16:56:31"
    ["post_content_filtered"]=>
    string(0) ""
    ["post_parent"]=>
    int(0)
    ["guid"]=>
    string(73) "http://sears-ubuntu:5007/questions/why-did-your-momma-name-you-skunkbutt/"
    ["menu_order"]=>
    int(0)
    ["post_type"]=>
    string(8) "question"
    ["post_mime_type"]=>
    string(0) ""
    ["comment_count"]=>
    string(1) "0"
    ["filter"]=>
    string(3) "raw"
  }
 */

/*
echo '<pre>';
var_dump(wp_get_object_terms(13677, 'category'));
*/
/**
 * array(1) {
  [0]=>
  object(stdClass)#85 (9) {
    ["term_id"]=>
    string(1) "1"
    ["name"]=>
    string(13) "uncategorized"
    ["slug"]=>
    string(13) "uncategorized"
    ["term_group"]=>
    string(1) "0"
    ["term_taxonomy_id"]=>
    string(1) "1"
    ["taxonomy"]=>
    string(8) "category"
    ["description"]=>
    string(0) ""
    ["parent"]=>
    string(1) "0"
    ["count"]=>
    string(2) "60"
  }
}
*/


/*function terms_by_post_type($taxonomy = 'category') {
	
	$types = array('post', 'question', 'guide');
	
	$key = 'comm_post_terms-' . explode('-', $types);
	
	if(! $out = get_transient($key)) {
		
		$posts = get_posts(array('posts_per_page'	=> -1,
								'post_type'			=> $types));
		
		$out = array();
		
		foreach($posts as $post) {
			
			//Create new element fro post type if it doesn't exist
			if(! array_key_exists($post->post_type, $out)) {
				
				$out[$post->post_type] = array();
			}
			
			//Get terms
			$terms = wp_get_object_terms($post->ID, $taxonomy);
			
			//Add terms for post type
			foreach($terms as $term) {
				
				if(! in_array($term, $out[$post->post_type]))
					$out[$post->post_type][] = $term;
			}
			
		}
		
		set_transient($key, $out, 60);
	
	} 
	
	wp_reset_postdata();
	return $out;
}*/

//Benchmark Test


/*
$start_time = microtime(true);

terms_by_post_type();

$end_time = microtime(true);

echo 'New code executed in: ' . ($end_time - $start_time) . '<br>';


$start_time = microtime(true);

 get_terms_by_post_type('category', 'question');
 get_terms_by_post_type('category', 'post');
 get_terms_by_post_type('category', 'guide');

$end_time = microtime(true);
echo 'Old code executed in: ' . ($end_time - $start_time);
*/

/*echo '<pre>';
var_dump(get_terms('category', array( 'orderby'       => 'name', 
						    'order'         => 'ASC',
						    'hide_empty'    => true, 
						   
						    'hierarchical'  => true)));

exit;*/

//get_template_part('parts/header');

//echo '<pre>';
/*var_dump(get_terms('skcategory', array( 'orderby'       => 'name', 
						    'order'         => 'ASC',
						    'hide_empty'    => true, 
						   
						    'hierarchical'  => true)));*/

echo '<pre>';
var_dump(get_terms_by_post_type('category', array('post_type' 	=> 'question',
													'exclude' 	=> 'batman,tim',
													'children' 	=> false,
													'sortby'	=> 'term_id')));


//get_template_part('parts/footer');
?>

