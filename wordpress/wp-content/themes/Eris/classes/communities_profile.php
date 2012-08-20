<?php
/**
 * Gets posts (posts, guides, questions) and comments (answer, comment) for a user.
 * 
 * @author Dan Crimmins [dcrimmi@searshc.com]
 *
 */
class User_Profile {
	
	const EXPERT_ROLE = 'communityexpert';
	/**
	 * User's wordpress user_id
	 * @var int
	 */
	protected $user_id;
	
	/**
	 * Array of post types
	 * @var array
	 */
	public $post_types = array('question',
								'post',
							  	'guides');
	
	/**
	 * Array of comment types
	 * @var array
	 */
	public $comment_types = array('',
									'answer',
									'comment');
	
	/**
	 * Array of action types
	 * @var array
	 */
	public $action_types = array('follow',
									'upvote');
	
	/**
	 * Holds array of post objects
	 * @var array
	 */
	public $posts = null;
	
	/**
	 * Holds array of comment objects 
	 * @var array
	 */
	public $comments = null;
	
	/**
	 * Holds array of activities objects
	 * (posts, comments, and actions)
	 * @var array
	 */
	public $activities = null;
	
	/**
	 * 
	 * @var unknown_type
	 */
	
	public $experts;
	
	/**
	 * Posts per page 
	 * 
	 * @var int
	 */
	private $posts_per_page = 5;
	
	/**
	 * Pagination offset
	 * @var int
	 */
	private $offset = 0;
	
	/**
	 * Pagination page
	 * @var int
	 */
	private $page = 1;
	
	/**
	 * SQL LIMIT string
	 * @var string
	 */
	private $limit;
	
	/**
	 * Next page 
	 * @var int
	 */
	public $next_page;
	
	/**
	 * Previous Page 
	 * @var int
	 */
	public $prev_page;
	
	/**
	 * Array of navigation, based on user's activity
	 * @var array
	 */
	public $nav;
	
	/**
	 * Constructor
	 * @param int $user_id
	 */
	public function __construct($user_id = false) {
		
		 if(! $user_id) die('You must supply a user ID to constructor of User_Profile.'); 
		 
		 $this->user_id = $user_id;
		
		 $this->set_experts($this->get_experts());
		 
		 $this->set_nav();
		 
		
	}
	
	/**
	 * Sets page property
	 * @param int $page_num
	 * @return object - instance of this object
	 */
	public function page($page_num) {
		
		$this->page = $page_num;
		
		return $this;
	}
	
	/**
	 * Sets offset and limit properties
	 * @param void
	 * @return object
	 */
	private function paginate() {
		
		//Calculate the offset
		$row = 0;
		
		for($i = 1; $i < $this->page; $i++) {
			
			$row = $row + $this->posts_per_page;
		}
		
		$this->offset = $row;
		
		$this->limit = ' LIMIT ' . $row . ',' . $this->posts_per_page;
		
		return $this;
	}
	
	/**
	 * Sets posts_per_page
	 * @param int $num
	 * @return object
	 */
	
	public function posts_per_page($num) {
		
		$this->posts_per_page = $num;
		
		return $this;
	}
	
	/**
	 * Gets user posts by type, sets posts property
	 * @param string $post_type
	 * @return object
	 */
	public function get_user_posts_by_type($post_type = 'post' ) {
		
		$args = 	array('author'			=> $this->user_id,
						'post_status'		=> 'publish',
						'post_type'			=> $post_type,
						'order'				=> 'DESC',
						'orderby'			=> 'date',
						'posts_per_page'	=> $this->posts_per_page,
						'paged'				=> $this->page);
						
		
		//Sets num_pages and offset
		$this->paginate();
						
		$this->posts = get_posts($args);
		
		$this->next_page = (count($this->posts) < $this->posts_per_page) ? null : ($this->page + 1);	
		$this->prev_page = ($this->page != 1) ?  ($this->page - 1) : null;
		
		//Get and add categories property to each post
		$this->set_post_categories();
		
		return $this;
	}
	
	/**
	 * Gets user's recent activities (posts,comments,actions).
	 * Sets activities property
	 * 
	 * @return object
	 */
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
				WHERE p.post_type IN ('question', 'guides', 'post')
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
				ON pa.post_action_id = ua.action_id
				WHERE pa.object_type = 'posts'
				AND pa.object_subtype IN ('question', 'guides', 'post')
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
				pa.action_type,
				c.comment_author_url,
				c.comment_content 
				
				FROM {$wpdb->comments} c 
				LEFT JOIN {$wpdb->prefix}post_actions pa 
				ON c.comment_ID = pa.object_id 
				LEFT JOIN {$wpdb->prefix}user_actions ua 
				ON pa.post_action_id = ua.action_id 
				WHERE pa.object_type = 'comments'
				AND pa.action_type IN ('upvote', 'follow') 
				AND c.comment_approved = 1 AND ua.user_id = 1 )
				
				ORDER BY date DESC" . $this->limit;
		
		/*echo $q;
		exit;*/
		
		
		$this->activities = $wpdb->get_results($q);
		
		
		$this->set_activities_attributes();
		
		$this->next_page = (count($this->activities) < $this->posts_per_page) ? null : ($this->page + 1);
		$this->prev_page = ($this->page != 1) ?  ($this->page - 1) : null;
		
		return $this;
	}
	
	/**
	 * Gets user's actions by action type
	 * 
	 * @param string $type
	 * @return object
	 */
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
				ON pa.post_action_id = ua.action_id
				WHERE pa.object_type = 'posts'
				AND pa.object_subtype IN ('question', 'guides', 'post')
				AND pa.action_type IN ('upvote', 'follow')
				AND p.post_status='publish'
				AND ua.user_id = {$this->user_id}
				)
				
				UNION ALL

				(SELECT DISTINCT c.comment_ID,
				c.comment_post_ID,
				c.user_id, 
				c.comment_date, 
				c.comment_type, 
				pa.action_type,
				c.comment_author_url,
				c.comment_content 
				
				FROM {$wpdb->comments} c 
				LEFT JOIN {$wpdb->prefix}post_actions pa 
				ON c.comment_ID = pa.object_id 
				LEFT JOIN {$wpdb->prefix}user_actions ua 
				ON pa.post_action_id = ua.action_id 
				WHERE pa.object_type = 'comments'
				AND pa.action_type IN ('upvote', 'follow') 
				AND c.comment_approved = 1 AND ua.user_id = 1 )
				
				ORDER BY date DESC" . $this->limit;
		
		
		
		$this->activities = $wpdb->get_results($q);
		
		$this->set_activities_attributes();
		
		$this->next_page = (count($this->activities) < $this->posts_per_page) ? null : ($this->page + 1);
		$this->prev_page = ($this->page != 1) ?  ($this->page - 1) : null;
		
		return $this;
	
	}
	
	public function get_expert_answers() {
		
		foreach($this->posts as $key=>$post) {
			
			$answers = $this->get_experts_answers($post->ID);
			
			$this->posts[$key]->expert_answers = $answers;
			
			unset($answers);
		}
		
		return $this;
	}
	
	private function get_experts_answers($post_id) {
		
		global $wpdb;
		
		$q = "SELECT * FROM {$wpdb->comments} WHERE comment_post_id = {$post_id} AND comment_type = 'answer' AND user_id IN ({$this->experts})";
		
		return $wpdb->get_results($q);
	}
	
	/**
	 * Gets and adds category object to posts property array.
	 * 
	 * @return void
	 */
	
	private function set_post_categories() {
		
		if(count($this->posts)){
			
			foreach($this->posts as $key=>$post) {
				
				$this->posts[$key]->categories = $this->get_post_categories($post->ID);
			}
		}
	}
	
	/**
	 * Sets comments and posts on activities property array.
	 * 
	 * @return void
	 */
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
	
	
	/**
	 * Gets user comments by type. Sets comments property.
	 * 
	 * @param string $type
	 */
	public function get_user_comments_by_type($type = '') {
		
	     $args = array(	'type'				=> $type,
					 	'status'			=> 'approve',
						'user_id'			=> $this->user_id,
						'order'				=> 'DESC',
						'orderby'			=> 'comment_date',
	     				'number'			=> $this->posts_per_page
						);
			
			$this->paginate();
			
			$args['offset'] = $this->offset;
						
			$this->comments = get_comments($args);
			
			$this->next_page = (count($this->comments) < $this->posts_per_page) ? null : ($this->page + 1);
			$this->prev_page = ($this->page != 1) ?  ($this->page - 1) : null;
			
			$this->get_comment_post();
			
			return $this;
			
	}
	
	/**
	 * Gets and sets comments property on each post object in posts property.
	 * 
	 * @return void
	 */
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
	
	/**
	 * Sets categories objects on each post object in posts property.
	 * 
	 * @param int $id -- Post ID
	 */
	private function get_post_categories($id) {
				
		$cat_ids = wp_get_post_categories($id);
		
			foreach((array)$cat_ids as $id) {
				
				$cat_obj[] = get_category($id);
			}
		
			return (isset($cat_obj)) ? $cat_obj : null;
	}
	
	
	/**
	 * 
	 * Gets expert user objects
	 * 
	 * @return array - an array of user objects
	 */
	public function get_experts() {
		
		global $wpdb;
		
		$q = "SELECT user_id FROM {$wpdb->usermeta} WHERE $wpdb->usermeta.meta_key = 'wp_capabilities' AND $wpdb->usermeta.meta_value LIKE '%" . self::EXPERT_ROLE . "%'";
		$experts = $wpdb->get_results($q);
		
		return $experts;
		
	}
	
	/**
	 * Sets experts property with csv of expert user ids
	 * 
	 * @param array $experts -- an array of user objects
	 */
	private function set_experts($experts) {
		
		if(count($experts)) {
			
			foreach($experts as $expert) {
				
				$expert_ids[] = $expert->user_id;
			}
			
			$this->experts = implode(',', $expert_ids);
		}
	}
	
	private function set_nav() {
		
	 	
		//Posts
		foreach($this->post_types as $type) {
			
			if($this->has_post_count($type)) {
				
				$this->nav[] = $type;
			}
		}
		
		//Comments
		foreach($this->comment_types as $type) {
			
			if($this->has_comment_count($type)) {
			
				$this->nav[] = $type;
			}
		}
		
			//If there's blank and comment, remove blank
		 	if(in_array('', $this->nav) && in_array('comment', $this->nav)) {
		 		
		 		//Find blank
		 		$i = array_search('', $this->nav);
		 		unset($this->nav[$i]);
		 		
		 	} else if(in_array('', $this->nav) && ! in_array('comment', $this->nav)) { 
		 		//If there's blank and NOT comment, replace blank with comment
		 		
		 		$i = array_search('', $this->nav);
		 		$this->nav[$i] = 'comment';
		 		
		 	}
		 	
		 		
		
		//Actions
		foreach($this->action_types as $type) {
			
			if($this->has_action_count($type)) {
				
				$this->nav[] = $type;
			}
		}
		
			if(count($this->nav)) {
				
				array_unshift($this->nav, 'recent');
			}
			
			/*echo '<pre>';
			var_dump($this->nav);
			exit;*/
			
	}
	
	private function has_post_count($type) {
		
		$args =  array('author'				=> $this->user_id,
						'post_status'		=> 'publish',
						'post_type'			=> $type,
						'order'				=> 'DESC',
						'orderby'			=> 'date',
						'posts_per_page'	=> $this->posts_per_page,
						'paged'				=> $this->page);
		
		if(count(get_posts($args))) {
			
			return true;
			
		} else {
			
			return false;
		}
		
		
	}
	
	private function has_comment_count($type) {
		
		$args = array(	'type'				=> $type,
					 	'status'			=> 'approve',
						'user_id'			=> $this->user_id,
						'order'				=> 'DESC',
						'orderby'			=> 'comment_date',
	     				'number'			=> $this->posts_per_page
						);
						
						
			if(count(get_comments($args))) {
				
				return true;
				
			} else {
				
				return false;
			}
						
	}
	
	private function has_action_count($type = false) {
		
		global $wpdb;
		
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
				ON pa.post_action_id = ua.action_id
				WHERE pa.object_type = 'posts'
				AND pa.object_subtype IN ('question', 'guides', 'post')
				AND pa.action_type = '{$type}'
				AND p.post_status='publish'
				AND ua.user_id = {$this->user_id}
				)
				
				UNION ALL

				(SELECT DISTINCT c.comment_ID,
				c.comment_post_ID,
				c.user_id, 
				c.comment_date, 
				c.comment_type, 
				pa.action_type,
				c.comment_author_url,
				c.comment_content 
				
				FROM {$wpdb->comments} c 
				LEFT JOIN {$wpdb->prefix}post_actions pa 
				ON c.comment_ID = pa.object_id 
				LEFT JOIN {$wpdb->prefix}user_actions ua 
				ON pa.post_action_id = ua.action_id 
				WHERE pa.object_type = 'comments'
				AND pa.action_type = '{$type}'
				AND c.comment_approved = 1 AND ua.user_id = 1 )
				
				ORDER BY date DESC LIMIT 0,1";
		
				
				/*echo '<pre>';
				var_dump($wpdb->get_results($q));
				exit;*/
				
				if(count($wpdb->get_results($q))) {
					
					return true;
					
				} else {
					
					return false;
				}	
		
	}
	
}