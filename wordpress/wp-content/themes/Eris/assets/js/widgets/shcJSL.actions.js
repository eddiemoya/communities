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
var allActions = [];

ACTIONS = {};
/**
 *
 * @type {Function}
 * @var element (Object) tooltip base element on page
 * @var options (Object) options regarding the construction of the tooltip
 */
ACTIONS.actions = $actions = function(element, options) {
    var _this = this;

    _this.isVoteSwitch = false;

    _this.action = {
        element: null
    };

    _this.options = {
        element: null,
        post: {
            id: 0,
            name: '',
            sub_type: '',
            type: '',
            nli_reset: ''
        }
    };

    _this.originalOptions = {};

    _this.originalAction = {};

    _this.click = function() {
        _this._postAction(_this.action.element, _this.options);
    };

    _this.init = function(element, options) {
        _this.setActionObject(element);

        /**
         * Take in options based in on creation and shove onto existing _this.options;
         */
        _this.originalOptions = _this.options = jQuery.extend(true, _this.options, options);
    };

    _this.setActionObject = function(element) {
        _this.originalAction.element = _this.action.element = element;
    };

    _this._postAction = function(element, options) {
        var post = options.post;

        jQuery.post(
            ajaxurl + '?action=add_user_action',
            post,
            function(data) {
                data = eval(data);

                if(typeof _this.options.post.nli_reset !== 'undefined') {
                    _this.options.post.nli_reset = null;
                    delete _this.options.post.nli_reset;
                } else {
                    _this.options.post.nli_reset = _this.options.post.name;
                }

                _this._decideForDownUpSwitch(data);

                if(data === 'activated') {
                    jQuery(element).addClass('active');
                } else if(data === 'deactivated') {
                    jQuery(element).removeClass('active');
                } else if(data === 'activated-out') {
                    _this._updateCookie(data);

                    jQuery(element).addClass('active');
                } else if(data === 'deactivated-out') {
                    _this._updateCookie(data);

                    jQuery(element).removeClass('active');
                }

                _this._resetActionTotal(data, element);
            }
        );
    };

    _this._decideForDownUpSwitch = function(data) {
        var newOptions = {};
        newOptions.actions = {};
        newOptions.actions.post = {
            id: 0,
            name: '',
            sub_type: '',
            type: '',
            nli_reset: ''
        };

        if(data === 'activated' || data === 'activated-out') {
            var thisAction = _this.options.post.name === 'downvote' ? 'upvote' : 'downvote';

            if(jQuery(_this.action.element).siblings('button[name=' + thisAction + ']').hasClass('active')) {
                var options = eval('(' + jQuery(_this.action.element).siblings('button[name=' + thisAction + ']').attr('shc:gizmo:options') + ')');

                _this._postAction(jQuery(_this.action.element).siblings('button[name=' + thisAction + ']'), options.actions);

                /**
                 * Unset nli_reset (Not Logged In Reset of vote)
                 */
                options.actions.post.nli_reset = null;
                delete options.actions.post.nli_reset;

                jQuery(_this.action.element).siblings('button[name=' + thisAction + ']').attr('shc:gizmo:options', JSON.stringify(options));

                console.log('options after nli_reset');
                console.log(_this.options);
                console.log('the sibling ' + thisAction + ' of ' + _this.options.post.name);
                console.log(jQuery(_this.action.element).siblings('button[name=' + thisAction + ']'));

                /**
                 * Reset options for _this clicked button
                 */
                _this.options.post.nli_reset = 'deactivate';
                newOptions.actions.post = _this.options.post;

                // this doesn't do anything/
//                newOptions.actions.post.nli_reset = 'deactivate';

                console.log(newOptions.actions.post.nli_reset);
                console.log(newOptions.actions.post);

                jQuery(_this.action.element).attr('shc:gizmo:options', JSON.stringify(newOptions));

                _this.isVoteSwitch = true;
            } else {
                _this.options.post.nli_reset = 'deactivate';
                newOptions.actions.post = _this.options.post;

                jQuery(_this.action.element).attr('shc:gizmo:options', JSON.stringify(newOptions));

                _this.isVoteSwitch = false;
            }
        } else {
            _this.options.post.nli_reset = null;
            delete _this.options.post.nli_reset;

            newOptions.actions.post = _this.options.post;

            jQuery(_this.action.element).attr('shc:gizmo:options', JSON.stringify(newOptions));
        }
    };

    _this._resetActionTotal = function(data, element) {
        var action = _this.options.post.name;
        var currentTotal = '';

        if(action == 'follow') {
            var text = jQuery(element.element).html() === 'following' ? 'follow' : 'following';

            jQuery(element).html(text);
        } else {
            var curId = jQuery(element).attr('id');
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

    _this._updateCookie = function(data) {
        var existingCookies = [];
        var jsonString = '{"actions": [';

        if(typeof(shcJSL.cookies('actions').serve('value')) !== 'undefined' && shcJSL.cookies('actions').serve('value') !== '') {
            existingCookies = eval('(' + shcJSL.cookies('actions').serve('value') + ')');
            existingCookies = existingCookies.actions;

            for(var i = 0; i < existingCookies.length; i++) {
                if(existingCookies[i].id != _this.options.post.id && existingCookies[i].name != _this.options.post.name) {
                    jsonString += '{"id": "' + existingCookies[i].id + '", "name": "' + existingCookies[i].name + '", "sub_type": "' + existingCookies[i].sub_type + '", "type": "' + existingCookies[i].type + '"}, ';
                    console.log(jsonString);
                }
            }
        }

        jsonString += '{"id": "' + _this.options.post.id + '", "name": "' + _this.options.post.name + '", "sub_type": "' + _this.options.post.sub_type + '", "type": "' + _this.options.post.type + '"}]}';

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
