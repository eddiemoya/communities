// Welcome Tooltip work
(function ($) {
	// Open the tooltip if not cookie is set
	/* TODO: Check cookie and open the tooltip if appropriate*/
	if (true) {
		
	
		// Close the tooltip on click
		$('#forum_tooltip .close').on('click', function() { 
			var input = $(this).parent().find('#message_hide');

			/* TODO:  Check the form value to see whether to set a session cookie or the full 90 day one.*/
			if (input.attr('checked')) {
				// set forever cookie
			} else {
				// set session cookie
			}
			
			// Hide the tooltip
			$(this).parent().hide(); 
		});	
	} else {	// Remove the tooltip.
		$('#forum_tooltip').remove();
	}
	
}(jQuery));
