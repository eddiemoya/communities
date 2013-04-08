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
		add_filter( 'rewrite_rules_array',		array(__CLASS__, 'section_rewrite_rules') );
		add_filter('query_vars', 				array(__CLASS__, 'add_var'));
		//add_action('pre_get_posts', 		array(__CLASS__, 'custom_primary_query'));
	}


	public function add_var($qvars) {
		//$qvars[] = 'meta_key';
		$qvars[] = 'old_post_type';
		$qvars[] = 'old_category';
		$qvars[] = 'old_paged';
		$qvars[] = 'old_format';


		return $qvars;
	}

//add_filter('request', 'custom_primary_query');
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
// function custom_primary_query($query = '') {

//     /**
//      * This is being used for the results list widget.
//      */
//     // if(isset($query->query_vars['meta_key'])){
//     //     if ( strstr( $query->query_vars['meta_key'], ' ') || strstr( $query->query_vars['meta_key'], ',') ) {

//     //         $meta_in = explode(' ', $query->query_vars['meta_key'] );
            

//     //         $meta_query = array('relation'=>'AND');

//     //         if(strstr( $query->query_vars['meta_key'], ' ') ){
// 	   //          foreach($meta_in as $meta_key){
// 	   //          	$meta_query[] = array(
// 	   //          		'key' => $meta_key,
// 	   //          		'value' => 'on',
// 	   //          		'compare' => 'IN'
// 	   //          	);
// 	   //          }
//     //     	}
//     //         if(strstr( $query->query_vars['meta_key'], ',') ){
// 	   //          $meta_not = explode(',', $query->query_vars['meta_key'] );
// 	   //          foreach($meta_not as $meta_key){
// 	   //          	$meta_query[] = array(
// 	   //          		'key' => $meta_key,
// 	   //          		'compare' => 'NOT EXISTS'
// 	   //          	);
// 	   //          }
//     //     	}

//     //         unset($query->query_vars['meta_key']);
//     //         unset($query->query['meta_key']);
//     //         $query->set('meta_query', $meta_query);
//             //$query->query['meta_query'] = $meta_query;
//             //$query->meta_query = 'TEST';
//             //$query->meta_query->parse_query_vars($meta_query);

//     //     }
//     // }
//     return $query;
// }

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
	        'query_var'			=> false,
	        'rewrite' 			=> 'section',
	        'show_in_admin_bar' => true,
	        'capability_type' 	=> 'page',
	        'has_archive' 		=> false,
	        'hierarchical' 		=> false,
	        'menu_position'	 	=> 3,
	        'supports' 			=> array('title', 'page-attributes'),
	        'taxonomies' 		=> array('category'),
	    );
	    register_post_type('section', $args);
	}

	/*
	 *
	 */
	public function section_rewrite_rules( $rules ) {

		add_rewrite_tag('%meta_key%','([^&]+)');
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


				$post->meta['rewrite_format_archive'] = get_post_meta($post->ID, 'widgetpress_format_archive', true);
				$post->meta['rewrite_category_format'] = get_post_meta($post->ID, 'widgetpress_category_format', true);

				$post->meta['rewrite_guide'] = get_post_meta($post->ID, 'widgetpress_guide_archive', true);
				$post->meta['rewrite_question'] = get_post_meta($post->ID, 'widgetpress_question_archive', true);
				$post->meta['rewrite_post'] = get_post_meta($post->ID, 'widgetpress_post_archive', true);


				$post_types = array();

				//Taxonomy/Post Intersection
				if( !empty($post->meta['rewrite_tax_guide']) || !empty($post->meta['rewrite_tax_question']) || !empty($post->meta['rewrite_tax_post']) ){
					$post_types[] = (!empty($post->meta['rewrite_tax_guide'])) 		? 'guide' 		:  '';
					$post_types[] = (!empty($post->meta['rewrite_tax_question'])) 	? 'question' 	:  '';
					$post_types[] = (!empty($post->meta['rewrite_tax_post'])) 		? 'post' 		:  '';


					$endpoint_pattern = implode('|', array_filter($post_types));
					$new_rules["{$term->taxonomy}/{$term->slug}/({$endpoint_pattern})/?$"] 
					= 'index.php?post_type=section&p='.$post->ID.'&category_name='.$term->slug.'&old_category='.$term->slug.'&old_post_type=$matches[1]';

					$new_rules["{$term->taxonomy}/{$term->slug}/({$endpoint_pattern})/page/?([0-9]{1,})/?$"] 
					= 'index.php?post_type=section&p='.$post->ID.'&category_name='.$term->slug.'&old_category='.$term->slug.'&old_post_type=$matches[1]&old_paged=$matches[2]';
				}

				//Taxonomy/Format Intersection
				if( !empty($post->meta['rewrite_category_format']) ){
					$formats[] = (!empty($post->meta['rewrite_category_format'])) 		? 'video' 		:  '';


					$endpoint_pattern = implode('|', array_filter($formats));
					$new_rules["{$term->taxonomy}/{$term->slug}/({$endpoint_pattern})/?$"] 
					= 'index.php?post_type=section&p='.$post->ID.'&category_name='.$term->slug.'&old_category='.$term->slug.'&old_format=$matches[1]';

					$new_rules["{$term->taxonomy}/{$term->slug}/({$endpoint_pattern})/page/?([0-9]{1,})/?$"] 
					= 'index.php?post_type=section&p='.$post->ID.'&category_name='.$term->slug.'&old_category='.$term->slug.'&old_format=$matches[1]&old_paged=$matches[2]';
				}

				//Post Type Archive
				if( !empty($post->meta['rewrite_guide']) || !empty($post->meta['rewrite_question']) || !empty($post->meta['rewrite_post'])){
					$post_types[] = (!empty($post->meta['rewrite_guide'])) 		? 'guide' 		:  '';
					$post_types[] = (!empty($post->meta['rewrite_question'])) 	? 'question' 	:  '';
					$post_types[] = (!empty($post->meta['rewrite_post'])) 		? 'post' 		:  '';

					$endpoint_pattern = implode('|', array_filter($post_types));
					$new_rules["({$endpoint_pattern})s?/?$"] 
					= 'index.php?post_type=section&p='.$post->ID.'&old_post_type=$matches[1]&category_name='.$term->slug;

					$new_rules["({$endpoint_pattern})s?/page/?([0-9]{1,})/?$"] 
					= 'index.php?post_type=section&p='.$post->ID.'&old_post_type=$matches[1]&category_name='.$term->slug.'&old_paged=$matches[2]';
				}

				//Format Archive
				if( !empty($post->meta['rewrite_format_archive'])){
					$formats[] = (!empty($post->meta['rewrite_format_archive'])) 	? 'video' 	:  '';

					$endpoint_pattern = implode('|', array_filter($formats));
					$new_rules["({$endpoint_pattern})s?/?$"] 
					= 'index.php?post_type=section&p='.$post->ID.'&old_format=$matches[1]&category_name='.$term->slug;

					$new_rules["({$endpoint_pattern})s?/page/?([0-9]{1,})/?$"] 
					= 'index.php?post_type=section&p='.$post->ID.'&old_format=$matches[1]&category_name='.$term->slug.'&old_paged=$matches[2]';

				}


				// //Category/Format filters
				// if( !empty($post->meta['rewrite_tax_format']) ){
				// 	$taxonomies[] = (!empty($post->meta['rewrite_tax_format'])) 		? 'video' 	:  '';

				// 	$endpoint_pattern = implode('|', array_filter($post_types));

				// 	$new_rules["{$term->taxonomy}/({$term->slug})/?$"] 
				// 	= 'index.php?post_type=section&p='.$post->ID.'&category_name='.$term->slug.'&old_category='.$term->slug;

				// 	$new_rules["{$term->taxonomy}/({$term->slug})/page/?([0-9]{1,})/?$"] 
				// 	= 'index.php?post_type=section&p='.$post->ID.'&category_name='.$term->slug.'&old_category='.$term->slug.'&old_paged=$matches[1]';
				// }

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
		    // // );
		    // $rewritten_terms = self::build_rules(wp_list_pluck(self::get_terms_by_post_type('category', 'section'), 'term_id'));

		    // $saved_rules = get_option('section_rewrite_rules', array());
		    // $new_rules = self::build_rules(array_filter($categories));

		    // $rules = array_merge($saved_rules, $new_rules);
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