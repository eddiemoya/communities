/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( http://www.codeflavors.com )
 * @version 2.4
 */

/**
 *	Starting with version 2.4 of FeaturedArticles for Wordpress,  
 *	each slider theme starts its sliders from a starter script file.
 *	For themes previous to this version, the plugin loads this file in order to start the sliders.
 */
(function($){
	$(document).ready(function(){
		$('.FA_slider').FeaturedArticles({
			load: function(){},
			before: function(){},
			after: function(){}
		});
	})
})(jQuery)