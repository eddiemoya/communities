<div class="span12 content-container widget dropzone-widget" style="">
	<form role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
		<div class="search-tick" style=""></div>
		<div>
			<label for="bbp_search" class="screen-reader-text"><?php _e($instance['fsw_title'], "bbpress"); ?></label>
			<p><?php echo  $instance['fsw_subtext']; ?></p>
			<div class="search-input-container" style="">
				<img src="/wp-content/themes/Eris/assets/img/forums/searchGlassIcon.png" height="15" width="15" />
				<input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="bbp_search" id="bbp_search" placeholder="Search Forums" style="" />
				<input tabindex="<?php bbp_tab_index(); ?>" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Go', 'bbpress' ); ?>" style="" />
			</div>
			<?php foreach($hrefs as $h) { ?>
				<?php if(!in_array($h['title'], $auth_only) || !$authenticated) { ?>
					<?php if(!empty($h['url'])) { ?>
						<?php if(!empty($h['full_tag'])) { ?>
							<?php echo($h['url']); ?>
						<?php } else { ?>
							<a href="<?php echo($h['url']); ?>"><?php echo($h['title']); ?></a>
						<?php } ?>
					<?php } else { ?>
						<?php echo($h['title']); ?>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</div>
	</form>
</div>
