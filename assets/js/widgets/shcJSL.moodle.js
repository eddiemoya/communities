/**
* Moodle: Responsive Modal Window
* @author Tim Steele
*	
* @package shcJSL
* @require jQuery 1.7.2
* 
* TABLE OF CONTENTS
* 
* @version [1.0: 2012-07-24]
* - Initial script release
*/

/**
 * MOODLE, MOODLE.moodle
 * 
 * The Moodle object and modal window object
 * 
 * @var MOODLE (Object) Moodle object for Moodle specific methods, properties
 * @var MOODLE.modal (Object) The modal window object
 * @var $Moodle (Object) a variation on the Moodle modal window object. 
 */
MOODLE = {}
MOODLE.modal = $Moodle = function(element, options) {
	/**
	 * PRIVATE VARIABLES
	 *
	 * @var getPosition (Function) top and left position of an element
	 * @var centerModal (Function) Centers the modal window 
	 * @var gears (object) The parts that make up the modal window
	 * @var self (Object) The main modal window object
	 * @var toggleLoading (Function) Toggles the modal loading screen
	 * @var toggleOverlay (Function) Toggles overlay on or off
	 */
	var centerModal;
	var getPosition;
	var gears = {};
	var self 	= this;
	var toggleLoading;
	var toggleOverlay;
	
	/**
	 * gears
	 * 
	 * @var overlay (HTMLObject) The overlay element
	 * @var modal (HTMLObject) The modal window element
	 * @var container (HTMLObject) The modal window's container element
	 */
	gears = {
		overlay: shcJSL.createNewElement('div','overlay', {id:'moodle_overlay'}),
	  modal: shcJSL.createNewElement('section','modal-window', {id:'moodle_window'}),
	  container: shcJSL.createNewElement('div','modal-container',{id:'moodle_container'})
	}
	
	/**
	 * defaults
	 * 
	 * List of settings for the moodle object.
	 * 
	 * @access Private
	 * @author Tim Steele
	 * @since 1.0
	 * 
	 * @param clickOverlayToClose (Boolean) whether the user can click on the overlay to close the modal
	 * @param height (String/Integer) The height of modal window, or auto
	 * @param method (String) The method to retrieve the content to put in the modal
	 * @param width (String/Integer) The width of the modal window, or auto
	 */
	defaults = {
		clickOverlayToClose: true,
		height: 'auto',
		method: 'ajax',
		width: 'auto'
	}
	
	/**
	 * PRIVATE METHODS
	 */
	
	/**
	 * getPosition
	 * 
	 * Calculates the distance from the left and top of the element
	 * to the browser window that will center the element.
	 * 
	 * @access Private
	 * @author Tim Steele
	 * @since 1.0
	 * 
	 * @param element (HTMLObject) Element to run calculation on
	 * 
	 * @return (Object) left [int], top [int]
	 */
	getPosition = function(element) {
		if (!element) element = gears.modal;
		return {
			left: ($(window).width() - $(element).outerWidth())/2,
			top:	($(window).height() - $(element).outerHeight())/2
		};
	}
	
	/**
	 * centerModal
	 * 
	 * Centers the modal window to the browser window.
	 * 
	 * @access Private
	 * @author Tim Steele
	 * @since 1.0
	 * 
	 * @return (HTMLObject) modal element
	 * @var styles (Object) properties left, top for CSS values
	 * 
	 * @todo Add ability to adjust to mobile/smaller view ports
	 */
	
	centerModal = function() {
		var styles; // Object for CSS key value pairs
		
		styles = {};
		/*
		 * Will the modal window be bigger than the available view port?
		 * If yes, abut the modal to the left/top browser window edge.
		 * If no, get the centering value and apply it.
		 */
		(getPosition().left < 0)? styles.left = 0:styles.left = getPosition().left;
		
		(getPosition().top < 0)? styles.top = 0:styles.top = getPosition().top;
		// Apply styles to center the modal
		$(gears.modal).css(styles);
		// Return modal element
		return gears.modal;
	}
	
	/**
	 * toggleOverlay
	 * 
	 * Toggles the overlay element on or off.
	 * 
	 * @access Private
	 * @author Tim Steele
	 * @since 1.0
	 */
	
	toggleOverlay = function() {
		(jQuery.contains(document.documentElement, gears.overlay))? jQuery(gears.overlay).remove():jQuery('body').append(gears.overlay);
		return self;
	}
	
	/**
	 * toggleLoading
	 * 
	 * Toggles the modal window loading screen
	 * 
	 * @access Private
	 * @author Tim Steele
	 * @since 1.0
	 */
	toggleLoading = function() {
		if (($(gears.container).children()).length != 0) $(gears.container).children().detach()
		$(gears.container).append("<section class='loading'></section>");
		return self;
	}
	
	error = function(xhr, status, message) {
		$(gears.container).html("<h1>Sh!t</h1><p>Cats broke the interwebz</p>");
	}
	
	/*
	 * PUBLIC METHODS
	 */
	
	/**
	 * create
	 * 
	 * Opens up Moodle (modal window).
	 * 
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0
	 */
	this.create = function(element, options) {
		//console.log("ELEMENT: "); console.log(element);
		//console.log("OPTIONS: "); console.log(options);
		if (jQuery.contains(document.body, gears.modal)) {
			/*
			 * Modal window is already opened.
			 * Activate the loading state - send new element/options to update
			 * the modal.
			 */
			toggleLoading().update(element, options);
		} else {
			/*
			 * Modal window is not already opened.
			 * Activate the overlay, activate loading, place modal
			 * Set the the window event for resizing the modal if the
			 * screen resized. Also bind widget activation to Moodle updates.
			 */
			toggleOverlay(); toggleLoading();
			// Append modal window to body, hide it.
			$(document.body).append($(gears.modal).hide());
			// center modal window, show it.
			$(centerModal()).show();
			// Bind the resize event to re-center the modal window
			$(window).bind("resize", centerModal);
			// Event for triggering Gizmos on new content.
			$(gears.modal).bind('moodle-update', shcJSL.gizmos.activate);
			// Trigger the moodle create event
			$(gears.modal).trigger('moodle-create');
			// Send to update to build out modal
			self.update(element, options);
		}
	}
	
	/**
	 * update
	 * 
	 * Updates Moodle (modal window) with new content
	 * 
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0
	 * 
	 * @param element (HTMLObject) element that triggered the moodle()
	 * @param options (Object) any additional parameters to be added
	 * 
	 * @todo Add support for methods: variable, on-page element
	 * @todo Add ability to close modal by clicking on overlay
	 */
	this.update = function(element, options) {
		var compose; // Function to draw out the modal window
		/*
		 * argumentOptions, elementOptions and settings are the three ways which
		 * define the final settings for the modal window. 'settings' is a clone of the default
		 * settings for Moodle, defined as defaults in the object. 'elementOptions'
		 * is the settings for the element from the shc:gizmo:options attribute. 
		 * 'argumentOptions' is the settings that may or may not be passed on instantiation.
		 *	argumentOptions will overwrite elementOptions who overwrite settings.
		 * argumentOptions > elementOptions < settings.
		 */
		var argumentOptions; // Options if they were passed with moodle() call
		var elementOptions;	// Options that may exist in shc:gizmo:options attribute 
		var settings;	// Default settings
		
		/*
		 * If the options argument exists, first check if the options is the
		 * window object -- this can happen if no argument is set, and it sets
		 * off all sorts of security alarms and browsers stop executing the script.
		 * If options exist and is not the window object, then set argumentOptions to
		 * equal options, otherwise set argumentOptions to an empty object.
		 */
		(options)? (options.constructor == Window)? argumentOptions = {}: argumentOptions = options:argumentOptions = {}; 
		/*
		 * This is a bit confusing looking, but simple:
		 * If the element contains the attribute 'shc:gizmo:option' attribute
		 * then turn the shc:gizmo:option attribute into a JSON object and see
		 * if it has a 'moodle' object/property. If neither the 'shc:gizmo:options'
		 * attribute is found or the moodle property, then 'elementOptions' is an
		 * empty object ({}), otherwise set elementOptions to be moodle property of
		 * the shc:gizmo:options attribute/JSON object.
		 */ 
		elementOptions = ($(element).attr("shc:gizmo:options") != 'undefined')? (((eval('(' + $(element).attr("shc:gizmo:options") + ')')).moodle)?(eval('(' + $(element).attr("shc:gizmo:options") + ')')).moodle:{}):{};
		// Make a clone of the default settings, as we don't actually want to change the defaults
		settings = jQuery.extend(true, {}, defaults);
 		
 		// By the power of JavaScript combine all three objects to form 'settings'
 		jQuery.extend(true, settings, elementOptions, argumentOptions)
		
		// If the method is AJAX
		if (String(settings.method).toLowerCase() === 'ajax') {
			// If a URL has not been included in the element options or argument options,
			// check the actual element for a URL
			if (!settings.target) settings.target = $(element).attr("href");
			// Make sure we have a URL to send the AJAX request
			if (settings.target && (settings.target != 'undefined' || settings.target != '')) {
				jQuery.ajax({
					dataType: 'html',
					url: settings.target
				}).success(function(data, status, xhr) {
					var htmlObject;	// New HTMLObject created the the AJAX response string
					
					// console.log("SUCCESS");
					// console.log("DATA: "); console.log(data);
					// console.log("TEXT: "); console.log(text);
					// console.log("XHR: "); console.log(xhr);
					
					htmlObject = shcJSL.preloadImages(shcJSL.renderHTML(shcJSL.createNewElement("div"), data)).firstChild;
					compose(htmlObject, [status, xhr])
				}).error(function(xhr, status, message) {
					error(xhr, status, message)
					// console.log("ERROR");
					// console.log("DATA: "); console.log(data);
					// console.log("TEXT: "); console.log(text);
					// console.log("XHR: "); console.log(xhr);
				})
			} // END if settings.target
		}
		
		if (String(settings.method).toLowerCase() === 'local') {
			if (document.getElementById(settings.target)) {
				compose(document.getElementById(settings.target));
			}
			else {
				console.log("fail");
			}
		}
		
		function compose(content, data) {
			console.log(settings);
			if (settings.width && settings.width != 'auto') if (!(isNaN(settings.width))) $(content).css('width', settings.width);
			if (settings.height && settings.height != 'auto') if (!(isNaN(settings.height))) $(content).css('height', settings.width);
			
			$(document.body).append($(content).toggleClass('moodle_transit'));
			window.scrollTo(0,0);
			
			
		}
		
		return;
		
						
		$($('body').get(0)).append(
			$($$.preloadImages(thread.html)).
			toggleClass('transit').
			css("opacity","0")
		);
		window.scrollTo(0,0)
		$(modal).animate({ 
			// Animate style parameters
				height:$(thread.html).outerHeight(),
				left:(assess(thread.html).left > 0)? assess(thread.html).left -12 : 12,
				top:(assess(thread.html).top > 0)? assess(thread.html).top - 12 : 12,
				width:$(thread.html).outerWidth()
			},{
				complete: function() {
					$(container).children().detach();
					$(container).append(thread.html);
					
					// Settings updater
					// if (opts && opts.settings) {};
					// if (settings.clickToClose == true) $(background).bind('click', self.close);
					if (settings.xButton !== false) {
					  $(container).find(".content-display").append($("<a href='#' class='close-button'>x</a>").bind('click', function(event) {Modal.close(); event.preventDefault();}))
					}
					$(modal).trigger('update', modal);
					
					$(thread.html).toggleClass("transit").animate({
						opacity:100
					},{
						complete:function() {
							$(modal).css("height","auto");
						},
						duration:500
					});
				},
				duration:500
			}
		)
	}
	
	/**
	 * destroy
	 * 
	 * Closes Moodle (modal window)
	 * 
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0 
	 */
	
	this.destroy = function() {
		
	}
	
	/**
	 * init
	 * 
	 * Initializes Moodle (creates modal window).
	 * 
	 * @access private
	 * @author Tim Steele
	 * @since 1.0
	 */
	init = function() {
		// Attach the modal container to the modal window
		$(gears.modal).append(gears.container);
		$(gears.modal).trigger('moodle-init');
	}
	
	// When the object is first created, call init once
	init();
	return self;
}

shcJSL.methods.moodle = function(target, options) {
	var method;	// (String) Method to use with the modal window [open|update|close]
	
	if (this.constructor == String) {
		method = this.toString();
	} else if (this.constructor == Object) {
		if (this.action) method = this.action.toString();
		else method = new String("create");
	} else if (this.constructor == Window) {
		method = new String("create");
	} else {
		// Something broke
		return;
	}
	
	($Moodle instanceof MOODLE.modal)? $Moodle[method](target, this):($Moodle = new $Moodle())[method](target,this)
		
	// if (method === ("create" || "destroy")) {
		// eval(capture)();
	// }
	
	return;
	
	//var options;	// (Object) Settings for the moodle widget.
	var assess;
	var center;
  var defaults; // Default settings for the modal window
  var modal; // Modal window element
  var overlay; // Overlay element
  var settings; // Configurations for the modal
  var self = this;    

	defaults	= {
		clickOverlayToClose: true,
		closeButton: true,
		height: 'auto',
		width: 'auto'
	};
	
	assess = function(element) {
		if (!element) element = modal;
		return {
			left: ($(window).width() - $(element).outerWidth())/2,
			top:	($(window).height() - $(element).outerHeight())/2
		};
	}
		
	center = function() {
		var styles; // Object for CSS key value pairs
		
		styles = {};
		if (assess().left < 0) {
			// if($(modal).css("width")) {
				// if ($(modal).data("width") == 'undefined' || $(modal).data("width") == undefined) $(modal).data("width",width);
			// }
			styles.left = 0;
			styles.width = $(window).width() - 24;
			styles.height = "auto";
		}
		else if (assess().left > 0) {
			styles.left = assess().left;
			if (($(modal).data("width") != 'undefined') && ($(modal).data("width") < $(modal).outerWidth())) styles.width = $(modal).data("width").width;
		}
		(assess().top < 0)? styles.top = 10:styles.top = assess().top;
		$(modal).css(styles);
		return modal;
	}

	/*
			[OVERLAY]
	*/
	this.overlay = {
		on	: function() {$('body').append(overlay); return self.overlay;},
		off	: function() { $(overlay).remove(); return self.overlay;}
	};
	
	this.loading = function() {
		// CHANGE TO USE ATTACH/DETACH
		if (($(container).children()).length == 0) {
			$(container).append("<section class='loading'></section>");
		} else {
			$(container).children().detach()
			$(container).append("<section class='loading'></section>");
		}
		//$(container).html("<section class='loading'></section>");
		return self;
	}
	
	/*
			[PUBLIC METHODS]
	*/
	this.open =	function(options) {
		if ($(modal).parents().is($('body').get(0))) {
			$(window).trigger("resize");
			if (options) settings = $.extend({},defaults,options);
		} else {
			self.overlay.on();
			$('body').append(modal.hide()); $(center()).show();
			if (options) settings = $.extend({},defaults,options);
			//$(window).bind("resize", center);
			
			// Apply event for firing when content is loaded
			$(modal).bind('update', $b.activate)
			$(modal).bind('update', $w.activate)
			// if (mobile_client && mobile_client == true) {
				// $(modal).bind('update', function() {
					// $.each($(modal).find('input[type="text"]'), function() {
// 						
						// $(this).bind('blur', function() {
							// $(window).bind('resize',center);
						// })
						// $(this).bind('focus', function() {
							// $(window).unbind('resize',center);
						// })
// 						
					// }) // END each
				// }) // END bind update
			// } // END if mobile_client	
		}
	};
	
	this.update = function(custom) {
		var thread; // The current thread/window to use.
		
		if (custom && custom.modal && custom.modal.complete && (typeof custom.modal.complete == 'function')) $(modal).bind('update', custom.modal.complete);
		
		if (custom && custom.thread) thread = custom.thread;
		else thread = $wf.threads[$wf.threads.length - 1][$wf.threads[$wf.threads.length-1].length - 1];
		
		if (custom && custom.refresh) {
			extras = {};
			extras.thread = thread;
			$.ajax({
				dataType: 'html',
				statusCode: {
					200: function(data, text, xhr) {
					$xhr[200](data, text, xhr, extras);
					}, // END StatusCode -> 200
					404: function(data, text, xhr) {
						$xhr[404](data, text, xhr, extras);
					}, // END StatusCode -> 404
					500: function(data, text, xhr) {
						$xhr[500](data, text, xhr, extras);
					} // END StatusCode -> 500
				},
				url: thread.url
			})
			return;
		}
						
		$($('body').get(0)).append($($$.preloadImages(thread.html)).toggleClass('transit').css("opacity","0"));
		window.scrollTo(0,0)
		$(modal).animate({ 
			// Animate style parameters
				height:$(thread.html).outerHeight(),
				left:(assess(thread.html).left > 0)? assess(thread.html).left -12 : 12,
				top:(assess(thread.html).top > 0)? assess(thread.html).top - 12 : 12,
				width:$(thread.html).outerWidth()
			},{
				complete: function() {
					$(container).children().detach();
					$(container).append(thread.html);
					
					// Settings updater
					// if (opts && opts.settings) {};
					// if (settings.clickToClose == true) $(background).bind('click', self.close);
					if (settings.xButton !== false) {
					  $(container).find(".content-display").append($("<a href='#' class='close-button'>x</a>").bind('click', function(event) {Modal.close(); event.preventDefault();}))
					}
					$(modal).trigger('update', modal);
					
					$(thread.html).toggleClass("transit").animate({
						opacity:100
					},{
						complete:function() {
							$(modal).css("height","auto");
						},
						duration:500
					});
				},
				duration:500
			}
		)
	};
	
	this.close = function() {
		Modal.loading();
		$($('body').get(0)).trigger("modal:close");
		$wf.kill()
		$(modal).remove();
		self.overlay.off();
		$(window).unbind("resize", center);
	};
	
	return this;
	
};

shcJSL.gizmos.bulletin['shcJSL.moodle.js'] = true;

/**
	 * Event Assigner
	 * 
	 * Assigns the click event for moodle to the element
	 * 
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0
	 */
if (shcJSL && shcJSL.gizmos)  {
	shcJSL.gizmos.moodle = function(element) {
		$(element).bind('click',function(event) {
			shcJSL.get(element).moodle();
			event.preventDefault();
		});
	}
}
