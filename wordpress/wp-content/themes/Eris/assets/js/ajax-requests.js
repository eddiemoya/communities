jQuery(document).ready(function($) { 

	/**
	 * Ajax request to return subcategories - pass 'category_id' as POST
	 
	 * @author Eddie Moya
	 */
 	$('.post-your-question select#category').bind('change', function(e){
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
				$('.post-your-question select#sub-category').remove();
				select.after(results);
			}
		});
 	});
 });