CAROUSEL = $carousel = function(element, options){
	var element = element ;
	var items = {};
	var options = options;
	var mobius;
	var shiftleft;
	var shiftright;
	var lock = false;
	var self = this;

	
	
		
	items = {
		all 	: $(".product", element), //$(element).find(".product"), // $(".product", element),
		active 	: {
			all		: $(element).find(".active"),	 
			last 	: $($('.active', element ).slice(-1)[0]).index(), //items.active.all[items.active.all.length - 1] //$($('.active', element ).slice(-1)[0]).index(),
			first 	: $($('.active', element )[0]).index()
		},
		ondeck	: {
			 left 	: $($('.inactive-left', element ).slice(-1)[0]).index(),
			 right 	: $($('.inactive-right', element )[0]).index()

		}
	};
	
	shiftleft = function(){
			items.ondeck.left--;
			items.ondeck.right--;
			items.active.first--;
			items.active.last--;
	};

	shiftright = function(){
			items.ondeck.left++;
			items.ondeck.right++;
			items.active.first++;
			items.active.last++;
	};

	//Right
	this.next = function(){

		if(!self.lock){
			self.lock = true;

			$(items.all[items.active.first]).animate({marginLeft:'-100%'},"slow", function (){

				$(this).removeClass('active').addClass('inactive-left').attr('style', '');
				$(items.all[items.ondeck.right]).removeClass('inactive-right').addClass('active');
				
				shiftright();
				self.mobius();	
				self.lock = false;
				$(items.all[items.ondeck.right]).trigger('ajax-get');

			});
		}
	};

	//Left
	this.prev = function(){

		if(!self.lock){
			self.lock = true;
			
			$(items.all[items.active.last]).removeClass('active').addClass('inactive-right');
			$(items.all[items.ondeck.left]).animate({marginLeft:'0'},"slow", function (){
				
				$(this).removeClass('inactive-left').addClass('active').attr('style', '');

				
				self.mobius();
				shiftleft();
				self.lock = false;
				$(items.all[items.ondeck.left]).trigger('ajax-get');
			});
		}
	};

	this.mobius = function(){
			var first	= items.all[0];
			var last	= items.all[items.all.length - 1];
			var done;

			//Prev /Left
			if(items.ondeck.left <= 0){

				$(first).before($(last).removeClass('inactive-right').addClass('inactive-left'));
				shiftright();
				done = true;
			} 

			//Next /Right
			if((items.ondeck.right == items.all.length) && !done){
								
				($(first).get(0)).parentNode.appendChild($(first).get(0));
				$(first).removeClass('inactive-left').addClass('inactive-right');
				shiftleft();
			}
			
			items.all = $(".product", element);						
	}

	this.stopAutoSlide = function() {
		if (self.timer) clearTimeout(self.timer);
	}

	this.startAutoSlide = function(interval) {
		var interval = interval;

		self.timer = setTimeout(function(){
			self.next();
			self.startAutoSlide(interval);
		},interval);
	}
}

shcJSL.methods.carousel = function(element, options){

	var element = element;
	var options = this;
	var carousel = new $carousel(element, options);

	if(typeof options.autoSlideInterval != 'undefined'){
		carousel.startAutoSlide(options.autoSlideInterval);
	}

	$(".right-arrow", element).bind('click', function(){
		carousel.stopAutoSlide();
		carousel.next();
	});

	$(".left-arrow", element).bind('click', function(){
		carousel.stopAutoSlide();
		carousel.prev();
	})

};

if(shcJSL && shcJSL.gizmos) {
	shcJSL.gizmos.carousel = function(element) {

		var options;

		options = ($(element).attr("shc:gizmo:options") != undefined) ? eval('(' + $(element).attr("shc:gizmo:options") + ')') : {};
		shcJSL.get(element).carousel(options);
	}
}