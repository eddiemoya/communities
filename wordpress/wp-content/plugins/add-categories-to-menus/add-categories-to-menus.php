<?php
/*
Plugin Name: Add Categories to Menu
Plugin URI: http://www.alistercameron.com/2009/12/13/wordpress-plugin-add-categories-menu/
Description: Adds a list of blog categories as a submenu under the menu item for the static posts page you have selected in Admin > Settings > Reading.
Version: 0.2
Author: Alister Cameron // Blogologist
Author URI: http://www.alistercameron.com
*/

if ( !function_exists('add_blog_cats_to_menu') ) {
	function add_blog_cats_to_menu($str) {		
		$cats = wp_list_categories('title_li=&echo=0');
		$title = get_the_title(get_option('page_for_posts'));
		return str_replace("title=\"$title\">$title</a>", "title=\"$title\">$title</a><ul>$cats</ul>", $str);
	}
	add_filter('wp_list_pages', 'add_blog_cats_to_menu', 1);
}