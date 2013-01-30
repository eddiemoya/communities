<?php
/*
 * Template Name: Product Test
 */
error_reporting(E_ALL);
ini_set('display_errors', true);

?>

<h1>Product Test</h1>

<?php 

//Product Detail Api Request
 /*$response = Products_Api_Request::factory(array('api' => 'detail',
											 'term' => '002VA50405301P'))
							->response();*/

//Product Search keyword request
$response = Products_Api_Request::factory(array('api'	=> 'search',
												'type'	=>	'keyword',
												'term'	=>	'pants',
												'page'	=> 1,
												'per_page'	=> 50))
								->response();
echo '<pre>';
var_dump($response);
echo '</pre>';
?>