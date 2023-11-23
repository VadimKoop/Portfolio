/**
 * Featured product
 *
 * @package Woostify Pro
 */

'use strict';

// Featured product.
var woostifyFeaturedProduct = function() {
	var featured = jQuery( '.adv-featured-product' );
	if ( ! featured.length || ! jQuery().slick ) {
		return;
	}

	featured.each(
		function() {
			var t         = jQuery( this ),
				perRow    = t.data( 'items' ),
				arrows    = t.parent().find( '.adv-featured-product-arrow' ),
				arrowPrev = arrows.find( '.prev-arrow' ),
				arrowNext = arrows.find( '.next-arrow' ),
				options   = {
					rows: 1,
					slidesPerRow: perRow,
					prevArrow: arrowPrev,
					nextArrow: arrowNext,
					adaptiveHeight: true
			}

			t.slick( options );

			t.slick( 'resize' );
		}
	);
}

document.addEventListener( 'DOMContentLoaded', woostifyFeaturedProduct );
