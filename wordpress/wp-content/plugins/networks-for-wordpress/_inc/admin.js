jQuery(document).ready( function() {

	update_domain_preview(jQuery('#newDom').val());
	update_siteurl_preview(jQuery('#newDom').val(), jQuery('#newPath').val());

	jQuery('#newDom').change(function(){
		var text = this.value;
		update_domain_preview(text);
		update_siteurl_preview(text, jQuery('#newPath').val());
		return false;
	});
	
	jQuery('#newPath').change(function() {
		update_siteurl_preview(jQuery('#newDom').val(), this.value);
		return false;
	});
	
	jQuery('#verify_domain').click(function() {
		jQuery('#verifying').html(strings.checkingString);
		var data = {
			action: 'check_domain',
			domain:	jQuery('#newDom').val(),
			path: jQuery('#newPath').val()
		};

		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#verifying').html(response);
		});
	});
	
	if(typeof(jQuery().pointer) != 'undefined' && strings.pointerText != '') {
		jQuery('#contextual-help-link').pointer({
			content    : strings.pointerText,
			position   : { 
				edge: 'top',
				align: 'right'
			},
			close  : function() {
				jQuery.post( ajaxurl, {
					pointer: 'networks-help',
					action: 'dismiss-wp-pointer'
				});
			}
		}).pointer('open');
		 
	}
	
});

function update_domain_preview( domain_name ) {
	jQuery('#domain_preview').html(domain_name);
}

function update_siteurl_preview( domain_name, path_name ) {
	if(path_name == undefined) path_name = '/';
	if(domain_name == undefined) return false;
	jQuery('#siteurl_preview').html('<a href="http://' + domain_name + path_name + '">http://' + domain_name + path_name + '</a>');
}