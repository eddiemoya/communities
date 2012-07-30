<?
/*
 * QueryString params:
 * 
 * 'part='
 * 
 * 1. recent - Recent Activities
 * 2. qa = Q/A's
 * 3. blog = Blog Posts
 * 4. guides = Buying Guides
 * 
 */
get_template_part('parts/header');

$profile_user = get_userdata(get_query_var('author'));

//Set profile_type
if(is_user_logged_in()){
	
	//The authenticated user is viewing their own profile
	if($profile_user->data->ID == $current_user->data->ID) {
		
		$profile_type = 'myprofile';
		
	} else { //Authenticated user is viewing another user's profile
		
		$profile_type = (in_array('communityexpert', $profile_user->roles)) ? 'expert' : 'member';
			
	}
	
} else {
	
	$profile_type = (in_array('communityexpert', $profile_user->roles)) ? 'expert' : 'member';
	
}


/*
 * Content Section
 */

//Menu Tabs
if($profile_type == 'expert' || $profile_type == 'member') {

	
} elseif($profile_type == 'myprofile') {
	
	
}

//Include template parts,based on section being requested
if(isset($_GET['part'])) {
	
	switch($_GET['part']) {
		
		//Recent Activities
		case 'recent':

	
		break;
		
		//Q&A
		case 'qa':
			
		break;
		
		//Blog Posts
		case 'blog':
			
		break;
		
		//Buying Guides
		case 'guides':
			
		break;
	}

} else {
	//Recent Activities
	
	
}

get_template_part('parts/footer');