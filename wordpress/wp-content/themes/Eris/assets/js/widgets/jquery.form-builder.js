/** 
 * @author Tim Steele
 * @created 2012-06-01
 *
 * @title Communities Master Script
 * @description
 * 
 * @requires jQuery 1.7.2
 * 
 * @version
 * 1.0 [2012-06-01]
 * ----------------
 * - Create document
 */

/*
 * [1.0] CONFIGURATIONS
 * --------------------
 * Settings for Form Builder based on your website.
 * 
 * fb.g: (Object) This is the global object for your website's scripts.
 * 
 * fb.p: (String) This is the prefix used for name spaces on attributes.
 */

var fb = new Object;

fb.g = shcJSWDL.widgets;
fb.p = "shc";

fb.g.form = form = function(element) {

	var options;	// Options for the element, from the *:options attribute on element
	
	// Private Methods
	
	// Public Methods
	
	// Init
	if (typeof $(element).attr(fb.p + ":options") == "string") options = eval("(" + $(element).attr(fb.p + ":options") + ")");
	
}

