/*
* Moodle: Responsive Modal Window
* @author Tim Steele
*
* @require jQuery 1.7.2
*
* Releases:
*
* @version [1.0: 2012-07-24]
* - Initial script release
*/


console.log(jQuery);

shcJSL.moodle = function () {
	var assess	// Function, find distances from center
  var center; // Function for centering modal
  var container; // Container for the modal contents
  var defaults; // Default settings for the modal window
  var modal; // Modal window element
  var overlay; // Overlay element
  var settings; // Configurations for the modal
  var self = this;    
    
  overlay = $("<div class='shc_overlay'></div>");
  modal = $("<section class='shc_modal'></section>");
  container = $("<div class='shc_modal_container'></div>");
  $(modal).append(container);

	defaults	= {
		xButton: true,
		exitFromOverlay: true
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