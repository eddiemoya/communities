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
 * MOODLE, MOODLE.moodle
 * 
 * The Moodle object and modal window object
 * 
 * @var MOODLE (Object) Moodle object for Moodle specific methods, properties
 * @var MOODLE.modal (Object) The modal window object
 * @var $Moodle (Object) a variation on the Moodle modal window object. 
 */
TOOLTIP = {}
TOOLTIP.tooltip = $tooltip = function(element, options) {
    var _this = this;

    _this.init = function() {
        console.log('logging');
    };

    _this.init();
};

shcJSL.methods.tooltip = function(target, options) {
	var method;	// (String) Method to use with the modal window [open|update|close]
	
	if (this.constructor == String) {
		method = this.toString();
	} else if (this.constructor == Object) {
		if (this.action) method = this.action.toString();
		else method = new String("create");
	} else if (this.constructor == Window) {
		method = new String("create");
	} else {
		// Something broke
		return;
	}
	
	($tooltip instanceof TOOLTIP.tooltip)? $Moodle[method](target, this) : ($tooltip = new $tooltip())[method](target,this)
}

shcJSL.gizmos.bulletin['shcJSL.tooltip.js'] = true;

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
	shcJSL.gizmos.tooltip = function(element) {
		$(element).bind('click',function(event) {
			shcJSL.get(element).tooltip();
			event.preventDefault();
		});
	}
}
