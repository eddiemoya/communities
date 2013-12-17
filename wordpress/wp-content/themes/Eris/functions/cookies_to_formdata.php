<?php

function formdata_cookie_check() {
	
	if(isset($_COOKIE['form-data'])) {
		
		if(is_user_logged_in()) {
			
			//$formdata = json_decode(urldecode(stripslashes(str_replace("'", "\'", $_COOKIE['form-data']))), true);
			$formdata = json_decode(urldecode(stripslashes($_COOKIE['form-data'])), true);
			
			//Post a question form
			if(array_key_exists('new_question_step_1', $formdata)) {
				
				$posts = $formdata['new_question_step_1'];
				
					foreach($posts as $name=>$value){
						
						$_POST[$name] = $value;
					}
					
					//Set form id in post data
					$_POST['new_question_step_1'] = true;
					
					//Delete cookie
					setcookie('form-data', '0', time() - 6000, '/');
					
			}
			
			
			//Enter more forms here...
		
		}
		
		
	}
}


add_action('init', 'formdata_cookie_check', 1);