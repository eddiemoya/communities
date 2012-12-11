/**
 * @author Tim Steele
 * @param element: 
 */
shcJSL.gizmos.persistr = function(element) {
	var offset;	// (Int) pixel difference from the top of the page
	var persist;		// (Function) function to persist the element
	var sticker;	// (HTMLObject) the persisted element
	
	sticker = element;
	offset = ($(sticker).offset().top).toFixed(0);
	
	function persist(event) {
		var y;	// Y scroll, y-axis of the scroll bar
		
		y = $(window).scrollTop();
		if (y >= offset) {
			$(sticker).parent().css("padding-top",$(sticker).outerHeight());
			$(sticker).addClass("persist");
		} else {
			$(sticker).removeClass("persist");
			$(sticker).parent().css("padding-top",0)	
		}
	}
	
	$(window).bind('scroll', persist);
	persist();
}

/**
 * @author Jason Corradino
 * @description:
 * Load specific rel="external" links in new windows, can be expanded to include all external links in the future.
 */

jQuery(window).load(
	function() {
		jQuery("#header_shopping a[rel=external]").on("click", function(event){
			event.preventDefault();
			window.open(this.href);
		});
	}
)