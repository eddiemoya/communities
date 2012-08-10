jQuery(document).ready(function($) { 

	/**
	 * Ajax request to return subcategories - pass 'category_id' as POST
	 
	 * @author Eddie Moya
	 */
 	$('.post-your-question select#category, select#sort-results').bind('change', function(e){
		var data = {
			action		: 'get_subcategories_ajax',
			category_id	: $('option', this).filter(':selected').val()
		};
		select = $(this);

		jQuery.ajax({
			url  : ajaxdata.ajaxurl,
			type: 'POST',
			data : data,
			success:function(results){
				$('#sub-category', select.parent()).remove();
				select.after(results);
			}
		});
 	});

 	$('#new_question_step_1').bind('submit', function(e){
		var data = {
			action		: 'get_subcategories_ajax',
			category_id	: $('option', this).filter(':selected').val()
		};
		select = $(this);

		jQuery.ajax({
			url  : ajaxdata.ajaxurl,
			type: 'POST',
			data : data,
			success:function(results){
				$('#sub-category', select.parent()).remove();
				select.after(results);
			}
		});
 	});

 	/**
 	 * Not actually in use - just an example for testing purposes
 	 */
 	// $('li#header_login a').bind('click', function(e){
 	// 	e.preventDefault();
		// var data = {
		// 	action		: 'get_template_ajax',
		// 	template	: 'page-login'
		// };

		// jQuery.ajax({
		// 	url  : ajaxdata.ajaxurl,
		// 	type: 'POST',
		// 	data : data,
		// 	success:function(xhr, message, status){
		// 		console.log(xhr);
		// 		console.log(message);
		// 		console.log(status)

		// 	}
		// });
 	// });
 });


