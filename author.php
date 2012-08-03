<?
error_reporting(E_ALL);
ini_set('display_errors', true);

//User_Profile class
require_once 'classes/communities_profile.php';

get_template_part('parts/header');

//Current page's url, with querystring params removed
$url_no_qs = get_site_url() . ((strpos($_SERVER['REQUEST_URI'], '?') !== false) ? substr($_SERVER['REQUEST_URI'], null, strpos($_SERVER['REQUEST_URI'], '?')): $_SERVER['REQUEST_URI']);


//Current User data
$profile_user = get_userdata(get_query_var('author'));

//User Profile object
$user_activities = new User_Profile($profile_user->data->ID);



//Set profile_type
if(is_user_logged_in() && ($profile_user->data->ID == $current_user->data->ID)){
		
	//Authenticated User viewing own profile
	$profile_type = 'myprofile';
	
} else {
	
	//Viewing someone else's profile
	$profile_type = (in_array('communityexpert', $profile_user->roles)) ? 'expert' : 'member';
	
}

//Available Tabs
switch($profile_type) {
	
	case 'myprofile' || 'member':
		
		$available_tabs = array('recent',
								'question',
								'answer',
								'comment',
								'follow',
								'votes'
								);
	break;
	
	
	case 'expert':
		
		$available_tabs = array('recent',
								'question',
								'answer',
								'comment',
								'follow',
								'votes',
								'guides',
								'posts'
								);
		
	break;
	
	
}


if(isset($_GET['post-type'])) {
	
	$type = $_GET['post-type'];
	$current_tab = ($_GET['post-type'] == 'aboutme') ? 'About Me' : 'Community Activity';
	$current_nav = $_GET['post-type'];
	
} else {
	//Recent Activities
	
	//$data = $user_activities;
	$type = 'recent';
	$current_tab = 'Community Activity';
	$current_nav = 'recent';	
}



?>
 <section class="span<?php echo ($profile_type == 'myprofile' ? "12" : "8" ); ?> profile">

    <?php include('parts/profile_nav.php'); ?>

<?php 


?>
	 <section class="content-container recent-activity">
	 
	 	   <ol class="content-body result clearfix">
	 	   
        <?php
      	//Comments
		if($type == 'answer' || $type == 'comment') {
			
			$activities = $user_activities->get_user_comments_by_type($type)
											->comments;
											
			include('parts/profile-comments.php');
		}
		
		//Posts
		if($type == 'posts' || $type == 'guides' || $type == 'question') {
			
			$activities = $user_activities->get_user_posts_by_type($type)
											->posts;
											
			include('parts/profile-posts.php');
		}
		
		//Actions
		if($type == 'follow' || $type == 'votes') {
			
			include('parts/profile-actions.php');
		}
		
		if($type == 'recent') {
			
			include('parts/profile-recent.php');
		}
	
        
          /*foreach ( $activities as $activity ):
            # logic for showing the badge
            $a_start = array();
            $badge = '';
            /*if ( $is_widget ) {
              $a_start[] = '<a href="#">' . $activity["author"] . '</a>';
              $badge = '<div class="badge span3"><img src="' . get_template_directory_uri() . '/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" /></div>';
            }
          
            # logic for showing the action.
            $a_start[] = in_array( $current_nav, array('recent') ) ? $actions[ $activity["action"] ] . ' this:' : NULL;
            $start = !empty( $a_start ) ? '<span>' . implode( $a_start, ' ' ) . '</span>' : '';
          
            # logic for showing an excerpt of the body
            $excerpt = '';
            if ( ( in_array( $current_nav, array( 1, 3, 4, 5, 6 ) ) ) && ( in_array( $activity["action"], array( 2, 3, 5 ) ) ) ) {
              $excerpt = '<article class="excerpt">';
              $excerpt .= strlen( $activity["excerpt"] ) > 180 ? substr( $activity["excerpt"], 0, 180 ) . "&#8230;" : $activity["excerpt"];
              $excerpt .= '</article>';
            }*/
        ?>
       
	 </ol>
	 </section>
	 
	 </section>
<?php 
	get_template_part('parts/footer');
?>