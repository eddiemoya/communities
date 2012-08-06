<?php
//var_dump($activities);

foreach($activities as $activity):
            
?>
	 <li class="clearfix">
	          <div>
	            <h3>
	              <?php //echo $start; ?>
	              <time class="content-date" datetime="<?php echo date( "Y-m-d", strtotime($activity->post_date)); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", strtotime($activity->post_date)); ?></time>
	              <a href="#" class="category"><?php echo (count($activity->categories)) ? $activity->categories[0]->cat_name : 'Uncategorized'; ?></a>
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
 	
 	<?php if($user_activities->next_page):?>
	 	<a href="" id="page-more">More...</a>
 	<?php endif;?>
      