<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');

loop();
?>

	<section class="span8">
		
		<article class="content-container post-your-question span12">
			
			<section class="content-body clearfix">
				
				<h6 class="content-headline">Post your question</h6>
				
				<form>
					<div class="state_post-your-question">
						<input type="text" class="input_text" name="post-question" value=""/>
						<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Next</button>
					</div>
				</form>
				
			</section>
	
		</article>
			
			
		<article class="content-container post-your-question span12">
			
			<section class="content-body clearfix">
				
				<h6 class="content-headline">Post your question</h6>
				
				<form>
					<div class="state_post-question-details">
						<ul class="form-fields">
							<li>
								<label for="screen-name" class="required">Screen Name</label>
								<input type="text" class="input_text" name="screen-name" id="screen-name" value="" required/>
							</li>
							<li>
								<label for="your-question" class="required">Your Question</label>
								<textarea name="your-question" id="your-question" class="input_textarea" required></textarea>
							</li>
							<li>
								<label for="your-question" class="optional">Add More Details</label>
								<textarea name="your-question" id="your-question" class="input_textarea" required></textarea>
							</li>
							<li>
								<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Post</button>
								<button type="submit" class="<?php echo theme_option("brand"); ?>_button azure">Cancel</button>
							</li>
						</ul>
					</div>
				</form>
				
			</section>
	
		</article>
	</section>


	<section class="span4">

	</section>
<?php
get_template_part('parts/footer');
?>