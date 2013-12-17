<?php

    # REQUIRED
    # $current_user     The current user's object.
    # $comment          The object containing all the info about the comment.

    # OPTIONS
    # $recursive =      false (default), true - whether to call this very partial again to display any child comments.

    $is_recursive = isset( $recursive ) ? $recursive : false;
    $container_class = isset(get_userdata($comment->user_id)->roles) && in_array('expert', get_userdata($comment->user_id)->roles) ? ' expert' : '';
    $parent_author = $is_recursive ? false : return_screenname( get_comment( $comment->comment_parent )->user_id ) ;
    $date = strtotime( $comment->comment_date );
		
    $comment_type = get_post_type( $comment->comment_post_ID ) == 'question' ? 'answer' : 'comment';
    
    $removed_text = "<p>This {$comment_type} has been removed.</p>";
   


    $parentId = (!isset($parentId)) ? $comment->comment_ID : $parentId;
?>

	<li class="comment clearfix<?php echo $container_class; ?>" id="<?php echo $comment_type.'-reply-'.$comment->comment_ID ?>">
    <?php 
    if($comment->comment_approved == 1) {
    	
     	get_partial( 'parts/crest', array( "user_id" => $comment->user_id, "width" => "span2" ) ); 

    }	
    ?>
    <div class="span10">
    	<article class="content-container">
				<section class="content-body clearfix">
					
					<div class="content-details clearfix">
						<time class="content-date" pubdate datetime="<?php echo date( "Y-m-d", $date ); ?>"><?php echo date( "F j, Y", $date ); ?><span class="time-stamp"><?php echo date( "g:ia", $date ); ?></span></time>
					</div>
					
					<?php if ($parent_author) : ?>
            <p class="responseTo">
            	In response to <?php echo $parent_author; ?>
            </p>
        	<?php endif;?>
          	<?php echo ($comment->comment_approved == 1) ? wpautop($comment->comment_content) : $removed_text; ?>
					
				</section>
			</article> <!-- END ARTICLE CONTENT CONTAINER -->
        <?php
            $form_style = '';
            $actions = array(
                "id"        => $comment->comment_ID,
                "type"      => 'comments',
                "sub_type"  => $comment_type,
                "options"   => array("flag", "downvote", "upvote"),
                'actions'   => $comment->actions,
                'post_id'   => $comment->comment_post_ID
            );

            # If there is an error, open the form
            if(isset($_GET['comm_err']) && ($_GET['cid'] == $comment->comment_ID )) {
                $form_style = ' style="display:block;"';
            }

            /**
            * Ensure plugin is active before displaying anything
            */        
            require_once ABSPATH . '/wp-admin/includes/plugin.php';   // http://codex.wordpress.org/Function_Reference/is_plugin_active
            if(is_plugin_active('action_jackson/action_jackson.php')) {
                get_partial( 'parts/forms/post-n-comment-actions', $actions );
            }

        ?>
        <div class="clearfix"></div>
        <div class="ugc-comment-answer_form clearfix">
        <?php	
        	if(!is_user_logged_in()) {
        ?>
					<div class="trigger discussion">
						<a href="#" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Reply</a>
					</div>
				<?php } else { ?>
					<div class="trigger discussion">
						<a href="#">Reply</a>
					</div>
					
        	<form action="<?php echo get_bloginfo('url'); ?>/wp-comments-post.php" shc:gizmo="transFormer" method="post" id="commentform-<?php echo $comment->comment_ID ?>" class="reply-to-form clearfix"<?php echo $form_style; ?>>
	        	<ul class="form-fields">
	        		<?php 
	        			$sso_user = SSO_User::factory()->get_by_id($current_user->ID);
	        			if($sso_user->guid && ! $sso_user->screen_name):
	        			//if(get_user_meta( $current_user->ID, 'sso_guid' ) && ! has_screen_name( $current_user->ID ) ):?>
	        			<li class="clearfix">
		              <label for="screen-name-<?php echo $child->comment_ID ?>" class="required">Screen Name</label>
		              <input type="text" class="input_text" name="screen-name" value="<?php echo (isset($_GET['comm_err']) && $_GET['cid'] == $comment->comment_ID) ? stripslashes( urldecode( $_GET['screen-name'] ) ) : null; ?>" shc:gizmo:form="{required:true, special: 'screen-name', pattern: /^[A-Za-z0-9_\-\.]{2,18}$/, message: 'Please follow the screen name guidelines.'}"/>
	        			</li>
		          <?php endif;?>
	        		<li class="clearfix">
	        			<textarea class="input_textarea discussion" name="comment" shc:gizmo:form="{required:true, trim:true, custom: function(self) { if(self.value.length >= 3) return true; else return false;}, message:'Please enter at least 3 characters.'}"><?php echo (isset($_GET['comm_err']) && $_GET['cid'] == $comment->comment_ID) ? stripslashes( urldecode( $_GET['comment'] ) ) : null; ?></textarea>
	        		</li>
	        		<li class="clearfix">
	        			<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Post</button>
                        <button class="<?php echo theme_option("brand"); ?>_button azure">Cancel</button>
                        <input type="hidden" name="comment_post_ID" value="<?php echo $comment->comment_post_ID; ?>" />
                        <input type="hidden" name="comment_parent" value="<?php echo $parentId; ?>" />
                        <input type="hidden" name="comment_type" value="<?php echo $comment_type; ?>" />
                        <input type="hidden" name="_wp_unfiltered_html_comment" value="27ff0ea567" />
	        		</li>
	        	</ul>
        	</form>
        	
        	<?php } ?>
        </div> <!-- END UGC COMMENT ANSWER FORM --> 
    </div> <!-- END SPAN10 -->
    <?php
        if (!empty( $comment->children ) ) :
    ?>
        <ol class="children">
            <?php
              foreach( $comment->children as $child ){
                  get_partial('parts/comment', array("current_user" => $current_user, "comment" => $child, 'parentId' => $parentId));
              }
            ?>
        </ol>
    <?php endif; ?>
	</li>
