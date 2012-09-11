<?php
    global $current_user;
    get_currentuserinfo();

    require_once get_template_directory().'/classes/communities_profile.php';

    $comment_type = get_post_type( $post->ID ) == 'question' ? 'answer' : 'comment';

    //get expert answers
    $userProfile = new User_Profile();

    $expertCommentCount = count($userProfile->page($page)
                                ->get_posts_by_id($post->ID)
                                ->get_expert_answers($comment_type)
                                ->posts[0]
                                ->expert_answers
                    );

    $comments = get_comments(array('post_id' => $post->ID, 'type' => $comment_type, 'status' => 'approve'));

    if ( isset( $comments ) && !empty( $comments ) ) :
?>
<section class="span12">
<?php if ($comment_type == "answer"): ?>
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
        ?> |
        <?php
            if($expertCommentCount > 0) {
                $string = ($expertCommentCount > 1) ? $expertCommentCount.' Community Team '.ucfirst($comment_type).'s' : $commentCount.' Community Team '.ucfirst($comment_type);

                echo $string;
            } else {
                echo '0 Community Team '.ucfirst($comment_type);
            }
        ?>
    </h4>
</header>
<?php endif; ?>

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
	<p>
	    No <?php echo $comment_type; ?>s yet.
	</p>

<?php
    endif;
?>
</section>
<?php
