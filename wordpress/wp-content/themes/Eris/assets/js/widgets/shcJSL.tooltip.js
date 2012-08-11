/**
* Moodle: Responsive Modal Window
* @author Tim Steele
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
TOOLTIP = {}
/**
 *
 * @type {Function}
 * @var element (Object) tooltip base element on page
 * @var options (Object) options regarding the construction of the tooltip
 */
TOOLTIP.tooltip = $tooltip = function(element, options) {
    var _this = this;

    _this.element = {};
    _this.position = {};
    _this.options = {
        closer: {
            initialized: true
        },
        events: {
            click: {
                callback: _this._click(),
                name: 'click',
                preventDefault: false
            },
            mouseover: {
                callback: _this._mouseOver,
                name: 'mouseover',
                preventDefault: false
            },
            mouseout: {
                callback: _this._mouseOut(),
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
            _this.element = (typeof(element) === 'jQuery') ? element : null;
        } catch(e) {
            console.log('The element is not a jQuery Object! Bailing!');

            return false;
        }

        //_this.options = //array merge options to defaults
    };

    _this.addListener = function() {
        var callback = function() {};
        var name = '';

        for(var i in _this.options.events) {
            callback = _this.options.events[0].callback();
            name = _this.options.events[0].name;

            jQuery(_this.element).on(name, callback);
        }
    };

    _this.click = function() {


        _this._preventDefault(_this.options.events.preventDefault);
    }

    _this._mouseOver = function() {
        _this._preventDefault(_this.options.events.preventDefault);
    }

    _this._mouseOut = function() {


        _this._preventDefault(_this.options.events.preventDefault);
    }

    /**
     *
     */
    _this.getPosition = function() {
        var position =

        _this._preventDefault(_this.options.events.preventDefault);
    };

    _this._getHeight = function() {

    };

    _this._getWidth = function() {

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
    }

    _this.init(element, options);
};

/**
 * Controller -
 * @param target
 * @param options = this instance
 */
shcJSL.methods.tooltip = function(element, options) {


    var elementOptions = ($(element).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(element).attr("shc:gizmo:options") + ')')).tooltip)?(eval('(' + $(element).attr("shc:gizmo:options") + ')')).tooltip:{}):{};

    console.log(elementOptions);

    var tooltip = ($tooltip instanceof TOOLTIP.tooltip) ? $tooltip : new $tooltip();

    $(element).bind(elementOptions.events, function() {
        var method = elementOptions.events;

        tooltip[method]();
    });
};

//Initialize a tooltip

/**
 * Instantiater
 *
 * @access Public
 * @author Tim Steele
 * @since 1.0
 */

shcJSL.gizmos.bulletin['shcJSL.tooltip.js'] = true;
if (shcJSL && shcJSL.gizmos)  {
	shcJSL.gizmos.tooltip = function(element, options) {
        shcJSL.get(element).tooltip();
	};
}