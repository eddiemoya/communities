/**
* actions
* @author Sebastian Frohm
*
* @package shcJSL
* @require jQuery 1.7.2
*
* @version [1.0: 2012-08-08]
* - Initial script release
*/
ACTIONS = {};
/**
 *
 * @type {Function}
 * @var element (Object) tooltip base element on page
 * @var options (Object) options regarding the construction of the tooltip
 */
ACTIONS.actions = $actions = function(element, options) {
    var _this = this;

    _this.action = {
        element: null
    };

    _this.options = {
        element: null,
        post: {
            id: 0,
            name: '',
            sub_type: '',
            type: ''
        }
    };

    _this.click = function() {
        _this._postAction();
    };

    _this.init = function(element, options) {
        _this.setActionObject(element);

        /**
         * Take in options based in on creation and shove onto existing _this.options;
         */
        _this.options = jQuery.extend(true, _this.options, options);
    };

    _this.setActionObject = function(element) {
        _this.action.element = element;
    };

    _this._postAction = function() {
        var post = _this.options.post;

        jQuery.post(
            ajaxurl + '?action=add_user_action',
            post,
            function(data) {
                jQuery(_this.action.element).addClass('active');
            }
        );
    };

    _this.init(element, options);
};

shcJSL.gizmos.bulletin['shcJSL.actions.js'] = true;

/**
 * Controller -
 * @param target
 * @param options = this instance
 */
shcJSL.methods.actions = function(_element, options) {
    var _elementOptions = ($(_element).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(_element).attr("shc:gizmo:options") + ')')).actions) ? (eval('(' + $(_element).attr("shc:gizmo:options") + ')')).actions:{}):{};

    var action = ($actions instanceof TOOLTIP.tooltip) ? $actions : new $actions(jQuery(_element), _elementOptions);

    var i, method;

    action.click(event);
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
	shcJSL.gizmos.actions = function(element) {
		options = ($(element).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(element).attr("shc:gizmo:options") + ')')).actions)?(eval('(' + $(element).attr("shc:gizmo:options") + ')')).actions:{}):{};

        jQuery(element).click(function() {
            shcJSL.get(element).actions(element, options);
        });
	}
}
