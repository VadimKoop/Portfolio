/**
 * Product List js
 *
 * @package Woostify.
 */

'use strict';

(function ($) {
	var WidgetProductList = function WidgetProductList($scope, $) {
		var featured = $scope.find( '.adv-featured-product' ),
			Items    = featured.attr( 'data-items' ),
			Data     = featured.attr( 'data-products' ),
			Time     = featured.attr( 'data-time' );

		if ( Items >= Data ) {
			return;
		}
		if ( ! featured.length ) {
			return;
		}
			featured.each(
				function() {
					var t         = jQuery( this ),
						perRow    = t.data( 'items' ),
						Auto      = t.data( 'auto' ),
						arrows    = t.parent().find( '.adv-featured-product-arrow' ),
						arrowPrev = arrows.find( '.prev-arrow' ),
						arrowNext = arrows.find( '.next-arrow' ),
						options   = {
							rows: perRow,
							slidesToShow: 1,
							autoplay: Auto,
							autoplaySpeed: Time,
							prevArrow: arrowPrev,
							nextArrow: arrowNext,
							adaptiveHeight: true }
					t.slick( options );
				}
			);
	};

	$( window ).on(
		'elementor/frontend/init',
		function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/woostify-product-list.default', WidgetProductList );
		}
	);
})( jQuery );
