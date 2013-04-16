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

Products_Updater::factory()->update();

