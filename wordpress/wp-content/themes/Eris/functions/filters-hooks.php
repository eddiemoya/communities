<?php 
add_filter('bbp_get_topic_content', array(BBcode, 'do_shortcode'));


/*************************************
 * Content, Class, and Query Filters *
 *************************************/

/**
 * Do not call this function directly, add it to the wp_nav_menu filter
 * Adds .first-menu-item and .last-menu-item to menu.
 * 
 * @param type $output
 * @return type 
 */
function add_menu_class_first_last($output) {
  $output = preg_replace('/class="menu-item/', 'class="first-menu-item menu-item', $output, 1);
  //$output = substr_replace($output, 'class="last-menu-item menu-item last-child', strripos($output, 'class="menu-item'), strlen('class="menu-item'));
  return $output;
}
add_filter('wp_nav_menu', 'add_menu_class_first_last');

/**
 * Do not call this function directly, add it to the body_class filter
 * 
 * Conditionally adds classes to the body class of a page for styling purposes.
 * These examples are from the Kmart Fashion and BirthdayClub themes.
 * 
 * @author Eddie Moya
 * 
 * @param type $classes
 * @return array 
 */
function filter_body_class($classes) {
    
    /**
     * Modify Styles pages on theme options. This example is from Kmart Fashion
     */
    $options = get_option('theme_options');
    
    if(isset($options['brand'])){
        $classes[] = $options['brand'];
    }
    
    if (is_category())
        $classes[] = get_queried_object()->category_nicename;

    
    if (is_page())
        $classes[] = 'page-' .get_queried_object()->post_name;    

    if(isset($_GET['s'])){
        $classes[] = 'search-results';
    }

    //Deprecated!!!
    if(get_query_var('old_post_type')){
        $classes[] = 'archive_' . get_query_var('old_post_type').'s';
    }


    if(is_single()){

        /* Post Type classes */


        //If this is a section..
        if('section' == get_post_type()){

            $classes[] = 'archive'; // This in particular will be the case for both category and post type archive shown with sections.

            if(get_query_var('sf_filter'))
            //And it theres a hidden post type...
            if(get_query_var('old_post_type')){

                //Add a bunch of crap....
                $classes[] = 'post-type-archive';
                $classes[] = 'post-type-archive-' . get_query_var('old_post_type');
                $classes[] = 'post-type-' . get_query_var('old_post_type');
            }

        //If this is not a section
        } else {

            $filter = get_query_var('sf_filter');
            // Checking if the new sections are being used - and if the post type is for them or for a contet post type
            if($filter == 'post' || $filter == 'guide' || $filter == 'question' ){
                $classes[] = 'post-type-archive';
                $classes[] = 'post-type-archive-' . $filter;
                $classes[] = 'post-type-' . $filter;
                $classes[] = 'archive_' . $filter . 's';
            }

            //Then simply add the post type to the single page.
            $classes[] = 'post-type-' . get_post_type();

        }

        /* Taxonomy classes */

        if('section' == get_post_type()){

            $terms = array(get_query_var('old_category'));
        } else {

            $terms = wp_get_post_terms(get_queried_object()->ID, 'category', array('fields' => 'slugs'));
        }

        if(!empty($terms[0])){

            if('section' == get_post_type()){
                $classes[] = 'category';
            }

            //Go through each term now
            foreach($terms as $term){

                //Add a class for it...
                $classes[] = "category-{$term}";
            }
        }

    }

    if('section' == get_post_type()){
        $classes = array_diff($classes, array('single', 'single-section'));
    }

    //print_pre($obj);
    
    return $classes;
}
add_filter('body_class', 'filter_body_class');



/**
 * Do not call this function directly, add it to the request filter
 * 
 * Modifies the original WP_Query so that we dont have to continuously re-query 
 * with query_posts from within templates. 
 * 
 * Consider also the 'pre_get_posts', and 'parse_query' filters. As well as
 * other query filters explained in the WP_Query codex page.
 * 
 * @author Eddie Moya
 * 
 * @global WP_Query $wp_query
 * @param WP_Query $query_string
 * @return modified WP_Query object
 */
function custom_primary_query($query = '') {

    /**
     * This is being used for the results list widget.
     */
    if(isset($query->query_vars['is_widget']) && isset($_REQUEST['widget'])){
        if ($query->query_vars['is_widget']['widget_name']== 'results-list' && $_REQUEST['widget'] == 'results-list') {

            $category = (isset($_REQUEST['filter-sub-category'])) ? $_REQUEST['filter-sub-category'] : $_REQUEST['filter-category'];

            unset($query->query_vars['cat']);
            $query->set('cat', $category);
            $query->set('category__in', array($category));
        }
    }

    // if(!empty($query->query_vars['category_name']) && !empty($query->query_vars['post_type']) ){
    //     $query->is_category = false;
    // }
    //return $query;
}
add_action('pre_get_posts', 'custom_primary_query');



/******************************************
 * END  Content, Class, and Query Filters *
 ******************************************/
add_filter( 'widget_form_callback', 'widget_form_extend', 10, 2);
add_filter( 'dynamic_sidebar_params', 'dynamic_sidebar_params' );
add_filter( 'widget_update_callback', 'widget_update', 10, 2 );




/*
 * Depricated?
 */
function widget_form_extend( $instance, $widget ) {

    if(get_class($widget) == 'WP_Widget_Links'){

        if(!isset($instance['classes'])){
            $instance['classes'] = null;
            $row = '';
            $row .= "<p>\n";
            $row .= "\t<label for='widget-{$widget->id_base}-{$widget->number}-sub-title'>Sub Title:</label>\n";
            if(isset($instance['sub-title'])){
                $row .= "\t<input type='text' name='widget-{$widget->id_base}[{$widget->number}][sub-title]' id='widget-{$widget->id_base}-{$widget->number}-sub-title' class='widefat' value='{$instance['sub-title']}'/>\n";
            }
            $row .= "</p>\n";

            echo $row;
        }
    }
    return $instance;
}





function dynamic_sidebar_params( $params ) {
    global $wp_registered_widgets;
    $widget_id  = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];

    $opts = $widget_opt[$widget_num];
    
    //Links Widget (built-in)
    if($widget_obj['name'] == 'Links'){
        $params[0]['after_title'] = 
        "\n\t<h4>".$opts['sub-title']
        ."</h4>".$params[0]['after_title']
        ."\n\t<section class='content-body clearfix'>";

        $params[0]['after_widget'] = 
            '</section>'
            .$params[0]['after_widget'];    
    }
    return $params;
}



function widget_update( $instance, $new_instance ) {
    $instance['sub-title'] = $new_instance['sub-title'];
    return $instance;
}


/**
 * Add/Remove fields from the Contact Info section in users' profiles.
 * 
 * @author Matt Strick
 */
function custom_contact_methods($contactmethods) {
	// Fields to be removed
	//unset($contactmethods['aim']);
	//unset($contactmethods['jabber']);

	// Fields to be added
	$contactmethods['googleplus'] = 'Google+';

	return $contactmethods;
}

add_filter('user_contactmethods', 'custom_contact_methods');


/**
 * Allows periods to be passed as part of a user slug.
 *
 * @author Eddie Moya, Dan Crimmins
 */
function sanitize_title_with_dots_and_dashes($title, $raw_title = '', $context = 'display') {
    $title = strip_tags($title);
    // Preserve escaped octets.
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    // Remove percent signs that are not part of an octet.
    $title = str_replace('%', '', $title);
    // Restore octets.
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

    if (seems_utf8($title)) {
        if (function_exists('mb_strtolower')) {
            $title = mb_strtolower($title, 'UTF-8');
        }   
        $title = utf8_uri_encode($title, 200);
    }

    $title = strtolower($title);
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    
    //Removed this because it was causing 404 on profile page for authors
    //with a . in their screen name
    
    /*if( 'query' == $context ){ 
        $title = str_replace('.', '-', $title); 
    }*/

    if ( 'save' == $context ) {
        // nbsp, ndash and mdash
        $title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );
        // iexcl and iquest
        $title = str_replace( array( '%c2%a1', '%c2%bf' ), '', $title );
        // angle quotes
        $title = str_replace( array( '%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba' ), '', $title );
        // curly quotes
        $title = str_replace( array( '%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d' ), '', $title );
        // copy, reg, deg, hellip and trade
        $title = str_replace( array( '%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2' ), '', $title );
    }

    $title = preg_replace( ( 'query' == $context ) ? '/[^%a-z0-9 ._-]/' : '/[^%a-z0-9 _-]/' , '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');

    return $title;
}
remove_filter('sanitize_title', 'sanitize_title_with_dashes');
add_filter('sanitize_title', 'sanitize_title_with_dots_and_dashes', 10, 3);




//add_action('template_redirect', 'template_check');
// function template_check(){
//     $pt = get_query_var('post_type');

//     if(function_exists('is_widget')){
//         if((!is_widget() && is_category() && ($pt != 'section' && $pt != 'page')) || (is_post_type_archive(array('guide', 'question')) || $pt == 'post' )){
//         $templates = array();

//         if(is_category()){
//             $templates[] = 'archive-tax-'.$pt.'.php';
//             $templates[] = 'archive-tax.php';
//         }

//         $templates[] = 'archive-'.$pt.'.php';
//         $templates[] = "archive.php";
//         $template = get_query_template($template_name, $templates);
//         //echo "<pre>";print_r($templates);echo "</pre>";
//         include( $template );
//         exit;
//         } 
//     }

    
// }

add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
//add_filter( 'image_send_to_editor', 'remove_thumbnail_dimensions', 10 );

function remove_thumbnail_dimensions( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}



add_filter( "the_excerpt", "add_class_to_excerpt" );
function add_class_to_excerpt( $excerpt ) {
    return str_replace('<p', '<p class="content-excerpt"', $excerpt);
}


add_action('init', 'catch_cookies');
function catch_cookies(){
    //echo "<pre>";print_r($_COOKIE);echo "</pre>";
}


// add_filter('widgetpress_widget_classname', 'featured_guide_class_filter');
// function featured_guide_class_filter($classname){
//     if($classname == 'featured-guide') {
//         $classname = 'featured-post';
//     }
//     return $classname;
// }

// add_filter('widgetpress_pre_add_classes', 'featured_question_class_filter');
// function featured_question_class_filter($params){
//     global $wp_registered_widgets;

//     $widget_id  = $params[0]['widget_id'];
//     $widget_obj = $wp_registered_widgets[$widget_id];
//     $widget_opt = get_option($widget_obj['callback'][0]->option_name);
//     $widget_num = $widget_obj['params'][0]['number'];
//     $widget = $widget_opt[$widget_num];

//     echo "<pre>";print_r($widget_obj);echo "</pre>";

//     return $params;
//}

/**
 * Handles posting of comment (of any with screen name
 * @param array - comment data
 * @author Dan Crimmins
 */
function post_comment_screen_name($commentdata) {
	
    
    if(isset($_POST['screen-name'])) {
        
    	//sanitize
    	$clean_screen_name = sanitize_text_field($_POST['screen-name']);

        //Attempt to set screen name
        $response = set_screen_name($clean_screen_name);

        /*var_dump($response);
        exit;*/

        //If setting screen name fails
        if($response !== true) {

            //Create QS
            $qs = '?comment=' . urlencode($_POST['comment']) . '&cid=' . $commentdata['comment_parent'] . '&comm_err=' . urlencode($response['message']);

            //Create return URL
            $linkparts = explode('#', get_comment_link());
            $url = ($commentdata['comment_parent'] == 0) ? $linkparts[0] . $qs .'#commentform' : $linkparts[0] . $qs .'#comment-' .$commentdata['comment_parent'];
            
            //Redirect to return url
            header('Location: ' . $url);
            exit;
        }
        
    }
    
    
    return $commentdata;
    
}

add_filter( 'preprocess_comment',  'post_comment_screen_name');


/**
 * Strips all HTML from all comment content except <a>, <p>, and <br>.
 * 
 * @author Dan Crimmins
 * @param array $commentdata
 * @return array - the commentdata with HTML stripped from comment_content
 */
function clean_comment($commentdata) {
	
	$html = (current_user_can('unfiltered_html_comments')) ? array('a' => array('href' => array(), 'title' => array())) : array();
	
	$commentdata['comment_content'] = wp_kses($commentdata['comment_content'], $html);
	
	return $commentdata;
}

add_filter('preprocess_comment', 'clean_comment');



function limit_search($query) {
	
	if(! isset($_REQUEST['bbp_search'])) { //Do not run on forum search
		
	    if($query->is_search && !is_admin()) {
	    	
	    	$query->set('post_type',array('post','question','guide'));
	    }	
	    
	}
    return $query;
}

add_filter('pre_get_posts','limit_search');



/**
 * Customize widget containers
 */
function filter_before_widget($html, $dropzone, $widget){

    $meta = (object)$widget->get('meta');

    /** Community Menu Widget **/
    if($meta->widgetpress_widget_classname = 'Communities_Menu_Widget'){

        $nav_menu = get_term_by('id', $meta->nav_menu, 'nav_menu');

        $extra_classes = implode(' ', array(
            "menu-{$nav_menu->slug}",
            "layout_{$meta->menu_layout}"
        ));

        $html = str_replace('communities_menu_widget', "communities_menu_widget {$extra_classes}", $html);
    }

    /** Featured Post Widget **/
    if($meta->widgetpress_widget_classname = 'Featured_Post_Widget'){
        $query = get_post($meta->post__in_1);
       // echo "<pre>";print_r($query);echo "</pre>";

        
        if(get_post_format($query->ID)){
            $html = str_replace('featured-post', "featured-post format-" . get_post_format($query->ID), $html);
        }

        $html = str_replace('featured-post', "featured-post featured-post-type-{$query->post_type}", $html);

        // DEPRECATED
        if($query->post_type == 'question'){
            if($meta->limit > 1){
                $html = str_replace('featured-post-type-post', 'featured-category-question', $html);
            } else { 
                $html = str_replace('featured-post-type-question', 'featured-post-type-question featured-question', $html);
            }
        }
    }

    /** Results Widget **/
    if($meta->widgetpress_widget_classname = 'Results_List_Widget'){

        if($meta->query_type == 'users'){
            $html = str_replace('results-list', 'results-list_users', $html);
           }
    }

    /** Taxonomy Widget **/
    if(!empty($meta->tw_featured) && ($meta->tw_featured == 1))
    {
        $html = str_replace("taxonomy-widget", "taxonomy-widget_featured", $html);
    }
    else if(!empty($meta->tw_list_style))
    {
        $html = str_replace("taxonomy-widget", "taxonomy-widget_" . $meta->tw_list_style, $html);
    }

    return $html;
}

add_action('template_redirect', 'template_redirect');

function template_redirect(){

    if(isset($_GET['s'])){
        $templates[] = 'search.php';

        $template = get_query_template($template_name, $templates);
        //echo "<pre>";print_r($templates);echo "</pre>";
        include( $template );
        exit;
        }
}

add_filter('widgetpress_before_widget', 'filter_before_widget', 10, 3);

function disallow_admin_access() {
    global $current_user;

    if(!is_ajax()) {
        $show_admin = (current_user_can("access_admin") || $current_user->caps["administrator"] == 1) ? true : false;
        if (!$show_admin) {
            wp_redirect(home_url());
            exit();
        }
    }
}

add_filter('admin_init', 'disallow_admin_access');

/**
 * When a search query occurs, check for profanity. If there is 
 * profanity, then clear out search, redirect to home with blank search.
 * 
 * @param void
 */
function search_profanity_filter() {
	
	if(isset($_GET['s'])) {
		
		if(strpos(sanitize_text($_GET['s']), '**') !== false) {
			
			$url = home_url('/') . '?s=';
			wp_redirect($url);
		}
	}
}

add_action('init', 'search_profanity_filter');

function force_list_class( $data , $postarr ) {
    $data['post_content'] = str_replace( '<ol>', '<ol class="bullets">', $data['post_content']);
    $data['post_content'] = str_replace( '<ul>', '<ul class="bullets">', $data['post_content']);
    return $data;
}

add_filter( 'wp_insert_post_data' , 'force_list_class' , '99', 2 );

function force_list_class_inline( $data ) {
    $data = str_replace( '<ol>', '<ol class="bullets">', $data);
    $data = str_replace( '<ul>', '<ul class="bullets">', $data);
    return $data;
}
add_filter( 'the_content', 'force_list_class_inline' );
add_filter( 'the_excerpt', 'force_list_class_inline' );

// Possible band-aid fix for caption-codes showing up on the site - Jason
// function the_excerpt_strip_formcodes( $data ) {
//     $data = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $data );
//     return $data;
// }
// add_filter( 'the_excerpt', 'the_excerpt_strip_formcodes' );


/**
 * WP Polls - this replaces the get_poll() function used in a hook in WidgetPress.
 *
 * This function overwrites the call to get_poll() - but only in the WidgetPress compatible version of the widget.
 *
 * The purpose is to make the widget show the form even when users are logged out.
 */


remove_action('widgetpress_compat_wp_polls-get_poll', 'get_poll');
add_action('widgetpress_compat_wp_polls-get_poll', 'comm_get_poll');

function comm_get_poll($temp_poll_id = 0, $display = true){
    global $wpdb, $polls_loaded;
    // Poll Result Link
    if(isset($_GET['pollresult'])) {
        $pollresult_id = intval($_GET['pollresult']);
    } else {
        $pollresult_id = 0;
    }
    $temp_poll_id = intval($temp_poll_id);
    // Check Whether Poll Is Disabled
    if(intval(get_option('poll_currentpoll')) == -1) {
        if($display) {
            echo stripslashes(get_option('poll_template_disable'));
            return;
        } else {
            return stripslashes(get_option('poll_template_disable'));
        }       
    // Poll Is Enabled
    } else {
        // Hardcoded Poll ID Is Not Specified
        switch($temp_poll_id) {
            // Random Poll&& array_key_exists($poll_form_id, $poll_cookie)
            case -2:
                $poll_id = $wpdb->get_var("SELECT pollq_id FROM $wpdb->pollsq WHERE pollq_active = 1 ORDER BY RAND() LIMIT 1");
                break;
            // Latest Poll
            case 0:
                // Random Poll
                if(intval(get_option('poll_currentpoll')) == -2) {
                    $random_poll_id = $wpdb->get_var("SELECT pollq_id FROM $wpdb->pollsq WHERE pollq_active = 1 ORDER BY RAND() LIMIT 1");
                    $poll_id = intval($random_poll_id);
                    if($pollresult_id > 0) {
                        $poll_id = $pollresult_id;
                    } elseif(intval($_POST['poll_id']) > 0) {
                        $poll_id = intval($_POST['poll_id']);
                    }
                // Current Poll ID Is Not Specified
                } elseif(intval(get_option('poll_currentpoll')) == 0) {
                    // Get Lastest Poll ID
                    $poll_id = intval(get_option('poll_latestpoll'));
                } else {
                    // Get Current Poll ID
                    $poll_id = intval(get_option('poll_currentpoll'));
                }
                break;
            // Take Poll ID From Arguments
            default:
                $poll_id = $temp_poll_id;
        }
    }
    
    // Assign All Loaded Poll To $polls_loaded
    if(empty($polls_loaded)) {
        $polls_loaded = array();
    }
    if(!in_array($poll_id, $polls_loaded)) {
        $polls_loaded[] = $poll_id;
    }

    // User Click on View Results Link
    if($pollresult_id == $poll_id) {
        if($display) {
            echo display_pollresult($poll_id);
            return;
        } else {
            return display_pollresult($poll_id);
        }
    // Check Whether User Has Voted
    } else {
        $poll_active = $wpdb->get_var("SELECT pollq_active FROM $wpdb->pollsq WHERE pollq_id = $poll_id");
        $poll_active = intval($poll_active);
        $check_voted = (is_user_logged_in()) ? check_voted($poll_id) : 0;
        if($poll_active == 0) {
            $poll_close = intval(get_option('poll_close'));
        } else {
            $poll_close = 0;
        }
        if(intval($check_voted) > 0 || (is_array($check_voted) && sizeof($check_voted) > 0) || ($poll_active == 0 && $poll_close == 1)) {
            if($display) {
                echo display_pollresult($poll_id, $check_voted);
                return;
            } else {
                return display_pollresult($poll_id, $check_voted);
            }
        } elseif(!check_allowtovote() || ($poll_active == 0 && $poll_close == 3)) {
            $disable_poll_js = '<script type="text/javascript">jQuery("#polls_form_'.$poll_id.' :input").each(funoction (i){jQuery(this).attr("disabled","disabled")});</script>';
            if($display) {
                echo display_pollvote($poll_id).$disable_poll_js;
                return;
            } else {
                return display_pollvote($poll_id).$disable_poll_js;
            }           
        } elseif($poll_active == 1) {
            if($display) {
                echo comm_display_pollvote($poll_id);
                return;
            } else {
                return comm_display_pollvote($poll_id);
            }
        }
    }   
}



### Function: Display Voting Form
function comm_display_pollvote($poll_id, $display_loading = true) {
	global $wpdb;
	
	// Temp Poll Result
	$temp_pollvote = '';
	// Get Poll Question Data
	$poll_question = $wpdb->get_row("SELECT pollq_id, pollq_question, pollq_totalvotes, pollq_timestamp, pollq_expiry, pollq_multiple, pollq_totalvoters FROM $wpdb->pollsq WHERE pollq_id = $poll_id LIMIT 1");
	// Poll Question Variables
	$poll_question_text = stripslashes($poll_question->pollq_question);
	$poll_question_id = intval($poll_question->pollq_id);
	$poll_question_totalvotes = intval($poll_question->pollq_totalvotes);
	$poll_question_totalvoters = intval($poll_question->pollq_totalvoters);
	$poll_start_date = mysql2date(sprintf(__('%s @ %s', 'wp-polls'), get_option('date_format'), get_option('time_format')), gmdate('Y-m-d H:i:s', $poll_question->pollq_timestamp)); 
	$poll_expiry = trim($poll_question->pollq_expiry);
	if(empty($poll_expiry)) {
		$poll_end_date  = __('No Expiry', 'wp-polls');
	} else {
		$poll_end_date  = mysql2date(sprintf(__('%s @ %s', 'wp-polls'), get_option('date_format'), get_option('time_format')), gmdate('Y-m-d H:i:s', $poll_expiry));
	}
	
	 
	//Is there a cookie set for this poll (if so, contains the choice(s) user made before prompted to login)
	$poll_form_id = "polls_form_$poll_question_id";
	$poll_input_name = "poll_$poll_question_id";
	$poll_cookie = (isset($_COOKIE['form-data'])) ? json_decode(urldecode(stripslashes(str_replace("'", "\"", $_COOKIE['form-data']))), true) : false;
	$poll_cookie_exists = ($poll_cookie && array_key_exists($poll_form_id, $poll_cookie)) ? true : false;
	
	$poll_multiple_ans = intval($poll_question->pollq_multiple);
	$template_question = stripslashes(get_option('poll_template_voteheader'));
	$template_question = str_replace("%POLL_QUESTION%", $poll_question_text, $template_question);
	$template_question = str_replace("%POLL_ID%", $poll_question_id, $template_question);
	$template_question = str_replace("%POLL_TOTALVOTES%", $poll_question_totalvotes, $template_question);
	$template_question = str_replace("%POLL_TOTALVOTERS%", $poll_question_totalvoters, $template_question);
	$template_question = str_replace("%POLL_START_DATE%", $poll_start_date, $template_question);
	$template_question = str_replace("%POLL_END_DATE%", $poll_end_date, $template_question);
	
	if($poll_multiple_ans > 0) {
		$template_question = str_replace("%POLL_MULTIPLE_ANS_MAX%", $poll_multiple_ans, $template_question);
	} else {
		$template_question = str_replace("%POLL_MULTIPLE_ANS_MAX%", '1', $template_question);
	}
	// Get Poll Answers Data
	$poll_answers = $wpdb->get_results("SELECT polla_aid, polla_answers, polla_votes FROM $wpdb->pollsa WHERE polla_qid = $poll_question_id ORDER BY ".get_option('poll_ans_sortby').' '.get_option('poll_ans_sortorder'));
	// If There Is Poll Question With Answers
	if($poll_question && $poll_answers) {
		// Display Poll Voting Form
		$temp_pollvote .= "<div id=\"polls-$poll_question_id\" class=\"wp-polls\">\n";
		$temp_pollvote .= "\t<form id=\"polls_form_$poll_question_id\" class=\"wp-polls-form\" action=\"".htmlspecialchars($_SERVER['REQUEST_URI'])."\" method=\"post\">\n";
		$temp_pollvote .= "\t\t<p style=\"display: none;\"><input type=\"hidden\" id=\"poll_{$poll_question_id}_nonce\" name=\"wp-polls-nonce\" value=\"".wp_create_nonce('poll_'.$poll_question_id.'-nonce')."\" /></p>\n";
		$temp_pollvote .= "\t\t<p style=\"display: none;\"><input type=\"hidden\" name=\"poll_id\" value=\"$poll_question_id\" /></p>\n";
		if($poll_multiple_ans > 0) {
			$temp_pollvote .= "\t\t<p style=\"display: none;\"><input type=\"hidden\" id=\"poll_multiple_ans_$poll_question_id\" name=\"poll_multiple_ans_$poll_question_id\" value=\"$poll_multiple_ans\" /></p>\n";
		}
		// Print Out Voting Form Header Template
		$temp_pollvote .= "\t\t$template_question\n";
		foreach($poll_answers as $poll_answer) {
			// Poll Answer Variables
			$poll_answer_id = intval($poll_answer->polla_aid); 
			$poll_answer_text = stripslashes($poll_answer->polla_answers);
			$poll_answer_votes = intval($poll_answer->polla_votes);
			$template_answer = stripslashes(get_option('poll_template_votebody'));
			$template_answer = str_replace("%POLL_ID%", $poll_question_id, $template_answer);
			$template_answer = str_replace("%POLL_ANSWER_ID%", $poll_answer_id, $template_answer);
			$template_answer = str_replace("%POLL_ANSWER%", $poll_answer_text, $template_answer);
			$template_answer = str_replace("%POLL_ANSWER_VOTES%", number_format_i18n($poll_answer_votes), $template_answer);
			
			
			
			if($poll_multiple_ans > 0) { //Multi-answer checkbox
				
				if($poll_cookie_exists) {
					
					if(in_array($poll_answer_id, $poll_cookie[$poll_form_id][$poll_input_name])) {
						
						$template_answer = select_it(str_replace("%POLL_CHECKBOX_RADIO%", 'checkbox', $template_answer));
						
					} else {
						
						$template_answer = str_replace("%POLL_CHECKBOX_RADIO%", 'checkbox', $template_answer);
					}
					
					
				} else {
				
					
					$template_answer = str_replace("%POLL_CHECKBOX_RADIO%", 'checkbox', $template_answer);
				}
				
			} else { //Single answer radio
				
				if($poll_cookie_exists) {
						
					if(in_array($poll_answer_id, $poll_cookie[$poll_form_id][$poll_input_name])) {
						
						$template_answer = select_it(str_replace("%POLL_CHECKBOX_RADIO%", 'radio', $template_answer));
						
					} else {
						
						$template_answer = str_replace("%POLL_CHECKBOX_RADIO%", 'radio', $template_answer);
					}
					
				} else {
					
						$template_answer = str_replace("%POLL_CHECKBOX_RADIO%", 'radio', $template_answer);
				}
				
			}
			// Print Out Voting Form Body Template
			$temp_pollvote .= "\t\t$template_answer\n";
		}
		// Determine Poll Result URL
		$poll_result_url = $_SERVER['REQUEST_URI'];
		$poll_result_url = preg_replace('/pollresult=(\d+)/i', 'pollresult='.$poll_question_id, $poll_result_url);
		if(isset($_GET['pollresult']) && intval($_GET['pollresult']) == 0) {
			if(strpos($poll_result_url, '?') !== false) {
				$poll_result_url = "$poll_result_url&amp;pollresult=$poll_question_id";
			} else {
				$poll_result_url = "$poll_result_url?pollresult=$poll_question_id";
			}
		}
		// Voting Form Footer Variables
		$template_footer = stripslashes(get_option('poll_template_votefooter'));
		$template_footer = str_replace("%POLL_ID%", $poll_question_id, $template_footer);
		$template_footer = str_replace("%POLL_RESULT_URL%", $poll_result_url, $template_footer);
		$template_footer = str_replace("%POLL_START_DATE%", $poll_start_date, $template_footer);
		$template_footer = str_replace("%POLL_END_DATE%", $poll_end_date, $template_footer);
		if($poll_multiple_ans > 0) {
			$template_footer = str_replace("%POLL_MULTIPLE_ANS_MAX%", $poll_multiple_ans, $template_footer);
		} else {
			$template_footer = str_replace("%POLL_MULTIPLE_ANS_MAX%", '1', $template_footer);
		}
		// Print Out Voting Form Footer Template
		$temp_pollvote .= "\t\t$template_footer\n";
		$temp_pollvote .= "\t</form>\n";
		$temp_pollvote .= "</div>\n";
		if($display_loading) {
			$poll_ajax_style = get_option('poll_ajax_style');
			if(intval($poll_ajax_style['loading']) == 1) {
				$temp_pollvote .= "<div id=\"polls-$poll_question_id-loading\" class=\"wp-polls-loading\"><img src=\"".plugins_url('wp-polls/images/loading.gif')."\" width=\"16\" height=\"16\" alt=\"".__('Loading', 'wp-polls')." ...\" title=\"".__('Loading', 'wp-polls')." ...\" class=\"wp-polls-image\" />&nbsp;".__('Loading', 'wp-polls')." ...</div>\n";
			}
		}
	} else {
		$temp_pollvote .= stripslashes(get_option('poll_template_disable'));
	}
	
	//Add JS to submit form if there is a poll cookie
	if($poll_cookie_exists && is_user_logged_in()) {
		
		$temp_pollvote .= " \n\n <script> \n\n shcJSL.cookies('form-data').eat(); \n\n poll_vote(". $poll_question_id ."); \n\n </script>";
	
	}
	
	// Return Poll Vote Template
	return $temp_pollvote;
}



function oembed_result_modification($data) {

	$data = str_replace("http://www.youtube.com", "https://www.youtube.com", $data);
	$data = str_replace("http://player.vimeo.com", "https://player.vimeo.com", $data);
	$data = str_replace("feature=oembed", "feature=oembed&wmode=opaque", $data);
	
	return $data;
}

add_filter("oembed_result", "oembed_result_modification", 10);

function add_attachment_slide_link_url( $form_fields, $post ) {
	$form_fields['slide-link-url'] = array(
		'label' => 'Link URL',
		'input' => 'text',
		'value' => get_post_meta( $post->ID, 'slide-link-url', true ),
		'helps' => 'When used on a Hero Slider item, this will direct user to linked webpage (either internal or external)',
	);
	
	$show_title = get_post_meta($post->ID, 'slide-hide-title', true);
	
	$form_fields['slide-hide-title'] = array(
		'label' => 'Hide Slide Title Bar',
		'input' => 'html',
		'html'	=> '<label for="attachments-'.$post->ID.'-show-title"><input type="ch	eckbox" name="attachments['.$post->ID.'][slide-hide-title]" value="1"'.($show_title ? ' checked="checked"' : '').' /> Yes</label>',
		'value' => $show_title,
		'helps' => 'Check to hide title bar',
	);
	
	return $form_fields;
}

add_filter( 'attachment_fields_to_edit', 'add_attachment_slide_link_url', 10, 2 );

function add_attachment_slide_link_url_save( $post, $attachment ) {
	if( isset( $attachment['slide-link-url'] ) )
		update_post_meta( $post['ID'], 'slide-link-url', $attachment['slide-link-url'] );
	
	if( isset( $attachment['slide-hide-title'] ) ) {
		update_post_meta( $post['ID'], 'slide-hide-title', $attachment['slide-hide-title'] );
	} else {
		delete_post_meta($post['ID'], 'slide-hide-title');
	}
	return $post;
}

add_filter( 'attachment_fields_to_save', 'add_attachment_slide_link_url_save', 10, 2 );

function add_post_slide_title_hide_save($return) {
	global $post;
	$current_meta = get_post_meta($post->ID, 'slide-hide-title', true);
	
	if ($current_meta == 1 && ($_POST['slide-hide-title'] == "" || 
			$_POST['slide-hide-title'] == 1) && ($current_meta == 0 || 
			$_POST['slide-hide-title'] == "") && $current_meta == ""
		) {
			if ($_POST['slide-hide-title'] == 1) {
				update_post_meta($post->ID, 'slide-hide-title', $_POST['slide-hide-title']);
			} else {
				delete_post_meta($post->ID, 'slide-hide-title', 1);
			}
	}
	
	return $return;
}

//add_filter( 'save_post', 'add_post_slide_title_hide_save', 10, 2 );

function enable_more_buttons($buttons) {
	$buttons[] = 'hr';
	return $buttons;
}
add_filter("mce_buttons", "enable_more_buttons");

add_filter('the_excerpt', "strip_shortcodes");


function post_formats(){
    add_theme_support('post-formats', array('video'));
    add_post_type_support( 'post', 'post-formats' );
    add_post_type_support( 'guide', 'post-formats' );
}
add_filter('after_setup_theme', 'post_formats');

// Input field, for a url, which is stored, and store in post_meta
function featured_video_box()
{
	add_meta_box("featured_video", "Featured Video", "print_featured_video_box", "post");
	add_meta_box("featured_video", "Featured Video", "print_featured_video_box", "guide");
}

function print_featured_video_box()
{
	// Use nonce for verification
	wp_nonce_field(plugin_basename( __FILE__ ), 'featured_video_box_nonce');
	
	$pid = get_the_ID();
	$stg = get_post_meta($pid, "featured_video_url", TRUE);
	
	$out = (!empty($stg)) ? wp_oembed_get($stg) : "";
	$fmt = get_post_format($pid);
		
	echo "<div>";
	echo '<div><label for="featured_video_url">' . __("URL") . '</label> ';
	echo '<input type="text" id="featured_video_url" name="featured_video_url" value="' . $stg . '" size="35" /></div>';
	
	if(!empty($out))
	{
		echo "<div style='color: red; margin-top: 10px;'>";
		echo ($fmt == "video") ? $out : "Your post format is not set to video.  Please do this to enable video format";
		echo "</div>";
	}
	
	echo "</div>";
}

function save_featured_video_box($post)
{ 
	// First we need to check if the current user is authorised to do this action. 
	if('page' == $_POST['post_type'] )
	{
		if(!current_user_can('edit_page', $post_id))
		{
			return;
		} 
	}
	else
	{
		if(!current_user_can('edit_post', $post_id))
		{
			return;
		}
	}
	
	if(!isset($_POST['featured_video_box_nonce']) || !wp_verify_nonce($_POST['featured_video_box_nonce'], plugin_basename( __FILE__ )))
	{
		return;
	}
	
	update_post_meta($post, "featured_video_url", esc_url($_POST['featured_video_url']));
	return $post;
}

add_action('save_post', "save_featured_video_box");
add_action('add_meta_boxes', "featured_video_box");

function setup_weather_plugin_path(){
    return "widgets/weather-widget";
}
add_filter('weather_widget_template_path', 'setup_weather_plugin_path');

if(class_exists("Media_Categories")){
    $my_custom_media_metabox = new Media_Categories('skcategory');
}



function import_file(){

    if (current_user_can('import_node_meta')) {
        if( isset($_GET['skcategory_file']) && class_exists('Meta_Importer_CSV') ){

            $file =  $_GET['skcategory_file'];
            $importer = new Meta_Importer_CSV('skcategory');
            $importer->parse($file);

            if( isset($_GET['ADD_META'])) {
                $importer->test_matches();
                $importer->add_meta();
            }

            if(isset($_GET['TEST_URLS'])){
            $importer->test_cr_links($_GET['TEST_URLS'],25);


                echo "<h2>Errors:". count($importer->errors). "</h2>";
                print_pre($importer->errors);

                echo "<h2>Success:". count($importer->success). "</h2>";
                //print_pre($importer->success);
            }

            if(isset($_GET['delete_taxonomy_cache'])){
                delete_option("skcategory_children");
            }
        }
    }

}
add_action('init', 'import_file');

remove_action('wp_head', 'rel_canonical');
add_action('wp_head', 'com_canonical');
function com_canonical() {
    if ( !is_singular() )
        return;

    global  $wp_query, $post;
     if ( !$id = $wp_query->get_queried_object_id() )
        return;


    ///DEPRECATED
    if('section' == $wp_the_query->query['post_type']){

        //is category
        $term = wp_get_object_terms($id, 'category');
        if(!empty($term)){
            $link = get_term_link($term[0], 'category');
        } 
        else {
            //is skcategory
            $term = wp_get_object_terms($id, 'skcategory');
            if(!empty($term)){
                $link = get_term_link($term[0], 'skcategory');
            } 
        }

    }

    $term = wp_get_object_terms($post->ID, $post->post_type);
    $filter = get_query_var('sf_filter');
    
    if(!is_wp_error($term)){ 
        $term = $term[0];
    }

    if(is_singular() && empty($link)){
        $link = get_permalink( $id );
    }
  
    if(!is_wp_error($term) && !empty($filter)){
        $link = get_term_link($term->slug, $term->taxonomy);

        if($filter == 'post' || $filter == 'guide' || $filter == 'question') {
            $link = $link . $filter;
        }
    }

    echo "<link rel='canonical' href='$link' />\n";
}

//Removes generator tag, request from sec team
remove_action('wp_head', 'wp_generator');


add_shortcode( 'quote' , 'shortcode_quote' );
add_shortcode( 'QUOTE' , 'shortcode_quote' );

function shortcode_quote( $atts = array(), $content = NULL ) {


        extract(shortcode_atts(array(
            'id' => '',
        ), $atts));

        ob_start();
        ?>
        <div class="bbcode-quote">

            <?php if (!empty($id)) : 
                $post = get_post($id);
                $user = get_userdata($post->post_author);
            ?>

                <div class="bbcode-by-line">
                    <span class="bbcode-username"><?php get_screenname($post->post_author); ?><span> said:
                </div>

            <?php endif; ?>

            <blockquote>
                <?php echo BBCode::do_shortcode($content); ?>
            </blockquote>
        </div>
        <?php

        return ob_get_clean();

        add_filter('wp_dropdown_users', 'MySwitchUser');
        function MySwitchUser($output)
        {
        
        	//global $post is available here, hence you can check for the post type here
        	$users = get_users('role=subscriber');
        
        	$output = "<select id=\"post_author_override\" name=\"post_author_override\" class=\"\">";
        
        	//Leave the admin in the list
        	$output .= "<option value=\"1\">Admin</option>";
        	foreach($users as $user)
        	{
        		$sel = ($post->post_author == $user->ID)?"selected='selected'":'';
        		$output .= '<option value="'.$user->ID.'"'.$sel.'>'.$user->user_login.'</option>';
        	}
        	$output .= "</select>";
        
        	return $output;
        }

}

