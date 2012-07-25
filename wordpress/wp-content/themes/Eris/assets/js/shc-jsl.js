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
	
	/*
	 * Added Array.remove([entry]) functionality to Array
	 */

Array.prototype.remove = function(e) {
	var t, _ref;
  if ((t = this.indexOf(e)) > -1) {return ([].splice.apply(this, [t, t - t + 1].concat(_ref = [])), _ref);}
};

/*
 * Add Array.map(callback, [thisArg]) functionality to
 * IE8 - which does not support Array.map() natively.
 * 
 * NOTE: pulled from Mozilla Developer Network (MDN)
 * https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/map
 */
if (!Array.prototype.map) {
  Array.prototype.map = function(callback, thisArg) {
    var T, A, k;
    if (this == null) {
      throw new TypeError(" this is null or not defined");
    }
    var O = Object(this);
    var len = O.length >>> 0;
    if ({}.toString.call(callback) != "[object Function]") {
      throw new TypeError(callback + " is not a function");
    }
    if (thisArg) {
      T = thisArg;
    }
    A = new Array(len);
    k = 0;
    while(k < len) {
      var kValue, mappedValue;
      if (k in O) {
        kValue = O[ k ];
        mappedValue = callback.call(T, kValue, k, O);
				A[ k ] = mappedValue;
      }
      k++;
    }
    return A;
  };      
}

shcJSL.methods = {
	test: function(a) {
		console.log(a);
		alert('tacos');
	}
}

shcJSL.get = function(element) {
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
			collection = collection.concat([].slice.call(getTags(element)));
		}
	} else if (typeof element == "object") {
		// element is an HTMLObject
	 	collection.push(element);
	}
	
	// Bind methods from shcJSL.methods to the output Array
	// Use a function call for the body of 'FOR' loop instead of
	// braces.
	for (var method in shcJSL.methods)(
		function(n,m) {
			// Create a new scope
			// n/m are key/value of the method object (n = method name, m = method)
			collection[n] = function(x) {
				collection.map(m,x);
				return collection;
			}
		}(method, shcJSL.methods[method]))
		
		return collection;
}

fruits ={};
fruits.get = function(css){ //dom utility
	
	
	
 //a couple of node harvesters, using id and tag name...
   function el(id){ return document.getElementById(id);}
   function tags(elm){return document.getElementsByTagName(elm);}

 //collect output:
 	console.log(typeof css);
 
   if(css[0]=="#"){//id
      out.push(el(css.slice(1)));
   } else if (typeof css == "object") {
   		out.push(css);
   }
   else{//tags
      out=out.concat([].slice.call(tags(css)));
   };//end if id or tagName
  
 //define some methods for the utility:
    var meths={
        hide:function(a){a.style.display="none";},
        show:function(a){a.style.display="";},
        remove:function(a){a.parentNode.removeChild(a);},
        color:function(a){a.style.color=this||a.style.color;},
        size:function(a){a.style.fontSize=this||a.style.fontSize;}
    };//end meths

 //bind the methods to the collection array:
    for(var method in shcJSL.methods)
    (function(n,m){
       out[n]=function(x){out.map(m,x); return out;}
    }(method, shcJSL.methods[method]));//next method

  return out;
}//end X dom utility



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

shcJSL.gizmos.persistr = function(element) {
	var offsetTop;	// (Int) pixel difference from the top of the page
	var persisted;	// (HTMLObject) the persisted element
	
	persisted = element;
	offsetTop = $(element).offset().top;
	
	$(window).scroll(function(event) {
		var yScroll;	// (Int) Current position of the top of the page via scroll
		
		yScroll = $(this).scrollTop();
		
		if (yScroll >= offsetTop) {
			$(element).css("position","fixed")
		} else {
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
		shcJSL.gizmos.activate();
	}
)
