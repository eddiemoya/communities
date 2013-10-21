<html>
<head>
	<title>Delete SSO Users Usermeta</title>
</head>
	
<body>
<?php if(is_user_logged_in() && current_user_can('manage_options')): ?>

	<?php if(! isset($_REQUEST['meta_delete'])): ?>
		<h3>Are you sure you would like to delete all SSO related user meta?</h3>
		
		<form method="post">
			<input type="submit" name="meta_delete" value="Delete All SSO usermeta" />
		</form>
	<?php else: 
	
		global $wpdb;
		
		var_dump($wpdb->usermeta);
		exit;
		//$q = $wpdb->query("delete from {$wpdb->usermeta} where meta_key IN ('sso_guid', 'profile_screen_name', 'user_zipcode')");
	?>
	
	<h3>All SSO related usermeta has been successfully removed from the wp_usermeta table. <?php echo number_format($q);?> rows affected.</h3>
	
	<?php endif;?>
	
<?php else:?>

	<h3>You do not have permission to view this page.</h3>
	
<?php endif;?>
</body>
</html>