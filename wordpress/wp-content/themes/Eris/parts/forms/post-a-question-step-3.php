            <section class="content-body clearfix">
                <h6 class="content-headline">Post your question</h6>
				<form id="new_question_step_1" id="new_question_step_1" method="post" action="">
					<?php wp_nonce_field('front-end-post_question-step-1'); ?>
					
					<div class="state_post-your-question state_confirm-post">
						<input type="text" class="input_text" name="post-question" value=""/>
						<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Next</button>
						<section class="background_light-gray confirmation">
							<p class="content-headline">
								Curious and good looking? You’re a double threat.
							</p>
							<p>
								Your question has been posted. Within 72 hours, you'll get an answer from our Community Team. 
							</p>
							<button type="submit" class="<?php echo theme_option("brand"); ?>_button azure">Close</button>
							<input type="hidden" name="question-post-complete" value="" />
						</section>
					</div>
				</form>
			</section>