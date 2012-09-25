<?php
    global $current_user;
    get_currentuserinfo();

    require_once get_template_directory().'/classes/communities_profile.php';

    $comment_type = get_post_type( $post->ID ) == 'question' ? 'answer' : 'comment';
    $comments = get_comments(array('post_id' => $post->ID, 'type' => $comment_type));
    
    
    $answer_count = (function_exists('get_custom_comment_count')) ? get_custom_comment_count('answer') : '';
    $expert_count = (function_exists('get_expert_comment_count')) ? get_expert_comment_count($post->ID) : '';
    $answer_count = $answer_count - $expert_count;
    $answer_count_string = ($answer_count > 500) ? "500+ answers" : $answer_count . " " . _n( ' answer', ' answers', $answer_count );
    $expert_count_string = ($expert_count > 500) ? "500+ community team answers" : $expert_count . " " . _n( ' community team answer', ' community team answers', $expert_count );
?>
    <section class="span12 content-container comments">
        <?php if ( isset( $comments ) && !empty( $comments ) ) { ?>



            <?php if ($comment_type == "answer"): ?>
    	       <header class="content-header clearfix">
        	       <h3><?php echo ucfirst( $comment_type ); ?>s</h3>
                   <h4><?php echo $answer_count_string; ?> | <?php echo $expert_count_string; ?></h4>
    	       </header> <!-- END ANSWER HEADER -->
            <?php endif; ?>


            <section class="content-body">
                <ol id="allComments">
                <?php
                    foreach($comments as $comment) {
                        get_partial('parts/comment', array("current_user" => $current_user, "comment" => $comment, "recursive" => true));
                    }
                ?>
                </ol> <!-- END ALL COMMENTS -->
            </section> <!-- END CONTENT BODY -->



        <?php } else { // no comments or comments empty ?>



            <section class="content-body">
                <?php if ( get_post_type( $post->ID ) == 'question' ): ?>

                    <p>
                        No <?php echo $comment_type; ?>s yet.
                    </p>
                   
                <?php endif; ?>
            </section> <!-- END CONTENT BODY -->



        <?php } ?>
    </section> <!-- END COMMENTS CONTENT CONTAINER -->
