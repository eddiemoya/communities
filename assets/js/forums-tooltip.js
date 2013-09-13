// Welcome Tooltip work
(function ($) {
	// Open the tooltip if no cookie is set
	/* TODO: Check cookie and open the tooltip if appropriate*/
	
	// Close the tooltip on click
	$('#forum_tooltip .close').on('click', function() { 
		/* TODO:  Check the form value to see whether to set a session cookie or the full 90 day one.*/
		
		// Hide the tooltip
		$(this).parent().hide(); 
	});
	
	
}(jQuery));
