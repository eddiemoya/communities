<?php
/*
 * Plugin Name: User Meta Taxonomy Plugin
 * Description: Assigns taxonomies to users via user meta
 * Version: 1.0
 * Author: Dan Crimmins
 */



//Initiate plugin
add_action('init', array('User_Meta_Taxonomy', 'init'));

/**
 * Template Tag 
 * When given a taxonomy slug and array of term_ids,
 * returns an array of users (objects) that are assigned to given taxonomy terms.
 * 
 * @param string $taxonomy
 * @param array $term_id
 * @param int $count (optional)
 * @param string $relation (optional)
 * @return array - an array of user objects
 */
function get_users_by_taxonomy($taxonomy, $term_id, $count = '', $relation = 'OR') {
	
	$um_tax = new User_Meta_Taxonomy;
	$users = $um_tax->get_users_by_taxonomy($taxonomy, (array)$term_id, $count, $relation);
	return $users;
}

/**
 * Template Tag
 * Given a user id, returns an array of terms for each user taxonomy
 * 
 * @param int $user_id
 * @return array
 */
function get_user_taxonomy_terms($user_id) {
	
	$um_tax = new User_Meta_Taxonomy;
	$terms = $um_tax->get_user_meta($user_id);
	return $terms;
}

/**
 * User_Meta_Taxonomy
 * @author Dan Crimmins [dcrimmi@searshc.com]
 */


class User_Meta_Taxonomy {
	
	const USER_META_PREFIX = 'um-taxonomy';
	
	/**
	 * Array of user taxonomies. Any future taxonomies should be added here.
	 * @var array
	 * @access private
	 */
	private $_taxonomies = array('badge', 'category');
	
	
	/**
	 * Array of all taxonomy objects
	 * @var array
	 * @access protected
	 */
	public $_taxonomy_terms;
	

	/**
	 * An array of user objects
	 * @var array
	 * @access private
	 */
	private $_users = array();
	
	
	/**
	 * Constructor
	 * 
	 * @param void
	 * @return void
	 */
	function __construct() {
		
		//Retrieve all terms for each taxonomy and set $_taxonomy_terms
		$this->get_tax_terms();
		
		//Set hooks
		add_action('show_user_profile', array($this, 'user_admin_display'));
		add_action('edit_user_profile', array($this, 'user_admin_display'));
		add_action('edit_user_profile_update', array($this, 'user_admin_save'));
	}
	

	/**
	 * Initialize - return an object of this class
	 * 
	 * @param void
	 * @return object - an object of this class
	 * @access public
	 */

	public static function init() {
		
		return new User_Meta_Taxonomy();
	}

		
	/**
	 * Given a taxonomy and an array of term ids, will return an array of 
	 * user objects for users that are assigned to the terms of taxonomy
	 * 
	 * @param string $taxonomy - Slug of taxonomy
	 * @param array $term_id - Array of taxonomy term ids 
	 * @param int $count - Number of users to return (optional)
	 * @param string $relation - Relation operator (optional)
	 * @return array - An array of user objects ($_users)
	 * @access public
	 */
	public function get_users_by_taxonomy($taxonomy, $term_id, $count = '', $relation = 'OR') {
		
		$terms = (array) $term_id;
		$um_key = self::USER_META_PREFIX . '-' . strtolower($taxonomy);
		
		$args = array('meta_query' => array('relation' => $relation,
											array('key' 	=> $um_key,
												  'value' 	=> $terms,
												  'compare' => 'IN')
											),
					'count_total' => true,
					'fields' => 'all',
					'number' => $count);
			
			$this->_users = get_users($args);
			
			return $this->_users;
	}
	
	

	/**
	 * Retrieve all terms for each taxonomy
	 * 
	 * @param void
	 * @return void
	 */
	private function get_tax_terms() {
		
		foreach($this->_taxonomies as $taxonomy) {
			
			$terms = get_terms($taxonomy, 'hide_empty=0');
			
			//Set taxonomy terms
			$this->set_taxonomy_terms($taxonomy, $terms);
		}
	}
	
	/**
	 * Sets $_taxonomy_terms
	 * 
	 * @param string $taxonomy
	 * @param array $terms
	 */
	private function set_taxonomy_terms($taxonomy, $terms) {
		
		if(is_array($terms)) {
			
			foreach($terms as $term) {
				
				$term_array[] = array('id' => $term->term_id,
									  'name' => $term->name);
			}
			
			$this->_taxonomy_terms[$taxonomy] = $term_array;
		} 
	}
	
	/**
	 * Prints user form for selecting terms for each taxonomy.
	 * 
	 * @param object $user
	 * @access public
	 * @return string - Outputs HTML
	 */
	
	public function user_admin_display($user) {
		
		//Get all terms for each taxonomy currently assigned to this user
		$user_terms = $this->get_user_meta($user->ID);
		
		ob_start();
		
		foreach($this->_taxonomies as $taxonomy):
		?>
		
		<table class="form-table">
			<tr>
				<th><label for=""><?php _e('Select ' . ucfirst($taxonomy));?></label></th>
				<td>
					<?php foreach($this->_taxonomy_terms[$taxonomy] as $term):?>
						<input type="checkbox" name="<?php echo self::USER_META_PREFIX . '-' . $taxonomy; ?>[]" id="<?php echo self::USER_META_PREFIX . '-' . $taxonomy . '-' . $term['id'];?>" value="<?php echo $term['id']; ?>" <?php checked(true, in_array($term['id'], $user_terms[$taxonomy]));?> />
						<label for="<?php echo self::USER_META_PREFIX . '-' . $taxonomy . '-' . $term['id']; ?>"><?php echo $term['name']; ?></label><br />
					<?php endforeach;?>
				</td>
			</tr>
		</table>
		<?php 
		endforeach;
		
		$out = '<h3>User Taxonomy</h3>' . ob_get_clean();
		echo $out;
	}
	
	
	/**
	 * Called when saving data from user profile page.
	 * 
	 * @param array $post - $_POST data
	 * @param int $user_id
	 * @return void
	 * @access public
	 */
	public function user_admin_save($user_id) {
			
			foreach($this->_taxonomies as $taxonomy) {
				
				//First, remove all meta data for this user for this taxonomy
				delete_user_meta($user_id, self::USER_META_PREFIX . '-' . $taxonomy);
				
				if(isset($_POST[self::USER_META_PREFIX . '-' . $taxonomy])) { //Post data exists for this taxonomy
					
					foreach($_POST[self::USER_META_PREFIX . '-' . $taxonomy] as $term_id) {
						
						//add user_meta
						add_user_meta($user_id, self::USER_META_PREFIX . '-' . $taxonomy, $term_id);
					}
				} 
			}
	}
	
	/**
	 * Given a user ID, returns an array of user meta for each taxonomy
	 * 
	 * @param int $user_id
	 * @return array 
	 * @access public
	 */
	public function get_user_meta($user_id) {
		
		foreach($this->_taxonomies as $taxonomy) {
			
			$meta_values[$taxonomy] = get_user_meta($user_id, self::USER_META_PREFIX . '-' . $taxonomy, false);
		}
			
			return $meta_values;
	}
	
	
}


