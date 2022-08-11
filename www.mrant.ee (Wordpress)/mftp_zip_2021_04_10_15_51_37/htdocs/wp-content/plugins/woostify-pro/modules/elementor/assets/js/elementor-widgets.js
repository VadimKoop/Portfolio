/**
 * Elementor preview
 *
 * @package Woostify Pro
 */

'use strict';

// For checkout form widget.
var shopToDifferentAddress = function() {
	var shippingField = document.querySelectorAll( '.woocommerce-shipping-fields' );
	if ( ! shippingField.length ) {
		return;
	}

	shippingField.forEach(
		function( element ) {
			var input   = element.querySelector( '[name="ship_to_different_address"]' ),
				address = element.querySelector( '.shipping_address' );

			if ( ! input || ! address ) {
				return;
			}

			input.addEventListener(
				'change',
				function( e ) {
					if ( input.checked ) {
						address.style.display = 'block';
					} else {
						address.style.display = 'none';
					}
				}
			);
		}
	);
}

// Elementor not print a 'product' class for product item. We add this. Please fix it.
var woostifyHandle = function() {
	var products = document.querySelectorAll( '.woostify-product-slider > li' );
	if ( ! products.length ) {
		return;
	}

	products.forEach(
		function( el ) {
			if ( el.classList.contains( 'product' ) ) {
				return;
			}

			el.classList.add( 'product' );
		}
	);
}

// Carousel widget.
var woostifyCarousel = function( selector ) {
	var element = document.querySelectorAll( selector );

	if ( ! element.length ) {
		return;
	}

	for ( var i = 0, j = element.length; i < j; i++ ) {

		if ( element[i].classList.contains( 'tns-slider' ) ) {
			continue;
		}

		var options = JSON.parse( element[i].getAttribute( 'data-tiny-slider' ) );

		options.container = element[i];

		var slider = tns( options );
	}
}

// Slider widget.
var woostifySlider = function() {
	var sliderWidget = document.getElementsByClassName( 'woostify-slider-widget' );

	if ( ! sliderWidget.length ) {
		return;
	}

	for ( var i = 0, j = sliderWidget.length; i < j; i++ ) {
		// Ignore if slider initialized.
		if ( sliderWidget[i].classList.contains( 'tns-slider' ) ) {
			continue;
		}

		// Get slider options.
		var options = JSON.parse( sliderWidget[i].getAttribute( 'data-tiny-slider' ) );

		options.container = sliderWidget[i];

		// Animated class.
		var animated = 'animated';

		// Callback to be run on initialization.
		options.onInit = function( info ) {
			var startAnimate      = info.slideItems[info.index].getAttribute( 'data-animate' ),
				startSlideContent = info.slideItems[info.index].querySelector( '.woostify-slide-container' );

			// Add the first animation.
			startSlideContent.classList.add( startAnimate, animated );
		}

		// Slider init.
		var slider = tns( options );

		// Bind function to event.
		slider.events.on(
			'transitionEnd',
			function( info, event ) {
				for ( var x = 0, y = info.slideItems.length; x < y; x++ ) {
					// Select slide content.
					var slideContent = info.slideItems[x].querySelector( '.woostify-slide-container' );

					// Remove all animation available.
					slideContent.classList.remove( 'pulse', 'rubberBand', 'shake', 'swing', 'tada', 'wobble', 'jello', 'heartBeat', 'zoomIn', 'fadeIn', 'flipInX', 'flipInY', 'lightSpeedIn', 'fadeInLeft', 'fadeInRight', 'fadeInUp', 'fadeInDown', 'animated' );
				}

				// Select current slide.
				var currentSlide    = info.slideItems[info.index],
					getSlideAnimate = currentSlide.getAttribute( 'data-animate' ),
					getSlideContent = currentSlide.querySelector( '.woostify-slide-container' );

				// Add current slide animation.
				getSlideContent.classList.add( getSlideAnimate, animated );
			}
		);
	}
}

// Nav menu mobile widget.
var woostifyNavMenu = function() {
	var nav = document.querySelectorAll( '.woostify-nav-menu-widget' );
	if ( ! nav.length ) {
		return;
	}

	nav.forEach(
		function( element, index ) {
			var position = element.getAttribute( 'data-menu-position' );
			if ( ! position ) {
				return;
			}

			var button  = element.querySelector( '.woostify-toggle-nav-menu-button' ),
				close   = element.querySelector( '.woostify-close-nav-menu-button' ),
				overlay = element.querySelector( '.woostify-nav-menu-overlay' ),
				inner   = element.querySelector( '.woostify-nav-menu-inner' );

			button.onclick = function() {
				document.documentElement.classList.add( 'woostify-nav-menu-open' );
				inner.classList.add( 'nav-inner-ready' );
			}

			close.onclick = function() {
				document.documentElement.classList.remove( 'woostify-nav-menu-open' );
			}

			overlay.onclick = function() {
				document.documentElement.classList.remove( 'woostify-nav-menu-open' );
			}
		}
	);

	if ( 'function' === typeof( sidebarMenu ) ) {
		sidebarMenu( jQuery( '.woostify-nav-menu-widget .main-navigation' ) );
	}
}

// Countdown widget.
var woostifyCountdown = function() {
	var el = document.querySelectorAll( '.woostify-countdown-widget' );
	if ( ! el.length ) {
		return;
	}

	for ( var i = 0, j = el.length; i < j; i++ ) {
		var date  = el[i].getAttribute( 'data-date' ),
			days  = el[i].querySelector( '.woostify-countdown-days' ).id,
			hours = el[i].querySelector( '.woostify-countdown-hours' ).id,
			mins  = el[i].querySelector( '.woostify-countdown-mins' ).id,
			secs  = el[i].querySelector( '.woostify-countdown-seconds' ).id;

		var counter = WoostifyCountdown(
			{
				targetDate: date,
				ids: {
					days: days,
					hours: hours,
					mins: mins,
					secs: secs,
				}
			}
		);

		counter.setup();
	}
}

// Countdown widget.
var woostifyToogleSidebar = function() {
	var toogle   = jQuery( '#toggle-sidebar-button' ),
		sidebar  = jQuery( '#sidebar-widgets.shop-widget' ),
		overlay  = jQuery( '#woostify-overlay' ),
		html     = jQuery( 'body' ),
		position = jQuery( '#sidebar-widgets' ).attr( 'data-position' );
		html.addClass( position );

		toogle.on(
			"click",
			function()
			{
				sidebar.addClass( 'show' );
				overlay.addClass( 'active' );
				html.addClass( 'sidebar-mobile-open' );  }
		);

		overlay.on(
			"click",
			function()
			{
				sidebar.removeClass( 'show' );
				overlay.removeClass( 'active' );
				html.removeClass( 'sidebar-mobile-open' );	}
		);
}

// DOM loaded.
document.addEventListener(
	'DOMContentLoaded',
	function() {
		// For preview mode.
		if ( 'function' === typeof( onElementorLoaded ) ) {
			onElementorLoaded(
				function() {
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/global',
						function() {
							woostifyCarousel( '.woostify-post-slider' );
							woostifyCarousel( '.woostify-product-slider' );
							woostifyHandle();
							woostifySlider();
							woostifyCountdown();
							shopToDifferentAddress();
							woostifyToogleSidebar();

							// Countdown with real time.
							if ( 'function' === typeof( woostifyCountdownUrgency ) ) {
								woostifyCountdownUrgency();
							}

							// Variation swatches.
							if ( 'function' === typeof( woostifyVariationSwatches ) ) {
								woostifyVariationSwatches();
							}

							// Swatch list.
							if ( 'function' === typeof( woostifySwatchList ) ) {
								woostifySwatchList();
							}

							// Quick view.
							if ( 'function' === typeof( woostifyQuickView ) ) {
								woostifyQuickView();
							}
						}
					);

					// Widget Toogle Sidebar.
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/woostify-toogle-sidebar.default',
						function() {
							woostifyToogleSidebar();
						}
					);

					// Product tabs.
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/woostify-product-tab.default',
						function() {
							if ( 'function' === typeof( woostifyInitSliderFirstTab ) ) {
								woostifyInitSliderFirstTab();
							}

							if ( 'function' === typeof( woostifyProductTab ) ) {
								woostifyProductTab();
							}
						}
					);

					// Nav menu widget.
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/woostify-nav-menu.default',
						function() {
							woostifyNavMenu();
						}
					);

					// Checkout form widget.
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/woostify-checkout-form.default',
						function() {
							jQuery( '.woocommerce-input-wrapper select' ).select2();
						}
					);

				}
			);
		}

		// For frontend.
		window.addEventListener(
			'load',
			function() {
				woostifyCarousel( '.woostify-post-slider' );
				woostifyCarousel( '.woostify-product-slider' );
				woostifySlider();

				// Countdown with real time.
				if ( 'function' === typeof( woostifyCountdownUrgency ) ) {
					setTimeout(
						function() {
							woostifyCountdownUrgency();
						},
						1000
					);
				}
			}
		);

		woostifyToogleSidebar();
		woostifyNavMenu();
		woostifyCountdown();
	}
);
