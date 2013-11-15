<?php 

if(is_user_logged_in() && current_user_can('manage_options')) {
	
	var_dump($_SERVER);

} else {
	
	echo 'Not allowed.';
}