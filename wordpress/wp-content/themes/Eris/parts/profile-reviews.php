<?php if( !is_ajax() ): ?>
<ol class="content-body result reviews clearfix" id="profile-results">
<?php endif; ?>

<?php
    foreach($activities as $activity):
?>
    <li class="clearfix">
      <div class="span2">
        <img src="<?php echo $activity["image_path"] ?>" />
      </div>
      <div class="span10">
        <h4><a href="#"><?php echo $activity["product"] ?></a></h4>
        <div class="rating">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/stars.png" alt="<?php echo $activity["rating"] ?>/5 stars" width="94" height="15" />
          <div class="rating-bar" style="width: <?php echo ($activity["rating"] / 5) * 100 ?>%;">&nbsp;</div>
        </div>
        <div class="review-text"><?php echo $activity["title"] ?></div>
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
