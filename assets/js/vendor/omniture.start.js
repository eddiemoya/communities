for(var key in s_properties) {
  if(s_properties.hasOwnProperty(key)) { //to be safe
    s[key] = s_properties[key];
  }
}

var omniture = (function () {
	var _isSocialInitialized = false,
		_socialTrackingVars = function (obj, service) {
			s.linkTrackVars = "prop12,prop28";
			s.prop12 = service + ":" + s.prop28;
			s.tl(obj, "o", s.prop12, null, "navigate");
		},
	
		_socialTrackingHandlers = function () {
			_isSocialInitialized = true;
			
			$('.addthis_toolbox .addthis_button_facebook_like, .sharemenulinks .addthis_button_facebook').on('click', function() {
				//console.log("Facebook Share");
				_socialTrackingVars(this, "Facebook");
			});
			$('.addthis_toolbox .addthis_button_tweet, .sharemenulinks .addthis_button_twitter').on('click', function() {
				//console.log("Twitter Share");
				_socialTrackingVars(this, "Twitter");
			});
			$('.addthis_toolbox .addthis_button_syw, .sharemenulinks .addthis_button_syw').on('click', function() {
				//console.log("Shop Your Way Share");
				_socialTrackingVars(this, "Shop Your Way");
			});
			$('.addthis_toolbox .addthis_button_email, .sharemenulinks .addthis_button_email').on('click', function() {
				//console.log("Email Share");
				_socialTrackingVars(this, "Email");
			});
		};
	
	return {
		socialTracking : function () {
			if  (_isSocialInitialized === false) {
				_socialTrackingHandlers();
			}
		}
	};
}());


/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
var s_code=s.t();if(s_code)document.write(s_code)//-->
