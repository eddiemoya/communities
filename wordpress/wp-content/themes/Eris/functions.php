<?php

/**
 * Add support for various useful WordPress features.
 * Options second parameter offers additional specificity.
 * 
 * Remove these when not necessary.
 *
 * Look carefully at all the possible aruguments - http://codex.wordpress.org/Custom_Headers
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
//add_image_size('custom-image-size', array(100, 100));

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
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
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
get_template_part('classes/section-front');



/**********************************
 * END Includes and General Setup *
 **********************************/



/********************************************************
 * Enqueue Scripts and Styles (remove uneccessary ones) *
 ********************************************************/
add_action('wp_enqueue_scripts', 'enqueue_scripts');
//add_action('wp_print_styles', 'enqueue_styles');

function enqueue_scripts() {
        
    if (!is_admin()) { // do not enqueue on admin pages
        
        //Set up array to be passed to the shcproducts js file.
        $data = array(
            'absurl'            => admin_url( 'admin-ajax.php'),
            'template_dir_uri'  => get_template_directory_uri(),
            'home_url'          => get_home_url(),
            #'cart_quantity'     => get_cart_object()->item_count,
            #'cart_link'         => cart_checkout_link(false),
         );
        
        //This condition is just an example, here we only needed to track omniture on category archives.
        if (is_category()) { $data['omchannel'] = single_cat_title('', false); }
            
        
       /* Scripts */
        wp_deregister_script('jquery'); 
        wp_register_script('jquery', get_template_directory_uri() . '/assets/js/vendor/jquery-1.7.2.min.js', array(), '1.7.2');

        /* @todo: Does modernizr not require jQuery as a dependancy? [TIM] No it does not. */
				wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.5.3.min.js', array(), '2.5.3');
				wp_register_script('shcJSL', get_template_directory_uri() . '/assets/js/shc-jsl.js', array(), '1.0');
				wp_register_script('authentic8r', get_template_directory_uri() . '/assets/js/json/authentic8r.js', array(), '1.0');
				wp_register_script('moodle', get_template_directory_uri() . '/assets/js/widgets/jquery.moodle.js', array(), '1.0');
        wp_enqueue_script('jquery');    
        wp_enqueue_script('modernizr');
        wp_enqueue_script('shcJSL');    
				wp_enqueue_script('authentic8r');
				wp_enqueue_script('moodle');
				
       	//wp_register_script('shcproducts', get_template_directory_uri() . '/assets/js/shcproducts.js', array('jquery'), '1.0');
        //wp_enqueue_script('shcproducts');
        //wp_localize_script('shcproducts', 'ajaxdata', $data);
        
        /* Styles */
        wp_register_style('main-styles', get_stylesheet_uri());
        wp_enqueue_style( 'main-styles');
        
				
				
				
    }
}

/**
 * Return a template instead of outputing it.
 *
 * add_theme_support('custom-background');
 * add_theme_support('custom-header');
 */
add_theme_support('post-thumbnails'); // a.k.a "Featured Images"


/**
 * Allows us to apply styles to the TinyMCE editor in the admin - to make it
 * look the way the content will look on the front end as they type.
 * 
 * add_editor_style('assets/css/editor-style.css');
 */



/**
 * Add image sizes so that WordPress will generate them when an image is uploaded.
 *
 * add_image_size('custom-image-size', array(100, 100));
 */


//Classes
get_template_part('classes/theme-options');
get_template_part('classes/section-front');

//Function
get_template_part('functions/ajax-callbacks');
get_template_part('functions/enqueued-assets');
get_template_part('functions/filter-hooks');
get_template_part('functions/menus');
get_template_part('functions/post-types');
get_template_part('functions/sidebar-declarations');
get_template_part('functions/template-tags');
