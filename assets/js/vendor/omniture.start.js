for(var key in s_properties) {
  if(s_properties.hasOwnProperty(key)) { //to be safe
    s[key] = s_properties[key];
  }
}

var omniture = (function () {
	// Initialization Vars
	var _isSocialInitialized = false,
		_isContentTrackingInitialized = false,
		
	// Omniture Functions
		_socialTrackingVars = function (obj, service) {
			s.linkTrackVars = "prop12,prop28";
			s.prop12 = service + ":" + s.prop28;
			s.tl(obj, "o", s.prop12, null, "navigate");
		},

		_contentTrackingVars = function (obj, article) {
			s.linkTrackVars = "prop12,prop28";
			s.prop12 = article + "+s.prop28";
			s.tl(obj, "o", s.prop12, null, "navigate");
		},
	
	// Event Handler Functions
		_socialTrackingHandlers = function () {
			_isSocialInitialized = true;

			// Check if social media is currently on the page
			if ($('.addthis_toolbox, .sharemenulinks').size() === 0) { console.warn("Social Tracking - Nothing found to track."); }
			
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
		},

		_contentTrackingHandlers = function () {
			_isContentTrackingInitialized = true;

			
			/* TODO: finish handlers
			TODO: make sure to add the 'see more' links as well.
			*/
			// Selector Declarations
			// Article links that contain the title
			var articleTitleLinks = [];
				articleTitleLinks.push('section.dropzone-inner-wrapper .featured-post-type-post h1.content-headline a'); // Category Page posts
				articleTitleLinks.push('body.search-results .post h1.content-headline a'); // Search Results posts
				articleTitleLinks.push('body.archive_posts .post h1.content-headline a'); // Post Page posts

			// Article links that do not contain the title
			var articleSeeMoreLinks = [];
				articleSeeMoreLinks.push('section.dropzone-inner-wrapper .featured-post-type-post .moretag') // Category Page see more link
				articleSeeMoreLinks.push('body.search-results .post .moretag'); // Search Results see more link
				articleSeeMoreLinks.push('body.archive_posts .post .moretag'); // Post Page see more link

			// Event handlers for articles
			$(articleTitleLinks.toString()).on('click', function() {
				_contentTrackingHandlers(this, this.innerHTML);
			});

			$(articleSeeMoreLinks.toString()).on('click',function() {
				var articleTitle = $(this).parent().parent().find('h1 a')[0];

				if (articleTitle === undefined) { 
					console.warn("No title found for article link."); 
					_contentTrackingHandlers(this, "No title found for article link.");
				} else {
					//console.log(articleTitle.innerHTML + "share!");
					_contentTrackingHandlers(this, articleTitle.innerHTML);
				}
			});

		};


	
	return {
		socialTracking : function () {
			if  (_isSocialInitialized === false) {
				_socialTrackingHandlers();
			}
		},
		contentTracking : function () {
			if (_isContentTrackingInitialized === false) {
				_contentTrackingHandlers();
			}
		}

	};
}());


/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
var s_code=s.t();if(s_code)document.write(s_code)//-->
