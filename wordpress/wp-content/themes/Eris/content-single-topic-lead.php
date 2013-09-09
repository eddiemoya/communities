<?php

/**
 * Single Topic Lead Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_lead_topic' ); ?>

<ul id="bbp-topic-<?php bbp_topic_id(); ?>-lead" class="bbp-lead-topic">

	<li class="bbp-topic-title">
		
		<?php bbp_topic_title(); ?>
		
	</li><!-- .bbp-header -->

	<li class="bbp-body">

	<!-- <div class="bbp-topic-header">

			<div class="bbp-meta">/
				<span class="bbp-topic-post-date"><?php //bbp_topic_post_date(); ?></span>

				<a href="<?php //bbp_topic_permalink(); ?>" title="<?php bbp_topic_title(); ?>" class="bbp-topic-permalink">#<?php bbp_topic_id(); ?></a>
				-->

				<?php //do_action( 'bbp_theme_before_topic_admin_links' ); ?>

				<?php //bbp_topic_admin_links(); ?>

				<?php //do_action( 'bbp_theme_after_topic_admin_links' ); ?>

			<!--  </div> --><!-- .bbp-meta -->

		<!--</div> --><!-- .bbp-topic-header -->

		<div id="post-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

			<div class="bbp-topic-author">

				<?php do_action( 'bbp_theme_before_topic_author_details' ); ?>

				<a href="<?php echo get_profile_url(get_post(bbp_get_topic_id())->post_author); ?>"><?php  echo profile_photo( bbp_get_topic_author(), array('wdith' => 60, 'height' => 60) ); ?></a>

				<?php do_action( 'bbp_theme_after_topic_author_details' ); ?>

			</div><!-- .bbp-topic-author -->

			<div class="bbp-topic-content">

				<?php do_action( 'bbp_theme_before_topic_content' ); ?>
				
				<?php reply_author_date(bbp_get_topic_id());?>
				
				<span class="forums-reply-content">
					<?php bbp_topic_content(); ?>
				</span>
				
				<?php if(! is_user_logged_in()):?>
					<div id="reply-not-loggedin-message">
						(To reply to any topic or comment, you must <a href="#" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Sign in</a>)
					</div>
				<?php endif;?>
				

				<?php do_action( 'bbp_theme_after_topic_content' ); ?>

			</div><!-- .bbp-topic-content -->

		</div><!-- #post-<?php bbp_topic_id(); ?> -->

	</li><!-- .bbp-body -->

	<!-- <li class="bbp-footer">


	</li> -->

</ul><!-- #bbp-topic-<?php bbp_topic_id(); ?>-lead -->

<?php do_action( 'bbp_template_after_lead_topic' ); ?>
