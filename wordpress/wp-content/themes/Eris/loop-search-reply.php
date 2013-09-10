<?php

/**
 * Search Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>


<div id="post-<?php bbp_reply_id(); ?>" <?php bbp_reply_class(); ?>>

	<div class="bbp-reply-author">

		<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>

		<?php echo profile_photo( bbp_get_reply_author_id(), array('width' => 60, 'height' => 60) ); ?>

		<?php if ( bbp_is_user_keymaster() ) : ?>

			<?php do_action( 'bbp_theme_before_reply_author_admin_details' ); ?>

			<div class="bbp-reply-ip"><?php bbp_author_ip( bbp_get_reply_id() ); ?></div>

			<?php do_action( 'bbp_theme_after_reply_author_admin_details' ); ?>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>

	</div><!-- .bbp-reply-author -->
	
	<div class="bbp-meta">

		<span class="bbp-reply-post-date"><?php reply_author_date(bbp_get_reply_id()); ?></span>

		<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

		<?php //bbp_reply_admin_links(); ?>

		<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>

	</div><!-- .bbp-meta -->

	<div class="bbp-reply-title">

		<h3><?php _e( 'In reply to: ', 'bbpress' ); ?>
		<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>" title="<?php bbp_topic_title( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a></h3>

	</div><!-- .bbp-reply-title -->

	

	<div class="bbp-reply-content">

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>

		<?php bbp_reply_content(); ?>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

	</div><!-- .bbp-reply-content -->

</div><!-- #post-<?php bbp_reply_id(); ?> -->

