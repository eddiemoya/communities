/** 
 * @author Tim Steele
 * @created 2012-05-31
 *
 * @title Communities Master Script
 * @description
 * 
 * @requires jQuery 1.7.2
 * 
 * @version
 * 1.0 [2012-05-31]
 * ----------------
 * - Create document
 * 
 * 1.01 [2012-07-03]
 * -----------------
 * - Added persistent header widget
 */

/* 
	The master object for this library.
  Sears Holding Corp (shc) (J)ava(S)cript (L)ibrary [shcJSL].
  The object can be referenced as '$S' for short.
*/
var shcJSL, $S;
if (!shcJSL) shcJSL = $S = {};

/*
	[1.0] NATIVE JAVASCRIPT EXTENSIONS
	----------------------------------
	Extending native JavaScript objects with additional
	functionality. 
*/
	/*
		[1.1] ARRAY
		-----------
	*/
	// Remove an entry from an array:
	// e.x.: [array].remove([entry])

Array.prototype.remove = function(e) {
	var t, _ref;
  if ((t = this.indexOf(e)) > -1) {return ([].splice.apply(this, [t, t - t + 1].concat(_ref = [])), _ref);}
};

/*
	[2.0] WIDGETS
	-------------
	Creating and activating widgets on page.
	
	NOTE: Any shc:widget 'widgets' have to be assigned to the shcJSL.widget object.
	ex. shcJSL.widgets.modal = { <modal object> }
*/

shcJSL.widgets = new Object();

/*
 * shcJSL.widgets.activate Arguments
 * 	event:
 * 		If shcJSL.widgets is activated through a custom event binding
 * 		then the first arguement passed will be event. Otherwise, the first
 * 		argument will need to be set to null.
 * 
 * 	parent:
 * 		This is the parent element -- the activate function will look for
 * 		widgets inside the parent element to activate. If argument is not 
 * 		set then the script defaults to the body element.
 * 
 * 	selector:
 * 		This is the jQuery selector string for finding the widgets to activate.
 */
shcJSL.widgets.activate = function(event, parent, selector) {
	var Parent;		// (HTMLObject) parent argument, or if null, the body element
	var Selector;	// (String) selector arguement or default jQuery selector based on attribute
	
	Parent = parent || $('body').get(0);
	Selector = selector || "*[shc\\:widget]";
	
	$.each(
		$(Parent).find(Selector),	// Array of elements matching selector inside parent
		function(index, value) {
			var attribute; // Cleaned attribute derived from selector
							
			// Remove the selector code to get attribute
			(Selector.toString().indexOf("\\") != -1)? attribute = ((Selector.split("\\")[0]) + (Selector.split("\\")[1])).replace(/(\*?\[)|(\])/g, '').toString():attribute = Selector.toString().replace(/(\*?\[)|(\])/g, '').toString();
			try {
				// If the the widget has 'shc:name' attribute, assign the
				// JavaScript object [shc:widget] to the global variable
				// that is [shc:name]
					($(this).attr("shc:name") != undefined)? window[$(this).attr("shc:name")] = new shcJSL.widgets[$(this).attr(attribute)](this):new shcJSL.widgets[$(this).attr(attribute)](this);
				
				// If it can not create the object, error out gracefully
				// and log the error, the widget that failed and the
				// error message
			} catch(error) {console.log("Failed to instantiate widget " + attribute + "[" + $(this).attr(attribute) + "] - " + error);}
		} // END $.each function
	) // END $.each
}

/**
 * @author Tim Steele
 * @param element: 
 */

shcJSL.widgets.persistr = function(element) {
	var offsetTop;	// (Int) pixel difference from the top of the page
	var persisted;	// (HTMLObject) the persisted element
	
	persisted = element;
	offsetTop = $(element).offset().top;
	
	$(window).scroll(function(event) {
		var yScroll;	// (Int) Current position of the top of the page via scroll
		
		yScroll = $(this).scrollTop();
		
		if (yScroll >= offsetTop) {
			// console.log("POSITION TO FIX"); 
			// console.log('YSCROLL: ' + yScroll);
			// console.log("OFFSETTOP: " + offsetTop);
			$(element).css("position","fixed")
		} else {
			// console.log("POSITION FROM FIXED"); 
			// console.log('YSCROLL: ' + yScroll);
			// console.log("OFFSETTOP: " + offsetTop);
			$(element).css("position","relative");
		}
	});
	
}

/*
	[3.0] ONLOAD EVENTS
	-------------------
	Events to fire on document load and ready.
*/
jQuery(window).load(
	function() {
		shcJSL.widgets.activate();
	}
)
