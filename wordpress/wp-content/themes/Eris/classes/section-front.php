<?php 
/**
 *
 */
class Section_Front{

	/**
	 * 
	 */
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
		add_filter( 'rewrite_rules_array',	array(__CLASS__, 'section_rewrite_rules') );

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
	        'menu_position'	 	=> 3,
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
	    $section_rules = get_option('section_rewrite_rules', array());
	    $rules = (array)$section_rules + (array)$rules;
	    return  $rules;
	}

	/**
	 * 
	 */
	public function flush_custom_rules(){
		$rules = get_option( 'rewrite_rules' );
	    $section_rules = get_option('section_rewrite_rules', array());

	    //@todo: use in_array or array_search, or some more performant array function
	    foreach((array)$section_rules as $regex => $querystring){
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

		if(isset($_POST['post_type'])){
		    if('section' != $_POST['post_type'])
		        return;

		    if ( !current_user_can( 'edit_post', $post_id ) )
		        return;

		    $categories = $_POST['post_category'];

		    self::rewrite_all_the_terms($_POST['post_category']);
		}
	}
 
	/**
	 *
	 */
	function rewrite_all_the_terms($new_categories){
		global $wp_rewrite;
		$categorized_sections = self::get_terms_by_post_type('category', 'section');

		$rules = array();
	    foreach((array)$categorized_sections as $cat){
	
	    	//echo "<pre>";print_r($cat);echo "</pre>";
	    	$rules[$cat->taxonomy . '/' . $cat->slug .'/?$'] = 'index.php?posts_per_page=1&post_type='.$_POST['post_type'].'&category_name='.$cat->slug;

	        // $rules[$cat_id] =array(
	        // $cat->taxonomy . '/' . $cat->slug .'/?$/page/?([0-9]{1,})/?$' => 'index.php?posts_per_page=1&post_type='.$_POST['post_type'].'&category_name='.$cat->slug,
	        // $cat->taxonomy . '/' . $cat->slug .'/?$' => 'index.php?posts_per_page=1&post_type='.$_POST['post_type'].'&category_name='.$cat->slug,

	    }
	    //echo "<pre>";print_r($rules);echo "</pre>";
	    // unset($rules[0]);
	    update_option('section_rewrite_rules', $rules);

	   	$wp_rewrite->flush_rules();
	}

	/**
	 *  Function to get terms only if they have posts by post type.
	 *
	 *  @param $taxonomy (string) taxonomy name eg: 'post_tag','category'(default),'custom taxonomy'
	 *  @param $post_type (string) post type name eg: 'post'(default),'page','custom post type'
	 *
	 *  Usage:
	 *  get_terms_by_post_type('post_tag','custom_post_type_name');
	 **/
	function get_terms_by_post_type($taxonomy = 'category',$post_type = 'post'){
	  //get a list of all post of your type
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $post_type
		);
		$terms= array();
		$posts = get_posts($args);
			foreach($posts as $p){
			//get all terms of your taxonomy for each type
			$ts = wp_get_object_terms($p->ID,$taxonomy); 
				foreach ( $ts as $t ) {
					if (!in_array($t,$terms)){ //only add this term if its not there yet
					//$t->cat_name = ''
					$terms[] = $t;
					}
				}
			}


		wp_reset_postdata();

		return $terms; 
	}

}

Section_Front::init();