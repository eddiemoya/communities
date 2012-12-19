/**
* Tooltip
* @author Sebastian Frohm
*	
* @package shcJSL
* @require jQuery 1.7.2
* 
* TABLE OF CONTENTS
* 
* @version [1.0: 2012-07-24]
* - Initial script release
*/

/**
 * TOOLTIP, TOOLTIP.tooltip
 *
 * The Tooltip class
 *
 * @var TOOLTIP (Object) Tooltip object for Tooltip specific methods, properties
 * @var MOODLE.modal (Object) The actual tooltip
 * @var $Moodle (Object) a variation on the Tooltip.
 */
var tooltipOpen = false;

TOOLTIP = {};
/**
 *
 * @type {Function}
 * @var element (Object) tooltip base element on page
 * @var options (Object) options regarding the construction of the tooltip
 */
TOOLTIP.tooltip = $tooltip = function(element, options) {
    var _thisTooltip = this;

    /**
     * Set all parent infomartion
     */
    _thisTooltip.actedObj = {
        element: {},
        height: 0,
        offset: {},
        position: {},
        width: 0
    };

    _thisTooltip.tooltip = {
        arrow: {
            element: null,
            height: 0
        },
        element: null,
        height: 0,
        offset: 0,
        position: 0,
        width: 0
    }; //keeps the tooltip base object

    _thisTooltip.options = {
        arrowPosition: 'left',
        closer: {
            initialized: true
        },
        customEvent: null,
        customListener: {
            callBack: function() {},
            name: ''
        },
        displayData: {
            element: null,
            callBack: {
                click: {
                    active: false,
                    name: 'click',
                    method: function() {}
                }
            }
        },
        events: {
            click: {
                callBack: _click,
                active: true,
                name: 'click',
                preventDefault: true
            },
            mouseover: {
                callBack: _mouseover,
                active: false,
                name: 'mouseover',
                preventDefault: false
            },
            mouseout: {
                callBack: _mouseout,
                active: false,
                name: 'mouseout',
                preventDefault: false
            },
            blur: {
                callBack: _blur,
                active: false,
                name: 'blur',
                preventDefault: false
            },
            focus: {
                callBack: _focus,
                active: false,
                name: 'focus',
                preventDefault: false
            }
        },
        position: {
            bottom: 0,
            left: 0,
            right: 0,
            top: 0
        },
        tooltipWidth: 250
    };

    _thisTooltip.init = function(element, options) {
        _thisTooltip.actedObj.element = null;

        try {
            _thisTooltip.actedObj.element = (typeof(element) === 'object') ? jQuery(element) : null;
        } catch(e) {
            console.log('The element is not a jQuery Object! Bailing!');

            return false;
        }

        /**
         * Take in options based in on creation and shove onto existing _thisTooltip.options;
         */
        _thisTooltip.options = jQuery.extend(true, _thisTooltip.options, options);

        _thisTooltip.addListener();

        _thisTooltip.setDisplayData();

        _thisTooltip.setTooltip();

        if(typeof _thisTooltip.options.customListener !== 'undefined') {
            jQuery(document).on(_thisTooltip.options.customListener.name, function(event) {
                _thisTooltip.options.customListener.callBack(_thisTooltip.actedObj.element);
            });
        }
    };

    _thisTooltip.addListener = function() {
        for(var i in _thisTooltip.options.events) {
            if(_thisTooltip.options.events[i].active === true) {
                jQuery(_thisTooltip.actedObj.element).bind(_thisTooltip.options.events[i].name, _thisTooltip.options.events[i].callBack);
            }
        }
    };

    function _click(event) {
        _thisTooltip._openTooltip();

        _thisTooltip._preventDefault(_thisTooltip.options.events.click.preventDefault, event);
    };

    function _mouseover(event) {
        _thisTooltip._openTooltip();

        _thisTooltip._preventDefault(_thisTooltip.options.events.preventDefault, event);
    };

    function _mouseout(event) {
        _thisTooltip._closeTooltip();

        _thisTooltip._preventDefault(_thisTooltip.options.events.preventDefault, event);
    };

    function _focus(event) {
        _thisTooltip._openTooltip();

        _thisTooltip._preventDefault(_thisTooltip.options.events.preventDefault, event);
    };

    function _blur(event) {
        _thisTooltip._closeTooltip();

        _thisTooltip._preventDefault(_thisTooltip.options.events.preventDefault, event);
    };

    _thisTooltip.setDisplayData = function() {
        if(typeof _thisTooltip.options.displayData.element === 'string') {
            _thisTooltip.options.displayData.element = jQuery('#' + _thisTooltip.options.displayData.element);
        } else {
            _thisTooltip.options.displayData.element = _thisTooltip.options.displayData.element();
        }

        _thisTooltip.setDisplayDataCallback();
    };

    _thisTooltip.setDisplayDataCallback = function() {
        var callBacks = _thisTooltip.options.displayData.callBack;

        for(var i in callBacks) {
            if(callBacks[i].active === true) {
                _thisTooltip.options.displayData.element.bind(callBacks[i].name, callBacks[i].method);
            }
        }
    };

    /**
     *
     */
    _thisTooltip.getOffset = function(obj, side) {
        return obj.offset[side];
    };

    /**
     *
     */
    _thisTooltip.setOffset = function(obj) {
        obj.offset = obj.element.offset();
    };

    /**
     *
     */
    _thisTooltip.getPosition = function(obj, side) {
        return obj.position[side];
    };

    /**
     *
     */
    _thisTooltip.setPosition = function(obj) {
        obj.position = obj.element.position();
    };

    _thisTooltip.getHeight = function(obj) {
        return obj.height;
    };

    _thisTooltip.setHeight = function(obj) {
        obj.height = obj.element.outerHeight(true);
    };

    _thisTooltip.getWidth = function(obj) {
        return obj.width;
    };

    _thisTooltip.setWidth = function(obj) {
        obj.width = obj.element.outerWidth(true);
    };

    _thisTooltip.setTooltip = function() {
        _thisTooltip.tooltip.element = jQuery('.tooltip').length > 0 ? jQuery('.tooltip') : null;

        _thisTooltip.tooltip.arrow.element = jQuery(_thisTooltip.tooltip.element).children('.arrow').length > 0 ? jQuery(_thisTooltip.tooltip.element).children('.arrow') : null;
    };

    _thisTooltip._positionArrow = function() {
        var arrow = _thisTooltip.tooltip.arrow.element;
        var tooltip = _thisTooltip.tooltip.element;
        var tooltipHeight = _thisTooltip.tooltip.height;
        var tooltipWidth = _thisTooltip.tooltip.width;

        switch(_thisTooltip.options.arrowPosition) {
            case 'left':
                jQuery(arrow).children().first('div').removeClass();
                jQuery(arrow).children().first('div').addClass('left');

                var arrowLeft = jQuery(arrow).children('.left');

                var arrowLeftHeight = arrowLeft.outerHeight();

//                arrow.css('top', (tooltipHeight - arrowLeftHeight) / 2);
                arrow.css('top', 7);
                arrow.css('left', '-10px');

                break;
            case 'top':
                jQuery(arrow).children().first('div').removeClass();
                jQuery(arrow).children().first('div').addClass('top');

                var arrowTop = jQuery(arrow).children('.top');

                var arrowTopHeight = (arrowTop.outerHeight() * -1) + .5;
                var arrowTopWidth = arrowTop.outerWidth();

                arrow.css('top', arrowTopHeight);
                arrow.css('left', ((tooltipWidth - arrowTopWidth) / 2) - 10);

                break;
        }
    };

    _thisTooltip._openTooltip = function() {
        if(tooltipOpen === true) {
            _thisTooltip.tooltip.element.hide();
        }

        var leftPosition = 0;
        var topPosition = 0;

        jQuery(_thisTooltip.tooltip.element).children('.middle').html(_thisTooltip.options.displayData.element);

        if(_thisTooltip.options.displayData.element.hasClass('hide')) {
            var children = jQuery(_thisTooltip.tooltip.element).children('.middle').children();

            children.each(function(i) {
                jQuery(children[i]).removeClass('hide');
            });
        }

        //set the width before calculating true with with arrow;
        _thisTooltip.tooltip.element
                .css('width', _thisTooltip.options.tooltipWidth + "px");

        /**
         * The position/dimensional info needs to be set here, in case elements with tooltips are hidden, etc.
         */
        _thisTooltip.setHeight(_thisTooltip.actedObj);
        _thisTooltip.setOffset(_thisTooltip.actedObj);
        _thisTooltip.setPosition(_thisTooltip.actedObj);
        _thisTooltip.setWidth(_thisTooltip.actedObj);

        _thisTooltip.setHeight(_thisTooltip.tooltip);
        _thisTooltip.setOffset(_thisTooltip.tooltip);
        _thisTooltip.setPosition(_thisTooltip.tooltip);
        _thisTooltip.setWidth(_thisTooltip.tooltip);

        switch(_thisTooltip.options.arrowPosition) {
            case 'left':
                if(_thisTooltip._switchSides()) {
                    _thisTooltip.options.arrowPosition = 'left';

                    _thisTooltip._openTooltip();

                    return false;
                }

                leftPosition = _thisTooltip.getOffset(_thisTooltip.actedObj, 'left') +
                                    _thisTooltip.getWidth(_thisTooltip.actedObj) +
                                        _thisTooltip.options.position.left + 15;
                topPosition = _thisTooltip.getOffset(_thisTooltip.actedObj, 'top') + _thisTooltip.options.position.top;

                if(_thisTooltip.getHeight(_thisTooltip.tooltip) < _thisTooltip.getHeight(_thisTooltip.actedObj)) {
                    topPosition += (_thisTooltip.getHeight(_thisTooltip.actedObj) / 2) - (_thisTooltip.getHeight(_thisTooltip.tooltip) / 2);
                }

                break;
            case 'top':
                leftPosition = _thisTooltip.getOffset(_thisTooltip.actedObj, 'left') -
                                ((_thisTooltip.getWidth(_thisTooltip.tooltip) / 2) - (_thisTooltip.getWidth(_thisTooltip.actedObj) / 2)) - 5;

                topPosition = _thisTooltip.getOffset(_thisTooltip.actedObj, 'top') +
                                _thisTooltip.getHeight(_thisTooltip.actedObj) +
                                    10; //needs to be changed to use actual height getter; doesn't work since it's all border

            default:
                break;
        }

        _thisTooltip.tooltip.element
                .css('left', leftPosition)
                .css('top', topPosition)
                .show();

        _thisTooltip._positionArrow();

        tooltipOpen = true;
    }

    _thisTooltip._closeTooltip = function() {
        jQuery(_thisTooltip.tooltip.element).hide();
    };

    /**
     * Decide if the tooltip should be on the opposite side of what it is defaulted as (used when displaying tooltip would go off screen)
     */
    _thisTooltip._switchSides = function() {

    };

    /**
     * Decides if default functionality of element's listener should happen
     *
     * @param prevent boolean
     * @private
     *
     * @void
     */
    _thisTooltip._preventDefault = function(prevent, event) {
        if(prevent === true) {
            event.preventDefault();
        }
    };

    _thisTooltip.init(element, options);
};

/**
 * Controller -
 * @param target
 * @param options = this instance
 */
shcJSL.methods.tooltip = function(_element, options) {
    var _elementOptions = ($(_element).attr("shc:gizmo:options") != undefined) ? (((eval('(' + $(_element).attr("shc:gizmo:options") + ')')).tooltip)?(eval('(' + $(_element).attr("shc:gizmo:options") + ')')).tooltip:{}):{};

    var tooltip = ($tooltip instanceof TOOLTIP.tooltip) ? $tooltip : new $tooltip(jQuery(_element), _elementOptions);
};

/**
 * Instantiater
 *
 * @access Public
 * @author Sebastia Frohm
 * @since 1.0
 */
shcJSL.gizmos.bulletin['shcJSL.tooltip.js'] = true;
if (shcJSL && shcJSL.gizmos)  {
	shcJSL.gizmos.tooltip = function(element, options) {
        shcJSL.get(element).tooltip(element, options);
	};
}
