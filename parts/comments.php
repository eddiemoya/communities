<?php
    global $current_user;
    get_currentuserinfo();

    $comment_type = get_post_type( $post->ID ) == 'question' ? 'answer' : 'comment';

    $comments = get_comments(array('post_id' => $post->ID, 'type' => $comment_type, 'status' => 'approve'));

    if ( isset( $comments ) && !empty( $comments ) ) :
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
            get_partial('parts/comment', array("current_user" => $current_user, "comment" => $comment, "recursive" => true));
        }
?>
</ol>
<?php
    # No Comments.
    else:
?>
<section>
    No <?php echo $comment_type; ?>s yet.
</section>
<?php
    endif;
