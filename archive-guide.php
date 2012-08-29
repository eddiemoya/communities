<?php get_template_part('parts/header'); ?>

<ul class="span8">
<?php
    if (have_posts()) :
        while (have_posts()) :
            get_partial("widgets/featured-post/single", array("is_widget_override" => true));
        endwhile;
    endif;
?>
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