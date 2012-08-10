<?php
//var_dump($activities);

foreach($activities as $activity):
            
?>
	 <li class="clearfix">
	          <div>
	            <h3>
	              <?php //echo $start; ?>
	              <time class="content-date" datetime="<?php echo date( "Y-m-d", strtotime($activity->post_date)); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", strtotime($activity->post_date)); ?></time>
	              <a href="<?php  echo (count($activity->categories)) ? get_term_link($activity->categories[0]) : null;?>" class="category"><?php echo (count($activity->categories)) ? $activity->categories[0]->cat_name : 'Uncategorized'; ?></a>
	              <a href="<?php echo get_permalink($activity->ID);?>"><?php echo $activity->post_title; ?></a>
	            </h3>
	            <?php //echo $excerpt; ?>
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
      