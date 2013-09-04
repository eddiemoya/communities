<?php

/**
 * Forums Loop
 *
 * @package bbPress
 * @subpackage Theme
 */



do_action( 'bbp_template_before_forums_loop' ); ?>

<?php  bbp_get_template_part( 'pagination', 'forums'  ); ?>

<ul id="forums-list-<?php bbp_forum_id(); ?>" class="bbp-forums">

	<li class="bbp-header">

		<ul class="forum-titles">
			<li class="bbp-forum-info"><?php (bbp_is_forum_archive()) ? _e( 'Forums', 'bbpress' ) : _e('Topics', 'bbpress'); ?></li>
			<li class="bbp-forum-topic-count"><?php _e( '', 'bbpress' ); ?></li>
			<!--  <li class="bbp-forum-reply-count"><?php //bbp_show_lead_topic() ? _e( 'Replies', 'bbpress' ) : _e( 'Posts', 'bbpress' ); ?></li> -->
			<li class="bbp-forum-freshness"><?php _e( 'Last Post', 'bbpress' ); ?></li>
		</ul>

	</li><!-- .bbp-header -->

	<li class="bbp-body">

		<?php while ( bbp_forums() ) : bbp_the_forum(); ?>

			<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>

		<?php endwhile; ?>

	</li><!-- .bbp-body -->

</ul><!-- .forums-directory -->

   <?php if(! bbp_is_forum_archive()) bbp_breadcrumb();?>

	<?php  bbp_get_template_part( 'pagination', 'forums'  ); ?>


<?php do_action( 'bbp_template_after_forums_loop' ); ?>
