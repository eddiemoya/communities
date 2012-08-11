<?php

//var_dump($activities); 

foreach($activities as $activity):

$excerpt = '<article class="excerpt">' . (strlen( $activity->comment_content ) > 180 ? substr( $activity->comment_content, 0, 180 ) . "&#8230;" : $activity->comment_content) . '</article>';
            
?>
	 <li class="clearfix">
	          <?php //echo $badge; ?>
	          <div>
	            <h3>
	              <?php //echo $start; ?>
	              <time class="content-date" datetime="<?php echo date( "Y-m-d", strtotime($activity->comment_date)); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", strtotime($activity->comment_date)); ?></time>
	              <a href="<?php echo (count($activity->post->categories)) ? get_term_link($activity->post->categories[0]) : null; ?>" class="category"><?php echo (count($activity->post->categories)) ? $activity->post->categories[0]->cat_name : 'Uncategorized'; ?></a>
	              <a href="<?php echo get_permalink($activity->post->ID);?>"><?php echo $activity->post->post_title; ?></a>
	            </h3>
	            <?php echo $excerpt; ?>
	          </div>
	        </li>
 <?php endforeach; ?>
 
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
 	<?php endif;?>
      
      
