<?php

if(isset($_POST['import_taxonomy']) && $_POST['tax_set'] != '') {
	
	$tax_set = $_POST['tax_set'];
	
	$msg = "<h3>Completed Import of Taxonomy Set " . ($tax_set + 1) ."</h3>";
	
	$site = (stripos(get_bloginfo('name'), 'sears') !== false) ? 'sears' : 'kmart';
	
	$store = ucfirst($site);
	
	$taxonomy = 'skcategory';
	
	
	$verticals = array(array('Appliances',
								'Automotive',
								'Baby',
								'Beauty',		
								'Bed & Bath',
								'Books & Magazines',
								'Cameras & Camcorders',
								'Food & Grocery',
								'For the Home'),
						array('Furniture & Mattresses',
								'Clothing',
								'Shoes',
								'Jewelry',
								'Computers',
								'TVs & Electronics',
								'Fitness & Sports',
								'Gift Registry',
								'Health & Wellness',
								'Home Services',
								'Office Products'),
						array('Pet Supplies',
								'Seasonal',
								'Shoes',
								'Sports Fan Shop',
								'Toys & Games',
								'Trends',
								'Lawn & Garden',
								'Music, Movies, & Gaming',
								'Workwear & Uniforms',
								'Outdoor Living',
								'Tools'));
											
											
	if(is_user_logged_in() && current_user_can('manage_options')) {
		
		//Run Job
		run();
		delete_option('skcategory_children');
		echo $msg;
	 }
 
}

function run() {
	
	global $verticals, $taxonomy, $site, $store, $tax_set;
	
	$completed = array();
	
	foreach($verticals[$tax_set] as $vertical) {
		
		if($cats = api_vertical_request($vertical)) {
			
			//insert vertical as term
			$vert_term = wp_insert_term($vertical, $taxonomy);
			
			if(! is_wp_error($vert_term)){
				
			
					$new_vertical_term_id = $vert_term['term_id'];
				
				foreach($cats->ShopByCategory as $cat) {
					
					if($cat->Category != 'Accessories'){
						
					//Insert category as term. with vertical parent
					$cat_term = wp_insert_term($cat->Category, $taxonomy, array('parent' => $new_vertical_term_id));
					$new_cat_term_id = $cat_term['term_id'];
					
					/*if($subcats = api_category_request($vertical, $cat->Category)) {
						
						foreach($subcats->ShopByCategory as $subcat) {
							
							wp_insert_term($subcat->SubCategory, $taxonomy, array('parent' => $new_cat_term_id,
																					'slug' => sanitize_title($subcat->SubCategory) . '-subcat'));
							unset($subcat);
						}
					}*/
					
					unset($cat);
					}
					
				}
		}
		}
	}
	
	
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

?>

<?php if(is_user_logged_in() && current_user_can('manage_options')): ?>
<form method="post">
	<select name="tax_set">
		<option value="">Select One...</option>
		<option value="0">1</option>
		<option value="1">2</option>
		<option value="2">3</option>
	</select>
	<br>
	<input type="submit" name="import_taxonomy" value="Import Taxonomy" />
</form>
<?php endif;?>
