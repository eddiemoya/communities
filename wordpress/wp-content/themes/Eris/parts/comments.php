<?php
    $comments = get_comments(array('post_id' => $post->ID));

    $comment_type = get_post_type( $post->ID ) == 'question' ? 'answer' : 'comment';

    if ( isset( $comments ) && !empty( $comments ) ) {

?>

<header class="section-header comments-header clearfix">
    <h3><?php echo ucfirst( $comment_type ); ?>s</h3>
    <h4><?php comments_number( '', '1 ' . $comment_type, '% ' . $comment_type . 's' ); ?></h4>
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
                "sub_type"      => $comment_type,
                "options"   => array( "reply", "flag", "upvote", "downvote" ),
                'actions'   => $comment->actions
            );
?>
    <li class="comment clearfix<?php echo $container_class; ?>">
        
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
                } else {
                    echo 'not active';
                }
            ?>
            <form action="http://localhost:4000/wp-comments-post.php" method="post" id="commentform" style="display: block; ">
                <textarea id="comment" name="comment" cols="45" rows="8" shc:gizmo="tooltip" shc:gizmo:options="{tooltip:{events:['click'],displayData:'shower'}}" aria-required="true"></textarea>
                <p class="form-submit">
                    <input type="submit" id="submit" class="kmart_button" value="Post">
                    <input type="submit" shc:gizmo="tooltip" shc:gizmo:options="{tooltip:{events:['click'],displayData:'shower2'}}" class="kmart_button azure" value="Cancel">
                    <input type="hidden" name="comment_post_ID" value="332" id="comment_post_ID">
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
                <li class="comment clearfix<?php echo $container_class; ?>">
                    <?php get_partial( 'parts/crest', $crest_options ); ?>
                    <div class="span10">
                        <time class="content-date" datetime="<?php echo date( "Y-m-d", $child_date ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $child_date ); ?></time>
                        <article>
                            <?php echo $child->comment_content; ?>
                        </article>
                        <?php
                            $child_actions = array(
                                "id"        => $child->comment_ID,
                                "type"      => 'comments',
                                "sub_type"  => $comment_type,
                                "options"   => array( "reply", "flag", "upvote", "downvote" ),
                                'actions'   => $child->actions
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
                        <form action="http://localhost:4000/wp-comments-post.php" method="post" id="commentform" style="display: block; ">
                            <textarea id="comment" name="comment" cols="45" rows="8" shc:gizmo="tooltip" shc:gizmo:options="{tooltip:{events:['click'],displayData:'shower'}}" aria-required="true"></textarea>
                            <p class="form-submit">
                                <input type="submit" id="submit" class="kmart_button" value="Post">
                                <input type="submit" shc:gizmo="tooltip" shc:gizmo:options="{tooltip:{events:['click'],displayData:'shower2'}}" class="kmart_button azure" value="Cancel">
                                <input type="hidden" name="comment_post_ID" value="332" id="comment_post_ID">
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
