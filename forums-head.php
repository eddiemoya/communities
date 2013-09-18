<?php
/**
 * Forums Head
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="forums-head">
	<?php if(bbp_is_forum_archive()):?>
	
		<h2><?php echo get_option('_bbp_forum_archive_heading', 'Forums Header Text');?></h2>
		<p><?php echo get_option('_bbp_forum_archive_subheading', 'Forums Sub-header Text');?></p>
		
	<?php else: ?>
	
		<h2><?php header_breadcrumbs();?></h2>
		
		<?php //For topics (subforums list) page only
			if(has_subforums()): ?>
			
			<p><?php echo get_option('_bbp_forum_topics_subheading', 'Forums Topic Sub-header Text');?></p>
		
		<?php endif;?>
		
	<?php endif;?>
</div>