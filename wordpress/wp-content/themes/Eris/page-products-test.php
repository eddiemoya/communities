<?php


echo '<h2>Ratings & Reviews Test Page</h2><br><br>';

$rr = RR_User_Reviews::factory(10007132);

$results = $rr->results;


echo '<pre>';
var_dump($rr);
echo '</pre>';


/*$product = Products_Api_Request::factory(array('api' => 'detail',
												'term' 	=> '02631512000P'))
								->response();
								
								
echo '<pre>';
var_dump($product);
exit;*/