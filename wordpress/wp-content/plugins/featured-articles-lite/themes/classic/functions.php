<?php
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

/**
 * For detailed instructions about what hooks and filters you can use when developing themes for
 * FeaturedArticles, please visit: http://www.codeflavors.com/documentation/wp-actions-and-filters/
 * 
 * The below filters and hooks are specific for FeaturedArticles. This is just a simple example that
 * creates a new option for animating the image container for theme Classic.
 */


/**
 * We want to create a new JavaScript parameter that gets passed to the slider script.
 * This parameter will enable/disable thumbnail image animation.
 * @param array $params - the default parameters from Featured Articles
 */
function fa_classic_extra_params( $params ){
	// we set the param to be false by default. Notice the key _fa_lite_js. Under this key are stored all parameters for javascript.
	$params['_fa_lite_js']['classic_thumb_animate'] = false;
	return $params;
}
add_filter('fa-extend-options', 'fa_classic_extra_params', 10, 1);

/**
 * Next, we need to tell PHP that this new field is an optional field meaning it's only for theme Classic
 * Let's extend the original optional fields with your own field.
 * Your field should be optional because only your theme will be using it. So you should allow other themes to disable it.
 * @param array $fields - a list of optional fields the plugin keeps track of
 */
function fa_classic_optional_fields( $fields ){
	$fields['classic_thumb_animate'] = 0; // 0 is disabled, 1 is enabled
	return $fields;
}
add_filter('fa-extend-optional-fields', 'fa_classic_optional_fields', 10, 1);

/**
 * All the above being done, we will output the field. 
 * For other themes to be able to disable this field since they won't use it, we'll set some classes on both label and field.
 * To flag it as optional we need to add class FA_optional to both label and field.
 * To disable the field (your theme will know about it and enable it) we set disabled attribute on field and class disabled on label
 * @param array $options - options from plugin to display the field as checked or not
 */
function fa_classic_extra_fields( $options ){
	
	$field_name = 'classic_thumb_animate';	
	$checked = $options['_fa_lite_js'][$field_name] ? 'checked="checked"' : '';
	
	printf('<input type="checkbox" class="FA_optional" disabled="disabled" name="%1$s" id="%1$s" value="1" %2$s /> ', $field_name, $checked);	
	printf('<label class="FA_optional disabled" for="%1$s">%2$s</label> <br />', $field_name, 'Enable thumbnail animation (for Classic theme only)');
		
}
add_action('fa_extra_animation_fields', 'fa_classic_extra_fields', 10, 1);

/**
 * Some details about the theme. 
 * Also notice key Fields. It stores the above field and flags it as enabled for this theme. All other themes will display this field disabled.
 */
function fa_classic_theme_details( $defaults ){
	$defaults = array(
		'Author'=>'CodeFlavors',
		'AuthorURI'=>'http://www.codeflavors.com',
		'Copyright'=>'author',
		'Compatibility'=>'Featured Articles 2.4',
		'Image'=>'image',
		'Fields'=>array(
			'classic_thumb_animate' => 1
		),
		'Message'=>'Slide background color is disabled for this theme.'		
	);	
	return $defaults;
	
}
add_filter('fa-theme-details-classic', 'fa_classic_theme_details', 1);
?>