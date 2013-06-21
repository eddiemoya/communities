<div class="ugc-comment-answer_form clearfix">

<?php if ($status) : ?>
    
    <div class="trigger discussion">
        <a href="#">Reply</a>
    </div>
    
    <form action="<?php echo get_bloginfo('url'); ?>/wp-comments-post.php" shc:gizmo="transFormer" method="post" id="commentform-<?php echo $comment->comment_ID ?>" class="reply-to-form clearfix"<?php echo $form_style; ?>>
        <ul class="form-fields">
            
            <li class="clearfix">
                <textarea class="input_textarea discussion" name="comment" shc:gizmo:form="{required:true}"><?php echo (isset($_GET['comm_err']) && $_GET['cid'] == $comment->comment_ID) ? stripslashes( urldecode( $_GET['comment'] ) ) : null; ?></textarea>
            </li>
            
            <li class="clearfix">
                <button type="submit" class="<?php echo theme_option("brand"); ?>_button">Post</button>
                <button class="<?php echo theme_option("brand"); ?>_button azure">Cancel</button>
                <input type="hidden" name="comment_post_ID" value="<?php echo $comment->comment_post_ID; ?>" />
                <input type="hidden" name="comment_parent" value="<?php echo $comment->comment_ID; ?>" />
                <input type="hidden" name="comment_type" value="<?php echo $comment_type; ?>" />
                <input type="hidden" name="_wp_unfiltered_html_comment" value="27ff0ea567" />
            </li>
        </ul>
    </form>
    
<?php else : ?>
    
    <div class="trigger discussion">
        <a href="#" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Reply</a>
    </div>
    
<?php endif; ?>

</div>