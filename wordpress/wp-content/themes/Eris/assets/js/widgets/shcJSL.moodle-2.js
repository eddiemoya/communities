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
	 * @var escapeModal (Function) Use the escape key to close the modal
	 * @var gears (object) The parts that make up the modal window
	 * @var self (Object) The main modal window object
	 * @var toggleLoading (Function) Toggles the modal loading screen
	 * @var toggleOverlay (Function) Toggles overlay on or off
	 */
	var centerModal,
		getPosition,
		escapeModal,
		gears = {},
		self 	= this,
		settings,
		toggleLoading,
		toggleOverlay;
	
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
		clickOverlayToClose: false,
		height: 'auto',
		method: 'ajax',
		type: 'GET',
		width: 'auto'
	}
	
	/**
	 * PRIVATE METHODS
	 */
	
	/**
	 * escapeModal
	 * 
	 * Closes the modal window when the escape key is pressed.
	 * 
	 * @access Private
	 * @author Tim Steele
	 * @since 1.0
	 */
	
	escapeModal = function(event) {
		event.preventDefault();
		if (event.keyCode == 27) self.destroy(event);
	}
	
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
			$(window).bind('moodle-update', shcJSL.gizmos.activate);
			// Trigger the moodle create event
			$(window).trigger('moodle-create');
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
		var error;	// Function to create the error message
		
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
		//var settings;	// Default settings
		
		/*
		 * If the options argument exists, first check if the options is the
		 * window object -- this can happen if no argument is set, and it sets
		 * off all sorts of security alarms and browsers stop executing the script.
		 * If options exist and is not the window object, then set argumentOptions to
		 * equal options, otherwise set argumentOptions to an empty object.
		 */
		(options)? (options == window)? argumentOptions = {}: argumentOptions = options:argumentOptions = {}; 
		/*
		 * This is a bit confusing looking, but simple:
		 * If the element contains the attribute 'shc:gizmo:option' attribute
		 * then turn the shc:gizmo:option attribute into a JSON object and see
		 * if it has a 'moodle' object/property. If neither the 'shc:gizmo:options'
		 * attribute is found or the moodle property, then 'elementOptions' is an
		 * empty object ({}), otherwise set elementOptions to be moodle property of
		 * the shc:gizmo:options attribute/JSON object.
		 */ 
		elementOptions = ($(element).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(element).attr("shc:gizmo:options") + ')')).moodle)?(eval('(' + $(element).attr("shc:gizmo:options") + ')')).moodle:{}):{};
		// Make a clone of the default settings, as we don't actually want to change the defaults
		settings = jQuery.extend(true, {data:{}}, defaults);
 		
 		// By the power of JavaScript combine all three objects to form 'settings'
 		jQuery.extend(true, settings, elementOptions, argumentOptions)
		
		// If the method is AJAX
		if (String(settings.method).toLowerCase() === 'ajax') {
			// If a URL has not been included in the element options or argument options,
			// check the actual element for a URL
			if (!settings.target) settings.target = $(element).attr("href");
			// Make sure we have a URL to send the AJAX request
			if (settings.target && (settings.target != undefined || settings.target != '')) {
				jQuery.ajax({
					dataType: 'html',
					data: settings.data,
					type: settings.type,
					url: settings.target
				}).success(function(data, status, xhr) {
					var htmlObject;	// New HTMLObject created the the AJAX response string
					
					// Convert the string into an HTML element
					htmlObject = shcJSL.first(shcJSL.preloadImages(shcJSL.renderHTML(shcJSL.createNewElement("div"), data)));
					compose(htmlObject, [status, xhr])
				}).error(function(xhr, status, message) {
					error(message, xhr, status)
				})
			} // END if settings.target
		}
		
		if (String(settings.method).toLowerCase() === 'local') {
			// Check to see if the object exists on page.
			if (document.getElementById(settings.target)) {
				compose(document.getElementById(settings.target));
			}
			else {
				error("ID not found.")
			}
		}
		
		function compose(content, data) {
			// If height and width exists not as 'auto' and is a valid number
			// set the height and width
			if (settings.width && settings.width != 'auto') if (!(isNaN(settings.width))) $(content).css('width', settings.width);
			if (settings.height && settings.height != 'auto') if (!(isNaN(settings.height))) $(content).css('height', settings.width);
			
			// Attach the 'mooodle_transit' class to the element, and turn opacity to zero
			// and then append it to the body so you can get the element's dimensions
			$(document.body).append($(content).show().toggleClass('moodle_transit').css('opacity','0'));
			// Scroll to the top of the page, where the modal window is
			window.scrollTo(0,0);
			// Animate the modal window to the height/width of the element
			$(gears.modal).animate({
				height:$(content).outerHeight(),
				left:(getPosition(content).left > 12)? getPosition(content).left -12:12,
				top:(getPosition(content).top > 12)? getPosition(content).top - 12:12,
				width:$(content).outerWidth()
			}, {
				complete: function() {
					// Remove all current elements of the modal container and
					// append the new content
					$(gears.container).children().detach();
					$(gears.container).append(content);
					
					// Create and attach the close button, set it to close the modal
					$(gears.container).append($(shcJSL.createNewElement("a","close-button",{href:'#'})).bind('click', {data:settings}, self.destroy))
					// User's can click on the overlay to close the modal if 
					// clickOverlayToClose is set to true.
					if (settings.clickOverlayToClose) $(gears.overlay).bind('click', {data:settings},self.destroy);
					// Otherwise don't let them close the overlay when it is clicked
					else $(gears.overlay).unbind('click',self.destroy);
					
					// Escape key closes modal
					$(document).bind('keyup', {data:settings}, escapeModal)
					
					// Trigger the moodle-update event
					$(window).trigger('moodle-update', gears.modal);
					
					// Make the modal content visible
					$(content).toggleClass("moodle_transit").animate({
						opacity:100
					},{
						complete: function() {
							$(gears.modal).css("height","auto");
						}					
					},{
						duration:750
					}) // End content animate
				},
				duration:500
			}) // END animate
		} // END compose
		
		// If an error occurs during the modal window process
		function error(message, xhr, status) {
			var oops; // Error HTMLObject

			oops = "<article class='span12 content-container' id='moodle_error'><section class='content-body clearfix'><h6 class='content-headline'>Oops!</h6><p>The modal window has encountered a problem.</p>";
			if (message) oops += "<p>" + ((xhr)? xhr.status:"") + " " + message + "</p>";
			oops += "</section></article>";
			oops = $(oops);
			compose(oops);
		}
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
	
	this.destroy = function(event) {
		// If the modal window had a local on page element
		// return that element to the page
		if (String(settings.method).toLowerCase() == 'local') {
			try {$(document.body).append($("#"+settings.target).hide())} 
			catch(error) {}
		}
		
		// turn on the loading screen
		toggleLoading();
		// Remove the modal window
		$(gears.modal).remove();
		// Remove the resize event
		$(window).unbind("resize", centerModal);
		// Remove the 'escape key to close' event
		$(document).unbind('keyup',escapeModal);
		// Remove the moodle update triggers gizmos event
		$(window).unbind('moodle-update', shcJSL.gizmos.activate);
		// Trigger the moodle-close event
		$(window).trigger("moodle-close");
		// Turn off loading
		toggleLoading();
		// Turn off the Overlay
		toggleOverlay();
	}
	
	this.load = function() {
		toggleLoading();
		centerModal();
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
	} else if (this == window) {
		method = new String("create");
	} else {
		// Something broke
		return false;
	}
	
	($Moodle instanceof MOODLE.modal)? $Moodle[method](target, this):($Moodle = new $Moodle())[method](target,this)
}

shcJSL.gizmos.bulletin['shcJSL.moodle-2.js'] = true;

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
