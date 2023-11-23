/**
 * Coupon form
 *
 * @package Woostify Pro
 */

/* global wc_checkout_params */

'use strict';

// Coupon form.
var couponForm = {
	showForm: function() {
		jQuery( document.body ).one(
			'click',
			'.woostify-coupon-modified .showcoupon',
			function( e ) {
				e.preventDefault();

				jQuery( '.woostify-coupon-modified .checkout_coupon' ).slideToggle(
					400,
					function() {
						jQuery( '.woostify-coupon-modified [name="coupon_code"]' ).focus();
					}
				);
			}
		);
	},
	submitForm: function() {
		jQuery( document.body ).on(
			'click',
			'.woostify-coupon-modified [name="apply_coupon"]',
			function() {
				if ( 'undefined' === typeof( wc_checkout_params ) ) {
					return;
				}

				var form = jQuery( this ).closest( '.woocommerce-form-coupon' );

				if ( form.is( '.processing' ) ) {
					return false;
				}

				form.addClass( 'processing' ).block(
					{
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					}
				);

				var data = {
					security: wc_checkout_params.apply_coupon_nonce,
					coupon_code: form.find( '[name="coupon_code"]' ).val()
				};

				jQuery.ajax(
					{
						type: 'POST',
						url: wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
						data: data,
						dataType: 'html',
						success: function( code ) {
							jQuery( '.woocommerce-error, .woocommerce-message' ).remove();
							form.removeClass( 'processing' ).unblock();

							if ( code ) {
								form.before( code );
								form.slideUp();

								jQuery( document.body ).trigger( 'applied_coupon_in_checkout', [ data.coupon_code ] );
								jQuery( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
							}
						}
					}
				);

				return false;
			}
		);
	},
}

// Init.
document.addEventListener(
	'DOMContentLoaded',
	function() {
		// For preview mode.
		if ( 'function' === typeof( onElementorLoaded ) ) {
			onElementorLoaded(
				function() {
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/woostify-checkout-coupon-form.default',
						function() {
							couponForm.showForm();
						}
					);
				}
			);
		}

		// Frontend mode.
		couponForm.submitForm();
	}
);
