jQuery(window).load(function() {
	jQuery('.flexslider').flexslider({
		animation: SLIDER_OPTIONS.animation,
		touch: SLIDER_OPTIONS.touch,
		directionNav: SLIDER_OPTIONS.directionNav,
		smoothHeight: false,
		controlNav: SLIDER_OPTIONS.controlNav,
	});
});
