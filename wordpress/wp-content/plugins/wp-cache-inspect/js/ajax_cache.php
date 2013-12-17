<?php

require_once('../../../../wp-load.php');

header('Content-Type: text/Javascript'); 
?>

// JavaScript Document
jQuery(document).ready(function() {
	jQuery("#cache_flush_footer").click(function() {
		jQuery.post("<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",{action: "cache_flush"}, function(data) {
			alert(data);
		});
		return false;
	});
	jQuery("#cache_flush_fav").click(function() {
		jQuery.post("<?php echo get_bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",{action: "cache_flush"}, function(data) {
			alert(data);
		});
		return false;
	});
});