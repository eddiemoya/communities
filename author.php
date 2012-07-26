<?
get_template_part('parts/header');

//Set profile type
if(is_user_logged_in()){
	
	//The authenticated user is viewing their own profile
	if(get_query_var('author') == $current_user->data->ID) {
		
		
	} else { //Authenticated user is viewing another user's profile
		
		echo '<pre>';
		var_dump($current_user);
		echo '</pre>';
	}
	//Retrieves '/author/username' username's userid
	$author = get_query_var('author');
	echo 'Profile for user: ' . $author . '<br>';
	
	echo 'Current logged in user: ' . $current_user->data->ID . '<br>';
	/*var_dump($current_user);
	exit;*/
	
} else {
	
	
}

echo 'Profile Page';

get_template_part('parts/footer');