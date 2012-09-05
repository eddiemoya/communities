<?php
    global $current_user;
    get_currentuserinfo();

    $comment_type = get_post_type( $post->ID ) == 'question' ? 'answer' : 'comment';

    $comments = get_comments(array('post_id' => $post->ID, 'type' => $comment_type));

    if ( isset( $comments ) && !empty( $comments ) ) {
?>
<header class="section-header comments-header clearfix">
    <h3><?php echo ucfirst( $comment_type ); ?>s</h3>
    <h4>
        <?php
            $commentCount = get_custom_comment_count($comment_type, $post->ID);

            if($commentCount > 0) {
                $string = ($commentCount > 1) ? $commentCount.' '.ucfirst($comment_type).'s' : $commentCount.' '.ucfirst($comment_type);

                echo $string;
            } else {
                echo 'No '.ucfirst($comment_type);
            }
        ?>
    </h4>
</header>
<ol id="allComments">
<?php
        foreach($comments as $comment) {
            $container_class = '';
            $comment_date = strtotime( $comment->comment_date );
            
            $crest_options = array(
                "user_id"   => $comment->user_id
            );
            
            if ( user_can( $comment->user_id, "administrator") ) {
                $container_class = ' expert';
                $crest_options["titling"] = true;
            }
            
            $comment_actions = array(
                "id"        => $comment->comment_ID,
                "type"      => 'comments',
                "sub_type"  => $comment_type,
                "options"   => array("reply", "flag", "downvote", "upvote"),
                'actions'   => $comment->actions,
                'post_id'   => $commint->comment_post_ID
            );
?>
    <li class="comment clearfix<?php echo $container_class; ?>" id="comment-<?php echo $comment->comment_ID ?>">
        <?php get_partial( 'parts/crest', $crest_options ); ?>
        
        <div class="span10">
            <time class="content-date" datetime="<?php echo date( "Y-m-d", $comment_date ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $comment_date ); ?></time>
            <article>
                <?php echo $comment->comment_content; ?>
            </article>
            <?php
                /**
                * Ensure plugin is active before displaying anything
                */
                if(is_plugin_active('action_jackson/action_jackson.php')) {
                    get_partial( 'parts/forms/post-n-comment-actions', $comment_actions );
                }
            ?>
            <form action="<?php echo get_bloginfo('url'); ?>/wp-comments-post.php" shc:gizmo="transFormer" method="post" id="reply-<?php echo $comment->comment_ID ?>" class="reply-to-form">
                <?php if(get_user_meta($current_user->ID, 'sso_guid') && ! has_screen_name($current_user->ID)):?>
                    <label for="screen-name" class="required">Screen Name</label>
                    <input type="text" class="input_text" name="screen-name" id="screen-name" value="" />
                <?php endif;?>
                <textarea id="comment-body-<?php echo $comment->comment_ID ?>" name="comment" rows="8" aria-required="true" shc:gizmo:form="{required:true}"></textarea>
                <p class="form-submit">
                    <?php
                        if(!is_user_logged_in()):
                    ?>
                        <input type="submit" id="submit" class="kmart_button" value="Post" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}" />
                        <input type="reset" class="kmart_button azure" value="Cancel" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}" />
                    <?php else: ?>
                        <input type="submit" id="submit" class="kmart_button" value="Post">
                        <input type="reset" class="kmart_button azure" value="Cancel">
                    <?php endif; ?>
                    <input type="hidden" name="comment_post_ID" value="<?php echo $comment->comment_post_ID; ?>" id="comment_post_ID">
                    <input type="hidden" name="comment_parent" id="comment_parent" value="<?php echo $comment->comment_ID; ?>">
                </p>
                <input type="hidden" name="comment_type" value="<?php echo $comment_type; ?>">
                <input type="hidden" id="_wp_unfiltered_html_comment" name="_wp_unfiltered_html_comment" value="27ff0ea567">
            </form>
        </div>
    <?php
        if(isset($comment->children) && !empty($comment->children)) :
            foreach($comment->children as $child) :
                $child_date = strtotime( $child->comment_date );
    ?>
            <ol class="children">
                <li class="comment clearfix<?php echo $container_class; ?>" id="comment-<?php echo $comment->comment_ID ?>">
                    <?php
                        $crest_options = array(
                                    "user_id"   => $child->user_id
                                );
                        get_partial( 'parts/crest', $crest_options );
                    ?>
                    <div class="span10">
                        <time class="content-date" datetime="<?php echo date( "Y-m-d", $child_date ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $child_date ); ?></time>
                        <p class="responseTo">In response to <?php comment_author($comment->comment_ID); ?></p>
                        <article>
                            <?php echo $child->comment_content; ?>
                        </article>
                        <?php
                            $child_actions = array(
                                "id"        => $child->comment_ID,
                                "type"      => 'comments',
                                "sub_type"  => $comment_type,
                                "options"   => array("reply", "flag", "downvote", "upvote"),
                                'actions'   => $child->actions,
                                'post_id'   => $commint->comment_post_ID
                            );

                            /**
                            * Ensure plugin is active before displaying anything
                            */
                            if(is_plugin_active('action_jackson/action_jackson.php')) {
                                get_partial( 'parts/forms/post-n-comment-actions', $child_actions );
                            } else {
                                echo 'not active';
                            }
                        ?>
                        <form action="<?php echo get_bloginfo('url'); ?>/wp-comments-post.php" shc:gizmo="transFormer" method="post" id="child-reply-<?php echo $comment->comment_ID ?>" class="reply-to-form">
                            <?php if(get_user_meta($current_user->ID, 'sso_guid') && ! has_screen_name($current_user->ID)):?>
                                <label for="screen-name" class="required">Screen Name</label>
                                <input type="text" class="input_text" name="screen-name" id="screen-name" value="" />
                            <?php endif;?>
                            <textarea id="comment-body-<?php echo $comment->comment_ID ?>" name="comment" rows="8" aria-required="true" shc:gizmo:form="{required:true}"></textarea>
                            <p class="form-submit">
                                <?php
                                    if(!is_user_logged_in()):
                                ?>
                                        <input type="submit" id="submit" class="kmart_button" value="Post" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}" />
                                        <input type="reset" class="kmart_button azure" value="Cancel" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}" />
                                <?php else: ?>
                                        <input type="submit" id="submit" class="kmart_button" value="Post" />
                                        <input type="reset" class="kmart_button azure" value="Cancel" />
                                <?php endif; ?>
                                <input type="hidden" name="comment_post_ID" value="<?php echo $comment->comment_post_ID; ?>" id="comment_post_ID">
                                <input type="hidden" name="comment_parent" id="comment_parent" value="<?php echo $comment->comment_ID; ?>">
                            </p>
                            <input type="hidden" name="comment_type" value="<?php echo $comment_type; ?>">
                            <input type="hidden" id="_wp_unfiltered_html_comment" name="_wp_unfiltered_html_comment" value="27ff0ea567">
                        </form>
                    </div>
                </li>
           </ol>
    <?php
            endforeach;
         endif;
    ?>
    </li>
<?php
        }
?>
</ol>
<?php
    }
    # No Comments.
    else {
?>
<section>
    No <?php echo $comment_type; ?>s yet.
</section>
<?php
    }
