/**
 * @package Featured articles PRO - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @url http://www.codeflavors.com/featured-articles-pro/
 * @version 2.4
 */
(function (A) {
    A.fn.simpleTooltip = function (B) {
        var C = A.extend({}, A.fn.simpleTooltip.defaults, B);
        A("body").append("<div id='v-tooltip' class='" + C.cssClass + "' style='display:none;'></div>");
        return this.each(function () {
            var D = A(this);
            D.hover(function (E) {
                var F = D.attr("title") && !C.overrideElementTitle ? D.attr("title") : C.title;
                D.attr("rel", F);
                D.removeAttr("title");
                A("#v-tooltip").html(F);
                A("#v-tooltip").css("top", (E.pageY - C.xOffset) + "px").css("display", "block").css("left", (E.pageX + C.yOffset) + "px").css("z-index", 1000).css("position", "absolute").fadeIn("fast")
            }, function (E) {
                A("#v-tooltip").css("display", "none")
            });
            D.mousemove(function (E) {
                A("#v-tooltip").css("top", (E.pageY - C.xOffset) + "px").css("left", (E.pageX + C.yOffset) + "px")
            });
            D.mouseout(function (E) {
                D.attr("title", D.attr("rel"));
                D.removeAttr("rel")
            })
        })
    };
    A.fn.simpleTooltip.defaults = {
        title: null,
        xOffset: 10,
        yOffset: 20,
        overrideElementTitle: false
    }
})(jQuery);
jQuery(document).ready(function () {
	jQuery(".FA_info").simpleTooltip({
    	xOffset: -20,
       cssClass: "CF_Tips"
    })
});	