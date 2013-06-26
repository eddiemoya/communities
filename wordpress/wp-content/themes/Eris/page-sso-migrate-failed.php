<?php






class SSO_Failed_User_Migration {
	
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
		
		$this->_get_failed();
		$this->_failed();
	}
	
	public static function factory() {
		
		return new SSO_Failed_User_Migration();
	}
	
	
	public function limit($num) {
		
		$this->_limit = $num;
		
		$this->_num_pages();
		
		return $this;
	}
	
	protected function _get_failed() {
		
		$opts = get_option('sso_migrate_failed', null);
		
		if($opts) {
			
			//Remove first three
			unset($opts[0]);
			unset($opts[1]);
			unset($opts[2]);
			
			$this->users = $opts;
			$this->user_cnt = count($opts);
			
		} else {
			
			$this->user_cnt = 0;
		}
		
	}
	
	public function run() {
		
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
			
			$opts = get_option('sso_fail_migrate_failed', null);
			
			if($opts) {
				
				$updated = array_merge($opts, $this->failed);
				$this->_set_option('sso_fail_migrate_failed', $updated);
				
			} else {
				
				$this->_set_option('sso_fail_migrate_failed', $this->failed);
				
			}
		}
		
		return $this;
	}
	
	 protected function _failed() {
		
		$opts = get_option('sso_fail_migrate_failed', null);
		
		if($opts) $this->num_failed = count($opts);
	}
	
	
	protected function _set_option($name, $args) {
		
		update_option($name, $args);
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
		
			return (int) substr((string)$zipcode, 0, 5);
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