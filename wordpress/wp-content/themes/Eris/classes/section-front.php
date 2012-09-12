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
		//add_action( 'save_post', 				array(__CLASS__, 'save_section') );
		//add_action( 'wp_loaded',				array(__CLASS__, 'flush_custom_rules' ) );
		add_filter( 'rewrite_rules_array',		array(__CLASS__, 'section_rewrite_rules') );

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
	   // $section_rules = get_option('section_rewrite_rules', array());
		$terms = self::get_terms_by_post_type('category', 'section');
		$posts = array();
		$new_rules = array();

		foreach($terms as &$term){
			$posts = get_posts(array(
				'posts_per_page' => -1,
				'post_type' => array('section'),
				'tax_query' => array(
					array(
						'taxonomy' => 'category',
						'terms' => $term->term_id,
						'field' => 'id',
			))));


			foreach($posts as $post){
				$post->term = $term;

				$post->meta['rewrite_tax_archive'] = get_post_meta($post->ID, 'widgetpress_post_type_none', true);
				$post->meta['rewrite_tax_guide'] = get_post_meta($post->ID, 'widgetpress_post_type_guide', true);
				$post->meta['rewrite_tax_question'] = get_post_meta($post->ID, 'widgetpress_post_type_question', true);
				$post->meta['rewrite_tax_post'] = get_post_meta($post->ID, 'widgetpress_post_type_post', true);

				$post_types = array();
				if( !empty($post->meta['rewrite_tax_guide']) || !empty($post->meta['rewrite_tax_question']) || !empty($post->meta['rewrite_tax_post']) ){
					$post_types[] = (!empty($post->meta['rewrite_tax_guide'])) ? 'guide' :  '';
					$post_types[] = (!empty($post->meta['rewrite_tax_question'])) ? 'question' :  '';
					$post_types[] = (!empty($post->meta['rewrite_tax_post'])) ? 'post' :  '';

					$endpoint_pattern = '(' . implode('|', array_filter($post_types)) . ')';
					$new_rules["{$term->taxonomy}/{$term->slug}/{$endpoint_pattern}/?$"] = 'index.php?posts_per_page=1&posts__in='.$post->ID.'&post_type='.$post->post_type.'&category_name='.$term->slug;


				}
				if(!empty($post->meta['rewrite_tax_archive'])){
					$new_rules["{$term->taxonomy}/{$term->slug}/?$"] = 'index.php?posts_per_page=1&posts__in='.$post->ID.'&post_type='.$post->post_type.'&category_name='.$term->slug;
				}


				$tposts[] = $post;
			}


			
		}
		
			//echo "<pre>";print_r(array($new_rules, $tposts,$terms));echo "</pre>";
		


	    //$rules = $new_rules + $rules;
	    return  $new_rules+$rules;
	}

	/**
	 * 
	 */
	public function flush_custom_rules(){
		$rules = get_option( 'rewrite_rules' );
	    $section_rules = get_option('section_rewrite_rules', array());
	    $flush = false;
	    //@todo: use in_array or array_search, or some more performant array function
	    foreach((array)$section_rules as $regex => $querystring){
			if ( ! isset( $rules[$regex] ) ) {
				$flush = true;
			}
		}
		if($flush){
			global $wp_rewrite;
		   	$wp_rewrite->flush_rules();

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

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return;

	   		if ( defined('DOING_AJAX') && DOING_AJAX ) 
	   			return;

		    if ( !current_user_can( 'edit_post', $post_id ) )
		        return;

		    $categories = $_POST['post_category'];
		    // $types = array(
		    	
		    // 	// 'question' => !empty($_POST['widgetpress_post_type_question']),
		    // 	// 'post' => !empty($_POST['widgetpress_post_type_post']),
		    // 	// 'guide' => !empty($_POST['widgetpress_post_type_guide']),
		    // 	'tax-archive' => !empty($_POST['widgetpress_post_type_none'])
		    // );
		    $rewritten_terms = self::build_rules(wp_list_pluck(self::get_terms_by_post_type('category', 'section'), 'term_id'));

		    $saved_rules = get_option('section_rewrite_rules', array());
		    $new_rules = self::build_rules(array_filter($categories));

		    $rules = array_merge($saved_rules, $new_rules);
		 //   // $rules 
			// //update_option('section_rewrite_rules', $new_rules);
			// echo "<pre>";print_r($categories);echo "</pre>";
		 //    echo "<pre>";print_r($rewritten_terms);echo "</pre>";
		 //    echo "<pre>";print_r($saved_rules);echo "</pre>";
		 //    echo "<pre>";print_r($new_rules);echo "</pre>";
		 //    echo "<pre>";print_r($rules);echo "</pre>";

		    //self::rewrite_all_the_terms($categories, array_filter($types));
		}

		$wp_rewrite->flush_rules();
		//return $post_id;
	}
 
 	function build_rules($categories){

 		$cats = array();
 		foreach($categories as $cat){
 			$cat = get_term($cat, 'category');
 			$cats[] = $cat;
 			$rules["{$cat->taxonomy}/{$cat->slug}/?$"] = 'index.php?posts_per_page=1&posts__in='.$_POST['post_ID'].'&post_type='.$_POST['post_type'].'&category_name='.$cat->slug;
 		}
 		return $rules;
 	}
	/**
	 *
	 */
	function rewrite_all_the_terms($new_categories, $types){
		global $wp_rewrite;
		$categorized_sections = self::get_terms_by_post_type('category', 'section');
		$rules = array();


		foreach((array)$categorized_sections as $cat){
		    foreach((array)$types as $type => $value){
			$type = ($type == 'tax-archive') ? '' : $type;
			$type_endpoint = (!empty($type)) ? "/{$type}" : '';

		
		    	//echo "<pre>";print_r($cat);echo "</pre>";
		    	

		    	//$rules[$cat->taxonomy . '/' . $cat->slug .$type_endpoint.'/?$'] = 'index.php?posts_per_page=1&posts__in='.$_POST['post_ID'].'&post_type='.$_POST['post_type'].'&category_name='.$cat->slug;

		        // $rules[$cat_id] =array(
		        // $cat->taxonomy . '/' . $cat->slug .'/?$/page/?([0-9]{1,})/?$' => 'index.php?posts_per_page=1&post_type='.$_POST['post_type'].'&category_name='.$cat->slug,
		        // $cat->taxonomy . '/' . $cat->slug .'/?$' => 'index.php?posts_per_page=1&post_type='.$_POST['post_type'].'&category_name='.$cat->slug,

		    }
		}
	    // echo "<pre>";print_r($rules);echo "</pre>";
	    // exit();
	    // unset($rules[0]);

	    // $section_rules = get_option('section_rewrite_rules', array());
	    // $rules = array_merge($section_rules, $rules);

	    // update_option('section_rewrite_rules', $rules);

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
			foreach($posts as &$p){
			//get all terms of your taxonomy for each type
			$ts = wp_get_object_terms($p->ID,$taxonomy); 
				foreach ( $ts as $t ) {
					if (!in_array($t,$terms)){ //only add this term if its not there yet
					//$t->cat_name = ''
					$terms[] = $t;
					}
				}
			}

		return $terms; 
	}

}

Section_Front::init();