<?php
    global $post;
    
    $removed_text = "<p>This {$comment_type} has been removed.</p>";
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
                </div>
                <?php if ($parent_author != "") : ?>
                    <p class="responseTo">In response to <?php echo $parent_author; ?></p>
                <?php endif; ?>
                <?php echo ($comment->comment_approved == 1) ? wpautop($comment->comment_content) : $removed_text; ?>
                <form class="actions clearfix" id="comment-actions-<?php echo($comment->comment_ID); ?>" method="post">
                    <?php get_partial('parts/flag', array('id' => $comment->comment_ID, 'type' => "comments", 'sub_type' => "comment", 'actions' => $comment->actions, 'brand' => $brand, 'logged_in' => is_user_logged_in())); ?>
                    <?php get_partial('parts/vote', array('id' => $comment->comment_ID, 'type' => "comments", 'sub_type' => 'comment', 'actions' => $comment->actions, 'logged_in' => is_user_logged_in())); ?>
                    <?php get_partial('parts/share', array('id' => $comment->comment_ID, 'type' => "comments")); ?>
                </form>
            </section>
        </article> <!-- END ARTICLE CONTENT CONTAINER -->

        <?php 
            if(is_user_logged_in()) {
                comment_reply_form($comment);
            }
        ?>
    
        <div class="clearfix"></div>
        <div class="ugc-comment-answer_form clearfix">
        <?php	
        	if(!is_user_logged_in()) {
        ?>
					<div class="trigger discussion">
						<a href="#" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Reply</a>
					</div>
				<?php } else { ?>
					
        	<form action="<?php echo get_bloginfo('url'); ?>/wp-comments-post.php" shc:gizmo="transFormer" method="post" id="commentform-<?php echo $comment->comment_ID ?>" class="reply-to-form clearfix hide"<?php echo $form_style; ?>>
	        	<ul class="form-fields">
	        		<?php 
	        			$sso_user = SSO_User::factory()->get_by_id($current_user->ID);
	        			if($sso_user->guid && ! $sso_user->screen_name):
	        			//if(get_user_meta( $current_user->ID, 'sso_guid' ) && ! has_screen_name( $current_user->ID ) ):?>
	        			<li class="clearfix">
		              <label for="screen-name-<?php echo $child->comment_ID ?>" class="required">Screen Name</label>
		              <input type="text" class="input_text" name="screen-name" value="<?php echo (isset($_GET['comm_err']) && $_GET['cid'] == $comment->comment_ID) ? stripslashes( urldecode( $_GET['screen-name'] ) ) : null; ?>" shc:gizmo:form="{required:true, special: 'screen-name', message: 'Screen name invalid. Screen name is already in use or does not follow the screen name guidelines.'}"/>
	        			</li>
		          <?php endif;?>
	        		<li class="clearfix">
	        			<textarea class="input_textarea discussion" name="comment" shc:gizmo:form="{required:true}"><?php echo (isset($_GET['comm_err']) && $_GET['cid'] == $comment->comment_ID) ? stripslashes( urldecode( $_GET['comment'] ) ) : null; ?></textarea>
	        		</li>
	        		<li class="clearfix">
	        			<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Post</button>
                        <button class="<?php echo theme_option("brand"); ?>_button azure">Cancel</button>
                        <input type="hidden" name="comment_post_ID" value="<?php echo $comment->comment_post_ID; ?>" />
                        <input type="hidden" name="comment_parent" value="<?php echo $parentId; ?>" />
                        <input type="hidden" name="comment_type" value="<?php echo $comment_type; ?>" />
                        <input type="hidden" name="_wp_unfiltered_html_comment" value="27ff0ea567" />
	        		</li>
	        	</ul>
        	</form>
        	
        	<?php } ?>
        </div> <!-- END UGC COMMENT ANSWER FORM --> 
    </div> <!-- END SPAN10 -->
    <?php
        if ($comment->children != "" && $comment->comment_parent == 0) :
            ?><ol class="children"><?php
                display_child_comments($comment); 
            ?></ol><?php
        elseif ($comment->children != "") :
            ?><a href="#" class="moreComments" shc:options="{commParent: <?php echo $comment->comment_ID ?>}">Show more comments</a><?php
        endif;
    ?>
</li>
<?php
    if ($load_more == true) {
        ?><li class="comment"><a href="#" class="moreComments" shc:options="{comment_parent: <?php echo $comment->comment_parent; ?>, comment_offset: 2}">Show more comments</a></li><?php
    }
?>
