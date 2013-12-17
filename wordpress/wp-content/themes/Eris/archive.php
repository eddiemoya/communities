<?php get_template_part('parts/header'); ?>

<section class="dropzones span12">

	<section class="span8 widget_dropzone_widget dropzone">
		<section class="dropzone-inner-wrapper">
			<?php if(!dynamic_sidebar('Blogs Content Area')) : ?> 

			<?php endif; ?> 
		</section>
	</section>

	<section class="span4 widget_dropzone_widget dropzone">
		<section class="dropzone-inner-wrapper">
			<?php if(!dynamic_sidebar('Blogs Sidebar')) : ?>

			<?php endif; ?>
		</section>
	</section>


</section>

<?php get_template_part('parts/footer'); ?>