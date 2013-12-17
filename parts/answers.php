<?php if (have_comments()) { ?>
    <h3 id="comments">
        <?php printf(_n('One Response to %2$s', '%1$s Responses to %2$s', get_comments_number()), number_format_i18n(get_comments_number()), '&#8220;' . get_the_title() . '&#8221;');?>
    </h3>

    <div class="navigation">
        <div class="alignleft"><?php previous_comments_link() ?></div>
        <div class="alignright"><?php next_comments_link() ?></div>
    </div>

    <ol class="commentlist">
        <?php wp_list_comments(array('type' => 'answer')); ?>
    </ol>

    <div class="navigation">
        <div class="alignleft"><?php previous_comments_link() ?></div>
        <div class="alignright"><?php next_comments_link() ?></div>
    </div>
<?php } else { // this is displayed if there are no comments so far  ?>

    <p class="nocomments">
    <?php if (comments_open()) : ?>
            <!-- If comments are open, but there are no comments. -->
            <?php _e('No one has answered this question.'); ?>

        <?php else : // comments are closed ?>
            <!-- If comments are closed. -->
            <?php _e('This question is closed.'); ?>

        <?php endif; ?>
    </p>

<?php } //have_comments() ?>

<?php if (comments_open()) { ?>

    <div id="respond">

        <h3><?php comment_form_title(__('Answer this Question'), __('Answer %s')); ?></h3>

        <div id="cancel-comment-reply">
            <small><?php cancel_comment_reply_link() ?></small>
        </div>


        <p>
    <?php
    //If comments are enabled sitewide and the current user is logged in.
    if (get_option('comment_registration') && !is_user_logged_in()) {
        //Show some stuff to users not logged in.
        printf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(get_permalink()));
    } else {
        //Show some stuff to logged in users.
        printf(__('Logged in as <a href="%1$s">%2$s</a>.'), get_option('siteurl') . '/wp-admin/profile.php', $user_identity);
        ?><a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php esc_attr_e('Log out of this account'); ?>"><?php _e('Log out &raquo;'); ?></a><?php
    }
    ?>
        <p>
        <!--  The action is set with a GET variable 'comment_type' set to 'answer. -->
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

            <?php if (!is_user_logged_in()){ ?>
            <p><input type="text" name="author" id="author" value="<?php echo esc_attr(); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                <label for="author"><small><?php _e('Name'); ?> <?php if ($req) _e('(required)'); ?></small></label></p>

            <p><input type="text" name="email" id="email" value="<?php echo esc_attr(); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
                <label for="email"><small><?php _e('Mail (will not be published)'); ?> <?php if ($req) _e('(required)'); ?></small></label></p>

            <p><input type="text" name="url" id="url" value="<?php echo esc_attr(); ?>" size="22" tabindex="3" />
                <label for="url"><small><?php _e('Website'); ?></small></label></p>
                <!--<p><small><?php printf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>'), allowed_tags()); ?></small></p>-->
            <?php } ?>
            <p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>

            <p>
                <input name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_attr_e('Answer Question'); ?>" />
                <input name='comment_type='
                <?php comment_id_fields(); ?>

            </p>

    <?php do_action('comment_form', $post->ID); ?>

        </form>

<?php } // comments_open()


