/**
* TransFormer: Interactive Form Handler
* @author Tim Steele
*	
* @package shcJSL
* @require jQuery 1.7.2
* 
* @version [1.0: 2012-08-17]
* - Initial script release
*/

/**
 * TRANSfORMER, TRANSfORMER.transFormer
 * 
 * TransFormer object
 */
TRANSfORMER = {}
TRANSfORMER.transFormer = $TransFormer = function(element, options) {

}

shcJSL.methods.transFormer = function(target, options) {
	var checkForLogin; // (Function) Check to see if the user is logged in
	var form;	// The form HTMLObject
	var figs; // Configurations for the current form
	var submitEval = []; // Functions to run for testing the form submitting
	var transformers = []; // (Array) Array of all the forms that are being monitored by transFormer
	
	form = target;
	
	// PRIVATE TEST METHODS
	checkForLogin = function() {
		if (OID != undefined) {
			var data = shcJSL.formDataToJSON(form);
			(form.id)? shcJSL.cookies("form-data").bake({value: '{"' + form.id + '":' + data + '}'}):shcJSL.cookies("form-data").bake({value:data});
			shcJSL.get(document).moodle({width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}});
			return false;
		}
	}
	
	
	
	figs = ($(form).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(form).attr("shc:gizmo:options") + ')')).form)?(eval('(' + $(form).attr("shc:gizmo:options") + ')')).form:{}):{};
	
	if (figs.requireLogIn === true) {
		submitEval[submitEval.length] = checkForLogin;
	}
	
	$(target).bind('submit',function(event) {
		var success; // Whether success passes the check
		if (submitEval.length > 0) {
			(function() {
				var i = 0;
				do {
					success = submitEval[i]();
					i++;
				} while (success != false || i < submitEval.length)
			})()
		}
		if (success === false) event.preventDefault();
	})
	
	//($Moodle instanceof MOODLE.modal)? $Moodle[method](target, this):($Moodle = new $Moodle())[method](target,this)
}

shcJSL.gizmos.bulletin['shcJSL.transFormer.js'] = true;

/**
	 * Event Assigner
	 * 
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0
	 */
if (shcJSL && shcJSL.gizmos)  {
	shcJSL.gizmos.transFormer = function(element) {
		shcJSL.get(element).transFormer();
	}
}
