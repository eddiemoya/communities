/*
* Responslide: Liquid, Responsive, Sliding Banner
* @author Tim Steele
*
* @require jQuery 1.7
* @require Touchswipe 1.1.1
*
* Releases:
*
* @version [1.0: 2/21/12]
* - Initial script release
* @version [2.0: 4/19/12]
* - Added mobile touchscreen functionality
* - Allowed more customization
*/

/* [SAMPLE HTML]
 * Sample Options
 * "kc:options" => "{startingBanner:0, animate:true, autoSlideBanners:true, slideSelector: '*[kc\\\\:shard*=\"banner\"]', autoSlidingBannerInterval:7000}"
 * 
 */

/*
 * [CONFIGURATIONS]
 * Settings for Responslide based on your website.
 * 
 * rs.g 
 * ----
 * (Object)
 * This is the global object for the site scipts.
 * 
 * rs.p
 * ----
 * (String)
 * Prefix used on the name spaces for attributes.
 */
var rs = {};
rs.g = shcJSL;
rs.p = 'shc';

//var responslide;

/*
 * Arguments
  
   element: (HTMLObject), Required
     Element that is the Responslide
     
   options: (Object), Optional
     Options for Responslide
  
 */

responslide = function(element, options) {
	/*
	 * [PRIVATE VARIABLES]
	 * 
	 * active (Integer/Number)
	 * ----------------
	 * This is the current active slide number.
	 * 
	 * components (Array)
	 * ------------------
	 * This stores all the elements that make up the sliding banner in sub-arrays. 
	 * As of v. 1.0 default is: 'slides', 'squares'.
	 * 
	 * Left open the ability to allow users to add additional/custom elements
	 * when creating the banner. Current logic regarding setting an 'active' class 
	 * will automatically set 'active' as a class on the element in this array that
	 * corresponds to the active integer variable.
	 * 
	 * self (Object)
	 * ----
	 * This is the object.
	 * 
	 * timer (Integer/Number)
	 * -----
	 * The timer/timeout that handles sliding the banners.
	 * 
	 * widget
	 * ------
	 * The widget references the sliding banner element (argument 'banner').
	 * 
	 */
	
	var active;											// Integer of active slide
	var components =  new Object();	// Object of elements that make up Responslide
	var self = this; 								// This object				
	var timer;											// Timer for auto-rotating
	var widget;											// Responslide element
	var inTransition = false;								// Boolean value on whether Responslide is in transition
	/*
	 * [PUBLIC METHODS]
	 * 
	 * activeSlide
	 * -----------
	 * When invoked activeSlide will return the integer/number associated with
	 * the current active banner.
	 */
	this.activeSlide = function() {
		return active;
	}
	 
	/* 
	 * back
	 * ----
	 * When invoked back moves the sliding banner back one. If back is invoked
	 * and the sliding banner is currently displaying the first banner, then back
	 * will show the last banner in the array.
	 * 
	 */
	this.back = function() {
		var s; // Integer of active slide
		s = parseInt(active,10);
		if (s - 1 == -1) {
			self.show(parseInt(components.slides.length,10) - 1);
				
		}
		else {
			self.show(s - 1, self.conf.useHashBang)
		}
	}
	
	/*
	 * next
	 * ----
	 * When invoked next moves the sliding banner forward one banner. If next is
	 * invoked and the sliding banner is currently displaying the last banner, then
	 * next will show the first banner in the array
	 * 
	 */
	this.next = function() {
		var s; // Integer of active slide
		s = parseInt(active,10);
		if ((s + 1) == components.slides.length) {
			self.show(0);
		}
		else {
			self.show(s + 1, self.conf.useHashBang)
		}
	}
	
	/*
	 * show
	 * ----
	 * Arguments:
	 * n: the number (integer) of the banner that should be displayed.
	 * 
	 * When invoked, show displays the corresponding banner to the number that was
	 * passed in the arguments. It then assigns an 'active' class to every corresponding
	 * element in the components array.
	 * 
	 * If n is null when show is invoked, show will return false.
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
				if (self.conf.autoSlideBanners == true) self.startAutoSlide();	// Restart the timer
				if (hash) {
					window.location.hash = "#!" + n + "/";
				}
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
			//console.log(self.conf.animate)
			if (active == undefined || n != active) {
				// Let Responslide know that a transition is in progress
				inTransition = true;
				
				// Make sure this is not a new instance, and that the user wants a sliding animation
				if (typeof active != 'undefined' && self.conf.animate == true) {
					//console.log("ANIMATE");
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
	
	/*
	 * stopAutoSlide
	 * -------------
	 * Stop the timer for auto rotating the banner. This is public so external
	 * scripts can stop the timer if necessary. 
	 */
	this.stopAutoSlide = function() {
		if (timer) clearTimeout(timer);
	}
	
	/*
	 * startAutoSlide
	 * --------------
	 * Start the timer for auto rotating the banner. This is public so external
	 * scripts can start the timer if necessary.
	 * 
	 * If the 'autoSlidingBanners' configuration was not set to true when the
	 * object was instantiated and an external script wants to start the banner 
	 * auto rotating, the script first has to set the object.conf.autoSlidingBanners 
	 * to true, and then invoke startAutoSlide.
	 * 
	 * If the script does not set object.conf.autoSlidingBanners to true before 
	 * starting the auto rotation, the sliding banners will stop auto rotating
	 * after the first rotation.
	 */
	this.startAutoSlide = function() {
		timer = setTimeout(function(){self.next()},self.conf.autoSlidingBannerInterval);
	}
	
	/*
	 * [CONSTRUCTOR]
	 */
	//console.log(element);
	//console.log(options);
	widget = element;	// Set widget to the sliding banner element arguement
	
	if (typeof options == "string") options = eval("(" + options + ")")

	/*
	 * Configurations are set here. The defaults are displayed and are extended 
	 * with options from the options argument.
	 */
	self.conf = $.extend({},{
		animate: false,															// Animate the sliding of the banners
		arrowNavigationOnly:false,
		autoSlideBanners: false,										// Auto rotate the banners
		autoSlidingBannerInterval: 5000,						// If banners auto-rotate, the interval between rotations
		navigation:true,
		startingBanner: 0,													// The starting banner
		slideSelector: '*[' + rs.p + '\\:shard*="banner"]'		// Selector of slides					
	}, options)
	
	/*
	 * Find all the sliding panels in the rotating banner element and
	 * store them into sub-array 'slides' in the components array. 
	 */
	$(widget).find(self.conf.slideSelector).each(
		function() {
			if (typeof components.slides == ('undefined' || undefined)) components.slides = new Array();
			components.slides[components.slides.length] = this;
		}
	);
	
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
			self.conf.startingBanner = key;
		});
	}

	// Invoke show on the starting banner
	self.show(self.conf.startingBanner);
	
	// Trigger a pre-loading event
	$(window).trigger("responslide", {state: "preload", slide: self.conf.startingBanner});
	
	// Remove the pre-load class from the rotating banner
	$(widget).removeClass('pre-load');
	
	// If auto-rotate is set, start the timer
	if (self.conf.autoSlideBanners == true) {
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








CAROUSEL = $carousel = function(element, options){
	var element = element ;
	var items = {};
	var options = options;
	var mobius;
	var shiftleft;
	var shiftright;
	var lock = false;
	var self = this;


	items = {
		all 	: $(".product", element),
		active 	: { 
			last 	: $($('.active', this.all ).slice(-1)[0]).index(),
			first 	: $($('.active', this.all )[0]).index()
		},
		ondeck	: {
			 left 	: $($('.inactive-left', this.all ).slice(-1)[0]).index(),
			 right 	: $($('.inactive-right', this.all )[0]).index()

		}
	};


	shiftleft = function(){
			items.ondeck.left--;
			items.ondeck.right--;
			items.active.first--;
			items.active.last--;
	};

	shiftright = function(){
			items.ondeck.left++;
			items.ondeck.right++;
			items.active.first++;
			items.active.last++;
	};




	this.next = function(){

		self.mobius();

		if(!self.lock){
			self.lock = true;

			$(items.all[items.ondeck.right]).removeClass('inactive-right').addClass('active');

			$(items.all[items.active.first]).animate({marginLeft:'-100%'},"slow", function (){
				$(this).removeClass('active').addClass('inactive-left').css('marginLeft','');
				shiftright();
				self.lock = false;
			});

			//$(items.all[items.active.first]).removeClass('active').addClass('inactive-left').css('marginLeft','');
			//shiftright();
		}
	};

	this.prev = function(){

		self.mobius();

		if(!self.lock){
			self.lock = true;
			
			$(this).removeClass('active').addClass('inactive-right').css('marginRight','');

			$(items.all[items.ondeck.left]).animate({marginLeft:'0'},"slow", function (){

				$(this).removeClass('inactive-left').addClass('active').css('marginLeft', '');

				self.lock = false;			
				shiftleft();
			});


			//$(items.all[items.active.last]).animate({marginRight:'-100%'},"slow", function (){
				//$(this).removeClass('active').addClass('inactive-right').css('marginRight','');
			//});

			// $(items.all[items.active.first]).removeClass('active').addClass('inactive-right');
			// $(items.all[items.ondeck.left]).animate({marginRight:'-100%'},"slow", function (){
			// 	$(this).removeClass('inactive-left').addClass('active').css('marginRight', '');
			// 	
			// });


				
			

			

	
		}
	};

	this.mobius = function(){
			var last = $(items.all).length-1;
			var lastItem = items.all[last];
			var firstItem = items.all[0];

			if(items.active.first == 1){

				$(items.all[0]).before($(lastItem).removeClass('inactive-right').addClass('inactive-left'));
				shiftright();
			}
			if(items.active.last == $(items.all).length-2){

				$(items.all[last]).after($(firstItem).removeClass('inactive-left').addClass('inactive-right'));
				shiftleft();
			}

			items.all = $(".product", element);
			console.log(items.all)
						
	
	}

	this.stopAutoSlide = function() {
		if (self.timer) clearTimeout(self.timer);
	}

	this.startAutoSlide = function(interval) {
		var interval = interval;

		self.timer = setTimeout(function(){
			self.next();
			self.startAutoSlide(interval);
		},interval);
	}
}

shcJSL.methods.carousel = function(element, options){

	var element = element;
	var options = this;
	var carousel = new $carousel(element, options);

	if(typeof options.autoSlideInterval != 'undefined'){
		carousel.startAutoSlide(options.autoSlideInterval);
	}

	$(".right-arrow", element).bind('click', function(){
		carousel.stopAutoSlide();
		carousel.next();
	});

	$(".left-arrow", element).bind('click', function(){
		carousel.stopAutoSlide();
		carousel.prev();
	})

};

if(shcJSL && shcJSL.gizmos) {
	shcJSL.gizmos.carousel = function(element) {

		var options;

		options = ($(element).attr("shc:gizmo:options") != undefined) ? eval('(' + $(element).attr("shc:gizmo:options") + ')') : {};
		shcJSL.get(element).carousel(options);
		

	}
}

