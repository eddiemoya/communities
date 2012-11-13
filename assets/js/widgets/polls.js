/*
+----------------------------------------------------------------+
|																							|
|	WordPress Plugin: WP-Polls										|
|	Copyright © 2012 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://lesterchan.net															|
|																							|
|	File Information:																	|
|	- Polls Javascript File															|
|	- wp-content/plugins/wp-polls/polls-js.js									|
|																							|
+----------------------------------------------------------------+
*/


// Variables
var poll_id = 0;
var poll_answer_id = '';
var is_being_voted = false;
pollsL10n.show_loading = 1;
pollsL10n.show_fading = 1;
// pollsL10n.show_loading = parseInt(pollsL10n.show_loading);
// pollsL10n.show_fading = parseInt(pollsL10n.show_fading);

// When User Vote For Poll 
function poll_vote(current_poll_id) {
	if (window['OID'] != undefined) {
		shcJSL.get(document).moodle({width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}});
	}
	else {		
		if(!is_being_voted) {
			set_is_being_voted(true);
			poll_id = current_poll_id;
			poll_answer_id = '';
			poll_multiple_ans = 0;
			poll_multiple_ans_count = 0;
			if(jQuery('#poll_multiple_ans_' + poll_id).length) {
				poll_multiple_ans = parseInt(jQuery('#poll_multiple_ans_' + poll_id).val());
			}
			jQuery('#polls_form_' + poll_id + ' input:checkbox, #polls_form_' + poll_id + ' input:radio').each(function(i){
				if (jQuery(this).is(':checked')) {					
					if(poll_multiple_ans > 0) {
						poll_answer_id = jQuery(this).val() + ',' + poll_answer_id;
						poll_multiple_ans_count++;
					} else {
						poll_answer_id = parseInt(jQuery(this).val());
					}
				}
			});
			if(poll_multiple_ans > 0) {
				if(poll_multiple_ans_count > 0 && poll_multiple_ans_count <= poll_multiple_ans) {
					poll_answer_id = poll_answer_id.substring(0, (poll_answer_id.length-1));
					poll_process();
				} else if(poll_multiple_ans_count == 0) {
					set_is_being_voted(false);
					/*
					 * If the poll is checkboxes and the user
					 * has not selected a checkbox.
					 */
					// NOT ENOUGH ANSEWRS
					// alert(pollsL10n.text_valid);
					
					displayError(poll_id, "Oops! You must choose an answer to submit your vote.");
					// alert("ER MY GAWD! NOT ENURFF ANSEWRS!")
				} else {
					set_is_being_voted(false);
					/*
					 * If poll is checkboxes and the user
					 * selected more than the maximum allowed
					 * checkboxes
					 */
					// TOO MANY ANSEWRS
					// alert(pollsL10n.text_multiple + ' ' + poll_multiple_ans);
					
					displayError(poll_id, "You've exceeded the number of answers allowed.");
					// alert("ER MY GAWD! TOO MANY ANSEWRS!");
				}
			} else {
				if(poll_answer_id > 0) {
					poll_process();
				} else {
					/*
					 * If poll is radio buttons and the user has not
					 * selected a radio button.
					 */
					set_is_being_voted(false);
					displayError(poll_id, "Oops! You must choose an answer to submit your vote.");
					// alert("ER MY GAWD! PICK ER NURMBER!")
					// alert(pollsL10n.text_valid);
				}
			}
		} else {
			// For waiting
			// alert(pollsL10n.text_wait);
			// alert("ER MY GAWD! JURST WAIT!")
		}
		
	}
}

// Process Poll (User Click "Vote" Button)
function poll_process() {
	poll_nonce = jQuery('#poll_' + poll_id + '_nonce').val();
	if(pollsL10n.show_fading) {
		jQuery('#polls-' + poll_id).fadeTo('def', 0, function () {
			if(pollsL10n.show_loading) {
				jQuery('#polls-' + poll_id + '-loading').show();
			}
			jQuery.ajax({type: 'POST', url: pollsL10n.ajax_url, data: 'action=polls&view=process&poll_id=' + poll_id + '&poll_' + poll_id + '=' + poll_answer_id + '&poll_' + poll_id + '_nonce=' + poll_nonce, cache: false, success: poll_process_success});
		});
	} else {
		if(pollsL10n.show_loading) {
			jQuery('#polls-' + poll_id + '-loading').show();
		}
		jQuery.ajax({type: 'POST', url: pollsL10n.ajax_url, data: 'action=polls&view=process&poll_id=' + poll_id + '&poll_' + poll_id + '=' + poll_answer_id + '&poll_' + poll_id + '_nonce=' + poll_nonce, cache: false, success: poll_process_success});
	}
}

// Poll's Result (User Click "View Results" Link)
function poll_result(current_poll_id) {
	if(!is_being_voted) {
		set_is_being_voted(true);
		poll_id = current_poll_id;
		poll_nonce = jQuery('#poll_' + poll_id + '_nonce').val();
		if(pollsL10n.show_fading) {
			jQuery('#polls-' + poll_id).fadeTo('def', 0, function () {
				if(pollsL10n.show_loading) {
					jQuery('#polls-' + poll_id + '-loading').show();
				}
				jQuery.ajax({type: 'GET', url: pollsL10n.ajax_url, data: 'action=polls&view=result&poll_id=' + poll_id + '&poll_' + poll_id + '_nonce=' + poll_nonce, cache: false, success: poll_process_success});
			});
		} else {
			if(pollsL10n.show_loading) {
				jQuery('#polls-' + poll_id + '-loading').show();
			}
			jQuery.ajax({type: 'GET', url: pollsL10n.ajax_url, data: 'action=polls&view=result&poll_id=' + poll_id + '&poll_' + poll_id + '_nonce=' + poll_nonce, cache: false, success: poll_process_success});
		}
	} else {
		// alert(pollsL10n.text_wait);
	}
}

// Poll's Voting Booth  (User Click "Vote" Link)
function poll_booth(current_poll_id) {
	if(!is_being_voted) {
		set_is_being_voted(true);
		poll_id = current_poll_id;
		poll_nonce = jQuery('#poll_' + poll_id + '_nonce').val();
		if(pollsL10n.show_fading) {
			jQuery('#polls-' + poll_id).fadeTo('def', 0, function () {
				if(pollsL10n.show_loading) {
					jQuery('#polls-' + poll_id + '-loading').show();
				}
				jQuery.ajax({type: 'GET', url: pollsL10n.ajax_url, data: 'action=polls&view=booth&poll_id=' + poll_id + '&poll_' + poll_id + '_nonce=' + poll_nonce, cache: false, success: poll_process_success});
			});
		} else {
			if(pollsL10n.show_loading) {
				jQuery('#polls-' + poll_id + '-loading').show();
			}
			jQuery.ajax({type: 'GET', url: pollsL10n.ajax_url, data: 'action=polls&view=booth&poll_id=' + poll_id + '&poll_' + poll_id + '_nonce=' + poll_nonce, cache: false, success: poll_process_success});
		}
	} else {
		//alert(pollsL10n.text_wait);
	}
}

// Poll Process Successfully
function poll_process_success(data) {
	jQuery('#polls-' + poll_id).replaceWith(data);
	if(pollsL10n.show_loading) {
		jQuery('#polls-' + poll_id + '-loading').hide();
	}
	if(pollsL10n.show_fading) {
		jQuery('#polls-' + poll_id).fadeTo('def', 1, function () {		
			set_is_being_voted(false);	
		});
	} else {
		set_is_being_voted(false);	
	}
}

// Set is_being_voted Status
function set_is_being_voted(voted_status) {
	is_being_voted = voted_status;
}

function displayError(id, message) {
	$("#polls-" + id).find(".polls_error").html(message);
}
