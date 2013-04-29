<?php
/**
 * General use loop function. Allows for a template to be selected. Currently 
 * defaults to product template because that is used by our themes most often.
 * 
 * @author Eddie Moya
 * 
 * @global type $wp_query
 * @param string|array $template [optional] Template part to be used in the loop. Defaults to 'post'
 * @param string|array $special [optional] Like get_template_part()'s second param, an appended portion of the filename delimited with a dash. $template-$special.php
 * @param string|null $base_path [optional] The path (relative to the theme root folder) in which to find the template. Defaults to "parts". Defaults to null.
 * @param string|null $no_posts_template [optional] The template to load should there not be any posts to show in the query. Defaults to null.
 *
 * @return void.
 */
function loop($template = 'post', $special = null, $base_path = "parts", $no_posts_template = null){
    global $wp_query;
    //print_pre($wp_query);

    //Allows for arrays of tempaltes to be passed, the first of which is found will be loaded.
    $template = (array)$template;
    $special = (array)$special;
    
    //echo "<pre>";print_r($special);echo "</pre>";
    $templates = array();
    $index_offset = 0;

    foreach($template as $index => $t){
        
        foreach($special as $s){

            $templates[] = trailingslashit($base_path) . $t . '-'.$s.'.php';
            $index_offset++;
        }

        $templates[$index+$index_offset] = trailingslashit($base_path) . $t .'.php';
    }

    if (have_posts()) {

        while (have_posts()) {
            the_post();
            locate_template($templates, true, false);
        }

    } else {

        if(!is_null($no_posts_template)){
            get_template_part($no_posts_template);
        }

    }
    //echo "<pre>";print_r($templates);echo "</pre>";

    //wp_reset_query();
}

/**
 * Similar to the loop() function but dynamically figures out the post type
 * partial it should use.
 * 
 * @author Eddie Moya
 * 
 * @global type $wp_query
 * @param type $special [optional] Template part to be used in the loop.
 *
 * @return void.
 */
function loop_by_type($special = null){
    global $wp_query;
   // print_pre($wp_query);
    
    
    //echo $template;
    if (have_posts()) { 
        while (have_posts()) {
            
            
            //echo 'TEMPALTE:'.$template;
            the_post();
     
            $template = (isset($special)) ? $wp_query->post->post_type.'-'.$special : $wp_query->post->post_type;
            //print_pre($template);
            get_template_part('parts/'.$template);
        }    
    }

    wp_reset_query();

}


/**
 * Return a template instead of outputing it.
 *
 * Intended for use in placing json_encoded templates in the header
 * via wp_localize_script();
 *
 * @author Eddie Moya
 * @param $template (string) Relative path/filename of the template to be returned
 *
 * @return (string) Contents of included template.
 */
function return_template_part($template){
    ob_start();
        get_template_part($template);
    return ob_get_clean();
}

/**
 * Checks if user has a screen name set. If so, returns true, else false
 * ersection
 * @author Dan Crimmins
 * @param int $user_id - WP User ID
 * @return bool
 */
function has_screen_name($user_id) {
	
	//if(get_user_meta($user_id, 'profile_screen_name', true)) {
	$user = SSO_User::factory()->get_by_id($user_id);
	
	//if this is a non-SSO user, return true to display user_nicename
	if(! $user->id) {
		
		return true;
		
	} else {
		
		return ($user->screen_name) ? true : false;
	}
	
}




/**
 * Process attempts to post a question from the front end of the site.
 *
 * @author Eddie Moya
 * @return (integer). The number of the step which should be rendered on any given page view.
 */
function process_front_end_question() {
	
	global $current_user;
	
	//Neither step has been taken, were on step 1
 	 $GLOBALS['post_question_data'] =  array('errors' => null, 'step' => '1');
			
    //If step 1 - return that we should move on to step 2.
    if( ( isset($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'], 'front-end-post_question-step-1' ) || isset($_POST['new_question_step_1'])) && (! isset($_POST['question-post-complete']))){
		
    	
        //If user is logged in - step 2
        if(is_user_logged_in() && strlen(trim($_POST['post-question'])) > 0) {
			
			
        	$GLOBALS['post_question_data'] = array('step'		=> '2',
        											'errors'	=> null);
        	
            
        } else { 
            /**
             * Kick off login modal SSO login crazyness here 
             */
            $GLOBALS['post_question_data'] = array('step'		=> '1',
        											'errors'	=> array('Please enter a question.'));
        }
    }

    //If step 2, add the post and move to step 3
    if( (isset($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'], 'front-end-post_question-step-2' ) && is_user_logged_in()) && ! isset($_POST['cancel-question'])) {
		
		$valid = true;
    	$errors = array();
    	
    	//Make sure they posted a question<input type="hidden" name="cancel-question" value="" />
    	if(strlen(trim($_POST['your-question'])) == 0) {
    		
    		$valid = false;
    		$errors['your-question'] = 'Please enter a question.';
    	}
    	
    	//Make sure that a category has been selected
    	if($_POST['category'] == 'default') {
    		
    		$valid = false;    		
    		$errors['category'] = 'Please select a category for your question.';
    	}
    	
    	//If a screen name is required...
    	if(isset($_POST['screen-name'])) {
    		 
    		 //Illegal chars for screen name
    		 $bad_chars = array('~', '!', '@', '#', '$', '%', 
    								'^', '&', '*', '(', ')', '+', '=',
    								':', ';', '"', '<', '>', '\'', '?',
    								'`', '/', '\\', ' ');
    		 
    		 	//Test screen name min / max length
    			if(strlen(trim($_POST['screen-name'])) < 2 || strlen(trim($_POST['screen-name'])) > 18) {
    				
    				$valid = false;
    				
    				$errors['screen-name'] = 'Screen name is required to be between 2-18 characters, and can only contain letters, numbers, dashes, underscores, and periods.';
    			}
    				
    				//Test for illegal chars
    				foreach($bad_chars as $char){
    					
	    				if(strpos($_POST['screen-name'], $char ) !== false) {
	    					
	    					$valid = false;
	    					$errors['screen-name'] = 'Screen name is required to be between 2-18 characters, and can only contain letters, numbers, dashes, underscores, and periods.';
	    					
	    					break;
	    				}
    				}
    				
	    				//If everything is valid, attempt to set screen name
	    				if($valid) {
	    				
	    					//$sso_guid = get_user_sso_guid($current_user->ID);
	    					$sso_user = SSO_User::factory()->get_by_id($current_user->ID);
	    					
	    					//$profile = new SSO_Profile;
	    					
	    					//$response = $profile->update($sso_user->guid, array('email' => $current_user->user_email ,
	    																 		//'screen_name' => $_POST['screen-name']));
	    					$response = SSO_Profile_Request::factory()->update($sso_user->guid, array('email' => $current_user->user_email ,
	    																 		'screen_name' => $_POST['screen-name']));
	    						//Check for error
	    						if(isset($response['code'])) {
	    							
	    							$valid = false;
	    							
	    							$errors['screen-name'] = $response['message'];
	    							
	    						} else {
	    							
	    							//Add user meta for screen name
	    							//update_user_meta($current_user->ID, 'profile_screen_name', $_POST['screen-name']);
	    												
    								$sso_user->set('screen_name', $_POST['screen-name'])
    										 ->save();
	    							
	    							//Update user's nicename to screen name
	    							if(! update_user_nicename($current_user->ID, $_POST['screen-name'])) 
	    							
	    									$valid = false;
	    									$errors['screen-name'] = 'Problem setting user nicename.';
	    						}
	    				}
    	}
    	
	    	//If valid, insert question
	    	if($valid) {
	    		
		        $raw_content = $_POST['more-details'];
		
		        $title =  sanitize_text(wp_kses($_POST['your-question'], array(), array()));
		        $content = sanitize_text(wpautop(wp_kses($_POST['more-details'], array(), array())));
		
		        $category = (isset($_POST['category'])) ?  absint((int)$_POST['category'])  : '' ;
		        $category = (isset($_POST['sub-category'])) ? absint((int)$_POST['sub-category']) : $category; 
		
		       	

		       		$post = array(
		                'post_title'    => $title,
		                'post_content'  => $content,
		                'post_category' => array($category),
		                'post_status'   => 'publish',           
		                'post_type'     => 'question'
		            );
				
		           
		            
		        //Only post if the question for this user doesn't exist    
		       if(! question_exists($post)) {   
		       	
		        	wp_insert_post($post); 
		        	do_action('wp_insert_post', 'wp_insert_post');
		        	
		       }
		        
		       
		       	
		       	unset($current_user);
		       	get_currentuserinfo();
		       	
		        $GLOBALS['post_question_data'] =  array('errors' => null, 'step' => '3');
		        
	    } else {
	    	
	    	$GLOBALS['post_question_data'] =  array('errors' => $errors, 'step' => '2');
	    }
    
    }

    
}

//Used for post a question widget
add_action('init', 'process_front_end_question');



function question_exists($post) {
	
	global $wpdb;
	global $current_user;
	get_currentuserinfo();
	
	$p = $wpdb->prefix . 'posts';
	
	$q = "SELECT ID FROM " . $p . " WHERE post_title = '" . $post['post_title'] ."' AND post_content = '". $post['post_content'] . "' AND post_type = 'question' AND post_author = " . $current_user->ID;
	
	return ($wpdb->get_var($q)) ? true : false;
}


/**
 * @author Eddie Moya
 * @since 1.0
 * 
 * Retreives the entire post object of the a single image that is categorized
 * with the category ID that is passed or the current category if none is passed.
 * 
 * @param int $category_id [optional] A category ID, defaults to the current category ID.
 * @param bool $echo [depricated since 1.1] Can't echo the entire post object.
 * 
 * @return object
 */
function get_category_image($category_id = null, $echo = false, $args = array()){
    
    $category_id = (empty($category_id)) ? get_query_var('cat') : $category_id;
    $img_args = array(
        'numberposts' => 1,
        'post_type' => 'attachment',
        'tax_query' => array (
            'relation' => 'AND',
            array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $category_id,
                'include_children' => false,
                'operator' => 'IN'
            )
         )
    );
    

    $image = get_posts(array_merge($img_args, $args));
    

    if(isset($image[0])) {
        $image = $image[0];
        
        if ($echo ) 
            { echo $image; }
        else 
            { return $image; }
    }
}



/**
 * @author Eddie Moya
 * @since 1.0
 * 
 * Gets the url of the first categorized image for a particular category.
 * 
 * @param int $category_id [optional] If set, it will use the given id to search for an image. If not set, it will use the current category from get_query_var('cat)
 * @param bool $echo [optional] If true, the url is echoed out, if false, it is returned. Default: true/
 * @return string The url of the image. 
 */
function get_category_image_url($category_id = null, $echo = true, $thumb = false){
    
    $category_id = (empty($category_id)) ? get_query_var('cat') : $category_id;

    $image = get_category_image($category_id, false);

    if($image){
        
        if (!$thumb) {
            $image_url = wp_get_attachment_url($image->ID);
        } else {
            $image_url = wp_get_attachment_thumb_url($image->ID);
        }

        if ($echo ) echo $image_url;
        else { return $image_url; }
        
    } else return false;
}


function print_pre($r){
    echo '<pre>';
    print_r($r);
    echo '</pre>';
}


/**
 *  Function to get terms only if they have posts by post type
 *  @param $taxonomy (string) taxonomy name eg: 'post_tag','category'(default),'custom taxonomy'
 *  @param $post_type (string) post type name eg: 'post'(default),'page','custom post type'
 *
 *  Usage:
 *  list_terms_by_post_type('post_tag','custom_post_type_name');
 *
 * @return (array) Terms found to have posts of the provided post type.
 **/
/*function get_terms_by_post_type($taxonomy = 'category',$post_type = 'post'){

    //get a list of all post of your type
    $args = array(
        'posts_per_page' => -1,
        'post_type' => $post_type
    );

    $terms = array();
    $posts = get_posts($args);

    foreach($posts as $p){
        //get all terms of your taxonomy for each type
        $ts = wp_get_object_terms($p->ID,$taxonomy); 
        foreach ( $ts as $t ) {
            if (!in_array($t,$terms)){ //only add this term if its not there yet
                //$t->cat_name = ''
                $terms[] = $t;
            }
        }
    }

    wp_reset_postdata();

    return $terms; 
}*/

function get_terms_by_post_type($taxonomy='category', $args = array()) {
	
	global $wpdb;
	
	$default_args = array('post_type' 	=> 'post', //Can be single tax string or array of taxonomies
							'sort'	  	=> 'ASC',
							'sortby'	=> 'name', //Can be name, term_id, or slug
							'exclude'	=> null, //array or comma sep list of term ids, or slugs
							'children'	=> false //return parents and children, defaults to returning only parents
							);
							
	$args = wp_parse_args($args, $default_args);
							
	$parent = ($args['children']) ? null : ' AND tt.parent = \'0\' ';
	$sortby = ($args['sortby'] == 'name' || $args['sortby'] == 'term_id' || $args['sortby'] == 'slug') ? 't.' . $args['sortby'] : 't.name';
	$sort = ($args['sort'] == 'ASC' || $args['sort'] == 'DESC') ? $args['sort'] : 'ASC';
	$exclude = '';
	
	//Exclude specific terms by either term_id or slug
	if($args['exclude']) {
		
		if(is_array($args['exclude'])) {
			
			$exclude = explode(', ', $args['exclude']);
			
		} elseif(is_string($args['exclude'])) {
			
			$exclude = str_replace(',', ', ', str_replace(' ', '', $args['exclude']));
		}
		
		//slug or id?
		if(is_numeric(substr($exclude, 0, strpos($exclude, ',')))) {
			
			$exclude = " AND t.term_id NOT IN ('". str_replace(', ', '\', \'', $exclude) ."') ";
			
		} else {
			
			$exclude = " AND t.slug NOT IN ('". str_replace(', ', '\', \'', $exclude) ."') ";
		}
	}
	
	//Return child terms?
	$parent = ($args['children']) ? '' : " AND tt.parent = '0' ";
	
	//Taxonomy - either a string or array is passed
	$taxonomy = ($taxonomy) ? ((is_array($taxonomy)) ? str_replace(', ', '\', \'', explode(', ', $taxonomy)) : $taxonomy) : 'category';
	
	//Post Type - string or array is passed
	$post_type = ($args['post_type']) ? ((is_array($args['post_type']) ? str_replace(', ', '\', \'', explode(', ', $args['post_type'])) : $args['post_type'])) : 'post';
	
	$q = "SELECT DISTINCT t.* FROM {$wpdb->prefix}terms AS t INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id = tt.term_id 
			INNER JOIN {$wpdb->prefix}term_relationships as tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
			INNER JOIN {$wpdb->prefix}posts as p ON tr.object_id = p.ID 
			WHERE tt.taxonomy IN ('{$taxonomy}') AND p.post_type IN ('{$post_type}'){$parent}{$exclude} ORDER BY {$sortby} {$sort}";
	
	return $wpdb->get_results($q);
	
}

/**
 * Includes partial while passing a set of variables into the included templates
 * scope.
 *
 * @author Eddie Moya & Carl Albrecht-Buehler
 *
 * @param $partial (string) [required] The filename or relative path to the intended partial template.
 * @param $variables (array) [optional] Associative array of values to be passed into the templates scope. The keys will become the variable names.
 *
 * @return void.
 */
function get_partial( $partial, $variables = array() ) {

    if(is_object($variables)){
        $variables = get_object_vars($variables);
    }
    
    extract( $variables );

    include get_template_directory() . '/' . $partial . '.php';
}

/**
 * Return a partial instead of outputting it.
 *
 *
 * @author Eddie Moya & Carl Albrecht-Buehler
 * @param $partial (string) [required] The filename or relative path to the intended partial template.
 * @param $variables (array) [optional] Associative array of values to be passed into the templates scope. The keys will become the variable names.
 *
 * @return (string) Contents of included partial.
 */
function return_partial( $partial, $variables = array() ){
    ob_start();
        get_partial( $partial, $variables );
    return ob_get_clean();
}

/**
 * Return a user's profile url.
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose screen name you want to look up.
 *
 * @return (string) User's screen name (user_nicename).
 */
function get_profile_url( $user_id ) {
	
	wp_cache_flush();
	
    # create a fallback screen name if one has not yet been set by sso
    if ( !has_screen_name( $user_id )) {
        $link = home_url( '/' ) . '?author=' . $user_id;
    }
    else {
        $link = get_author_posts_url( $user_id );
    }
    
    //Check to make sure we have a valid link. If link same as home link, do this...
    if($link == home_url())
    	 $link = home_url('/' ) . '?author=' . $user_id;
    	
    return $link;
}

/**
 * Return a user's screen name (user_nicename).
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose screen name you want to look up.
 *
 * @return (string) User's screen name (user_nicename).
 */
function return_screenname( $user_id ) {
  
   global $wpdb;
   $wpdb->flush();
   
   $q = "SELECT * FROM {$wpdb->base_prefix}users WHERE ID = {$user_id}";
   $user_info = $wpdb->get_results($q);
   
   //if screen name has been blocked...
   if(is_screen_name_blocked($user_id)) return '*********';
  
    $screen_name = '';
    # create a fallback screen name if one has not yet been set by sso
    if ( !has_screen_name( $user_id ) ) {
        $email_parts = explode( '@', $user_info[0]->user_login );
        $screen_name = $email_parts[0];
    }
    else {
        $screen_name = $user_info[0]->user_nicename;
    }
    return $screen_name;
}

/**
 * Checks if user has been flagged to block display of his/her screen name
 *
 * @param int $user_id
 * @return bool
 */
function is_screen_name_blocked($user_id) {
	
	return (get_user_meta($user_id, 'block_screen_name', true) == 'yes') ? true : false;
}

/**
 * Echo a user's screen name (user_nicename).
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose screen name you want to look up.
 *
 * @return void.
 */
function get_screenname( $user_id ) {
    echo return_screenname( $user_id );
}

/**
 * Return a user's screen name (user_nicename) formatted into an HTML anchor.
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose screen name you want to look up.
 *
 * @return (string) User's screen name (user_nicename) formatted into an HTML anchor.
 */
function return_screenname_link( $user_id ) {
    return '<a href="' . get_profile_url( $user_id ) . '">' . return_screenname( $user_id ) . '</a>';
}

/**
 * Echo a user's screen name (user_nicename) formatted as an HTML anchor.
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose screen name you want to look up.
 *
 * @return void.
 */
function get_screenname_link( $user_id ) {
    echo return_screenname_link( $user_id );
}


/**
 * Returns the date of a user's last post.
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose last post you want to look up.
 *
 * @return (integer) Timestamp of the last post.
 */
function return_last_post_date( $user_id ) {
    $last_post = get_posts( array( 'numberposts' => 1, 'author' => $user_id, 'post_type' => array( 'question', 'guide', 'post' ) ) );
    return isset( $last_post[0] ) ? strtotime( $last_post[0]->post_date ) : 0;
}

/**
 * Returns the count of a user's total posts.
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose last post you want to look up.
 *
 * @return (integer) Timestamp of the last post.
 */
function return_post_count( $user_id ) {
    global $wpdb;

    if(!empty($user_id)){
        return $wpdb->get_var( "
            select count(`ID`) as `num_posts` 
            from {$wpdb->posts} 
            where `post_type` in ( 'question', 'guide', 'post' ) 
            and `post_author` = {$user_id}" );
    } else {
        return null;
    }
}

/**
 * Returns a formatted address of a user.
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose address you want to look up.
 *
 * @return (string) User's address as [City, State]; [City]; [State]; or [&nbsp;].
 */
function return_address( $user_id ) {
    $a_address = array();
    $address = '&nbsp;';
    
    $sso_user = SSO_User::factory()->get_by_id($user_id);
    
    if($sso_user->guid){
    	
    	$city = $sso_user->city;
    	$state = $sso_user->state;
    	
    } else {
    	
	    $city  = get_user_meta( $user_id, 'user_city', true );
	    $state = get_user_meta( $user_id, 'user_state', true );
    	
    }
    
   
    
    if ( $city != '' )  { $a_address[] = $city; }
    if ( $state != '' ) { $a_address[] = strtoupper($state); }
    if ( !empty( $a_address ) ) {
        $address = implode( ', ', $a_address );
    }
    
    return $address;
}


/**
 * Detects AJAX request, returns true if it is, else returns false
 * 
 * @author Dan Crimmins
 * @return bool
 */
function is_ajax() {

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        
        return true;
    }
    
    return false;
}


/**
 * @author Dan Crimmins
 */
function get_user_sso_guid($user_id) {
    
        global $wpdb;
        $usermeta = $wpdb->base_prefix . 'usermeta';
        
        $user_query = "SELECT meta_value FROM " . $usermeta ." WHERE meta_key = 'sso_guid' AND user_id = " . $user_id;
        $sso_guid = $wpdb->get_var($user_query);
        
        return $sso_guid;
}

/**
 * @author Dan Crimmins
 */
function update_user_nicename($uid, $name) {
	
	global $wpdb;
	$user = $wpdb->base_prefix . 'users';
	
		$update = $wpdb->update($user, 
								array('user_nicename' => $name),
								array('ID' => $uid));
						
		return ($update) ? true : false;
}

/**
 * @author Dan Crimmins
 */
function set_screen_name($screen_name) {
	
	global $current_user;
	get_currentuserinfo();
	
	$sso_user = SSO_User::factory()->get_by_id($current_user->ID);
	
	//$sso_guid = get_user_sso_guid($current_user->ID);
	//$sso_guid = SSO_User::factory()->get_by_id($current_user->ID)->guid;
		    					
	//$profile = new SSO_Profile;
	    					
	//$response = $profile->update($sso_user->guid, array('email' => $current_user->user_email,
    											  //'screen_name' => $screen_name));
	$response = SSO_Profile_Request::factory()->update($sso_user->guid, array('email' => $current_user->user_email,
    											  								'screen_name' => $screen_name));
	//Check for error
	if(isset($response['code'])) {
			
		return $response;
			
	} else {
			
		//Add user meta for screen name
		//update_user_meta($current_user->ID, 'profile_screen_name', $screen_name);
		$sso_user->set('screen_name', $screen_name)
				 ->save();
			
		//Update user's nicename to screen name
		update_user_nicename($current_user->ID, $screen_name);
			
		return true;
	}

}




/**
 * Gets the number of comments in a post by an expert
 *
 * @author Jason Corradino
 * @param $post_id (integer) [required] The ID of the post being looked up
 * @param $category (integer/array) [optional] Limit expert categories to selected category, defaults to post categories
 *
 * @return (integer) Number of comments by an expert
 */
function get_expert_comment_count($post_id, $category = "") {
    global $wpdb;
    if($category == ""){
        $category = wp_get_post_categories($post_id);
    } elseif(is_string($category)) {
        $category = array($category);
    } elseif(!is_array($category)) {
        return false;
    }
    return lookup_expert_comments_count($post_id, $category);
}

/**
 * Returns the number of expert comments within a given post
 *
 * @author Jason Corradino
 * @param $post_id (integer) [required] The ID of the post being looked up
 * @param $category (array) [required] Limit expert categories to selected category, defaults to post categories
 *
 * @return (integer) Number of comments by an expert
 */
function lookup_expert_comments_count($post_id, $categories) {
    global $wpdb;
    $roles = new WP_Roles();
    $roles = $roles->role_objects;
    $experts = array();
    foreach($roles as $role) {
        if($role->has_cap("post_as_expert"))
            $experts[] = trim($role->name);
    }
    $expert_list = implode("|", $experts);
    $query = "SELECT COUNT(DISTINCT c.comment_ID) AS count FROM {$wpdb->comments} AS c ";
    $query .= "JOIN {$wpdb->usermeta} AS m ON m.user_id = c.user_id AND m.meta_key = 'um-taxonomy-category' ";
    // if (sizeof($categories) == 1) {
    //     $query .= "AND {$categories[0]} IN (m.meta_value) ";
    // } else {
    //     $query .= "AND (";
    //     foreach($categories as $key => $category) {
    //         if ($key != 0) {$query .= "OR ";}
    //         $query .= "$category IN (m.meta_value) ";
    //     }
    //     $query .= ") ";
    // }
    $query .= "JOIN {$wpdb->usermeta} AS m2 ON m2.user_id = c.user_id AND m2.meta_key = '{$wpdb->prefix}capabilities' AND m2.meta_value REGEXP '$expert_list' ";
    $query .= "WHERE c.comment_post_ID = $post_id";
    $return = $wpdb->get_results($wpdb->prepare($query));
    return $return[0]->count;
}

/**
 * Sanitizes text of any profanity
 * 
 * @param string $text
 * @uses WP Content Filter plugin [required]
 */
function sanitize_text($text) {
	
	if(function_exists('pccf_filter')){
		
		return pccf_filter($text);
	} 
	
	return $text;
}


function the_truncated_title($length = 100){

    $title = get_the_title();

    if (strlen($title) > $length) $title = substr($title, 0, $length) . "...";

    echo $title;

}

function truncated_text($text, $length = 100) {

	 if (strlen($text) > $length) $text = substr($text, 0, $length) . "...";

    	return $text;
}


/**
 * Horrible clusterfuck that generates a shitty omniture string - which we'll probably need to completely redo anyway.
 *
 * The Section rewrite rules interfere with the ability to retrieve original query data at the time of enqueueing scripts.
 * If and WHEN we're asked to rewrite omniture - this problem should be solved from within Section/WidgetPress first.
 *
 * @author Eddie Moya
 */
function get_omniture($post_id = null){

    global $wp_query;
    //echo "<pre>";print_r($wp_query);echo "</pre>";
    if(is_single()){
        global $post;
    }

    if(is_null($post_id)){
        $post_id = $post->ID;
    }

    $omchannel = array();

    if(is_front_page()){
        $omchannel[2] = "Home";
    }



    //If is archive...
    if(is_archive()){

        //If is a post type archive
        if(is_post_type_archive()){

            $post_type = (get_query_var('post_type') != 'section') ? get_query_var('post_type') : get_query_var('old_post_type');
            $omchannel[0] = (is_array($post_type)) ? $post_type[0] : $post_type;
        }

        //If category archive..
        if(is_category()){
            $omchannel[1] =  single_cat_title('', false);
        }

        if(is_search() && isset($_GET['s'])){
            $omchannel[2] = "Search";
        }
        //echo "<pre>";print_r(get_query_var('old_post_type'));echo "</pre>";

    }
    

    // index 2 is the blog post title
    if(is_single()){
        if(get_query_var('post_type') == 'section'){

            $post_type = get_query_var('old_post_type');
            $omchannel[0] = (is_array($post_type)) ? $post_type[0] : $post_type;


            $category_name = get_query_var('old_category');

            if(!empty( $category_name )){
              $omchannel[1] = get_category_by_slug($category_name)->name;
            } 


        } else {
            $omchannel[0] = $post->post_type;

            $category = get_the_category($post_id);
            $omchannel[1] = $category[0]->name;

            $omchannel[2] = $post->post_title;
        }
    }

    switch($omchannel[0]){
        case 'post':
            $omchannel[0] = 'Blog Posts';
            break;
        case 'question':
            $omchannel[0] = 'Q&A';
            break;
        case 'guide':
            $omchannel[0] = 'Guides';
            break;
        default:
            unset($omchannel[0]);
    }
    
    $omniture = implode(' > ', $omchannel);
    return $omniture;
}

/**
 * get_last_activity_date() - Given a user's ID returns the 
 * date of the most recent post/comment made by user.
 * 
 * @param int $user_id
 * @return string|bool
 * @author Dan Crimmins
 */
function get_last_activity_date($user_id) {
	
	global $wpdb;
	
	$q = "SELECT GREATEST(MAX(c.comment_date), MAX(p.post_date)) as latest FROM {$wpdb->comments} c, {$wpdb->posts} p
		 WHERE p.post_author = {$user_id} AND c.user_id = {$user_id} AND c.comment_type IN ('', 'answer', 'comment')";
	
	
	return $wpdb->get_var($q);
}

/**
 * return the contents of a http request
 * 
 * @param string $url
 * @return string -- content of the request
 * @author Carl Albrecht-Buehler
 */
function curl_it($url){
    $curl_handle = curl_init();
    curl_setopt( $curl_handle, CURLOPT_URL, $url );
    curl_setopt( $curl_handle, CURLOPT_CONNECTTIMEOUT, 2 );
    curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, 1 );
    $content = curl_exec( $curl_handle );
    curl_close( $curl_handle );
    return $content;
}

/**
 * Modified wp_drop_down_categories for communities - added shcJSL form validation.
 * 
 * @param array $args
 * @return string -- the select element
 * @author Dan Crimmins
 */
function comm_wp_dropdown_categories( $args = '' ) {
	$defaults = array(
		'show_option_all' => '', 'show_option_none' => '',
		'orderby' => 'id', 'order' => 'ASC',
		'show_last_update' => 0, 'show_count' => 0,
		'hide_empty' => 1, 'child_of' => 0,
		'exclude' => '', 'echo' => 1,
		'selected' => 0, 'hierarchical' => 0,
		'name' => 'cat', 'id' => '',
		'class' => 'postform', 'depth' => 0,
		'tab_index' => 0, 'taxonomy' => 'category',
		'hide_if_empty' => false
	);

	$defaults['selected'] = ( is_category() ) ? get_query_var( 'cat' ) : 0;

	// Back compat.
	if ( isset( $args['type'] ) && 'link' == $args['type'] ) {
		_deprecated_argument( __FUNCTION__, '3.0', '' );
		$args['taxonomy'] = 'link_category';
	}

	$r = wp_parse_args( $args, $defaults );

	if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
		$r['pad_counts'] = true;
	}

	$r['include_last_update_time'] = $r['show_last_update'];
	extract( $r );

	$tab_index_attribute = '';
	if ( (int) $tab_index > 0 )
		$tab_index_attribute = " tabindex=\"$tab_index\"";

	$categories = get_terms( $taxonomy, $r );
	$name = esc_attr( $name );
	$class = esc_attr( $class );
	$id = $id ? esc_attr( $id ) : $name;

	if ( ! $r['hide_if_empty'] || ! empty($categories) )
		$output = "<select name='$name' id='$id' class='$class clearfix' shc:gizmo:form=\"{required:true}\" $tab_index_attribute>\n";
	else
		$output = '';

	if ( empty($categories) && ! $r['hide_if_empty'] && !empty($show_option_none) ) {
		$show_option_none = apply_filters( 'list_cats', $show_option_none );
		$output .= "\t<option value='default' selected='selected'>$show_option_none</option>\n";
	}

	if ( ! empty( $categories ) ) {

		if ( $show_option_all ) {
			$show_option_all = apply_filters( 'list_cats', $show_option_all );
			$selected = ( '0' === strval($r['selected']) ) ? " selected='selected'" : '';
			$output .= "\t<option value='0'$selected>$show_option_all</option>\n";
		}

		if ( $show_option_none ) {
			$show_option_none = apply_filters( 'list_cats', $show_option_none );
			$selected = ( 'default' === strval($r['selected']) ) ? " selected='selected'" : '';
			$output .= "\t<option value='default'$selected>$show_option_none</option>\n";
		}

		if ( $hierarchical )
			$depth = $r['depth'];  // Walk the full depth.
		else
			$depth = -1; // Flat.

		$output .= walk_category_dropdown_tree( $categories, $depth, $r );
	}
	if ( ! $r['hide_if_empty'] || ! empty($categories) )
		$output .= "</select>\n";


	$output = apply_filters( 'wp_dropdown_cats', $output );

	if ( $echo )
		echo $output;

	return $output;
}

function lookup_stylesheet() {
	$current = get_queried_object();
	
	$css [] = theme_option("brand");
	
	if ($current->term_taxonomy_id && $checked_categories[$current->term_taxonomy_id] != "true") {
		if ($current->parent != 0 && $checked_categories[$current->parent] != "true") {
			$checked_categories[$current->parent] = "true";
			$parent_taxonomy = get_term_by("ID", "$current->parent", "category");
			$css[] = $parent_taxonomy->slug;
		}
		$checked_categories[$current->term_taxonomy_id] = "true";
		$css[] = $current->slug;
	} else {
		$categories = wp_get_post_categories($current->ID);
		if (!empty($categories)) {
			foreach($categories as $category) {
				if ($checked_categories[$category] != "true") {
					$cat_data = get_term_by("ID", "$category", "category");
					if ($cat_data->parent != 0 && $checked_categories[$cat_data->parent] != "true") {
						$checked_categories[$cat_data->parent] = "true";
						$parent_data = get_term_by("ID", "$cat_data->parent", "category");
						$css_parents[] = $parent_data->slug;
					}
					$checked_categories[$cat_data->term_id] = "true";
					$css_current[] = $cat_data->slug;
				}
			};
			$css = array_merge((array)$css, (array)$css_parents);
			$css = array_merge((array)$css, (array)$css_current);
		}
	}
	foreach (array_reverse($css) as $file) {
		if (file_exists(get_stylesheet_directory()."/assets/css/$file.css")) {
			return get_template_directory_uri()."/assets/css/$file.css";
		}
	}
}

/**
 * Modified wp_drop_down_categories for communities - added shcJSL form validation.
 * 
 * @param array $args
 * @return string -- the select element
 * @author Dan Crimmins
 */
function get_oembed_object($url, $w = NULL, $h = NULL)
{
    require_once(ABSPATH . 'wp-includes/class-oembed.php');
    $param = array();

    if(!is_null($w))
    {
        $param['width'] = $w;
    }

    if(!is_null($h))
    {
        $param['height'] = $h;
    }
    
    $oembed= new WP_oEmbed;
    $provider = $oembed->discover($url);

    $video = $oembed->fetch($provider, $url, $param);
    return $video;
}

function get_oembed_thumbnail($url, $pt = "https", $w = NULL, $h = NULL)
{
    $video = get_oembed_object($url, $w, $h);

    $title = $video->title;
    $html = $video->html;
    $thumb = str_replace("http://", "$pt://", $video->thumbnail_url);

    return "<img alt='$title' src='$thumb' />";
}

function get_excerpt_by_id($post_id){
    $the_post = get_post($post_id); //Gets post ID

    $the_excerpt = (!empty($the_post->post_excerpt)) ? $the_post->post_excerpt : $the_post->post_content; //Gets post_content to be used as a basis for the excerpt
    $excerpt_length = 35; //Sets excerpt length by word count
    $the_excerpt = strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
    $the_excerpt = str_replace(array("'", '"'), "", $the_excerpt);
    $the_excerpt = trim(preg_replace( '/\t+|\n+|\s+|\r+/', ' ', $the_excerpt ));

    $words = explode(' ', $the_excerpt, $excerpt_length + 1);

    if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '…');
        $the_excerpt = implode(' ', $words);
    endif;

    return $the_excerpt;
}

function meta_description(){
    global $wp_query;

    if ( !$id = $wp_query->get_queried_object_id() )
        return;

    if('section' == $wp_query->query['post_type']){
        //is category
        $term = wp_get_object_terms($id, 'category');
    

        if(empty($term)){
            $term = wp_get_object_terms($id, 'skcategory');
        }

        $description = $term[0]->description;
    } else {

        if(empty($term) && is_single() ){
            $description = get_excerpt_by_id($wp_query->post->ID);
            $description = esc_html($description);
        }
    }

    if(empty($description)) {
          $description = get_bloginfo('description');
        //$description = 'single';
    }

    //print_pre($wp_query);

    echo $description;
}