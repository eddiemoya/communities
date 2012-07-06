<?php
comment_form(array(
    'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to answer a question.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
    'title_reply'          => __( 'Answer this Question' ),
    'title_reply_to'       => __( 'Anwser %s\'s Question' ),
    'cancel_reply_link'    => __( 'Cancel Answer' ),
    'label_submit'         => __( 'Submit Answer' ),
    'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( 'Answer', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>'
));

?>