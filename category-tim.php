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
			
	</section>


	<section class="span4">

	</section>
<?php
get_template_part('parts/footer');

?>


Tim: Does category-tim  (post form) need to be a widget to be placed within dropzones?
