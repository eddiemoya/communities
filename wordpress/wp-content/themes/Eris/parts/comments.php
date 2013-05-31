<?php
    global $current_user;
    
    if (empty($current_user)) : // only grab current user if necessary
        get_currentuserinfo();
    endif;
    
    $n_comments = 10; // number of comments to display on any given page
    
    $page = (get_query_var("page")) ? get_query_var("page") : 1;
    $post_type = get_post_type( $post->ID );
    $comment_type = ($post_type == 'question') ? 'answer' : 'comment';
    $comment_count = get_comments(array(
        'post_id' => $post->ID, 
        'type' => $comment_type, 
        'count' => true
    ));
    
    $comment_offset = $n_comments*($page-1);
    $comment_offset = ($comment_offset < $comment_count) ? $comment_offset : 0; // make sure there is actually comments after the offset
    $page = ($comment_offset < $comment_count) ? $page : 1;

    $comments = get_comments(array(
        'post_id' => $post->ID, 
        'type' => $comment_type, 
        'number' => $n_comments, 
        'offset' => $comment_offset
    ));
?>
    <section class="span12 content-container comments">
        <?php if ( !empty($comments) ) : ?>

            <?php if ($comment_type == "answer") : ?>
                <header class="content-header clearfix">
                    <h3><?php echo ucfirst( $comment_type ); ?>s</h3>
                    <h4><?php echo ($comment_count > 500) ? "500+ answers" : $comment_count . " " . _n( ' answer', ' answers', $comment_count ); ?></h4>
                </header>
            <?php endif; ?>


            <section class="content-body">
                <ol id="allComments">
                <?php
                    foreach($comments as $comment) :
                        get_partial('parts/comment', array("current_user" => $current_user, "comment" => $comment, "recursive" => true));
                    endforeach;
                ?>
                </ol> <!-- END ALL COMMENTS -->
            </section> <!-- END CONTENT BODY -->



        <?php else : // no comments or comments empty ?>



            <section class="content-body">
                <?php if ( $post_type == 'question' ) : ?>

                    <p>
                        No <?php echo $comment_type; ?>s yet.
                    </p>
                   
                <?php endif; ?>
            </section> <!-- END CONTENT BODY -->



        <?php endif; ?>
    </section> <!-- END COMMENTS CONTENT CONTAINER -->
