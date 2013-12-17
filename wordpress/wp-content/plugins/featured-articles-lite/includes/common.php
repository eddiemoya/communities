<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */


/**
 * Modify buttons in media upload to display only FA Lite button when needed
 * @param array $form_fields
 * @param object $post
 */
function attachment_fields($form_fields, $post) {
	
	if ( !substr( $post->post_mime_type, 0, 5 ) == 'image' ){
		return $form_fields;
	}	
	
	$ajax_nonce = wp_create_nonce( "fa_cust_image" );
	$form_fields['buttons'] = array( 'tr' => "\t\t<tr class='submit'><td></td><td class='savesend'><a href=\"#\" id=\"fa-custom-img-".$post->ID."\" onclick='FA_SetAsCustom(\"".$post->ID."\", \"$ajax_nonce\");return false;'>".__('Set as FA Lite featured image', 'falite')."</a></td></tr>\n" );
	
    return $form_fields;
}
/**
 * Add a post parameter to swfupload 
 * @param array $params
 */
function FA_swfupload_post_param($params){
	// check if FA Lite initiated the upload
	if( !isset( $_GET['edit-falite'] ) ) return $params;
	// set a post param to know whe're uploading an image for FA Lite
	$params['edit_falite'] = 1;
	/* 
	 * if short param is set, after upload async-upload returns only the image id and a new AJAX request will be made for the actual content. 
	 * If it isn't set, it will return directly the HTML and POST variable will be available.
	 */
	$params['short'] = 0;
	return $params;
}
// pre Wordpress 3.3 filter
add_filter('swfupload_post_params', 'FA_swfupload_post_param', 15, 1);
// Wordpress 3.3 filter
add_filter('upload_post_params', 'FA_swfupload_post_param', 15, 1);
/**
 * Check if variables are set on link to change the Add to post button into what we need.
 * Post variable comes from SWF upload as it is set in FA_swfupload_post_param() function.
 * Pay attention to short param, see description on function mentioned above.
 */
function FA_check_upload(){
	/**
	 * get variable is set when opening the modal window
	 * post variable is sent to swfupload and returned by it
	 */ 
	if( isset( $_GET['edit-falite'] ) || isset($_POST['edit_falite']) ){
		add_filter('attachment_fields_to_edit', 'attachment_fields', 10, 2 );
	}
	// add the script needed to place the image as featured
	global $pagenow;
	if( $pagenow == 'media-upload.php' ){
		wp_enqueue_script('FA_cust_image', FA_path('scripts/custom_image.js', array('jquery')));
	}
}
add_action('admin_init', 'FA_check_upload');
/**
 * Needed for the browser upload version to add the GET param on form link
 * @param string $url
 */
function FA_hijack_upload($url){
	if( strstr($url, 'media-upload.php') && isset($_GET['edit-falite']) ){
		$url.='&edit-falite=1';
	}
	return $url;
}
add_filter('admin_url', 'FA_hijack_upload');
/**
 * AJAX request response to set image as featured
 */
function FA_set_post_image(){
	
	$post_ID = intval( $_POST['post_id'] );
	if ( !current_user_can( 'edit_post', $post_ID ) )
		die( '-1' );
	$thumbnail_id = intval( $_POST['thumbnail_id'] );

	check_ajax_referer( "fa_cust_image" );
	
	update_post_meta( $post_ID, '_fa_image', $thumbnail_id );
	$th = wp_get_attachment_image_src( $thumbnail_id, 'thumbnail' );
	die($th[0]);	
}
add_action('wp_ajax_FA-post-thumbnail', 'FA_set_post_image');
/**
 * Ajax call for removing a post image
 */
function FA_remove_post_image(){
	
	$post_ID = intval( $_POST['post_id'] );
	if ( !current_user_can( 'edit_post', $post_ID ) )
		die( '-1' );
	
	check_ajax_referer( "featured-articles-post-image-remove" );
	
	delete_post_meta( $post_ID, '_fa_image');
	
	die('1');	
}
add_action('wp_ajax_FA-remove-post-thumbnail', 'FA_remove_post_image');
/**
 * Creates a hidden input in media gallery search and filter form to allow
 * searches to keep the variable that forces the FA link.
 * 
 * @since 2.4.1
 */
function FA_media_forms_set_var( $links_arr ){
	
	if( !isset( $_GET['edit-falite'] ) ) return $links_arr;

	$links_arr[] ='<input type="hidden" name="edit-falite" value="1" />';
	return $links_arr;	
}
add_filter('media_upload_mime_type_links', 'FA_media_forms_set_var', 1, 10);

/**
 * Returns the complete path of a given file from within the plugin
 *
 * @param string $file
 * @return string
 */
function FA_path( $file ){
	if( !defined('FA_PLUGIN_URL') ){
		define('FA_PLUGIN_URL', WP_PLUGIN_URL.'/featured-articles-lite/');
	}
	return FA_PLUGIN_URL.$file;	
}
/**
 * Returns complete path of a file within the plugin
 *
 * @param string $file
 * @return string
 */
function FA_dir( $file ){
	if( !defined('FA_PLUGIN_DIR') ){
		define('FA_PLUGIN_DIR', WP_PLUGIN_DIR.'/featured-articles-lite/');
	}
	return FA_PLUGIN_DIR.$file;	
}
/**
 * Truncates a text on a given number of characters. Based on the Smarty plugin
 *
 * @param string $string
 * @param int $length
 * @param string $etc
 * @param bool $break_words
 * @param bool $middle
 * @return string
 */
function FA_truncate_text($string, $length = 80, $etc = '...', $break_words = false, $middle = false){
    if ($length == 0)
        return '';
	
    if (strlen($string) > $length) {
        $length -= strlen($etc);
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length).$etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}

/**
 * Truncates a text containing HTML markup. Closes all tags that remain opened after trucncating to a given text length
 * @param string $string
 * @param int $length
 * @param string $ending
 */
function FA_truncate_html($string, $length = 80, $ending = '...'){
	// if text without HTML is smaller than length, return the whole text
	if (strlen(preg_replace('/<.*?>/', '', $string)) <= $length) {
		return $string;
	}
	
	$truncated = '';
	$total_length = strlen($ending);
	$opened = array();
	$auto_closed = array('img','br','input','hr','area','base','basefont','col','frame','isindex','link','meta','param');
	
	preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $string, $tags, PREG_SET_ORDER);
	
	foreach( $tags as $tag ){
		$tag_name = strtolower($tag[2]);
		if( !in_array($tag_name, $auto_closed) ){
			if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
				array_unshift($opened, $tag[2]);
			} else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
				$pos = array_search($closeTag[1], $opened);
				if ($pos !== false) {
					array_splice($opened, $pos, 1);
				}
			}		
		}
		// if empty, it's plain text
		if( !empty($tag[2]) )
			$truncated.=$tag[1];
		// calculate string length
		$string_length = strlen($tag[3]);
		if( $total_length + $string_length <= $length ){
			$truncated.=$tag[3];
			$total_length+=$string_length;
		}else{
			if( $total_length == 0 ){
				$truncated.= substr($tag[3], 0, $length).$ending;
				break;	
			}
			$diff = $length - $total_length;
			$truncated.= substr($tag[3], 0, $diff).$ending;
			break;
		}		
	}
	// close all opened tags
	foreach ($opened as $tag) {
		$truncated .= '</'.$tag.'>';
	}
	return $truncated;
}

/**
 * Returns the maximum size in pixels for a given size name
 * @param string $size
 */
function FA_image_size_pixels($size){
	global $_wp_additional_image_sizes;

	if ( $size == 'thumb' || $size == 'thumbnail' ) {
		$max_width = intval(get_option('thumbnail_size_w'));
		$max_height = intval(get_option('thumbnail_size_h'));
		
		if ( !$max_width && !$max_height ) {
			$max_width = 128;
			$max_height = 96;
		}
	}
	elseif ( $size == 'medium' ) {
		$max_width = intval(get_option('medium_size_w'));
		$max_height = intval(get_option('medium_size_h'));
	}
	elseif ( $size == 'large' ) {
		$max_width = intval(get_option('large_size_w'));
		$max_height = intval(get_option('large_size_h'));
	} elseif ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) && in_array( $size, array_keys( $_wp_additional_image_sizes ) ) ) {
		$max_width = intval( $_wp_additional_image_sizes[$size]['width'] );
		$max_height = intval( $_wp_additional_image_sizes[$size]['height'] );
	}
	
	if( isset($max_width) && isset($max_height) ){
		return array('w'=>$max_width, 'h'=>$max_height);
	}	
	// $size == 'full' has no constraint
	else {
		return array();
	}	
}
/**
 * Returns the image path set in meta field for a post or page
 * @param int $post_id
 * @param string $meta_field
 * @param string/array $size
 */
function FA_get_meta_image($post_id, $meta_field, $size){
	$meta_image_id = get_post_meta($post_id, $meta_field, true);
	$meta_image = wp_get_attachment_image_src( $meta_image_id, $size );
	return $meta_image;
}

/**
 * Scan given content for images and try to detect image id from database.
 * Returns an array with keys img and id. If image path is detected, img key will hold that value.
 * If image id can be detected, id key will hold that value
 * @param HTML string $content
 * @param string/array $size
 */
function FA_scan_image($content, $size = 'thumbnail'){
	// check for images in text
	preg_match_all("#\<img(.*)src\=(\"|\')(.*)(\"|\')(/?[^\>]+)\>#Ui", $content, $matches);
	// no image is available
	if( !isset($matches[0][0]) ){ 
		return false;
	}
	
	$result = array('img'=>false, 'id'=>false);
	
	// get image attributes in order to determine the attachment guid
	preg_match_all("#([a-z]+)=\"(.*)\"#Ui", $matches[0][0], $attrs);
	$inversed = array_flip($attrs[1]);
	
	// if image doesn't have width/height attributes set on it, there's no point in going further
	if( !array_key_exists('width', $inversed) || !array_key_exists('height', $inversed) ){
		$result['img'] = $matches[3][0];
		return $result;
	}
	
	// image attributes hold the image URL. Replace those to get the real image guid
	$img_size_url = '-'.$attrs[2][$inversed['width']].'x'.$attrs[2][$inversed['height']];
	$real_image_guid = str_replace( $img_size_url, '', $matches[3][0] );
	
	global $wpdb;
	$the_image = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid = '$real_image_guid' AND post_type='attachment'" ) );
	// if unsuccessful, return the image url from content
	if( !$the_image ){
		$result['img'] = $matches[3][0];
	}else{
		$meta_image = wp_get_attachment_image_src( $the_image->ID, $size );
		if( $meta_image ){
			// if meta image was found, set the id as custom field for the post so that all the query work won't be needed again
			$result['id'] = $the_image->ID;
			$result['img'] = $meta_image[0];
		}else{
			$result['img'] = $matches[3][0];
		}	
	}
	return $result;
}

/**
 * Image detection inside post
 *
 * @param object $post
 * @return string - image path
 */
function FA_article_image ($post, $slider_id, $other_size = false){
	// if thumbnails are stopped from admin, return false
	$options = FA_slider_options($slider_id, '_fa_lite_aspect');
	if( !$options['thumbnail_display'] ) 
		return false;
	
	$image_size = $other_size ? $other_size : $options['th_size'];	
		
	// check for custom field image	
	$meta_image = FA_get_meta_image($post->ID, '_fa_image', $image_size);
	if( $meta_image )
		return $meta_image[0];
		
	// check for post thumbnail
	if( current_theme_supports('post-thumbnails') ){
		$meta_image = FA_get_meta_image($post->ID, '_thumbnail_id', $image_size);
		if( $meta_image )
			return $meta_image[0];
	}	

	// check for auto detected images
	$meta_image = FA_get_meta_image($post->ID, '_fa_image_autodetect', $image_size);
	if( $meta_image ){
		return $meta_image[0];
	}
	// if none of the meta keys is set, try to auto detect image in post content		
	$image = FA_scan_image($post->post_content, $image_size);
	if( !$image['img'] ) 
		return false;
	
	if( $image['id'] ){
		update_post_meta($post->ID, '_fa_image_autodetect', $image['id']);
	}

	return $image['img'];
}
/**
 * For image autodetection, scan post text to save the image on save_post action.
 */
function FA_check_autoimg_update($post_id){
	
	$meta_key = get_post_meta($post_id, '_fa_image_autodetect', true);
	if(!$meta_key) return;
	
	$post = get_post($post_id);
	$image = FA_scan_image($post->post_content);
	if( $image['id'] ){
		update_post_meta($post_id, '_fa_image_autodetect', $image['id']);	
	}else{
		delete_post_meta($post_id, '_fa_image_autodetect');
	}
}
add_action('save_post', 'FA_check_autoimg_update');

/**
 * Reads a given folder and returns the files list
 * @param string $path
 * @param array $exclude
 */
function FA_read_dir( $path, $exclude = array() ){
	$result = array();
	
	if( !is_dir($path) ) return $result;
	
	$not = array('.', '..');
	$exclude = array_merge($exclude, $not);	
	if ($handle = opendir( $path )) {
		while (false !== ($file = readdir($handle))) {
			if( in_array($file, $exclude) || substr($file, 0, 1) == '.' || substr($file, 0, 1) == '_' ) continue;
			$result[] = $file;
		}		
	}
	return $result;
}
/**
 * Returns an array of available themes. Also, can return themes from a different
 * folder (if provided). This second option is used when user tries to modify
 * default themes folder from within plugin settings in Wordpress admin
 * 
 * @since 2.4 - modified in 2.4.1
 */
function FA_themes( $new_path = false ){
	
	/**
	 * If new path is given, read it for the themes.
	 * This is used to check if user copied themes files into new folder before trying to change themes folder path.
	 */
	if( $new_path ){
		$themes_dir_full_path = WP_CONTENT_DIR.'/'.$new_path;
		$themes_dir_full_url = WP_CONTENT_URL.'/'.$new_path;
	}else{// let's look inside the default folder
		$themes_dir_full_path = FA_themes_path();
		$themes_dir_full_url = FA_themes_url();	
	}
	
	$theme_folders = FA_read_dir( $themes_dir_full_path );	
	
	// will store all themes details
	$themes = array();
	foreach ($theme_folders as $theme){
		
		// theme path and URL
		$theme_path = $themes_dir_full_path.'/'.$theme;
		$theme_url = $themes_dir_full_url.'/'.$theme;
		
		// default theme information		
		$default_headers = array(
        	'Author'		=>'',
        	'AuthorURI'		=>'', 
        	'Copyright'		=>'', 
        	'Fields'		=> array(), 
        	'Image'			=>'',
        	'Message'		=>''
        );
        // filter theme details. This way themes can add their own information.
		$theme_details = apply_filters('fa-theme-details-'.$theme, $default_headers);
		
		// theme should have a display.php file. If it doesn't, skip it.
		$display_file = $theme_path.'/display.php';
		if( !is_file( $display_file ) ){
			continue; // stop processing this theme. It doesn't have a display.php file
		}
		
		// merge filtered array with defaults and put it into theme config key
		$themes[$theme]['theme_config'] = wp_parse_args($theme_details, $default_headers);;
		
		// let's see if theme has preview image
        $preview_image = $theme_path.'/preview.jpg';
        if( is_file($preview_image) ){
        	$themes[$theme]['preview'] = $theme_url.'/preview.jpg';        	
        }else{
        	$themes[$theme]['preview'] = 0;        	
        }

        // next, check for functions file
        $functions_file = $theme_path.'/functions.php';
        if( is_file($functions_file) ){
        	$themes[$theme]['funcs'] = $functions_file;
        }else{
        	$themes[$theme]['funcs'] = false;
        }
        
        // last, check colors stylesheets
        $colors_path = $theme_path.'/colors/';
	    $colors = FA_read_dir( $colors_path );	
	    $themes[$theme]['colors'] = $colors;        
	}
	
	return $themes;	
}

/**
 * Themes backwards compatibility function. Based on theme name passed to it,
 * if theme is old dark or old light, verifies the existance of the themes.
 * 
 * If dark of light is set, it will return an array with classic theme and appropriate
 * color stylesheet for it.
 *
 * @param string $theme - theme name
 * @return bool
 * @since 2.4.2
 */
function FA_should_load_classic( $theme ){
	if( 'dark' == $theme || 'light' == $theme ){
		$check_theme_path = FA_theme_path($theme);// verifies folder existance
		// theme dark or light doesn't exist. Switch to classic
		if( !$check_theme_path ){
			$result = array(
				'active_theme' => 'classic',
				'active_theme_color' => $theme.'.css'
			);			
			return $result;
		}
	}
	return false;
}


/***********************************************************************************************
 * Slideshow theme template functions
 ***********************************************************************************************/

/**
 * Echoes the current item image. Shorthand for do_the_fa_image
 * @param string $before
 * @param string $after
 */
function the_fa_image( $before = '<div class="image_container">', $after = '</div>' ){
	global $FA_slider_options;
	
	$image = do_the_fa_image($before, $after, $FA_slider_options['_fa_lite_aspect']['thumbnail_click']);
	if( $image )
		echo $image;	
}

/**
 * Template function for displaying the image
 * @param string $image
 * @param string $before
 * @param string $after
 * @param bool $echo
 */
function do_the_fa_image($before = '', $after = '', $clickable = false) {
	// use the global $post since it's set by our loop and the original post gets restored after slider is displayed
	global $post;
	
	if( !$post || (!isset($post->FA_image) || empty($post->FA_image) ) ) return;
	// assume not link on image
	$link_open = '';
	$link_close = '';
	// set opening and closing tags for image link
	if( $clickable ){
		$link_open = sprintf('<a href="%1$s" title="%2$s" target="%3$s">', get_permalink($post->ID), $post->post_title, $post->link_target);
		$link_close = '</a>';
	}
	// add link to image if any
	$image_html = sprintf('%1$s<img src="%2$s" alt="" />%3$s', $link_open, $post->FA_image, $link_close);
	return $before . $image_html . $after;	
}
/**
 * Echoes the title. Shorthand for do_the_fa_title
 * @param string $before
 * @param string $after
 */
function the_fa_title( $before = '', $after = ''){
	global $FA_slider_options;
	$title = do_the_fa_title($before, $after, $FA_slider_options['_fa_lite_aspect']['title_click']);
	if( $title )
		echo $title;
}
/**
 * Template function to display the slide title. Also adds link if set in options
 * @param string $before
 * @param string $after
 * @param bool $clickable
 * @param bool $echo
 */
function do_the_fa_title( $before = '', $after = '', $clickable = false ){
	// use the global $post since it's set by our loop and the original post gets restored after slider is displayed
	global $post;
	
	if( !$post ) return;
	// not wrapped in link
	$link_open = '';
	$link_close = '';
	// wrap in link if set
	if( $clickable ){
		$link_open = sprintf('<a href="%1$s" title="%2$s" target="%3$s">', get_permalink($post->ID), $post->post_title, $post->link_target);
		$link_close = '</a>';
	}
	// put it all in one var
	// run filters on title to enable plugins like qTranslate to return the correct title
	$title_html = $link_open . apply_filters('the_title', $post->post_title) . $link_close;
	return $before . $title_html . $after;
}

/**
 * Echoes the post content. Shorthand for do_the_fa_content
 * @param string $before
 * @param string $after
 */
function the_fa_content( $before = '', $after = '' ){
	global $FA_slider_options;
	$content = do_the_fa_content($before, $after, $FA_slider_options['_fa_lite_aspect']['strip_shortcodes']);
	if( $content )
		echo $content;
}

/**
 * Returns or echoes the slide content. Can do shortcodes if option is set
 * @param string $before
 * @param string $after
 * @param bool $strip_shortcodes
 * @param bool $echo
 */
function do_the_fa_content( $before = '', $after = '', $strip_shortcodes = true ){
	// use the global $post since it's set by our loop and the original post gets restored after slider is displayed
	global $post;
	
	if( !$post ) return;
	
	$content_html = '';
	if( !$strip_shortcodes ){
		$content_html .= do_shortcode($post->FA_post_content);
	}else{
		$content_html .= strip_shortcodes($post->FA_post_content);
	}
	
	$result = $before.$content_html.$after;
	
	return $result;
}
/**
 * Outputs the read-more link
 */
function the_fa_read_more( $class = 'FA_read_more' ){
	// use the global $post since it's set by our loop and the original post gets restored after slider is displayed
	global $post;
	
	if( !$post ) return;
	
	$link_html = '<a class="%1$s" href="%2$s" title="%3$s" target="%4$s">%5$s</a>';
	
	printf( $link_html,
		$class, // %1$s
		get_permalink($post->ID), // %2$s
		$post->post_title, // %3$s
		$post->link_target, // %4$s
		$post->fa_read_more // %5$s
	);	
}

/**
 * Returns the image as background styling
 * @param string $image
 * @param string $position
 * @param string $repeat
 * @param bool $echo
 */
function the_fa_background($show_image = true, $position = 'top left', $repeat = 'no-repeat', $echo = true ){
	
	global $post;
	
	if( !$post ) return;
	
	$declarations = array();
	
	$bg_color = get_post_meta($post->ID, '_fa_bg_color', true);
	if( !empty( $bg_color ) ){
		$declarations[] = 'background-color:'.$bg_color;
	}
	
	if( $show_image && !empty($post->FA_image) ){
		$declarations[] = 'background-image:url('.$post->FA_image.'); background-position:'.$position.'; background-repeat:'.$repeat;
	}
	
	if( $echo )
		echo implode('; ', $declarations);
	else 
		return implode('; ', $declarations);	
}
/**
 * Display the title above the slider. Tests if title should be displayed in options.
 * @param string $before
 * @param string $after
 * @param bool $echo
 */
function the_slideshow_title($before = '<h2 class="FA_title_section">', $after = '</h2>', $echo = true){
	
	$display_title = FA_get_option( array('_fa_lite_aspect', 'section_display') );
	if( 1 != $display_title ) 
		return;
	
	$title = FA_get_option( array('_fa_lite_aspect', 'section_title') );
	
	if( $echo ){
		echo $before.$title.$after;
	}else{
		return $before.$title.$after;
	}	
}
/**
 * Outputs the slider height styling
 */
function the_slider_height( $echo = true ){
	$size = FA_style_size();
	if( $echo )
		echo $size['y'];
	else
		return $size['y'];
}
/**
 * Outputs the slider width styling
 */
function the_slider_width( $echo = true ){
	$size = FA_style_size();
	if( $echo )
		echo $size['x'];
	else
		return $size['x'];
}
/**
 * Returns CSS class for slider color scheme. Useful when multiple slideshows are displayed into the same page, 
 * having the same slideshow theme but different color schemes.
 * 
 * @param bool $echo - display result(true) or return it (false)
 */
function the_slider_color( $echo = true ){
	$theme = FA_get_option('_fa_lite_theme');
	$color = '';
	
	if( isset( $theme['active_theme_color'] ) ){
		$color = str_replace('.css', '', $theme['active_theme_color']);
	}
	
	if( $echo ){
		echo ' '.$color;
	}else{
		return $color;
	}	
}
/**
 * Outpus a slide CSS class specified by user in wp-admin
 */
function the_fa_class( $echo = true ){
	global $post;
	
	if( !isset( $post->css_class ) || empty( $post->css_class ) )
		return;
	
	if( $echo )
		echo $post->css_class;
	else 	
		return $post->css_class;	
}

/*
 * Returns a simple array with width and height styling for easy access in theme.
 * These values are used for resizing the slider according to admin user settings
 */
function FA_style_size(){
	
	$width = FA_get_option(array( '_fa_lite_aspect', 'slider_width'));
	$height = FA_get_option(array( '_fa_lite_aspect', 'slider_height'));
	
	//$options = FA_slider_options($slider_id, '_fa_lite_aspect');
	$size = array('x'=>'', 'y'=>'');
	if( !empty( $width ) && 0 != $width ){
		$size['x'] = 'width:'.$width.( is_numeric( $width ) ? 'px':'' );
	}	
	if( !empty( $height ) && 0 != $height ){
		$size['y'] = 'height:'.$height.( is_numeric( $height ) ? 'px':'' );
	}	
	return $size;
}
/**
 * Checks in options if bottom navigation should be displayed
 */
function has_bottom_nav(){
	
	$bottom_nav_option = FA_get_option( array('_fa_lite_aspect', 'bottom_nav') );
	if( 1 != $bottom_nav_option )
		return false;
	
	return true;
}
/**
 * Checks in options is sideways navigatin should be displayed
 */
function has_sideways_nav(){
	
	$sideways_nav_option = FA_get_option( array('_fa_lite_aspect', 'sideways_nav') );
	if( 1 != $sideways_nav_option )
		return false;
	
	return true;
}


/***********************************************************************************************
 * Slideshow content retrieval
 ***********************************************************************************************/

/**
 * Get posts according to options set for a certain slider
 * @param int $slider_id
 * @param array $options
 */
function FA_get_posts($slider_id, $options = array()){
	if( !$options ){
		$options = FA_slider_options( $slider_id, '_fa_lite_content' );
	}

	if( !is_array($options['display_from_category']) ){
		$options['display_from_category'] = array();
	}else{
		if( empty($options['display_from_category'][0]) ){
			array_shift($options['display_from_category']);
		}
	}
	
	$args = array(
		'numberposts' => $options['num_articles'],
		'category' => implode(',',$options['display_from_category']),
		'order'=>'DESC'
	);
	// Filter by author
	if( isset($options['author']) && 0!=$options['author'] ){
		$args['author'] = $options['author'];
	}
	
	switch ($options['display_order']){
		case 1: 
		default:
			$args['orderby'] = 'post_date';
		break;
		case 2:
			$args['orderby'] = 'comment_count post_date';
		break;
		case 3:
			$args['orderby'] = 'rand';
		break;
	}

	return get_posts($args);
}

/**
 * Get pages for a certain slider.
 * @param int $slider_id
 * @param array $options
 */
function FA_get_pages( $slider_id, $options = array() ){
	if( !$options ){
		$options = FA_slider_options( $slider_id, '_fa_lite_content' );
	}
	
	$meta_key = '_fa_lite_'.$slider_id.'_page_ord';
	$posts = $options['display_pages'];
	
	if(empty($posts)){
		return array();
	}
	
	$args = array(
		'post__in'			=> $posts,
		'post_type'			=> 'page',
		'post_status'		=>'publish',
		'meta_key'			=>$meta_key,
		'orderby'			=>'meta_value',
		'order'				=>'ASC',
		'posts_per_page'	=>'-1'
	);
	
	$query = new WP_Query($args);
	$postslist = $query->posts;	
	return $postslist;
}

/**
 * Returns the content list according to the settings
 */
function FA_get_content( $slider_id ){
	$options = FA_slider_options( $slider_id, '_fa_lite_content' );
	
	if( $options['displayed_content'] == 1 ){
		$postslist = FA_get_posts($slider_id, $options);
	}else{
		$postslist = FA_get_pages($slider_id, $options);
	}
	
	$aspect_opt = FA_slider_options( $slider_id, '_fa_lite_aspect' );
	foreach($postslist as $k=>$v){
		// detect post image and save it in returned array
		$image = FA_article_image($v, $slider_id);
		$postslist[$k]->FA_image = $image;
		
		// use custom titles if set
		if( $aspect_opt['title_custom'] ){
			$title_txt = get_post_meta($v->ID, '_fa_cust_title', true);
			if( $title_txt && !empty($title_txt) ){
				$postslist[$k]->post_title = $title_txt;
			}
		}
		
		// check if custom read-more link is set else set it as the default for the slider
		$cust_read_more = get_post_meta($v->ID, '_fa_cust_link', true);
		if( !empty( $cust_read_more ) ){
			$postslist[$k]->fa_read_more = $cust_read_more;
		}else{
			$postslist[$k]->fa_read_more = $aspect_opt['read_more'];
		}
		
		// check if extra CSS classes are set on item
		$extra_css = get_post_meta($v->ID, '_fa_cust_class', true);
		$postslist[$k]->css_class = $extra_css ? $extra_css : '';
		
		// link target
		$link_target = get_post_meta($v->ID, '_fa_cust_url_blank' , true);
		$postslist[$k]->link_target = $link_target == 1 ? '_blank' : '_self';
		
		// custom slides are displayed using the complete text entered by the user
		if( 'fa_slide' == $v->post_type ){
			$postslist[$k]->FA_post_content = $v->post_content;
			continue;
		}		
		// custom text for FA Lite
		if( $aspect_opt['use_custom_text'] ){
			$txt = get_post_meta($v->ID, '_fa_cust_txt', true);
			if( $txt && !empty($txt) ){
				$postslist[$k]->FA_post_content = $txt;
				continue;
			}
		}
		// use excerpt if option is on		
		if( $aspect_opt['use_excerpt'] ){
			if( !empty($v->post_excerpt) ){
				$postslist[$k]->FA_post_content = $v->post_excerpt;
				continue;
			}	
		}

		// this part is skipped if user uption for content is either custom text or excerpt and at least one is not empty
		$content = $v->post_content;
		// remove shortcodes if set
		if( $aspect_opt['strip_shortcodes'] ){
			$content = strip_shortcodes($content);
		}
		// remove all HTML tags except links
	    $string = strip_tags($content, $aspect_opt['allowed_tags']);
	    //store the slider stripped text into a different variable
	    
	    $strlen = $image ? $aspect_opt['desc_truncate'] : $aspect_opt['desc_truncate_noimg'];
	    
	    if( !empty($aspect_opt['allowed_tags']) ){
	    	$postslist[$k]->FA_post_content = FA_truncate_html($string, $strlen, $aspect_opt['end_truncate']);	
	    }else{	    
			$postslist[$k]->FA_post_content = FA_truncate_text($string, $strlen, $aspect_opt['end_truncate']);
	    }
	}	
	return $postslist;
}

/**
 * Creates a JSON string from an array
 * @param array $array
 */
function FA_lite_json($array){
	if( function_exists('json_encode') ){
		return json_encode($array);
	}else{
		if( file_exists(ABSPATH.'/wp-includes/js/tinymce/plugins/spellchecker/classes/utils/JSON.php') ){
			require_once(ABSPATH.'/wp-includes/js/tinymce/plugins/spellchecker/classes/utils/JSON.php');
			$json_obj = new Moxiecode_JSON();
			return $json_obj->encode($array);
		}	
	}
}
/**
 * Updates options for pages and categories display
 * @param string $option
 * @param int $slider_id
 * @param array/bool $new_values
 */
function FA_update_display($option = false, $slider_id, $new_values){
	$options = array('fa_lite_categories', 'fa_lite_pages');
	if(!$option) return;
	if( !in_array($option, $options) ) return;
	
	// save categories where the slider is displayed
	$wp_opt = get_option($option, array());
	if( $new_values ){
		foreach ( $wp_opt as $c=>$s){
			if( !in_array($c, $new_values) ){
				unset($wp_opt[$c][$slider_id]);
			}
			if( empty($wp_opt[$c]) ){
				unset($wp_opt[$c]);
			}
		}		
		foreach ($new_values as $new_cat){
			$wp_opt[$new_cat][$slider_id] = $slider_id;
		}
	}else{
		foreach( $wp_opt as $cat=>$sliders ){
			if( is_array($slider_id) ){
				$wp_opt[$cat] = array_diff($wp_opt[$cat], $slider_id);
			}else if(in_array($slider_id, $sliders)){
				unset($wp_opt[$cat][$slider_id]);
			}
			if( empty($wp_opt[$cat]) ){
				unset($wp_opt[$cat]);
			}			
		}
	}
	update_option($option, $wp_opt);	
}

/**
 * Deletes sliders by bulk delete or individual items delete
 * @param int/array $item
 */
function FA_delete_sliders( $item ){
	if( !$item ) return false;
	// delete multiple sliders if parameter is an array
	if( is_array($item) ){
		foreach ($item as $id){
			$id = (int)$id;
			wp_delete_post($id, true);
		}
	}else{
		// delete single ids
		$id = (int)$item;
		wp_delete_post($id, true);
	}
	// get sliders set to display on home page
	$home_sliders = get_option('fa_lite_home', array());
	if( !is_array($item) && in_array($item, $home_sliders) ){
		unset($home_sliders[$item]);
	}
	if( is_array($item) ){
		$home_sliders = array_diff($home_sliders, $item);
	}
	update_option('fa_lite_home', $home_sliders);
	
	// remove the slider id from pages and categories display
	FA_update_display('fa_lite_categories', $item, false);
	FA_update_display('fa_lite_pages', $item, false);
	
}

/**
 * Checks the current location to see if any slider needs to be displayed
 */
function FA_display(){
	$sliders = array();
	
	if( !fa_load_in_mobile() ){
		return $sliders;
	}
	
	if( is_home() ){
		$option = get_option('fa_lite_home', false);
		if( $option )
			$sliders = $option;		
	}else if( is_category() ){
		$option = get_option('fa_lite_categories', false);
		$cat_ID = get_query_var('cat');
		if( $option && array_key_exists($cat_ID, $option) ){
			$sliders = $option[$cat_ID];
		}				
	}else if( is_page() ){
		$option = get_option('fa_lite_pages', false);
		if( $option ){
			global $post;
			if( array_key_exists($post->ID, $option) ){
				$sliders = $option[$post->ID];
			}
		}		
	}

	// check if posts exist
	foreach( $sliders as $key=>$slider ){
		// first check if id exists
		if( !is_numeric($slider) ){
			unset( $sliders[$key] );
			continue;
		}
		// second check if post exists
		if( !get_post( $slider ) ){
			unset( $sliders[$key] );
		}
	}
	
	return $sliders;
}

/**
 * Returns the slider options from database or the default values
 * @param int $id - slider id
 * @param string $meta_key - the meta key id from database
 */
function FA_slider_options( $id = false, $meta_key = false ){
	// the default values
	$fields = array(
		'_fa_lite_content'=>array(
			'num_articles'				=>5, // number of articles to retrieve
			'author'					=>0,
			'display_order'				=>1, // display articles order
			'display_pages'				=>array(), // pages in slider
			'display_featured'			=>array(), // custom featured content in slider
			'display_from_category'		=>array(), // posts from categories
			'displayed_content'			=>1 // type of content to display
		),
		'_fa_lite_aspect'=>array(
			'section_display'			=>1, // display title of slider
			'section_title'				=>'Featured articles', // default slider title
			'slider_width'				=>'100%', // slider width
			'slider_height'				=>300, // slider height
			'thumbnail_display'			=>true, // display article image
			'th_size'					=>'thumbnail', // article image size
			'thumbnail_click'			=>false, // article image is clickable
			'title_click'				=>false, // title is clickable
			'title_custom'				=>false, // custom defined title usage
			'use_excerpt'				=>false, // excerpt usage
			'use_custom_text'			=>false, // use custom text set on posts/pages
			'strip_shortcodes'			=>true, // remove shortcodes from post/page/custom slide content
			'desc_truncate'				=>500, // truncate descriptions with image
			'desc_truncate_noimg'		=>800, // truncate descriptions without image
			'end_truncate'				=>'...', // end truncated text with this
			'read_more'					=>'Read more', // read more link text
			'allowed_tags'				=>'<a>',	 // allowed tags in truncated descriptions
			'bottom_nav'				=>true, // display bottom navigation
			'sideways_nav'				=>true // display sideways navigation
		),
		'_fa_lite_display'				=>array(
			'loop_display'				=>0, // display on loop x - for automatically placed sliders
			'show_author'				=>true // show author link
		),
		'_fa_lite_js'=>array(
			'slideDuration'				=>5, // duration in seconds for a slide to stay on page
			'effectDuration'			=>.6, // duration of animation effect
			'fadeDist'					=>0, // fade distance
			'fadePosition'				=>'left', // fade position
			'stopSlideOnClick'			=>false, // is auto is on and user clicks navigation, stop slider
			'autoSlide'					=>false, // slider changes slides automatically
			'mouseWheelNav'				=>true // enable mouse wheel navigation
		),
		'_fa_lite_theme'=>array(
			'active_theme'				=>'classic', // active theme
			'active_theme_color'		=>'dark' // active theme color scheme
		),
		'_fa_lite_home_display'			=>false, // store option to display slider on homepage
		'_fa_lite_categ_display'		=>array(), // store categories to display slider on
		'_fa_lite_page_display'			=>array() // store pages to display slider on
	);
	
	$fields = apply_filters('fa-extend-options', $fields);
	
	// if no post ID is set, return default values
	if( !$id ){
		if( !$meta_key )
			return $fields;
		else 	
			return $fields[$meta_key];	
	}
	// if a certain meta key is searched, return only the values associated with it
	if( $meta_key ){
		$defaults = $fields[$meta_key];
		$post_meta = get_post_meta($id, $meta_key, true);
		if( !empty($post_meta) || ( is_bool($defaults) || empty($defaults) ) ){
			if( is_array($post_meta) ){
				$defaults = array_merge($defaults, $post_meta);
			}else{
				$defaults = $post_meta;
			}	
		}
		return $defaults;
	}
	// return all values
	foreach( $fields as $key=>$values ){
		$post_meta = get_post_meta($id, $key, true);
		if( !empty($post_meta) || ( is_bool($values) || empty($values) ) ){
			if( is_array($post_meta) ){
				$post_meta = array_merge($values, $post_meta);
			}
			
			$fields[$key] = $post_meta;
		}
	}
	return $fields;	
}

/**
 * For a given slider ID the function sets as globals the slider options.
 * To get an option, use FA_get_option
 * @param int $slider_id
 */
function FA_set_slider_options( $slider_id ){	
	global $FA_slider_options;	
	$FA_slider_options = FA_slider_options($slider_id);	
}
/**
 * Returns a certain option based on the key passed to it. Doesn't do any verification 
 * for key existance or anything so make sure the key exists.
 * 
 * Options are stored into a 2 levels array. Main keys are groups of options
 * and subsequent keys are the actual options.
 * 
 * To get only one option, pass as parameter an array containing the main key
 * and the option key. In this case the funciton will return a single value.
 * 
 * @param string/array $key
 */
function FA_get_option( $key ){
	global $FA_slider_options;
	
	// to return a single value, pass an array make of main key and subset key
	if( is_array($key) ){
		$set = $FA_slider_options[$key[0]];
		return $set[$key[1]];
	}else{
		return $FA_slider_options[$key];	
	}	
}

/**
 * Returns according to theme all configurable fields
 * @param array $theme_params
 */
function FA_fields($theme_params){
	
	$all_config_fields = array(
		'section_display'		=>1,
		'title_click'			=>1,
		'thumbnail_display'		=>1,
		'thumbnail_click'		=>1,
		'desc_truncate_noimg'	=>1,
		'bottom_nav'			=>1,
		'sideways_nav'			=>1,
		'effectDuration'		=>1,
		'fadeDist'				=>1,
		'fadePosition'			=>1
	);
	$all_config_fields = apply_filters('fa-extend-optional-fields', $all_config_fields);
	
	foreach ( $all_config_fields as $k=>$v ){
		if( array_key_exists($k, $theme_params) ){
			$all_config_fields[$k] = $theme_params[$k];
		}
	}	
	return $all_config_fields;	
}

/**
 * Stores and retrieves general plugin options
 */
function FA_plugin_options(){
	
	$default_options = array(
		'complete_uninstall'=>0,
		'auto_insert'		=>1,
		'load_in_wptouch'	=>0
	);
	$option_name = 'feat_art_options';
	
	if( isset( $_POST['fa_options'] ) && !empty($_POST['fa_options']) ){
		if( wp_verify_nonce($_POST['fa_options'],'featured-articles-set-options') ){			
			foreach ($default_options as $option=>$value){
				if( isset( $_POST[$option] ) ){
					$default_options[$option] = $_POST[$option];
				}else{
					$default_options[$option] = 0;
				}							
			}
			$o = add_option($option_name, $default_options, '', 'no');
			if( !$o ){
				update_option($option_name, $default_options);
			}
			return $default_options;				
		}
	}
	
	$o = get_option($option_name, array());
	$result = array_merge($default_options, $o);
	return $result;
}

/*********************************************************************************************************
 * Slideshow themes path solving. Searches the current set folder to hold themes.
 *********************************************************************************************************/

/**
 * Return complete path to given theme folder. Enter only the folder name.
 * Function will check folder existance.
 * 
 * @param string $theme_folder
 * @return complete path to folder
 * @since 2.4.1
 */
function FA_theme_path( $theme_folder ){
	$themes_folder = FA_themes_path();
	$full_path = $themes_folder.'/'.$theme_folder;
	// check folder existance
	if( !is_dir($full_path) ){	
		$full_path = false;
	}else if( !is_file( $full_path.'/display.php' ) ){ // also check existance for display.php file
		$full_path = false;
	}
	return $full_path;	
}

/**
 * Return complete url to a given theme folder
 * 
 * @since 2.4.1
 */
function FA_theme_url( $theme_folder ){
	$themes_url = FA_themes_url();
	return $themes_url.'/'.$theme_folder;	
}

/**
 * Returns or echoes the path to current FA themes folder.
 * Path can be returned full or relative to wp content folder
 * @param bool $full_path
 * @param bool $echo
 * @return path to FA themes current folder
 * 
 * @since 2.4.1
 */

function FA_themes_path( $full_path = true, $echo  = false ){
	$path = FA_get_themes_folder( $full_path, false );
	if( $echo ){
		echo $path;
	}else{
		return $path;
	}
}

/**
 * Returns or echoes full URL to current FA themes folder
 * @param bool $echo
 * @return full url to themes folder
 * 
 * @since 2.4.1
 */
function FA_themes_url( $echo = false ){
	$url = FA_get_themes_folder( true, $echo, true );
	if( $echo ){
		echo $url;
	}else{
		return $url;
	}
}

/**
 * FA themes folder can be changed from plugin administration. This solves the problem with the themes being deleted on plugin update.
 * Theme folder must be located inside wp content folder, where default plugins and themes folders from Wordpress are.
 * 
 * Use either FA_themes_path() or FA_themes_url() to retrieve folder path information.
 * 
 * @param bool $full_path -  return a full path (true) or just the path from inside wp content folder(false)
 * @param bool $echo - return the path (false) or echo it(true)
 * @param bool $return_url - return url instead of path
 * @return path to FA themes folder
 * 
 * @since 2.4.1
 */
function FA_get_themes_folder( $full_path = true, $echo = false, $return_url = false ){
	// plugin option set on activation.
	$plugin_options = get_option('fa_plugin_details', array());
	
	// if themes folder path is in plugin options, let's use it
	if( array_key_exists('themes_folder', $plugin_options) ){
		// small problem with themes folder settings being set wrong. Correct it if it's the case.
		if( strstr($plugin_options['themes_folder'], 'featured-articles-pro') ){
			$plugin_options['themes_folder'] = str_replace('featured-articles-pro', 'featured-articles-lite', $plugin_options['themes_folder']);
		}
		
		if( !$full_path ){
			$path = $plugin_options['themes_folder'];
		}else{
			$path = ( $return_url ? WP_CONTENT_URL : WP_CONTENT_DIR ).'/'.$plugin_options['themes_folder'];
		}	
	}else{ // the default path is inside plugin folder
		$path =  $return_url ? FA_path('themes') : FA_dir('themes');
		if( !$full_path ){			
			$remove = $return_url ? WP_CONTENT_URL : WP_CONTENT_DIR;			
			$path = str_replace($remove.'/', '', $path);	
		}			
	}
	
	if( $echo ){
		echo $path;
	}else{	
		return $path;
	}	
}

/**
 * Sets the option that stores the themes folder path. Function first checks that the folder already exists. After doing that, it 
 * verifies that the themes are copied inside it. Only after all that is done it will actually set it.
 * 
 * @param string $new_rel_path - new relative path to themes folder
 * @return bool - true on success, false on failure
 * 
 * @since 2.4.1
 */
function FA_set_themes_folder( $new_rel_path = false ){
	
	if( !$new_rel_path || empty($new_rel_path) )
		return false;
	// new path should be only folder path within Wordpress content dir so let's create the full path
	$full_path = WP_CONTENT_DIR.'/'.$new_rel_path;
	// if new path isn't an existing folder, bail out
	if( !is_dir($full_path) ){
		return false;
	}	
	
	// let's read this new folder and see if themes are copied in it
	$themes = FA_themes( $new_rel_path );
	if( !$themes )
		return false;
	
	// by now all should be OK. Let's set the new option
	$plugin_options = get_option('fa_plugin_details', array());
	$new_path = array(
		'themes_folder' => $new_rel_path
	);
	$new_params = wp_parse_args($new_path, $plugin_options);
	update_option('fa_plugin_details', $new_params);
	return true;
}

/**
 * ==============================================================================
 * Compatibility functions
 * ==============================================================================
 */

/**
 * Verify if wptouch plugin function for mobile detection is on
 * @return bool
 */
function fa_is_wptouch_mobile(){
	
	$is_mobile = false;
	if( function_exists('bnc_wptouch_is_mobile') ){
		$is_mobile = bnc_wptouch_is_mobile();
	}
	return $is_mobile;
}

/**
 * Check if wptouch is set on exclusive. This means that, is plugin is installed,
 * it checks if option for not loading styles and scripts into header or footer is on.
 * @return bool
 */
function fa_is_wptouch_exclusive(){
	
	// wptouch has an option to disable scripts and stylesheets in header/footer
	$is_exclusive = false;
	
	if( function_exists('bnc_wptouch_is_exclusive') ){
		$is_exclusive = bnc_wptouch_is_exclusive();
	}
	return $is_exclusive;
}

/**
 * Verifies if the plugin is allowed to display slideshows.
 * @return bool
 */
function fa_load_in_mobile(){
	// by default, slideshows will be displayed into WP 
	$display = true;
	// check if it's a mobile WPtouch theme
	if( fa_is_wptouch_mobile() ){
		// get option to display in mobile versions
		$options = FA_plugin_options();		
		if( !$options['load_in_wptouch'] || fa_is_wptouch_exclusive() ){
			$display = false;
		}
	}	
	return $display;
}
?>