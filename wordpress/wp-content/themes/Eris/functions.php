<?php

//include('/Library/WebServer/Documents/communities/public_html/wp-content/plugins/posts-widget/widgets/post-widgets.php');

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
// add_theme_support('custom-background');
// add_theme_support('custom-header'); // Look carefully at all the possible aruguments - http://codex.wordpress.org/Custom_Headers

/**
 * Allows us to apply styles to the TinyMCE editor in the admin - to make it
 * look the way the content will look on the front end as they type. 
 */
add_editor_style('assets/css/editor-style.css');

//Classes
get_template_part('classes/theme-options');
//get_template_part('classes/section-front');
get_template_part('classes/communities-walker-nav-menu');
get_template_part('classes/communities-nav-menu-field');

//Function
get_template_part('functions/general');
get_template_part('functions/ajax-callbacks');
get_template_part('functions/enqueued-assets');
get_template_part('functions/filters-hooks');
get_template_part('functions/menus');
get_template_part('functions/post-types');
get_template_part('functions/comment-types');
get_template_part('functions/sidebar-declarations');
get_template_part('functions/template-tags');
get_template_part('functions/rewrite-rules');
get_template_part('functions/userphoto');
get_template_part('functions/cookies_to_formdata');
get_template_part('functions/custom_taxonomy');
