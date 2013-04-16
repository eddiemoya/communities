<?php
/**
 * @author Eddie Moya
 * 
 * Ajax callback for getting subcategories
 */
function get_subcategories_ajax(){

    if(isset($_POST['category_id']) && $_POST['category_id'] != 'default'){
        $hide_empty = ($_POST['hide_empty'] == "true") ? true : false;
        $parent = absint((int)$_POST['category_id']);

            wp_dropdown_categories(array(
                'depth'=> 1,
                'child_of' => $parent,
                'hierarchical' => true,
                'hide_if_empty' => true,
                'hide_empty' => $hide_empty,
            	'orderby'	=> 'name',
				'order'	=> 'ASC',
                'class' => 'input_select',
                'name' => 'sub-category',
                'id' => 'sub-category',
                'echo' => true
            ));

        exit();
    }
}
add_action('wp_ajax_get_subcategories_ajax', 'get_subcategories_ajax');
add_action('wp_ajax_nopriv_get_subcategories_ajax', 'get_subcategories_ajax');


/**
 * @author Eddie Moya
 * 
 */
function get_template_ajax(){
    if( isset($_POST['template']) ){
        get_template_part($_POST['template']);
    } else {
        echo "<!-- No template selected -->";
    }
    exit();
    
}

add_action('wp_ajax_nopriv_get_template_ajax', 'get_template_ajax');
add_action('wp_ajax_get_template_ajax', 'get_template_ajax');

/**
 * @author Eddie Moya
 * 
 */
function get_posts_ajax(){
    if( isset($_POST['template']) ){
        global $wp_query;
       
            $query['cat'] = $_POST['category'];

            if(isset($_GET['s'])) { 
                $query['s'] = $_GET['s'];
            }

            if(isset($_POST['post_type'])){
                $query['post_type'] = array($_POST['post_type']);
            }

            if(isset($_POST['order'])){
                $query['order'] = $_POST['order'];
            }

            if(isset($_POST['orderby'])){
                $query['orderby'] = $_POST['orderby'];
            }

            $wp_query = new WP_Query($query);
        $path = (isset($_POST['path'])) ? $_POST['path'] : 'parts';
      
        loop(array($_POST['template'], 'post'), $_POST['special'], $path, 'parts/no-results');
        wp_reset_query();

    } else {
        echo "<!-- No template selected -->";
    }
    exit();
}

add_action('wp_ajax_nopriv_get_posts_ajax', 'get_posts_ajax');
add_action('wp_ajax_get_posts_ajax', 'get_posts_ajax');



/**
 * @author Eddie Moya
 * 
 */
function get_post_by_id(){
    if( isset($_POST['id']) ){

    	if( isset($_POST['post_type']) && $_POST['post_type'] == 'product'){
     		$product = new Products_Model();
     		$product = $product->get_by_id($_POST['id'])->products[0];
     		
     		get_partial('widgets/product-widget/product', array(
				'class' => $_POST['css_class'],
				'd' => $product,
			));
    	}
  	}
    exit();
}

add_action('wp_ajax_nopriv_get_post_by_id', 'get_post_by_id');
add_action('wp_ajax_get_post_by_id', 'get_post_by_id');




/**
 * @author Eddie Moya
 * 
 */
function get_users_ajax(){

    $category = array($_POST['category']);
    $hide_untaxed = ($category > 0);
    
    if(class_exists('Results_List_Widget')){
        $users = Results_List_Widget::query_users(array('hide_untaxed' => $hide_untaxed, 'terms' => $category, 'cap' => 'post_as_expert', 'order' => $_POST['order']));
        get_partial($_POST['path'].'/'.$_POST['template'], array('users' => $users));
    }
    
    exit();
}

add_action('wp_ajax_nopriv_get_users_ajax', 'get_users_ajax');
add_action('wp_ajax_get_users_ajax', 'get_users_ajax');

/**
 * @author Dan Crimmins
 * 
 * Handles 'More' ajax pagination requests
 * 
 * @returns string - HTML output
 */
function profile_paginate() {
     
    $uid = $_POST['uid'];
    $type = $_POST['type'];
    $page = $_POST['page'];
    
    
    require_once get_template_directory() . '/classes/communities_profile.php';
    
    //User Profile object
    $user_activities = new User_Profile($uid);
    
    ob_start();
    
        //Comments
        if($type == 'answer' || $type == 'comment') {
            
            $activities = $user_activities->page($page)
                                            ->get_user_comments_by_type($type)
                                            ->comments;
                  
                                                                                                        
            include(get_template_directory() . '/parts/profile-comments.php');
        }
        
        //Posts
        if($type == 'posts' || $type == 'guides' || $type == 'question') {
            
        	
	        if($type == 'question') {
					
					$activities = $user_activities->page($page)
													->get_user_posts_by_type($type)
													->get_expert_answers()
													->posts;
													
				
				} else {
					
					$activities = $user_activities->page($page)
													->get_user_posts_by_type($type)
													->posts;
												
				}
                                            
            include(get_template_directory() . '/parts/profile-posts.php');
        }
        
        //Actions
        if($type == 'follow' || $type == 'upvote') {
            
            $activities = $user_activities->page($page)
                                            ->get_actions($type)
                                            ->activities;
            
            include(get_template_directory() . '/parts/profile-recent.php');
        }
        
        if($type == 'recent') {
            
            $activities = $user_activities->page($page)
                                            ->get_recent_activities()
                                            ->activities;
                                            
            include(get_template_directory() . '/parts/profile-recent.php');
        }
        
        if($type == 'review') {
        	
       		 if(is_plugin_active('products/plugin.php')) {
				
				$activities = $user_activities->page($page)
												->get_reviews()
												->reviews;
			 }
			 
			 include(get_template_directory() . '/parts/profile-reviews.php');
        }
        
    $output = ob_get_clean();
    
    echo $output;
    
    die;
}

add_action('wp_ajax_profile_paginate', 'profile_paginate');
add_action('wp_ajax_nopriv_profile_paginate', 'profile_paginate');

/**
 * Validates a screen name. Returns 'true' if it is valid and 
 * available; false if not.
 * 
 * @author Dan Crimmins
 * @param string $screen_name
 * @return string - 'true' or 'false'
 */
function validate_screen_name() {
	
	$screen_name = $_POST['screen_name'];
	
	if(class_exists('SSO_Profile')) {
		
		$profile = new SSO_Profile;
		$response = $profile->validate_screen_name($screen_name);
		
		echo ($response['code'] == '200') ? 'true' : 'false';
		
		exit;
	}
	
		
	exit;
}

add_action('wp_ajax_validate_screen_name', 'validate_screen_name');
add_action('wp_ajax_nopriv_validate_screen_name', 'validate_screen_name');

function ajaxify_comments() {
    global $current_user;
    get_currentuserinfo();

    $data = array(
    	'comment_post_ID'  => $_POST['comment_post_ID'],
    	'comment_content'  => $_POST['comment'],
    	'comment_date'     => date('Y-m-d H:i:s'),
    	'comment_date_gmt' => date('Y-m-d H:i:s'),
    	'comment_approved' => 1,
        'comment_type'     => 'flag'
    );

    if(is_user_logged_in()) {
        $data['user_id'] = $current_user->ID;
    }

    $comment_id = wp_insert_comment($data);

    echo $comment_id;
    exit;
}
add_action('wp_ajax_flag_me', 'ajaxify_comments');
add_action('wp_ajax_nopriv_flag_me', 'ajaxify_comments');


/**
 * Sets comment_approve
 * @author Dan Crimmins
 */
function user_delete_comment() {
	
	global $wpdb;
	
	$comment_id = $_POST['cid'];
	$uid = $_POST['uid'];
	
	if(wp_verify_nonce($_POST['_wp_nonce'], 'comment_delete_' . $comment_id . '_' . $uid)) {
		
		$update = $wpdb->update($wpdb->comments, 
							array('comment_approved' => '0'),
							array('comment_ID' => $comment_id));
	
		echo ($update) ? $comment_id : 0;
		
	} else {
		
		echo 0;
	}
	
	exit;
}

add_action('wp_ajax_user_delete_comment', 'user_delete_comment');
add_action('wp_ajax_nopriv_user_delete_comment', 'user_delete_comment');


/**
 *  comm_vote_poll() - replaces vote_poll() in the Polls plugin. 
 *  This fixes the issue with an
 *  error message being displayed when hitting the back button after voting 
 *  and resubmitting a vote. Also, this is the function that is used on the AJAX call when voting.
 *  
 *  @author Dan Crimmins
 *  @param void
 *  @return string - the html for the poll results.
 */


remove_action('wp_ajax_polls', 'vote_poll');
remove_action('wp_ajax_nopriv_polls', 'vote_poll');
add_action('wp_ajax_polls', 'comm_vote_poll');
add_action('wp_ajax_nopriv_polls', 'comm_vote_poll');

function comm_vote_poll() {
	global $wpdb, $user_identity, $user_ID;
	
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'polls')
	{
		// Load Headers
		polls_textdomain();
		header('Content-Type: text/html; charset='.get_option('blog_charset').'');		
		
		// Get Poll ID
		$poll_id = (isset($_REQUEST['poll_id']) ? intval($_REQUEST['poll_id']) : 0);
		
		// Ensure Poll ID Is Valid
		if($poll_id == 0)
		{
			_e('Invalid Poll ID', 'wp-polls');
			exit();
		}
		
		// Verify Referer
		if(!check_ajax_referer('poll_'.$poll_id.'-nonce', 'poll_'.$poll_id.'_nonce', false))
		{
			_e('Failed To Verify Referrer', 'wp-polls');
			exit();
		}
		
		// Which View
		switch($_REQUEST['view'])
		{
			// Poll Vote
			case 'process':				
				$poll_aid = $_POST["poll_$poll_id"];
				$poll_aid_array = array_unique(array_map('intval', explode(',', $poll_aid)));
				if($poll_id > 0 && !empty($poll_aid_array) && check_allowtovote()) {
					$check_voted = check_voted($poll_id);
					if($check_voted == 0) {
						if(!empty($user_identity)) {
							$pollip_user = htmlspecialchars(addslashes($user_identity));
						} elseif(!empty($_COOKIE['comment_author_'.COOKIEHASH])) {
							$pollip_user = htmlspecialchars(addslashes($_COOKIE['comment_author_'.COOKIEHASH]));
						} else {
							$pollip_user = __('Guest', 'wp-polls');
						}
						$pollip_userid = intval($user_ID);
						$pollip_ip = get_ipaddress();
						$pollip_host = esc_attr(@gethostbyaddr($pollip_ip));
						$pollip_timestamp = current_time('timestamp');
						// Only Create Cookie If User Choose Logging Method 1 Or 2
						$poll_logging_method = intval(get_option('poll_logging_method'));
						if($poll_logging_method == 1 || $poll_logging_method == 3) {
							$cookie_expiry = intval(get_option('poll_cookielog_expiry'));
							if($cookie_expiry == 0) {
								$cookie_expiry = 30000000;
							}
							$vote_cookie = setcookie('voted_'.$poll_id, $poll_aid, ($pollip_timestamp + $cookie_expiry), COOKIEPATH);						
						}
						$i = 0;
						foreach($poll_aid_array as $polla_aid) {
							$update_polla_votes = $wpdb->query("UPDATE $wpdb->pollsa SET polla_votes = (polla_votes+1) WHERE polla_qid = $poll_id AND polla_aid = $polla_aid");
							if(!$update_polla_votes) {
								unset($poll_aid_array[$i]);
							}
							$i++;
						}
						$vote_q = $wpdb->query("UPDATE $wpdb->pollsq SET pollq_totalvotes = (pollq_totalvotes+".sizeof($poll_aid_array)."), pollq_totalvoters = (pollq_totalvoters+1) WHERE pollq_id = $poll_id AND pollq_active = 1");
						if($vote_q) {
							foreach($poll_aid_array as $polla_aid) {
								$wpdb->query("INSERT INTO $wpdb->pollsip VALUES (0, $poll_id, $polla_aid, '$pollip_ip', '$pollip_host', '$pollip_timestamp', '$pollip_user', $pollip_userid)");
							}
							echo display_pollresult($poll_id,$poll_aid_array, false);
						} else {
							printf(__('Unable To Update Poll Total Votes And Poll Total Voters. Poll ID #%s', 'wp-polls'), $poll_id);
						} // End if($vote_a)
					} else {
						//printf(__('You Had Already Voted For This Poll. Poll ID #%s', 'wp-polls'), $poll_id);
						echo display_pollresult($poll_id, 0, false);
					}// End if($check_voted)
				} else {
					printf(__('Invalid Poll ID. Poll ID #%s', 'wp-polls'), $poll_id);
				} // End if($poll_id > 0 && !empty($poll_aid_array) && check_allowtovote())
				break;
			// Poll Result
			case 'result':
				echo display_pollresult($poll_id, 0, false);
				break;
			// Poll Booth Aka Poll Voting Form
			case 'booth':
				echo display_pollvote($poll_id, false);
				break;
		} // End switch($_REQUEST['view'])
	} // End if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'polls')
	exit();
}
