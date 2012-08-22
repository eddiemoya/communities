<?php

function formdata_cookie_check() {
	
	if(isset($_COOKIE['form-data']) && empty($_POST)) {
		
		if(is_user_logged_in()) {
			
			$formdata = json_decode(stripslashes(str_replace("'", "\"", $_COOKIE['form-data'])), true);
			
			//Post a question form
			if(array_key_exists('new_question_step_1', $formdata)) {
				
				$posts = $formdata['new_question_step_1'];
				
					foreach($posts as $name=>$value){
						
						$_POST[$name] = $value;
					}
					
					//Set form id in post data
					$_POST['new_question_step_1'] = true;
					
			}
			
			//Enter more forms here...
			
			//Delete cookie
			setcookie('form-data', '0', time() - 6000, '/');
		
		}
		
		
	}
}

add_action('init', 'formdata_cookie_check');