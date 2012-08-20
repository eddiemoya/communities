<?php get_template_part('parts/header'); ?>

<?php $data = process_front_end_question(); ?>

<ul class="span8 widget_dropzone_widget dropzone">
	<article class="span12 widget_post_a_question content-container widget post-your-question">
		<?php include (get_template_directory() . '/parts/forms/post-a-question-step-'. $data['step'] . '.php'); ?>
	</article>
	<article class="span12 widget_post_a_question content-container widget post-your-question">
		<?php get_template_part('widgets/results-list/archive'); ?>
	</article>
</ul>



<ul class="span4 widget_dropzone_widget dropzone">
	<article class="span12 widget_post_a_question content-container widget post-your-question">

		<?php if ( !dynamic_sidebar('Q&A Sidebar') ) : ?> 
        <? endif; ?>

	</article>
</ul>


 <?php //echo "<pre>";print_r($wp_query);echo "</pre>"; ?>

<?php get_template_part('parts/footer'); ?>