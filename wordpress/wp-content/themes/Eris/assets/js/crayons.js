(function( window ) {
	
var Tim = (function() {

	// Define a local copy of Tim
	var Tim = function( selector, context ) {
			// The Tim object is actually just the init constructor 'enhanced'
			return new Tim.fn.init( selector, context);
	};
	

	Tim.fn = Tim.prototype = {
		constructor: Tim,
		init: function( element, context, rootTim ) {
			
			var collection; // (Array) array of objects with shcJSL.methods.
			var getID;			// (Method) method to get element by ID.
			var getTags;		// (Method) method to get elements by tag name.
			
			getID = function(id) {
				/**
				 * @param id: ID of element
				 */
				return document.getElementById(id)
			}
			getTags = function(tag) {
					/**
			 		 * @param tag name of element
			 		 */
			 		return document.getElementsByTagName(tag);
			}
		
			// Declare collection as an array
			collection = [];
			
			// Take the element(s) and turn it into a true array
			if (typeof element == "string") {
				if (element[0] == "#") {	// Selector is an ID
					collection.push(getID(element.slice(1)));
				// Need to add in class selector at some point.
				} else {	// Selector is an element OR not a valid selector
					collection = shcJSL.sequence(getTags(element));
				}
			} else if (typeof element == "object") {
				// element is an HTMLObject
			 	collection.push(element);
			}
			
			// Bind methods from shcJSL.methods to the output Array
			// Use a function call for the body of 'FOR' loop instead of
			// braces.
			for (var method in testMeth)(
				function(n,m) {
					// Create a new scope
					// n/m are key/value of the method object (n = method name, m = method)
					collection[n] = function(x) {
						collection.map(m,x);
						return collection;
					}
				}(method, testMeth[method]))
				
				return collection;
					
					
					// console.log(selector);
					// console.log(this);
// 			
					// return selector;
				}
			};

	// Give the init function the Tim prototype for later instantiation
	Tim.fn.init.prototype = Tim.fn;
	
	return Tim;

})();


var testMeth = {
	doh: function(target) {
		console.log("DOH");
		console.log(this);
		console.log(target);
	},
	duh: function(target) {
		console.log("DUH");
		console.log(this);
		console.log(target);
	}
}

// jQuery.fn.extend({
	// text: function( value ) {
		// return value;
	// }
// });

// Expose Tim to the global object
window.Tim = Tim;

})( window );


// 
// shcJSL.hash = function(element, properties) {
	// var methods,	// Methods of the hash object;
		// table = [];		// Array to hold all the objects;
// 	
	// methods = {
		// put: function(value) {
			// console.log("PUT");
			// console.log(this);
			// console.log(value);
		// },
		// get: function(value) {
			// console.log("GET");
			// console.log(this);
			// console.log(value);
		// }
	// }
// 
	// for (var method in methods)(
		// function(n,m) {
			// // n/m are key/value of the method object (n = method name, m = method)
			// table[n] = function(x) {
				// m.call(element, x);
				// return table;
			// }
		// }(method, methods[method]))
// 		
	// return table;
// }
