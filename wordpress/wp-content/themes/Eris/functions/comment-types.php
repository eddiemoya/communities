<?php 
/**
 * Custom Comment Types 
 */
if (class_exists('CCT_Controller_Comment_Types')) {
    
    /**
     * 
     */
     function register_flags() {
         $args = array(
             'labels' => array(
                 'name' => _x('Flags', 'post type general name'),
                 'singular_name' => _x('Flag', 'post type singular name'),
                 'plural' => 'Flags',
                 'add_new' => _x('Add New', 'flag'),
                 'add_new_item' => __('Add New Flag'),
                 'edit_item' => __('Edit Flag'),
                 'new_item' => __('New Flag'),
                 'all_items' => __('All Flags'),
                 'view_item' => __('View Flags'),
                 'search_items' => __('Search Flags'),
                 'not_found' => __('No flags found'),
                 'not_found_in_trash' => __('No flags found in Trash'),
                 'parent_item_colon' => 'Flag:',
                 'menu_name' => 'Flags'
             ),
             'parent_domain' => 'post',
             'parent_type' => 'question',
             'capability' => 'moderate_flags',
             'menu_icon' => get_template_directory_uri() . '/assets/img/admin/flags_admin_icon.gif',
             'menu_position' => 8,
             'template' => get_template_directory_uri() . '/parts/flags.php'
         );

         CCT_Controller_Comment_Types::register_comment_type('flag', $args);
     }
     add_action('init', 'register_flags', 11);

    /**
     * 
     */
    function register_answers() {
        $args = array(
            'labels' => array(
                'plural' => 'Answers',
                'name' => _x('Answers', 'post type general name'),
                'singular_name' => _x('Answer', 'post type singular name'),
                'add_new' => _x('Add New', 'answer'),
                'add_new_item' => __('Add New Answer'),
                'edit_item' => __('Edit Answer'),
                'new_item' => __('New Answer'),
                'all_items' => __('All Answers'),
                'view_item' => __('View Answers'),
                'search_items' => __('Search Answers'),
                'not_found' => __('No answers found'),
                'not_found_in_trash' => __('No answers found in Trash'),
                'parent_item_colon' => 'Question:',
                'menu_name' => 'Answers'
            ),
            'parent_domain' => 'post',
            'parent_type' => 'question',
            'capability' => 'moderate_answers',
            'menu_position' => 9,
            'template' => get_template_directory_uri() . '/parts/flags.php'
        );

        CCT_Controller_Comment_Types::register_comment_type('answer', $args);
    }

    add_action('init', 'register_answers', 11);


    function register_comments() {
        $args = array(
            'labels' => array(
                'name' => _x('Comments', 'post type general name'),
                'singular_name' => _x('Answer', 'post type singular name'),
                'add_new' => _x('Add New', 'answer'),
                'add_new_item' => __('Add New Answer'),
                'edit_item' => __('Edit Answer'),
                'new_item' => __('New Answer'),
                'all_items' => __('All Answers'),
                'view_item' => __('View Answers'),
                'search_items' => __('Search Answers'),
                'not_found' => __('No answers found'),
                'not_found_in_trash' => __('No answers found in Trash'),
                'parent_item_colon' => 'Question:',
                'menu_name' => 'Answers'
            ),
            'parent_domain' => 'post',
            'parent_type' => 'question',
            'capability' => 'moderate_comments',
            'menu_position' => 9,
            'template' => get_template_directory_uri() . '/parts/flags.php'
        );

        CCT_Controller_Comment_Types::register_comment_type('comment', $args);
    }

    //add_action('init', 'register_comments', 11);

}


/**
 *
 * @param type $is_answer
 * @param type $comment_type
 * @param type $comment_data
 * @param type $parent
 * @return boolean 
 */

function set_answers_comment_type($is_answer, $comment_type, $comment_data, $parent){
    
    $is_answer = false;
    if($_REQUEST['comment_type'] == 'answer'){
        $is_answer = true;
    }
   
    return $is_answer;
}

function set_flags_comment_type($is_flag, $comment_type, $comment_data, $parent){
    
    $is_flag = false;
    if($_REQUEST['comment_type'] == 'flag'){
        $is_flag = true;
    }
   
    return $is_flag;
}

function set_comment_comment_type($is_comment, $comment_type, $comment_data, $parent){
    
    $is_comment = false;
    if($_REQUEST['comment_type'] == 'comment'){
        $is_comment = true;
    }
   
    return $is_flag;
}

add_filter('cct_condition_answer', 'set_answers_comment_type', 10, 4);
add_filter('cct_condition_flag', 'set_flags_comment_type', 10, 4);
add_filter('cct_condition_comment', 'set_comment_comment_type', 10, 4);


function organizeByChildren($comments) {
	
	if(! is_admin()){
		
	    if(isset($comments) && !empty($comments)) {
	        foreach($comments as $key=>$comment) {
	            if(isset($comment->comment_parent) && $comment->comment_parent != '0' && $comment->comment_parent != '') {
	                $children[$comment->comment_parent][] = $comment;
	
	                unset($comments[$key]);
	            }
	        }
	
	        if(isset($children) && !empty($children)) {
	            foreach($comments as $comment) {
	                if(array_key_exists($comment->comment_ID, $children)) {
	                    foreach($children[$comment->comment_ID] as $child) {
	                        $comment->children[] = $child;
	                    }
	
	                    // ensure oldest child comment is first
	                    $comment->children = array_reverse($comment->children);
	                }
	            }
	        }
	    }
	}

    return $comments;
}
//add_filter('the_comments', 'organizeByChildren');