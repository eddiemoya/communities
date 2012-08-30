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
    var _thisToolip = this;

    /**
     * Set all parent infomartion
     */
    _thisToolip.actedObj = {
        element: {},
        height: 0,
        offest: {},
        position: {},
        width: 0
    };

    _thisToolip.tooltip = {
        arrow: {
            element: null,
            height: 0
        },
        element: null,
        height: 0,
        offset: 0,
        position: 0
    }; //keeps the tooltip base object

    _thisToolip.options = {
        arrowPosition: 'left',
        closer: {
            initialized: true
        },
        displayData: null,
        events: {
            click: {
                callBack: _thisToolip.click,
                active: false,
                name: 'click',
                preventDefault: false
            },
            mouseover: {
                callBack: _thisToolip.mouseover,
                active: false,
                name: 'mouseover',
                preventDefault: false
            },
            mouseout: {
                callBack: _thisToolip.mouseout,
                active: false,
                name: 'mouseout',
                preventDefault: false
            },
        },
        position: {
            bottom: 0,
            left: 0,
            right: 0,
            top: 0
        }
    };

    _thisToolip.init = function(element, options) {

        try {
            _thisToolip.actedObj.element = (typeof(element) === 'object') ? element : null;
        } catch(e) {
            console.log('The element is not a jQuery Object! Bailing!');

            return false;
        }

        /**
         * Take in options based in on creation and shove onto existing _thisToolip.options;
         */
        _thisToolip.options = jQuery.extend(true, _thisToolip.options, options);

        _thisToolip.addListener(options);

        _thisToolip.setTooltip();
    };

    _thisToolip.addListener = function(options) {
        var callback = function() {};
        var name = '';

        for(var i in _thisToolip.options.events) {
            if(_thisToolip.options.events[i].active === true) {
                jQuery(_thisToolip.actedObj.element).bind(_thisToolip.options.events[i].name, _thisToolip.options.events[i].callBack);
            }
        }
    };

    _thisToolip.click = function(event) {
        event.preventDefault();

        _thisToolip._openTooltip();

        _thisToolip._preventDefault(_thisToolip.options.events.click.preventDefault, event);
    };

    _thisToolip.mouseover = function(event) {
        _thisToolip._openTooltip();

        _thisToolip._preventDefault(_thisToolip.options.events.preventDefault, event);
    };

    _thisToolip.mouseout = function(event) {
        _thisToolip._closeTooltip();

        _thisToolip._preventDefault(_thisToolip.options.events.preventDefault, event);
    };

    _thisToolip.setDisplayData = function() {
        if(typeof(_thisToolip.options.displayData) !== 'function') {
            _thisToolip.options.displayData = jQuery('#default').length() > 0 ? jQuery('#default') : null;
        } else {
            _thisToolip.options.displayData = _thisToolip.options.displayData();
        }
    };

    /**
     *
     */
    _thisToolip.getOffset = function(obj, side) {
        return obj.offset[side];
    };

    /**
     *
     */
    _thisToolip.setOffset = function(obj) {
        obj.offset = jQuery(obj.element).offset();
    };

    /**
     *
     */
    _thisToolip.getPosition = function(obj, side) {
        return obj.position[side];
    };

    /**
     *
     */
    _thisToolip.setPosition = function(obj) {
        obj.position = jQuery(obj.element).position();
    };

    _thisToolip.getHeight = function(obj) {
        return obj.height;
    };

    _thisToolip.setHeight = function(obj) {
        obj.height = jQuery(obj.element).outerHeight();
    };

    _thisToolip.getWidth = function(obj) {
        return obj.width;
    };

    _thisToolip.setWidth = function(obj) {
        obj.width = jQuery(obj.element).innerWidth();
    };

    _thisToolip.setTooltip = function() {
        _thisToolip.tooltip.element = jQuery('#tooltip').length > 0 ? jQuery('#tooltip') : null;

        _thisToolip.tooltip.arrow.element = jQuery(_thisToolip.tooltip.element).children('.arrow').length > 0 ? jQuery(_thisToolip.tooltip.element).children('.arrow') : null;
    };

    _thisToolip._positionArrow = function() {
        var arrow = _thisToolip.tooltip.arrow.element;
        var tooltip = _thisToolip.tooltip.element;
        var tooltipHeight = _thisToolip.tooltip.height;
        var tooltipWidth = _thisToolip.tooltip.width;

        switch(_thisToolip.options.arrowPosition) {
            case 'left':
                var arrowLeft = jQuery(arrow).children('.left');

                var arrowLeftHeight = arrowLeft.outerHeight();

//                arrow.css('top', (tooltipHeight - arrowLeftHeight) / 2);
                arrow.css('top', 15);
                arrow.css('left', '-10');

                break;
            case 'top':
                var arrowTop = jQuery(arrow).children('.top');

                var arrowTopHeight = (arrowTop.outerHeight() * -1) + .5;
                var arrowTopWidth = arrowTop.outerWidth();

                arrow.css('top', arrowTopHeight);
                arrow.css('left', (tooltipWidth - arrowTopWidth) / 2);

                break;
        }
    };

    _thisToolip._openTooltip = function() {
        if(tooltipOpen === true) {
            _thisToolip.tooltip.element.hide();
        }

        var leftPosition = 0;
        var topPosition = 0;

        //var data = jQuery(_thisToolip.options.displayData).clone().removeClass('hide');

        //jQuery(_thisToolip.tooltip.element).children('.middle').html(data);

        jQuery(_thisToolip.tooltip.element).children('.middle').append(_thisToolip.options.displayData);

        /**
         * The position/dimensional info needs to be set here, in case elements with tooltips are hidden, etc.
         */
        _thisToolip.setHeight(_thisToolip.actedObj);
        _thisToolip.setOffset(_thisToolip.actedObj);
        _thisToolip.setPosition(_thisToolip.actedObj);
        _thisToolip.setWidth(_thisToolip.actedObj);

        _thisToolip.setHeight(_thisToolip.tooltip);
        _thisToolip.setOffset(_thisToolip.tooltip);
        _thisToolip.setPosition(_thisToolip.tooltip);
        _thisToolip.setWidth(_thisToolip.tooltip);

        switch(_thisToolip.options.arrowPosition) {
            case 'left':
                if(_thisToolip._switchSides()) {
                    _thisToolip.options.arrowPosition = 'left';

                    _thisToolip._openTooltip();

                    return false;
                }

                leftPosition = _thisToolip.getPosition(_thisToolip.actedObj, 'left') + _thisToolip.getWidth(_thisToolip.actedObj) + _thisToolip.options.position.left + 12;
                topPosition = _thisToolip.getPosition(_thisToolip.actedObj, 'top') + _thisToolip.options.position.top;

                if(_thisToolip.getHeight(_thisToolip.tooltip) < _thisToolip.getHeight(_thisToolip.actedObj)) {
                    topPosition += (_thisToolip.getHeight(_thisToolip.actedObj) / 2) - (_thisToolip.getHeight(_thisToolip.tooltip) / 2);
                }

                break;
            case 'top':

                leftPosition = _thisToolip.getPosition(_thisToolip.actedObj, 'left') + _thisToolip.getWidth(_thisToolip.actedObj) + _thisToolip.options.position.left + 12;
                topPosition = _thisToolip.getPosition(_thisToolip.actedObj, 'top') + _thisToolip.options.position.top;

                if(_thisToolip.getHeight(_thisToolip.tooltip) < _thisToolip.getHeight(_thisToolip.actedObj)) {
                    topPosition += (_thisToolip.getHeight(_thisToolip.actedObj) / 2) - (_thisToolip.getHeight(_thisToolip.tooltip) / 2);
                }

            default:
                break;
        }

        _thisToolip.tooltip.element
                .css('left', leftPosition)
                .css('top', topPosition)
                .show();

        _thisToolip._positionArrow();

        tooltipOpen = true;
    }

    _thisToolip._closeTooltip = function() {
        jQuery(_thisToolip.tooltip.element).fadeOut('slow');
    };

    /**
     * Decide if the tooltip should be on the opposite side of what it is defaulted as (used when displaying tooltip would go off screen)
     */
    _thisToolip._switchSides = function() {

    };

    /**
     * Decides if default functionality of element's listener should happen
     *
     * @param prevent boolean
     * @private
     *
     * @void
     */
    _thisToolip._preventDefault = function(prevent, event) {
        if(prevent === true) {
            event.preventDefault();
        }
    };

    _thisToolip.init(element, options);
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