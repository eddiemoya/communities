<?php 
/*************************************
 * Content, Class, and Query Filters *
 *************************************/

/**
 * Do not call this function directly, add it to the wp_nav_menu filter
 * Adds .first-menu-item and .last-menu-item to menu.
 * 
 * @param type $output
 * @return type 
 */
function add_menu_class_first_last($output) {
  $output = preg_replace('/class="menu-item/', 'class="first-menu-item menu-item', $output, 1);
  //$output = substr_replace($output, 'class="last-menu-item menu-item last-child', strripos($output, 'class="menu-item'), strlen('class="menu-item'));
  return $output;
}
add_filter('wp_nav_menu', 'add_menu_class_first_last');


/**
 * Do not call this function directly, add it to the body_class filter
 * 
 * Conditionally adds classes to the body class of a page for styling purposes.
 * These examples are from the Kmart Fashion and BirthdayClub themes.
 * 
 * @author Eddie Moya
 * 
 * @param type $classes
 * @return array 
 */
function filter_body_class($classes) {
    
    /**
     * Modify Styles pages on theme options. This example is from Kmart Fashion
     */
    $options = get_option('theme_options');
    $classes[] = $options['blog_brand_shop_style'];
    
    if (is_category())
        $classes[] = get_queried_object()->category_nicename;
    
    if (is_page())
        $classes[] = 'page-' .get_queried_object()->post_name;

     if ('section' == get_post_type())
        $classes[] = 'section';
    
    return $classes;
}
add_filter('body_class', 'filter_body_class');



/**
 * Do not call this function directly, add it to the request filter
 * 
 * Modifies the original WP_Query so that we dont have to continuously re-query 
 * with query_posts from within templates. 
 * 
 * Consider also the 'pre_get_posts', and 'parse_query' filters. As well as
 * other query filters explained in the WP_Query codex page.
 * 
 * @author Eddie Moya
 * 
 * @global WP_Query $wp_query
 * @param WP_Query $query_string
 * @return modified WP_Query object
 */
function custom_primary_query($query = '') {

    /**
     * This is being used for the results list widget.
     */
    if ($query->query_vars['is_widget']['widget_name']== 'results-list' && $_REQUEST['widget'] == 'results-list') {

        $category = (isset($_REQUEST['filter-sub-category'])) ? $_REQUEST['filter-sub-category'] : $_REQUEST['filter-category'];

        unset($query->query_vars['cat']);
        $query->set('cat', $category);
        $query->set('category__in', array($category));
    }
    //return $query;
}
add_action('pre_get_posts', 'custom_primary_query');

/******************************************
 * END  Content, Class, and Query Filters *
 ******************************************/
add_filter( 'widget_form_callback', 'widget_form_extend', 10, 2);
add_filter( 'dynamic_sidebar_params', 'dynamic_sidebar_params' );
add_filter( 'widget_update_callback', 'widget_update', 10, 2 );



function widget_form_extend( $instance, $widget ) {

    if(get_class($widget) == 'WP_Widget_Links'){

        if(!isset($instance['classes'])){
            $instance['classes'] = null;

            $row .= "<p>\n";
            $row .= "\t<label for='widget-{$widget->id_base}-{$widget->number}-sub-title'>Sub Title:</label>\n";
            $row .= "\t<input type='text' name='widget-{$widget->id_base}[{$widget->number}][sub-title]' id='widget-{$widget->id_base}-{$widget->number}-sub-title' class='widefat' value='{$instance['sub-title']}'/>\n";
            $row .= "</p>\n";

            echo $row;
        }
    }
    return $instance;
}





function dynamic_sidebar_params( $params ) {
    global $wp_registered_widgets;
    $widget_id  = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];

    $opts = $widget_opt[$widget_num];
    
    //Links Widget (built-in)
    if($widget_obj['name'] == 'Links'){
        $params[0]['after_title'] = 
        "\n\t<h4>".$opts['sub-title']
        ."</h4>".$params[0]['after_title']
        ."\n\t<section class='content-body clearfix'>";

        $params[0]['after_widget'] = 
            '</section>'
            .$params[0]['after_widget'];    
    }
    return $params;
}



function widget_update( $instance, $new_instance ) {
    $instance['sub-title'] = $new_instance['sub-title'];
    return $instance;
}








