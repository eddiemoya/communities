<section class="span12">
	<?php if(!bbp_is_search()) : ?>
	<div id="comm-forums-main" class="span9">

		<?php the_content(); ?>

	</div>
	<div id="comm-forums-side" class="span3">		
		<?php 
		if(function_exists('dynamic_sidebar')) {
			  if(bbp_is_forum_archive()) { //Forums Archive
			
					dynamic_sidebar('Forums Archive Sidebar');
				
			  } elseif(bbp_is_single_forum() && ! has_subforums()) { //Single Forum
				
					dynamic_sidebar('Forum Threads Sidebar');
				
			  } elseif(bbp_is_single_forum() && has_subforums()) { //Forum Topics
				
					dynamic_sidebar('Forum Topics Sidebar');
				
			  } elseif(bbp_is_single_topic()) { //Topic Threads
				
					dynamic_sidebar('Forum Thread Archive Sidebar');
				
			  } 		  
		}		
		?>

	</div>
	<?php else: ?>
	<div id="comm-forums-main" class="span12">

		<?php the_content(); ?>

	</div>
	<?php endif; ?>
</section>
