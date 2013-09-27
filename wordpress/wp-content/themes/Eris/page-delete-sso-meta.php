<html>
<head>
	<title>Delete SSO related User Meta</title>
</head>
<body>
<?php if(empty($_POST)):?>

<form method="post">
	<input type="submit" name="delete_sso_meta" value="Delete SSO User Meta" />
</form>

<?php else:?>

<div id="job-complete">

	<?php if(isset($_POST['delete_sso_meta'])): 
			
			$affected = delete_sso_meta();
			
			if($affected !== false) {
			
				echo 'Job completed successfully! ' . $affected . ' rows affected.';
				
			} else {
				
				echo 'The job did not complete successfully!';
			}
			
			
			else:
			
				echo 'Job was NOT run.';
			
			endif;
	?>
	
</div>

<?php endif;?>
</body>
</html>

<?php 

function delete_sso_meta() {
	
	global $wpdb;
	
	$q = "DELETE FROM $wpdb->usermeta WHERE meta_key IN ('sso_guid', 'profile_screen_name', 'user_zipcode')";
	return $wpdb->query($q);
}



?>