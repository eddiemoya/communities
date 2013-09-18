jQuery(document).ready(function($) { 

	/**
	 * Ajax request to return subcategories - pass 'category_id' as POST
	 
	 * @author Eddie Moya
	 */
 	$('.post-your-question select#category, select.filter-results-posts, select.filter-results-users').bind('change', function(e){

		var data = {
			action		: 'get_subcategories_ajax',
			category_id	: $('option', this).filter(':selected').val(),
			hide_empty : $('.hide_empty', $(this).closest('form')).val()
		};
		
		if(data.category_id != 0){
			$.each(
				$(this).parent().find('input[type="hidden"]'), function() {
					data[($(this).get(0)).name] = ($(this).get(0)).value;
			});
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
		}
 	});

 	/**
 	 * Super massively awesome jquery that, matched with the somewhat 
 	 * lamer widgets/results-list/archive.php template, and the ajax-callbacks.php 
 	 * template, allows the posts widget to filter and sorted via ajax.
 	 *
 	 * @author Eddie Moya
 	 */ 
 	$('.results-list select.filter-results-users, .results-list select.sort-results-users').on('change', function(e){
 		e.preventDefault();

 		container = $(this).closest('.results-list');

		var data = {
			action		: 'get_users_ajax',
			template 	: 'author-archive',
			category	: $('.filter-results-users option', container).filter(':selected').val(),
			order		: $('.sort-results-users option', container).filter(':selected').val(),
			path		: 'widgets/results-list'
		};
		
		//console.log(data);
		data.category = ( $('#sub-category', container).length > 0 ) ? $('#sub-category .filter-results option', this).filter(':selected').val() : data.category; //console.log(data);

		

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

 	/**
 	 * @author Eddie Moya
 	 */
 	$('.results-list select.filter-results-posts, .results-list select.sort-results-posts').on('change', function(e){
 		e.preventDefault();

 		var container = $(this).closest('.results-list');
 		
		var data = {
			action		: 'get_posts_ajax',
			post_type 	: $('.post_type', container).val(),
			template 	: $('.post_type', container).val(),
			category	: $('.filter-results-posts option', container).filter(':selected').val(),
			order		: $('.sort-results-posts option', container).filter(':selected').val(),
			path		: 'widgets/results-list'
		};
		
		if(data.category == "" || data.category == undefined) {
			data.category = $('.content-header input[name=filter-category]').val();
		}
		
		if(data.order == 'comment_count'){
			data.orderby = data.order;
			delete data.order;
		}


		data.category = ( $('#sub-category', container).length > 0 ) ? $('#sub-category .filter-results option', this).filter(':selected').val() : data.category; //console.log(data);

		jQuery.ajax({
			url  : ajaxdata.ajaxurl,
			type: 'POST',
			data : data,
			success:function(results){
				//console.log(results)
				$('.content-body', container).empty();
				$('.content-body', container).append($(results));
			}
		});
 	});

	$('.product').on('ajax-get', function(e){
		var loaded = $(this).attr('data-loaded');
		var data;
		var self = this;

		if(typeof loaded != 'undefined' && loaded == "false" ) {

			//alert($(this).attr('data-pid'));

			var css_class;
			css_class = ( $(this).hasClass('active') ) ? 'active'  :'';
			css_class = ( $(this).hasClass('inactive-left') ) ? 'inactive-left'  :'';
			css_class = ( $(this).hasClass('inactive-right') ) ? 'inactive-right'  :'';


			data = {
				action		: 'get_post_by_id',
				post_type 	: 'product',
				css_class	: css_class,
				id 			: $(this).attr('data-pid')
			};

			jQuery.ajax({
				url  : ajaxdata.ajaxurl,
				type: 'POST',
				data : data,
				success:function(results){
					$(self).html($(results).html());
					$(self).attr('data-loaded', 'true');
				
				}
			});

		}
	});


	$('#forums-topic-reply-link a').on('click', function(){

   		container = $(this).closest('.reply');

   		var data = {
			action		: 'get_post_by_id',
			post_type 	: 'reply',
			id 			: $(container).attr('data-id')
		};

		jQuery.ajax({
				url  : ajaxdata.ajaxurl,
				type: 'POST',
				data : data,
				dataType: 'json',
				success:function(post){
					console.log(post);
					$.markItUp({target:"#bbp_reply_content", openWith:'[quote id='+ post.ID +']\n'+ $.trim(post.post_content), closeWith:'\n[/quote]' });
				
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


