<?php 
class Section_Front{

	var $post_types = array('section', 'pages');
	/**
	 * 
	 */
	public function init(){
		self::add_actions();
	}

	/**
	 *
	 */
	public function add_actions(){

		add_action( 'init', 					array(__CLASS__, 'register_sections_type') );
		add_action( 'save_post', 				array(__CLASS__, 'save_section') );
		add_action( 'wp_loaded',				array(__CLASS__, 'flush_custom_rules' ) );
		add_filter( 'category_rewrite_rules',	array(__CLASS__, 'section_rewrite_rules') );

	}

	/**
	 *
	 */
	public function register_sections_type() {
	    $labels = array(
	        'name' 				=> _x('Section', 'post type general name'),
	        'singular_name' 	=> _x('Section', 'post type singular name'),
	        'add_new' 			=> _x('Add New', 'section'),
	        'add_new_item' 		=> __('Add New Section'),
	        'edit_item' 		=> __('Edit Section'),
	        'new_item' 			=> __('New Section'),
	        'all_items' 		=> __('All Section'),
	        'view_item' 		=> __('View Section'),
	        'search_items' 		=> __('Search Sections'),
	        'not_found' 		=> __('No sections found'),
	        'not_found_in_trash' => __('No sections found in Trash'),
	        'parent_item_colon' => '',
	        'menu_name' 		=> 'Sections'
	    );
	    $args = array(
	        'labels' 			=> $labels,
	        'public' 			=> true,
	        'publicly_queryable' => true,
	        'exclude_from_search' => true,
	        'show_ui' 			=> true,
	        'show_in_menu' 		=> true,
	        'show_in_nav_menus' => false,
	        'show_in_admin_bar' => true,
	        'query_var' 		=> false,
	        'rewrite' 			=> false,
	        'capability_type' 	=> 'page',
	        'has_archive' 		=> false,
	        'hierarchical' 		=> false,
	        'menu_position'	 	=> 9,
	        'supports' 			=> array('title', 'page-attributes'),
	        'taxonomies' 		=> array('category'),
	        'rewrite' 			=> false,
	    );
	    register_post_type('section', $args);
	}

	/*
	 *
	 */
	public function section_rewrite_rules( $rules ) {
	    $newrules = array();
	    $rule_sets = get_option('section_rewrite_rules', array());

	     foreach($rule_sets as $set){
	         $newrules = $newrules + $set;
	     }
	    return $newrules + $rules;
	}


	public function flush_custom_rules(){
		$newrules = array();
		$rules = get_option( 'rewrite_rules' );
	    $rule_sets = get_option('section_rewrite_rules', array());

	    foreach($rule_sets as $set){
	         $newrules = $newrules + $set;
	    }

	    foreach($newrules as $regex => $querystring){
			if ( ! isset( $rules[$regex] ) ) {
				global $wp_rewrite;
			   	$wp_rewrite->flush_rules();
			}
		}
	}

	/**
	 * 
	 */
	public function save_section( $post_id ){
		global $wp_rewrite;

	    if('section' != $_POST['post_type'])
	        return;

	    if ( !current_user_can( 'edit_post', $post_id ) )
	        return;

	    //delete_option('section_rewrite_rules');
	    $rules = get_option('section_rewrite_rules', array());
	    $categories = $_POST['post_category'];

	    $cats = array();
	    foreach($categories as $i => $cat_id){
	        $cat = get_category($cat_id);

	        $rules[$cat_id] =array(
	        	$cat->taxonomy . '/' . $cat->slug .'/?$/page/?([0-9]{1,})/?$' => 'index.php?posts_per_page=1&post_type='.$_POST['post_type'].'&category_name='.$cat->slug,
	            $cat->taxonomy . '/' . $cat->slug .'/?$' => 'index.php?posts_per_page=1&post_type='.$_POST['post_type'].'&category_name='.$cat->slug,
	        );
	    }
	    unset($rules[0]);
	    update_option('section_rewrite_rules', $rules);

	   	//$wp_rewrite->flush_rules();
	}

}

Section_Front::init();