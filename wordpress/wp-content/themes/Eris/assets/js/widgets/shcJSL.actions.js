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
                data = eval(data);

                if(data === 'activated') {
                    jQuery(_this.action.element).addClass('active');
                } else if(data === 'deactivated') {
                    jQuery(_this.action.element).removeClass('active');
                } else if(data === 'activated-out') {
                    _this._updateCookie();

                    jQuery(_this.action.element).addClass('active');
                } else if(data === 'deactivated-out') {
                    _this._updateCookie();

                    jQuery(_this.action.element).removeClass('active');
                }

                _this._resetActionTotal(data);
            }
        );
    };

    _this._resetActionTotal = function(data) {
        var action = _this.options.post.name;
        var currentTotal = '';

        if(action == 'follow') {
            var text = jQuery(_this.action.element).html() === 'following' ? 'follow' : 'following';

            jQuery(_this.action.element).html(text);
        } else {
            var curId = jQuery(_this.action.element).attr('id');
            var curValue = jQuery('label[for="' + curId + '"]').html();

            curValue = curValue.replace(/[^0-9]/g, '');

            if(data === 'activated' || data === 'activated-out') {
                currentTotal = parseInt(curValue) + 1;
            } else if(data === 'deactivated' || data === 'deactivated-out') {
                currentTotal = parseInt(curValue) - 1;
            }

            jQuery('label[for="' + curId + '"]').html("(" + currentTotal + ')');
        }
    };

    _this._updateCookie = function() {
        var existingCookies = [];
        var jsonString = '{"actions": [';

        existingCookies = eval('(' + shcJSL.cookies('actions').serve('value') + ')');
        existingCookies = existingCookies.actions;

        if(typeof(existingCookies) !== 'undefined') {
            for(var i = 0; i < existingCookies.length; i++) {
                jsonString += '{"id": "' + existingCookies[i].id + '", "name": "' + existingCookies[i].name + '", "sub_type": "' + existingCookies[i].sub_type + '", "type": "' + existingCookies[i].type + '"}, ';

                console.log(jsonString);
            }
        }

        jsonString += '{"id": "' + _this.options.post.id + '", "name": "' + _this.options.post.name + '", "sub_type": "' + _this.options.post.sub_type + '", "type": "' + _this.options.post.type + '"}]}';
console.log('final: ' + jsonString);
        shcJSL.cookies("actions").bake({value: jsonString, expiration: '1y'});
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
