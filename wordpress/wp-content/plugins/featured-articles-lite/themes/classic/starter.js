/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */
(function($){
	
	$(document).ready(function(){		
		
		$('.FA_overall_container_classic').FeaturedArticles({
			load: classicTheme_onLoad,
			before: classicTheme_beforeSlide,
			after: classicTheme_afterSlide
		});
		
	})
	
	var classicTheme_onLoad = function(options){
		// animate slide titles from span on bottom nav mouse over
		$.each( this.navLinks, function(i, el){
			var title = $(el).parent().find('span');
			if( title.length > 0 ){
				$(el).mouseenter(function(e){
					$(title).css({'display':'block','top': -25,'opacity':0}).animate({'opacity':1,'top':-20});
				}).mouseleave(function(e){
					title.css({'display':'none','opacity':0,'top':-25});
				})
			}
		})
	}
	// before sliding animation callback
	var classicTheme_beforeSlide = function(d){
		
		var s = this.settings();
		if( !s.classic_thumb_animate ) return;
		
		var img = jQuery(d.nextElem).find('.image_container');
		img.css({'margin-top':-500, 'margin-bottom':800}).animate({'margin-top':0}, {queue:false, duration:800});
		
		var img = jQuery(d.currentElem).find('.image_container');
		img.animate({'margin-top':500}, {queue:false, duration:800});
	};
	// after sliding animation callback
	var classicTheme_afterSlide = function(d){
		
		var s = this.settings();
		if( !s.classic_thumb_animate ) return;
		
		var img = jQuery(d.currentElem).find('.image_container');
		img.css({'margin-top':0});
	};	
	
})(jQuery)