<?php
    $fields =  array();

    $args = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'must_log_in'          => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => null,
		'comment_notes_after'  => null,
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'title_reply'          => null,
		'title_reply_to'       => __( 'Leave a Reply to %s' ),
		'cancel_reply_link'    => __( 'Cancel reply' ),
		'label_submit'         => __( 'Post Comment' ),
	);

    $args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

    if ( comments_open() ) : ?>
		<div id="respond">
			<h3 id="reply-title">
                <?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?>
                <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small>
            </h3>
			<?php
                if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) :
                    echo $args['must_log_in'];
                    do_action( 'comment_form_must_log_in_after' );
			    else : ?>
				    <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
                        <?php do_action( 'comment_form_top' ); ?>
                        <?php if ( is_user_logged_in() ) : ?>
                            <?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
                            <?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
                        <?php else : ?>
                            <?php echo $args['comment_notes_before']; ?>
                            <?php
                            do_action( 'comment_form_before_fields' );
                            foreach ( (array) $args['fields'] as $name => $field ) {
                                echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
                            }
                            do_action( 'comment_form_after_fields' );
                            ?>
                        <?php
                            endif;

                            echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
                            echo $args['comment_notes_after']; ?>
                            <p class="form-submit">
                            <input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
                            <input type="submit" name="Cancel" id="cancel" class="cancel" value="Cancel" />
                            <?php comment_id_fields( $post_id ); ?>
                        </p>
                        <?php do_action( 'comment_form', $post_id ); ?>
                    </form>
                <?php endif; ?>
		</div><!-- #respond -->
    <?php
        do_action( 'comment_form_after' );
    else :
        do_action( 'comment_form_comments_closed' );
    endif;

    $comments = get_comments(array('post_id' => $post->ID));

    foreach($comments as $comment) {

    }