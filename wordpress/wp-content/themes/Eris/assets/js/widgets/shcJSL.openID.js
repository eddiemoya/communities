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
	var configs;	// Configurations for openID
	var element;	// Element for openID
	var getOptions; // (Function) Get optional parameters for the service
	var options;	// Element options for openID
	var services;	// OpenID service URLs
	
	element = target;
	options = this;

	configs = {
		tokenURL: (window['OID'] != undefined && window['OID'].token_url != undefined)? window['OID'].token_url:'',
		ssoURL: 'https://sears-qa.rpxnow.com' //QA
		//ssoURL: 'https://signin.shld.net' //Prod
	}
	
	services = {
		facebook: {	// Needs ? for tokenURL
			url: '/facebook/connect_start',
			options: ["publish_stream","offline_access","user_activities","friends_activities","user_birthday","friends_birthday","user_events","friends_events","user_interests","friends_interests","user_likes","friends_likes","email","user_location","friends_location","user_hometown","friends_hometown"]
		}, // END facebook
		yahoo: {	// Needs & for tokenURL
			url: '/openid/start?openid_identifier=http://me.yahoo.com'
		}, // END Yahoo!
		google: {	// Neds & for tokenURL
			url: '/openid/start?openid_identifier=https://www.google.com/accounts/o8/id'
		}, // END Google
		twitter: {	// Needs ? for tokenURL
			url: '/twitter/start'
		}
	}
	
	$.each($(element).find("*[shc\\:openID]"), function(key, value) {
		var service; // Service the user is using for open ID
		var url; // Pop up URL
		
		service = $(value).attr("shc:openid");
		url = configs.ssoURL + services[service].url;
		if (configs.tokenURL != '')(url.indexOf("?") == -1)? url = url + "?token_url=" + escape(configs.tokenURL):url = url + "&token_url=" + escape(configs.tokenURL);
		if (services[service].options) url = url + "&ext-perm=" + services[service].options.join();
		
		$(value).bind('click',function() {
			var height; // New window height
			var width; // New window width
			
			height = 600;
			width = 600;
			mywindow = window.open(url, 'Login', 'resizable=0,height=' + height + ',width=' + width + ',left=' + ((screen.width/2) - (width/2)) + ',top=' + ((screen.height/2) - (height-2)))
		});		
	})
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
		options = ($(element).attr("shc:gizmo:options") != undefined)? (((eval('(' + $(element).attr("shc:gizmo:options") + ')')).openID)?(eval('(' + $(element).attr("shc:gizmo:options") + ')')).openID:{}):{};
		shcJSL.get(element).openID(options);
	}
}
