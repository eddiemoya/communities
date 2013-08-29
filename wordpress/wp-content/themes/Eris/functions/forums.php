<?php

function forums_get_last_activity($forum_id) {
	
	$activity_id = bbp_get_forum_last_active_id($forum_id);
	
	$activity = get_post($activity_id);
	
	if($activity) {
		
		$title = ($activity->post_type == 'reply') ? get_post($activity->post_parent)->post_title : $activity->post_title;
		$activity_link = ($activity->post_type == 'topic') ? get_permalink($activity->ID) : get_permalink(get_post($activity->post_parent)->ID) . '#post-'. $activity->ID;
		$author_profile_url = get_profile_url($activity->post_author);
		$author_name = return_screenname($activity->post_author);
		$post_date = date('m-d-Y, h:i A', strtotime($activity->post_date));
		
		return array('title'		=> $title,
					'link'			=> $activity_link,
					'screen_name'	=> $author_name,
					'profile_link'	=> $author_profile_url,
					'date'			=> $post_date);
		
	}
	
	return null;
	
}

function forums_last_activity($forum_id) {
	
	$latest = forums_get_last_activity($forum_id);
	
	if($latest) {
		
		ob_start();
		?>
		 <ul class="forums-last-activity">
		 	<li><a href="<?php echo $latest['link']?>"><?php echo $latest['title'];?></a></li>
		 	<li>By <a href="<?php echo $latest['profile_link'];?>"><?php echo $latest['screen_name'];?></a></li>
		 	<li><?php echo $latest['date'];?></li>
		 </ul>
		<?php 
		echo ob_get_clean();
	}
}

function forums_paginate() {
	
}