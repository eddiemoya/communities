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

// Is a string just a bunch of white space?
String.prototype.devoid = function() {
	return (!/\S/.test(this))? true:false;
}

/*
 * Add Array.indexOf(searchElement [, fromIndex]) functionality to 
 * IE8 - which does not support Array.indexOf() natively.
 * 
 * NOTE: pulled from Mozilla Developer Network (MDN)
 * https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/Array/indexOf
 */
if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
    "use strict";
    if (this == null) {
        throw new TypeError();
    }
    var t = Object(this);
    var len = t.length >>> 0;
    if (len === 0) {
        return -1;
    }
    var n = 0;
    if (arguments.length > 1) {
        n = Number(arguments[1]);
        if (n != n) { // shortcut for verifying if it's NaN
            n = 0;
        } else if (n != 0 && n != Infinity && n != -Infinity) {
            n = (n > 0 || -1) * Math.floor(Math.abs(n));
        }
    }
    if (n >= len) {
        return -1;
    }
    var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
    for (; k < len; k++) {
        if (k in t && t[k] === searchElement) {
            return k;
        }
    }
		return -1;
  }
}

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
 * Add String.trim() functionality to
 * IE8 - which does not support String.trim() natively.
 * 
 * NOTE: pulled from Mozilla Developer Network (MDN)
 * https://developer.mozilla.org/en-US/docs/JavaScript/Reference/Global_Objects/String/Trim
 */

if (!String.prototype.trim) {
	String.prototype.trim = function() {
  	return this.replace(/^\s+|\s+$/g,'');
 	}
}

/*
 * Add Array.remove([entry]) to Array object
 */

if (!Array.prototype.remove) {
	Array.prototype.remove = function(e) {
		var t, _ref;
	  if ((t = this.indexOf(e)) > -1) {return ([].splice.apply(this, [t, t - t + 1].concat(_ref = [])), _ref);}
	}
}

/**
 * @author Tim Steele
 * @description:
 * This object keeps track of what gizmos have been loaded 
 * into the page. 
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
		return (shcJSL.fn.createNewElement("a",null,{href:file})).pathname.split('/').pop();
	}
	
	function checkForScript(file, add) {
		if (shcJSL.bulletin[file] === undefined) {
			if (add !== false) shcJSL.bulletin[file] = true;
			return false;
		} else {
			return true;
		}
	}
	
	this.add = function(script) {
		if (shcJSL.fn.getObjectType(script) === "[object String]") {
			queue[queue.length] = function() {self.load(script);}
		} else if (shcJSL.fn.getObjectType(script) === "[object Function]") {
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
			document.getElementsByTagName("head").item(0).appendChild(shcJSL.fn.createNewElement('script',null,{'src':url, "type":"text/javascript"}));
		}
		
		if (notifier && callback) radar();
	}
	
	this.plug = function(socket) {
		if (shcJSL.sockets[socket]) {
			var path;	//	Path to the socket's URL
			var file;	//	File's name
			
			path = shcJSL.sockets[socket]; 
			file = getFileName(path);
			
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
	SHCJSL METHODS
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
	var type;				// (Function) Shorthand for calling shcJSL.fn.getObjectType
	
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

	type = shcJSL.fn.getObjectType;

	// Declare collection as an array
	collection = [];
			
	// Take the element(s) and turn it into a true array
	if (type(element) == "[object String]") {
		if (element[0] == "#") {	// Selector is an ID
			collection.push(getID(element.slice(1)));
		// Need to add in class selector at some point.
		} else {	// Selector is an element OR not a valid selector
			collection = shcJSL.fn.sequence(getTags(element));
		}
	} else if (type(element) == "[object Object]" || (type(element).toString()).indexOf("HTML") != -1) {
		// element is an HTMLObject
		collection.push(element); 
	} else if (type(element) == "[object Array]") {
		// element is already an array
	 	collection = shcJSL.fn.sequence(element);
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
	shcJSL Functions 
*/

shcJSL.fn = {
	
	createNewElement: function(e, c, a) {
		var newElement; // New element that will be created;
		
		newElement = document.createElement(e);
		if (c != null) newElement.className = c;
		if (typeof a != 'undefined' && typeof a == 'object') {
			for (var i in a) {
				newElement.setAttribute(i, a[i]);
			}
		}
		return newElement;
	},
	preloadImages: function(html) {
		if (html) {
			var image = new Image(); // Dummy new image object;
			
			jQuery.each($(html).find('img'), function() {
				image.src = this.src;
			})
			
			return html;
		} else return false;
	},
	renderHTML: function(parent, html) {
		if ((parent && html) && typeof html == 'string') {
			parent.innerHTML = html;
		}
		return parent;
	},	
	addChildren: function(p, c) {
		if (p && c) {
			for (var i=0; i < c.length; i++) {
				p.appendChild(c[i]);
			}
		}
		return p;
	},
	setStyles: function(e, s) {
		if (typeof s != undefined && typeof e != undefined) {
			for (var i in s) {
				e.style[i] = s[i];
			}
		}
		return e;
	},
	formDataToJSON: function(form) {
		var form = form,	// Set the form to equal the form;
			json = {};		// JSON object to be converted into a string;
		
		// Gather all the form elements and turn it into a true array then
		// then turn the array into a key/value pair object
		(shcJSL.sequence(form.elements)).map(set);
		
		// 'e' is form element;
		function set(e) {
			switch((e.nodeName == "INPUT")? e.type:(e.nodeName).toLowerCase()) {
				case "fieldset":
					(function(){
						// 'a' is an array, to hold the checked elements
						var a = [];
						
						// this is the fieldset element;
						($(this).find('[type="checkbox"]').length > 0)? a = $(this).find('[type="checkbox"]'):a = $(this).find('[type="radio"]');
						
						if (a.length > 0) {
							for (var i = 0; i < a.length; i++) {
								if (a[i].checked && a[i].getAttribute("name"))  {
									if (!json[a[i].getAttribute("name")]) json[a[i].getAttribute("name")] = [];
									json[a[i].getAttribute("name")].push(a[i].value);
								}
							}
						}
	
					}).call(e);
					break;
				case "hidden":
					(function(){
						if (this.getAttribute("name")) json[this.getAttribute("name")] = this.value;
					}).call(e);
					break;
				case "select":
					(function() {
						if (this.getAttribute("name")) {
							if (this.type != "select-multiple") json[this.getAttribute("name")] = this.options[this.selectedIndex].value
							else {
								for (var i = 0; i < this.options.length; i++) {
									if (this.options[i].selected) {
										if (!json[this.getAttribute("name")]) json[this.getAttribute("name")] = [];
										json[this.getAttribute("name")].push(this.options[i].value);
									}
								}
							}
						}
					}).call(e);
					break;
				case "text":
					(function(){
						if (this.getAttribute("name"))
							if (!(this.value).devoid()) json[this.getAttribute("name")] = this.value;
					}).call(e);
					break;
				case "textarea":
					(function(){
						if (this.getAttribute("name"))
							if (!(this.value).devoid())  json[this.getAttribute("name")] = this.value;
					}).call(e);
					break;
				default:
					break;
			}
		}
			
		return JSON.stringify(json);
	},
	sequence: function(array) {
		var sequence = [];	// New 'true' array
		try {sequence = sequence.concat([].slice.call(array));}
		catch(e) {
			for (var i=0; i < array.length; i++) {
				sequence.push(array[i]);
			}
		}
		return sequence;
	},
	getObjectType: function(object) {
		//return Object.prototype.toString.call(object);
		return Object.prototype.toString.call(object).replace(/^\[object (.*)\]$/, function(match, key, value, offset, string) {return key;})
		//return (Object.prototype.toString.call(object)).replace(/(object )/,'');
	},
	createCustomActionArray: function(array, methods) {
		for (var action in methods) (
			function(n,m) {
				array[n] = function(x) {
					return array.map(m,x);
				}
				
			}(action, methods[action])
		)
		return array;
	}
};

shcJSL.schematic = {}; shcJSL.apparatus = {}; shcJSL.gizmos = {};

/*
 * The governor
 */

shcJSL.governor = new function() {
	this.populace = {};	// (Object) contains list of elements keyed to their gizmo
	
	var self = this;
	
	this.architect = function(gizmo, element) {
		if (self.populace[gizmo] === undefined) self.populace[gizmo] = [];
		console.log(self.populace)
		self.populace[gizmo].push(element);
		console.log(self.populace)
	}
	
	this.activate = function() {
		for (var i in self.populace) {
			shcJSL.lazee.plug(i);
			$(window).one(i, {gizmo: i, elements:self.populace[i]}, function(event){
				var data = event.data;
				try {
					shcJSL.gizmos[event.data.gizmo](event.data.elements);
					delete shcJSL.governor.populace[event.data.gizmo];
					$(window)
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

var Merge = function(obj1, obj2) {
	for (var p in obj2) {
	    try {
	      // Property in destination object set; update its value.
	      if ( obj2[p].constructor==Object ) {
	        obj1[p] = MergeRecursive(obj1[p], obj2[p]);
	
	      } else {
	        obj1[p] = obj2[p];
	
	      }
	
	    } catch(e) {
	      // Property in destination object not set; create it and set its value.
	      obj1[p] = obj2[p];
	
	    }
	  }

  return obj1;
}
shcJSL.gizmos.calculate = function(event, parent) {
	var draft 		= "",												// Temporary object for storing option data
		Parent 		= parent || $('body').get(0),						// (HTMLObject) parent argument, or if null, the body element
		Selector 	= new Array("*[shc\\:gizmo]"),	// (Array) Array of selectors
		date 		= new Date(),
		uid 		= (new Array( date.getYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(),  date.getSeconds(), date.getMilliseconds() )).join("");
	
	Gizmo = Parent.querySelectorAll("*[shc\\:gizmo]");
	
	for (var i=0;i<Gizmo.length;i++) (function(){
		var gizmo 		= this.getAttribute("shc:gizmo"),			// shc:gizmo attribute || null
			options 	= this.getAttribute("shc:gizmo:options"),	// shc:gizmo:options attrubute || null
			stamp		=	uid++;
		
		// Assign unique ID
		this.setAttribute("shc:stamp",stamp);

		if (options) {
			if (draft.length > 0) draft += ",";
			draft += '"' + stamp.toString() + '":' + options;
		}
	}).call(Gizmo[i])
	
	// Convert all options into JSON Object
	draft = JSON.parse("{" + draft + "}");
	
	Merge(shcJSL.schematic, draft);
	
}
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
	var draft 		= "",																								// Temporary object for storing option data
			Parent 		= parent || $('body').get(0),												// (HTMLObject) parent argument, or if null, the body element
			Selector 	= new Array("*[shc\\:gizmo]", "*[shc\\:gadget]"),		// (Array) Array of selectors
			date 			= new Date(),
			uid 			= (new Array( date.getYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(),  date.getSeconds(), date.getMilliseconds() )).join("");
				
	$.each(
		$(Parent).find(Selector.join()),	// Array of elements matching selector inside parent
		function(index, value) {
			var gadget 		= this.getAttribute("shc:gadget"),			// shc:gadget attribute || null
				gizmo 		= this.getAttribute("shc:gizmo"),			// shc:gizmo attribute || null
				options 	= this.getAttribute("shc:gizmo:options"),	// shc:gizmo:options attrubute || null
				sprocket 	= this.getAttribute("shc:gadget:sprocket"),	// shc:gadget:sprocket attribute || null
				stamp		=	uid++;																				// Unique SHCJSL identifier
						
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
				
				
				if (shcJSL.fn.getObjectType(giz) === "[object String]") shcJSL.governor.architect(giz, this)
				else if (shcJSL.fn.getObjectType(giz) === "[object Array]") {
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
// 	
	// (function() {
		// for (var i in draft) {
			// shcJSL.schematic[i] = draft[i];
		// }
	// })();
	
	Merge(shcJSL.schematic, draft);
	
	shcJSL.governor.activate();
}

M = {};
M.hash = function() {
        var table = [],
                entry;
                
        entry = function(key, value) {
                return {'key': key, 'value': value};
        }
        
        this.get = function() {
                return (function(key) {
                	
                	var n = (table.length)? parseInt(table.length - 1):0;
                	if (table[n]) {
                		do {
	                		if (table[n]['key'] == key) return table[n]['value'];
	                	} while(n--);
                	}
                	return false;
                        // if (table.length > 0) {
                                // for (var i=0;i<table.length;i++) {
                                        // if (table[i]['key'] == key) return table[i]['value'];
                                // }
                                // return false;
                        // } else return false;
                }).apply(this, arguments);
        }
        
        this.put = function() {
                return (function(key, value) {
                        var e = this.get(key);
                        (!e)? table.push(entry(key, value)):Merge(e,value);
                        return this;
                }).apply(this, arguments);
        }
        
        this.spit = function() {
        	return table;
        }
        
        return this;
}

M.schema = new M.hash();

M.activate = function(event, parent) {
	var Gizmos,
		Parent = parent || document.body,												// (HTMLObject) parent argument, or if null, the body element

	Gizmos = Parent.querySelectorAll("*[shc\\:gizmo]");
	
	for (var i=0;i<Gizmos.length;i++) (function(){
		var gizmo 		= this.getAttribute("shc:gizmo"),
			options 	= this.getAttribute("shc:gizmo:options");
		
		if (options && gizmo) {
			M.schema.put(this, JSON.parse(options));
		}
	}).call(Gizmos[i])
}

jQuery(document).ready(function() {
	shcJSL.gizmos.calculate();
	M.activate();
	window.loaded = true;
});
