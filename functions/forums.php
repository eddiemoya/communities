<?php

/**
 * Forums (bbPress) Functions
 * 
 * @author Dan Crimmins [dcrimmi@searshc.com]
 * @since 09-18-2013
 */


 



/**
 * Retrieves a forums last activity.
 * 
 * @param int $forum_id
 * @return mixed [array | NULL]
 * 
 * @uses bbp_get_forum_last_active_id()
 */
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

/**
 * Template tag - prints the last activity for a given forum.
 * 
 * @param int $forum_id
 * @uses forums_get_last_activity()
 */
function forums_last_activity($forum_id) {
	
	$latest = forums_get_last_activity($forum_id);
	
	if($latest) {
		
		ob_start();
		?>
		 <ul class="forums-last-activity">
		 	<li><a href="<?php echo $latest['link']?>"><?php echo $latest['title'];?></a></li>
		 	<li class="forum-activity-small">By <a href="<?php echo $latest['profile_link'];?>"><?php echo $latest['screen_name'];?></a></li>
		 	<li class="forum-activity-small"><?php echo $latest['date'];?></li>
		 </ul>
		<?php 
		echo ob_get_clean();
	}
}

/**
 * 
 * Template Tag - prints the last activity info for a give topic.
 * 
 * @param int $topic_id
 * @uses bbp_get_topic_last_active_id()
 */
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

/**
 *  Template tag - prints the by-line for replies
 *
 * @param int $id - the reply post ID
 */
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

/**
 * Template tag - prints topic reply link
 * @param string $target - the target url the link uses
 */
function topic_reply_link($target=null) {
	
	ob_start();
	?>
	<div id="forums-topic-reply-link">
		<a href="<?php if($target) echo $target;?>" <?php if(! is_user_logged_in()):?> shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}"<?php endif;?>>Reply</a>
	</div>
	<?php
	echo ob_get_clean(); 
}

/**
 * Template tag used to print forum pagination.
 * 
 * @param int $forum_id
 * @uses bbp_get_forums_pagination()
 */
function bbp_forums_pagination($forum_id) {
	
	echo bbp_get_forums_pagination(array('forum_id' => $forum_id));
}

/**
 * Outputs pagination for forums archive.
 * 
 * @param array $args
 * @return string - forum pagination HTML
 */
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

/**
 * Template Tag - prints the content of a forum
 * 
 * @param int $forum_id [optional]
 */
function forum_description($forum_id=null) {
	
	$p = ($forum_id) ? get_post($forum_id) : get_queried_object();
	
	if($p) echo $p->post_content;
}

/**
 * Outputs breadcumb style header text used on forum pages.
 * 
 * @param void
 * @return string - the header text
 */
function header_breadcrumbs() {
	
	$p = get_queried_object();
	
	if($p) {
		
		//Top-level forum
		if($p->post_type == 'forum' && ! count($p->ancestors)) {
			
			$out = $p->post_title;
			
		} elseif (($p->post_type == 'forum' && count($p->ancestors) > 0) ||
					$p->post_type == 'topic') { //Subforum or topic
		
			$forum1 = get_post($p->ancestors[0]);
			$forum2 = get_post($p->ancestors[1]);
			
			if(count($forum1->ancestors) > 0) {
				
				$forum = $forum2->post_title; 
				$subforum = $forum1->post_title;
			
			} else {
				
				$forum = $forum1->post_title;
				$subforum = $forum2->post_title;
			}
				
			$out = $forum . ' : ' . $subforum;
		}	
				
		echo $out;
	}
}

/**
 * Checks whether a forum has subforums
 * 
 * @param int $id -- post ID of parent forum
 * @return bool
 */
function has_subforums($id=null) {
	
	$id = ($id) ? $id : get_queried_object_id();
	
	$subs = get_posts(array('post_type'			=> 'forum',
							'post_status'		=> 'publish',
							'post_parent'		=> $id,
							'posts_per_page'	=> -1));
	
	return (count($subs)) ? true : false;
}

//Sanitize UGC on replies/threads
add_action('bbp_new_reply_pre_insert', 'forums_sanitize_content');
add_action('bbp_new_topic_pre_insert', 'forums_sanitize_content');

function forums_sanitize_content($content) {
	
	if(function_exists('sanitize_text')) {
		
		$content['post_title'] = sanitize_text($content['post_title']);
		$content['post_content'] = sanitize_text($content['post_content']);
	}
	
	return $content;
}


//Breadcrumbs -- Remove 'Home' from breadcrumbs
add_filter('bbp_breadcrumbs', 'comm_forums_breadcrumbs');

function comm_forums_breadcrumbs($crumbs){
	
	unset($crumbs[0]);
	return $crumbs;
}


//Show lead topic in topics replies first
add_filter( 'bbp_show_lead_topic', '__return_true' );




//Customized bbp_list_forums() -- need to remove forums pagination
function comm_bbp_list_forums( $args = '' ) {

	// Define used variables
	$output = $sub_forums = $topic_count = $reply_count = $counts = '';
	$i = 0;
	$count = array();

	// Parse arguments against default values
	$r = bbp_parse_args( $args, array(
		'before'            => '<ul class="bbp-forums-list">',
		'after'             => '</ul>',
		'link_before'       => '<li class="bbp-forum">',
		'link_after'        => '</li>',
		'count_before'      => ' (',
		'count_after'       => ')',
		'count_sep'         => ', ',
		'separator'         => ', ',
		'forum_id'          => '',
		'show_topic_count'  => true,
		'show_reply_count'  => true,
	), 'list_forums' );

	// Loop through forums and create a list
	$sub_forums = comm_bbp_forum_get_subforums( $r['forum_id'] );
	
	if ( !empty( $sub_forums ) ) {

		// Total count (for separator)
		$total_subs = count( $sub_forums );
		foreach ( $sub_forums as $sub_forum ) {
			$i++; // Separator count

			// Get forum details
			$count     = array();
			$show_sep  = $total_subs > $i ? $r['separator'] : '';
			$permalink = bbp_get_forum_permalink( $sub_forum->ID );
			$title     = bbp_get_forum_title( $sub_forum->ID );

			// Show topic count
			if ( !empty( $r['show_topic_count'] ) && !bbp_is_forum_category( $sub_forum->ID ) ) {
				$count['topic'] = bbp_get_forum_topic_count( $sub_forum->ID );
			}

			// Show reply count
			if ( !empty( $r['show_reply_count'] ) && !bbp_is_forum_category( $sub_forum->ID ) ) {
				$count['reply'] = bbp_get_forum_reply_count( $sub_forum->ID );
			}

			// Counts to show
			if ( !empty( $count ) ) {
				$counts = $r['count_before'] . implode( $r['count_sep'], $count ) . $r['count_after'];
			}

			// Build this sub forums link
			$output .= $r['link_before'] . '<a href="' . $permalink . '" class="bbp-forum-link">' . $title . $counts . '</a>' . $show_sep . $r['link_after'];
		}

		// Output the list
		echo apply_filters( 'bbp_list_forums', $r['before'] . $output . $r['after'], $r );
	}
}

//Customized bbp_forum_get_subforums() -- removing forums pagination
function comm_bbp_forum_get_subforums( $args = '' ) {

	// Use passed integer as post_parent
	if ( is_numeric( $args ) )
		$args = array( 'post_parent' => $args );

	// Setup possible post__not_in array
	$post_stati[] = bbp_get_public_status_id();

	// Super admin get whitelisted post statuses
	if ( bbp_is_user_keymaster() ) {
		$post_stati = array( bbp_get_public_status_id(), bbp_get_private_status_id(), bbp_get_hidden_status_id() );

	// Not a keymaster, so check caps
	} else {

		// Check if user can read private forums
		if ( current_user_can( 'read_private_forums' ) ) {
			$post_stati[] = bbp_get_private_status_id();
		}

		// Check if user can read hidden forums
		if ( current_user_can( 'read_hidden_forums' ) ) {
			$post_stati[] = bbp_get_hidden_status_id();
		}
	}

	// Parse arguments against default values
	$r = bbp_parse_args( $args, array(
		'post_parent'         => 0,
		'post_type'           => bbp_get_forum_post_type(),
		'post_status'         => implode( ',', $post_stati ),
		'posts_per_page'      => -1,
		'orderby'             => 'menu_order',
		'order'               => 'ASC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true
	));
	$r['post_parent'] = bbp_get_forum_id( $r['post_parent'] );

	// Create a new query for the subforums
	$get_posts = new WP_Query();

	// No forum passed
	$sub_forums = !empty( $r['post_parent'] ) ? $get_posts->query( $r ) : array();

	return (array) apply_filters( 'bbp_forum_get_subforums', $sub_forums, $r );
}

/**
 * SEARCH RESULTS CSS CLASS FUNCTIONS
 */

// Remove odd/even class from forum, topic, reply

add_filter('bbp_get_forum_class', 'forums_search_remove_odd_even_class', 10, 2);
add_filter('bbp_get_topic_class', 'forums_search_remove_odd_even_class', 10, 2);
add_filter('bbp_get_reply_class', 'forums_search_remove_odd_even_class', 10, 2);

function forums_search_remove_odd_even_class($classes, $id) {
	
	if(bbp_is_search()) { //Only for search results
		
		$odd = array_search('odd', $classes);
		$even = array_search('even', $classes);
		
		$i = ($odd !== false) ? $odd : $even;
		
		unset($classes[$i]);
		
		return $classes;
	}
	
	return $classes;
}


$bbp_search_item_index = 0; //Global search results list item index


//Get the odd/even class for search results
function bbp_get_search_results_class($id = 0, $classes = array()) {
	
	global $bbp_search_item_index;
	
	$type = ((bbpress()->current_forum_id) ? 'forum' :
				((bbpress()->current_topic_id) ? 'topic' :
				((bbpress()->current_reply_id) ? 'reply' : false)));
	
	switch($type) {
		
		case 'forum':
			
			$class = bbp_get_forum_class( $id, $classes );
			
			break;
			
		case 'topic':
			
			$class = bbp_get_topic_class($id, $classes);
			
			break;
			
		case 'reply':
			
			$class = bbp_get_reply_class($id, $classes);
			
			break;
			
		default:
			
			$class = '';
			
			break;
	}
	
	//Increment the counter, and assign the odd or even class; add to class string
	$class_odd_even = ((int) ++$bbp_search_item_index % 2) ? 'odd' : 'even';
	$class = substr_replace($class, ' ' . $class_odd_even . '"', (strlen($class) - 1));
	
	return $class;	
}

//prints odd/even class for search results
function bbp_search_results_class($id=0, $classes=array()) {
	
	echo bbp_get_search_results_class($id, $classes);
}


//Truncate breadcrumb items to 20 chars
add_filter('bbp_breadcrumbs', 'forums_truncate_breadcrumbs');

function forums_truncate_breadcrumbs($crumbs) {
	
	array_walk($crumbs, 'forums_breadcrumbs_cb');
	return $crumbs;
}

//Callback function used by forums_truncate_breadcrumbs()
function forums_breadcrumbs_cb(&$item) {
	
	if(function_exists('truncated_text')) {
			
		$end_tag_pos = strpos($item, '>');
		
		if($end_tag_pos !== false) {
			
			//Get begining tag
			$begin_tag_close = (int) $end_tag_pos + 1;
			$start_tag = substr($item, 0, $begin_tag_close); //beginning tag
			
			//Get Text
			$text_w_end = substr($item, $begin_tag_close);
			$text_end = strpos($text_w_end, '</');
			$text = substr($text_w_end, 0, $text_end); //text
			
			//Get closing tag - could be an anchor or span
			$end_tag = (strpos($item, '<a') !== false) ? substr($item, -4) 
														: substr($item, -7); //closing tag
			
			$item = $start_tag . truncated_text($text, 20) . $end_tag;
		
		}
	}
}

/**
 * ADMIN SECTION ADDITIONS
 */

//Change Topic labels display to 'Thread'
add_filter('bbp_register_topic_post_type', 'forums_topics_labels');

function forums_topics_labels($args) {
	
	$args['labels'] = array('name'			 	=> 'Threads',
							'menu_name'			=> 'Threads',
							'singular_name'		=> 'Thread',
							'all_items'			=> 'All Threads',
							'add_new'			=> 'New Thread',
							'add_new_item'		=> 'Create New Thread',
							'edit'				=> 'Edit',
							'edit_item'			=> 'Edit Thread',
							'new_item'			=> 'New Thread',
							'view'				=> 'View Thread',
							'view_item'			=> 'View Thread',
							'search_items'		=> 'Search Threads',
							'not_found'			=> 'No threads found',
							'not found_in_trash'=> 'No threads found in Trash',
							'parent_item_colon' => 'Forum:');

	return $args;
}


//Change field names for topics (Threads) admin headings
add_filter('bbp_admin_topics_column_headers', 'forums_topics_headings');

function forums_topics_headings($cols) {
	
	$cols['title'] = __('Threads');
	
	return $cols;
}

//Change second column on Forums admin page from Topics to Threads
add_filter('bbp_admin_forums_column_headers', 'forums_forum_headings');

function forums_forum_headings($cols) {
	
	unset($cols['bbp_forum_freshness']); //Remove freshness col, going to replace...
	
	$cols['bbp_forum_fresh_mod'] = __( 'Freshness', 'bbpress' ); //Custom Freshness column
	
	$cols['bbp_forum_topic_count'] = __( 'Threads',    'bbpress' );
	
	return $cols;
}

//Change output used in forum Freshness column
add_action('bbp_admin_forums_column_data', 'forums_forum_freshness_output', 10, 2);

function forums_forum_freshness_output($column=null, $forum_id=null) {
	
	$last_active = bbp_get_forum_last_active_time( $forum_id, false );
	
	if ( !empty( $last_active ) )
		echo $last_active;
	else
		_e( 'No Threads', 'bbpress' );
	
}


/**
 * Forums Per page setting, added to Per Page section
 */

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

/**
 * Page Headings Section
 */

//Add cap for this section, so it appears in forum settings
add_filter('bbp_map_settings_meta_caps', 'add_cap_bbp_forum_headings');

function add_cap_bbp_forum_headings($caps, $cap = NULL, $user_id = NULL, $args = NULL) {
	
	if($cap == 'bbp_forum_headings') {
		
		return array( bbpress()->admin->minimum_capability );
	}
	
	return $caps;
}

//Add Froum Headings section to settings page
add_filter('bbp_admin_get_settings_sections', 'add_forums_settings_headings_section');

function add_forums_settings_headings_section($sections) {
	
	$sections['bbp_forum_headings'] = array('title' => 'Forum Page Headings',
											'callback' => 'bbp_admin_setting_callback_headings_section',
											'page'	=> 'bbpress');
	
	return $sections;
}

function bbp_admin_setting_callback_headings_section() {
	?>
	<p><?php _e( 'Add headings to forum pages.', 'bbpress' ); ?></p>
	
	<?php 
}

//Add fields
add_filter('bbp_admin_get_settings_fields', 'bbp_admin_setting_callback_forum_archive_heading');

function bbp_admin_setting_callback_forum_archive_heading($fields) {
	
	$fields['bbp_forum_headings']['_bbp_forum_archive_heading'] = array('title' => 'Forums Archive Heading',
																		'callback'	=> 'bbp_admin_setting_callback_forum_archive_headings',
																		'sanitize_callback'	=> 'sanitize_text_field',
																		'args' => array());
	
	$fields['bbp_forum_headings']['_bbp_forum_archive_subheading'] = array('title' => 'Forums Archive Sub-heading',
																		'callback'	=> 'bbp_admin_setting_callback_forum_archive_subheadings',
																		'sanitize_callback'	=> 'sanitize_text_field',
																		'args' => array());
	return $fields;
}


//The fields callbacks
function bbp_admin_setting_callback_forum_archive_headings() {
	
	?>
	<input name="_bbp_forum_archive_heading"  step="1" id="_bbp_forum_archive_heading" value="<?php bbp_form_option( '_bbp_forum_archive_heading', 'Forums Archive Heading Text' ); ?>" class="regular-text"<?php bbp_maybe_admin_setting_disabled( '_bbp_forum_archive_heading' ); ?> />
	<?php 
}

function bbp_admin_setting_callback_forum_archive_subheadings() {
	
	?>
	<input name="_bbp_forum_archive_subheading"  step="1" id="_bbp_forum_archive_subheading" value="<?php bbp_form_option( '_bbp_forum_archive_subheading', 'Forums Archive Sub-heading Text' ); ?>" class="regular-text"<?php bbp_maybe_admin_setting_disabled( '_bbp_forum_archive_subheading' ); ?> />
	<?php 
}

