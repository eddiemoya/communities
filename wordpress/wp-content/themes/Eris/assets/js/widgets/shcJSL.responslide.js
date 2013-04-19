/**
* Responslide: Liquid, Responsive, Sliding Banner
* @author Tim Steele
*
* @require jQuery 1.7
* @require Touchswipe 1.1.1
*
* Releases:
*
* @version [1.0: 02/21/12]
* @author Tim Steele
* - Initial script release
* 
* @version [2.0: 04/19/12]
* @author Tim Steele
* - Added mobile touchscreen functionality
* - Allowed more customization
* 
* @version [2.1: 04/16/13]
* @author Tim Steele
* - Added hashbang linking
* - Adjusted to match shcJSL.v1.0
* - Removed global configuration
* - Adjusted configurations
* - Commented out hashbang
* 
* @todo Find way to use multiple hasbang sliders on one page
*/

/**
 * @example
 * "kc:options" => "{startingBanner:0, animate:true, autoSlideBanners:true, slideSelector: '*[kc\\\\:shard*=\"banner\"]', autoSlidingBannerInterval:7000}"
 * 
 */

/**
 * Arguments
 * @param element (HTMLObject) Responslide element [Required] 
 * @param options (Object) Options for Responslide
 * Arguments
 */

Responslide = function(element, options) {
	/**
	 * @var active (Integer)	Current active slide index.
	 * @var components (Object)	Object with keys for components and values that are 
	 * 		sub-arrays of groups of components that make up the sliding banner. When 
	 * 		changing active slides, logic will apply a class 'active' to all components'
	 * 		sub-arrays' corresponding indexed element.
	 * 		
	 * 		As of v2.1 the only components are: 
	 * 		* slides:	The rotating slides.
	 * 		* squares:	The navigation elements.
	 * 
	 * 		This allows future iterations to add additional custom elements without the
	 * 		need to re-write the code.
	 * 
	 * @var self (Object)	A reference to the current scope's root object (Responslide).
	 * @var timer (Object)	The Timeout object used to auto-slide banners.
	 * @var widget (HTMLObject)	The sliding banner element, i.e. the element originally
	 * 		passed as the 'element' parameter.
	 */
	var active,						// Current active index;
		components =  new Object(),	// Object with component arrays;
		self = this,				// Scope root Responslide object;				
		timer,						// Auto-rotate Timeout element;
		widget,						// Sliding banner HTMLElement
		inTransition = false;		// Is scope Responslide currently transitioning
		
	/**
	 * @method activeSlide
	 * @access public
	 * @return int
	 * 
	 * Returns current active index of Responslide.
	 */
	this.activeSlide = function() {
		return active;
	}
	 
	/** 
	 * @method back
	 * @access public
	 * 
	 * Moves sliding banner index--; if index is < 0, then the index is the number
	 * of slides.
	 * 
	 * @example Moves slider left one slide, if the current slide is the first slide
	 * in the series - then it moves to the last slide in the series.
	 */
	this.back = function() {
		var s; // Integer of active slide
		s = parseInt(active,10);
		if (s - 1 == -1) {
			self.show(parseInt(components.slides.length,10) - 1);
				
		}
		else {
			// Hashbang 'back';
			// self.show(s - 1, self.conf.useHashBang)
			self.show(s - 1, false)
		}
	}
	
	/**
	 * @method next
	 * @access public
	 * 
	 * Moves sliding banner index++; if index is > number of slides, then the index is 0.
	 * 
	 * @example Moves slider to right, if current slide is last slide in the series, then
	 * it moves to the first slide in the series.
	 */
	this.next = function() {
		var s; // Integer of active slide
		s = parseInt(active,10);
		if ((s + 1) == components.slides.length) {
			self.show(0);
		}
		else {
			// Hashbang 'next';
			// self.show(s + 1, self.conf.useHashBang)
			self.show(s + 1, false)
		}
	}
	
	/**
	 * @method show
	 * @access public
	 * 
	 * @param n (Integer) Index of banner that should be displayed.
	 * @param hash (Boolean) Should a hashbang be added to the window.location.
	 * 
	 * Shows the corresponding components to the index passed through 'n', and 
	 * assigns the class 'active' to the corresponding component elements at 
	 * index n.
	 * 
	 * If hash is true, then it will set the hashbang for the current index.
	 * NOTE: Hashbang is disabled in v.2.1 until discovery can be made on using
	 * multiple hashbangs on one page.
	 * 
	 * If n is null, then show will return false.
	 */
	this.show = function(n, hash) {
		/*
		 * Check to make sure Responslide is not already in transition. If
		 * you attempt to transition the slides while a banner is already 
		 * sliding, it causes the banner to disappear.
		 */
		if (inTransition === false) {
			
			self.stopAutoSlide();	// Stop the timer from rotating slides
			
			if (typeof n == 'undefined') return false; // Make sure n is set
			
			/*
			 * toggleActive is a private method that handles the assigning and removing of
			 * classes on the sliding elements that are necessary after the slide.
			 */
			var toggleActive = function(n) {
				for (var c in components) {
					$(components[c][n]).addClass('active').removeClass("in-transit").css("height","").css("width","");
					if (typeof active != 'undefined') $(components[c][active]).removeClass('active').removeAttr("style");
				}
				active = n; // Set the new slide as the current active slide
				if (self.conf.autoslide == true) self.startAutoSlide();	// Restart the timer
				// Hashbang functionality disabled
				// if (hash) {
					// window.location.hash = "#!" + n + "/";
				// }
				// Triggers a window event that has the state and index of the current slide;
				$(window).trigger("responslide", {state: "end", slide: n});
				inTransition = false; // Transition is complete
			}
			
			/*
			 * If active is not set (a new instance of liquid sliding banner), then
			 * set the current banner
			 * 
			 * - OR -
			 * 
			 * Active is set, and the new banner (n) is not the same as the current active
			 * banner
			 * 
			 * invoke the taggleActive function to change the current banner.
			 */
			if (active == undefined || n != active) {
				// Let Responslide know that a transition is in progress
				inTransition = true;
				
				// Make sure this is not a new instance, and that the user wants a sliding animation
				if (typeof active != 'undefined' && self.conf.animate == true) {
					/*
					 * We need to set the height and width of the new slide to be the same
					 * as the current slide's height and width. This is removed after the
					 * transition.
					 */
					$(components.slides[n]).addClass("in-transit").height($(widget).height()).width($(widget).width()).animate(
						{left:"0%"}, 750, function() {
							toggleActive(n); // After the animation remove styles/classes
							
						}
					);
				}
				// There is no active slide and this is a new instance, so show the default slide
				else toggleActive(n);
				
			} else return false;
		
		} else return false;
		
	}
	
	/**
	 * @method stopAutoSlide
	 * @access public
	 * 
	 * Stop the timer for auto rotating the banner.
	 */
	this.stopAutoSlide = function() {
		if (timer) clearTimeout(timer);
	}
	
	/**
	 * @method startAutoSlide
	 * @access public
	 * 
	 * Start the timer for auto rotating the banner.
	 */
	this.startAutoSlide = function() {
		if (!self.conf.autoslide) self.conf.autoslide = true;
		timer = setTimeout(function(){self.next()},self.conf.interval);
	}
	
	/*
	 * CONSTRUCTOR
	 */
	(function() {
		
	})();
	widget = element;	// Set widget to the sliding banner element arguement
	
	if (typeof options == "string") options = eval("(" + options + ")")

	/**
	 * @param animate (String) [Default = false] If false, then no animation between transitions. 
	 * 		Otherwise current options include: 'slide','fade'
	 * @param navigation (String) [Default = 'full'] If false than no navigation will be displayed.
	 * 		Otherwise current options include:
	 * 		* 'full':	left/right arrows, and squares for each slide
	 * 		* 'arrows':	left/right arrows only.
	 * 		* 'legend':	squares for each slide, no arrows.
	 * 
	 * Configurations are set here. The defaults are displayed and are extended 
	 * with options from the options argument.
	 */
	self.conf = $.extend({},{
		animate: false,		// Animate the sliding of the banners;
		autoslide: false,	// Auto rotate the banners;
		// hashbang: false,	// Whether the slider should use the hashbang
		interval: 5000,		// Interval between autorotating banners;
		navigation:'full',	// Navigation to use;
		onset: 0,			// Starting index
	}, options);
	self.perm = {
		selector: '*[shc\\:responslide*="banner"]'	// Attribute looking for banners
	};
	
	/*
	 * Find all the sliding panels in the rotating banner element and
	 * store them into sub-array 'slides' in the components array. 
	 */
	$(widget).find(self.perm.selector).each(
		function() {
			if (typeof components.slides == ('undefined' || undefined)) components.slides = new Array();
			components.slides[components.slides.length] = this;
		}
	);
	
	/**
	 * @todo Rewrite this to use new navigation
	 */
	if (self.conf.navigation) {
		// Create the navigation container element
		if (!self.conf.arrowNavigationOnly) {
			var guide = $("<div class='rotating-banner-navigation' " + rs.p + ":shard='guide'></div>");
		}
		//var guide = _dom.createNewElement("div","rotating-banner-navigation", {"kgc:type":"rotatingBanner::guide"});
		
		/*
		 * Create a new square for each slide, assign the 'show' method as an onclick event 
		 * and store them in sub-array 'squares' in the components array.
		 */
	
		
	
		if (components.slides) {
			
			// BEFORE SLIDES
			// if more than one slider
			// NEW A [arrow] and move components.squares as new array here
			// create arrow, bind event here
			
			if (components.slides.length > 1 && self.conf.arrowNavigationOnly) {
				if (typeof components.squares == 'undefined') components.squares = new Array();
				components.squares[0] = $("<a href='#' class='next-slide'></a>");
				$(components.squares[0]).bind('click', function() {self.next();return false;});
			}
			
			if (!self.conf.arrowNavigationOnly) {
				var a; for (a=0;a<components.slides.length;a++) {
					if (typeof components.squares == 'undefined') components.squares = new Array();
					components.squares[a] = $("<a href='#' " + rs.p + ":slideNumber='" + a + "'></a>")
					$(components.squares[a]).bind('click', function() {self.show($(this).attr(rs.p + ":slideNumber"), true);return false;});
				}
			}
			
			// AFTER SLIDES
			// if more than one slider
			// New arrow, component bind back() here
			
			if (components.slides.length > 1 && self.conf.arrowNavigationOnly) {
				if (typeof components.squares == 'undefined') components.squares = new Array();
				components.squares[1] = $("<a href='#'  class='prev-slide'></a>");
				$(components.squares[1]).bind('click', function() {self.back();return false;});
			}
			
		}
				
		/*
		 * Append the sqaures to the navigation container element, and append
		 * the navigation container element to the sliding banner element.
		 */
		if (components.squares) {
			$.each(components.squares, function(){
				if (self.conf.arrowNavigationOnly) {
					$(".widget.hero-slider").append(this);
				} else {
					$(guide).append(this);
				}
			});
		}
	
		$(widget).append(guide);
	}

	// Check to see if the hashbang is used
	if (self.conf.useHashBang) {
		window.location.hash.replace(/#!(\d+)/, function(match, key, value, offset, string) {
			self.conf.onset = key;
		});
	}

	// Invoke show on the starting banner
	self.show(self.conf.onset);
	
	// Trigger a pre-loading event
	$(window).trigger("responslide", {state: "preload", slide: self.conf.onset});
	
	// Remove the pre-load class from the rotating banner
	$(widget).removeClass('pre-load');
	
	// If auto-rotate is set, start the timer
	if (self.conf.autoslide == true) {
		self.startAutoSlide();
	}
	
	// Look for elements bound to slides
	$.each(
		$("[data-responslide]"), function(index, value) {
			$(this).on('click', function(event) {
				self.show($(this).attr("data-responslide"), true); 
				event.preventDefault();
			})
		}
	)
	
	if ($().touchwipe) {
		$(widget).touchwipe({
			wipeLeft: function() {
				self.next();
			},
			wipeRight: function() {
				self.back();
			},
			min_move_x: 20,
			preventDefaultEvents: false
		});
	}
}

if(shcJSL && shcJSL.gizmos) {
	shcJSL.gizmos.responslide = function(element) {
		responslide(element, element.getAttribute("shc:options"));
		$(".hero-slider-container .banner").on("click", function(){
			if($(this).attr("shc:url")!=""){
				window.location.href = $(this).attr("shc:url");
			}
		});
	}
}
