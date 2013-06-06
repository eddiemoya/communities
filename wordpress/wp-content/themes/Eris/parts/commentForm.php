<div class="ugc-comment-answer_form span12">
    <?php if (!is_user_logged_in() && comments_open()) : ?>
        <div class="trigger discussion">
            <a href="#" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">
                Leave <?php echo $comment_type_text; ?> <span class="smaller">&#9660;</span>
            </a>
        </div>
    <?php elseif (comments_open()) : ?>
        <div class="trigger discussion">
            <a href="#">Leave <?php echo $comment_type_text;?> <span class="smaller">&#9660;</span></a>
        </div>
        <?php if ($comm_err != "" && $cid == 0) : ?>
            <div class="form-errors">
                <?php echo $comm_err; ?>
            </div>
        <?php endif; ?>
        
        <form id="answer-form" action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" shc:gizmo="transFormer">
            <ul class="form-fields">
                <?php if(get_user_meta($current_user->ID, 'sso_guid') && !has_screen_name($current_user->ID)): ?>
                    <li class="clearfix">
                        <label for="screen-name" class="required">Screen Name</label>
                        <input type="text" class="input_text" name="screen-name" id="screen-name" value="<?php echo $screen_name_value;?>" shc:gizmo:form="{required:true, special: 'screen-name', message: 'Screen name invalid. Screen name is already in use or does not follow the screen name guidelines.'}"/>
                    </li>
                <?php endif; ?>
                <li class="clearfix">
                    <?php
                        do_action( 'comment_form_top' );
                        echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );
                        echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );
                        echo $args['comment_notes_after'];
                    ?>
                </li>
                <li class="clearfix">
                    <button type="submit" class="<?php echo theme_option("brand"); ?>_button"><?php echo esc_attr( $args['label_submit'] ); ?></button>
                    <button class="<?php echo theme_option("brand"); ?>_button azure">Cancel</button>
                    <?php comment_id_fields( $post->ID ); ?>
                    <input type='hidden' name='comment_type' value='<?php echo $comment_type; ?>' />
                    <?php do_action( 'comment_form', $post->ID ); ?>
                </li>
            </ul>
        </form>
        <?php do_action( 'comment_form_after' ); ?>
    <?php else : ?>
        <?php do_action( 'comment_form_comments_closed' ); ?>
    <?php endif; ?>
</div>