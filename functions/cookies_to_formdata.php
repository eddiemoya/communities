<?php

function formdata_cookie_check() {
	
	if(isset($_COOKIE['form-data'])) {
		
		$formdata = json_decode($_COOKIE['form-data']);
		
		var_dump($formdata);
		exit;
	}
}

add_action('init', 'formdata_cookie_check');