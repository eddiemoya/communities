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
		var select = $(this);

		$('#sub-category', select.parent()).remove();

		jQuery.ajax({
			url  : ajaxdata.ajaxurl,
			type: 'POST',
			data : data,
			success:function(results){
				select.after($(results));
			}
		});
 	});

 	/**
 	 * Super massively awesome jquery that, matched with the somewhat 
 	 * lamer widgets/results-list/archive.php template, and the ajax-callbacks.php 
 	 * template, allows the posts widget to filter via ajax.
 	 */ 
 	$('.result-list select').on('change', function(e){
 		e.preventDefault();

		var data = {
			action		: 'get_posts_ajax',
			template 	: 'post-results-list',
			category	: $('option', this).filter(':selected').val()
		};

		data.category = ( $('#sub-category', container).length > 0 ) ? $('#sub-category option', this).filter(':selected').val() : data.category;

		container = $(this).closest('.result-list');

		jQuery.ajax({
			url  : ajaxdata.ajaxurl,
			type: 'POST',
			data : data,
			success:function(results){
				$('.content-body', container).empty();
				$('.content-body', container).append($(results));
			}
		});
 	});


 	// $('#new_question_step_1').bind('submit', function(e){
		// var data = {
		// 	action		: 'get_subcategories_ajax',
		// 	category_id	: $('option', this).filter(':selected').val()
		// };
		// select = $(this);

		// jQuery.ajax({
		// 	url  : ajaxdata.ajaxurl,
		// 	type: 'POST',
		// 	data : data,
		// 	success:function(results){
		// 		$('#sub-category', select.parent()).remove();
		// 		select.after(results);
		// 	}
		// });
 	// });

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


