/**
 * Buy Now Product
 *
 * @package Woostify Pro
 */

'use strict';

function woostifyBuyNowProduct() {
	var popup = arguments.length > 0 && undefined !== arguments[0] ? arguments[0] : false,
		cart  = document.querySelectorAll( 'form.cart' );

	if ( true == popup ) {
		cart = document.querySelectorAll( '#woostify-quick-view-panel form.cart' );
	}

	if ( ! cart.length ) {
		return;
	}

	for ( var i = 0, j = cart.length; i < j; i++ ) {
		if ( cart[i].classList.contains( 'grouped_form' ) ) {
			continue;
		}

		var cartForm      = cart[i],
			button        = cartForm.querySelector( '.woostify-buy-now' );

		if ( ! button ) {
			return;
		}

		var checkoutUrl   = button.getAttribute( 'data-checkout_url' ),
			variationForm = cartForm.classList.contains( 'variations_form' ),
			items         = {},
			urlParam      = [],
			finalUrl;

		if ( variationForm ) {
			var productField   = cartForm.querySelector( '[name="product_id"]' ),
				variationField = cartForm.querySelector( '[name="variation_id"]' ),
				getProductAttr = cartForm.querySelectorAll( 'select[name^="attribute"]' ),
				variationId    = 0;
		}

		button.addEventListener( 'click', function( e ) {
			e.preventDefault();

			var productId = this.value,
				input     = cartForm.getElementsByClassName( 'qty' )[0],
				quantity  = input ? input.value : 0;

			items['add-to-cart'] = parseInt( productId );

			if ( variationForm ) {
				productId   = productField.value;
				variationId = variationField.value;

				items['add-to-cart'] = parseInt( productId );

				getProductAttr.forEach( function( x ) {
					var productName  = x.name,
						productValue = x.value;

					items[productName] = productValue;
				} );
			}

			items['quantity'] = parseInt( quantity );

			for ( var i in items ) {
				urlParam.push( encodeURI( i ) + '=' + encodeURI( items [ i ] ) );
			}

			finalUrl = checkoutUrl + '?' + urlParam.join( '&' );

			window.location = finalUrl;
		} );
	}
}

document.addEventListener( 'DOMContentLoaded', function() {
	woostifyBuyNowProduct();
} );
