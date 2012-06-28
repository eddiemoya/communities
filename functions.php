<?php

/******************************
 * Includes and General Setup *
 ******************************/

/**
 * Add support for various useful WordPress features.
 * Options second parameter offers additional specificity.
 * 
 * Remove these when not necessary.
 * 
 * @tutorial http://codex.wordpress.org/Function_Reference/add_theme_support
 */
add_theme_support('post-thumbnails'); // a.k.a "Featured Images"
add_theme_support('custom-background');
add_theme_support('custom-header'); // Look carefully at all the possible aruguments - http://codex.wordpress.org/Custom_Headers

/**
 * Allows us to apply styles to the TinyMCE editor in the admin - to make it
 * look the way the content will look on the front end as they type. 
 */
add_editor_style('assets/css/editor-style.css');


/**
 * Add image sizes so that WordPress will generate them when an image is uploaded.
 */
add_image_size('custom-image-size', array(100, 100));

/**
 * Register Widgetized Areas 
 */
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Sidebar',
        'description' => 'Sidebar',
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
    ));
    register_sidebar(array(
        'name' => 'Post Footer',
        'description' => 'Footer of every post',
        'before_widget' => '<span class="widget %2$s" id="%1$s">',
        'after_widget' => '</span>',
    ));
    
}


/**
 * Create Dynamic Menu Location, and create a Menu to use in that location
 * 
 * @author Eddie Moya
 */
function register_menus() {
  register_nav_menus( array(
      'main-mavigation' => __( 'Main Navigation'),
      'footer-navigation' => _( 'Footer Navigation' )
      ));
}
add_action( 'init', 'register_menus' );


/**
 * Include Theme Options page. Based on (lookup credit)
 */
get_template_part('classes/theme-options');



/**********************************
 * END Includes and General Setup *
 **********************************/



/********************************************************
 * Enqueue Scripts and Styles (remove uneccessary ones) *
 ********************************************************/
add_action('wp_enqueue_scripts', 'enqueue_scripts');
add_action('wp_print_styles', 'enqueue_styles');
add_action('wp_print_scripts', 'denqueue_scripts');

function enqueue_scripts() {
    
    wp_dequeue_script('sears-products-front-scripts');
    wp_dequeue_script('sears-products-overlay-scripts');
        
    if (!is_admin()) { // do not enqueue on admin pages
        
        
        //Set up array to be passed to the shcproducts js file.
        $data = array(
            'absurl'            => admin_url( 'admin-ajax.php'),
            'template_dir_uri'  => get_template_directory_uri(),
            'home_url'          => get_home_url(),
            'cart_quantity'     => get_cart_object()->item_count,
            'cart_link'         => cart_checkout_link(false),
         );
        
        //This condition is just an example, here we only needed to track omniture on category archives.
        if (is_category()) { $data['omchannel'] = single_cat_title('', false); }
            
        
       /* Scripts */
        wp_deregister_script('jquery'); 
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), '1.7.1');
        wp_enqueue_script('jquery');    
        
        wp_register_script('shcproducts', get_template_directory_uri() . '/assets/scripts/shcproducts.js', array('jquery'), '1.0');
        wp_enqueue_script('shcproducts');
        wp_localize_script('shcproducts', 'ajaxdata', $data);
        
        /* Styles */
        wp_register_style('main-styles', get_stylesheet_uri());
        wp_enqueue_style( 'main-styles');
        
    }
}

function enqueue_styles() {  
    wp_dequeue_style('shcp-front-style');
    wp_dequeue_style('shcp-front-kmart'); 
    wp_dequeue_style('shcp-front-sears'); 
}

function denqueue_scripts() {
    wp_dequeue_script('sears-products-front-scripts');
    wp_dequeue_script('sears-products-overlay-scripts');
}

/***********************************
 * END Enqueue Scripts and Styles  *
 ***********************************/

   



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
  $output = substr_replace($output, 'class="last-menu-item menu-item last-child', strripos($output, 'class="menu-item'), strlen('class="menu-item'));
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
    
    
    /**
     * Examples of conditionally adding some useful classes
     * These are borrowed from the BirthdayClub Theme. 
     */
    if (is_category())
        $classes[] = get_queried_object()->category_nicename;
    
    if (is_page())
        $classes[] = 'page-' .get_queried_object()->post_name;
    
    // Example custom taxonomy usage - remove if not needed.
    if(is_tax('facebook_gallery')){
        $classes[] = 'facebook-gallery';
        $classes[] = 'testgallery';
    }
    
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

    /**
     * If this is a category other than 'articles', 
     * set the post type to 'shcproduct' show 12 per page.
     */
    if (isset($query_string['category_name']) && $query_string['category_name'] != 'articles') {
        $query_string['post_type'] = 'shcproduct';
        $query_string['posts_per_page'] = '12';
    }

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



/********************************
 * Rewrite Rules and Permalinks *
 ********************************/

/**
 * Example Only - uncomment filter below to activate - modify as needed.
 * 
 * Do not call this function directly, add it to the term_link filter
 * 
 * Filters the auto-generated category permalinks to force them to have the permastructure we want.
 * 
 * The example below removes the 'category' base from the 'lookbook' category permalinks.
 * 
 * @author Eddie Moya
 * 
 * @param string $path Original path being generated by wordpress.
 * @return string
 */
function custom_term_links($path) {

    $bloghome = get_bloginfo('url');

    /**
     * If this is a path to a category called product 
     */
    if (preg_match('%' . $bloghome . '/category/lookbook/(.*?)$%i', $path)) {
        $path = preg_replace('%' . $bloghome . '/category/lookbook/(.*?)$%i', $bloghome . '/$1', $path);
    }
 
    return $path;
}

//add_filter('term_link', 'custom_term_links');

/**
 * Example Only - uncomment filter below to activate - modify as needed.
 * 
 * Do not call this function directly, add it to the wp_loaded hook.
 * 
 * Flushes the current reqwite rules if our custom rules are not set
 * 
 * The example below looks to see if our custom rule is aleady set.
 * 
 * @author Eddie Moya
 * @global type $wp_rewrite 
 */
function flush_custom_rules(){
	$rules = get_option( 'rewrite_rules' );

	if ( ! isset( $rules['lookbook/(.+?)/?$'] ) || ! isset( $rules['lookbook/(.+?)/page/?([0-9]{1,})/?$'] )  ) {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
}
//add_action( 'wp_loaded','flush_custom_rules' );


/**
 * Example Only - uncomment filter below to activate - modify as needed.
 * 
 * Do not call this function directly, add it to the rewrite_rules_array filter
 * 
 * Adds new regex to the rewrite rules.
 * 
 * @author Eddie Moya
 * 
 * @param array $rules
 * @return array
 */
function custom_rewrite_rules( $rules ) {
    
    $newrules = array();
    
    /**
     *  Turns 'birthday-gifts' slug into its own category base
     *  this allows pagination to work, but not feeds with this permastructure. 
     */
    $newrules['birthday-gifts/(.+?)/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';     
    $newrules['birthday-gifts/(.+?)/?$'] = 'index.php?category_name=$matches[1]';
    
    return $newrules + $rules;
}
//add_filter( 'rewrite_rules_array','custom_rewrite_rules' );


/************************************
 * END Rewrite Rules and Permalinks *
 ************************************/



/**
 * General use loop function. Allows for a template to be selected. Currently 
 * defaults to product template because that is used by our themes most often.
 * 
 * @author Eddie Moya
 * 
 * @global type $wp_query
 * @param type $template [optional] Template part to be used in the loop.
 */

function loop($template = 'post'){
    global $wp_query;
    
    if (have_posts()) { 
        while (have_posts()) {

            the_post();

            get_template_part('templates/'.$template);
        }    
    }

    wp_reset_query();

}
    
    
    
    
    
    







