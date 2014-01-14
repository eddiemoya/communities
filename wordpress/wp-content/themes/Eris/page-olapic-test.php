<?php
/*
 * Template Name: Forgot Password
 */

/**
 * @package WordPress
 * @subpackage White Label
 */
if(! is_ajax()):

get_template_part('parts/header'); ?>
	
	<section class="span8">
<?php endif;?>	
		<article class="content-container span12">
			<section class="content-body clearfix">	
				<h5 class="content-headline">
	              Olapic Widget
	            </h5>
	            <div id="olapic_specific_widget"></div>
	            <script type="text/javascript" src="http://widgets.photorank.me/render?element_id=olapic_specific_widget&customer_id=216237&gallery=1529627061&widget_config=75676835" async="async"></script>
			</section>
	
		</article>
		
<?php if(! is_ajax()):?>
	</section>


	<section class="span4">
	</section>
	
<?php
get_template_part('parts/footer');

endif;
?>