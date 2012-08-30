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
    var _thisTooltipForm = this;

    /**
     * Set all parent infomartion
     */
    _thisTooltipForm.actedObj = {
        element: {},
        height: 0,
        offest: {},
        position: {},
        width: 0
    };

    _thisTooltipForm.options = {
        events: {
            click: {
                callBack: _thisTooltipForm._openTooltip,
                active: true,
                name: 'click',
                preventDefault: false
            },
        },
        form: {
            attributes: {
                action: ajaxurl + '?',
            },
            events: null,
            class: '',
            elements:  [
                {
                    attributes: {
                        rows: 5,
                        cols: 4,
                        name: 'comment'
                    },
                    class: 'flagField',
                    element: 'textarea',
                    events: {
                        click: {
                            callBack: function(event) {
                            },
                            active: false,
                            name: 'click',
                            preventDefault: false
                        },
                    }
                },
                {
                    attributes: {
                        type: 'submit',
                        value: 'Submit'
                    },
                    class: '',
                    element: 'input',
                    events: {
                        click: {
                            callBack: function(event) {
                            },
                            active: true,
                            name: 'click',
                            preventDefault: false
                        },
                    }
                },
                {
                    attributes: {
                        type: 'reset',
                        value: 'Cancel'
                    },
                    class: '',
                    element: 'input',
                    events: {
                        click: {
                            callBack: _thisTooltipForm._closeTooltip,
                            active: true,
                            name: 'click',
                            preventDefault: false
                        },
                    }
                }
            ]
        }
    };

    _thisTooltipForm.tooltip = {};

    _thisTooltipForm.addListeners = function(element, events) {
        for(var i in events) {
            if(events[i].active === true) {
                if(typeof events[i].callBack !== 'undefined') {
                    element.bind(events[i].name, events[i].callBack);
                } else {
                    console.log('not defined')
                    element.bind(events[i].name, _thisTooltipForm[events[i].name]);
                }
            } else {
            }
        }
    };

    _thisTooltipForm.click = function() {
        _thisTooltipForm._openTooltip();
    };

    _thisTooltipForm._openTooltip = function() {
        _thisTooltipForm.tooltip._openTooltip();
    };

    _thisTooltipForm._renderForm = function() {
        var childrenHtml = [];

        var children = _thisTooltipForm.options.form.elements;
        var parentAttributes = _thisTooltipForm.options.form.attributes;

        var parentHtml = shcJSL.createNewElement("form", _thisTooltipForm.options.form.class, parentAttributes);

        if(typeof(children) !== 'undefined' && children.length > 0) {
            childrenHtml = _thisTooltipForm._setChildren(children);

            return shcJSL.addChildren(parentHtml, childrenHtml);
        }

        return parentHtml;
    };

    _thisTooltipForm._setChildren = function(children) {
        var elems = [];

        for(var i = 0; i < children.length; i++) {
            elems[i] = shcJSL.createNewElement(children[i].element, children[i].class, children[i].attributes);

            _thisTooltipForm.addListeners(jQuery(elems[i]), children[i].events)
        }

        return elems;
    };

    _thisTooltipForm.resetOptions = function(options) {
        _thisTooltipForm.options = jQuery.extend(true, _thisTooltipForm.options, options);
    };

    _thisTooltipForm._closeTooltip = function() {
        _thisTooltipForm.tooltip._closeTooltip();
    }

    _thisTooltipForm.init = function(element, options) {
        try {
            _thisTooltipForm.actedObj.element = (typeof(element) === 'object') ? element : null;
        } catch(e) {
            console.log('The element is not a jQuery Object! Bailing!');

            return false;
        }

        /**
         * Take in options based in on creation and shove onto existing _thisTooltipForm.options;
         */
        _thisTooltipForm.options = jQuery.extend(true, _thisTooltipForm.options, options);

        _thisTooltipForm.addListeners(jQuery(_thisTooltipForm.actedObj.element), _thisTooltipForm.options.events);

        var ttOptions = {
            arrowPosition: 'top',
            displayData: _thisTooltipForm._renderForm()
        };

        _thisTooltipForm.tooltip = new $tooltip(jQuery(element), ttOptions);
    };

    _thisTooltipForm.init(element, options);
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
