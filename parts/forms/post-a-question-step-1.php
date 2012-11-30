			<section class="content-body clearfix">
				
				<h6 class="content-headline">Post your question</h6>
				
				<?php if($data['errors']):?>
					
					<?php foreach($data['errors'] as $error):?>
					
						<div class="form-errors"><?php echo $error;?></div>
						
					<?php endforeach;?>
					
				<?php endif;?>
				
				<form id="new_question_step_1" name="new_question_step_1" method="post" action="" shc:gizmo="transFormer" shc:gizmo:options="{form:{requireLogIn:true}}">
					<?php wp_nonce_field('front-end-post_question-step-1'); ?>
					
					<ul class="state_post-your-question form-fields">
						<li>
							<input type="text" class="input_text" name="post-question" id="post-question" value="<?php echo (isset($_POST['post-question'])) ? $_POST['post-question'] : null;?>" shc:gizmo:form="{required:true}"/>
							<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Next</button>
						</li>
						<li>
							<textarea name="test1"></textarea>
							<input type="hidden" name="test1" value="3" />
						</li>
						
						<li>
							<select name="selectTest">
								<option value="Tits">Tits</option>
								<option value="No Tits">No Tits</option>
							</select>
							
						</li>
					</ul>
				</form>
				
			</section>