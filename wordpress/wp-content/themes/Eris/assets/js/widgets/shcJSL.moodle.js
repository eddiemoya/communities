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
 * @var MOODLE.moodle (Object) The modal window object
 * @var $Moodle (Object) a variation on the Moodle modal window object. 
 */
MOODLE = {}
MOODLE.moodle = $Moodle = function() {
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
	 * settings
	 * 
	 * List of settings for the moodle object.
	 * 
	 * @access Private
	 * @author Tim Steele
	 * @since 1.0
	 * 
	 * @param state (String) The current state of the modal window ['open'|'closed']
	 * @param clickOverlayToClose (Boolean) whether the user can click on the overlay to close the modal
	 * @param height (String/Integer) The height of modal window, or auto
	 * @param width (String/Integer) The width of the modal window, or auto
	 */
	settings = {
		defaults: {
			clickOverlayToClose: true,
			height: 'auto',
			width: 'auto'
		},
		state: 'closed'
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
		if (!element) element = modal;
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
		 * If yes, abut the modal to the left browser window edge.
		 * If no, get the centering value and apply it.
		 */
		(getPosition().left < 0)? styles.left = 0:styles.left = getPosition().left;
		// Apply styles to center the modal
		$(modal).css(styles);
		// Return modal element
		return modal;
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
		if (($(container).children()).length != 0) $(container).children().detach()
		$(container).append("<section class='loading'></section>");
	}
	
	this.test = function() {
		toggleOverlay();
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
	this.create = function() {
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
	}
	
	/**
	 * update
	 * 
	 * Updates Moodle (modal window) with new content
	 * 
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0
	 */
	this.update = function() {
		
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
		// $(modal).append(container);
	}
	
	// When the object is first created, call init once
	init()
}

shcJSL.methods.moodle = function(target, options) {
	var method;	// (String) Method to use with the modal window [open|update|close]
	
	if (this.constructor == String) {
		method = this.toString();
	}
	else if (this.constructor == Object) {
		method = this.action.toString();
	}
	
	if (this.constructor == Window) {
		method = new String("create");
	}
		
	// if (method === ("create" || "destroy")) {
		// eval(capture)();
	// }
	
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