/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
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

(function (A) {
    A.fn.FA_form = function () {
        A("#FeaturedArticles_settings .FA_number").keyup(function (E) {
            var C = A(this).attr("value");
            if (A(this).hasClass("float")) {
                if (E.keyCode == 190 || E.keyCode == 110) {
                    return
                }
                var D = parseFloat(C)
            } else {
                var D = parseInt(C)
            }
            A(this).attr("value", D ? D : 0)
        });
        var B = A("input[name=displayed_content]");
        B.each(function (D, E) {
            var C = A(E).attr("id");
            A(E).click(function (F) {
                if (E.checked) {
                    A("#d_" + C).css("display", "block");
                    B.each(function (G, H) {
                        if (A(H).attr("id") !== C) {
                            A("#d_" + A(H).attr("id")).css("display", "none")
                        }
                    })
                }
            })
        });
        A("#FA-wrapper label").each(function (C, D) {
            if (A(D).attr("title")) {
                A(D).addClass("FA_info")
            }
        });
        A(".FA_info").simpleTooltip({
            xOffset: -20,
            cssClass: "CF_Tips"
        })
    }
})(jQuery);
jQuery(document).ready(function () {
	jQuery("FeaturedArticles_settings").FA_form()
});