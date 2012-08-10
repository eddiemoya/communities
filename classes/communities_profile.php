<?php
class User_Profile {
	
	const ANSWER_TYPE = 'answer';
	const QUESTION_TYPE = 'question';
	const BLOG_POST_TYPE = 'blog_post';
	const BUYING_GUIDE_TYPE = 'buying_guide';
	
	protected $user_id;
	
	public $post_types = array('post',
								'question',
							  	'guide');
	
	public $comment_types = array('',
									'answer',
									'comment');
	
	public $posts = null;
	
	public $comments = null;
	
	public $activities = null;
	
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
		
		for($i = 1; $i < $this->page; $i++) {
			
			$row = $row + $this->posts_per_page;
		}
		
		
		//$this->num_pages = ceil(($this->total_results / $this->posts_per_page));
		/*echo $this->num_pages;
		exit;*/
		$this->offset = $row;
		
		//$this->next_page = ($this->page != $this->num_pages) ? ($this->page + 1) : null;
		
		$this->limit = ' LIMIT ' . $row .','. $this->posts_per_page;
		
		return $this;
	}
	
	public function posts_per_page($num) {
		
		$this->posts_per_page = $num;
		
		return $this;
	}
	
	public function get_user_posts_by_type($post_type = 'post' ) {
		
		$args = 	array('author'			=> $this->user_id,
						'post_status'		=> 'publish',
						'post_type'			=> $post_type,
						'order'				=> 'DESC',
						'orderby'			=> 'date',
						'posts_per_page'	=> $this->posts_per_page,
						'paged'				=> $this->page
						);
						
		//Sets total_results		
		//$this->get_post_count($args);
		
		//Sets num_pages and offset
		$this->paginate();
		
		//$args['posts_per_page'] = $this->posts_per_page;
		//$args['paged'] = $this->page;		
						
		$this->posts = get_posts($args);
		
		$this->next_page = (count($this->posts) < $this->posts_per_page) ? null : ($this->page + 1);
		
		//Get and add categories property to each post
		$this->set_post_categories();
		
		return $this;
	}
	
	public function get_recent_activities() {
		
		global $wpdb;
		
		//Set pagination properties
		$this->paginate();
		
		$q = "(SELECT 
				p.ID as ID,  
				p.post_parent as parent,
				p.post_author as author,
				p.post_date as date,
				p.post_type as type,
				p.post_excerpt as action,
				p.post_title as title,
				p.post_content as content
				
				FROM {$wpdb->posts} as p
				WHERE p.post_type IN ('question', 'guide', 'post')
				AND p.post_status='publish'
				AND p.post_author = {$this->user_id}
				)
				
				UNION ALL
				
				(SELECT 
				c.comment_ID,
				c.comment_post_ID,
				c.user_id,
				c.comment_date,
				c.comment_type,
				c.comment_karma,
				c.comment_author_url,
				c.comment_content
				
				FROM {$wpdb->comments} as c
				WHERE c.comment_type IN ( 'answer', 'comment', '' )
				AND c.comment_approved = 1
				AND c.user_id = {$this->user_id}
				)
				
				UNION ALL
				
				(SELECT DISTINCT
				p.ID as ID,  
				p.post_parent as parent,
				p.post_author as author,
				p.post_date as date, 
				p.post_type as type,
				pa.action_type as action,
				p.post_title as title,
				p.post_content as content
				
				FROM {$wpdb->posts} p
				LEFT JOIN {$wpdb->prefix}post_actions pa
				ON p.ID = pa.object_id
				LEFT JOIN {$wpdb->prefix}user_actions ua
				ON pa.post_action_id = ua.object_id
				WHERE pa.object_subtype IN ('question', 'guide', 'post')
				AND pa.action_type IN ('upvote', 'follow')
				AND p.post_status='publish'
				AND ua.user_id = {$this->user_id}
				)
				
				UNION ALL

				(SELECT c.comment_ID,
				c.comment_post_ID,
				c.user_id, 
				c.comment_date, 
				c.comment_type, 
				c.comment_karma,
				c.comment_author_url,
				c.comment_content 
				
				FROM {$wpdb->comments} c 
				LEFT JOIN {$wpdb->prefix}post_actions pa 
				ON c.comment_ID = pa.object_id 
				LEFT JOIN {$wpdb->prefix}user_actions ua 
				ON pa.post_action_id = ua.object_id 
				WHERE pa.object_subtype IN ('answer', 'comment', '' ) 
				AND pa.action_type IN ('upvote', 'follow') 
				AND c.comment_approved = 1 AND ua.user_id = 1 
				
				)
				
				ORDER BY date DESC" . $this->limit;
		
		/*echo $q;
		exit;*/
		
		
		
		$this->activities = $wpdb->get_results($q);
		
		$this->set_activities_attributes();
		
		$this->next_page = (count($this->activities) < $this->posts_per_page) ? null : ($this->page + 1);
		
		
		return $this;
	}
	
	public function get_actions($type) {
		
		global $wpdb;
		
		$this->paginate();
		
		$q = "(SELECT DISTINCT
				p.ID as ID,  
				p.post_parent as parent,
				p.post_author as author,
				p.post_date as date, 
				p.post_type as type,
				pa.action_type as action,
				p.post_title as title,
				p.post_content as content
				
				FROM {$wpdb->posts} p
				LEFT JOIN {$wpdb->prefix}post_actions pa
				ON p.ID = pa.object_id
				LEFT JOIN {$wpdb->prefix}user_actions ua
				ON pa.post_action_id = ua.object_id
				WHERE pa.object_subtype IN ('question', 'guide', 'post')
				AND pa.action_type = '{$type}'
				AND p.post_status='publish'
				AND ua.user_id = {$this->user_id}
				)
				
				UNION ALL

				(SELECT c.comment_ID,
				c.comment_post_ID,
				c.user_id, 
				c.comment_date, 
				c.comment_type, 
				c.comment_karma,
				c.comment_author_url,
				c.comment_content 
				
				FROM {$wpdb->comments} c 
				LEFT JOIN {$wpdb->prefix}post_actions pa 
				ON c.comment_ID = pa.object_id 
				LEFT JOIN {$wpdb->prefix}user_actions ua 
				ON pa.post_action_id = ua.object_id 
				WHERE pa.object_subtype IN ('answer', 'comment', '' ) 
				AND pa.action_type = '{$type}'
				AND c.comment_approved = 1 AND ua.user_id = 1 
				
				)
				
				ORDER BY date DESC" . $this->limit;
		
		$this->activities = $wpdb->get_results($q);
		
		$this->set_activities_attributes();
		
		$this->next_page = (count($this->activities) < $this->posts_per_page) ? null : ($this->page + 1);
		
		
		return $this;
	
	}
	
	private function set_post_categories() {
		
		if(count($this->posts)){
			
			foreach($this->posts as $key=>$post) {
				
				$this->posts[$key]->categories = $this->get_post_categories($post->ID);
			}
		}
	}
	
	private function set_activities_attributes() {
		
		if(count($this->activities)) {
			
			foreach($this->activities as $key => $activity) {
				
				//If is a comment
				if(in_array($activity->type, $this->comment_types)) {
					
					//set post property on comment
					$this->activities[$key]->post = get_post($activity->parent);
					
					//If post property is an object, get post category and add 
					//category property to object
					if(is_object($this->activities[$key]->post)) {
						
						$this->activities[$key]->post->category = $this->get_post_categories($activity->parent);
					}
						
					
				}
				
				//If is a post
				if(in_array($activity->type, $this->post_types)) {
					
					$this->activities[$key]->category = $this->get_post_categories($activity->ID);
					
				}
				
			}
		}
	}
	
	private function get_post_count($args) {
		
		$args['posts_per_page'] = -1;	
		$this->total_results = count(get_posts($args));
		
		
		/*echo '<pre>';
		var_dump(get_posts($args));
		exit;*/
	}
	
	
	public function get_user_comments_by_type($type = '') {
		
	     $args = array(	'type'				=> $type,
					 	'status'			=> 'approve',
						'user_id'			=> $this->user_id,
						'order'				=> 'DESC',
						'orderby'			=> 'comment_date',
	     				'number'			=> $this->posts_per_page
						);

			//Sets total
			//$this->get_total_comments($args);
			
			$this->paginate();
			
			//$args['number'] = $this->posts_per_page;
			$args['offset'] = $this->offset;
						
			$this->comments = get_comments($args);
			
			$this->next_page = (count($this->comments) < $this->posts_per_page) ? null : ($this->page + 1);
			
			$this->get_comment_post();
			
			return $this;
			
	}
	
	private function get_comment_post() {
		
		if(count($this->comments)) {
			
			foreach($this->comments as $key=>$comment) {
				
				$post = get_post($comment->comment_post_ID);
				
				$post->categories = $this->get_post_categories($comment->comment_post_ID);
				
				$this->comments[$key]->post = $post;
				
					//Set category property on post object
					if(is_object($this->comments[$key]->post)) {
						
						$this->comments[$key]->post->category = $this->get_post_categories($comment->comment_post_ID);
					}
				
				unset($post);
			}
		}
		
	}
	

	private function get_total_comments($args) {
		
		$args['count'] = 1;
		
		$count = get_comments($args);
		
		/*var_dump(get_comments($args));
		exit;*/
		
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