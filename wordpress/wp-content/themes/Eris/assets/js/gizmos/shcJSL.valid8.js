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

valid8 = {};

/**
 * CUSTOM ERROR MESSAGES
 * Client is looking for custom error messages
 * for fields. These are the custom error messages
 * tied to the field types.
 */

valid8.cipher = {
	"category": {
		"required": "Please choose a category."
	},
	"confirm-email": {
		"match": "Your email does not match. Please check and try again."
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
		"availability": "That screen name has already been taken. Please select a new one.",
		"pattern": "Please follow the screen name guidelines."
	},
	"zip-code": {
		"pattern": "Please enter a valid Zip code."
	}
}

/**
 * REGEX VALIDATION PATTERNS
 */
valid8.patterns = {
	"email": /^.+@.+?\.[a-zA-Z]{2,}$/,
	"password": /^\w*(?=\w{8,})(?=\w*\d)(?=\w*[a-zA-Z])(?!\w*_)\w*$/,
	"zip-code": /(^\d{5})(-\d{4})?$/
}

valid8.forms 	= {}

valid8.form		= function(form, options) {
	var self = this;	// This object
	
	var elements;			// (Array) All the form fields
	var functions;		// (Object) Contains functions to use on form/form fields
	var form = form;	// (HTMLElement) The form HTMLElement
	var options;			// (Object) Any options that exist with the form
	var spy;			// (Function) Assign 
	
	// If options exist, set 'em;
	options = (options)? options:undefined;
	
	
	
	// Gather the form elements
	elements = shcJSL.functions.sequence(form.elements);
	elements.map(spy);
	
	// Function to assign listeners, events and
	// validation to form fields;
	function spy(element) {
		console.log(element);	
	}
	
	this.verify = function() {
		alert("BANANANAS");
	}
	
}

shcJSL.methods.valid8 = function(target) {
	var command;	// If there is an action to be performed on the form (such as verify);
	var stamp;		// The stamp identifier on the form;
	var options;	// Any options that were set with the form;

	command = (this.constructor == String)? this.toString():undefined;
	stamp	= target.getAttribute("shc:stamp");
		
	options = shcJSL.options(stamp);
			
	if (!valid8.forms[stamp]) valid8.forms[stamp] = (options && options.valid8)? new valid8.form(target, options.valid8):new valid8.form(target);
	
	if (command && valid8.forms[stamp] && valid8.forms[stamp][command]) valid8.forms[stamp][command]();

}

// TRANSfORMER.blunder = function(element) {
	// var error;			// (Object) new error message
	// var goofs = [];	// (Array) contains goof object
	// var methods;		// (Object) methods for errors
// 	
	// function error(message) {
		// return shcJSL.addChildren(shcJSL.createNewElement("p","error-message"),[shcJSL.createNewElement("span","error-pointer"),document.createTextNode(message)])
	// }
// 	
	// methods = {
		// create: function(param) {
			// var err = new error(this);
			// if (($(param.parentNode).children(".error-message")).length < 1) {
				// param.parentNode.insertBefore(err, param.nextSibling);
				// $(param.parentNode).addClass("error");
			// } else {
				// param.parentNode.replaceChild(err, $(param.parentNode).children(".error-message").get(0));
			// }
		// },
		// destroy: function(param) {
			// var err = $(param.parentNode).children(".error-message");
			// if (err.length > 0) {
				// param.parentNode.removeChild($(err).get(0));
				// $(param.parentNode).removeClass("error");
			// }
		// }
	// }
// 	
	// goofs.push(element);
// 	
	// for (var action in methods)(
		// function(n,m) {
			// goofs[n] = function(x) {
				// return goofs.map(m,x);
			// }
		// }(action, methods[action])
	// )
// 	
	// return goofs;
// }

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