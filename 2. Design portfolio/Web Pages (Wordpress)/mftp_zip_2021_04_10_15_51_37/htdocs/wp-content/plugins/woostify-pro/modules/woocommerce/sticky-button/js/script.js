/**
 * Sticky Button
 *
 * @package Woostify Pro
 */

'use strict';

// Scrolling detect.
var woostifyScrollDetect = function() {
	if ( 'function' !== typeof( woostifyConditionScrolling ) || ! woostifyConditionScrolling() || 'function' !== typeof( scrollingDetect ) ) {
		return;
	}

	scrollingDetect();
}

// Format number.
var woostifyFormatNumber = function( num ) {
	return num.toString().replace( /(\d)(?=(\d{3})+(?!\d))/g, '$1,' );
}

// Add to cart section.
var woostifyAddToCartSection = function() {
	var section = document.querySelector( '.sticky-add-to-cart-section' ),
		cart    = document.querySelector( 'form.cart' );

	if ( ! section || ! cart ) {
		return;
	}

	var stickyButton = section.querySelector( '.sticky-atc-button' ),
		singleButton = cart.querySelector( '.single_add_to_cart_button' ),
		price        = section.querySelector( '.sticky-atc-price' ),
		quantity     = cart.querySelector( '.quantity .qty' ),
		originPrice  = price.innerHTML,
		data         = JSON.parse( section.querySelector( '.sticky-atc-data' ).getAttribute( 'data-product' ) );

	// Update total price.
	var totalPrice = function( min, max ) {
		if ( ! quantity ) {
			return;
		}

		var qty          = quantity.value,
			min          = arguments.length > 0 && undefined !== arguments[0] ? arguments[0] : data.price,
			max          = arguments.length > 0 && undefined !== arguments[1] ? arguments[1] : data.regular_price,
			getPriceHtml = function( q ) {
				var q         = arguments.length > 0 && undefined !== arguments[0] ? arguments[0] : qty,
					minFix    = woostifyFormatNumber( parseFloat( q * min ).toFixed( 2 ) ),
					maxFix    = woostifyFormatNumber( parseFloat( q * max ).toFixed( 2 ) ),
					priceHtml = '',
					minPrice  = '',
					maxPrice  = '';

				minFix = minFix.replace( /,/g, data.currency_separator );
				minFix = minFix.replace( '.', data.currency_decimal );

				maxFix = maxFix.replace( /,/g, data.currency_separator );
				maxFix = maxFix.replace( '.', data.currency_decimal );

				switch ( data.currency_pos ) {
					case 'right':
						minPrice = minFix + data.currency;
						maxPrice = maxFix + data.currency;
						break;
					case 'right_space':
						minPrice = minFix + ' ' + data.currency;
						maxPrice = maxFix + ' ' + data.currency;
						break;
					case 'left_space':
						minPrice = data.currency + ' ' + minFix;
						maxPrice = data.currency + ' ' + maxFix;
						break;
					case 'left':
					default:
						minPrice = data.currency + minFix;
						maxPrice = data.currency + maxFix;
						break;
				}

				if ( min == max ) {
					priceHtml = minPrice;
				} else {
					priceHtml += '<del>' + maxPrice + '</del>';
					priceHtml += '<ins>' + minPrice + '</ins>';
				}

				return priceHtml;
			};

		// Update price when quantity change.
		quantity.addEventListener( 'change', function() {
			if ( quantity.value < 1 || isNaN( quantity.value ) ) {
				alert( data.valid_qty );

				return;
			}

			price.innerHTML = getPriceHtml( quantity.value );
		} );
	}
	totalPrice();

	// For Variable product.
	if ( section.classList.contains( 'variations-product' ) ) {
		var reset         = document.querySelector( '.reset_variations' ),
			variationForm = 'form.variations_form';

		// Update price.
		jQuery( document.body ).on( 'found_variation', variationForm, function ( event, variation ) {
			// No need update Price if product have single Variation or Not.
			if (
				section.classList.contains( 'no-need-update-price' ) ||
				data.price == data.regular_price ||
				event.currentTarget.closest( '#woostify-quick-view-panel' )
			) {
				return;
			}

			// Print price html.
			totalPrice( variation.display_price, variation.display_regular_price );
		});

		// Check variations.
		jQuery( document.body ).on( 'check_variations', variationForm, function( event ) {
			var _disabled = event.target.querySelector( '.woocommerce-variation-add-to-cart-disabled' )

			// If inside quickview, return.
			if ( event.currentTarget.closest( '#woostify-quick-view-panel' ) ) {
				return;
			}

			if ( _disabled ) {
				stickyButton.classList.add( 'disabled' );
			} else {
				stickyButton.classList.remove( 'disabled' );
			}
		} );

		// Reset clicked.
		if ( reset ) {
			reset.onclick = function() {
				price.innerHTML = originPrice;
			}
		}
	}

	// Section scroll to top.
	section.onclick = function( e ) {
		var isAddToCart = section.querySelector( '.sticky-atc-button' );

		if ( e.target === isAddToCart && ! isAddToCart.classList.contains( 'disabled' ) ) {
			singleButton.click();
			return;
		}

		var adminBar       = document.getElementById( 'wpadminbar' ),
			adminBarHeight = adminBar ? adminBar.offsetHeight : 0,
			host           = jQuery( '.variations' ).offset().top - adminBarHeight;

		jQuery( 'html, body' ).animate( { scrollTop: host }, 300 );
	}
}

// Sticky Add to cart section.
var woostifyStickySection = function() {
	var section      = document.querySelector( '.sticky-add-to-cart-section' ),
		singleButton = document.querySelector( 'form.cart .single_add_to_cart_button' );

	if ( ! section || ! singleButton ) {
		return;
	}

	var sticky         = section.classList.contains( 'sticky-on-all-devices' ),
		stickyDesktop  = section.classList.contains( 'sticky-on-desktop' ),
		stickyMobile   = section.classList.contains( 'sticky-on-mobile' ),
		inner          = section.querySelector( '.sticky-atc-inner' ),
		offsetTop      = singleButton.getBoundingClientRect().top || 0,
		adminBar       = document.getElementById( 'wpadminbar' ),
		adminBarHeight = adminBar ? adminBar.offsetHeight : 0,
		sticked        = new Event( 'stickedAddToCart' ),
		unSticked      = new Event( 'unStickedAddToCart' );

	if (
		sticky ||
		( stickyDesktop && window.matchMedia( '( min-width: 768px )' ).matches ) ||
		( stickyMobile && window.matchMedia( '( max-width: 767px )' ).matches )
	) {
		if ( offsetTop - adminBarHeight >= 0 ) {
			section.classList.remove( 'active' );
			document.documentElement.dispatchEvent( unSticked );
		} else {
			section.classList.add( 'active' );
			document.documentElement.dispatchEvent( sticked );
		}
	} else {
		section.classList.remove( 'active' );
		document.documentElement.dispatchEvent( unSticked );
	}
}

document.addEventListener( 'DOMContentLoaded', function( event ) {
	window.addEventListener( 'load', woostifyStickySection );
	window.addEventListener( 'resize', woostifyStickySection );
	window.addEventListener( 'scroll', function() {
		woostifyScrollDetect();
		woostifyStickySection();
	} );

	woostifyAddToCartSection();	
} );
