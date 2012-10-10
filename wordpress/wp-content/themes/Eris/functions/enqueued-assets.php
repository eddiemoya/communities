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
            'ajaxurl'            => site_url('/wp-admin/admin-ajax.php'),
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
        // wp_register_script('moodle', get_template_directory_uri() . '/assets/js/widgets/shcJSL.moodle.js', array(), '1.0');
        wp_register_script('ajaxrequests', get_template_directory_uri() . '/assets/js/ajax-requests.js', array('jquery'), '1.2');
        // wp_register_script('openID', get_template_directory_uri() . '/assets/js/widgets/shcJSL.openID.js', array(), '1.0');
        // wp_register_script('tooltip', get_template_directory_uri() . '/assets/js/widgets/shcJSL.tooltip.js', array(), '1.0');
        // wp_register_script('actions', get_template_directory_uri() . '/assets/js/widgets/shcJSL.actions.js', array(), '1.0');
        // wp_register_script('transFormer', get_template_directory_uri() . '/assets/js/widgets/shcJSL.transFormer.js', array(), '1.0');
        // wp_register_script('flagger', get_template_directory_uri() . '/assets/js/widgets/shcJSL.flagger.js', array(), '1.0');
        wp_register_script('addthis', 'http://s7.addthis.com/js/250/addthis_widget.js', array(), '1.0');
				
				// NOT FOR PRODUCTION
				//wp_register_script('debug', get_template_directory_uri() . '/assets/js/vendor/debug.js', array(), '1.0');

        wp_enqueue_script('jquery');    
        wp_enqueue_script('modernizr');
       	// wp_enqueue_script('debug');
        wp_enqueue_script('shcJSL');
        // wp_enqueue_script('moodle');
        wp_enqueue_script('ajaxrequests');   
        // wp_enqueue_script('openID');
        // wp_enqueue_script('tooltip');
        // wp_enqueue_script('actions');
        // wp_enqueue_script('transFormer');
        // wp_enqueue_script('flagger');
        wp_enqueue_script('addthis');

		wp_localize_script('jquery', 'ajaxdata', $data);		
        
        /* Styles */
        $style_path = STYLESHEETPATH . "/style.css";
        $style_version = file_exists( $style_path ) ? filemtime( $style_path ) : '1.0';
        wp_register_style( 'main-styles', get_stylesheet_uri(), array(), $style_version );
        wp_enqueue_style( 'main-styles');
        
       

        
        //Enqueue profile ajax only for author template
        if(is_author()) {
        	
        	wp_register_script('profile-ajax', get_template_directory_uri() . '/assets/js/profile-ajax.js', array('jquery'), '1.0');
        	wp_enqueue_script('profile-ajax');
        	
        	if(is_user_logged_in()) {
	        	wp_register_script('user-delete-comment', get_template_directory_uri() . '/assets/js/user-delete-comment.js', array('jquery'), '1.0');
	        	wp_enqueue_script('user-delete-comment');
        	}
        }
        
    }
}

//add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {

    $url = site_url('/wp-admin/admin-ajax.php');

    echo '
        <script type="text/javascript">
            var ajaxurl = \''.$url.'\';
        </script>
    ';
}