<?php
/**
 * General use loop function. Allows for a template to be selected. Currently 
 * defaults to product template because that is used by our themes most often.
 * 
 * @author Eddie Moya
 * 
 * @global type $wp_query
 * @param type $template [optional] Template part to be used in the loop.
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
 */
function loop_by_type($special = null){
    global $wp_query;
   // print_pre($wp_query);
    
    
    //echo $template;
    if (have_posts()) { 
        while (have_posts()) {
            
            
            //echo 'TEMPALTE:'.$template;
            the_post();
     
            $template = (isset($special)) ? $wp_query->post->post_type.'-'.$special : $template;
            //print_pre(get_post_type());
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
 */
function return_template_part($template){
    ob_start();
        get_template_part($template);
    return ob_get_clean();
}

/**
 * Process attempts to post a question from the front end of the site.
 *
 * @author Eddie Moya
 */
function process_front_end_question(){

    //If step 1 - return that we should move on to step 2.
    if( wp_verify_nonce( $_POST['_wpnonce'], 'front-end-post_question-step-1' )){

        //If user is logged in - step 2
        if(is_user_logged_in()) {
            return "2";

        } else {
            /**
             * Kick off login modal SSO login crazyness here 
             */
            return "1";
        }
    }

    //If step 2, add the post and move to step 3
    if(wp_verify_nonce( $_POST['_wpnonce'], 'front-end-post_question-step-2' ) && is_user_logged_in()) {

        $raw_content = $_POST['more-details'];

        $title =  wp_kses($_POST['your-question'], array(), array());
        $content = wpautop(wp_kses($_POST['more-details'], array(), array()));

        $category = (isset($_POST['category'])) ?  absint((int)$_POST['category'])  : '' ;
        $category = (isset($_POST['sub-category'])) ? absint((int)$_POST['sub-category']) : $category; 

        if(!empty($title) && !empty($content) && !empty($category)) {
            $post = array(
                'post_title'    => $title,
                'post_content'  => $content,
                'post_category' => array($category),
                'post_status'   => 'publish',           
                'post_type'     => 'question'
            );
        } else {
            //Need to handle so that it reloads with the users previous content
            return "2";
        }

        wp_insert_post($post); 
        do_action('wp_insert_post', 'wp_insert_post'); 
        return "3";
    }

    //Neither step has been taken, were on step 1
    return "1";
}

function print_pre($r){
    echo '<pre>';
    print_r($r);
    echo '</pre>';
}