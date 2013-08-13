<?php $comment_count = count_comments(); ?>
<section class="span12 content-container comments" shc:gizmo="mint">
    <?php if ( $comment_count > 0 ) : ?>
        <?php if ($comment_type == "answer") : ?>
            <header class="content-header clearfix">
                <h3><?php echo ucfirst( $comment_type ); ?>s</h3>
                <h4>
                    <?php 
                        echo ($comment_count > 500) ? "500+ answers" : $comment_count . " " . _n( ' answer', ' answers', $comment_count ); 
                    ?>
                </h4>
            </header>
        <?php endif; ?>
        <section class="content-body">
            <ol id="allComments">
            <?php
                display_comments($comment_count);
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