<?php
/*
 * Plugin Name: Communities User Taxonomies Plugin
 * Description: Creates user taxonomies: badges and categories
 * Version: 1.0
 * Author: Dan Crimmins
 */


//Register Badge taxonomy for users
add_action('init', 'register_badges');

//Register categories for users
//add_action('init', 'register_user_categories');

//Display current user badge(s) on user profile page
add_action('show_user_profile', 'show_admin_user_badges');
add_action('edit_user_profile', 'show_admin_user_badges');




/**
 * 
 *  Registers the user taxonomy 'Badge'.
 *  
 *  @param void
 *  
 */

//Register badges
function register_badges() {
	
	register_taxonomy('badge', 'user', array(
	'public'		=>true,
	'labels'		=>array(
		'name'						=>'Badge',
		'singular_name'				=>'Badge',
		'menu_name'					=>'Badges',
		'search_items'				=>__('Search Badges'),
		'popular_items'				=>__('Popular Badges'),
		'all_items'					=>__('All Badges'),
		'edit_item'					=>__('Edit Badges'),
		'update_item'				=>__('Update Badges'),
		'add_new_item'				=>__('Add New Badge'),
		'new_item_name'				=>__('New Badge Name'),
		'separate_items_with_commas'=>__('Separate badges with commas'),
		'add_or_remove_items'		=>__('Add or remove badges'),
		'choose_from_most_used'		=>__('Choose from the most popular badges'),
	),
	'rewrite'		=>array(
		'with_front'				=>true,
		'slug'						=>'author/badge',
	),
	'capabilities'	=> array(
		'manage_terms'				=>'edit_users',
		'edit_terms'				=>'edit_users',
		'delete_terms'				=>'edit_users',
		'assign_terms'				=>'read',
	)
	
));
}

//Register categories
function register_user_categories() {
	
	register_taxonomy('category', array('user', 'post'), array(
	'public'		=>true,
	'labels'		=>array(
		'name'						=>'Category',
		'singular_name'				=>'Category',
		'menu_name'					=>'Category',
		'search_items'				=>__('Search Categories'),
		'popular_items'				=>__('Popular Categories'),
		'all_items'					=>__('All Categories'),
		'edit_item'					=>__('Edit Categories'),
		'update_item'				=>__('Update Categories'),
		'add_new_item'				=>__('Add New Category'),
		'new_item_name'				=>__('New Category Name'),
		'separate_items_with_commas'=>__('Separate categories with commas'),
		'add_or_remove_items'		=>__('Add or remove Categories'),
		'choose_from_most_used'		=>__('Choose from the most popular Categories'),
	),
	'rewrite'		=>array(
		'with_front'				=>true,
		'slug'						=>'author/category',
	),
	'capabilities'	=> array(
		'manage_terms'				=>'edit_users',
		'edit_terms'				=>'edit_users',
		'delete_terms'				=>'edit_users',
		'assign_terms'				=>'read',
	),
	'heirarchical' => true
	
));

	/*register_taxonomy('category', 'post', array(
	'public'		=>true,
	'labels'		=>array(
		'name'						=>'Category',
		'singular_name'				=>'Category',
		'menu_name'					=>'Category',
		'search_items'				=>__('Search Categories'),
		'popular_items'				=>__('Popular Categories'),
		'all_items'					=>__('All Categories'),
		'edit_item'					=>__('Edit Categories'),
		'update_item'				=>__('Update Categories'),
		'add_new_item'				=>__('Add New Category'),
		'new_item_name'				=>__('New Category Name'),
		'separate_items_with_commas'=>__('Separate categories with commas'),
		'add_or_remove_items'		=>__('Add or remove Categories'),
		'choose_from_most_used'		=>__('Choose from the most popular Categories'),
	),
	'rewrite'		=>array(
		'with_front'				=>true,
		'slug'						=>'/category',
	),
	'capabilities'	=> array(
		'manage_terms'				=>'edit_post',
		'edit_terms'				=>'edit_post',
		'delete_terms'				=>'edit_post',
		'assign_terms'				=>'read',
	)
));*/
	

}


/**
 * 
 * User_Badge class: Given a user id, retrieves the user's 'badge' and associated
 * badge image.
 * 
 * @author Dan Crimmins
 *
 */
class User_Badge {
	
	/**
	 * User ID
	 * @var int
	 */
	private $user_id;
	
	/**
	 * An array of badge term objects.
	 * @var array
	 */
	public $user_terms = array();
	
	/**
	 * Constructor
	 * @param int $user_id
	 * 
	 */
	function __construct($user_id = false) {
		
		if(! $user_id) {
			
			die('A user ID must be provided.');
		}
		
			$this->user_id = $user_id;
			
			//Sets user_terms
			$this->get_user_badge_terms();
			
			//Get and set the image for each badge term
			$this->set_badge_images();
	}
	
	/**
	 * 
	 * Retrieves badge(s) assigned to a user and 
	 * sets the $user_terms property.
	 * 
	 * @param void
	 */
	
	private function get_user_badge_terms() {
		
		$terms = get_user_taxonomy_terms($this->user_id);
		$term_objects = array();
		
			if(count($terms['badge'])) {
				
				foreach($terms['badge'] as $term_id) {
					
					$this->user_terms[] = get_term_by('id', $term_id, 'badge');
				}
			}
		
	}
	
	/**
	 * 
	 * Callback function used to retrieve an 
	 * image assigned to a badge and append 
	 * image property (url) to given object
	 * 
	 * @param object $value
	 * @param int $key
	 */
	
	private function get_badge_image(&$value, $key) {
		
		$args = array('post_type' => 'attachment',
						'showposts' => -1,
						'taxonomy' => 'badge',
						'term' => $value->slug);
						
		$badge = get_posts($args);
		
		//Add image property to term object
		$value->image = $badge[0]->guid;
	}
	
	/**
	 *  Loops over $user_terms (array) and uses 
	 *  get_badge_image() callback method to retrieve  
	 *  and set badge image.
	 *  
	 *  @param void
	 *  @uses get_badge_image() (callback method)
	 *  
	 */
	
	private function set_badge_images() {
		
		array_walk($this->user_terms, array($this, 'get_badge_image'));
	}
	
	
}


/**
 * Template Tag
 * Display a user's badge(s) and associated badge image.
 * 
 * @uses User_Badge class
 * @param int $user_id The user id of a user.
 * @return string HTML to display badge image and name.
 * 
 */
function show_user_badges($user_id) {
	
	$user_badges = new User_Badge($user_id);
	
	ob_start();
	
	foreach($user_badges->user_terms as $user_badge):
	?>
		
			<div class="user-badges">
				<img src="<?php echo $user_badge->image;?>" class="badge-image">
				<span class="badge-name"><?php echo $user_badge->name;?></span>
			</div>
		
	<?php endforeach;
	
	$output = ob_get_clean();
	
	echo $output;
}

/**
 * Used to display user badges on admin user profile page
 * 
 * @param object $user
 */

function show_admin_user_badges($user) {
	
	$badges = new User_Badge($user->ID);
	
	ob_start();
	?>
	<h3><?php _e('User Badge(s)'); ?> </h3>
		<table class="form-table">
			<?php foreach($badges->user_terms as $badge):?>
			<tr>
				<th>
					<img src="<?php echo $badge->image;?>" width="50" height="50"/>
				</th>
				<td>
					<?php echo $badge->name;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	<?php 
	$output = ob_get_clean();
	
	echo $output;
}
	
?>