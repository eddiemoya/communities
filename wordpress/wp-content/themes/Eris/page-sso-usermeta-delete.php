<html>
<head>
	<title>Delete SSO Users Usermeta</title>
</head>
	
<body>

<?php if(! isset($_REQUEST['meta_delete'])): ?>
	<h3>Are you sure you would like to delete all SSO related user meta?</h3>
	
	<form method="post">
		<input type="submit" name="meta_delete" value="Delete All SSO usermeta" />
	</form>
<?php else: 

	global $wpdb;
	$wpdb->query("delete from wp_usermeta where meta_key IN ('sso_guid', 'profile_screen_name', 'user_city', 'user_state', 'user_zipcode')");
?>

<h3>All SSO related usermeta has been successfully removed from the wp_usermeta table.</h3>

<?php endif;?>
</body>
</html>