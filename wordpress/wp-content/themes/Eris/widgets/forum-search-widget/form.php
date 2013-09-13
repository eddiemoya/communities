<h4 class="forum-search-header"><?php _e("Search Forums:", "bbpress"); ?></h4>
<p class="form-search-text">This is some descriptive text</p>
<form role="search" method="get" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
	<div class="form-search-actions">
		<input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="bbp_search" id="bbp_search" />&nbsp;
		<input tabindex="<?php bbp_tab_index(); ?>" class="button" type="submit" id="bbp_search_submit" value="<?php esc_attr_e( 'Search', 'bbpress' ); ?>" />
	</div>
</form>
<?php foreach($hrefs as $h) { ?>
	<?php if(!in_array($h['title'], $auth_only) || !$authenticated) { ?>
		<div class="form-search-urls-row">
			<?php if(!empty($h['url'])) { ?>
				<a href="<?php echo($h['url']); ?>"><?php echo($h['title']); ?></a>
			<?php } else { ?>
				<?php echo($h['title']); ?>
			<?php } ?>
		</div>
	<?php } ?>
<?php } ?>
