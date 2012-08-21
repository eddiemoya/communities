<?php 
/**
 * Custom Post Types 
 */

add_action('init', 'register_questions_type');
add_action('init', 'register_buying_guides_type');


/** 
 * @author Eddie Moya
 */
function register_questions_type() {
    $labels = array(
        'name'          => _x('Questions', 'post type general name'),
        'singular_name' => _x('Questions', 'post type singular name'),
        'add_new'       => _x('Add New', 'question'),
        'add_new_item'  => __('Add New Question'),
        'edit_item'     => __('Edit Question'),
        'new_item'      => __('New Question'),
        'all_items'     => __('All Questions'),
        'view_item'     => __('View Question'),
        'search_items'  => __('Search Questions'),
        'not_found'     => __('No questions found'),
        'not_found_in_trash' => __('No questions found in Trash'),
        'parent_item_colon' => '',
        'menu_name'     => 'Questions'
    );
    $rewrite = array(
        'slug'          => 'questions',
        'with_front'    => false,
        'feeds'         => true,
        'paged'         => true,
        'ep_mask'       => array()

    );
    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'publicly_queryable' => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'query_var'     => false,
        'rewrite'       => $rewrite,
        'capability_type' => 'post',
        'has_archive'   => true,
        'hierarchical'  => false,
        'menu_position' => 6,
        'supports'      => array('title', 'editor', 'author', 'comments', 'thumbnail'),
        'menu_icon'     => get_template_directory_uri() . '/assets/img/admin/questions_admin_icon.gif',
        'taxonomies'    => array('category', 'post_tag')
    );
    register_post_type('question', $args);
}

/** 
 * @author Jason Corradino
 */
function register_buying_guides_type() {
    $labels = array(
        'name' => _x('Buying Guides', 'post type general name'),
        'singular_name' => _x('Buying Guide', 'post type singular name'),
        'add_new' => _x('Add New', 'Guide'),
        'add_new_item' => __('Add New Guide'),
        'edit_item' => __('Edit Guide'),
        'new_item' => __('New Guide'),
        'all_items' => __('All Guides'),
        'view_item' => __('View Guides'),
        'search_items' => __('Search Guides'),
        'not_found' => __('No buying guides found'),
        'not_found_in_trash' => __('No buying guides found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Buying Guides'
    );
	$rewrite = array(
        'slug'          => 'guides',
        'with_front'    => false,
        'feeds'         => true,
        'paged'         => true,
        'ep_mask'       => array()
    );
    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'publicly_queryable' => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'query_var'     => false,
		'rewrite'         => $rewrite,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 7,
        'supports' => array('title', 'editor', 'author', 'comments', 'thumbnail'),
        //'menu_icon' => get_template_directory_uri() . '/assets/img/admin/questions_admin_icon.gif',
        'taxonomies' => array('category', 'post_tag')
    );
    register_post_type('guide', $args);
}

function new_excerpt_more($excerpt) {
    global $excerptLength;

    if(!isset($excerptLength) || $excerptLength <= 0) {
        return $excerpt;
    }

    if(strlen($excerpt) > $excerptLength) {
	    return $excerpt.'... <a class="moretag" href="'. get_permalink($post->ID) . '">Read more</a>';
    }

    return $excerpt;
}
add_filter('get_the_excerpt', 'new_excerpt_more');

function custom_excerpt_length( $excerpt ) {
    global $excerptLength, $post;

    if(!isset($excerptLength) || $excerptLength <= 0) {
        return $excerpt;
    }

    if(strlen($post->post_content) > $excerptLength) {
        $words = explode(' ', $post->post_content);

        $curTotal = 0;
        $i = 0;
        $newExcerpt = '';

        do {
            $newExcerpt .= $words[$i].' ';

            $curTotal += strlen($words[$i]);
            $i++;

        } while($curTotal <= $excerptLength);

        $newExcerpt = substr($newExcerpt, 0, -1);
    }

	return $newExcerpt;
}
add_filter( 'get_the_excerpt', 'custom_excerpt_length', 9);

// add_action( 'registered_post_type', 'redefine_posts' );
// function redefine_posts() {
//     global $wp_post_types;
//     $wp_post_types['page']->menu_position = 4;
//     echo "<pre>";print_r($wp_post_types['page']->menu_position);echo "</pre>";
// }
