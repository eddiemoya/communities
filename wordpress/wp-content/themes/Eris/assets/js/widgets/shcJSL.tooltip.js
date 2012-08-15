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

TOOLTIP = {}
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
        element: null,
        height: 0,
        offset: 0,
        position: 0
    }; //keeps the tooltip base object

    _this.options = {
        arrowPosition: 'left',
        closer: {
            initialized: true
        },
        events: {
            click: {
//                callback: _this.click(),
                name: 'click',
                preventDefault: false
            },
            mouseover: {
//                callback: _this.mouseOver,
                name: 'mouseover',
                preventDefault: false
            },
            mouseout: {
//                callback: _this.mouseOut(),
                name: 'mouseout',
                preventDefault: false
            },
        },
        padding: {
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
        //_this.options = //array merge options to defaults

        _this.setTooltip();

        _this.setOffset(_this.actedObj);
        _this.setOffset(_this.tooltip);

        _this.setPosition(_this.actedObj);
        _this.setPosition(_this.tooltip);

        _this.setHeight(_this.actedObj);
        _this.setHeight(_this.tooltip);

        _this.setWidth(_this.actedObj);
        _this.setWidth(_this.tooltip);
    };

    _this.addListener = function() {
        var callback = function() {};
        var name = '';

        for(var i in _this.options.events) {
            callback = _this.options.events[0].callback();
            name = _this.options.events[0].name;

            jQuery(_this.actedObj.element).on(name, callback);
        }
    };

    _this.click = function() {
        _this._openTooltip();

        _this._preventDefault(_this.options.events.click.preventDefault);
    };

    _this.mouseover = function() {_this._openTooltip();

        _this._preventDefault(_this.options.events.preventDefault);
    };

    _this.mouseout = function() {
        _this._closeTooltip();

        _this._preventDefault(_this.options.events.preventDefault);
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
        obj.height = jQuery(obj.element).height();
    };

    _this.getWidth = function(obj) {
        return obj.width;
    };

    _this.setWidth = function(obj) {
        obj.width = jQuery(obj.element).innerWidth();
    };

    _this.setTooltip = function() {
        _this.tooltip.element = jQuery('#tooltip').length > 0 ? jQuery('#tooltip') : null;
    };

    _this._openTooltip = function() {
       if(tooltipOpen === true) {
           _this.tooltip.element.hide();
       }

       var leftPosition = 0;
       var topPosition = 0;

       switch(_this.options.arrowPosition) {
           case 'left':
               leftPosition = _this.getPosition(_this.actedObj, 'left') + _this.getWidth(_this.actedObj) + _this.options.padding.left + 12;
               topPosition = _this.getPosition(_this.actedObj, 'top') + ((_this.getHeight(_this.actedObj) / 2) - (_this.getHeight(_this.tooltip) / 2)) + _this.options.padding.top;

               console.log((_this.actedObj));
               console.log("_this.getWidth(_this.actedObj): " +  _this.getWidth(_this.actedObj));
               console.log("_this.getPosition(_this.actedObj, 'left'): " + _this.getPosition(_this.actedObj, 'left'));
               console.log("_this.getPosition(_this.actedObj, 'top'): " + _this.getPosition(_this.actedObj, 'top'));
               console.log("_this.getOffset(_this.actedObj, 'left'): " + _this.getOffset(_this.actedObj, 'left'));

               _this.tooltip.element
                       .css('left', leftPosition)
                       .css('top', topPosition)
                       .show();

               break;
           default:
               break;
       }

       tooltipOpen = true;
    }

    _this._closeTooltip = function() {
        jQuery(_this.tooltip.element).fadeOut('slow');
    };

    /**
     * Decides if default functionality of element's listener should happen
     *
     * @param prevent boolean
     * @private
     *
     * @void
     */
    _this._preventDefault = function(prevent) {
        if(prevent === true) {
            jQuery(_this.element).preventDefault();
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
    var _elementOptions = ($(_element).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(_element).attr("shc:gizmo:options") + ')')).tooltip)?(eval('(' + $(_element).attr("shc:gizmo:options") + ')')).tooltip:{}):{};

    var tooltip = ($tooltip instanceof TOOLTIP.tooltip) ? $tooltip : new $tooltip(jQuery(_element));

    var i, method;

    for (i = 0; i < _elementOptions.events.length; i++) {
        method = _elementOptions.events[i];

        jQuery(_element).bind(method, function() {
            console.log(tooltip);
            tooltip[method]();
        });
    }
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