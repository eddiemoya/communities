/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */

/**
 * AJAX request to set custom image for a post/page or featured content.
 */
function FA_SetAsCustom(id, nonce){
	var $link = jQuery('a#fa-custom-img-' + id);

	$link.text( setPostThumbnailL10n.saving );
	jQuery.post(ajaxurl, {
		action:"FA-post-thumbnail", post_id: post_id, thumbnail_id: id, _ajax_nonce: nonce, cookie: encodeURIComponent(document.cookie)
	}, function(img_url){
			var win = window.dialogArguments || opener || parent || top;
			$link.text('Set as FA Lite featured image');			
			set_FA_image( img_url );
		}
	);
}

function set_FA_image( img_url ){
	if ( img_url == '0' ) {
		alert('Could not set that as featured image. Please try a different image.');
		
		jQuery('#FA_new_image', window.parent.document).css('display', 'inline');
		jQuery('#FA_rem_image', window.parent.document).css('display', 'none');
		
	} else {
		var img = jQuery('#FA-current-image', window.parent.document);
		if( img.length > 0 ){
			jQuery('#FA-current-image', window.parent.document).attr('src', img_url);
			
		}else{
			var wrapper = jQuery('#FA-curr-img-wrap', window.parent.document);
			var h = '<img src="' + img_url + '" alt="Current Featured Articles image set for this post." id="FA-current-image" />';
			wrapper.html(h);			
		}		
		jQuery('#FA_new_image', window.parent.document).css('display', 'none');
		jQuery('#FA_rem_image', window.parent.document).css('display', 'inline');		
	}
}