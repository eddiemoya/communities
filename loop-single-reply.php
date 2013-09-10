<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="post-<?php bbp_reply_id(); ?>">

</div><!-- #post-<?php bbp_reply_id(); ?> -->

<div <?php bbp_reply_class(); ?>>

	<div class="bbp-reply-author">

		<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>

		<a href="<?php echo get_profile_url(get_post(bbp_get_reply_id())->post_author); ?>"><?php  echo profile_photo( bbp_get_reply_author_id(), array('width' => 60, 'height' => 60) ); ?></a>

		<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>

	</div><!-- .bbp-reply-author -->

	<div class="bbp-reply-content">

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>
		
		<?php reply_author_date(bbp_get_reply_id()); ?>
		
		<?php bbp_reply_content(); ?>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

	</div><!-- .bbp-reply-content -->
	
	<div class="forums-single-reply-footer">
	<?php topic_reply_link('#forum-reply-form');?>
	</div>

</div><!-- .reply -->


