/**
 * Moodle: Responsive Modal Window
 * @author Tim Steele
 * 
 * @package Machina
 *
 * @version 1.0 [2012-07-24]
 * @version 2.0 [2013-05-21]
 * * Modified to work with Machina 1.0
 */

 (function( Machina ) {

 	var Moodle = Machina.Gizmos.moodle = function(gizmos) {
 		return Moodle.fn.init(gizmos);
 	};

 	Moodle.fn = Moodle.prototype = {
 		init: function(gizmos) {
 			if (Machina.event) {
 				var l = gizmos.length,
	 				i = parseInt(l-1);
	 			do {
	 				Machina.event.on(gizmos[i], "click", function(event) {
	 					event.preventDefault();
	 					M(this).moodle();
	 				});
	 			} while(i--);
 			}

 		},
 		model: undefined,
 		view: function(element, options) {
 			// Load animation module;
 			// Machina.require("script/vendor/anim/anim.js");

 			var m			= Machina,
 				dom			= m.DOM,
 				make		= dom.create,
 				self		= this,
 				modal		= make('div','modal-window',{id:'moodle_window'}),
 				container	= make('section','modal-container',{id:'moodle_container'}),
 				overlay		= make('div','overlay',{id:'moodle_overlay'}),
 				// Default Modal properties;
 				defaults = {
 					clickOverlayToClose: false,
 					height	: "auto",
 					method	: "ajax",
 					width	: "value",  
 					//Wordpress/Community specific defaults;
 					type	: "POST",
 					target	: (typeof ajaxdata != undefined && typeof ajaxdata.ajaxurl != undefined)? ajaxdata.ajaxurl:false
 				},
 				// Private Methods
 				escapeModal = function(event) {
 					event.preventDefault();
 					if (event.keyCode == 27) self.destroy(event);
 				},
 				centerModal = function() {
 					var moodle 	= modal,
 						left	= ((window.innerWidth || document.documentElement.clientWidth) - moodle.offsetWidth)/2,
						top		= ((window.innerHeight || document.documentElement.clientHeight) - moodle.offsetHeight)/2,
						styles	= "";

 					(left < 0)? styles = styles + "left:" + 0 + "px;":styles = styles + "left:" + left + "px;";
 					(top < 0)? styles = styles + "top:" + 0 + "px;":styles = styles + "top:" + top + "px;";
						
 					moodle.style.cssText += styles;
 					return moodle;
 				},
 				scrubModal = function() {
 					Machina.deactivate(modal);
					if (container.firstElementChild) {
						var a = container.children,
							i = a.length;

						while (i--) {
							container.removeChild(a[i]);
						}
					}
 				};

			modal.appendChild(container);

			// Public Methods;
			this.create = function(element, options) {
				var doc = document,
					f	= doc.createDocumentFragment(), // New Frag to hold code
					m	= Machina,	// Bring Machina in scope
					e	= m.event;	// Reference Machina events

				if (modal.parentNode == doc.body) {
					// Modal already active;
					doc.body.removeChild(modal);
					e.off(modal);
					scrubModal()

				} else {
					// Modal isn't active;
					modal.style.cssText += "display:none;"
					
					m.DOM.append(f,[
						overlay,
						modal
					]);
					// Set events
					e.fire(Moodle, "moodle.init");
					e.on(window, "resize", centerModal);
					e.on(Moodle, "moodle.update", m.activate, modal)
					doc.body.appendChild(f);
				}

				self.update(element, options);
			};
			this.update = function(element, options) {
				var m = Machina,
					method,
					parameters	= (m.type(options) == "Object")? options:{}, 
					properties	= m.get(element),
					settings,
					error = function() {
						var m = Machina,
							dom = m.DOM,
							content = dom.create("article","span12 content-container",{"id":"moodle_error"}),
							section	= dom.append(
								// parent;
								dom.create("section","content-body clearfix"),
								[
									(dom.create("h6","content-headline")).appendChild(document.createTextNode("Oops!")),
									(dom.create("p")).appendChild(document.createTextNode("The modal window has encountered a problem."))
								]
							);
						content.appendChild(section);
						compose(content);
					};

				(properties)? (properties.moodle)? (properties = properties.moodle):{}:{};
				settings = m.merge({},defaults, properties, parameters);

				method = String(settings.method).toLowerCase(); 

				switch(method) {
					case "ajax":
						// SET URL
						var target	= settings.target;
						if (!target) target = element.href || false;

						// Make sure we have a place to get content from;
						if (target) {
							// Request Settings;
							var request = {
									url: target,
									type: settings.type,
									success: function(xhr) {
										var content = Machina.DOM.create("div");

										content.innerHTML = xhr.responseText;
										compose(content, xhr);
									},
									error: function(xhr) {
										error();
									}
								};
							if (settings.data) request.data = settings.data;
							Machina.jax(request);
						};
						break;
					case "local":
						var target = document.getElementById(target);
						target? compose(target):error();
						break;
					case "iframe":
						var target 	= settings.target,
							height 	= settings.height,
							width	= settings.width;
						if (!target) target = element.href || false;

						if (height != "auto") height = parseInt(height) - 12;
						if (width != "auto") width = parseInt(width) - 12;

						if (target) {
							/**
							 * @todo create a utility that turns json data into query string
							 */
							var data 	= settings.data;
							if (data) {
								var string = "";
								for (var i in data) {
									if (string.length != 0) string += "&";
									string += encodeURIComponent(i) + "=" + encodeURIComponent(data[i]);
								};
								if (string.length > 0) (target.indexOf("?") == -1)? target += "?" + string:target += "&" + string;
								compose(m.DOM.create("iframe",null,{"id":"easyXDM_default1953_provider", "frameborder":"0","name":"easyXDM_default1953_provider","src":target,"height":height,"width":width}));
							}
						};
						break;
					default:
						return false;
						break;
				}

				function compose(content, data) {
					var m		= Machina,
						e		= m.event,
						dom		= m.DOM,
						height	= settings.height,
						width	= settings.width;

					container.appendChild(content);

					if (width || height) {
						var css = "";
						if (width != "auto" && !(isNaN(width))) css += "width:"+width+"px;";
						if (height != "auto" && !(isNaN(height))) css += "height:"+height+"px;";
					}

					content.style.cssText = css;
					container.appendChild(
						dom.create("a","close-button",{"href":"#", "m:moodle":"close"})
					);

					console.log(settings);

					m.event.on(modal,"click","[m\\:moodle='close']",self.destroy, {data:settings});

					(settings.clickOverlayToClose)?
						e.on(overlay,'click',self.destroy,{data:settings}):
						e.off(overlay,'click');

					e.on(document,'keyup',escapeModal,{data:settings});

					modal.style.cssText = modal.style.cssText.replace(/display: ?none;/,"visibility:hidden;");
					centerModal();
					modal.style.visibility = "visible";
					e.fire(Moodle, "moodle.update");
				}
			};
			this.destroy = function(event) {
				var doc			= document,
					m			= Machina,
					e			= m.event,
					settings;
				if (event.data) settings = event.data[0].data;

				Machina.event.fire(Moodle, "moodle.close");

				modal.style.cssText == "display:none;";
				
				console.log(settings);

				if (settings) {
					if (String(settings.method).toLowerCase() == "local") {
						var target;
						if (settings.target) target = doc.getElementById(settings.target);
						if (target) {
							m.deactivate(modal);
							doc.body.appendChild(target);
						}
					} else {
						scrubModal();
					}
				}

				e.off(window, "resize", centerModal);
				e.off(Moodle, "moodle.update", m.activate)
				e.off(modal);
				e.off(overlay);
				e.off(document,'keyup',escapeModal);

				doc.body.removeChild(modal);
				doc.body.removeChild(overlay);

				e.fire(Moodle, "moodle.die");
			}
 		}
 	};

 	Moodle.fn.controller = Machina.methods.moodle = function(target) {
 		var method,	// (String) Method for the modal window
 			args	= this[0];

 		if (args == undefined) {
 			method = new String("create");
 		} else if (args.constructor == String) {
 			method = args.toString();
 		} else if (args.constructor == Object) {
 			method = new String("create");
 		} else {
 			console.log("Moodle Failed");
 			// Something broke;
 			return false;
 		}

 		(!Moodle.fn.model)? (Moodle.fn.model = new Moodle.fn.view())[method](target,args):Moodle.fn.model[method](target, args);
 	};

 })(Machina);