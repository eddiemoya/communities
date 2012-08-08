more = function(event) {
	event.preventDefault();
	
	action = {'action' 	: 'profile_paginate',
				'type' 	: jQuery('#type').val(),
				'page' 	: jQuery('#next-page').val(),
				'uid' 	: jQuery('#uid').val()
			};
	
	jQuery.ajax({
		type:"POST",
		url: "/wp-admin/admin-ajax.php",
		data: action,
		success:function(html){
			//Remove current next-page element
			jQuery('#next-page').remove();
			jQuery('#page-more').remove();
			
			jQuery("#profile-results").append(html);
			
			jQuery('#page-more').bind('click',more)
		}
	});
}

jQuery(document).ready( function(){
	
	jQuery('#page-more').bind('click', more);

});

