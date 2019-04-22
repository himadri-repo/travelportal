(function($) {
	
	"use strict";
	
	// Cache Selectors
	var mainWindow		=$(window),
		myBody			=$('body'),
		mainSlider		=$('.flexslider');
	
	
	// Flex Slider
	mainWindow.on('load', function(){
		mainSlider.flexslider({
			animation: "slide",
			direction: "horizontal",
			reverse: false,
			start: function(slider){
				//myBody.removeClass('loading');
				//alert('hi');
			},
			startAt: 0,
			touch: true,
			slideshowSpeed: 2000,
			animationSpeed: 700,
			initDelay: 0,
			flexDirectionNav: true,
			controlNav: true,
			animationLoop: true,
			prevText: "Previous",
			nextText: "Next",
			itemWidth: 310,
			itemMargin: 5
		});
	});

})(jQuery);