<?php 
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
    $classes[] = $options['blog_brand_shop_style'];
    
    if (is_category())
        $classes[] = get_queried_object()->category_nicename;
    
    if (is_page())
        $classes[] = 'page-' .get_queried_object()->post_name;

     if ('section' == get_post_type())
        $classes[] = 'section';
    
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
function custom_primary_query($query_string) {
    global $wp_query;
    /**
     * If this is a archive of the 'facebook_gallery' custom taxonomy,
     * set the post type to 'facebook_images and show them all at once. 
     * 
     * Remove when not needed.
     */
    /*
    if (isset($query_string['facebook_gallery'])) {
        $query_string['post_type'] = 'facebook_images';
        $query_string['posts_per_page'] = '-1';
    }*/

    return $query_string;
}
add_filter('request', 'custom_primary_query');

/******************************************
 * END  Content, Class, and Query Filters *
 ******************************************/