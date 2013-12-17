/* Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
* Licensed under the MIT License (LICENSE.txt).
*
* Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
* Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
* Thanks to: Seamus Leahy for adding deltaX and deltaY
*
* Version: 3.0.6
*
* Requires: 1.2.2+
*/
(function(D){var B=["DOMMouseScroll","mousewheel"];if(D.event.fixHooks){for(var A=B.length;A;){D.event.fixHooks[B[--A]]=D.event.mouseHooks}}D.event.special.mousewheel={setup:function(){if(this.addEventListener){for(var E=B.length;E;){this.addEventListener(B[--E],C,false)}}else{this.onmousewheel=C}},teardown:function(){if(this.removeEventListener){for(var E=B.length;E;){this.removeEventListener(B[--E],C,false)}}else{this.onmousewheel=null}}};D.fn.extend({mousewheel:function(E){return E?this.bind("mousewheel",E):this.trigger("mousewheel")},unmousewheel:function(E){return this.unbind("mousewheel",E)}});function C(J){var H=J||window.event,G=[].slice.call(arguments,1),K=0,I=true,F=0,E=0;J=D.event.fix(H);J.type="mousewheel";if(H.wheelDelta){K=H.wheelDelta/120}if(H.detail){K=-H.detail/3}E=K;if(H.axis!==undefined&&H.axis===H.HORIZONTAL_AXIS){E=0;F=-1*K}if(H.wheelDeltaY!==undefined){E=H.wheelDeltaY/120}if(H.wheelDeltaX!==undefined){F=-1*H.wheelDeltaX/120}G.unshift(J,K,F,E);return(D.event.dispatch||D.event.handle).apply(this,G)}})(jQuery);