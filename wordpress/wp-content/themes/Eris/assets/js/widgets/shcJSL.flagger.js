/**
* actions
* @author Sebastian Frohm
*
* @package shcJSL
 * @require jQuery 1.7.2
 * @require shcJSL.tooltipForm
*
* @version [1.0: 2012-08-08]
* - Initial script release
*/

TOOLTIPFORM = {};

/**
 *
 * @type {Function}
 * @var element (Object) tooltip base element on page
 * @var options (Object) options regarding the construction of the tooltip
 */
TOOLTIPFORM.tooltipForm = $tooltipForm = function(element, options) {
    var _this = this;

    _this.options = {
        form: {
            elements:  [
                {
                    element: 'textarea',
                    class: 'flagField',
                    attributes: {
                        'cols': 2,
                        'name': 'comment',
                        'aria-required': true,
                        'id': 'comment-body-<?php echo $comment->comment_ID ?>'
                    }
                },
                {
                    element: 'input',
                    class: '',
                    attributes: {
                        'type': 'submit',
                        'value': 'Submit'
                    }
                },
                {
                    element: 'input',
                    class: '',
                    attributes: {
                        'type': 'reset',
                        'value': 'Cancel'
                    }
                }
            ]
        }
    };

    _this.tooltip = {};

    _this.renderForm = function() {
        shcJSL.createNewElement("div");
    };

    _this.resetOptions = function(options) {
        _this.options = jQuery.extend(true, _this.options, options);
    };

    _this.init = function(element, options) {
        try {
            _this.actedObj.element = (typeof(element) === 'object') ? element : null;
        } catch(e) {
            console.log('The element is not a jQuery Object! Bailing!');

            return false;
        }

        /**
         * Take in options based in on creation and shove onto existing _this.options;
         */
        _this.options = jQuery.extend(true, _this.options, options);

        _this.tooltip =
    };

    _this.init(element, options);
};

shcJSL.gizmos.bulletin['shcJSL.tooltipForm.js'] = true;

/**
 * Controller -
 * @param target
 * @param options = this instance
 */
shcJSL.methods.tooltipForm = function(_element, options) {
    var _elementOptions = ($(_element).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(_element).attr("shc:gizmo:options") + ')')).tooltipForm) ? (eval('(' + $(_element).attr("shc:gizmo:options") + ')')).tooltipForm:{}):{};

    var tooltipForm = ($tooltipForm instanceof TOOLTIPFORM.tooltipForm) ? $tooltipForm : new $tooltipForm(jQuery(_element), _elementOptions);
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
	shcJSL.gizmos.tooltipForm = function(element) {
        shcJSL.get(element).tooltipForm(element);
    }
}
