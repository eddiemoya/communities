<?php
$site = '';
$verticals = array('sears' => array('Appliances',
									'Automotive & Tires',
									'Baby',
									'Beauty & Health',			
									'Bed, Bath & Home',
									'Books & Magazines',
									'Clothing, Shoes & Jewelry',
									'Electronics, TVs, & Office',
									'Fashion',
									'Fitness & Sports',
									'Gifts & Seasonal',
									'Lawn & Garden',
									'Music, Movies, & Gaming',
									'Outdoor Living',
									'Parts & Services',
									'Tools',
									'Toys'
									),
					'kmart' => array('Appliances',
										'Automotive',
										'Baby',
										'Beauty & Health',
										'Bed & Bath',
										'Books & Magazines',
										'Clothing',
										'DIY & Tools',
										'Electronics, TVs, & Office',
										'Fitness & Sports',
										'For the Home',
										'Furniture & Mattresses',
										'Jewelry & Watches',
										'Lawn & Garden',
										'Music, Movies & Gaming',
										'Outdoor Living & Patio',
										'Pharmacy',
										'Pet, Food, & Grocery',
										'Shoes',
										'Toys & Games'									
										));
										
										
foreach($verticals[$site] as $vertical) {
	
	
}
 

echo '<pre>';
var_dump(api_request('Automotive&amp;Tires'));


function api_request($vertical) {
	
	$opts = array(CURLOPT_RETURNTRANSFER  => 1,
		        	CURLOPT_CONNECTTIMEOUT => 300,          // timeout on connect 
		       		CURLOPT_TIMEOUT        => 300);
	
	$url = "http://webservices.sears.com/shcapi/productsearch?apiVersion=v2&appID=FitStudio&authID=nmktplc303B1614B51F9FE5340E87FD1A1CEB3C06222010&apikey=06749c96f1e1bfadfeca4a02b4120253&store=Sears&verticalName={$vertical}&searchType=vertical";
	
	 // Init the curl resource.
     $ch = curl_init($url);

     // Set connection options
     if( ! curl_setopt_array($ch, $opts)){
     	
          throw new Exception('Failed to set CURL options, check CURL documentation.');
      }

      // Get the response body
      $body = curl_exec($ch);
        
      // Get the response information
      $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      
       if($body === FALSE)
        {
            $error = curl_error($ch);
        }

        // Close the connection
        curl_close($ch);
        
        if(! $error) {
        	
        	$out = simplexml_load_string($body);
        	
        	return $out;
        	
        } else {
        	
        	return 'An error occured on cURL request.';
        }
        
}