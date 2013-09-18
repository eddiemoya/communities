// Welcome Tooltip work
(function ($) {
	// Open the tooltip if not cookie is set
	if ((shcJSL.cookies("forums-tooltip").serve() != 'true')) {
		//console.log("NO COOKIES AT ALL!");
		$('#forum_tooltip').show();
		
		// Close the tooltip on click
		$('#forum_tooltip .close').on('click', function() { 
			var input = $(this).parent().find('#message_hide');

			/* TODO:  Check the form value to see whether to set a session cookie or the full 90 day one.*/
			if (input.attr('checked')) {
				// set forever cookie
				shcJSL.cookies("forums-tooltip").bake({value: true, expiration: '1y'});
			} else {
				// set session cookie
				shcJSL.cookies("forums-tooltip").bake({value: true});
			}
			
			// Hide the tooltip
			$(this).parent().hide(); 
		});	
	} else {	// Remove the tooltip.
		//console.log("COOKIES!");
		$('#forum_tooltip').remove();
	}
	
}(jQuery));
