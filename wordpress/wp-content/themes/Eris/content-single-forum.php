<?php

/**
 * Single Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">

	<?php bbp_get_template_part('forums', 'head'); ?>
	
	<?php do_action( 'bbp_template_before_single_forum' ); ?>
	
	<?php ?>

	<?php if ( post_password_required() ) : ?>

		<?php bbp_get_template_part( 'form', 'protected' ); ?>

	<?php else : ?>

		<?php //bbp_single_forum_description(); ?>
				
		<?php if ( bbp_has_forums() ) : ?>

			<?php bbp_get_template_part( 'loop', 'forums' ); ?>
			
		<?php endif; ?>

		
		<?php if ( !bbp_is_forum_category() && bbp_has_topics() && ! bbp_has_forums() ) : ?>
		
		<div id="forums-thread-top">
		
			<?php bbp_get_template_part('forums', 'new-thread'); // New thread pagination ?>
			
			<?php bbp_get_template_part( 'pagination', 'topics'); ?>
		</div>
			
			<?php bbp_get_template_part( 'loop', 'topics' ); ?>

			<?php bbp_get_template_part( 'form', 'topic'); ?>
			
		<div id="forums-thread-bottom">
			
			<?php bbp_breadcrumb();?>
			<?php bbp_get_template_part( 'pagination', 'topics'); ?>
				
		</div>

		<?php elseif ( !bbp_is_forum_category() && ! bbp_has_forums() ) : ?>

			<?php //bbp_get_template_part( 'feedback',   'no-topics' ); ?>
			
			<div id="forums-thread-top">
				<?php bbp_get_template_part('forums', 'new-thread'); ?>
				
				<?php bbp_get_template_part( 'pagination', 'topics'); ?>
			</div>

			<?php bbp_get_template_part( 'form',  'topic'); ?>
			
			<div id="forums-thread-bottom">
			
			<?php bbp_breadcrumb();?>
			<?php bbp_get_template_part( 'pagination', 'topics'); ?>
				
			</div>

		<?php endif; ?>
		
		

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_single_forum' ); ?>

</div>
