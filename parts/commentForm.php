<?php
    $fields =  array();

    $args = array(
        'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
        'comment_field'        => '<textarea id="comment" name="comment" cols="45" rows="8" shc:gizmo="tooltip" shc:gizmo:options="{tooltip:{events:[\'click\'],displayData:\'shower\'}}" aria-required="true"></textarea>',
        'must_log_in'          => null,
        'logged_in_as'         => null,
        'comment_notes_before' => null,
        'comment_notes_after'  => null,
        'id_form'              => 'commentform',
        'id_submit'            => 'submit',
        'title_reply'          => null,
        'title_reply_to'       => __( 'Leave a Reply to %s' ),
        'cancel_reply_link'    => null,
        'label_submit'         => __( 'Post' ),
    );

    $args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

    if ( comments_open() ) :
        $comment_type = get_post_type( $post->ID ) == 'question' ? 'an answer' : 'a comment';
?>
    <div class="commentForm" xmlns="http://www.w3.org/1999/html">
            <div class="top clearfix">
                <span class="leaveComment">Leave <?php echo $comment_type; ?> <span class="smaller">&#9660;</span></span>
            </div>
            <?php
                if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) :
                    echo $args['must_log_in'];
                    do_action( 'comment_form_must_log_in_after' );
                else : ?>
                    <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
                        <?php
                            do_action( 'comment_form_top' );

                            if ( is_user_logged_in() ) :
                                echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );
                            endif;

                            echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
                            echo $args['comment_notes_after'];
                        ?>
                            <p class="form-submit">
                                <input type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" class="<?php echo theme_option("brand"); ?>_button" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
                                <input type="submit" shc:gizmo="tooltip" shc:gizmo:options="{tooltip:{events:['click'],displayData:'shower'}}" class="<?php echo theme_option("brand"); ?>_button azure" value="Cancel" />
                                <?php comment_id_fields( $post_id ); ?>
                            </p>
                        <?php do_action( 'comment_form', $post_id ); ?>
                    </form>
                <?php endif; ?>
        </div><!-- #respond -->
        <script>
//            $(".commentForm form").hide();
            $(".leaveComment").click(function () {
              $(".commentForm form").slideToggle("slow");
            });
        </script>
        <p class="hidden" id="shower">Hello</p>
    <?php
        do_action( 'comment_form_after' );
    else :
        do_action( 'comment_form_comments_closed' );
    endif;

    comments_template('/parts/tooltip.php');