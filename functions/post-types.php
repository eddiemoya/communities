<?php 
/**
 * Custom Post Types 
 */

add_action('init', 'register_questions_type');
add_action('init', 'register_buying_guides_type');
//add_action('init', 'register_tips_ideas_type');

/**
 * @author Eddie Moya
 */
function register_questions_type() {
    $labels = array(
        'name'          => _x('Questions', 'post type general name'),
        'singular_name' => _x('Question', 'post type singular name'),
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
        'rewrite'         => $rewrite,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'supports'      => array('title', 'editor', 'author', 'comments', 'thumbnail', 'excerpt'),
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
        'view_item' => __('View Guide'),
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
        'supports' => array('title', 'editor', 'author', 'comments', 'thumbnail', 'excerpt'),
        //'menu_icon' => get_template_directory_uri() . '/assets/img/admin/questions_admin_icon.gif',
        'taxonomies' => array('category', 'post_tag')
    );
    register_post_type('guide', $args);
}

/**
 * @author Jason Corradino

function register_tips_ideas_type() {
    $labels = array(
        'name' => _x('Tips & Ideas', 'post type general name'),
        'singular_name' => _x('Tips & Ideas', 'post type singular name'),
        'add_new' => _x('Add New', 'Tip'),
        'add_new_item' => __('Add New Tip'),
        'edit_item' => __('Edit Tip'),
        'new_item' => __('New Tip'),
        'all_items' => __('All Tips'),
        'view_item' => __('View Tip'),
        'search_items' => __('Search Tips'),
        'not_found' => __('No tips or ideas found'),
        'not_found_in_trash' => __('No tips or ideas found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Tips & Ideas'
    );
	$rewrite = array(
        'slug'          => 'tips',
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
        'supports' => array('title', 'editor', 'author', 'comments', 'thumbnail', 'excerpt'),
        //'menu_icon' => get_template_directory_uri() . '/assets/img/admin/questions_admin_icon.gif',
        'taxonomies' => array('category', 'post_tag')
    );
    register_post_type('tips', $args);
}
 */
function strip_oembed_from_excerpt($excerpt){
	$oembedProviders = array(
		'#http://(www\.)?youtube.com/watch.*#i',
		'#http://youtu.be/*#i',
		'#http://blip.tv/*#i',
		'#http://(www\.)?vimeo\.com/.*#i',
		'#http://(www\.)?dailymotion\.com/.*#i',
		'#http://(www\.)?flickr\.com/.*#i',
		'#http://(.+\.)?smugmug\.com/.*#i',
		'#http://(www\.)?hulu\.com/watch/.*#i',
		'#http://(www\.)?viddler\.com/.*#i',
		'#http://qik.com/*#i',
		'#http://revision3.com/*#i',
		'#http://i*.photobucket.com/albums/*#i',
		'#http://gi*.photobucket.com/groups/*#i',
		'#http://(www\.)?scribd\.com/.*#i',
		'#http://wordpress.tv/*#i',
		'#http://(.+\.)?polldaddy\.com/.*#i',
		'#http://(www\.)?funnyordie\.com/videos/.*#i'
	);
	
	foreach ($oembedProviders as $provider) {
		if(preg_match_all($provider, $excerpt, $matches)) {
			foreach($matches[0] as $match) {
				$excerpt = str_replace($match, "", $excerpt);
			}
		}
	}
	
	return $excerpt;
}

function see_more_excerpt($excerpt) {
    global $excerptLength;

    if(!isset($excerpt) || $excerpt == '' || strlen($excerpt) <= $excerptLength) {
        return $excerpt;
    }

    return $excerpt.'... <a class="moretag" href="'. get_permalink($post->ID) . '">See More</a>';
}
//add_filter('get_the_excerpt', 'see_more_excerpt');

function custom_excerpt_length($excerpt) {
    global $excerptLength, $post;

    if(!isset($excerptLength) || $excerptLength <= 0 || (isset($excerpt) && $excerpt != '' && strlen($excerpt) > 0)) {
        return $excerpt . '... <a class="moretag" href="'. get_permalink($post->ID) . '">See More</a>';
    }

    $excerpt = trim(strip_oembed_from_excerpt(strip_tags($post->post_content)));

    if(strlen($post->post_content) > $excerptLength) {
        return substr($excerpt, 0, strpos($excerpt, " ", $excerptLength)) . '... <a class="moretag" href="'. get_permalink($post->ID) . '">See More</a>';
    }

	return $excerpt;
}
add_filter('get_the_excerpt', 'custom_excerpt_length', 9);
add_filter('excerpt_length', 'custom_excerpt_length', 9 );
add_filter('wpdz_dropzones', 'custom_excerpt_length', 9);

// add_action( 'registered_post_type', 'redefine_posts' );
// function redefine_posts() {
//     global $wp_post_types;
//     $wp_post_types['page']->menu_position = 4;
//     echo "<pre>";print_r($wp_post_types['page']->menu_position);echo "</pre>";
// }


//add_action( 'init', 'default_post_type', 1 );
function default_post_type() {

    register_post_type( 'post', array(
        'labels' => array(
            'name_admin_bar' => _x( 'Post', 'add new on admin bar' ),
        ),
        'public'  => true,
        '_builtin' => false, 
        '_edit_link' => 'post.php?post=%d', 
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'hierarchical' => false,
        'rewrite' => array( 'slug' => 'post' ),
        'query_var' => false,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'post-formats' ),
    ) );
}
