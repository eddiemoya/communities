<?php


echo '<h2>Ratings & Reviews Test Page</h2><br><br>';

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

/*$rr = get_posts(array('post_type'		=> 'product',
						'post_status'	=> array('publish', 'draft'),
						'posts_per_page' => 2,
						'paged'	=> 4));*/




/*echo '<pre>';
var_dump($rr);
echo '</pre>';*/

try {
		Products_Updater::factory()
						->update();
						
} catch(Exception $e) {
	
	echo $e->getMessage();
}


//part string in elem 5
/*$piece = explode('/', 'http://www.kmart.com/shc/s/p_10151_10104_021W004989196001P');
$review_str = str_replace('p_', 'allmodreviews_', $pieces[5]);

echo $review_str;*/


/*$product = Products_Api_Request::factory(array('api' => 'detail',
												'term' 	=> 'ghfhgfh'))
								->response();
								
								
echo '<pre>';
var_dump($product);
exit;*/