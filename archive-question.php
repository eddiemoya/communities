<?php get_template_part('parts/header'); ?>

<?php 
	global $post_question_data;
	$data = $post_question_data;
	
	$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
	$sidebar_args = array(
	    'before_widget' => '<article class="span12 content-container widget dropzone-widget sidebar-widget widget %2$s" id="%1$s">',
	    'after_widget' => '</article>',
	    'before_title' => '<header class="content-header"><h3 class="widgettitle">',
	    'after_title' => '</h3></header>'
	);
 ?>

<section class="span8 widget_dropzone_widget dropzone">
	<?php if(!dynamic_sidebar('Q&A Content Area')) : ?> 

		<article class="span12 widget_post_a_question content-container widget post-your-question">
			<?php include ( get_template_directory().'/parts/forms/post-a-question-step-'.$data['step'].'.php' ); ?>
		</article>

		<article class="span12 widget_result-list content-container widget results-list">
			<?php get_template_part('widgets/results-list/archive'); ?>
		</article> 

	<?php endif; ?> 

</section>

<section class="span4 widget_dropzone_widget dropzone">
	<?php 
		if(!dynamic_sidebar('Q&A Sidebar')){

			$instance = array('nav_menu' => $nav_menus[3]->term_id, 'title' => 'Related Links');
			the_widget('Communities_Menu_Widget', $instance, $sidebar_args);

		 }
	?>
</section>

<?php get_template_part('parts/footer'); ?>