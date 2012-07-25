<?php 
/**
 * Register Widgetized Areas 
 */
if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Sidebar',
        'description' => 'Sidebar',
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
    ));
    register_sidebar(array(
        'name' => 'Post Footer',
        'description' => 'Footer of every post',
        'before_widget' => '<span class="widget %2$s" id="%1$s">',
        'after_widget' => '</span>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>'
    ));
    
}