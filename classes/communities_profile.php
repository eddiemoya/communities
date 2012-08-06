<?php
class User_Profile {
	
	const ANSWER_TYPE = 'answer';
	const QUESTION_TYPE = 'question';
	const BLOG_POST_TYPE = 'blog_post';
	const BUYING_GUIDE_TYPE = 'buying_guide';
	
	protected $user_id;
	
	public $posts = null;
	
	public $comments = null;
	
	public $experts;
	
	private $posts_per_page = 5;
	
	public $num_pages;
	
	private $offset = 0;
	
	private $page = 1;
	
	private $limit;
	
	public $total_results;
	
	public $next_page;
	
	
	public function __construct($user_id = false) {
		
		 if(! $user_id) die('You must supply a user ID to constructor of User_Profile.'); 
		 
		 $this->user_id = $user_id;
		
		 $this->set_experts($this->get_experts());
	}
	
	public function page($page_num) {
		
		$this->page = $page_num;
		
		return $this;
	}
	
	
	private function paginate() {
		
		//Calculate the offset
		$row = 0;
		
		for($i=1; $i<$this->page; $i++) {
			
			$row = $row + $this->posts_per_page;
		}
		
		
		$this->num_pages = round(($this->total_results / $this->posts_per_page), 0, PHP_ROUND_HALF_UP);
		$this->offset = $row;
		
		$this->next_page = ($this->page != $this->num_pages) ? ($this->page + 1) : null;
		
		//$this->limit = ' LIMIT ' . $row .','. $this->posts_per_page;
		
		return $this;
	}
	
	public function posts_per_page($num) {
		
		$this->posts_per_page = $num;
		
		return $this;
	}
	
	public function get_user_posts_by_type($post_type = 'post' ) {
		
		$args = 	array('author'		=> $this->user_id,
						'post_status'	=> 'publish',
						'post_type'		=> $post_type,
						'order'			=> 'DESC',
						'orderby'		=> 'date'
						);
						
		//Sets total_results			
		$this->get_post_count($args);
		
		//Sets num_pages and offset
		$this->paginate();
		
		$args['posts_per_page'] = $this->posts_per_page;
		$args['paged'] = $this->page;		
						
		$this->posts = get_posts($args);
		
		//Get and add categories property to each post
		$this->set_post_categories();
		
		return $this;
	}
	
	private function set_post_categories() {
		
		if(count($this->posts)){
			
			foreach($this->posts as $key=>$post) {
				
				$this->posts[$key]->categories = $this->get_post_categories($post->ID);
			}
		}
	}
	
	private function get_post_count($args) {
		
		$this->total_results = count(get_posts($args));
	}
	
	
	public function get_user_comments_by_type($type = '') {
		
	     $args = array(	'type'				=> $type,
					 	'status'			=> 'approve',
						'user_id'			=> $this->user_id,
						'order'				=> 'DESC',
						'orderby'			=> 'comment_date'
						);

			//Sets total
			$this->get_total_comments($args);
			
			$this->paginate();
			
			$args['number'] = $this->posts_per_page;
			$args['offset'] = $this->offset;
						
			$this->comments = get_comments($args);
			
			$this->get_comment_post();
			
			return $this;
			
	}
	
	private function get_comment_post() {
		
		if(count($this->comments)) {
			
			foreach($this->comments as $key=>$comment) {
				
				$post = get_post($comment->comment_post_ID);
				
				$post->categories = $this->get_post_categories($comment->comment_post_ID);
				
				$this->comments[$key]->post = $post;
				
				unset($post);
			}
		}
		
	}
	

	private function get_total_comments($args) {
		
		$args['count'] = 1;
		
		$count = get_comments($args);
		
		$this->total_results = $count;
		
	}
	
	private function get_post_categories($id) {
				
		$cat_ids = wp_get_post_categories($id);
		
			foreach((array)$cat_ids as $id) {
				
				$cat_obj[] = get_category($id);
			}
		
			return (isset($cat_obj)) ? $cat_obj : null;
	}
	
	
	public function get_experts() {
		
		global $wpdb;
		
		$q = "SELECT user_id FROM {$wpdb->usermeta} WHERE $wpdb->usermeta.meta_key = 'wp_capabilities' AND $wpdb->usermeta.meta_value LIKE '%administrator%'";
		$experts = $wpdb->get_results($q);
		
		return $experts;
		
	}
	
	private function set_experts($experts) {
		
		if(count($experts)) {
			
			foreach($experts as $expert) {
				
				$expert_ids[] = $expert->user_id;
			}
			
			$this->experts = implode(',', $expert_ids);
		}
	}
	
}