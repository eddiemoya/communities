<?php
/**
 * @author Eddie Moya
 * 
 * Ajax callback for getting subcategories
 */
function get_subcategories_ajax(){

    if(isset($_POST['category_id'])){
        $parent = absint((int)$_POST['category_id']);
            wp_dropdown_categories(array(
                'depth'=> 1,
                'child_of' => $parent,
                'hierarchical' => true,
                'hide_if_empty' => true,
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
    exit;
    
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
                $wp_query['s'] = $_GET['s'];
            }
            $wp_query = new WP_Query($query);

        loop($_POST['template']);
        wp_reset_query();

    } else {
        echo "<!-- No template selected -->";
    }


    exit;
    
}

add_action('wp_ajax_nopriv_get_posts_ajax', 'get_posts_ajax');
add_action('wp_ajax_get_posts_ajax', 'get_posts_ajax');

/**
 * @author Jason Corradino
 * 
 */
function get_filtered_authors_ajax(){
    global $wpdb;
    $category = ($_POST["category"] == -1) ? array() : array($_POST["category"]);
    $roles = new WP_Roles();
    $roles = $roles->role_objects;
    $experts = array();
    foreach($roles as $role) {
        if($role->has_cap("post_as_expert"))
            $experts[] = trim($role->name);
    }
    $query = Results_List_Widget::get_user_role_tax_intersection(array('hide_untaxed' => false, 'roles' => $experts, 'terms' => $category));
    $users = $wpdb->get_results($wpdb->prepare($query));
    get_partial('widgets/results-list/author-filtered-list', array('users' => $users));
    exit;
}

add_action('wp_ajax_nopriv_get_filtered_authors_ajax', 'get_filtered_authors_ajax');
add_action('wp_ajax_get_filtered_authors_ajax', 'get_filtered_authors_ajax');

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
            
            /*$activities = $user_activities->page($page)
                                            ->get_user_posts_by_type($type)
                                            ->posts;*/
        	
	        if($type == 'question') {
					
					$activities = $user_activities->page($page)
													->get_user_posts_by_type($type)
													->get_expert_answers()
													->posts;
													
						/*echo '<pre>';
						var_dump($activities);
						exit;*/							
				
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

    echo $comment_id.' is the comment';
}
add_action('wp_ajax_flag_me', 'ajaxify_comments');
add_action('wp_ajax_nopriv_flag_me', 'ajaxify_comments');