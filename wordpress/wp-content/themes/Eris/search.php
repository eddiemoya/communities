<?php
/**
 * @package WordPress
 * @subpackage White Label
 */
 $class = (have_posts()) ? "results-list widgets_results-list" : "";

get_template_part('parts/header'); ?>

<article class="span12 content-container widget <?php echo $class; ?>">
	<?php get_template_part('widgets/results-list/archive'); ?>
</article> 

<?php get_template_part('parts/footer');
