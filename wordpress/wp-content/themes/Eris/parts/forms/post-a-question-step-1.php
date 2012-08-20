			<section class="content-body clearfix">
				
				<h6 class="content-headline">Post your question</h6>
				
				<?php if($data['errors']):?>
					
					<?php foreach($data['errors'] as $error):?>
					
						<div><?php echo $error;?></div>
						
					<?php endforeach;?>
					
				<?php endif;?>
				
				<form id="new_question_step_1" name="new_question_step_1" method="post" action="" shc:gizmo="transFormer" shc:gizmo:options="{form:{requireLogIn:true}}">
					<?php wp_nonce_field('front-end-post_question-step-1'); ?>
					
					<ul class="state_post-your-question form-fields">
						<li>
							<input type="text" class="input_text" name="post-question" id="post-question" value=""/>
							<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Next</button>
						</li>
						
						<!-- ERROR STYLE
						<li class="error">
							<input type="text" class="input_text" name="post-question" id="post-question" value=""/>
							<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Next</button>
							<p class="error-message">
								<span class="error-pointer"></span>
								Please enter a question
							</p>
						</li> -->
					</ul>
				</form>
				
			</section>