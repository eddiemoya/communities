<?php
require_once('../../../../wp-load.php');

header('Content-Type: text/Javascript'); 
?>

// JavaScript Document
jQuery(document).ready( function($) {
$('.postbox:not(.closed) h3').prepend('<a class="togbox">-</a>');
$('.closed h3').prepend('<a class="togbox">+</a>');
$('.postbox h3').click(
	function() {
		var postbox = $($(this).parent().get(0));
		postbox.toggleClass('closed');
		var closed = postbox.is('.closed');
		$($(this)).find('.togbox').text(closed ? '+' : '-');
		$.post(
			'<?php echo get_bloginfo("wpurl") ?>/wp-admin/admin-ajax.php', {
				'action':'set_toggle_status',
				'set_toggle_id':postbox.attr('id'),
				'set_toggle_status': (closed ? 'closed' : '')
			}
		);
	}
);
});