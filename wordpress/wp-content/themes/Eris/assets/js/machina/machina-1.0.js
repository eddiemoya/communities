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
		 * of activation can be restricted to the scope of activating new widgets only
		 * as child nodes to the parent element.
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

		/* DOES THIS NEED TO BE IN CORE? */
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
		 * to make sure that event handlers aren't causing memory leaks.
		 * @param  {Object} parent HTMLElement that will be scoped for deactivation
		 */
		Machina.deactivate = Machina.fn.deactivate = function(parent) {
			var Gizmos,	// List of all elements that are Gizmos
				i,
				Parent = parent || document.body; // If no parent is passed, Machina will deactivate all elements
				
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
             * @return {[type]}       New hash entry object
             */
	        entry = function(key, value) {
	                return {'key': key, 'value': value};
	        }
	        
	        this.get = function(key) {
            	var n = (table.length)? parseInt(table.length - 1):0;
            	if (table[n]) {
            		do {
                		if (table[n]['key'] === key) return table[n]['value'];
                	} while(n--);
            	}
            	return false;
	        }
	        
	        this.put = function(key, value) {
                var e = hash.get(key),
                	value = value || {};

                (!e)? table.push(entry(key, value)):Machina.fn.merge(e,value);
                return hash;
	        }

	        this.debug = function() {
	        	return table;
	        }

	        return this;
		};	// END Machina.hash

		Machina.jax = Machina.fn.jax = function(settings) {
			if (settings.url) {
				var m 		= Machina,
					method 	= settings.type,
					xhr;

				if (typeof XMLHttpRequest !== 'undefined') xhr = new XMLHttpRequest();
				else return false;
				
				function ready() {
					var m = Machina,
						x = xhr,
						s = settings
						u = settings.url;

					if (x.readyState > 3) {
						
						if (x.status !== 200) {
							// Error
							if (s.error && m.type(s.error) == "Function") s.error(x);
						} else if (x.readyState === 4) {
							// Success
							if (s.success && m.type(s.success) == "Function") s.success(x);
						}
					}
				}


				if (settings.data) {
					var data = settings.data;

					if (data && m.type(data) == "Object") {
						var string = "";
						for (var i in data) {
							if (string.length != 0) string += "&";
							string += encodeURIComponent(i) + "=" + encodeURIComponent(data[i]);
						}
						if (string.length > 0) data = string;
					}
				}

				if (method) {
					if (method == "GET" && data) (settings.url.indexOf("?") == -1)? settings.url += "?" + data:settings.url += "&" + data;
				} else method = "POST";

				xhr.onreadystatechange = ready;

				xhr.open(method,settings.url, true);
				xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
				if (data && method == "POST") {
					xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
					xhr.send(data)
				} else {
					xhr.send();
				}
			
			} else return false;	// No URL
			
		};

		/* DOES THIS NEED TO BE IN CORE? */
		Machina.merge = Machina.fn.merge = function() {
			var merge;

			/**
			 * @todo Remove the try..catch from these functions to mantain scope
			 */
			merge = function(master, slave) {
				for (var property in slave) {
					try {
						if (slave[property].constructor == Object) master[property] = merge(master[property], slave[property]);
						else master[property] = slave[property];
					} catch(e) {
						master[property] = slave[property];
					}
				}
				return master;
			}

			if (arguments.length > 1) {
				var master = 0, 
					slave  = 1;

				do {
					try {
						merge(arguments[master],arguments[slave]);
						slave++;
					} catch(e) {}
				} while(arguments[slave])	
				return arguments[0];

			} else return arguments[0] || false;
		};	// END Machina.merge

		Machina.require = Machina.fn.require = function() {
			// Set globals locally
			var doc		= document,
				m		= Machina,
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