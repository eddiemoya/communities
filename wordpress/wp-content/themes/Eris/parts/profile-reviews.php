<?php if( !is_ajax() ): ?>
<ol class="content-body result reviews clearfix" id="profile-results">
<?php endif; ?>

<?php
    foreach($activities as $activity):
    
    $editlink = ($profile_type == 'myprofile') ? "<a href=\"{$activity->edit_link}\" class=\"edit-review right\">Edit</a>" : '';
?>
    <li class="clearfix">
      <div class="span2">
        <img src="<?php echo $activity->product_data->image;?>" />
      </div>
      <div class="span10">
        <h4><a href="<?php echo 'http://www.' . $activity->origin_site  . '/' . $activity->product_data->product_uri; ?>"><?php echo $activity->product_data->description;?></a></h4>
        <div class="rating">
          <div class="rating-stars">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/stars1.png" alt="<?php echo $activity->attribute_rating[0]->value; ?>/5 stars" />
          </div>
          <div class="rating-bar" style="width: <?php echo ((int)$activity->attribute_rating[0]->value / 5) * 100 ?>%;">&nbsp;</div>
        </div>
        <div class="review-text">
          <a href="<?php echo $activity->all_reviews_link;?>"><?php echo $activity->summary;?></a>
          <?php echo $editlink;?>
        </div>
      </div>
    </li>
<?php
    endforeach;
?>

<?php if( !is_ajax() ): ?>
</ol>

<input type="hidden" id="type" value="<?php echo $type;?>" />
<input type="hidden" id="uid" value="<?php echo $profile_user->data->ID; ?>" />
<?php endif;?>
 
<input type="hidden" id="next-page" value="<?php echo $user_activities->next_page; ?>" />

<noscript>
<?php if( $user_activities->prev_page ): ?>
    <a href="<?php echo $url_no_qs . '?post-type=' . $type . '&page=' . $user_activities->prev_page; ?>" id="page-prev">&lt; Previous</a>
<?php endif; ?>
&nbsp;&nbsp;
</noscript>

<?php if( $user_activities->next_page ): ?>
    <a href="<?php echo $url_no_qs . '?post-type=' . $type . '&page=' . $user_activities->next_page; ?>" id="page-more">More &gt;</a>
<?php endif; ?>
