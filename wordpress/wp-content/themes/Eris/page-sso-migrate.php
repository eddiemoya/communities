<?php if(is_user_logged_in() && current_user_can('manage_options')): ?>
<html>
<head>
	<title>SSO User Migration</title>
</head>
<body>

<h2>SSO User Migration</h2>
<?php if(empty($_POST)): 
	
	$migrate = new SSO_User_Migration;
	
?>

<div id="stats">
	<p>Number of SSO Users (in system): <?php echo $migrate->user_cnt;?></p>
	<p>Number of failed user migrations: <?php echo $migrate->num_failed;?></p>
	<p>Next set number to migrate: <?php echo $migrate->next_page;?></p>
	<p>Total number of sets: <?php echo $migrate->num_pages;?></p>
</div>


<?php if($migrate->next_page):?>

<div id="migrate">
	<form id="migrate-form" method="post">
		<input type="hidden" name="page" id="page" value="<?php echo $migrate->next_page;?>" />
		<input type="submit" name="submit" value="Migrate Set <?php echo $migrate->next_page;?>" />
	</form>
</div>

<?php else:?>

<div id="no-more">
	<h3>All SSO Users have been imported.</h3>
</div>

<?php endif;?>



<?php else: //Form handler?>

<h3>Migration Running. Migrating user set: <?php echo $_POST['page'];?> ...</h3>

<?php
		//Run 
		$run = SSO_User_Migration::factory()
								->page($_POST['page'])
								->run();
						
?> 

<div id="sso-mig-results">
	<p>Test completed. There were <?php echo $run->num_failed;?> failed user migrations.</p>
	<p><a href="">Migrate next set of users</a></p>
</div>

<?php endif; ?>
</body>
</html>		

<?php else:?>

<h3>You do not have permission to view this page.</h3>

<?php endif;?>


<?php

/**
 * Class:: SSO_User_Migration
 */

class SSO_User_Migration {
	
	public static $option_name = 'sso_data_migration';
	
	public $user_cnt;
	
	public $users = array(); //array of WP user_ids
	
	public $page;
	
	public $num_pages;
	
	public $next_page;
	
	public $num_failed = 0;
	
	public $failed = array();
	
	protected $_limit = 1000;  
	
	protected $_options_default = array('last_page' => 0,
										'last_offset' => 0);
	
	protected $_options;

	protected $_offset;
	
	
	public function __construct() {
		
		$this->_user_cnt();
		$this->_num_pages();
		$this->_get_option();
		$this->_failed();
		
		$this->next_page = (($this->_options['last_page'] + 1) <= $this->num_pages) ? (($this->_options['last_page'] == 0) ? 1 : ($this->_options['last_page'] + 1)) : (get_option(self::$option_name, false) ? 0 : 1);
		
	}
	
	public static function factory() {
		
		return new SSO_User_Migration();
	}
	
	protected function _user_cnt() {
		
		global $wpdb;
		
		$q = "SELECT COUNT(DISTINCT ID) as num_users FROM {$wpdb->base_prefix}users u INNER JOIN {$wpdb->base_prefix}usermeta um ON u.ID = um.user_id where um.meta_key = 'sso_guid'";
		
		$cnt = $this->_convert($wpdb->get_results($q), 'num_users');
		$this->user_cnt =  (int) $cnt[0];
	}
	
	public function page($page) {
		
		$this->page = $page;
		
		return $this;
	}
	
	public function limit($num) {
		
		$this->_limit = $num;
		
		$this->_num_pages();
		
		return $this;
	}
	
	public function run() {
		
		$this->_offset();
		$this->_users();
		$this->num_failed = 0; //Reset num_failed to zero
		
		
		foreach($this->_users as $user_id) {
			
			$meta = $this->_get_user_meta($user_id);
			
			if(! $this->_insert_sso_user($meta)) {
				
				$this->failed[] = $user_id;
				$this->num_failed++;
			}
		}
		
		$this->_set_option(self::$option_name, array('last_page' 		=> $this->page,
														'last_offset' 	=> $this->_offset));
		
		//If we have failed inserts...
		if(count($this->failed)) {
			
			$opts = get_option('sso_migrate_failed', null);
			
			if($opts) {
				
				$updated = array_merge($opts, $this->failed);
				$this->_set_option('sso_migrate_failed', $updated);
				
			} else {
				
				$this->_set_option('sso_migrate_failed', $this->failed);
				
			}
		}
		
		return $this;
	}
	
	 protected function _failed() {
		
		$opts = get_option('sso_migrate_failed', null);
		
		if($opts) $this->num_failed = count($opts);
	}
	
	
	protected function _num_pages() {
		
		$this->num_pages = ceil($this->user_cnt / $this->_limit);
	}
	
	protected function _get_option() {
		
		$opts = get_option(self::$option_name, null);
		
		$this->_options = ($opts) ? $opts : $this->_options_default;
	}
	
	protected function _set_option($name, $args) {
		
		update_option($name, $args);
	}
	
	protected function _offset() {
		
		$offset = 0;
		
		for($i = 1; $i < $this->page; $i++) {
			
			$offset = ($offset + (int) $this->_limit);
		}
		
		$this->_offset = $offset;
	}
	
	protected function _users() {
		
		global $wpdb;
		
		$q = "SELECT DISTINCT ID FROM {$wpdb->base_prefix}users u INNER JOIN {$wpdb->base_prefix}usermeta um ON u.ID = um.user_id where um.meta_key = 'sso_guid' LIMIT {$this->_offset}, {$this->_limit}";
		
		$this->_users = $this->_convert($wpdb->get_results($q), 'ID');
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
		$obj->zipcode = get_user_meta($user_id, 'user_zipcode', true);
		
		return $obj;
	}
	
	protected function _insert_sso_user($user_meta) {
		
		global $wpdb;
		
		if($user_meta->zipcode) {
			
			$q = "INSERT INTO {$wpdb->base_prefix}sso_users (user_id, guid, screen_name, city, state, zipcode) VALUES ({$user_meta->ID}, {$user_meta->guid}, '{$user_meta->screen_name}', '{$user_meta->city}', '{$user_meta->state}', '{$user_meta->zipcode}')";
			
		} else {
            
	    	$q = "INSERT INTO {$wpdb->base_prefix}sso_users (user_id, guid, screen_name, city, state) VALUES ({$user_meta->ID}, {$user_meta->guid}, '{$user_meta->screen_name}', '{$user_meta->city}', '{$user_meta->state}')";
        }
        
        
        try {
        	
        	$wpdb->query($q);
        	return true;
        	
        } catch(Exception $e) {
        	
        	return false;
        }
       
	}
	
}
?>
