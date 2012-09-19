delete_comment = function(event) {
	
	event.preventDefault();
	
	cid = jQuery(this).attr('id');
	uid = jQuery('#profile_uid_' + cid).val();
	nonce = jQuery('#_wp_nonce_' + cid).val();
	
	jQuery('#profile_uid_' + cid).remove();
	
	post = {'action' 		: 'user_delete_comment',
			'_wp_nonce' 	: nonce,
			'cid' 			: cid,
			'uid'			: uid};
	
	jQuery.ajax({
		type:"POST",
		url: ajaxdata.ajaxurl,
		data: post,
		success:function(data){
			
			if(data != 0) {
				
				jQuery('#comm-' + cid).remove();
				
			} else {
				
				
			}
		}
	});
}

jQuery(document).ready( function(){
	
	jQuery('.delete-comment').bind('click', delete_comment);

});