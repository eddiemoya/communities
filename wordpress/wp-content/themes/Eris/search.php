<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header'); ?>

<article class="span12 content-container widget">
	<?php get_template_part('widgets/results-list/archive'); ?>
</article> 

<?php get_template_part('parts/footer');
