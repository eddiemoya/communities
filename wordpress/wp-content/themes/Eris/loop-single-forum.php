<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>

	<li class="bbp-forum-info">

		<?php do_action( 'bbp_theme_before_forum_title' ); ?>

		<a class="bbp-forum-title" href="<?php bbp_forum_permalink(); ?>" title="<?php bbp_forum_title(); ?>"><?php truncated_text(bbp_forum_title(), 115); ?></a>

		<?php do_action( 'bbp_theme_after_forum_title' ); ?>

		<?php do_action( 'bbp_theme_before_forum_description' ); ?>

		<div class="bbp-forum-content"><?php the_content(); ?></div>

		<?php do_action( 'bbp_theme_after_forum_description' ); ?>

		<?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>

		<?php comm_bbp_list_forums(array('show_topic_count' => false, 'show_reply_count' => false, 'separator' => ' | ')); ?>

		<?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>

		<?php bbp_forum_row_actions(); ?>

	</li>

	<li class="bbp-forum-topic-count">
		<ul class="forum-counts">
			<li>Threads <?php bbp_forum_topic_count(); ?></li>
			<li>Posts <?php bbp_forum_reply_count();?></li>
		</ul>
	</li>

	<!-- <li class="bbp-forum-reply-count"><?php //bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li> -->

	<li class="bbp-forum-freshness">
	
		<?php forums_last_activity(bbp_get_forum_id(0));?>
		
	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->
