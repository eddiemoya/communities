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

function topics_last_activity($topic_id) {
	
	$last_id = bbp_get_topic_last_active_id($topic_id);
	
	if($last_id) {
		
		$reply = get_post($last_id);
		ob_start();
		?>
			<span class="bbp-topic-freshness-author">
				By <a href="<?php echo get_profile_url($reply->post_author); ?> "><?php get_screenname($reply->post_author);?></a>
			</span>

			<p class="bbp-topic-meta">
				 <?php echo date('m-d-Y, h:i A', strtotime($reply->post_date)); ?>
			</p>
		<?php 
		
		echo ob_get_clean();
	}
	
}

function reply_author_date($id) {
	
	$reply = get_post($id);
	
	if($reply) {
		
		ob_start();
		?>
		<div class="forums-post-author-date">
		 	By <a href="<?php echo get_profile_url($reply->post_author);?>"><?php get_screenname($reply->post_author);?></a> 
		 	posted <?php echo ($reply->post_date == 0) ? date('m-d-Y, h:i A', strtotime($reply->post_modified)) : date('m-d-Y, h:i A', strtotime($reply->post_date));?>
		</div>
		<?php 
		
		echo ob_get_clean();
	}
}

function topic_reply_link($target=null) {
	
	ob_start();
	?>
	<div id="forums-topic-reply-link">
		<a href="<?php if($target) echo $target;?>">Reply</a>
	</div>
	<?php
	echo ob_get_clean(); 
}

function bbp_forums_pagination($forum_id) {
	
	echo bbp_get_forums_pagination(array('forum_id' => $forum_id));
}

function bbp_get_forums_pagination( $args = '' ) {
	
		// Parse arguments against default values
		$r = bbp_parse_args( $args, array(
			'before'   => '<span class="bbp-forum-pagination">',
			'after'    => '</span>',
			'forum_id' => 0
		));

		//Get base URL
		$base = add_query_arg('paged', '%#%', (strpos($_SERVER['REQUEST_URI'], '?') !== false) ? reset(explode($_SERVER['REQUEST_URI'], '?')) : $_SERVER['REQUEST_URI'] );

		// Get total 
		$total = forums_count($r['forum_id']);

		// Pagination settings
		$pagination = array(
			'base'      => $base,
			'format'    => '',
			'total'     => ceil( (int) $total / (int) get_option('_bbp_forums_per_page', 15) ), 
			'current'   => (get_query_var('paged')) ? get_query_var('paged') : 1,
			'prev_next' => true,
			'prev_text' => '&#8592;',
			'next_text' => '&#8594;',
			'mid_size'  => 2,
			'end_size'  => 3
		);

		// Add pagination to query object
		$pagination_links = paginate_links( $pagination );
		if ( !empty( $pagination_links ) ) {

			$pagination_links = str_replace( '&#038;paged=1', '', $pagination_links );

			// Add before and after to pagination links
			$pagination_links = $r['before'] . $pagination_links . $r['after'];
		}

		return apply_filters( 'bbp_get_topic_pagination', $pagination_links, $args );
}


function forums_count($forum_id) {
	
	return count(get_posts(array('post_type'		=> 'forum',
								'post_status'		=> 'publish',
								'posts_per_page'	=> -1,
								'post_parent'		=> $forum_id)));
	
}



//Breadcrumbs -- Remove 'Home' from breadcrumbs
add_filter('bbp_breadcrumbs', 'comm_forums_breadcrumbs');

function comm_forums_breadcrumbs($crumbs){
	
	unset($crumbs[0]);
	return $crumbs;
}

//Show lead topic in topics replies first
add_filter( 'bbp_show_lead_topic', '__return_true' );

/**
 * ADMIN SECTION
 */


//Adding forums per page field/setting

add_filter('bbp_admin_get_settings_fields', 'add_forums_per_page');

function add_forums_per_page($settings_fields) {
	
	
	$settings_fields['bbp_settings_per_page']['_bbp_forums_per_page'] = array('title' 				=> 'Forums',
																				'callback'			=> 'bbp_admin_setting_callback_forums_per_page',
																				'sanitize_callback'	=> 'intval',
																				'args'				=> array());
	return $settings_fields;
}

function bbp_admin_setting_callback_forums_per_page() {
	?>
	<input name="_bbp_forums_per_page" type="number" min="1" step="1" id="_bbp_forums_per_page" value="<?php bbp_form_option( '_bbp_forums_per_page', '15' ); ?>" class="small-text"<?php bbp_maybe_admin_setting_disabled( '_bbp_forums_per_page' ); ?> />
	<label for="_bbp_forums_per_page"><?php _e( 'per page', 'bbpress' ); ?></label>
	<?php 
}

