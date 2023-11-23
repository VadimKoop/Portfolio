/**
 * Quick view
 *
 * @package Woostify Pro
 */

'use strict';

function woostifyQuickViewVariation() {
	var variationForm = jQuery( '#woostify-quick-view-panel .variations_form' );
	if ( ! variationForm.length ) {
		return;
	}

	if ( undefined !== typeof( wc_add_to_cart_variation_params ) ) {
		variationForm.wc_variation_form();
		variationForm.find( '.variations select' ).change();
	}

	if ( 'function' === typeof( woostifyVariationSwatches ) ) {
		woostifyVariationSwatches();
	} else if ( 'undefined' !== typeof( jQuery.fn.tawcvs_variation_swatches_form ) ) {
		variationForm.tawcvs_variation_swatches_form();
	}
}

function woostifyQuickViewSlider() {
	var quickViewGallery = document.getElementById( 'quick-view-gallery' );

	if ( ! quickViewGallery || ! quickViewGallery.classList.contains( 'quick-view-slider' ) ) {
		return;
	}

	var quickViewTinySlider = tns(
		{
			loop: false,
			container: '#quick-view-gallery',
			items: 1,
			mouseDrag: true,
		}
	);

	/*RESET SLIDER*/
	jQuery( document.body ).on(
		'found_variation',
		'form.variations_form',
		function ( event, variation ) {
			quickViewTinySlider.goTo( 'first' );
		}
	);

	jQuery( '.reset_variations' ).on(
		'click',
		function() {
			quickViewTinySlider.goTo( 'first' );
		}
	);
}

function woostifyQuickViewClose() {
	document.documentElement.classList.remove( 'quick-view-open' );
}

function woostifyQuickView() {
	var quickViewBtn      = document.querySelectorAll( '.product-quick-view-btn' ),
		quickViewPanel    = document.getElementById( 'woostify-quick-view-panel' ),
		quickViewContent  = quickViewPanel ? quickViewPanel.querySelector( '.quick-view-content' ) : false,
		quickViewCloseBtn = quickViewPanel ? quickViewPanel.querySelector( '.quick-view-close-btn' ) : false;

	if ( ! quickViewBtn.length ) {
		return;
	}

	for ( var i = 0, j = quickViewBtn.length; i < j; i++ ) {
		quickViewBtn[i].onclick = function() {
			var productId = this.getAttribute( 'data-pid' ),
				currentId = quickViewPanel ? quickViewPanel.getAttribute( 'data-view_id' ) : false;

			if ( productId === currentId ) {
				document.documentElement.classList.add( 'quick-view-open' );
				return;
			}

			quickViewContent.innerHTML = '';

			document.documentElement.classList.add( 'quick-view-open', 'quick-viewing' );

			document.body.addEventListener(
				'keyup',
				function( e ) {
					if ( 27 === e.keyCode ) {
						woostifyQuickViewClose();
					}
				}
			);

			quickViewCloseBtn.addEventListener(
				'click',
				function() {
					woostifyQuickViewClose();
				}
			);

			quickViewPanel.addEventListener(
				'click',
				function( e ) {
					if ( this !== e.target ) {
						return;
					}

					woostifyQuickViewClose();
				}
			);

			quickViewPanel.setAttribute( 'data-view_id', productId );

			// Request.
			var request = new Request(
				woostify_quick_view_data.ajax_url,
				{
					method: 'POST',
					body: 'action=shop_quick_view&ajax_nonce=' + woostify_quick_view_data.ajax_nonce + '&product_id=' + productId,
					credentials: 'same-origin',
					headers: new Headers(
						{
							'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
						}
					)
				}
			);

			// Fetch API.
			fetch( request )
				.then(
					function( res ) {
						if ( 200 !== res.status ) {
							alert( woostify_quick_view_data.ajax_error );
							console.log( 'Status Code: ' + res.status );
							return;
						}

						res.json().then(
							function( data ) {
								document.documentElement.classList.remove( 'quick-viewing' );

								// Append new content.
								quickViewContent.innerHTML = data.content;

								// Update review link.
								var reviewLink = document.querySelector( '.shop-quick-view .woocommerce-review-link' );
								if ( reviewLink ) {
									reviewLink.setAttribute( 'href', data.review_link );
								}

								// Re-init quantity.
								if ( 'function' === typeof( customQuantity ) ) {
									customQuantity();
								}

								// Re-init slider.
								woostifyQuickViewSlider();

								// Re-init woocommerce variation form.
								woostifyQuickViewVariation();

								if ( 'function' === typeof( woostifyCountdownUrgency ) ) {
									woostifyCountdownUrgency();
								}

								// Re-init product variation.
								if ( 'function' === typeof( productVariation ) ) {
									productVariation( '.quick-view-images', '#woostify-quick-view-panel form.variations_form' );
								}

								// Re-init ajax add to cart.
								if ( 'function' === typeof( woostifyAjaxSingleAddToCartButton ) ) {
									woostifyAjaxSingleAddToCartButton();
								}

								// Re-init buy now button.
								if ( 'function' === typeof( woostifyBuyNowProduct ) ) {
									woostifyBuyNowProduct( true );
								}

								// Re-init stock level progress bar.
								if ( 'function' === typeof( woostifyStockQuantityProgressBar ) ) {
									setTimeout(
										function() {
											woostifyStockQuantityProgressBar();
										},
										200
									)
								}
							}
						);
					}
				).catch(
					function( err ) {
						alert( woostify_quick_view_data.ajax_error );
						console.log( err );
					}
				);
		}
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyQuickView();
	}
);
