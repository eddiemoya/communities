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
 * 
 * @author Dan Crimmins
 * @param int $user_id - WP User ID
 * @return bool
 */
function has_screen_name($user_id) {
	
	if(get_user_meta($user_id, 'profile_screen_name', true)) {
		
		return true;
	}
	
		return false;
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
    if((wp_verify_nonce( $_POST['_wpnonce'], 'front-end-post_question-step-1' ) || isset($_POST['new_question_step_1'])) && (! isset($_POST['question-post-complete']))){

        //If user is logged in - step 2
        if(is_user_logged_in() && ! empty($_POST['post-question'])) {
			
			
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
    if((wp_verify_nonce( $_POST['_wpnonce'], 'front-end-post_question-step-2' ) && is_user_logged_in()) && ! isset($_POST['cancel'])) {
		
		$valid = true;
    	$errors = array();
    	
    	if(empty($_POST['your-question'])) {
    		
    		$errors['your-question'] = 'Please enter a question.';
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
	    				
	    					$sso_guid = get_user_sso_guid($current_user->ID);
	    					
	    					$profile = new SSO_Profile;
	    					
	    					$response = $profile->update($sso_guid, array('email' => $current_user->user_email ,
	    																 'screen_name' => $_POST['screen-name']));
	    					
	    						//Check for error
	    						if(isset($response['code'])) {
	    							
	    							$valid = false;
	    							
	    							$errors['screen-name'] = $response['message'];
	    							
	    						} else {
	    							
	    							//Add user meta for screen name
	    							update_user_meta($current_user->ID, 'profile_screen_name', $_POST['screen-name']);
	    							
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
		
		        $title =  wp_kses($_POST['your-question'], array(), array());
		        $content = wpautop(wp_kses($_POST['more-details'], array(), array()));
		
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
		       	
		       /*	echo '<pre>';
		       	var_dump($current_user);
		       	exit;*/
		       	
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
function get_terms_by_post_type($taxonomy = 'category',$post_type = 'post'){

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
    if ( !has_screen_name( $user_id ) ) {
        $link = home_url( '/' ) . '?author=' . $user_id;
    }
    else {
        $link = get_author_posts_url( $user_id );
    }
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
    $i = 0;
    
    $city  = get_user_meta( $user_id, 'user_city', true );
    $state = get_user_meta( $user_id, 'user_state', true );
    
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
	
	$sso_guid = get_user_sso_guid($current_user->ID);
		    					
	$profile = new SSO_Profile;
	    					
	$response = $profile->update($sso_guid, array('email' => $current_user->user_email,
    											  'screen_name' => $screen_name));
		
	//Check for error
	if(isset($response['code'])) {
			
		return $response;
			
	} else {
			
		//Add user meta for screen name
		update_user_meta($current_user->ID, 'profile_screen_name', $screen_name);
			
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

/*
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
