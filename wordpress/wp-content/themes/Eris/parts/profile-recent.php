
<?php if(! is_ajax()): ?>
<ol class="content-body result clearfix" id="profile-results">
<?php endif;

/*echo '<pre>';
var_dump($activities);
exit;*/

$activities_text = array(
  'question' => 'Asked this: ',
  'answer'   => 'Answered this: ',
  ''         => 'Commented this: ',
  'comment'  => 'Commented this: ',
  'post'     => 'Posted this: ',
  'guide'    => 'Posted this: '
);
							
$action_text = array(
  'follow' => 'Followed this: ',
  'upvote' => 'Liked this: '
);

foreach($activities as $activity):

	$activity_text = (array_key_exists($activity->action, $action_text)) ? $action_text[$activity->action] : $activities_text[$activity->type];
	
	$excerpt = '<article class="excerpt">' . (strlen( $activity->content ) > 200 ? substr( $activity->content, 0, 200 ) . "&#8230;" : $activity->content) . '</article>';
	            
?>
        <li class="clearfix">

            <div>
                <h3>
                    <span><?php echo $activity_text; ?></span>
                    <?php get_partial( 'parts/space_date_time', array( "timestamp" => strtotime($activity->date) ) ); ?>
                    <a href="<?php echo (in_array($activity->type, $user_activities->comment_types)) ? ((count($activity->post->category)) ? get_term_link($activity->post->category[0]) : null) : ((count($activity->category)) ? get_term_link($activity->category[0]) : null) ;?>" class="category"><?php echo (in_array($activity->type, $user_activities->comment_types)) ? ((count($activity->post->category)) ? $activity->post->category[0]->cat_name : 'Uncategorized') : ((count($activity->category)) ? $activity->category[0]->cat_name : 'Uncategorized') ;?></a>
                    <a href="<?php echo (in_array($activity->type, $user_activities->comment_types)) ? get_permalink($activity->post->ID) : get_permalink($activity->ID) ;?>"><?php echo (in_array($activity->type, $user_activities->comment_types)) ? sanitize_text($activity->post->post_title) : sanitize_text($activity->title);?></a>
                </h3>
                <?php echo (in_array($activity->type, $user_activities->comment_types)) ? sanitize_text($excerpt) : null; ?>
            </div>
        </li>
 <?php endforeach; ?>
 
<?php if(! is_ajax()):?>
 </ol>
<?php endif;?>
 
<input type="hidden" id="next-page" value="<?php echo $user_activities->next_page; ?>" />

<?php if(! is_ajax()):?>
    <input type="hidden" id="type" value="<?php echo $type;?>" />
    <input type="hidden" id="uid" value="<?php echo $profile_user->data->ID; ?>" />
<?php endif;?>

<noscript>
    <?php if($user_activities->prev_page):?>
        <a href="<?php echo $url_no_qs . '?post-type=' . $type . '&page=' . $user_activities->prev_page; ?>" id="page-prev">&lt; Previous</a>
    <?php endif;?>
    &nbsp;&nbsp;
</noscript>

<?php if($user_activities->next_page):?>
    <a href="<?php echo $url_no_qs . '?post-type=' . $type . '&page=' . $user_activities->next_page; ?>" id="page-more">More &gt;</a>
<?php endif;
