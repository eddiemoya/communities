<?php

function get_user_sso_guid($user_id) {
	
		global $wpdb;
		$usermeta = $wpdb->prefix . 'usermeta';
		
		$user_query = "SELECT meta_value FROM " . $usermeta ." WHERE meta_key = 'sso_guid' AND user_id = " . $user_id;
		$sso_guid = $wpdb->get_var($user_query);
	 	
 		return $sso_guid;
}