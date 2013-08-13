function bbpress_direct_quotes_quotePost(postID){
	jQuery("#bbpress_direct_quotes_loader_" + postID).show();
	jQuery.post(ajaxurl, {
		action: "bbpress_direct_quotes_GetPostContent",
		postID: postID
	}, function(data){
		quote = data;
		replyTextfield = jQuery("#bbp_reply_content");
		text = replyTextfield.val();
		if(jQuery.trim(text) != ''){
			text += "\n\n";
		}
		text += quote;
		replyTextfield.val(text);
		jQuery("#bbpress_direct_quotes_loader_" + postID).hide();
		bbpress_direct_quotes_scrollToElement("new-post", 1000);
	});
	
}

function bbpress_direct_quotes_scrollToElement(elementID, duration){
	var isOldIE = (jQuery.browser.msie && (parseInt(jQuery.browser.version) < 9));
	if(isOldIE){
		document.location.href = "#" + elementID;
	}
	else{
		jQuery("html, body").animate({scrollTop: jQuery("#" + elementID).offset().top}, duration);
	}
}