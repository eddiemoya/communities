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
                callBack: _openTooltip,
                active: true,
                name: 'click',
                preventDefault: true
            }
        },
        form: {
            attributes: {
                action: ajaxurl + '?',
                id: 'default-form',
                method: 'post'
            },
            events: null,
            className: '',
            elements:  [
                {
                    attributes: {
                        rows: 5,
                        cols: 4,
                        name: 'comment'
                    },
                    className: 'flagField',
                    element: 'textarea',
                    events: {
                        click: {
                            active: false,
                            name: 'click',
                            preventDefault: true
                        },
                    }
                },
                {
                    attributes: {
                        type: 'submit',
                        value: 'Submit'
                    },
                    className: '',
                    element: 'input',
                    events: {
                        click: {
                            active: true,
                            callBack: _postForm,
                            name: 'click',
                            preventDefault: true
                        },
                    }
                },
                {
                    attributes: {
                        type: 'reset',
                        value: 'Cancel'
                    },
                    className: '',
                    element: 'input',
                    events: {
                        click: {
                            callBack: _closeTooltip,
                            active: true,
                            name: 'click',
                            preventDefault: false
                        },
                    }
                }
            ],
            isAjax: false
        }
    };

    _thisTooltipForm.tooltip = {};

    _thisTooltipForm.addListeners = function(element, events) {
        for(var i in events) {
            if(events[i].active === true) {
                if(typeof events[i].callBack !== 'undefined') {
                    element.bind(events[i].name, events[i].callBack);
                } else {
                    element.bind(events[i].name, _thisTooltipForm[events[i].name]);
                }
            }
        }
    };

    /**
     * Private functions
     */

    function _openTooltip (event) {
        _thisTooltipForm.tooltip._openTooltip();

        shcJSL.gizmos.activate(null, _thisTooltipForm.tooltip.tooltip.element);

        _thisTooltipForm._preventDefault(true, event);
    };

    function _closeTooltip () {
        _thisTooltipForm.tooltip._closeTooltip();

        _thisTooltipForm._preventDefault(true, event);
    };

    function _postForm(event) {
        var data = {};
        var form = jQuery('#' + _thisTooltipForm.options.form.attributes.id);

        form.children().each(function(i) {
            data[jQuery(this).attr('name')] = jQuery(this).val();
        });

        if(_thisTooltipForm.options.form.isAjax === true) {
            jQuery.post(
                _thisTooltipForm.options.form.attributes.action,
                data,
                function(data) {
                    jQuery(_thisTooltipForm.tooltip.tooltip.element).children('.middle').children('form').children('textarea').val('');

                    _thisTooltipForm._closeTooltip();
                }
            );
        }

        _thisTooltipForm._preventDefault(true, event);
    }

    /**
     * Public functions
     */

    _thisTooltipForm.click = function() {
        _thisTooltipForm._openTooltip();
    };

    _thisTooltipForm._renderForm = function() {
        var childrenHtml = [];

        var children = _thisTooltipForm.options.form.elements;
        var parentAttributes = _thisTooltipForm.options.form.attributes;

        var parentHtml = shcJSL.createNewElement("form", _thisTooltipForm.options.form.className, parentAttributes);

        if(typeof(children) !== 'undefined' && children.length > 0) {
            childrenHtml = _thisTooltipForm._setChildren(children);

            return shcJSL.addChildren(parentHtml, childrenHtml);
        }

        return parentHtml;
    };

    _thisTooltipForm._setChildren = function(children) {
        var childAttributes = '';
        var elems = [];

        for(var i = 0; i < children.length; i++) {
            elems[i] = shcJSL.createNewElement(children[i].element, children[i].className, children[i].attributes);

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

    /**
     * Decides if default functionality of element's listener should happen
     *
     * @param prevent boolean
     * @private
     *
     * @void
     */
    _thisTooltipForm._preventDefault = function(prevent, event) {
        if(prevent === true) {
            event.preventDefault();
        }
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
