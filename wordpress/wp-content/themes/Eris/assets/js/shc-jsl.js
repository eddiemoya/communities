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

shcJSL.methods = {}

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
			collection = shcJSL.sequence(getTags(element));
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

/**
 * @author Tim Steele
 * C.R.U.D. Cookie Handler
 */

shcJSL.cookies = function(cookie) {
	var bakery;				// (Object) methods to be used on cookies
	var cookies = [];	// (Array) contains the cookie object
	var defaults;			// Default settings for the cookie
	var getTimes;			// (Function) get the time for the cookie expiration
	var settings;			// Settings for the cookie
	
	bakery = {
		bake: function(cookie) {
			var params = this;
			settings = $.extend({}, params);
			document.cookie = cookie + "=" + ((settings.value)? settings.value:'undefined') + ((settings.expiration)? ("; expires=" + getTimes(settings.expiration)):"") + "; path=" + ((settings.path)? settings.path:"/");
		},
		serve: function(cookie) {
			var cookie = cookie;
			
			if (cookie) {
				cookie = cookie + "=";
			}
			$Cookies = document.cookie.split(';');
			var value;
			for (var i=0;i < $Cookies.length;i++) {
				$cookie = $Cookies[i];
				while ($cookie.charAt(0) == ' ') $cookie = $cookie.substring(1, $cookie.length);

				if ($cookie.indexOf(cookie) == 0) {
					value = $cookie.substr(cookie.length);
				}
			}
			return value;
		},
		eat: function(cookie) {
			bakery.bake.call({expiration:'-1m'}, cookie)
		}
	}
	
	function getTimes(time) {
		var timeEquations; // Equiations for figuring out time values
		timeEquations = {
			m: function(n) {return (n*60*1000);},
			h: function(n) {return (n*60*60*1000);},
			d: function(n) {return (n*24*60*60*1000);},
			y: function(n) {return (n*365*24*60*60*1000);}
		}
		var date = new Date();
				date.setTime(date.getTime() + (timeEquations[time.match(/([mhdy])$/)[0]](time.match(/^(-?\d+)/)[0])));
		return date.toGMTString();
	}
	cookies.push(cookie);
	for (var action in bakery)(
		function(n,m) {
			cookies[n] = function(x) {
				return cookies.map(m,x).toString();
			}
		}(action, bakery[action])
	)
	
	return cookies;
}


/*
	[2.0] SEARS METHODS
	-------------------
	
*/

shcJSL.createNewElement = function(e, c, a) {
	var newElement; // New element that will be created;
	
	newElement = document.createElement(e);
	if (c != null) newElement.className = c;
	if (typeof a != 'undefined' && typeof a == 'object') {
		for (var i in a) {
			newElement.setAttribute(i, a[i]);
		}
	}
	return newElement;
}

shcJSL.preloadImages = function(html) {
	if (html) {
		var image = new Image(); // Dummy new image object;
		
		jQuery.each($(html).find('img'), function() {
			image.src = this.src;
		})
		
		return html;
	} else return false;
}

shcJSL.renderHTML = function(parent, html) {
	if ((parent && html) && typeof html == 'string') {
		parent.innerHTML = html;
	}
	return parent;
}

shcJSL.first = function(element) {
	var firstChild = element.firstChild;
	while (firstChild.nodeName == "#text") {
		firstChild = firstChild.nextSibling;
	}
	return firstChild;
}

shcJSL.addChildren = function(p, c) {
	if (p && c) {
		for (var i=0; i < c.length; i++) {
			p.appendChild(c[i]);
		}
		return p;
	}
}

shcJSL.setStyles = function(e, s) {
	if (typeof s != undefined && typeof e != undefined) {
		for (var i in s) {
			e.style[i] = s[i];
		}
		return e;
	}
}

shcJSL.formDataToJSON = function(form) {
	
	var fields;	// Form elements;
	var form = form;	// Set the form to equal the form;
	
	fields = form.elements;
	
	console.log(fields)
	
	var cereal; // Serialized string of the form
	var jason;	// (String) Our JSON object
	var scrub;	// (Function) Function to clean up values for JSON
	var values; // (Array) values pulled from the serialized string
	
	cereal = $(form).serialize();
	
	console.log(cereal);
	
	values = cereal.split("&"); 
	
	jason = "{";
	if (values.length > 0) {
		for (var i=0;i<values.length;i++) {
			jason += values[i].replace(/^(.*)=(.*)?/,scrub)
		}
	}
	
	jason = jason.substr(0,jason.length -1) + "}";
	
	console.log(jason);
	
	function scrub(match, key, value, offset, string) {
		key = key.replace(/\+/g, " ");
		value = value.replace(/\+/g, " ");
		json = '"' + escape(key) + '":"' + escape(value) + '",';
		return json;
	}
	
	return jason;
}

shcJSL.sequence = function(array) {
	var sequence = [];	// New 'true' array
	try {sequence = sequence.concat([].slice.call(array));}
	catch(e) {
		for (var i=0; i < array.length; i++) {
			sequence.push(array[i]);
		}
	}
	return sequence;
}
/*
	[2.0] WIDGETS
	-------------
	Creating and activating widgets on page.
	
	NOTE: Any shc:widget 'widgets' have to be assigned to the shcJSL.widget object.
	ex. shcJSL.widgets.modal = { <modal object> }
*/

shcJSL.gizmos = new Object();

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
 * 		This is the jQuery selector string for finding the gizmos to activate.
 */
shcJSL.gizmos.activate = function(event, parent, selector) {
	var Parent;		// (HTMLObject) parent argument, or if null, the body element
	var Selector;	// (String) selector arguement or default jQuery selector based on attribute
	
	Parent = parent || $('body').get(0);
	Selector = selector || "*[shc\\:gizmo]";	
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
				($(this).attr("shc:name") != undefined)? window[$(this).attr("shc:name")] = new shcJSL.gizmos[$(this).attr(attribute)](this):new shcJSL.gizmos[$(this).attr(attribute)](this);
				// If it can not create the object, error out gracefully
				// and log the error, the widget that failed and the
				// error message
			} catch(error) {
				if (console && console.log) console.log("Failed to instantiate widget " + attribute + "[" + $(this).attr(attribute) + "] - " + error);
			}
		} // END $.each function
	) // END $.each
}

/**
 * @author Tim Steele
 * @description:
 * This object keeps track of what gizmos have been loaded 
 * into the page. 
 */
shcJSL.gizmos.bulletin = {}

/**
 * @author Tim Steele
 * @param element: 
 */
shcJSL.gizmos.persistr = function(element) {
	var offset;	// (Int) pixel difference from the top of the page
	var persist;		// (Function) function to persist the element
	var sticker;	// (HTMLObject) the persisted element
	
	sticker = element;
	offset = ($(sticker).offset().top).toFixed(0);
	
	function persist(event) {
		var y;	// Y scroll, y-axis of the scroll bar
		
		y = $(window).scrollTop();
		if (y >= offset) {
			$(sticker).parent().css("padding-top",$(sticker).outerHeight());
			$(sticker).addClass("persist");
		} else {
			$(sticker).removeClass("persist");
			$(sticker).parent().css("padding-top",0)	
		}
	}
	
	$(window).bind('scroll', persist);
	persist();
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
