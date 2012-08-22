<?php
/**
 * General use loop function. Allows for a template to be selected. Currently 
 * defaults to product template because that is used by our themes most often.
 * 
 * @author Eddie Moya
 * 
 * @global type $wp_query
 * @param type $template [optional] Template part to be used in the loop.
 *
 * @return void.
 */
function loop($template = 'post', $special = null){
    global $wp_query;

    $template = (isset($special)) ? $template.'-'.$special : $template;

    if (have_posts()) {
        while (have_posts()) {
            the_post();
            get_template_part('parts/'.$template);
        }
    }

    wp_reset_query();

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
            // print_pre($template);
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
function process_front_end_question(){
	
	
    //If step 1 - return that we should move on to step 2.
    if( wp_verify_nonce( $_POST['_wpnonce'], 'front-end-post_question-step-1' ) || (isset($_POST['new_question_step_1']))){

        //If user is logged in - step 2
        if(is_user_logged_in() && ! empty($_POST['post-question'])) {
			
			
        	return array('step'		=> '2',
        				'errors'	=> null);
        	
            
        } else {
            /**
             * Kick off login modal SSO login crazyness here 
             */
            return array('step'		=> '1',
        				'errors'	=> array('Please enter a question.'));
        }
    }

    //If step 2, add the post and move to step 3
    if((wp_verify_nonce( $_POST['_wpnonce'], 'front-end-post_question-step-2' ) && is_user_logged_in())) {
		
    	global $current_user;
		get_currentuserinfo();
		
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
	    							/*wp_update_user(array('ID'				=> $current_user->ID,
		 								 				'user_nicename' 	=> $_POST['screen-name']));*/
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
		
		        wp_insert_post($post); 
		        do_action('wp_insert_post', 'wp_insert_post'); 
		        
		        	
		       	
		        
		        return array('errors' => null, 'step' => '3');
		        
	    } else {
	    	
	    	return array('errors' => $errors, 'step' => '2');
	    }
    
    }

    //Neither step has been taken, were on step 1
    return array('errors' => null, 'step' => '1');
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
 * Return a user's screen name (user_nicename).
 *
 *
 * @author Carl Albrecht-Buehler
 * @param $user_id (integer) [required] ID of the user whose screen name you want to look up.
 *
 * @return (string) User's screen name (user_nicename).
 */
function return_screenname( $user_id ) {
    $user_info = get_userdata( $user_id );
    return $user_info->user_nicename;
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
 * @return (string) User's screen name (user_nicename) formatted into an HTML anchor..
 */
function return_screenname_link( $user_id ) {
    return '<a href="' . get_author_posts_url( $user_id ) . '">' . return_screenname( $user_id ) . '</a>';
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
 * Detects AJAX request, returns true, else returns false
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

function update_user_nicename($uid, $name) {
	
	global $wpdb;
	$user = $wpdb->base_prefix . 'users';
	
		$update = $wpdb->update($user, 
								array('user_nicename' => $name),
								array('ID' => $uid));
						
		return ($update) ? true : false;
}



