/**
* TransFormer: Interactive Form Handler
* @author Tim Steele
*	
* @package shcJSL
* @require jQuery 1.7.2
* 
* @version [1.0: 2012-08-17]
* - Initial script release
*/

/**
 * TRANSfORMER, TRANSfORMER.transFormer
 * 
 * TransFormer object
 */
TRANSfORMER = {}
TRANSfORMER.transFormer = $TransFormer = function(element, options) {

}

shcJSL.methods.transFormer = function(target, options) {
	var method;	// (String) Method to use with the modal window [open|update|close]
	
	//($Moodle instanceof MOODLE.modal)? $Moodle[method](target, this):($Moodle = new $Moodle())[method](target,this)
}

shcJSL.gizmos.bulletin['shcJSL.transFormer.js'] = true;

/**
	 * Event Assigner
	 * 
	 * @access Public
	 * @author Tim Steele
	 * @since 1.0
	 */
if (shcJSL && shcJSL.gizmos)  {
	shcJSL.gizmos.moodle = function(element) {
		$(element).bind('click',function(event) {
			shcJSL.get(element).moodle();
			event.preventDefault();
		});
	}
}
