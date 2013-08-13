<?php
	/*
	Plugin Name: bbPress Direct Quotes
	Description: Adds a "Quote" link to each post, which inserts its content (inside of a <blockquote>) to the reply textfield.
	Version: 1.1
	Author: destroflyer
	*/

	add_action("init", "bbpress_direct_quotes_Initialize");
	
	function bbpress_direct_quotes_Initialize(){
		if(is_user_logged_in()){
			wp_enqueue_script("bbpress_direct_quotes_Quotes", plugins_url("quotes.js", __FILE__), array("jquery"));
			wp_enqueue_style("bbpress_direct_quotes_Style", plugins_url("style.css", __FILE__));
			add_action("wp_ajax_bbpress_direct_quotes_GetPostContent", "bbpress_direct_quotes_GetPostContent");
			add_action("bbp_theme_before_reply_admin_links", "bbpress_direct_quotes_AddQuoteLinkToReply");
		}
	}
	
	function bbpress_direct_quotes_GetPostContent(){
		global $wpdb;
		$postAuthor = "destroflyer";
		$postRow = $wpdb->get_row("SELECT post_author, post_content FROM " . $wpdb->posts . " WHERE ID = " . intval($_POST["postID"]) . " LIMIT 1");
		$postAuthor = $wpdb->get_var("SELECT user_login FROM " . $wpdb->users . " WHERE ID = " . intval($postRow->post_author) . " LIMIT 1");
		echo '<blockquote><cite><a href="' . site_url() . '/members/' . $postAuthor . '/" rel="nofollow">@' . $postAuthor . '</a> said:</cite>' . "\n" . bbpress_direct_quotes_preparePostContent($postRow->post_content) . '</blockquote>';
		exit();
	}
	
	function bbpress_direct_quotes_preparePostContent($postContent){
		$postContent = preg_replace('#<blockquote[^>]*>([^<]+|<(?!/?blockquote)[^>]*>|(?R))+</blockquote>#isU', '', $postContent);
		$postContent = trim($postContent);
		return $postContent;
	}
	
	function bbpress_direct_quotes_AddQuoteLinkToReply(){
		$postID = bbp_get_reply_id();
		echo '
			<span class="bbp-admin-links">
				&nbsp;|&nbsp;<a href="#" onclick="bbpress_direct_quotes_quotePost(' . $postID . '); return false;">Quote</a>
				<img id="bbpress_direct_quotes_loader_' . $postID . '" class="bbpress_direct_quotes_loader" src="' . plugins_url("images/loader.gif", __FILE__) . '"/>
			</span>
		';
	}
?>