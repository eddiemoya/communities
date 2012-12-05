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
 * Title Navigation theme details
 */
function fa_title_navigation_theme_details( $defaults ){
	$defaults = array(
		'Author'=>'CodeFlavors',
		'AuthorURI'=>'http://www.codeflavors.com',
		'Copyright'=>'author',
		'Compatibility'=>'Featured Articles 2.4',
		'Fields'=>array(
			'bottom_nav'=>0,
			'sideways_nav'=>0
		),
		'Image'=>'image'
	);	
	return $defaults;
	
}
add_filter('fa-theme-details-title_navigation', 'fa_title_navigation_theme_details', 1);


?>