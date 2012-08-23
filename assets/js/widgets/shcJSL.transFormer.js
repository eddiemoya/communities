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
TRANSfORMER.transFormer = $TransFormer = function(form) {
	var bindMethods;	// (Function) Bind the validation methods to the fields
	var error; // (Function) generate the error message
	var fields = [];	// (Array) Array of all the form fields
	var required = [];	// (Array) Array of required fields
	var self = this;
	var methods = {};	// Field validation methods;
	var transformer; // The form object
	this.verify;	// (Function) Verify that the form is valid for submission
	var valid;	// (Boolean) Whether the form is currently valid for submission or not
	
	transformer = form;
	
	methods = {
		pattern: function(a, b) {
			
		}
	}
	
	error = {
		create: function() {
			alert("ERROR");
		},
		destroy: function() {
			alert("OKAY");
		}
	}
	
	bindMethods = function(target) {
		var options;	// Form options from shc:gizmo:form

		if ($(target).attr("shc:gizmo:form") != undefined) {
			options = eval('(' + $(target).attr("shc:gizmo:form") + ')');
			if (options.required && options.required == true) required[required.length] = target;
			if (options.pattern) {
				var pattern = new RegExp();
				$(target).bind('blur', function() {
					if (target.value != '') {
						if (pattern.test(target.value.toString())) error.destroy();
						else error.create();
					}
				});
			}
		}
	}
	
	fields = fields.concat([].slice.call(transformer.elements));
	fields.map(bindMethods);

	
	this.verify = function() {
		return false;
	}
	
	//console.log(fields);
}

shcJSL.methods.transFormer = function(target, options) {
	var checkForLogin; // (Function) Check to see if the user is logged in
	var form;	// The form HTMLObject
	var figs; // Configurations for the current form
	var submitEval = {}; // Functions to run for testing the form submitting
	var transformers = []; // (Array) Array of all the forms that are being monitored by transFormer
	
	form = target;
	
	submitEval[form.id] = [];
	
	// PRIVATE TEST METHODS
	checkForLogin = function() {
		if (OID != undefined) {
			var data = shcJSL.formDataToJSON(form);
			(form.id)? shcJSL.cookies("form-data").bake({value: '{"' + form.id + '":' + data + '}'}):shcJSL.cookies("form-data").bake({value:data});
			shcJSL.get(document).moodle({width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}});
			return false;
		}
	}
	
	transformers[form.id] = new $TransFormer(form);
	submitEval[form.id][submitEval[form.id].length] = transformers[form.id].verify;
		
	figs = ($(form).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(form).attr("shc:gizmo:options") + ')')).form)?(eval('(' + $(form).attr("shc:gizmo:options") + ')')).form:{}):{};
	
	if (figs.requireLogIn === true) {
		submitEval[form.id][submitEval[form.id].length] = checkForLogin;
	}
		
	$(target).bind('submit',function(event) {
		var success; // Whether success passes the check
		if (submitEval[this.id].length > 0) {
			var i = 0;
			do {
				success = submitEval[this.id][i]();
				i++;
			} while (success != false || i < submitEval.length)
		}
		if (success === false) event.preventDefault();
		else event.preventDefault();
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
