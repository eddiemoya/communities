<div id="flagForm-<?php echo $comment_id; ?>" class="hide">
	<form class="flag-form" id="commentForm-<?php echo $comment_id; ?>" method="post">
		<textarea class="flagField" rows="5" name="comment" aria-required="true"></textarea>
		<input class="<?php echo $brand; ?>_button" type="submit" value="Flag" />
		<input class="<?php echo $brand; ?>_button azure" type="reset" value="Cancel" />
		<input name="comment_post_ID" type="hidden" value="<?php echo $post_id; ?>" />
		<input name="comment_parent" type="hidden" value="<?php echo $comment_id; ?>" />
		<input name="comment_type" type="hidden" value="flag" />
	</form>
</div>