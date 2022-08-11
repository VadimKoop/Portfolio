/**
 * Ajax Shop Filter
 *
 * @package Woostify Pro
 */

/* global woostify_ajax_shop_filter */

'use strict';

// Default price slider of woocommerce.
var woostifySliderPrice = function() {
	var sliderPriceSelector = jQuery( '.price_slider:not(.ui-slider)' );
	if ( ! sliderPriceSelector.length ) {
		return;
	}

	jQuery( 'input#min_price, input#max_price' ).hide();
	jQuery( '.price_slider, .price_label' ).show();

	var min_price         = jQuery( '.price_slider_amount #min_price' ).data( 'min' ),
		max_price         = jQuery( '.price_slider_amount #max_price' ).data( 'max' ),
		step              = jQuery( '.price_slider_amount' ).data( 'step' ) || 1,
		current_min_price = jQuery( '.price_slider_amount #min_price' ).val(),
		current_max_price = jQuery( '.price_slider_amount #max_price' ).val();

	sliderPriceSelector.slider(
		{
			range: true,
			animate: true,
			min: min_price,
			max: max_price,
			step: step,
			values: [ current_min_price, current_max_price ],
			create: function() {
				jQuery( '.price_slider_amount #min_price' ).val( current_min_price );
				jQuery( '.price_slider_amount #max_price' ).val( current_max_price );

				jQuery( document.body ).trigger( 'price_slider_create', [ current_min_price, current_max_price ] );
			},
			slide: function( event, ui ) {
				jQuery( 'input#min_price' ).val( ui.values[0] );
				jQuery( 'input#max_price' ).val( ui.values[1] );

				jQuery( document.body ).trigger( 'price_slider_slide', [ ui.values[0], ui.values[1] ] );
			},
			change: function( event, ui ) {
				jQuery( document.body ).trigger( 'price_slider_change', [ ui.values[0], ui.values[1] ] );
			}
		}
	);
}

// Sort by.
var woostifySortBy = function() {
	var order   = document.querySelector( '.woocommerce-ordering' ),
		orderby = order ? order.querySelector( '.orderby' ) : false,
		eWrap   = order ? ( order.closest( '.elementor-element' ) || order.closest( '.e-element' ) ) : false;

	if ( ! order || ! orderby || eWrap ) {
		return;
	}

	// Disable submit form by Woocommerce js.
	jQuery( '.woocommerce-ordering' ).on(
		'submit',
		function( e ) {
			e.preventDefault();
		}
	);

	order.insertAdjacentHTML( 'beforeend', '<button type="submit"></button>' );

	orderby.addEventListener(
		'change',
		function() {
			var button = order.querySelector( '[type="submit"]' );
			if ( button ) {
				woostifyShopFilter();
				button.click();
			}
		}
	);
}

// Select woocommerce.
var woostifySelectWoo = function() {
	var woo = document.querySelectorAll( '[class*="dropdown_layered_nav_"]' );
	woo.forEach(
		function( element, index ) {
			var classNames     = Array.from( element.classList ),
				getSpecialAttr = classNames.filter(
					function( el ) {
						return el.includes( 'dropdown_layered_nav_' );
					}
				);

			var selector = getSpecialAttr.length ? '.' + getSpecialAttr.join() : false;

			// Continue.
			if ( ! selector ) {
				return;
			}

			// Update value on change.
			jQuery( selector ).eq( index ).on(
				'change',
				function() {
					var that   = jQuery( this ),
						slug   = that.val(),
						name   = selector.replace( '.dropdown_layered_nav_', '' ),
						form   = that.closest( 'form' ),
						filter = form ? form.find( 'input[name="filter_' + name + '"]' ) : [];

					if ( filter.length ) {
						slug = ( slug && slug.includes( ',' ) ) ? slug.join( ',' ) : slug;
						filter.val( slug );
					}

					// Submit form on change if standard dropdown.
					if ( ! that.attr( 'multiple' ) ) {
						form.submit();
					}
				}
			);

			// Use Select2 enhancement if possible.
			if ( jQuery().selectWoo ) {
				var firstOption = jQuery( selector ).eq( index ).find( 'option:eq(0)' ),
					anyLabel    = firstOption.length ? firstOption.html() : '';

				var wc_layered_nav_select = function() {
					jQuery( selector ).eq( index ).selectWoo(
						{
							placeholder: decodeURIComponent( anyLabel ),
							minimumResultsForSearch: 5,
							width: '100%',
							allowClear: false
						}
					);
				};
				wc_layered_nav_select();
			}
		}
	);
}

// Dropdown select categories.
var woostifyDropdownSelectCategory = function() {
	var dropdown = document.querySelectorAll( '.widget_product_categories .dropdown_product_cat' );
	if ( ! dropdown.length ) {
		return;
	}

	dropdown.forEach(
		function( ele ) {
			ele.addEventListener(
				'change',
				function() {
					var selectVal = ele.value.trim(),
						thisPage  = woostify_ajax_shop_filter.shop_url,
						homeUrl   = woostify_ajax_shop_filter.home_url;

					if ( ! selectVal ) {
						return;
					}

					if ( homeUrl.includes( '?' ) ) {
						thisPage = homeUrl + '&product_cat=' + selectVal;
					} else {
						thisPage = homeUrl + '?product_cat=' + selectVal;
					}

					location.href = thisPage;
				}
			);
		}
	);
}

// Ajax shop filter.
var woostifyShopFilter = function() {
	var sidebar = document.getElementById( 'secondary' );
	if ( ! sidebar ) {
		return;
	}

	var selector = document.querySelectorAll( '.woostify-clear-filter-item, .advanced-product-filter a, .woocommerce-widget-layered-nav a, .woocommerce-ordering [type="submit"], .widget.widget_price_filter [type="submit"], .woocommerce-product-search [type="submit"], .woocommerce-widget-layered-nav [type="submit"], .woocommerce-pagination a' );
	if ( ! selector.length ) {
		return;
	}

	var location = window.location;

	selector.forEach(
		function( element ) {
			element.onclick = function( e ) {
				e.preventDefault();

				var url       = element.href,
					products  = document.querySelector( 'ul.products' ),
					offsetTop = products ? products.offsetTop : 0;

				// Filter by form.
				if ( 'submit' == element.type ) {
					var form     = element.closest( 'form' ),
						getParam = form ? form.querySelectorAll( '[name]' ) : [],
						params   = {},
						validate = false;

					if ( getParam.length ) {
						getParam.forEach(
							function( input ) {
								var inputValue = input.value.trim();

								// Continue.
								if ( 'paged' == input.name ) {
									return;
								}

								if ( inputValue ) {
									params[ input.name ] = inputValue;
								} else if ( 'search' == input.type ) {
									input.focus();
									validate = true;
								}
							}
						);

						// Return.
						if ( validate ) {
							return;
						}

						var paramsToString = new URLSearchParams( params ).toString();

						url = decodeURI( location.pathname + '?' + paramsToString );
					}
				}

				// Add loading animation.
				document.documentElement.classList.add( 'woostify-filter-updating' );

				// Request.
				var request = new Request(
					url,
					{
						method: 'GET',
						credentials: 'same-origin',
						headers: new Headers(
							{
								'Content-Type': 'text/html'
							}
						)
					}
				);

				// Fetch API.
				fetch( request )
					.then(
						function( res ) {
							return res.text();
						}
					).then(
						function( data ) {
							var dom           = new DOMParser(),
								doc           = dom.parseFromString( data, 'text/html' ),
								resPrimary    = doc.querySelector( '#primary' ),
								resSecondary  = doc.querySelector( '#secondary' ),
								resResult     = resPrimary ? resPrimary.querySelector( '.woocommerce-result-count' ) : false,
								resPagination = resPrimary ? resPrimary.querySelector( '.woocommerce-pagination' ) : false,
								// Original DOM.
								primary    = document.getElementById( 'primary' ),
								result     = primary ? primary.querySelector( '.woocommerce-result-count' ) : false,
								pagination = primary ? primary.querySelector( '.woocommerce-pagination' ) : false;

							if ( primary && resPrimary ) {
								primary.innerHTML = resPrimary.innerHTML;
							}

							if ( resSecondary ) {
								sidebar.innerHTML = resSecondary.innerHTML;
							}

							if ( result && resResult ) {
								result.innerHTML = resResult.innerHTML;
							}

							if ( pagination && resPagination ) {
								pagination.innerHTML = resPagination.innerHTML;
							}
						}
					).catch(
						function( error ) {
							console.log( error );
						}
					).finally(
						function() {
							if ( history.pushState ) {
								history.pushState( null, null, url );
							}

							// Re-init quick view.
							if ( 'function' === typeof( woostifyQuickView ) ) {
								woostifyQuickView();
							}

							// Re-init swatch list.
							if ( 'function' === typeof( woostifySwatchList ) ) {
								woostifySwatchList();
							}

							// Re-init featured product carousel.
							if ( 'function' === typeof( woostifyFeaturedProduct ) ) {
								woostifyFeaturedProduct();
							}

							// Re-init product categories.
							if ( 'function' === typeof( woostifyProductCategoriesAccordion ) ) {
								woostifyProductCategoriesAccordion();
							}

							// Re-init woocommerce sidebar mobile.
							if ( 'function' === typeof( woostifySidebarMobile ) ) {
								woostifySidebarMobile();
							}

							// Re-init countdown urgency.
							if ( 'function' === typeof( woostifyCountdownUrgency ) ) {
								woostifyCountdownUrgency();
							}

							// Re-init when new dom apply.
							woostifyShopFilter();

							// Slider price.
							woostifySliderPrice();

							// Order by.
							woostifySortBy();

							// Select woocommerce.
							woostifySelectWoo();

							// Remove loading animation.
							document.documentElement.classList.remove( 'woostify-filter-updating' );

							woostifyDropdownSelectCategory();

							// Scroll to products element.
							if ( element.classList.contains( 'page-numbers' ) && products ) {
								setTimeout(
									function() {
										window.scrollTo(
											{
												top: offsetTop,
												left: 0,
												behavior: 'smooth'
											}
										);
									},
									100
								);
							}
						}
					);
			}
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifySortBy();
		woostifyShopFilter();
	}
);
