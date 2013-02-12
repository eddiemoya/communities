<?php


$site = 'sears';//(stripos(get_bloginfo('name'), 'sears') !== false) ? 'sears' : 'kmart';

$store = ucfirst($site);

$taxonomy = 'skcategory';


$verticals = array('sears' => array('Appliances',/*
									'Automotive',
									'Baby',
									'Beauty',			
									'Bed & Bath',
									'Books & Magazines',
									'Cameras & Camcorders',
									'Food & Grocery',
									'For the Home',
									'Furniture & Mattresses',
									'Clothing',
									'Shoes',
									'Jewelry',
									'Computers',
									'TVs & Electronics',
									'Fitness & Sports',
									'Gift Registry',
									'Gifts',
									'Health & Wellness',
									'Home Services',
									'Office Products',
									'Pet Supplies',
									'Seasonal',
									'Shoes',
									'Sports Fan Shop',
									'Toys & Games',
									'Trends',
									'Lawn & Garden',
									'TVs & Electronics',
									'Music, Movies, & Gaming',
									'Workwear & Uniforms',
									'Outdoor Living',
									'Tools',
									'Toys'*/
									),
					'kmart' => array('Appliances',
									'Automotive',
									'Baby',
									'Beauty',			
									'Bed & Bath',
									'Books & Magazines',
									'Cameras & Camcorders',
									'Food & Grocery',
									'For the Home',
									'Furniture & Mattresses',
									'Clothing',
									'Shoes',
									'Jewelry',
									'Computers',
									'TVs & Electronics',
									'Fitness & Sports',
									'Gift Registry',
									'Gifts',
									'Health & Wellness',
									'Home Services',
									'Office Products',
									'Pet Supplies',
									'Seasonal',
									'Shoes',
									'Sports Fan Shop',
									'Toys & Games',
									'Trends',
									'Lawn & Garden',
									'TVs & Electronics',
									'Music, Movies, & Gaming',
									'Workwear & Uniforms',
									'Outdoor Living',
									'Tools',
									'Toys'
									));
										
										
 

echo '<pre>';
//var_dump(api_vertical_request('TVs & Electronics'));
//var_dump(api_category_request('Appliances', 'Refrigerators'));
var_dump(run());


function run() {
	
	global $verticals, $taxonomy, $site, $store;
	
	$completed = array();
	
	foreach($verticals[$site] as $vertical) {
		
		if($cats = api_vertical_request($vertical)) {
			
			//$completed[$vertical] = array();
			
			//insert vertical as term
			$vert_term = wp_insert_term($vertical, $taxonomy);
			$new_vertical_term_id = $vert_term['term_id'];
			
			foreach($cats->ShopByCategory as $cat) {
				
				if($cat->Category != 'Accessories'){
				//Insert category as term. with vertical parent
				$cat_term = wp_insert_term($cat->Category, $taxonomy, array('parent' => $new_vertical_term_id));
				$new_cat_term_id = $cat_term['term_id'];
				
				if($subcats = api_category_request($vertical, $cat->Category)) {
					
					//$completed[$vertical][$cat->Category] = array();
					
					foreach($subcats->ShopByCategory as $subcat) {
						
						//$completed[$vertical][$cat->Category][] = $subcat->Category;
						
						wp_insert_term($subcat->SubCategory, $taxonomy, array('parent' => $new_cat_term_id));
						unset($subcat);
					}
				}
				
				unset($cat);
				}
				
			}
		}
	}
	
	return 'Job Completed';
}

function api_vertical_request($vertical) {
	
	global $store;
	
	//$vertical = str_replace(' ', '', htmlspecialchars($vertical));
	$vertical = urlencode($vertical);
	
	$opts = array(CURLOPT_RETURNTRANSFER  => 1,
		        	CURLOPT_CONNECTTIMEOUT => 300,          // timeout on connect 
		       		CURLOPT_TIMEOUT        => 300);
	
	$url = "http://webservices.sears.com/shcapi/productsearch?apiVersion=v2&appID=FitStudio&authID=nmktplc303B1614B51F9FE5340E87FD1A1CEB3C06222010&apikey=06749c96f1e1bfadfeca4a02b4120253&store={$store}&verticalName={$vertical}&searchType=vertical";
	
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
        
        if(! isset($error)) {
        	
        	$out = extract_cats(simplexml_load_string($body));
        	//$out = simplexml_load_string($body);
        	
        	return $out;
        	
        } else {
        	
        	return 'An error occured on cURL request.';
        }
        
}

function api_category_request($vertical, $category) {
	
	global $store;
	
	$category = urlencode($category);
	$vertical = urlencode($vertical);
	
	$url = "http://webservices.sears.com/shcapi/productsearch?apiVersion=v2&appID=FitStudio&authID=nmktplc303B1614B51F9FE5340E87FD1A1CEB3C06222010&apikey=06749c96f1e1bfadfeca4a02b4120253&store={$store}&verticalName={$vertical}&categoryName={$category}&searchType=category";
	
	$opts = array(CURLOPT_RETURNTRANSFER  => 1,
		        	CURLOPT_CONNECTTIMEOUT => 300,          // timeout on connect 
		       		CURLOPT_TIMEOUT        => 300);
	
	
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
        
        if(! isset($error)) {
        	
        	$out = extract_cats(simplexml_load_string($body));
        	
        	return $out;
        	
        } else {
        	
        	return 'An error occured on cURL request.';
        }
        
}

function extract_cats($xml_object) {
	
	foreach($xml_object->NavGroups->NavGroup as $group) {
		
		if($group->DisplayName == 'Shop by Category' && isset($group->ShopByCategories)) {
			
			return $group->ShopByCategories;
		}
	}
	
	return false;
}

