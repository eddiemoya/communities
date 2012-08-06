<?php
/********************************************************
 * Enqueue Scripts and Styles (remove uneccessary ones) *
 ********************************************************/
add_action('wp_enqueue_scripts', 'enqueue_scripts');

/**
 * Enqueue scripts and styles, also "localize" data.
 *
 * @author Eddie Moya
 */
function enqueue_scripts() {
        
    if (!is_admin()) { // do not enqueue on admin pages
        
        //Set up array to be passed to the shcproducts js file.
        $data = array(
            'ajaxurl'            => admin_url( 'admin-ajax.php'),
            'template_dir_uri'  => get_template_directory_uri(),
            'home_url'          => get_home_url(),
            #'cart_quantity'     => get_cart_object()->item_count,
            #'cart_link'         => cart_checkout_link(false),
         );
        
        //This condition is just an example, here we only needed to track omniture on category archives.
        if (is_category()) { $data['omchannel'] = single_cat_title('', false); }
            
        
       /* Scripts */
        wp_deregister_script('jquery'); 
        wp_register_script('jquery',    get_template_directory_uri() . '/assets/js/vendor/jquery-1.7.2.min.js', array(), '1.7.2');
        wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.5.3.min.js', array(), '2.5.3');
        wp_register_script('shcJSL',    get_template_directory_uri() . '/assets/js/shc-jsl.js', array(), '1.0');

        /* @todo: Does modernizr not require jQuery as a dependancy? */

        wp_enqueue_script('jquery');    
        wp_enqueue_script('modernizr');
        wp_enqueue_script('shcJSL');
				
				wp_register_script('authentic8r', get_template_directory_uri() . '/assets/js/json/authentic8r.js', array(), '1.0');
				wp_register_script('moodle', get_template_directory_uri() . '/assets/js/widgets/shcJSL.moodle.js', array(), '1.0');
				wp_enqueue_script('authentic8r');
				wp_enqueue_script('moodle');  

        wp_register_script('ajaxrequests', get_template_directory_uri() . '/assets/js/ajax-requests.js', array('jquery'), '1.2');
        wp_enqueue_script('ajaxrequests');   
			
		wp_localize_script('jquery', 'ajaxdata', $data);		
       	//wp_register_script('shcproducts', get_template_directory_uri() . '/assets/js/shcproducts.js', array('jquery'), '1.0');
        //wp_enqueue_script('shcproducts');
        //wp_localize_script('shcproducts', 'ajaxdata', $data);
        
        /* Styles */
        wp_register_style('main-styles', get_stylesheet_uri());
        wp_enqueue_style( 'main-styles');
        
        //Enqueue profile ajax only for author template
        if(is_author()) {
        	
        	wp_register_script('profile-ajax', get_template_directory_uri() . '/assets/js/profile-ajax.js', array('jquery'), '1.0');
        	wp_enqueue_script('profile-ajax');
        }
        
    }
}