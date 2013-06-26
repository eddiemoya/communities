<?php if(is_user_logged_in() && current_user_can('manage_options')): ?>
<html>
<head>
	<title>SSO Post User Migration Tool</title>
</head>
<body>

<h2>SSO Post User Migration Tool</h2>
<?php if(empty($_POST)): 
	
	$migrate = new SSO_Post_User_Migration;
	
?>

<div id="stats">
	<p>Number of SSO Users (in system): <?php echo $migrate->user_cnt;?></p>
	<p>Number of failed user migrations: <?php echo $migrate->num_failed;?></p>
	<p>Number of SSO Users NOT migrated yet: <?php echo $migrate->to_migrate_cnt;?></p>
</div>


<?php if($migrate->to_migrate_cnt):?>

<div id="migrate">
	<form id="migrate-form" method="post">
		<input type="submit" name="submit" value="Migrate Remaining <?php echo $migrate->to_migrate_cnt;?> SSO Users" />
	</form>
</div>

<?php else:?>

<div id="no-more">
	<h3>All SSO Users have been imported.</h3>
</div>

<?php endif;?>



<?php else: //Form handler?>

<h3>Migration Running...</h3>

<?php
		//Run 
		$run = SSO_Post_User_Migration::factory()->run();				
?> 

<div id="sso-mig-results">
	<p>Job completed. There were <?php echo $run->num_failed;?> failed user migrations.</p>
</div>

<?php endif; ?>
</body>
</html>		

<?php else:?>

<h3>You do not have permission to view this page.</h3>

<?php endif;?>


<?php

/**
 * Class:: SSO_Post_User_Migration
 */

class SSO_Post_User_Migration {
	
	public $user_cnt;
	
	public $migrated_cnt;
	
	public $to_migrate_cnt;
	
	public $users = array(); //array of WP user_ids
	
	public $page;
	
	public $num_pages;
	
	public $next_page;
	
	public $num_failed = 0;
	
	public $failed = array();
	
	protected $_limit = 5000;  
	
	protected $_offset;
	
	protected $_options_default = array('last_page' => 0,
										'last_offset' => 0);
	
	protected $_options;
	
	
	public function __construct() {
		
		$this->_user_cnt();
		$this->_migrated_user_cnt();
		$this->_user_cnt_to_migrate();
		$this->_failed();
	}
	
	public static function factory() {
		
		return new SSO_Post_User_Migration();
	}
	
	protected function _user_cnt() {
		
		global $wpdb;
		
		$q = "SELECT COUNT(DISTINCT ID) as num_users FROM {$wpdb->base_prefix}users u INNER JOIN {$wpdb->base_prefix}usermeta um ON u.ID = um.user_id where um.meta_key = 'sso_guid'";
		
		$cnt = $this->_convert($wpdb->get_results($q), 'num_users');
		$this->user_cnt =  (int) $cnt[0];
	}
	
	protected function _migrated_user_cnt() {
		
		global $wpdb;
		
		$q = "SELECT COUNT(*) as num_migrated FROM {$wpdb->base_prefix}sso_users";
		
		$cnt = $wpdb->get_var($q);
		$this->migrated_cnt = ($cnt) ? $cnt : 0; 
	}
	
	protected function _user_cnt_to_migrate() {
		
		$this->to_migrate_cnt = ($this->migrated_cnt > 0) ? ($this->user_cnt - $this->migrated_cnt) : 0;
		
		if($this->to_migrate_cnt) {
			
			$this->_offset = ($this->user_cnt - $this->to_migrate_cnt);
		}
	}
	
	
	public function limit($num) {
		
		$this->_limit = $num;
		
		$this->_num_pages();
		
		return $this;
	}
	
	public function run() {
		
		$this->_users();
		$this->num_failed = 0; //Reset num_failed to zero
		
		foreach($this->users as $user_id) {
			
			$meta = $this->_get_user_meta($user_id);
			
			if($this->_insert_sso_user($meta) === false) {
				
				$this->failed[] = $user_id;
				$this->num_failed++;
			}
		}
		
				
		//If we have failed inserts...
		if(count($this->failed)) {
			
			$opts = get_option('sso_post_migrate_failed', null);
			
			if($opts) {
				
				$updated = array_merge($opts, $this->failed);
				$this->_set_option('sso_post_migrate_failed', $updated);
				
			} else {
				
				$this->_set_option('sso_post_migrate_failed', $this->failed);
				
			}
		}
		
		return $this;
	}
	
	 protected function _failed() {
		
		$opts = get_option('sso_post_migrate_failed', null);
		
		if($opts) $this->num_failed = count($opts);
	}
	
	
	protected function _set_option($name, $args) {
		
		update_option($name, $args);
	}
	
	
	protected function _users() {
		
		global $wpdb;
		
		$q = "SELECT DISTINCT ID FROM {$wpdb->base_prefix}users u INNER JOIN {$wpdb->base_prefix}usermeta um ON u.ID = um.user_id where um.meta_key = 'sso_guid' LIMIT {$this->_offset}, {$this->_limit}";
		
		$this->users = $this->_convert($wpdb->get_results($q), 'ID');
	}
	
	protected function _convert($results, $property) {
		
		if(is_array($results)) {
			
			$out = array();
			
			foreach($results as $elem) {
				
				$out[] = $elem->{$property};
			}
			
			return $out;
		}
		
		return $results;
	}
	
	protected function _get_user_meta($user_id) {
		
		$obj = new stdClass();
		
		$obj->ID = $user_id;
		$obj->guid = get_user_meta($user_id, 'sso_guid', true);
		$obj->screen_name = get_user_meta($user_id, 'profile_screen_name', true);
		$obj->city = get_user_meta($user_id, 'user_city', true);
		$obj->state = get_user_meta($user_id, 'user_state', true);
		$obj->zipcode = $this->_truncate_zipcode(get_user_meta($user_id, 'user_zipcode', true));
		
		return $obj;
	}
	
	protected function _truncate_zipcode($zipcode) {
		
		if(strlen($zipcode) > 5) {
		
			return substr((string)$zipcode, 0, 5);
		}
	
		return $zipcode;
	}
	
	protected function _insert_sso_user($user_meta) {
		
		global $wpdb;
		
		if($user_meta->zipcode) {
			
			$q = "INSERT INTO {$wpdb->base_prefix}sso_users (user_id, guid, screen_name, city, state, zipcode) VALUES ({$user_meta->ID}, {$user_meta->guid}, \"{$user_meta->screen_name}\", \"{$user_meta->city}\", \"{$user_meta->state}\", \"{$user_meta->zipcode}\")";
			
		} else {
            
	    	$q = "INSERT INTO {$wpdb->base_prefix}sso_users (user_id, guid, screen_name, city, state) VALUES ({$user_meta->ID}, {$user_meta->guid}, \"{$user_meta->screen_name}\", \"{$user_meta->city}\", \"{$user_meta->state}\")";
        }
        
        
        try {
        	
        	return $wpdb->query($q);
        	
        } catch(Exception $e) {
        	
        	return false;
        }
       
	}
	
}
?>
