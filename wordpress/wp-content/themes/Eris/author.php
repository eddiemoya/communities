<?php

define(EXPERT_ROLE, 'expert');

//User_Profile class
require_once 'classes/communities_profile.php';

get_template_part('parts/header');

//Current User data
$profile_user = get_userdata(get_query_var('author'));

//User Profile object
$user_activities = new User_Profile($profile_user->ID);

//Current page's url, with querystring params removed
$author_url = get_author_posts_url( $user_id ) . str_replace("author_name=", "", $wp->query_string) . "/";

if ( !has_screen_name( $profile_user->ID )) {
    $author_url = home_url( '/' ) . '?' . $wp->query_string;
}


//No JS pagination, set page
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;

//Set profile_type
if(is_user_logged_in() && ($profile_user->ID == $current_user->ID)){
		
	//Authenticated User viewing own profile
	$profile_type = 'myprofile';
	
} else {
	
	//Viewing someone else's profile
	$profile_type = (in_array(EXPERT_ROLE, $profile_user->roles)) ? 'expert' : 'member';
	
}




if($profile_type != 'myprofile') {
	
	//Remove recent tab
	unset($user_activities->nav[0]);
	
}

/*var_dump($user_activities->nav);
exit;*/

$available_tabs = empty( $user_activities->nav ) ? array() : $user_activities->nav;


if(isset($_GET['post-type'])) {
	
	$type = empty($_GET['post-type']) ? null : $_GET['post-type'];
	$current_tab = ($_GET['post-type'] == 'aboutme') ? 'About Me' : 'Community Activity';
	$current_nav = $_GET['post-type'];
	$container_class = ($_GET['post-type'] == 'aboutme') ? '' : ' recent-activity';
	
} else {
	
	$default = (isset($available_tabs[0])) ? $available_tabs[0] : $available_tabs[1]; 
	
	$type = $default;
	$current_tab = 'Community Activity';
	$current_nav = $default;
	$container_class = ' recent-activity';	
	
}



?>
<section class="span<?php echo ($profile_type == 'myprofile' ? "12" : "8" ); ?> profile">

    <?php include('parts/profile_nav.php'); ?>

<?php 


?>
    <section class="content-container<?php echo $container_class; ?>">

       
	 	   
        <?php
      	//Comments
		if($type == 'answer' || $type == 'comment' || $type == '') {
			
			
			$activities = $user_activities->page($page)
											->get_user_comments_by_type($type)
											->comments;
											
			include('parts/profile-comments.php');
		}
		
		//Posts
		if($type == 'post' || $type == 'guides' || $type == 'question') {
			
			
			if($type == 'question') {
				
				$activities = $user_activities->page($page)
												->get_user_posts_by_type($type)
												->get_expert_answers()
												->posts;
												
					/*echo '<pre>';
					var_dump($activities);
					exit;*/					
			
			} else {
				
				$activities = $user_activities->page($page)
												->get_user_posts_by_type($type)
												->posts;
											
			}
											
											
			include('parts/profile-posts.php');
		}
		
		//Actions
		if($type == 'follow' || $type == 'upvote') {
			
			$activities = $user_activities->page($page)
											->get_actions($type)
											->activities;
											
			include('parts/profile-recent.php');
		}
		
		if($type == 'recent') {
			
			$activities = $user_activities->page($page)
											->get_recent_activities()
											->activities;
			
			include('parts/profile-recent.php');
		}
		
		if($type == 'aboutme') {
			
			if(class_exists('SSO_Profile')) {
				
				$sso_profile = new SSO_Profile();
				
				/*echo $profile_user->data->ID;
				exit;*/
				$guid = get_user_sso_guid($profile_user->data->ID);

				if($guid){
					
					$user_profile = $sso_profile->get(get_user_sso_guid($profile_user->data->ID));
				}
			}
			
			include('parts/profile-aboutme.php');
		}
	
       
        ?>
       
	 </ol>
     <script type="text/javascript">
     $(document).ready(function() {
         $(".expert-answers").hide();
         $(".answers-toggle").on('click', function () {
             $(this).next(".expert-answers").slideToggle("fast");
         });
         $('.upload-photo button').hide();
         $('.upload-photo #userphoto_image_file').hide();
         $(".upload-photo label").on('click', function () {
             $(".upload-photo button").show();
             $(".upload-photo #userphoto_image_file").show();
         });
     });
     </script>
	 
	 </section>
	 	
	 </section>
<?php 
	get_template_part('parts/footer');
?>