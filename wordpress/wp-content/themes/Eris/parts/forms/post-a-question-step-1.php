			<section class="content-body clearfix">
				
				<h6 class="content-headline">Post your question</h6>
				
				<form id="new_question_step_1" name="new_question_step_1" method="post" action="">
					<?php wp_nonce_field('front-end-post_question-step-1'); ?>

					<div class="state_post-your-question">
						<input type="text" class="input_text" name="post-question" id="post-question" value=""/>
						<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Next</button>
					</div>
				</form>
				
			</section>