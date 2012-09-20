<?php if(! is_ajax()):?>
 <ol class="content-body result clearfix" id="profile-results">
 <?php endif;?>

<?php
//var_dump($activities);
foreach($activities as $activity):
            
?>
<li class="clearfix">
    <h3>
        <?php get_partial( 'parts/space_date_time', array( "timestamp" => strtotime( $activity->post_date ) ) ); ?>
        <a href="<?php  echo (count($activity->categories)) ? get_term_link($activity->categories[0]) : null;?>" class="category"><?php echo (count($activity->categories)) ? $activity->categories[0]->cat_name : 'Uncategorized'; ?></a>
        <a href="<?php echo get_permalink($activity->ID);?>"><?php echo $activity->post_title; ?></a>
    </h3>
<?php 
        // Expert answers
        if( $type == 'question' && !empty( $activity->expert_answers ) && $profile_type == 'myprofile' ):
?>
    <span class="answers-toggle link-emulator">Expert Answers (<?php echo count($activity->expert_answers) ?>)</span>
    <ol class="expert-answers clearfix">
<?php
            // expert answers are an array of comment objects
            foreach($activity->expert_answers as $answer):
?>
    <li class="clearfix expert">
        <?php get_partial( 'parts/crest', array( "user_id" => $answer->user_id, "width" => "span2") ); ?>
        <div class="span10">
            <?php get_partial( 'parts/space_date_time', array( "timestamp" => strtotime( $answer->comment_date ) ) ); ?>
            <p class="content-excerpt"><?php echo $answer->comment_content; ?></p>
        </div>
    </li>

<?php
            endforeach;
?>
    </ol>
<?php
        endif;
?>
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
<?php endif;?>

      