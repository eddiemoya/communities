<?php
global $current_user;
get_currentuserinfo();
?>
			<section class="content-body clearfix">
				
				<h6 class="content-headline">Post your question</h6>
				<?php if($data['errors']):?>
					
					<?php foreach($data['errors'] as $error):?>
					
						<div class="form-errors"><?php echo $error;?></div>
						
					<?php endforeach;?>
					
				<?php endif;?>
				
				<form id="new_question" name="new_question" method="post" action="" shc:gizmo="transFormer" shc:gizmo:options="{form:{requireLogIn:true}}">
					<?php wp_nonce_field('front-end-post_question-step-2'); ?>

					<div class="state_post-question-details">
						<ul class="form-fields">
							<?php if(get_user_meta($current_user->ID, 'sso_guid') && ! has_screen_name($current_user->ID)):?>
							<li class="clearfix">
								<label for="screen-name" class="required">Screen Name</label>
								<input type="text" class="input_text" name="screen-name" id="screen-name" value="" shc:gizmo:form="{required:true, special: 'screen-name', message: 'Screen name invalid. Screen name is already in use or does not follow the screen name guidelines.'}" />
							</li>
							<?php endif;?>
							<li class="clearfix">
								<label for="your-question" class="required">Your Question</label>
								<textarea name="your-question" id="your-question" class="input_textarea" shc:gizmo:form="{required:true}"><?php 
									echo esc_textarea(stripslashes( ($_POST['post-question'] ) ? $_POST['post-question'] : $_POST['your-question'] )); 
								?></textarea>
							</li>
							<li class="clearfix">
								<label for="more-details" class="optional">Add More Details</label>
								<textarea name="more-details" id="more-details" class="input_textarea"><?php 
									echo esc_textarea(stripslashes( $_POST['more-details'] )); 
								?></textarea>
							</li>
							<li class="clearfix">
								<label for="category" class="required">Category</label>
									<?php 
									wp_dropdown_categories(array(
										'depth'=> 1,
										'selected' => get_queried_object()->term_id,
										'hierarchical' => true,
										'hide_if_empty' => true,
										'class' => 'input_select',
										'name' => 'category',
										'id' => 'category'
									));
									?>
									
							</li>


							<li class="clearfix">
								<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Post</button>
								<button type="submit" class="<?php echo theme_option("brand"); ?>_button azure">Cancel</button>
							</li>
						</ul>
					</div>
				</form>
			</section>