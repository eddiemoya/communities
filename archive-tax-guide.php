<?php get_template_part('parts/header'); ?>

<?php
	$posts = get_posts(array('post_type' => 'guide', 'numberposts' => 2));

	//echo "<pre>";print_r($posts);echo "</pre>";
	$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
	$sidebar_args = array(
	    'before_widget' => '<article class="span12 content-container widget dropzone-widget sidebar-widget widget %2$s" id="%1$s">',
	    'after_widget' => '</article>',
	    'before_title' => '<header class="content-header"><h3 class="widgettitle">',
	    'after_title' => '</h3></header>'
	);
	$fp1_instance = array(
		'widget_title' => 'Buying Guides',
		'widget_subtitle' => 'Find out more and get the most out of every purchase.',
		'show_title' => true,
		'show_subtitle' => true,
		'show_comment_count' => 'on',
		'show_category' => true,
		'show_date' => true,
		'show_thumbnail' => true,
		'show_content' => true,
		'show_share'	=> true,
		'filter-by' => 'manual',
		'widget_name' => 'featured-post',
		'limit' => 1,
		'post__in_1' => 518
	);
	$fp2_instance = array(
		'show_comment_count' => 'on',
		'show_category' => true,
		'show_date' => true,
		'show_thumbnail' => true,
		'show_content' => true,
		'show_share'	=> true,
		'filter-by' => 'manual',
		'widget_name' => 'featured-post',
		'limit' => 1,
		'post__in_1' => 607
	);

 ?>

<ul class="span8 widget_dropzone_widget dropzone">

	<?php if(!dynamic_sidebar('Posts Content Area')) : ?> 
			<article class="span12 widget_results-list content-container widget results-list">
				<?php get_template_part('widgets/results-list/archive'); ?>
			</article> 
	<?php endif; ?> 


</ul>

<ul class="span4 widget_dropzone_widget dropzone">
	<?php 
		if(!dynamic_sidebar('Posts Sidebar')){
	
			$instance = array('title' => 'About', 'text' => '<p><strong>Buying Guides</strong> give you the inside scoop on all types of products. use them to make the best purchase every single time.</p>');
			the_widget('Content_Blurb', $instance, $sidebar_args);

			$instance = array('nav_menu' => $nav_menus[6]->term_id, 'title' => 'First Time Here?', 'sub-title' => "Here's our best stuff");
			the_widget('Communities_Menu_Widget', $instance, $sidebar_args);

			$instance = array('nav_menu' => $nav_menus[7]->term_id, 'title' => 'Related Stories');
			the_widget('Communities_Menu_Widget', $instance, $sidebar_args);

		 }
	?>
</ul>

<?php get_template_part('parts/footer'); ?>