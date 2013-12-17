<?php

/**
 * AJAX function - Attempt to verify this domain for network creation
 * @param string $_POST['domain'] Domain name to check
 * @param string $_POST['path'] Path to check
 */
function networks_check_domain() {
	
	global $wpdb;
	
	$domain = $_POST['domain'];
	$path = $_POST['path'];
	
	/** Check DNS settings */
	$domain_addr = gethostbyname($domain);
	$current_addr = gethostbyname($_SERVER['HTTP_HOST']);
	if($domain_addr == $current_addr) {
		$dns_result = __('IP address for the new domain is a match!','njsl-networks');
		$dns_result_class = 'success';
	} else {
		$dns_result = sprintf(__('New domain IP ( %s ) does not match current IP ( %s ).<br />Check your DNS settings.','njsl-networks'),$domain_addr,$current_addr);
		$dns_result_class = 'error';
	}
	
	/** Check domain availability in WP */
	$site = $wpdb->get_var($wpdb->prepare(
		"SELECT COUNT(*) FROM {$wpdb->site} WHERE domain=%s", 
		$domain
	));
	
	if($site == 0) {

		/** Check domain mapping tables, if installed */
		if( isset( $wpdb->dmtable ) ) {
			$mapped_domain = $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->dmtable} WHERE domain=%s",
				$domain
			) );
		}

		if( ! isset( $wpdb->dmtable ) || $mapped_domain == 0 ) {
			$site_result = __('This domain is available!','njsl-networks');
			$site_result_class = 'success';
			
			$path_result = __('This path is available!','njsl-networks');
			$path_result_class = 'success';
		} else {
			$site_result = __('This domain is in use by domain mapping.','njsl-networks');
			$site_result_class = 'warning';
			
			$path_result = __('This path is available!','njsl-networks');
			$path_result_class = 'success';
		}
		
	} else {

		$site_result = __('One or more networks exist on this domain.','njsl-networks');
		$site_result_class = 'error';

		/** Path availability */
		$path = $wpdb->get_var($wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->site} WHERE domain='%s' AND path='%s'", 
			$wpdb->escape($domain),
			$wpdb->escape($path)
		));
		
		if($path == 0) {
			$path_result = __('This path is available, but there are other networks on this domain.','njsl-networks');
			$path_result_class = 'warning';
		} else {
			$path_result = __('This path is NOT available.','njsl-networks');
			$path_result_class = 'error';
		}
		
	}
	
	?>
	<div id="network_verify_result">
	<h5><?php _e('Results:','njsl-networks'); ?></h5>
	<ul>
		<li class="<?php echo $dns_result_class ?>">DNS: <?php echo $dns_result; ?></li>
		<li class="<?php echo $site_result_class ?>">Domain: <?php echo $site_result; ?></li>
		<li class="<?php echo $path_result_class ?>">Path: <?php echo $path_result; ?></li>
	</ul>
	</div>
	<?php
	die();
}

?>