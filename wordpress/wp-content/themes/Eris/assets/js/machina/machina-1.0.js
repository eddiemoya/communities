/**
 * @title Machina
 * @author Tim Steele
 * @created 2013-04-29
 * @description Machina is a JavaScript widget and plugin library
 *
 * @version 1.0
 *
 * 1.0 [2013-04-29]
 * * Adapted from shcJSL library 2.0 (unreleased) and jQuery structure
 */

/**
 * @param {Object} [conf] Configuration options that are passed into Machina on initialization
 */
(function(conf) {
	
	/**
	 * Machina object
	 * @return {Object} Returns the Machina object after initializing
	 */
	var Machina = (function() {
		// Define a local copy of Machina
		var Machina = function(selector) {
				// The Machina object is actually just the init constructor 'enhanced'
				return new Machina.fn.init(selector);
		};

		/**
		 * If configurations were passed in the conf argument, store those 
		 * properties in the Machina.conf property
		 * @type {Object}
		 */
		if (conf) Machina.conf = conf;

		/**
		 * Core Machina functions. All these functions are called using M.[method]
		 * @type {Object}
		 */
		Machina.fn = Machina.prototype = {
			/**
			 * This is the main Machina selector. When you pass an element or array 
			 * through Machina(element) or M(element) this is what is being called.
			 *
			 * Machina then takes the element and puts it in to an array, with the
			 * Gizmos init methods from Machina.methods as methods that you can call 
			 * on the element(s).
			 *
			 * @todo allow Machina to take an array.
			 */
			init: function( element ) {
				/**
				 * Check to make sure what is being passed is not a string.
				 */
				if (typeof element == "object") {
					var collection = [],
						type;

					// What type of element was passed?
					type = Machina.fn.type(element);

					// Is it a single HTML element?
					if (type.indexOf("HTML") != -1 || element.nodeName) {
						// If it is a single HTML element than put it into a new array;
						collection.push(element);
					}
					else if (type == "Array") {
						/**
						 * @todo Set up passing of Arrays to Machina
						 */
					} else return undefined;
					/**
					 * Go through the methods listed in Machina.methods and attach each
					 * as a method to the new array.
					 * @param  {String} 	n method name
					 * @param  {Function} 	m method function
					 * @return {Array}   	New array with methods attached
					 */
					for (var method in Machina.methods)(function(n,m) {
						// Create a new scope
						// n/m are key/value of the method object (n = method name, m = method)
						collection[n] = function(x) {
							collection.map(m,arguments);
							return collection;
						}
					}(method, Machina.methods[method]))
					// Return the new array that has the Machina.methods	
					return collection;

				} else return undefined;
				
			}
		};

		// Give the init function the Machina prototype for later instantiation
		Machina.fn.init.prototype = Machina.fn;
		
		/*
			This is the Machina activate function. This is the function that is called
			automatically on page load. It can also be called after page load with the 
			optional parameter of a parent element - this is for instantiating Machina
			widgets after page load, i.e. on content loaded from an AJAX call.
		 */
		/**
		 * This is the function that initiates Machina. It is called automatically on
		 * documentReady or page load. It can be called after page load and the scope
		 * of activation can be restricted to an element and it's child nodes by passing
		 * an element as the parent argument.
		 * @param  {Object} event  Event object is activated as an event
		 * @param  {Object} parent HTMLElement to restrict scope of activation
		 */
		Machina.activate = Machina.fn.activate = function(event, parent) {
			/**
			 * If Machina is activated through window load, remove the window load event.
			 */
			if (window.removeEventListener) window.removeEventListener("load",arguments.callee, false);
			var Gizmos,
				Dependencies 	= [],
				Parent 			= parent || document.body, 	// (HTMLObject) parent argument, or if null, the body element
				Reqs 			= {},				 		// New hash table to hold all the HTMLGizmos
				Schema 			= Machina.schema, 			// Reference to the master hash table of gizmo options

			// Get all the gizmos and create a native array;
			Gizmos = Machina.arrange(Parent.querySelectorAll("*[m\\:gizmo]"));
			
			// Allows you to add/retrieve from master Schema using M.get()/M.put();			
			Machina.get = Schema.get,
			Machina.put = Schema.put;

			// Variables for handling Gizmo looping;
			var l 		= Gizmos.length,		// Hard length of the array;
				i 		= parseInt(l - 1);		// Setting the value of i (index);

			if (l > 0) {
				do {
					var element = Gizmos[i],								// Gizmo element
						gizmo 	= element.getAttribute("m:gizmo"), 			// Get the name of the Gizmo || undefined;
						options	= element.getAttribute("m:gizmo:options"), 	// Get the Gizmo options || undefined
						plugin; 											// Arry it all Gizmo's for this element

					// Store the element in the master Gizmo schema with element's options || {};
					Schema.put(element, (options)? JSON.parse("{" + options + "}"):{});

					// Are there multiple Gizmos on this element?
					// Ex. m:gizmo="moodle, openID"
					(gizmo.indexOf(",") == -1)? (plugin = []).push(gizmo):plugin = gizmo.split(/, ?/g);
					// Set up loop for all plugins on this element;
					var y = plugin.length, z = parseInt(y - 1);

					do {
						// Add plug in to a list of required Machina plugins to load
						var g = plugin[z];
						(Reqs[g])? Reqs[g].push(element):(Reqs[g] = []).push(element);
					} while(z--);
				} while(i--);
			};

			// To support IE8, can't use Object.keys for loop;
			// Use a for..loop function to create scope - otherwise the last
			// plugin is the one that will always get loaded;
			for (var g in Reqs) (function(gizmo, array) {
				// Add scripts to list of Dependencies for Machina
				Dependencies.push({script:g, callback: function() {
					// Use a try loop so one bad script doesn't fail to load the rest of
					// Machina's scripts;
					try {
						Machina.Gizmos[gizmo](array);
					} catch(e) {
						console.log("ERROR");
						console.log(e);
					}
				}})
			})(g, Reqs[g])
			// Send the required plugins to be loaded;
			Machina.require(Dependencies);
		};

		/**
		 * This takes a list of elements, whether it is a nodeList
		 * or something type of non-true array, and turns it into
		 * a true array.
		 * @param  {Array} array Non-true array
		 * @return {Array}       The true array
		 */
		Machina.arrange = Machina.fn.arrange = function(array) {
			var collection = [];	// To hold the new true array
			for (var i=0, l=collection.length=array.length; i<l; i++) {
				collection[i] = array[i];
			}
			return collection;
		};	// END Machina.arrange

		/**
		 * This will deactivate Gizmos, removing all event handlers.
		 * Whenever code is removed from the DOM, you should run a deactivate
		 * to make sure that event handlers aren't causing memory leaks. If no
		 * parent argument is passed, then all Gizmos on the page are
		 * deactivated.
		 * @param  {Object} parent HTMLElement that will be scoped for deactivation
		 */
		Machina.deactivate = Machina.fn.deactivate = function(parent) {
			var Gizmos,	// List of all elements that are Gizmos
				i,
				Parent = parent || document.body; // If no parent is passed, Machina will deactivate all elements
			// Get all Gizmos of the parent element;	
			Gizmos = Machina.arrange(Parent.querySelectorAll("*[m\\:gizmo]"));

			i = Gizmos.length;
			while (i--) {
				Machina.event.off(Gizmos[i]);	// Remove event handlers
			}
		}

		/**
		 * Machina hash tables allow you to store HTMLElement as the keys in 
		 * a JavaScript Object. 
		 * @return {Object} New Machina hash object
		 */
		Machina.hash = Machina.fn.hash = function() {
			var entry,		// Creates a new entry 
				hash = this,// reference to the current 'this' 
				table = [];	// hash table array (hash table is actually an array)
            
            /**
             * Creates a new hash table entry.
             * @param  {Object} key   HTMLElement to serve as hash key
             * @param  {Any} value 	  Any value
             * @return {Object}       New hash entry object
             */
	        entry = function(key, value) {
	                return {'key': key, 'value': value};
	        }
	        
	        /**
	         * Use this to get value from the hash table by passing in
	         * the element key.
	         * @param  {Object} key Element key
	         * @return {any}     	Corresponding value
	         */
	        this.get = function(key) {
	        	// Get the hash table length;
            	var n = (table.length)? parseInt(table.length - 1):0;
            	if (table[n]) {
            		// If hash table exists loop through it and find the key and
            		// return the value;
            		do {
                		if (table[n]['key'] === key) return table[n]['value'];
                	} while(n--);
            	}
            	// Table doesn't have any values or key not found;
            	return false;
	        }
	        
	        /**
	         * This puts values into the hash table. If the key already exists
	         * it updates the value to the new value. If the key doesn't exist
	         * it creates a new entry.
	         * @param  {Object} key   Element to serve as the key
	         * @param  {any} 	value The value
	         * @return {Object}       Returns the hash table
	         */
	        this.put = function(key, value) {
                var e = hash.get(key),		// Existing entry or false;
                	value = value || {};	// If no value passed, create empty value

                // If entry doesn't exist, create new entry otherwise update
                // current entry in the hash table;
                (!e)? table.push(entry(key, value)):Machina.fn.merge(e,value);
                return hash;
	        }

	        // This is to dump the values of the hash table
	        this.debug = function() {
	        	return table;
	        }

	        return this;
		};	// END Machina.hash

		/**
		 * This is the Machina ajax function.
		 * @param  {[Object} settings Object with the AJAX call settings as the properties
		 * @return {XHRResponse}      If successful AJAX call, XHRResponse
		 */
		Machina.jax = Machina.fn.jax = function(settings) {
			// Make sure the user provided us with a URL to make our AJAX call;
			if (settings.url) {
				var m 		= Machina,			// Scope Machina
					method 	= settings.type,	// Get the method
					xhr;						// Set the XHR variable

				// We aren't supporting lessor versions of IE8 so we can just create
				// an XMLHttpRequest, otherwise the function quits.
				if (typeof XMLHttpRequest !== 'undefined') xhr = new XMLHttpRequest();
				else return false;
				
				/**
				 * Create the function to execute on the response of the AJAX Request
				 */
				function ready() {
					var m = Machina,		// Scope Machina
						x = xhr,			// Response
						s = settings		// XMLHttpRequest settings
						u = s.url;	// Store URL from settings

					// If the response is ready
					if (x.readyState > 3) {
						
						// Something happened and we did not get a successful response
						if (x.status !== 200) {
							// Error
							// If the user defined an error function, execute it;
							if (s.error && m.type(s.error) == "Function") s.error(x);
						} else if (x.readyState === 4) {
							// Success
							// This response is successful, if user defined a success
							// function, execute it;
							if (s.success && m.type(s.success) == "Function") s.success(x);
						}
					}
				}

				// Check to see if the user wants to send data along with the request;
				if (settings.data) {
					var data = settings.data; // Localize data;

					if (data && m.type(data) == "Object") {	// Make sure we are dealing with an object;
						var string = "";
						// Loop through data and build out the query string;
						for (var i in data) {
							if (string.length != 0) string += "&";
							string += encodeURIComponent(i) + "=" + encodeURIComponent(data[i]);
						}
						// If there is a query string, replace data with the string;
						if (string.length > 0) data = string;
					}
				}

				// How are we going to post this data?;
				if (method) {
					// If the method is GET and we have data, append it to the end of the URL
					// for the request;
					if (method == "GET" && data) (settings.url.indexOf("?") == -1)? settings.url += "?" + data:settings.url += "&" + data;
				} else method = "POST";

				// Set the ready function we prepped earlier;
				xhr.onreadystatechange = ready;

				// Open up the request;
				xhr.open(method,settings.url, true);
				// Let it be known we are making an AJAX request;
				xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
				// If we are making a POST with data...;
				if (data && method == "POST") {
					// Set the data/POST as form;
					xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					xhr.send(data)
				} else {
					xhr.send();
				}
			
			} else return false;	// No URL
			
		};

		/**
		 * This allows you to merge multiple objects together into one
		 * object. You can pass as many objects as you want. The objects
		 * will always be merged into the first object. If you want to create
		 * a new object containing the properties of all the objects, pass {}
		 * as the first parameter.
		 * @return {Object} Merged object
		 */
		Machina.merge = Machina.fn.merge = function() {
			var merge;

			/**
			 * The recursive merge function.
			 * @param  {Object} master Object to be merged into
			 * @param  {Object} slave  Object to merge into the master object
			 * @return {Object}        Newly merged master object
			 */
			merge = function(master, slave) {
				/**
				 * @todo Remove the try..catch from these functions to avoid creating
				 * a new scope.
				 */
				for (var property in slave) {
					try {
						// If this is property is an object, recurse merge;
						if (slave[property].constructor == Object) master[property] = merge(master[property], slave[property]);
						// otherwise set the property in master;
						else master[property] = slave[property];
					} catch(e) {
						master[property] = slave[property];
					}
				}
				return master;
			}

			// More than two objects were passed...;
			if (arguments.length > 1) {
				var master = 0, // Master Index;
					slave  = 1; // Start of slave index;

				do {
					try {
						// Try merging current master/slave;
						merge(arguments[master],arguments[slave]);
						// Move on to next argument;
						slave++;
					} catch(e) {}
				} while(arguments[slave]) // As long as there is another argument;	
				return arguments[0];

			} else return arguments[0] || false;
		};	// END Machina.merge

		/**
		 * The require function allows you pass required JavaScript files to Machina in
		 * three different ways:
		 * 1. A single script with/without an optional callback:
		 * ex. require('script/javascript.js', callback);
		 *
		 * 2. Load multiple scripts with one callback when done:
		 * ex. require(['script/script1.js','script/script2.js'],callback);
		 *
		 * 3. Load multiple scripts each with a callback:
		 * ex. require([{script:'script/script1.js',callback:function},{script:'script/script2.js',callback:function}]);
		 */
		Machina.require = Machina.fn.require = function() {
			// Set globals locally
			var doc		= document,											// Localize document
				m		= Machina,											// Localize Machina
				defer	= [],												// Place deferred scripts though
				frag	= doc.createDocumentFragment(), 					// Place the scripts at once with a docFragment
				head	= doc.getElementsByTagName("head")[0], 				// Get the page head
				primed	= (!m.primed)? m.primed = new m.hash():m.primed,	// Create hash table of loaded scripts
				type	= m.type(arguments[0]),								// Type of first argument passed
				name	= function(name) {return (name.indexOf('.js') == -1)? name + ".js":(m.DOM.create("a",null,{href:name})).pathname.split('/').pop();},
				queue	= function(file, callback) {
					file.onload = file.onreadystatechange = function() {
						if (!file.readyState || file.readyState == 'loaded' || file.readyState == 'complete') {
							file.onload = file.onreadystatechange = null;
							callback();
							
						}
					}
				};				

				// Figure out what we are doing here;
				if (type == "String") {
					// Loading single script;
					var s = arguments[0],				// Script name;
						c = arguments[1] || undefined,	// Callback if there is one;
						n = name(s),					// Actual name of script;
						e = primed.get(n);				// Already exists?

					if (!e) {
						primed.put(n);

						var f = m.DOM.create("script",null,{"src":(s.indexOf(".js") == -1)? Machina.conf.path + n:s, "type":"text/javascript"});
						if (c) queue(f, c);

						frag.appendChild(f);
					}	else if(c) c();
				} else if (type == "Array") {
					// Loading multiple scripts;
					var callback,
						scripts = arguments[0],
						type = m.type(scripts[0]),
						count = scripts.length,
						reserve = function(n, f) {
							var queued = n,
								complete = f;
							this.done = function() {
								queued--;
								if (queued == 0) complete();
							}
						}

					// Multiple scripts, what we doing here?	
					if (type == "String") {
						// Multiple scripts, one callback

						if (arguments[1]) {
							callback = new reserve(count, arguments[1])
						}

						while (count--) {
							var s = scripts[count],
								n = name(s),
								e = primed.get(n);

							if (!e) {
								primed.put(n);
								var f = m.DOM.create("script",null,{"src":(s.indexOf(".js") == -1)? Machina.conf.path + n:s, "type":"text/javascript"});

								if (callback) queue(f, callback.done);

								frag.appendChild(f);
							} else if (callback) callback.done();
						}

						

					} else if (type == "Object") {
						// Multiple scripts with individual callbacks
						while (count--) {
							var current	= scripts[count],
								s 		= current.script,
								c 		= current.callback,
								n		= name(s),
								e		= primed.get(n);

							if (!e) {
								var f = m.DOM.create("script",null,{"src":(s.indexOf(".js") == -1)? Machina.conf.path + n:s, "type":"text/javascript"});

								primed.put(n);
								if (c) queue(f, c);

								frag.appendChild(f);
							} else defer.push(c);
						}
					}
				} else return;

				head.appendChild(frag);
				var d = defer.length; while (d--) {defer[d]();}
		};

		Machina.type = Machina.fn.type = function(object) {
			return Object.prototype.toString.call(object).replace(/^\[object (.*)\]$/, function(match, key, value, offset, string) {return key;})
		};	// END Machina.type

		return Machina;

	})();

	Machina.DOM = {
		fn : {
			ready: function(fn) {
				if (document.readyState == "complete") fn();
				else {
					(document.addEventListener)? document.addEventListener("DOMContentLoaded", fn, false):window.attachEvent("onload", fn);
					if (document.addEventListener) window.addEventListener("load",fn,false);
				}
			},
			create: function(e, c, a) {
				var element = document.createElement(e);

				if (c) element.className = c;
				if (a && Machina.type(a) == "Object") {
					for (var i in a) {
						element.setAttribute(i, a[i]);
					}
				}
				return element;
			},
			append: function(p, c) {
				if (p && c) {
					for (var i=0,l=c.length;i<l;i++) {
						/**
						 * @todo find a way to make validated code that will work in IE
						 * var child = c[i];
						 * if (Machina.type(child).indexOf("HTML") != -1) p.appendChild(child);
						 */
						p.appendChild(c[i]);
					}
				}
				return p;
			}
		}
	}; Machina.DOM = Machina.DOM.fn;

	Machina.Gizmos	= {};

	Machina.schema = new Machina.hash();
	
	Machina.methods = {};

	if (conf) {
		if (conf.require && conf.require.length > 0) {
			Machina.require(conf.require, function(){
				if (bean) Machina.event = bean;
				Machina.DOM.ready(Machina.activate);
			});
		} else Machina.DOM.ready(Machina.activate);
	}

	// Expose Machina to the global object
	window.Machina = M = Machina;

})(machina? machina:{});