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
            //'echo' => false
        ));
         exit;
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
        $wp_query = new WP_Query(array('cat' => $_POST['category']));
        
        loop_by_type($_POST['template']);
        wp_reset_query();


    } else {
        echo "<!-- No template selected -->";
    }


    exit;
    
}

add_action('wp_ajax_nopriv_get_posts_ajax', 'get_posts_ajax');
add_action('wp_ajax_get_posts_ajax', 'get_posts_ajax');


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
            
            $activities = $user_activities->page($page)
                                            ->get_user_posts_by_type($type)
                                            ->posts;
                                            
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