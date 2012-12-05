/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( http://www.codeflavors.com )
 * @version 2.4
 */

/**
 *	Author: CodeFlavors ( http://www.codeflavors.com )
 *	Copyrigh (c) 2011 - author
 *	License: MIT(http://www.opensource.org/licenses/mit-license.php) and GPL (http://www.opensource.org/licenses/gpl-license.php)
 *	Package: Wordpress Featured Articles Lite plugin
 *	Version: 1.1
 *	jQuery version: 1.4.4
 *	Uses: DOMMouseScroll by Brandon Aaron (http://brandonaaron.net)
 */
;(function($){
    $.fn.FeaturedArticles = function(options) {
        // support mutltiple elements
       	if (this.length > 1){
            this.each(function() { 
				$(this).FeaturedArticles(options);				
			});
			return this;
        }
		// combine parameters from wordpress with parameters set from slider initialization script
		var params = get_wp_params(),
			id = $(this).attr('id'),
			o = params[id] ? $.parseJSON(params[id].replace(/&quot;/g, "\"")) : {};		
		if( o.slideDuration ) o.slideDuration = parseFloat(o.slideDuration)*1000;
		if(o.effectDuration) o.effectDuration = parseFloat(o.effectDuration)*1000;			
		var options = $.extend({}, o, options);
		
		// SETUP private variables;
        var FeaturedArticles = this;
		
		// setup options
        var defaultOptions = {
			slideDuration:5000,
			effectDuration:1000,
			fadeDist:null,
			fadePosition:null,
			stopSlideOnClick: false,
			autoSlide: false,
			mouseWheelNav: false,
			/* Events */
			load: function(){}, // fires on script being ready 
			before: function(){}, // fires on slide change before any animation is started on slides
			after: function(){}, // fires on slide change after all animations are done
			change: function(){}, // firex on slide chage
			start: function(){}, // fires when autoslide goes on
			stop: function(){}, // fires when autoslide goes off
			/* Selectors */
			articleSelector:'.FA_article', // articles selector. Must be descendant of container ( this )
			individualNavSelector:'.FA_navigation a', // navigation links selector
			backSelector:'.FA_back', // go back navigation selector
			nextSelector:'.FA_next' // go next navigation selector
		};		
		var options = $.extend({}, defaultOptions, options);
		var self = this;
		
		this.currentKey = 0;
		this.interval = false;
		this.stopped = false;
		this.paused = false;
		
		var initialize = function(){
			self.slides = $(self).find(options.articleSelector);
			if( self.slides.length < 2 ){
				return;
			}
			
			prepareSlides();
			prepareNavigation();
			prepareSideNavs();
			
			// Author link. Please give credit where credit is due.

//			var aLink = $('<a>', {
//				'href':'http://www.codeflavors.com/featured-articles-pro/',
//				'title':'Powered by FeaturedArticles for Wordpress',
//				'target':'_blank',
//				'text':'',
//				'style':'display:block; position:absolute; right:5px; bottom:5px; width:16px; height:16px; background-image:url(http://www.codeflavors.com/r_ico/fa_lite_ico.png); z-index:10000;'
//			}).appendTo($(self));
				
			var o = settings();
			if( o.autoSlide ){
				startSlider();
				$(self).mouseenter(function(){ stopSlider(); self.paused = true; });
				$(self).mouseleave(function(){ startSlider();  self.paused = false; });
			}
			
			if( o.mouseWheelNav ){
				$(self).mousewheel(function(e, delta){
					e.preventDefault();
					if (delta > 0) {
						var key = self.currentKey - 1 < 0 ? self.slides.length - 1 : self.currentKey - 1
					} else {
						if (delta < 0) {
							var key = self.currentKey + 1 > self.slides.length - 1 ? 0 : self.currentKey + 1
						}
					}
					stopSlider();
					goToSlide(key, delta);
				})
			}			
			// fire load event
			options.load.call(self, o);			
			return self;
		}
		
		var settings = function(){
			return options; 
		}
		
		var changeSlide = function(){
			var i = self.currentKey + 1 >= self.slides.length ? 0 : self.currentKey + 1;
			goToSlide(i);
		}
		
		var stopSlider = function(){
			clearInterval(self.interval);
			self.interval = false;
			var o = settings();
			o.stop.call(self, {});
		}
		
		var startSlider = function(){
			if( self.stopped ) return;
			
			var o = settings();
			if(self.interval){
				clearInterval(self.interval);
			}
			var t = function(){
				changeSlide(self);
			}
			self.interval = setInterval(t, o.slideDuration||3000);
			o.start.call(self, {});
		}
		
		var prepareSlides = function(){
			
			var styles = {
				'position': 'absolute',
				'top': 0,
				'left': 0,
				'z-index':1
			};
			var visibleSlideStyles = {
				'z-index':100
			}
			
			if( options.effectDuration > 0 ){
				styles.opacity = 0;	
				visibleSlideStyles.opacity = 1;
				visibleSlideStyles.filter = 1;
			}			
			self.slides.css(styles);
			$(self.slides[self.currentKey]).css(visibleSlideStyles);
		}
		/* individual slide navigation */
		var prepareNavigation = function(){
			self.navLinks = $(self).find(options.individualNavSelector);
			if( self.navLinks.length < 1 ) return;
			var o = settings();
			
			$.each(self.navLinks, function(i, el){
				var title = $(el).parent().find('span');
				if( title.length > 0 ){
					$(el).mouseenter(function(e){
						$(title).css({'display':'block','top': -25,'opacity':0}).animate({'opacity':1,'top':-20});
					}).mouseleave(function(e){
						title.css({'display':'none','opacity':0,'top':-25});
					})
				}
				$(el).click(function(e){
					e.preventDefault();
					if ( self.interval ) {
						stopSlider();
					}
					goToSlide(i);
					if (!o.stopSlideOnClick && o.autoSlide) {
						if( !self.paused )
							startSlider();
					}else if(o.stopSlideOnClick && o.autoSlide){
						self.stopped = true;
					}	
				})
				if (i == self.currentKey) {
                    $(self.navLinks[i]).addClass("active");
                }
			})		
		}
		/* side navigation (back/forward) */
		var prepareSideNavs = function(){
			var navBack = $(self).find(options.backSelector),
				navNext = $(self).find(options.nextSelector),
				o = settings();
				
			if( navBack.length > 0 ){
				$(navBack).click(function(e){
					e.preventDefault();
					prev();
				})
			}
			if( navNext.length > 0 ){
				$(navNext).click(function(e){
					e.preventDefault();
					next();
				})
			}
		}
		/* navigate to the next slide */
		var next = function(){
			if ( self.interval ) {
				stopSlider();
			}
			changeSlide();
			if (!o.stopSlideOnClick && o.autoSlide) {
				if( !self.paused )
					startSlider();
			}else if(o.stopSlideOnClick && o.autoSlide){
				self.stopped = true;
			}
		}
		/* navigate to previous slide */
		var prev = function(){
			var index = self.currentKey - 1 >= 0 ? self.currentKey - 1 : self.slides.length - 1;
			if ( self.interval ) {
				stopSlider();
			}
			goToSlide(index, 1);
			if (!o.stopSlideOnClick && o.autoSlide) {
				if( !self.paused )
					startSlider();
			}else if(o.stopSlideOnClick && o.autoSlide){
				self.stopped = true;
			}
		}
		
		var goToSlide = function(index, direction){
			if (index == self.currentKey) {
                return;
            }
            if ( index < 0 || index >=self.slides.length ) {
                return;
            }
			
			var dir = direction||-1,
				o = settings(),
				fading = o.fadePosition == "left" ? "left" : "top";
				
			var data = {
				'parent':$(self), 
				'currentElem':self.slides[self.currentKey], 
				'nextElem':self.slides[index],
				'direction':direction,
				'currentIndex':self.currentKey,
				'nextIndex':index
			};
			o.before.call(self, data);
			
			var currentStyles = {
				'z-index':10
			};
			var nextStyles = {
				'z-index':100,
				'display':'block'
			};
			var currentAnimate = {
				'opacity':0
			};
			var nextAnimate = {
				'opacity':1
			};
			
			switch( fading ){
				case 'top':
					currentStyles.top = 0;
					nextStyles.top = -dir*o.fadeDist;
					currentAnimate.top = dir * o.fadeDist;
					nextAnimate.top = 0;
				break;				
				case 'left':
					currentStyles.left = 0;
					nextStyles.left = -dir*o.fadeDist;
					currentAnimate.left = dir * o.fadeDist;
					nextAnimate.left = 0;
				break;
			}
			
			if( o.effectDuration > 0 ){
				$(self.slides[self.currentKey]).stop().css(currentStyles).animate(
					currentAnimate,{
						queue:false, duration:o.effectDuration, complete:function(){
							$(this).css({'z-index':1, 'display':'none'});
				}});
				$(self.slides[index]).stop().css(nextStyles).animate(
					nextAnimate,{
						queue:false, duration:o.effectDuration, complete:function(){
							$(self.slides[index]).css('filter', '');
							o.after.call(self, data);						
				}});
			}else{
				$(self.slides[self.currentKey]).css('z-index', 10);
				$(self.slides[index]).css('z-index', 100);
				o.after.call(self, data);
			}
			
			
			if (self.navLinks.length > 0) {
				$(self.navLinks[self.currentKey]).removeClass("active");
				$(self.navLinks[index]).addClass("active")
			}
			self.currentKey = index;
			o.change.call(self, data);
		}
		
		/* public methods */
		this.next = function(){ 
			next(); 
		}	
		this.prev = function(){
			prev();
		}
		this.stop = function(){
			stopSlider();
		}
		this.start = function(){
			startSlider();
		}
		this.settings = function(){
			return settings();
		}		
		return initialize();
    }
})(jQuery);

// Helper function to return parameters set from wp and injected directly into the page
var wp_params = false;
function get_wp_params(){
	if(wp_params) return wp_params;
	// get parameters set from Wordpress
	var hParams = typeof(FA_Lite_params) !== 'object' ? {} : FA_Lite_params,
		fParams = typeof(FA_Lite_footer_params) !== 'object' ? {} : FA_Lite_footer_params;
	wp_params = jQuery.extend(hParams, fParams);
	return wp_params;	
}