/**
* valid8: Interactive Form Handler
* @author Tim Steele
*	
* @package shcJSL
* @require jQuery 1.7.2
* 
* @version [1.0: 2012-08-17]
* - Initial script release (Under TransFormer)
* 
* @version [2.0: 2012-10-10]
* - Adapted to be valid8
*/

valid8 = {
	allowed: {
		
	},
	/**
 * CUSTOM ERROR MESSAGES
 * Client is looking for custom error messages
 * for fields. These are the custom error messages
 * tied to the field types.
 */
	cipher: {
		"category": {
			"required": "Please choose a category."
		},
		"confirm-email": {
			"function": "Your email does not match. Please check and try again.",
			"required": "Your email does not match. Please check and try again."
		},
		"email": {
			"pattern": "The email address you enter should follow this format: name@domain.com. Please try again.",
			"required": "Please enter a valid email address."
		},
		"flag": {
			"pattern": "Please enter at least 3 characters.",
			"required": "This field cannot be empty."
		},
		"password": {
			"pattern": "please enter a valid password.",
			"required": "Please enter your password."
		},
		"open-text": {
			"pattern": "Please enter at least 3 characters.",
			"required": "Please enter at least 3 characters."
		},
		"post-question": {
			"required": "Please enter your question."
		},
		"screen-name": {
			"function": {
				"default": "Please follow the screen name guideslines.",
				"155": "Please follow the screen name guideslines.",
				"120": "That screen name has already been taken. Please select a new one."
			},
			"pattern": "Please follow the screen name guidelines."
		},
		"zip-code": {
			"pattern": "Please enter a valid Zip code.",
			"required": "Please enter a valid Zip code."
		}
	},
	"function": {
		"confirm-email": function(value, type) {
			console.log(this);
			console.log(value);
			console.log(type);
			var email = $(this).closest("form").find('[data-type="email"]').get(0);
			console.log(email)
		},
		"screen-name": function(value, type) {
			// console.log($._data(this, "events"));
			var valid,
					element = this;
			if (window['ajaxdata'] && window['ajaxdata']['ajaxurl']) {
				jQuery.ajax({
					async: false,
					dataType: 'html',
					data: {
						"screen_name": value,
						"action": "validate_screen_name"
					},
					type: "POST",
					url: window['ajaxdata']['ajaxurl']
				}).success(function(data, status, xhr) {
					if (parseInt(data) === 200) valid = true;
					else {
						valid = null;
						valid8.oops(element).concoct(
							(valid8.cipher[element.getAttribute("data-type")] && valid8.cipher[element.getAttribute("data-type")]["function"])? ((valid8.cipher[element.getAttribute("data-type")]["function"][data.substr(data.indexOf(".") + 1)])? valid8.cipher[element.getAttribute("data-type")]["function"][data.substr(data.indexOf(".") + 1)]:valid8.cipher[element.getAttribute("data-type")]["function"]["default"]):"Error"
						)
						
					}
				}).error(function(xhr, status, message) {
					valid = true;
				})
			}
			return (valid == true)? true:null;
		}
	},
	pattern: {
		"email": /^.+@.+?\.[a-zA-Z]{2,}$/,
		"password": /^\w*(?=\w{8,})(?=\w*\d)(?=\w*[a-zA-Z])(?!\w*_)\w*$/,
		"screen-name":/^[A-Za-z0-9_\-\.]{2,18}$/,
		"zip-code": /(^\d{5})(-\d{4})?$/
	}
};

valid8.oops = function(element, failure) {
	var pariah = [];
	
	function blunder(message) {
		return shcJSL.fn.addChildren(
			shcJSL.fn.createNewElement("p","error-message"),
			[
				shcJSL.fn.createNewElement("span","error-pointer"),
				document.createTextNode(message)
			]
		);
	}
	
	methods = {
		// This is the error message;
		// element is the element in question;
		concoct: function(element) {
			if (!element.nextSibling || (element.nextSibling && element.nextSibling.className.indexOf("error-message") == -1)) {
				element.parentNode.insertBefore((new blunder(this)), element.nextSibling);
			}
			else if (element.nextSibling && element.nextSibling.className.indexOf("error-message") != -1) {
				element.parentNode.replaceChild((new blunder(this)),element.nextSibling);
			}
			
			if (element.parentNode.className.indexOf("error") == -1) element.parentNode.className += " error";
		},
		consume: function(element) {
			if (element.nextSibling && element.nextSibling.nodeName != "#text" && element.nextSibling.className.indexOf("error-message") != -1) element.parentNode.removeChild(element.nextSibling);
			if (element.parentNode.className.indexOf("error") != -1) element.parentNode.className = element.parentNode.className.replace(/( )?error/, '');
		}
	}
	
	pariah.push(element);
	
	pariah = shcJSL.fn.createCustomActionArray(pariah, methods);
	
	return pariah;	
}

/**
 * REGEX VALIDATION PATTERNS
 */

valid8.forms 	= {}

valid8.form		= function(form, options) {
	var self 		= this,														// Root object
			elements,																	// (Array) All the form fields
			form 		= form,														// (HTMLElement) The form HTMLElement
			invalid = [],															// (Object) Object containing invalid fields (using name:element pairing);
			options	= (options)? options:undefined,		// (Object) Any options that exist with the form
			spy,																			// (Function) Assign 
			watch;																		// (Timeout) The timeout for onkeyup validation
		
	// Gather the form elements
	if (form) {
		elements = shcJSL.fn.sequence(form.elements);
		elements.map(spy);
	}
	
	function validate(element) {
		var element = (shcJSL.fn.getObjectType(element) == "[object Array]")? element[0]:element,
				fn,
				input,
				type 		= element.getAttribute("data-type"),
				valid 	= true;
		
		input = (element.value = element.value.trim()).toString();

		// Clear the timeout and set it so the watch is undeclared;
		window.clearTimeout(watch);
		watch = null;
		
		fn = {
			pattern: function(type) {
				if (!(input.devoid())) {
					return (valid8.pattern[type].test(input))? true:false;
				} else return true;
			},
			"function": function(type) {
				if (!(input.devoid())) {
					return valid8["function"][type].call(element, input, type);
				} else return true;
			}
		};
		
		(function() {
			for (var i in valid8.cipher[type]) {
				var result = undefined;
				if (i != "required") {
					result = fn[i](type)
					if (result === false) {
						valid = false;
						valid8.oops(element).concoct(
							(valid8.cipher[element.getAttribute("data-type")] && valid8.cipher[element.getAttribute("data-type")][i])? valid8.cipher[element.getAttribute("data-type")][i]:"Error"
						);
						break;
					} else if (result === null) {
						valid = false;
						break;
					}
				}
			}
		}());

		if (valid) {
			invalid.remove(element);
			valid8.oops(element).consume();
			return true;
		}
		else {
			if (invalid.indexOf(element) == -1) invalid.push(element);
			return false;
		}
	}
	
	// Function to assign listeners, events and
	// validation to form fields;
	function spy(element) {
		$(element).on('blur keyup',function(event){
			if (event.type == "blur") {
				// User has left the field - run tests;
				validate(event.target);
			}
			else if (event.type == "keyup") {
				// User has typed something, wait 2.5 seconds and then run tests;
				if (watch != null) {
					window.clearTimeout(watch);
					watch = null;
					
				}
				watch = window.setTimeout(validate,1500,[event.target])
			}
		});
	}
	
	this.verify = function() {
		
		if (invalid.length <= 0) {
			elements.map(validate);
			
			if (invalid.length > 0) {
				return false;
			}
			
			else {
				
			}
		}
		
	}
	
	if ($(form).parents("#moodle_container").length) {
		var fn = {};
				fn[form.getAttribute("shc:stamp")] = function() {
					delete valid8.forms[form.getAttribute("shc:stamp")];
					$(document).off("moodle-preclose", fn[form.getAttribute("shc:stamp")])
				}
		$(document).on('moodle-preclose', fn[form.getAttribute("shc:stamp")])
	}
	
	return this;
}

shcJSL.methods.valid8 = function(target) {
	var command = (this.constructor == String)? this.toString():undefined,	// If there is an action to be performed on the form (such as verify);
			stamp		= target.getAttribute("shc:stamp"),		// The stamp identifier on the form;
			options = shcJSL.options(stamp);	// Any options that were set with the form;
			
	if (!valid8.forms[stamp]) valid8.forms[stamp] = (options && options.valid8)? new valid8.form(target, options.valid8):new valid8.form(target);
	
	if (command && valid8.forms[stamp] && valid8.forms[stamp][command]) valid8.forms[stamp][command]();

}

/**
	 * Event Assigner
	 * 
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0
	 */
if (shcJSL && shcJSL.gizmos)  {
	shcJSL.gizmos.valid8 = function(element) {
		shcJSL.get(element).valid8();
	}
}

$(window).trigger("valid8");