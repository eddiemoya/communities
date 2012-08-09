/**
* OpenID
* @author Tim Steele
*	
* @package shcJSL
* @require jQuery 1.7.2
* 
* @version [1.0: 2012-08-08]
* - Initial script release
*/

shcJSL.methods.openID = function(target, options) {
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
	
	($Moodle instanceof MOODLE.modal)? $Moodle[method](target, this):($Moodle = new $Moodle())[method](target,this)
}

shcJSL.gizmos.bulletin['shcJSL.openID.js'] = true;

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
	shcJSL.gizmos.openID = function(element) {
		$(element).bind('click',function(event) {
			shcJSL.get(element).openID();
			event.preventDefault();
		});
	}
}
