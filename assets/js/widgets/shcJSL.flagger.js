/**
* actions
* @author Sebastian Frohm
*
* @package shcJSL
 * @require jQuery 1.7.2
 * @require shcJSL.flagger
*
* @version [1.0: 2012-08-08]
* - Initial script release
*/

FLAGGER = {};

/**
 *
 * @type {Function}
 * @var element (Object) tooltip base element on page
 * @var options (Object) options regarding the construction of the tooltip
 */
FLAGGER.flagger = $flagger = function(element, options) {
    var _this = this;


};

shcJSL.gizmos.bulletin['shcJSL.flagger.js'] = true;

/**
 * Controller -
 * @param target
 * @param options = this instance
 */
shcJSL.methods.flagger = function(_element, options) {
    var _elementOptions = ($(_element).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(_element).attr("shc:gizmo:options") + ')')).flagger) ? (eval('(' + $(_element).attr("shc:gizmo:options") + ')')).flagger:{}):{};

    var flagger = ($flagger instanceof FLAGGER.flagger) ? $flagger : new $flagger(jQuery(_element), _elementOptions);
};

/**
	 * Event Assigner
	 *
	 * Assigns the click event for moodle to the element
	 *
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0
	 */
if (shcJSL && shcJSL.gizmos)  {
	shcJSL.gizmos.flagger = function(element) {
        shcJSL.get(element).flagger(element);
    }
}
