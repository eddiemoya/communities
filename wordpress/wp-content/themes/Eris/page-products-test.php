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


//$rr = convert_meta(get_post_custom(13640));

//$rr = Products_Model::factory(array('00303348000P', '06703104000P', '08898528099P', '026W002842300001P', '026W004146922001P'));

/*$id = 13640;
var_dump(get_post($id));*/
$rr = Products_Model::factory()->get_by_id(array(13619,13620))->products;

/*$rr = get_posts(array('post_type'	=> 'product',
						'meta_query'	=> array(array('key'	=> 'partnumber',
															'value'	=> '00339678000P')),
						'post_status'	=> 'publish'
								));*/

echo '<pre>';
var_dump($rr);
echo '</pre>';

echo 'Not Found: ' . var_dump($rr->not_found);

/*try {
		Products_Updater::factory()
						->update();
						
} catch(Exception $e) {
	
	echo $e->getMessage();
}*/


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

/*function convert_meta($meta) {
	
	$data = new stdClass();
	
	foreach((array) $meta as $key=>$meta_value) {
		
		$value = @unserialize($meta_value[0]);
		
		if($value !== false) {
			
			$data->{$key} = $value;
			
		} else {
			
			$data->{$key} = $meta_value[0];
		}
	}
	
	return $data;
}*/