<?php
    $removed_text = "<p>This {$comment_type} has been removed.</p>";
    $brand = theme_option("brand") ? theme_option("brand") : 'sears';
?>
<li class="comment clearfix<?php echo $container_class; ?>" id="<?php echo $comment_type.'-reply-'.$comment->comment_ID ?>">
    <?php 
        if($comment->comment_approved == 1) :
            get_partial( 'parts/crest', array( "user_id" => $comment->user_id, "width" => "span2" ) );
        endif;
    ?>
    <div class="span10">
        <article class="content-container">
            <section class="content-body clearfix">
                <div class="content-details clearfix">
                    <time class="content-date" pubdate datetime="<?php echo date( "Y-m-d", $date ); ?>">
                        <?php echo date( "F j, Y", $date ); ?>
                        <span class="time-stamp"><?php echo date( "g:ia", $date ); ?></span>
                    </time>
                </div><?php //print_pre($comment); ?>
                <?php if ($parent_author != "") : ?>
                    <p class="responseTo">In response to <?php echo $parent_author; ?></p>
                <?php endif; ?>
                <?php echo ($comment->comment_approved == 1) ? wpautop($comment->comment_content) : $removed_text; ?>
                <?php if(is_user_logged_in()) { ?>
                <form class="actions clearfix" id="comment-actions-<?php echo($comment->comment_ID); ?>" method="post">
					<?php get_partial('parts/flag', array('id' => $comment->comment_ID, 'type' => "comments", 'sub_type' => "comment", 'actions' => $comment->actions, 'brand' => $brand)); ?>
					<?php get_partial('parts/vote', array('id' => $comment->comment_ID, 'type' => "comments", 'sub_type' => 'comment', 'actions' => $comment->actions, 'logged_in' => is_user_logged_in())); ?>
					<?php //get_partial('parts/follow', array('id' => $comment->comment_ID, 'type' => "comments")); ?>
					<?php //get_partial('parts/share', array('id' => $comment->comment_ID, 'type' => "comments")); ?>
                </form>
                <?php } ?>
            </section>
        </article> <!-- END ARTICLE CONTENT CONTAINER -->
    
        <div class="clearfix"></div>
    </div> <!-- END SPAN10 -->
    <?php 
        if ($comment->children != "") :
            ?><ol class="children"><?php
                display_child_comments($comment); 
            ?></ol><?php
        endif;
    ?>
</li>