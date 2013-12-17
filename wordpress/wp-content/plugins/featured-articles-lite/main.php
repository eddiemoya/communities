<?php
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */
/*
Plugin Name: Featured articles Lite
Plugin URI: http://www.codeflavors.com/featured-articles-pro/
Description: Create fancy animated sliders into your blog pages by choosing from plenty of available options and different themes. Compatible with Wordpress 3.1+
Author: CodeFlavors
Version: 2.4.8
Author URI: http://www.codeflavors.com
*/

/**
 * Plugin administration capability, current version and Wordpress compatibility
 */
define('FA_CAPABILITY', 'edit_FA_slider');
define('FA_VERSION', '2.4.8');
define('FA_WP_COMPAT', '3.1');

include_once WP_PLUGIN_DIR .'/featured-articles-lite/includes/common.php';
include_once WP_PLUGIN_DIR .'/featured-articles-lite//includes/widgets.php';

/**
 * Keeps track of the current loop. Used only when displaying slideshows
 * using the automatic display feature.
 */
$FA_current_loop = array();

/**
 * Global variable that gets populated with the currently displayed slider options.
 * To get these options use function FA_get_option( 'option name or empty for all options' )
 */
$FA_slider_options = array();

/**
 * Global variable that stores javaScript settings for all slideshows to be displayed. All params
 * stored are placed into the output and used in slideshow script file.
 */
$FA_SLIDERS_PARAMS = array();

/**
 * Displays the featured articles box on index page
 */
function featured_articles_slideshow(){
	
	$sliders = FA_display();
	if(!$sliders) return;
	
	global $FA_current_loop;
	foreach( $sliders as $slider_id ){
		// since we allow to display on any loop, let's keep track of them for each slideshow
		if( !array_key_exists( $slider_id, $FA_current_loop ) ){
			// start counting loops
			$FA_current_loop[$slider_id] = 0;
		}
		
		// set global options for the current slider
		FA_set_slider_options( $slider_id );
		$loop_display = FA_get_option( array(
			'_fa_lite_display', 
			'loop_display'
		));
		
		// it it's not the time to display the slideshow (loop not reached), bail out but not before counting the loop
		if( $FA_current_loop[$slider_id] != $loop_display  ){
			$FA_current_loop[$slider_id]+=1;
			continue;
		}
		// increase loop count because loop setting reached current loop value
		$FA_current_loop[$slider_id]+=1;		
		
		// get the theme options for this slideshow
		$theme = FA_get_option( array( 
			'_fa_lite_theme', 
			'active_theme'
		));
		
		/**
		 * This is for backwards compatibility.
		 * Prior to V2.4, theme Classic was actually 2 different themes: Light and Dark.
		 * If slideshow was set on either dark or lite theme, those themes should no longer exist.
		 * If user saved them, we'll use them. If not, switch to classic.
		 */
		$load_classic = FA_should_load_classic( $theme );
		if( $load_classic ){
			$theme = $load_classic['active_theme'];
		}
		
		// OK, now everything is in place. Time to set theme paths and url's
		$theme_path = FA_theme_path( $theme ); // if folder doesn't exist, function returns false
		$theme_url = FA_theme_url( $theme );
		
		// check if theme folder exists. If not, bail out
		if( !$theme_path ){
			continue; // and we skip this slideshow
		}
		
		// @deprecated get slider size; don't use this in themes anymore, you know have the_slider_height() and the_slider_width() template functions
		$styles = FA_style_size();
		
		/**
		 * @deprecated - set the options for older, not updated themes. 
		 * Use templating functions instead. See here: http://www.codeflavors.com/documentation/display-slider-file/ 
		 */
		$options = FA_get_option('_fa_lite_aspect');
		
		// create unique ID for the slider		
		$FA_slider_id = 'FA_slider_'.$slider_id;
		
		// get the slides for this slideshow		
		$postslist = FA_get_content($slider_id);
		
		// now let's mess with $post and $id
		global $post, $id;
		// save the original post
		$original_post = $post;
		// this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop
		$original_id = $id;
		
		// Include FeaturedArticles display file
		include( $theme_path.'/display.php' );
		
		// now that we're done with $post and $id, let's restore them with the original values 
		$post = $original_post;
		$id = $original_id;
	}
}
/**
 * Function for manually placing slideshows in theme pages. 
 * It doesn't check current page so if you want to manually place a slideshow
 * directly in your theme, all page verifications must be done by you. This means
 * that if this function is called in header.php template, it will display on every
 * page of your blog.
 * 
 * Usage in themes:
 * <?php	
 *		if( function_exists('FA_display_slider') ){	
 *			FA_display_slider( $slider_id );
 *		}	
 *	?>
 */
function FA_display_slider($slider_id, $echo = true){
	
	$slider = get_post($slider_id);
	// if not set, bail out
	if(!$slider || 'fa_slider' != $slider->post_type) return;
	
	// set global options for the current slider
	FA_set_slider_options( $slider_id );
	
	/* theme to be displayed as set in options */
	$theme 			= FA_get_option(array( '_fa_lite_theme', 'active_theme'));
	$theme_color 	= FA_get_option(array( '_fa_lite_theme', 'active_theme_color'));
	
	/**
	 * This is for backwards compatibility.
	 * Prior to V2.4, theme Classic was actually 2 different themes: Light and Dark.
	 * If slideshow was set on either dark or lite theme, those themes should no longer exist.
	 * If user saved them, we'll use them. If not, switch to classic.
	 */
	$load_classic = FA_should_load_classic( $theme );
	if( $load_classic ){
		$theme = $load_classic['active_theme'];
		$theme_color = $load_classic['active_theme_color'];
	}	
	
	// selected theme path and url 
	$theme_path = FA_theme_path( $theme );
	$theme_url 	= FA_theme_url( $theme );
	// check if theme exists
	if( !$theme_path ){
		return; // bail out if not
	}
	
	// display file path
	$theme_display = $theme_path.'/display.php';
	
	// enqueue theme stylesheet
	$stylesheet_handler = 'FA_Lite_'.$theme;
	wp_enqueue_style($stylesheet_handler, $theme_url.'/stylesheet.css');
	// let's see if colors is on
	if( !empty( $theme_color ) ){
		$color_style_handler = $stylesheet_handler.'-'.$theme_color;
		$color_style_url = $theme_url.'/colors/'.$theme_color;
		wp_enqueue_style($color_style_handler, $color_style_url, array( $stylesheet_handler ));
	}
	
	// check for starter script or load the default one
	$custom_starter = $theme_path.'/starter.js';
	if( is_file( $custom_starter ) ){
		wp_enqueue_script('FA_starter-'.$theme, $theme_url.'/starter.js', array('jquery'));
	}else{
		wp_enqueue_script('FA_general_starter', FA_path('scripts/script-loader.js'), array('jquery'));
	}
	
	global $post, $id, $FA_SLIDERS_PARAMS;
	
	// get js options for this slider and add them to global FA_SLIDERS_PARAMS that gets printed as footer script
	$js_options = FA_get_option('_fa_lite_js');
	$FA_SLIDERS_PARAMS['FA_slider_'.$slider_id] = FA_lite_json($js_options);
	
	// get the contents of this slideshow
	$postslist = FA_get_content($slider_id);
	// save the original post
	$original_post = $post;
	// this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop
	$original_id = $id;
	
	// @deprecated slider size. Don't use this anymore, there are template functions for this
	$styles = FA_style_size();
	// @deprecated - set the options for older, not updated themes
	$options = FA_get_option('_fa_lite_aspect');
	
	// unique ID for this slider
	$FA_slider_id = 'FA_slider_'.$slider_id;
	
	// if slideshow HTML should be returned, do it
	if( !$echo ){
		ob_start();
	}
	
	// include the template file
	include( $theme_display );

	// get the output it it is to be returned
	if( !$echo ){
		$slider_content = ob_get_contents();
		ob_end_clean();
	}
	
	// give $post and $id his original value 
	$post = $original_post;
	$id = $original_id;
	// return slideshow if asked as return value
	if(!$echo){
		return $slider_content;
	}
}
/**
 * Function to load stylesheets and scripts into the footer.
 * This is needed for manually defined sliders to load scripts and stylesheets 
 * only when needed. Uses global variable $FA_SLIDERS_PARAMS to output JavaScript
 * parameters for each slideshow.
 * This function uses hook wp_footer. Your theme must respect WP standards and 
 * call wp_footer() function in footer.php, right above </body> closing tag.
 */
function FA_load_footer(){
	global $FA_SLIDERS_PARAMS;
	
	if(!$FA_SLIDERS_PARAMS) return;
	
	wp_print_styles();
	wp_register_script('jquery-mousewheel', FA_path('scripts/jquery.mousewheel.min.js'), 'jquery', '3.0.6');
	wp_enqueue_script('FeaturedArticles-jQuery', FA_path('scripts/FeaturedArticles.jquery.js'), array('jquery', 'jquery-mousewheel'), '1.0');
	wp_enqueue_script('FA_footer', FA_path('scripts/fa_footer.js'), array('FeaturedArticles-jQuery'));
	
	wp_localize_script('FA_footer', 'FA_Lite_footer_params', $FA_SLIDERS_PARAMS);	
	wp_print_scripts();		
}
/**
 * Add JavaScript for sliders set to display automatically
 *
 */
function FA_add_scripts(){	
	$sliders = FA_display();	
	if(!$sliders) return;
	
	$js_options = array();
	foreach( $sliders as $slider_id ){
		
		// get the selected theme option for this slideshow
		$theme_option = FA_slider_options($slider_id, '_fa_lite_theme');
				
		/**
		 * This is for backwards compatibility.
		 * Prior to V2.4, theme Classic was actually 2 different themes: Light and Dark.
		 * If slideshow was set on either dark or lite theme, those themes should no longer exist.
		 * If user saved them, we'll use them. If not, switch to classic.
		 */
		$load_classic = FA_should_load_classic( $theme_option['active_theme'] );
		if( $load_classic ){
			$theme_option = $load_classic;
		}
		
		// load the js starter if any
		$theme_path = FA_theme_path( $theme_option['active_theme'] );
		$theme_url = FA_theme_url( $theme_option['active_theme'] );
		
		// if for some reason theme doesn't exit, skip this slideshow
		if( !$theme_path ){
			continue; // skip slider if theme folder doesn't exist in configured themes folder
		}
		
		// get slideshow js options
		$options = FA_slider_options( $slider_id, '_fa_lite_js' );
		// add js options to slideshows options array. This will output in page.		
		$js_options['FA_slider_'.$slider_id] = FA_lite_json($options);
		
		// check if theme has custom starter script
		if( is_file( $theme_path.'/starter.js' ) ){
			$starter_handle = 'FA_starter-'.$theme_option['active_theme'];
			$starter_url = $theme_url.'/starter.js';
			wp_enqueue_script( $starter_handle, $starter_url, array('FeaturedArticles-jQuery') );
		}else{
			// if starter isn't preset (older theme maybe), load the default starter
			wp_enqueue_script('FA_general_starter', FA_path('scripts/script-loader.js'), array('FeaturedArticles-jQuery'));
		}
	}
	
	wp_register_script('jquery-mousewheel', FA_path('scripts/jquery.mousewheel.min.js'), 'jquery', '3.0.6');
	wp_enqueue_script('FeaturedArticles-jQuery', FA_path('scripts/FeaturedArticles.jquery.js'), array('jquery', 'jquery-mousewheel'), '1.0');
	wp_localize_script('FeaturedArticles-jQuery', 'FA_Lite_params', $js_options);	
}

/**
 * Add stylesheets to sliders set to display automatically
 *
 */
function FA_add_styles(){	
	$sliders = FA_display();	
	if(!$sliders) return;
	
	foreach( $sliders as $slider_id ){
		$theme = FA_slider_options($slider_id, '_fa_lite_theme');
		
		/**
		 * This is for backwards compatibility.
		 * Prior to V2.4, theme Classic was actually 2 different themes: Light and Dark.
		 * If slideshow was set on either dark or lite theme, those themes should no longer exist.
		 * If user saved them, we'll use them. If not, switch to classic.
		 */
		$load_classic = FA_should_load_classic( $theme['active_theme'] );
		if( $load_classic ){
			$theme = $load_classic;
		}
				
		// theme path and url
		$theme_path = FA_theme_path( $theme['active_theme'] );
		$theme_url = FA_theme_url( $theme['active_theme'] );
		
		// if theme folder doesn't exist, skip this slider
		if( !$theme_path ){
			continue; // bail out
		}
		
		// enqueue main stylesheet
		$stylesheet_handle = 'FA_style_'.$theme['active_theme'];
		$stylesheet_url = $theme_url.'/stylesheet.css';
		wp_enqueue_style( $stylesheet_handle, $stylesheet_url );
		
		// enqueue color stylesheet		
		if( !empty( $theme['active_theme_color'] ) ){
			$color_style_handle = $stylesheet_handle.'-'.$theme['active_theme_color'];
			$color_style_url = $theme_url.'/colors/'.$theme['active_theme_color'];
			wp_enqueue_style($color_style_handle, $color_style_url);	
		}		
	}			
}

/**
 * Plugin administration menu
 */
function FA_plugin_menu(){
	
	$menu_slug = 'featured-articles-lite';
	
	add_menu_page( 'FA Lite', 'FA Lite', FA_CAPABILITY, $menu_slug, 'fa_slideshows', FA_path('styles/ico.png') ); 
	$main_page = add_submenu_page( $menu_slug, __('FA Lite Sliders', 'falite'), __('Sliders', 'falite'), FA_CAPABILITY, $menu_slug, 'fa_slideshows');
	$new_slideshow = add_submenu_page( $menu_slug, __('FA Lite Slider', 'falite'), __('Add New Slider', 'falite'), FA_CAPABILITY, $menu_slug.'-new-slideshow', 'fa_new_slideshow');
	
	// only administrator can manage slider settings user capabilities
	add_submenu_page( $menu_slug, __('Settings', 'falite'), __('Settings', 'falite'), 'manage_options', $menu_slug.'/settings.php');
	$pro_page = add_submenu_page($menu_slug, __('Featured Articles PRO', 'falite'), __('Go PRO!', 'falite'), FA_CAPABILITY, $menu_slug.'/pro.php');
	
	add_submenu_page(NULL, __('Add content', 'falite'), __('Add content', 'falite'), FA_CAPABILITY, $menu_slug.'/add_content.php');
	add_submenu_page(NULL, __('Preview Slider', 'falite'), __('Preview Slider', 'falite'), FA_CAPABILITY, $menu_slug.'/preview.php');
		
	// styles for editing/creating sliders pages
	add_action('admin_print_styles-'.$main_page, 'FA_edit_styles');
	add_action('admin_print_styles-'.$new_slideshow, 'FA_edit_styles');
	// styles for creating slides pages
	add_action('admin_print_styles-featured-articles-lite/pro.php', 'FA_pro_styles');
		
		
}
add_action('admin_menu', 'FA_plugin_menu');

add_action('admin_print_styles-post.php', 'FA_post_edit_scripts');
add_action('admin_print_styles-post-new.php', 'FA_post_edit_scripts');

/**
 * Slideshows admin menu callback function. It displays all pages needed for listing/editing/creating/deleting
 * slideshows.
 */
function fa_slideshows(){
	// get the current action from link to determine what to show
	$action = isset( $_GET['action'] ) ? $_GET['action'] : '';
	$current_page = menu_page_url('featured-articles-lite', false);
	
	$screen = get_current_screen();
	$page_hook = $screen->id;
	
	
	// bulk delete
	if( (isset($_POST['action2']) && 'delete' == $_POST['action2']) || (isset($_POST['action']) && 'delete' == $_POST['action'] ) ){
		$action = 'bulk-delete';
	}
	
	switch( $action ){
		// edit/create slideshows
		case 'edit':
		case 'new':			
			$slider_id = isset( $_GET['id'] ) ? (int)$_GET['id'] : false;
			// set the current slider options
			FA_set_slider_options( $slider_id );
			
			// start meta boxes
			add_meta_box('submitdiv', __('Save Slider', 'falite'), 'fa_lite_save_panel', $page_hook, 'side');
			add_meta_box('fa-lite-implement', __('Manual placement', 'falite'), 'fa_lite_implement_panel', $page_hook, 'side');
			add_meta_box('fa-lite-info', __('Help, support & info', 'falite'), 'fa_lite_info_panel', $page_hook, 'side');
			// include template
			include FA_dir('edit.php');
		break;	
		// delete individual slideshows
		case 'delete':
			if( wp_verify_nonce($_GET['_wpnonce']) ){
				FA_delete_sliders( $_GET['item_id'] );					
			}	
			wp_redirect( $current_page );
			exit();	
		break;
		// bulk delete slideshows
		case 'bulk-delete':
			if( wp_verify_nonce($_POST['FA_bulk_del'], 'featured-articles-sliders-bulk-delete') ){
				FA_delete_sliders( $_POST['item_id'] );				
			}
			wp_redirect( $current_page );
			exit();	
		break;	
		// show the slideshows list	
		default:
			include FA_dir('sliders.php');
		break;	
	}
}
/**
 * New slideshow admin menu callback function. All functionality is inside 
 * function fa_slideshows, it just sets the action to alert a new slideshow 
 * is created.
 */
function fa_new_slideshow(){
	$_GET['action'] = 'new';
	fa_slideshows();
}

/**
 * Slider edit - save slider metabox callback
 */
function fa_lite_save_panel(){
	$slider_id = isset( $_GET['id'] ) ? (int)$_GET['id'] : false;
	$options = FA_get_option('_fa_lite_aspect');
	
	$themes = FA_themes();
	$theme_options = FA_get_option('_fa_lite_theme');
	
	/**
	 * This is for backwards compatibility.
	 * Prior to V2.4, theme Classic was actually 2 different themes: Light and Dark.
	 * If slideshow was set on either dark or lite theme, those themes should no longer exist.
	 * If user saved them, we'll use them. If not, switch to classic.
	 */
	$load_classic = FA_should_load_classic( $theme_options['active_theme'] );
	if( $load_classic ){
		$theme_options = $load_classic;
	}
	
	$current_theme = $theme_options['active_theme'];
	$fields = FA_fields( (array)$themes[$current_theme]['theme_config']['Fields'] );
	
	$current_page = menu_page_url('featured-articles-pro', false);
	include FA_dir('displays/panel_slider_save.php');
}
/**
 * Slider edit - slider manual implementation metabox callback
 */
function fa_lite_implement_panel(){
	$slider_id = isset( $_GET['id'] ) ? (int)$_GET['id'] : false;
	include FA_dir('displays/panel_slider_implement.php');
}

function fa_lite_info_panel(){
	include FA_dir('displays/panel_cf_info.php');
}

/**
 * Add scripts and styles for slider edit page in admin
 */
function FA_edit_styles(){
	wp_enqueue_script(array(
		'FA_edit_script',
		'FA_tooltip_script',
		'jquery-ui-dialog',
		'post',
		'postbox',
		'jquery-cookie'
	));
	
	wp_enqueue_style(array(
		'FA_edit_styles',
		'FA_tooltip_styles',
		'FA_dialog',
		'thickbox',
		'jquery-ui-dialog'
	));
}
/**
 * Styling for go pro page
 */
function FA_pro_styles(){
	wp_register_style('FA_pro_styles', FA_path('styles/pro.css'));
	wp_enqueue_style('FA_pro_styles');
}
/**
 * Starting with version 2.4, slideshow themes can have their own functions.php file.
 * Using this file in a custom created theme allows to create new optional fields for 
 * the theme that are unique to it. Also, by using this functionality, messages can be displayed to user
 * when selecting your theme from themes drop-down in slideshow editing administration area.
 * 
 * Function for loading themes extra functionality. These files are called only in administration are. 
 */
function FA_run_themes_functions(){
	global $plugin_page;
	
	if( !strstr($plugin_page, 'featured-articles') ) return;	
	
	// include themes function files to allow them to run filters and hooks on admin display
	$themes = FA_themes();
	foreach( $themes as $theme=>$configs ){
		if( $configs['funcs'] ){
			include_once $configs['funcs'];
		}
	}
}
add_action('admin_init', 'FA_run_themes_functions');

/**
 * Post editing FA metabox scripts.
 */
function FA_post_edit_scripts(){
	wp_enqueue_script('farbtastic');
	wp_enqueue_style( array(
		'farbtastic'
	));
}
/**
 * Load admin styles and scripts.
 */
function FA_admin_init(){	
	// register styles
	wp_register_script('FA_edit_script', FA_path('scripts/admin_edit.js'), array( 'jquery', 'jquery-ui-sortable' ), '1.0');
	wp_register_style('FA_edit_styles', FA_path('styles/admin_edit.css'));	
	wp_register_script('FA_tooltip_script', FA_path('scripts/simpleTooltip.jquery.js'), array( 'jquery' ), '1.0');
	wp_register_style('FA_tooltip_styles', FA_path('styles/admin_tooltip.css'));	
	wp_register_style('FA_dialog', FA_path('styles/jquery-ui-dialog.css'));	
}
add_action('admin_init', 'FA_admin_init');
/**
 * Register sliders post type into wordpress
 */
function FA_init(){
	
	register_post_type( 'fa_slider', 
		array(
			'labels' => array(
	        	'name' => __('Featured Articles Sliders', 'falite'),
	        	'singular_name' => __( 'Featured Articles Slider', 'falite' )
	   		),
	    	'public' => false
	    )
	);

	register_post_type( 'fa_slide',
		array(
			'labels' => array(
	        	'name' => 'fa_slide',
	        	'singular_name' => __( 'Featured Articles Slide', 'falite' )
	   		),
	    	'public' => false
	    )
	);
	
	/**
	 * Localization is needed only for admin area
	 */
	if( is_admin() ){
		// localization - needed only for admin area
		load_plugin_textdomain( 'falite', false, dirname( 'featured-articles-lite' ) . '/languages/' );
	}	
}

/**
 * Add meta-box for posts and pages options.
 */
function FA_post_actions() {
	if( !current_user_can(FA_CAPABILITY) ) return;
    // meta box to add custom image to post or page, insert shortcode into post and feature post/page into slider
	add_meta_box( 'FA-actions', __('Featured Articles Lite', 'falite'), 'FA_meta_box', 'post', 'normal', 'high' );
    add_meta_box( 'FA-actions', __('Featured Articles Lite', 'falite'), 'FA_meta_box', 'page', 'normal', 'high' );
}
/**
 * Display the meta box for posts and pages
 */
function FA_meta_box(){
	global $post;
	// get current image attached by the user for FA Artiles
	$current_image_id = get_post_meta($post->ID, '_fa_image', true);
	if( $current_image_id ){
		$image = wp_get_attachment_image_src( $current_image_id, 'thumbnail' );
		$current_image = $image[0];
	}
		
	// check if post is already featured or not
	$meta = get_post_meta($post->ID, '_fa_featured', true);
	$featured = !empty($meta) ? $meta : array();
	global $post, $id;
	// save the original post
	$original_post = $post;
	// this is used for comments. The comments function uses a global $id variable to count comments. the current id is for the first item in loop
	$original_id = $id;
	/* get the posts */
	$args = array(
        'post_type' => 'fa_slider',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
	$loop = new WP_Query( $args );
	// extra fields
	$fields = array(
		'_fa_cust_title'=>'',
		'_fa_cust_link'=>'',
		'_fa_cust_class'=>'',
		'_fa_cust_txt'=>'',
		'_fa_bg_color'=>''
	);
	foreach($fields as $field=>$val){
		$opt = get_post_meta($post->ID, $field, true);
		if( $opt ){
			$fields[$field] = $opt;
		}
	}
	
	include('displays/panel_post.php');
	$post = $original_post;
	$id = $original_id;
}
/**
 * Saves the data for featured posts/pages
 */
function FA_save_meta(){
	if( !current_user_can(FA_CAPABILITY) ) return;
	if( isset($_POST['fa_nonce']) && wp_verify_nonce($_POST['fa_nonce'],'fa_article_featured') ){
		$id = (int)$_POST['post_ID'];		
		// feature post
		
		if( isset( $_POST['fa_lite_featured'] ) ){
			update_post_meta( $_POST['post_ID'], '_fa_featured', $_POST['fa_lite_featured']);
		}else{
			delete_post_meta( $_POST['post_ID'], '_fa_featured' );	
		}
		// delete custom image
		if( isset( $_POST['fa_remove_meta_image'] ) ){
			delete_post_meta( $_POST['post_ID'], '_fa_image' );	
		}
		// add custom stuff 
		$extra_fields = array(
			'fa_cust_title'=>'_fa_cust_title',
			'fa_cust_link'=>'_fa_cust_link',
			'fa_cust_class'=>'_fa_cust_class',
			'fa_cust_txt'=>'_fa_cust_txt',
			'fa_bg_color'=>'_fa_bg_color'
			
		);
		foreach( $extra_fields as $post_key=>$meta_field ){
			if( empty( $_POST[$post_key] ) ){
				delete_post_meta( $_POST['post_ID'], $meta_field );
			}else{
				update_post_meta( $_POST['post_ID'], $meta_field, $_POST[$post_key]);
			}
		}	
	}
}

/**
 * Shortcode slider display
 */
add_shortcode('FA_Lite', 'FA_lite_shortcode');
function FA_lite_shortcode($atts){
	extract(shortcode_atts(array(
	      'id' => false
    ), $atts));
    return FA_display_slider($id, false); 
}

/**
 * Activation hook to add admin capabilities.
 * Also stores current plugin details like plugin version, wp version and others.
 * These options may get used on future updates to allow backwards compatibility with
 * previous plugin versions. 
 */
function FA_activation(){
	// give permission to administrator to change slider settings
	if( current_user_can('manage_options') ){
		if( !current_user_can( FA_CAPABILITY ) ){
			global $wp_roles;
			$wp_roles->add_cap('administrator', FA_CAPABILITY);
		}
	}
	// get the existing options
	$existing_option = get_option('fa_plugin_details', array());
	// current plugin details
	$plugin_details = array(
		'version'=>FA_VERSION,
		'wp_version'=>get_bloginfo('version'),
		'plugin_activation_date'=>date('d M Y H:i:s'),
		'themes_folder'=>'plugins/featured-articles-lite/themes' // default themes folder is inside plugin directory
	);
	// if themes folder is already in, don't change it. It can only be changed by the user in plugin Settings page
	if( array_key_exists('themes_folder', $existing_option) ){
		// to solve a previous error
		if( strstr($existing_option['themes_folder'], 'featured-articles-pro') ){
			$plugin_details['themes_folder'] = str_replace( 'featured-articles-pro', 'featured-articles-lite', $existing_option['themes_folder'] );	
		}else{		
			$plugin_details['themes_folder'] = $existing_option['themes_folder'];
		}	
	}
	// try to create the option
	$create = add_option('fa_plugin_details', $plugin_details, '', false);
	// update current option if creation failed
	if( !$create ){
		update_option('fa_plugin_details', $plugin_details);
	}
}

register_activation_hook(WP_PLUGIN_DIR . '/featured-articles-lite/main.php', 'FA_activation');

/**
 * Admin messages
 */
function FA_admin_head(){
	if( !isset($_GET['page']) || !strstr($_GET['page'], 'featured-articles') ) return;
	/**
	 * @todo - show messages for users
	 */
}
add_action('all_admin_notices', 'FA_admin_head');
/**
 * Hooks
 */
add_action('init', 'FA_init');

add_action('admin_menu', 'FA_post_actions');
add_action('save_post', 'FA_save_meta');
// script loading in header
add_action('wp_print_scripts', 'FA_add_scripts');
add_action('wp_print_styles','FA_add_styles');
// script loading in footer for manually implemented sliders
add_action('wp_footer', 'FA_load_footer');
add_action('loop_start', 'featured_articles_slideshow',1);
?>