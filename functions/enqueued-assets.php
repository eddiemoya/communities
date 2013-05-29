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
        $stylesheet_suffix = theme_option("bust_stylesheets_suffix");
        $javascript_suffix = theme_option("bust_javascript_suffix");
        //Set up array to be passed to the shcproducts js file.
        $data = array(
            'ajaxurl'            => site_url('/wp-admin/admin-ajax.php'),
            'template_dir_uri'  => get_template_directory_uri(),
            'home_url'          => get_home_url(),
            #'cart_quantity'     => get_cart_object()->item_count,
            #'cart_link'         => cart_checkout_link(false),
         );
        
        //This condition is just an example, here we only needed to track omniture on category archives.
       // if (is_category()) { $data['omchannel'] = single_cat_title('', false); }
        $channel = (theme_option("brand") == 'kmart')? "myKmart Community":"mySears Community";
        $pagename = get_omniture();

        $s_properties = array(
            'pageName' => $channel . ' > ' . $pagename, 
            'channel' => $channel,   
            'prop1' => $channel . ' > ' . $pagename, 
            'prop2' => $channel . ' > ' . $pagename,  
            'prop3' => $channel . ' > ' . $pagename, 
            'prop18' => $channel . ' > ' . $pagename,  
            'prop27' => $channel . ' > ' . $pagename,  
            'prop28' => $channel . ' > ' . $pagename, 
        );

        //Removing default WP Polls stuff 
        remove_action('wp_head', 'poll_head_scripts');
        wp_dequeue_style('wp-polls');
        wp_dequeue_script('wp-polls');
        wp_deregister_style('wp-polls');
        wp_deregister_script('wp-polls');
        
       /* Scripts */
        wp_deregister_script('jquery'); 
        wp_register_script('jquery',    get_template_directory_uri() . '/assets/js/vendor/jquery-1.7.2.min.js', array(), '1.7.2', true);
        wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.5.3.min.js', array(), '2.5.3');
        wp_register_script('shcJSL',    get_template_directory_uri() . '/assets/js/shc-jsl.js', array(), $javascript_suffix, true);
        wp_register_script('moodle', get_template_directory_uri() . '/assets/js/widgets/shcJSL.moodle.js', array(), $javascript_suffix, true);
        wp_register_script('ajaxrequests', get_template_directory_uri() . '/assets/js/ajax-requests.js', array('jquery'), $javascript_suffix, true);
        wp_register_script('openID', get_template_directory_uri() . '/assets/js/widgets/shcJSL.openID.js', array(), $javascript_suffix, true);
        wp_register_script('tooltip', get_template_directory_uri() . '/assets/js/widgets/shcJSL.tooltip.js', array(), $javascript_suffix, true);
        wp_register_script('actions', get_template_directory_uri() . '/assets/js/widgets/shcJSL.actions.js', array(), $javascript_suffix, true);
        wp_register_script('transFormer', get_template_directory_uri() . '/assets/js/widgets/shcJSL.transFormer.js', array(), $javascript_suffix, true);
        wp_register_script('flagger', get_template_directory_uri() . '/assets/js/widgets/shcJSL.flagger.js', array(), $javascript_suffix, true);
		wp_register_script('responslide', get_template_directory_uri() . '/assets/js/widgets/shcJSL.responslide.js', array(), $javascript_suffix, true);
        wp_register_script('wp-polls', get_template_directory_uri() . '/assets/js/widgets/polls.js', array(), $javascript_suffix, true);
        wp_register_script('carousel', get_template_directory_uri() . '/assets/js/widgets/shcJSL.carousel.js', array(), $javascript_suffix, true);
        
	    wp_register_script('omniture_scode', get_template_directory_uri() . '/assets/js/vendor/omniture.'.theme_option("brand").'.js', array(), $javascript_suffix, true);
        wp_register_script('omniture_start', get_template_directory_uri() . '/assets/js/vendor/omniture.start.js', array('omniture_scode'), $javascript_suffix, true);


        wp_register_script('omniture', get_template_directory_uri() . '/assets/js/vendor/omniture.' . theme_option("brand") . '.js', array(), $javascript_suffix, true);
        wp_register_script('addthis', 'http://s7.addthis.com/js/250/addthis_widget.js', array(), $javascript_suffix, true);

        // NOT FOR PRODUCTION
        //wp_register_script('debug', get_template_directory_uri() . '/assets/js/vendor/debug.js', array(), '1.0');

        wp_enqueue_script('jquery');    
        wp_enqueue_script('modernizr');
       // wp_enqueue_script('debug');
        wp_enqueue_script('shcJSL');
        wp_enqueue_script('moodle');
        wp_enqueue_script('ajaxrequests');   
        wp_enqueue_script('openID');
        wp_enqueue_script('tooltip');
        wp_enqueue_script('actions');
        wp_enqueue_script('transFormer');
        wp_enqueue_script('flagger');
        //wp_enqueue_script('responslide');
        wp_enqueue_script('omniture');
        //wp_enqueue_script('carousel');
        //wp_enqueue_script('wp-polls');
        wp_enqueue_script('omniture_scode');
        wp_enqueue_script('omniture_start');
        // wp_enqueue_script('addthis');

        wp_localize_script('jquery', 'ajaxdata', $data);
        wp_localize_script('omniture_start', 's_properties', $s_properties);

        wp_localize_script('jquery', 'ajaxdata', $data);
        //wp_localize_script('addthis', 's.pageName', $pageName);    		

        
        /* Styles */
        //$lookup_stylesheet = str_replace('kmart.com', 'sears.com', lookup_stylesheet());
        $lookup_stylesheet = lookup_stylesheet();

		wp_register_style( 'main-styles', $lookup_stylesheet, array(), $stylesheet_suffix );
        wp_enqueue_style( 'main-styles' );
        
        //Enqueue profile ajax only for author template
        if(is_author()) {

            wp_register_script('profile-ajax', get_template_directory_uri() . '/assets/js/profile-ajax.js', array('jquery'), $javascript_suffix, true);
            wp_enqueue_script('profile-ajax');

            if(is_user_logged_in()) {
                wp_register_script('user-delete-comment', get_template_directory_uri() . '/assets/js/user-delete-comment.js', array('jquery'), $javascript_suffix, true);
                wp_enqueue_script('user-delete-comment');
            }
        }     
    }
}

add_action('wp_head','pluginname_ajaxurl');
// add_action('wp_head','pluginname_addthis_config');
// add_action('wp_head','omniture_sVars');

function pluginname_ajaxurl() {

    $url = site_url('/wp-admin/admin-ajax.php');

    echo '
        <script type="text/javascript">
            var ajaxurl = \''.$url.'\';
        </script>
    ';
}
function pluginname_addthis_config() {
    
    global $current_user;
    
    $email_from = is_user_logged_in() ? $current_user->user_email : '';

    echo '
        <script type="text/javascript">
            var addthis_config = {
                ui_email_note: "Thought you might like this from My' . ucfirst( theme_option("brand") ) .' Community.",
                ui_email_from: "' . $email_from . '"
              }
        </script>
    ';
}
function omniture_sVars() {
    echo "
        <script type='text/javascript'>
            s.pageName = '". get_omniture() . "';
            s.prop1 = '". get_omniture() . "';
            s.prop2 = '". get_omniture() . "';
            s.prop3 = '". get_omniture() . "';
            s.prop13 = '". get_omniture() . "';
            s.prop18 = '". get_omniture() . "';
            s.prop27 = '". get_omniture() . "';
            s.prop28 = '". get_omniture() . "';
        </script>
    ";
}