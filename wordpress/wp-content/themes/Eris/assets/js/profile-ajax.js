var action = {'action' 	: 'profile_paginate',
				'type' 	: jQuery('#type').val(),
				'page' 	: jQuery('#next-page').val(),
				'uid' 	: jQuery('#uid').val()
				};

jQuery(document).ready( function(){
	
	jQuery('#page-more').click(function(e){
			
		e.preventDefault();
		
		jQuery.ajax({
		type:"POST",
		url: "/wp-admin/admin-ajax.php",
		data: action,
		success:function(html){
			//Remove current next-page element
			jQuery('#next-page').remove();
			
			jQuery("#profile-results").append(html);
		}
	});
		 
		return false;
			
	});
});
