/**
 * Multi step checkout
 *
 * @package woostify
 */

/* global woostify_woocommerce_general, woostify_multi_step_checkout */

'use strict';

// Email input validate.
var woostifyValidateEmail = function( email ) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test( String( email ).toLowerCase() );
}

// Expand order review on mobile.
var woostifyExpandOrderReview = function() {
	var multiStep = document.querySelector( '.has-multi-step-checkout' ),
		checkout  = document.querySelector( 'form.woocommerce-checkout' ),
		expand    = checkout ? checkout.querySelector( '.woostify-before-order-review' ) : false,
		state     = 1;

	if ( ! multiStep || ! expand ) {
		return;
	}

	expand.onclick = function() {
		if ( 1 === state ) {
			checkout.classList.add( 'expanded-order-review' );
			state = 2;
		} else {
			checkout.classList.remove( 'expanded-order-review' );
			state = 1;
		}
	}
}

// Multi step checkout.
var woostifyMultiStepCheckout = function() {
	var multiStep = document.querySelector( '.has-multi-step-checkout' ),
		box       = document.querySelector( '.multi-step-checkout' );

	if ( ! multiStep || ! box ) {
		return;
	}

	var items    = box.querySelectorAll( '.multi-step-item' ),
		checkout = document.querySelector( 'form.woocommerce-checkout' );

	if ( ! items.length || ! checkout ) {
		return;
	}

	var toggleCoupon   = document.querySelector( '.woocommerce-form-coupon-toggle' ),
		shipping       = checkout.querySelector( '#shipping_method' ), // Shipping methods.
		cartSubtotal   = checkout.querySelector( '.cart-subtotal' ), // Cart subtotal.
		wrapperContent = checkout.querySelector( '.multi-step-checkout-wrapper' ), // Wrapper content.
		firstStep      = checkout.querySelector( '.multi-step-checkout-content[data-step="first"]' ), // First step.
		secondStep     = checkout.querySelector( '.multi-step-checkout-content[data-step="second"]' ), // Second step.
		lastStep       = checkout.querySelector( '.multi-step-checkout-content[data-step="last"]' ), // Last step.
		wrapperButton  = checkout.querySelector( '.multi-step-checkout-button-wrapper' ), // Wrapper button action.
		fields         = checkout.querySelectorAll( '.woocommerce-billing-fields__field-wrapper .validate-required' ), // Required input.
		buttonAction   = wrapperButton ? wrapperButton.querySelectorAll( '.multi-step-checkout-button' ) : [], // Back and continue action.
		contineButton  = wrapperButton ? wrapperButton.querySelector( '.multi-step-checkout-button[data-action="continue"]' ) : false, // Get Contine button.
		continueText   = contineButton ? contineButton.getAttribute( 'data-continue' ) : ''; // Get "Continue to" text.

	// Button action.
	if ( buttonAction.length ) {
		buttonAction.forEach(
			function( button ) {
				button.onclick = function() {
					var buttonAction  = button.getAttribute( 'data-action' ),
						currentActive = box.querySelector( '.multi-step-item.active' ),
						prevStep      = currentActive ? currentActive.previousElementSibling : false,
						nextStep      = currentActive ? currentActive.nextElementSibling : false;

					if ( 'back' == buttonAction && prevStep ) {
						prevStep.click();
					}

					if ( 'continue' == buttonAction && nextStep ) {
						nextStep.click();
					}

					// Scroll to top.
					jQuery( 'html, body' ).animate( { scrollTop: jQuery( box ).offset().top }, 300 );
				}
			}
		);
	}

	// Shipping methods.
	var getShippingMethods = function() {
		if ( ! secondStep ) {
			return;
		}

		var methods         = document.querySelectorAll( '#shipping_method .shipping_method' ),
			shippingContent = '';

		if ( methods.length ) {
			wrapperContent.classList.remove( 'no-shipping-available' );
			if ( 1 === methods.length ) {
				var shippingLabel = document.querySelector( '#shipping_method .shipping_method + label' );
				if ( shippingLabel ) {
					shippingContent += '<div class="shipping-methods-modified-item">';
					shippingContent += '<label class="shipping-methods-modified-label"><span>' + shippingLabel.innerHTML + '</span></label>';
					shippingContent += '</div>';
				}
			} else {
				methods.forEach(
					function( method, ix ) {
						var checked = 'checked' == method.getAttribute( 'checked' ) ? 'checked="checked"' : '',
							label   = method.nextElementSibling;

						shippingContent += '<div class="shipping-methods-modified-item">';
						shippingContent += '<label class="shipping-methods-modified-label" for="shipping-methods-index-' + ix + '"><input type="radio" ' + checked + ' name="shipping-method-modified[0]" id="shipping-methods-index-' + ix + '" class="shipping-methods-modified-input" value="' + method.value + '"><span>' + label.innerHTML + '</span></label>';
						shippingContent += '</div>';
					}
				);
			}
		} else {
			wrapperContent.classList.add( 'no-shipping-available' );
		}

		if ( document.querySelector( '.shipping-methods-modified' ) ) {
			document.querySelector( '.shipping-methods-modified' ).innerHTML = shippingContent;
		} else {
			secondStep.insertAdjacentHTML( 'beforeend', '<div class="shipping-methods-modified">' + shippingContent + '</div>' );
		}

		// Trigger shipping method change.
		var modifiedInput = document.querySelectorAll( '.shipping-methods-modified-input' );
		if ( modifiedInput.length ) {
			modifiedInput.forEach(
				function( _inputed, _i ) {
					// Set first checked.
					if ( _inputed.checked && _inputed.value.includes( 'local_pickup' ) ) {
						wrapperContent.classList.add( 'has-local-pickup' );
					}

					_inputed.onclick = function() {
						var currentIndex = _i + 1,
							currentInput = document.querySelector( '#shipping_method li:nth-of-type(' + currentIndex + ') input[type="radio"]' );

						if ( currentInput ) {
							currentInput.click();
						}

						if ( _inputed.value.includes( 'local_pickup' ) ) {
							wrapperContent.classList.add( 'has-local-pickup' );
						} else {
							wrapperContent.classList.remove( 'has-local-pickup' );
						}
					}
				}
			);
		}
	}

	// Validate input.
	var validateInput = function( param ) {
		var fields = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : [];
		if ( ! fields.length ) {
			return;
		}

		// Check input.
		var checkInput = function( iparam, iparam2 ) {
			var input = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : false,
				field = ( arguments.length > 0 && undefined !== arguments[1] ) ? arguments[1] : false,
				ipv   = input ? input.value.trim() : '';

			if ( ! field ) {
				return;
			}

			if ( ipv ) {
				if ( 'email' == input.type ) {
					if ( woostifyValidateEmail( ipv ) ) {
						field.classList.remove( 'field-required' );
					} else {
						field.classList.add( 'field-required' );
					}
				} else {
					field.classList.remove( 'field-required' );
				}
			} else {
				field.classList.add( 'field-required' );
			}
		}

		fields.forEach(
			function( field ) {
				var input = field.querySelector( '[name]' );
				if ( ! input ) {
					return;
				}

				input.addEventListener(
					'input',
					function() {
						checkInput( input, field );
					}
				);
			}
		);
	}

	// Get required fields.
	var getRequiredFields = function( param ) {
		var requiredFields = checkout.querySelectorAll( '.woocommerce-billing-fields__field-wrapper .validate-required' ),
			shippingTo     = document.getElementById( 'ship-to-different-address-checkbox' ),
			echo           = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : false;;

		// Validate input.
		if ( ! echo ) {
			validateInput( requiredFields );
		}

		// Shipping to different address.
		if ( shippingTo ) {
			if ( shippingTo.checked ) {
				requiredFields = checkout.querySelectorAll( '.woocommerce-billing-fields__field-wrapper .validate-required, .woocommerce-shipping-fields__field-wrapper .validate-required' );
			} else {
				requiredFields = checkout.querySelectorAll( '.woocommerce-billing-fields__field-wrapper .validate-required' );
			}

			shippingTo.addEventListener(
				'change',
				function() {
					if ( this.checked ) {
						requiredFields = checkout.querySelectorAll( '.woocommerce-billing-fields__field-wrapper .validate-required, .woocommerce-shipping-fields__field-wrapper .validate-required' );
					} else {
						requiredFields = checkout.querySelectorAll( '.woocommerce-billing-fields__field-wrapper .validate-required' );
					}

					// Validate input.
					if ( ! echo ) {
						validateInput( requiredFields );
					}
				}
			);
		}

		// Return all required field.
		if ( echo ) {
			return requiredFields;
		}
	}
	getRequiredFields();

	// Multi step checkout.
	items.forEach(
		function( ele, i ) {
			ele.onclick = function() {
				var nextStep       = ele.nextElementSibling,
					nextStateText  = nextStep ? nextStep.innerText : '',
					validate       = false, // Check validate.
					requiredFields = getRequiredFields( true );

				if ( requiredFields.length ) {
					requiredFields.forEach(
						function( field ) {
							var input = field.querySelector( '[name]' );
							if ( ! input ) {
								return;
							}

							var inputValue = input.value.trim();

							if ( inputValue ) {
								if ( 'email' == input.type ) {
									if ( woostifyValidateEmail( inputValue ) ) {
										field.classList.remove( 'field-required' );
									} else {
										validate = true;
										field.classList.add( 'field-required' );
										return;
									}
								} else {
									field.classList.remove( 'field-required' );
								}
							} else {
								validate = true;
								field.classList.add( 'field-required' );
								return;
							}
						}
					);
				}

				if ( validate ) {
					if ( document.getElementById( 'place_order' ) ) {
						document.getElementById( 'place_order' ).click();
					}

					return;
				}

				// Hide Notice Group error.
				var noticeGroup = document.querySelector( '.woocommerce-NoticeGroup' );
				if ( noticeGroup ) {
					noticeGroup.style.display = 'none';
				}

				// Update next step text.
				if ( contineButton ) {
					contineButton.innerHTML = continueText + ' ' + nextStateText;
				}

				// Active for step.
				var sib = siblings( ele );
				ele.classList.add( 'active' );
				if ( sib.length ) {
					sib.forEach(
						function( e ) {
							e.classList.remove( 'active' );
						}
					);
				}

				// Get review information.
				var reviewBlock    = document.querySelectorAll( '.multi-step-review-information' ),
					_email         = document.getElementById( 'billing_email' ),
					_emailValue    = _email ? _email.value.trim() : '',
					_address1      = document.getElementById( 'billing_address_1' ),
					_address2      = document.getElementById( 'billing_address_2' ),
					_city          = document.getElementById( 'billing_city' ),
					_countryField  = document.getElementById( 'billing_country' ),
					_country       = _countryField ? document.querySelector( '#billing_country option[value="' + _countryField.value + '"]' ) : false,
					_shippingTo    = document.getElementById( 'ship-to-different-address-checkbox' ),
					_shippingAdd1  = document.getElementById( 'shipping_address_1' ),
					_shippingAdd2  = document.getElementById( 'shipping_address_2' ),
					_city2         = document.getElementById( 'shipping_city' ),
					_countryField2 = document.getElementById( 'shipping_country' ),
					_country2      = _countryField2 ? document.querySelector( '#shipping_country option[value="' + _countryField2.value + '"]' ) : false,
					_shippingField = document.querySelector( '#shipping_method .shipping_method[checked="checked"]' ) || document.querySelector( '#shipping_method .shipping_method[data-index="0"]' ),
					_shippingID    = _shippingField ? _shippingField.id : false,
					_shipping      = _shippingID ? document.querySelector( '#shipping_method label[for="' + _shippingID + '"]' ) : false,
					_addressBill   = '',
					_addressShip   = '',
					_addressValue  = '';

					_addressBill += _address1 ? _address1.value.trim() : '';
					_addressBill += _address2 ? ' ' + _address2.value.trim() : '';
					_addressBill += _city ? ' ' + _city.value.trim() : '';
					_addressBill += _country ? ' ' + _country.innerText.trim() : '';

					_addressValue = _addressBill;

				if ( _shippingTo && _shippingTo.checked ) {
					_addressShip += _shippingAdd1 ? _shippingAdd1.value.trim() : '';
					_addressShip += _shippingAdd2 ? ' ' + _shippingAdd2.value.trim() : '';
					_addressShip += _city2 ? ' ' + _city2.value.trim() : '';
					_addressShip += _country2 ? ' ' + _country2.innerText.trim() : '';

					_addressValue = _addressShip;
				}

				if ( reviewBlock.length ) {
					reviewBlock.forEach(
						function( rb ) {
							var reviewEmail    = rb.querySelector( '.multi-step-review-information-row[data-type="email"] .review-information-content' ),
								reviewAddress  = rb.querySelector( '.multi-step-review-information-row[data-type="address"] .review-information-content' ),
								reviewShipping = rb.querySelector( '.multi-step-review-information-row[data-type="shipping"] .review-information-content' );

							if ( reviewEmail ) {
								reviewEmail.innerHTML = _emailValue;
							}

							if ( reviewAddress ) {
								reviewAddress.innerHTML = _addressValue;
							}

							if ( reviewShipping && _shipping ) {
								reviewShipping.innerHTML = _shipping.innerHTML;
							}
						}
					);
				}

				// Get shipping methods.
				getShippingMethods();

				// Update review information.
				var updateReview = document.querySelectorAll( '.review-information-link' );
				if ( updateReview.length ) {
					updateReview.forEach(
						function( ur ) {
							ur.onclick = function() {
								var urParent = ur.closest( '.multi-step-review-information-row' ),
									urType   = urParent ? urParent.getAttribute( 'data-type' ) : false;

								if ( urType ) {
									switch ( urType ) {
										default:
										case 'email':
											items[0].click();
											if ( _email ) {
												_email.focus();
											}
											break;
										case 'address':
											items[0].click();
											if ( _shippingTo && _shippingTo.checked && _shippingAdd1 ) {
												_shippingAdd1.focus();
											} else if ( _address1 ) {
												_address1.focus();
											}
											break;
										case 'shipping':
											items[1].click();
											break;
									}
								}
							}
						}
					);
				}

				// Active for content.
				var index       = i + 1,
					currentItem = wrapperContent.querySelector( '.multi-step-checkout-content.active' ),
					nearlyItem  = wrapperContent.querySelector( '.multi-step-checkout-content:nth-of-type(' + index + ')' );

				if ( currentItem ) {
					currentItem.classList.remove( 'active' );
				}

				if ( nearlyItem ) {
					nearlyItem.classList.add( 'active' );
				}

				// Active for wrapper.
				var firstStep = 0 == i ? true : false,
					lastStep  = index == items.length ? true : false;

				wrapperContent.classList.remove( 'first', 'last' );
				if ( firstStep ) {
					wrapperContent.classList.add( 'first' );

					// Update price first step on mobile.
					var priceMobileFirstStep = document.querySelector( '.woostify-before-order-review .woostify-before-order-review-total-price strong' );
					if ( priceMobileFirstStep ) {
						priceMobileFirstStep.innerText = woostify_multi_step_checkout.content_total;
					}
				} else if ( lastStep ) {
					wrapperContent.classList.add( 'last' );
				}

				if ( 0 == i ) {
					resetCartTotal();
					window.updateOrderState = false;
				} else if ( 1 == i ) {
					jQuery( document.body ).trigger( 'update_checkout' );
				}
			}
		}
	);

	// Shipping placeholder.
	var resetCartTotal = function() {
		if ( ! cartSubtotal ) {
			return;
		}

		var subTotalPrice   = cartSubtotal.querySelector( '.amount' ),
			subTotalPrice   = subTotalPrice ? subTotalPrice.innerHTML : '',
			totalPriceValue = document.querySelector( '.order-total td strong' ),
			orderTotalPrice = checkout.querySelector( '.order-total .amount' ),
			afterSubtotal   = '<tr class="shipping-placeholder">';

		afterSubtotal += '<th>' + woostify_woocommerce_general.shipping_text + '</th>';
		afterSubtotal += '<td>' + woostify_woocommerce_general.shipping_next + '</td>';
		afterSubtotal += '</tr>';

		// Add text.
		if ( ! document.querySelector( '.shipping-placeholder' ) && document.querySelector( 'form.woocommerce-checkout .cart-subtotal' ) ) {
			document.querySelector( 'form.woocommerce-checkout .cart-subtotal' ).insertAdjacentHTML( 'afterend', afterSubtotal );
		}

		// Update total price on step 1.
		if ( totalPriceValue && woostify_multi_step_checkout.price ) {
			totalPriceValue.innerHTML = woostify_multi_step_checkout.price;
		} else if ( orderTotalPrice ) {
			orderTotalPrice.innerHTML = subTotalPrice;
		}

		jQuery( document.body ).on(
			'updated_checkout',
			function( e, data ) {
				var firstStep          = document.querySelector( '.multi-step-checkout-wrapper.first' ),
					renderCheckout     = firstStep ? firstStep.closest( 'form.woocommerce-checkout' ) : false,
					getCartTotal       = renderCheckout ? renderCheckout.querySelector( '.cart-subtotal' ) : false,
					getCartTotalPrice  = getCartTotal ? getCartTotal.querySelector( '.amount' ) : false,
					getCartTotalPrice  = getCartTotalPrice ? getCartTotalPrice.innerHTML : '',
					getOrderTotal      = renderCheckout ? renderCheckout.querySelector( '.order-total' ) : false,
					getOrderTotalPrice = getOrderTotal ? getOrderTotal.querySelector( '.amount' ) : false;

				// Add placeholder text. Always render this.
				if ( document.querySelector( 'form.woocommerce-checkout .cart-subtotal' ) && ! document.querySelector( '.shipping-placeholder' ) ) {
					document.querySelector( 'form.woocommerce-checkout .cart-subtotal' ).insertAdjacentHTML( 'afterend', afterSubtotal );
				}

				// Reset order total price.
				if ( getOrderTotalPrice ) {
					getOrderTotalPrice.innerHTML = getCartTotalPrice;
				} else if ( ! window.updateOrderState ) {
					var updateOrderTable = document.querySelector( '.woocommerce-checkout-review-order-table' );

					if ( updateOrderTable ) {
						updateOrderTable.innerHTML = data.fragments['.woocommerce-checkout-review-order-table'];
					}

					window.updateOrderState = true;
				}
			}
		);
	}
	resetCartTotal();
}

var woostifyUpdateCheckout = function() {
	// Data.
	var data = {
		action: 'update_checkout',
		ajax_nonce: woostify_multi_step_checkout.ajax_nonce
	};

	data = new URLSearchParams( data ).toString();

	// Request.
	var request = new Request(
		woostify_woocommerce_general.ajax_url,
		{
			method: 'POST',
			body: data,
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
					alert( woostify_woocommerce_general.ajax_error );
					console.log( 'Status Code: ' + res.status );
					throw res;
				}

				return res.json();
			}
		).then(
			function( json ) {
				if ( ! json.success ) {
					return;
				}

				var orderTotalTd     = document.querySelector( '.order-total td' ),
					orderTotalTdText = orderTotalTd ? orderTotalTd.innerText : '',
					priceOnMobile    = document.querySelector( '.woostify-before-order-review .woostify-before-order-review-total-price strong' ),
					isFirstStep      = document.querySelector( '.multi-step-checkout-wrapper.first' ),
					getTotalPrice    = orderTotalTd ? orderTotalTd.querySelector( 'strong' ) : false;

				if ( getTotalPrice ) {
					if ( isFirstStep ) {
						getTotalPrice.innerHTML = json.data.content_total;
					} else {
						getTotalPrice.innerHTML = json.data.cart_total;
					}
				}

				if ( priceOnMobile ) {
					if ( isFirstStep ) {
						priceOnMobile.innerHTML = json.data.content_total;
					} else {
						priceOnMobile.innerHTML = json.data.cart_total;
					}
				}
			}
		).catch(
			function( err ) {
				console.log( err );
			}
		);
}

// Update total price on mobile.
var woostifyTotalPriceMobile = function( e, data ) {
	var totalPrice      = document.querySelector( '.order-total td' ),
		totalPriceInner = totalPrice ? totalPrice.innerText : '',
		mobilePrice     = document.querySelector( '.woostify-before-order-review .woostify-before-order-review-total-price strong' ),
		isFirstStep     = document.querySelector( '.multi-step-checkout-wrapper.first' ),
		totalPriceValue = totalPrice ? totalPrice.querySelector( 'strong' ) : false;

	// Update total price on step 1 after apply coupon.
	if ( isFirstStep && totalPriceValue && woostify_multi_step_checkout.price ) {
		totalPriceValue.innerHTML = woostify_multi_step_checkout.price;
	}

	// Update checkout.
	woostifyUpdateCheckout();

	if ( ! mobilePrice ) {
		return;
	}

	if ( isFirstStep ) {
		mobilePrice.innerText = woostify_multi_step_checkout.content_total;
	} else {
		mobilePrice.innerText = woostify_multi_step_checkout.cart_total;
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyMultiStepCheckout();
		woostifyExpandOrderReview();

		jQuery( document.body ).on( 'updated_checkout', woostifyTotalPriceMobile );
	}
);
