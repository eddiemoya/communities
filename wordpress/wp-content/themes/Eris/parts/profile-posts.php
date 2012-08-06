<?php
//var_dump($activities);

foreach($activities as $activity):

//$excerpt = '<article class="excerpt">' . (strlen( $activity->comment_content ) > 180 ? substr( $activity->comment_content, 0, 180 ) . "&#8230;" : $activity->comment_content) . '</article>';
            
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
      