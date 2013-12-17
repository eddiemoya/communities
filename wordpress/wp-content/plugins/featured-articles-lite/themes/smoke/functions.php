<?php
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

/**
 * For detailed instructions about what hooks and filters you can use when developing themes for
 * FeaturedArticles, please visit: http://www.codeflavors.com/documentation/wp-actions-and-filters/
 */

/**
 * Theme details for theme Smoke
 */
function fa_smoke_theme_details( $defaults ){
	$defaults = array(
		'Author'=>'CodeFlavors',
		'AuthorURI'=>'http://www.codeflavors.com',
		'Copyright'=>'author',
		'Compatibility'=>'Featured Articles 2.4',
		'Fields'=>array(
			'bottom_nav'=>0,
			'sideways_nav'=>0,
			'desc_truncate_noimg'=>0,
			'thumbnail_display'=>0,
			'thumbnail_click'=>0
		),
		'Image'=>'background',
		'Message'=>'This theme has full image background. Check your image size to be same or close to slider size under Slide Content Options -> Image'
	);	
	return $defaults;
	
}
add_filter('fa-theme-details-smoke', 'fa_smoke_theme_details', 1);
?>