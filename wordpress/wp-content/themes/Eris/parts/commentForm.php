<div class="ugc-comment-answer_form span12">
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
	<div class="trigger discussion">
		<a href="#" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Leave <?php echo $comment_type_text; ?> <span class="smaller">&#9660;</span></a>
	</div>
<?php
    } else {
        $fields =  array();

        $args = array(
            'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
            'comment_field'        => '<textarea id="comment-answer_textarea" class="input_textarea discussion" name="comment" shc:gizmo:form="{required:true, trim:true, pattern: /^\w{3,}$/, message:\'Please enter at least 3 characters.\'}">'. $comment_value .'</textarea>',
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
    <div class="trigger discussion">
			<a href="#">Leave <?php echo $comment_type_text;?> <span class="smaller">&#9660;</span></a>
		</div>

      <?php
        if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) :
            echo $args['must_log_in'];
            do_action( 'comment_form_must_log_in_after' );
            else : ?>
            
            <?php 
            	# If there is a screen name error, show it
            	if(isset($_GET['comm_err']) && ($_GET['cid'] == 0)): 
            ?>
            <div class="form-errors">
               <?php echo stripslashes(urldecode($_GET['comm_err']));?>
        		</div>
        		<?php endif; ?>
        		
            <form id="answer-form" action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" shc:gizmo="transFormer">
							<ul class="form-fields">
								<?php
	                # If a user doesn't have a screen name, prompt them to enter one
	                $sso_user = SSO_User::factory()->get_by_id($current_user->ID);
	                if($sso_user->guid && ! $sso_user->screen_name):
	                //if(get_user_meta($current_user->ID, 'sso_guid') && ! has_screen_name($current_user->ID)):
	              ?>
	              <li class="clearfix">	
	                <label for="screen-name" class="required">Screen Name</label>
	                <input type="text" class="input_text" name="screen-name" id="screen-name" value="<?php echo $screen_name_value;?>" shc:gizmo:form="{required:true, special: 'screen-name', pattern: /^[A-Za-z0-9_\-\.]{2,18}$/, message: 'Please follow the screen name guidelines.'}"/>
	             	</li>
	              <?php endif;?>
								<li class="clearfix">
									<?php
		                do_action( 'comment_form_top' );
		
		                if ( is_user_logged_in() ) :
		                    echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );
		                endif;
		
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
      <?php endif; ?>

    <?php
            do_action( 'comment_form_after' );
        else :
            do_action( 'comment_form_comments_closed' );
        endif;
    }
?>
</div>