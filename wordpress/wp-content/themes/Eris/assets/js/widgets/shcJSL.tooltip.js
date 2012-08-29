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
    var _this = this;

    /**
     * Set all parent infomartion
     */
    _this.actedObj = {
        element: {},
        height: 0,
        offest: {},
        position: {},
        width: 0
    };

    _this.tooltip = {
        arrow: {
            element: null,
            height: 0
        },
        element: null,
        height: 0,
        offset: 0,
        position: 0
    }; //keeps the tooltip base object

    _this.options = {
        arrowPosition: 'right',
        closer: {
            initialized: true
        },
        displayData: null,
        events: {
            click: {
                callBack: _this.click,
                active: false,
                name: 'click',
                preventDefault: false
            },
            mouseover: {
                callBack: _this.mouseover,
                active: false,
                name: 'mouseover',
                preventDefault: false
            },
            mouseout: {
                callBack: _this.mouseout,
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

        _this.addListener(options);

        _this.setTooltip();
    };

    _this.addListener = function(options) {
        var callback = function() {};
        var name = '';

        for(var i in _this.options.events) {
            if(_this.options.events[i].active === true) {
                jQuery(_this.actedObj.element).bind(_this.options.events[i].name, _this.options.events[i].callBack);
            }
        }
    };

    _this.click = function(event) {
        event.preventDefault();

        _this._openTooltip();

        _this._preventDefault(_this.options.events.click.preventDefault, event);
    };

    _this.mouseover = function(event) {
        _this._openTooltip();

        _this._preventDefault(_this.options.events.preventDefault, event);
    };

    _this.mouseout = function(event) {
        _this._closeTooltip();

        _this._preventDefault(_this.options.events.preventDefault, event);
    };

    /**
     *
     */
    _this.getOffset = function(obj, side) {
        return obj.offset[side];
    };

    /**
     *
     */
    _this.setOffset = function(obj) {
        obj.offset = jQuery(obj.element).offset();
    };

    /**
     *
     */
    _this.getPosition = function(obj, side) {
        return obj.position[side];
    };

    /**
     *
     */
    _this.setPosition = function(obj) {
        obj.position = jQuery(obj.element).position();
    };

    _this.getHeight = function(obj) {
        return obj.height;
    };

    _this.setHeight = function(obj) {
        obj.height = jQuery(obj.element).outerHeight();
    };

    _this.getWidth = function(obj) {
        return obj.width;
    };

    _this.setWidth = function(obj) {
        obj.width = jQuery(obj.element).innerWidth();
    };

    _this.setTooltip = function() {
        _this.tooltip.element = jQuery('#tooltip').length > 0 ? jQuery('#tooltip') : null;

        _this.tooltip.arrow.element = jQuery(_this.tooltip.element).children('.arrow').length > 0 ? jQuery(_this.tooltip.element).children('.arrow') : null;
    };

    _this._positionArrow = function() {
        var tooltip = _this.tooltip.element;
        var arrow = _this.tooltip.arrow.element;
        var arrowLeft = jQuery(arrow).children('.arrowLeft');

        var tooltipHeight = _this.tooltip.height;
        var arrowLeftHeight = arrowLeft.outerHeight();

        arrow.css('top', (tooltipHeight - arrowLeftHeight) / 2);
    };

    _this._openTooltip = function() {
        if(tooltipOpen === true) {
            _this.tooltip.element.hide();
        }

        var leftPosition = 0;
        var topPosition = 0;

        var data = jQuery('#' + _this.options.displayData).clone().removeClass('hide');

        jQuery(_this.tooltip.element).children('.middle').html(data);

        /**
         * The position/dimensional info needs to be set here, in case elements with tooltips are hidden, etc.
         */
        _this.setHeight(_this.actedObj);
        _this.setOffset(_this.actedObj);
        _this.setPosition(_this.actedObj);
        _this.setWidth(_this.actedObj);

        _this.setHeight(_this.tooltip);
        _this.setOffset(_this.tooltip);
        _this.setPosition(_this.tooltip);
        _this.setWidth(_this.tooltip);

        switch(_this.options.arrowPosition) {
            case 'right':
                if(_this._switchSides()) {
                    _this.options.arrowPosition = 'left';

                    _this._openTooltip();

                    return false;
                }

                leftPosition = _this.getPosition(_this.actedObj, 'left') + _this.getWidth(_this.actedObj) + _this.options.position.left + 12;
                topPosition = _this.getPosition(_this.actedObj, 'top') + _this.options.position.top;

                if(_this.getHeight(_this.tooltip) < _this.getHeight(_this.actedObj)) {
                    topPosition += (_this.getHeight(_this.actedObj) / 2) - (_this.getHeight(_this.tooltip) / 2);
                }

                break;
            default:
                break;
        }

        _this.tooltip.element
                .css('left', leftPosition)
                .css('top', topPosition)
                .show();

        _this._positionArrow();

        tooltipOpen = true;
    }

    _this._closeTooltip = function() {
        jQuery(_this.tooltip.element).fadeOut('slow');
    };

    /**
     * Decide if the tooltip should be on the opposite side of what it is defaulted as (used when displaying tooltip would go off screen)
     */
    _this._switchSides = function() {

    };

    /**
     * Decides if default functionality of element's listener should happen
     *
     * @param prevent boolean
     * @private
     *
     * @void
     */
    _this._preventDefault = function(prevent, event) {
        if(prevent === true) {
            event.preventDefault();
        }
    };

    _this.init(element, options);
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