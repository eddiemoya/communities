
//jQuery(document).ready( function(){

//Check that user is not logged in
if(OID != undefined) {
	
	//attach submit event to form
	$jQuery('#new_question_step_1', '#commentform' ).submit(function(event){
	
		event.preventDefault();
		
		//Get formdata
		formdata = jQuery(this).serialize();
		
		//Set cookie, expires in 2 mins
		set_question_cookie(formdata, 120);
		
		//Launch modal login
		shcJSL.get(document).moodle({width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}});
		
	});
	
}

});

function set_question_cookie(value, secs) {
	
	//Set cookie name
	cookie_name = 'login-post-data';
	
	//Set expire date/time
	dt = new Date();
	dt.setTime((dt.getTime() + secs));
	
	document.cookie = cookie_name + '=' + value + '; ' + 'expires=' + dt;
}