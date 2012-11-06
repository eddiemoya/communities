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
 * 
 * 2.0 [2012-10-03]
 * -----------------
 * - Added bulletin functionality
 * - Added dynamic script manager
 * - Added gizmo grouping (gadgets)
 * - Optimized code, removing eval() and switching to JSON
 */

/* 
	The master object for this library.
  Sears Holding Corp (shc) (J)ava(S)cript (L)ibrary [shcJSL].
  The object can be referenced as '$S' for short.
*/
var shcJSL, $S;
if (!shcJSL) shcJSL = $S = {};

/*
	SOCKETS
	-------
	(Object)
	shcJSL gizmos (widgets) object.
	
	[key] "widget name" : [value] "widget URL"
*/
shcJSL.sockets = {
	"moodle": "/wp-content/themes/Eris/assets/js/gizmos/shcJSL.moodle.js",
	"openID": "/wp-content/themes/Eris/assets/js/gizmos/shcJSL.openID.js",
	"valid8": "/wp-content/themes/Eris/assets/js/gizmos/shcJSL.valid8.js"
}


/*
	NATIVE JAVASCRIPT EXTENSIONS
	----------------------------
	Extending native JavaScript objects with additional
	functionality. 
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

/*
	 
*/

shcJSL.bulletin = {};

/*
	LAZEE
	-----
	JavaScript script manager and lazy loader.
*/
shcJSL.lazee = new function() {
	var getFileName;	// (Function) get's the JavaScript file name
	var self = this;	// (Object) Parent function/object	
	queue = [];	// (Array) Array of functions to be performed on page load
	
	function getFileName(file) {
		return (shcJSL.functions.createNewElement("a",null,{href:file})).pathname.split('/').pop();
	}
	
	function checkForScript(file, add) {
		if (shcJSL.bulletin[file] === undefined) {
			if (add !== false) shcJSL.bulletin[file ] = true;
			return false;
		} else {
			return true;
		}
	}
	
	this.add = function(script) {
		if (shcJSL.functions.getObjectType(script) === "[object String]") {
			queue[queue.length] = function() {self.load(script);}
		} else if (shcJSL.functions.getObjectType(script) === "[object Function]") {
			queue[queue.length] = function() {script()}
		}
	}
	
	this.load = function(url, notifier, callback, data) {
		var radar;	// (Function) Function that looks for the notifier for script load
		var reflex;	// (Function) The function that calls the callback function, passes data
		var wait;		// (Integer) The time limit to wait for the script to load
		
		wait = 400; // 20 seconds
		
		function radar() {
			setTimeout(
				function() {
					limit--;
					if (typeof window[notifier] !== undefined) {
						reflex();
					} else if (limit > 0) radar();
					else {
						console.log("ERROR: Script failed to load [" + url + "]")
					}
				}, // END function
				200 // Timeout interval
			) // END setTimeout
		}
		
		function reflex() {
			if (callback && typeof callback == "function") {
				try {callback(data);}
				catch(e) {console.log("ERROR: Bad callback [" + url + "]")}
			}
		}
		if (!checkForScript(getFileName(url))) {
			document.getElementsByTagName("head").item(0).appendChild(shcJSL.functions.createNewElement('script',null,{'src':url, "type":"text/javascript"}));
		}
		
		if (notifier && callback) radar();
	}
	
	this.plug = function(socket) {
		if (shcJSL.sockets[socket]) {
			var path;	//	Path to the socket's URL
			var file;	//	File's name
			
			var path = shcJSL.sockets[socket]; 
			var file = getFileName(path);
			
			/* Check to see if the script exists already. If
			 * it doesn't exist, then add it and return true
			 * otherwise return false.
			 * 
			 * If returns true, invoke the add function with
			 * the url as a string as a parameter. It return
			 * false, then invoke the add function with a
			 * function to execute as the parameter.
			 */
			(!checkForScript(file, false))? self.add(path):self.add( function() {
				$(window).trigger(socket);
			}); // End checkForScript 
			
		}
		else console.log("Missing Plug In: " + socket);
	}
	
	this.enact = function() {
		for (var i = 0; i < queue.length; i++) {
			queue[i]();
		}
	}
	
}

/*
	SHCJSL FUNCTIONS
	----------------
	Functions to interact with SHCJSL specific
	properties and functionality. 
*/

shcJSL.methods = {};

/**
* get
* ---
* 
* @author Tim Steele
* @since 1.0
* 
* @param element (HTMLObject|ID Selector|Element TAG) Elements to get
* 
* @return Array of elements with available SHCJSL actions
*/
shcJSL.get = function(element) {
	var collection; // (Array) array of objects with shcJSL.methods.
	var getID;			// (Method) method to get element by ID.
	var getTags;		// (Method) method to get elements by tag name.
	var type;				// (Function) Shorthand for calling shcJSL.functions.getObjectType
	
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

	type = shcJSL.functions.getObjectType;

	// Declare collection as an array
	collection = [];
			
	// Take the element(s) and turn it into a true array
	if (type(element) == "[object String]") {
		if (element[0] == "#") {	// Selector is an ID
			collection.push(getID(element.slice(1)));
		// Need to add in class selector at some point.
		} else {	// Selector is an element OR not a valid selector
			collection = shcJSL.functions.sequence(getTags(element));
		}
	} else if (type(element) == "[object Object]" || (type(element).toString()).indexOf("HTML") != -1) {
		// element is an HTMLObject
		collection.push(element); 
	} else if (type(element) == "[object Array]") {
		// element is already an array
	 	collection = shcJSL.functions.sequence(element);
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
* cookies
* -------
* C.R.U.D. cookies handler.
* Examples:
* [create]
* shcJSL.cookie(%new cookie name%).bake({settings:value, expiration:value, path:value});
* 
* [retrieve]
* shcJSL.cookie(%cookie name%).serve();
* 
* [update]
* shcJSL.cookie(%cookie name%).bake({settings:value, expiration:value, path:value});
* 
* [delete]
* shcJSL.cookie(%cookie name%).eat();
* 
* @author Tim Steele
* @since 1.0
* 
* @param cookie (String) name of the cookie
*/

shcJSL.cookies = function(cookie) {
	var bakery;				// (Object) methods to be used on cookies
	var cookies = [];	// (Array) contains the cookie object
	var defaults;			// Default settings for the cookie
	var getTimes;			// (Function) get the time for the cookie expiration
	var settings;			// Settings for the cookie
	
	/**
	 * @author Tim Steele
	 * 
	 * List of actions to perform on the cookie
	 */
	bakery = {
		/**
		 * bake
		 * ----
		 * Create a new cookie or update existing cookie
		 * @author Tim Steele
		 */
		bake: function(cookie) {
			var params = this;
			settings = $.extend({}, params);
			document.cookie = cookie + "=" + ((settings.value)? settings.value:'undefined') + ((settings.expiration)? ("; expires=" + getTimes(settings.expiration)):"") + "; path=" + ((settings.path)? settings.path:"/");
		},
		/**
		 * serve
		 * -----
		 * retrieve a cookie's value
		 * @author Tim Steele
		 * 
		 * @return Cookie's value
		 */
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
		/**
		 * eat
		 * ---
		 * delete cookie
		 * @author Tim Steele
		 */
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
/**
* gadget
* ------
* 
* @author Tim Steele
* @since 2.0
* 
* @param gadget (String) Gadget name
* 
* @return (Object) an object with all the sprockets in a gadget
*/
shcJSL.gadget = function(gadget) {
	if (shcJSL.apparatus[gadget] === undefined) return false;
	else return shcJSL.apparatus[gadget];
}

/**
* options
* -------
* 
* @author Tim Steele
* @since 2.0
* 
* @param stamp (Integer) The stamp number of an element
* 
* @return (Object) SHC Gizmo options of element of corresponding stamp #
*/
shcJSL.options = function(stamp) {
	if (shcJSL.schematic[stamp] === undefined) return false;
	else return shcJSL.schematic[stamp];
}

/*
	SHCJSL SHORTCUTS
	----------------
	Functions to assist in manipulating the DOM
*/

shcJSL.functions = shcJSL.F = {}

shcJSL.functions.createNewElement = function(e, c, a) {
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

shcJSL.functions.preloadImages = function(html) {
	if (html) {
		var image = new Image(); // Dummy new image object;
		
		jQuery.each($(html).find('img'), function() {
			image.src = this.src;
		})
		
		return html;
	} else return false;
}

shcJSL.functions.renderHTML = function(parent, html) {
	if ((parent && html) && typeof html == 'string') {
		parent.innerHTML = html;
	}
	return parent;
}

shcJSL.functions.first = function(element) {
	var firstChild = element.firstChild;
	while (firstChild.nodeName == "#text") {
		firstChild = firstChild.nextSibling;
	}
	return firstChild;
}

shcJSL.functions.addChildren = function(p, c) {
	if (p && c) {
		for (var i=0; i < c.length; i++) {
			p.appendChild(c[i]);
		}
		return p;
	}
}

shcJSL.functions.setStyles = function(e, s) {
	if (typeof s != undefined && typeof e != undefined) {
		for (var i in s) {
			e.style[i] = s[i];
		}
		return e;
	}
}

shcJSL.functions.formDataToJSON = function(form) {
	var cereal; // Serialized string of the form
	var jason;	// (String) Our JSON object
	var scrub;	// (Function) Function to clean up values for JSON
	var values; // (Array) values pulled from the serialized string
	
	cereal = $(form).serialize();
	
	values = cereal.split("&"); 
	
	jason = "{";
	if (values.length > 0) {
		for (var i=0;i<values.length;i++) {
			jason += values[i].replace(/^(.*)=(.*)?/,scrub)
		}
	}
	
	jason = jason.substr(0,jason.length -1) + "}";
	
	function scrub(match, key, value, offset, string) {
		key = key.replace(/\+/g, " ");
		value = value.replace(/\+/g, " ");
		json = '"' + escape(key) + '":"' + escape(value) + '",';
		return json;
	}
	
	return jason;
}

shcJSL.functions.sequence = function(array) {
	var sequence = [];	// New 'true' array
	try {sequence = sequence.concat([].slice.call(array));}
	catch(e) {
		for (var i=0; i < array.length; i++) {
			sequence.push(array[i]);
		}
	}
	return sequence;
}

shcJSL.functions.id = function(id) {return document.getElementById(id);}

shcJSL.functions.getObjectType = function(object) {
	return Object.prototype.toString.call(object);
}

shcJSL.schematic = {}; shcJSL.apparatus = {}; shcJSL.gizmos = {};

/*
 * The governor
 */

shcJSL.governor = new function() {
	this.populace = {};	// (Object) contains list of elements keyed to their gizmo
	
	var self = this;
	
	this.architect = function(gizmo, element) {
		if (self.populace[gizmo] === undefined) self.populace[gizmo] = [];
		self.populace[gizmo].push(element);
	}
	
	this.activate = function() {
		for (var i in self.populace) {
			shcJSL.lazee.plug(i);
			$(window).bind(i, {gizmo: i, elements:self.populace[i]}, function(event){
				var data = event.data;
				try {
					shcJSL.gizmos[event.data.gizmo](event.data.elements);
					delete shcJSL.governor.populace[event.data.gizmo];
				} catch(error) { console.log("Failed to instantiate widget " + event.data.gizmo + " - " + error); }
			});
		}
		if (!window.loaded) {
			$(window).load(shcJSL.lazee.enact)
		} else {
			shcJSL.lazee.enact();
		}
	}
	
};


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
 */
shcJSL.gizmos.activate = function(event, parent) {
	var draft = "";	// Temporary object for storing option data
	var Parent;			// (HTMLObject) parent argument, or if null, the body element
	var Selector;		// (Array) Array of selectors
	
	var date = new Date();
	var uid = (new Array( date.getYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(),  date.getSeconds(), date.getMilliseconds() )).join("");
	
	Parent = parent || $('body').get(0);
	Selector = new Array("*[shc\\:gizmo]", "*[shc\\:gadget]")

	$.each(
		$(Parent).find(Selector.join()),	// Array of elements matching selector inside parent
		function(index, value) {
			var date 			= new Date();																// New data object for unique ID
			var gadget 		= this.getAttribute("shc:gadget");					// shc:gadget attribute || null
			var gizmo 		= this.getAttribute("shc:gizmo");						// shc:gizmo attribute || null
			var options 	= this.getAttribute("shc:gizmo:options");		// shc:gizmo:options attrubute || null
			var sprocket 	= this.getAttribute("shc:gadget:sprocket");	// shc:gadget:sprocket attribute || null
			var stamp	=	uid++;																				// Unique SHCJSL identifier
			
			// Assign unique ID
			this.setAttribute("shc:stamp",stamp);

			/*
			 * Only group elemens that have both a gadget
			 * and a sprocket. 
			 */
			if (gadget && sprocket) {
				if (shcJSL.apparatus[gadget] === undefined) shcJSL.apparatus[gadget] = {};
				shcJSL.apparatus[gadget][sprocket] = this;
			}			
			
			if (options) {
				if (draft.length > 0) draft += ",";
				draft += '"' + stamp.toString() + '":' + options;
			}
			
			if (gizmo) {
				var giz;	// (String|Array) Gizmo(s) assigned to the element
				
				(/^\[.*\]$/.test(gizmo))? gizmo.replace(/^\[(.*)\]$/, function(match, key, value, offset, string) {giz = key.split(/, ?/g);}):giz = gizmo;
				
				if (shcJSL.functions.getObjectType(giz) === "[object String]") shcJSL.governor.architect(giz, this)
				else if (shcJSL.functions.getObjectType(giz) === "[object Array]") {
					for (var i=0; i < giz.length; i++) {
						shcJSL.governor.architect(giz[i], this);
					} 
				}

			}
			
		} // End EACH function
	);	// End EACH	
	
	
	// Convert all options into JSON Object
	draft = JSON.parse("{" + draft + "}");
	
	/* 
	 * Shallow copy Objects into schematics
	 * Put in to new scope, to keep var i 
	 * fresh.
	 */
	(function() {
		for (var i in draft) {
			shcJSL.schematic[i] = draft[i];
		}
	})();
	
	shcJSL.governor.activate();
		
	return;
	
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
jQuery(document).ready(function() {
	shcJSL.gizmos.activate();
	window.loaded = true;
});
