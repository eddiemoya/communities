
<?php
	global $current_user;
    get_currentuserinfo();
    
    //Set default values for comment & screen name
    $comment_value = (isset($_GET['comm_err']) && $_GET['cid'] == 0) ? urldecode($_GET['comment']) : null;
    $screen_name_value = (isset($_GET['comm_err']) && $_GET['cid'] == 0) ? urldecode($_GET['screen-name']) : null;
    
    $comment_type_text = get_post_type( $post->ID ) == 'question' ? 'an answer' : 'a comment';
    $comment_type = get_post_type( $post->ID ) == 'question' ? 'answer' : 'comment';

    if(!is_user_logged_in()) {
?>
        <div class="commentForm" xmlns="http://www.w3.org/1999/html">
            <div class="top clearfix">
                <a class="leaveComment" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Leave <?php echo $comment_type_text; ?> <span class="smaller">&#9660;</span></a>
            </div>
        </div>
<?php
    } else {
        $fields =  array();

        $args = array(
            'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
            'comment_field'        => '<textarea id="comment" name="comment" rows="8" aria-required="true" shc:gizmo:form="{required:true}">'. $comment_value .'</textarea>',
            'must_log_in'          => null,
            'logged_in_as'         => null,
            'comment_notes_before' => null,
            'comment_notes_after'  => null,
            'id_form'              => null,
            'id_submit'            => 'submit',
            'title_reply'          => null,
            'title_reply_to'       => __( 'Leave a Reply to %s' ),
            'cancel_reply_link'    => null,
            'label_submit'         => __( 'Post' ),
        );

        if(!isset($defaults))
            $defaults = array();

        $args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

        if ( comments_open() ) :
    ?>
    <div class="commentForm" xmlns="http://www.w3.org/1999/html">
        <div class="top clearfix">
            <span class="leaveComment">Leave <?php echo $comment_type_text;?> <span class="smaller">&#9660;</span></span>
        </div>
        
        <?php
        if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) :
            echo $args['must_log_in'];
            do_action( 'comment_form_must_log_in_after' );
            else : ?>
            <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" shc:gizmo="transFormer">
            
                <?php
                # If a user doesn't have a screen name, prompt them to enter one
                if(get_user_meta($current_user->ID, 'sso_guid') && ! has_screen_name($current_user->ID)):
                    
                    # If there is a screen name error, show it
                    if(isset($_GET['comm_err']) && ($_GET['cid'] == 0)): 
                ?>
                   <div>
                       <?php echo stripslashes(urldecode($_GET['comm_err']));?>
                   </div>
                   <?php endif; ?>
                    <label for="screen-name" class="required">Screen Name</label>
                    <input type="text" class="input_text" name="screen-name" id="screen-name" value="<?php echo $screen_name_value;?>" shc:gizmo:form="{required:true, special: 'screen-name', message: 'Screen name invalid. Screen name is already in use or does not follow the screen name guidelines.'}"/>
                <?php endif;?>
               
               
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
                    <input type="reset" class="<?php echo theme_option("brand"); ?>_button azure" value="Cancel" />
                    <?php comment_id_fields( $post->ID ); ?>
                </p>
                <input type='hidden' name='comment_type' value='<?php echo $comment_type; ?>' />
                <?php do_action( 'comment_form', $post->ID ); ?>
            </form>
        <?php endif; ?>
    </div>
    <?php
            do_action( 'comment_form_after' );
        else :
            do_action( 'comment_form_comments_closed' );
        endif;
    }

    comments_template('/parts/tooltip.php');
