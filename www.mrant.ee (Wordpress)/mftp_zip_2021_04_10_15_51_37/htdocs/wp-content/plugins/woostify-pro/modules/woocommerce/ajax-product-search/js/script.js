/**
 * Ajax product search
 *
 * @package Woostify Pro
 */

/* global woostify_ajax_product_search_data */

'use strict';

// Set delay time when user typing.
var woostifySearchDelay = function() {
	var timer = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : 0;

	return function( callback, ms ) {
		clearTimeout( timer );
		timer = setTimeout( callback, ms );
	};
}();

// Ajax product search.
var woostifyAjaxProductSearch = function() {
	var selector = document.querySelectorAll( '.search-field' );
	if ( ! selector.length || 'undefined' === typeof( woostify_ajax_product_search_data ) ) {
		return;
	}

	selector.forEach(
		function( element ) {
			var form            = element.closest( 'form' ),
				isProductSearch = form ? form.classList.contains( 'woocommerce-product-search' ) : false,
				search          = form ? form.querySelector( '.search-field' ) : false;

			if ( ! form || ! isProductSearch || ! search ) {
				return;
			}

			search.setAttribute( 'autocomplete', 'off' );

			var parent  = form.closest( '.site-search' ) || form.closest( '.dialog-search-content' ),
				results = parent ? parent.querySelector( '.ajax-search-results' ) : false;

			if ( ! results ) {
				return;
			}

			// Add clear text button.
			var clearText = document.createElement( 'span' );
			clearText.setAttribute( 'class', 'clear-search-results ti-close' );
			search.parentNode.insertBefore( clearText, search.nextSibling );

			// Append select markup html.
			if ( woostify_ajax_product_search_data.select ) {
				form.insertAdjacentHTML( 'afterbegin', woostify_ajax_product_search_data.select );
				form.classList.add( 'category-filter' );
			}

			// Category selector.
			var category = form.querySelector( '.ajax-product-search-category-filter' );

			// Fetch.
			var fetchApi = function() {
				var categoryFilter = undefined !== arguments[0] ? arguments[0] : false,
					catId          = category ? category.value.trim() : '';

				// Return if search field is empty.
				var keyword = search.value.trim();
				if ( ! keyword ) {
					clearText.classList.remove( 'show' );
					results.classList.add( 'hide' );
					return;
				}

				// Clear text and hide search results.
				clearText.classList.add( 'show' );
				results.classList.remove( 'hide' );

				clearText.onclick = function() {
					search.value = '';
					clearText.classList.remove( 'show' );
					results.classList.add( 'hide' );
				}

				// If current value === Prev value AND NOT category filter, return.
				var prevValue = search.getAttribute( 'data-value' );
				if ( keyword === prevValue && ! categoryFilter ) {
					return;
				}

				// Set Current search value.
				search.setAttribute( 'data-value', keyword );

				// Add class 'loading' to View.
				form.classList.add( 'loading' );

				// Request.
				var request = new Request(
					woostify_ajax_product_search_data.ajax_url,
					{
						method: 'POST',
						body: 'action=ajax_product_search&ajax_nonce=' + woostify_ajax_product_search_data.ajax_nonce + '&ajax_product_search_keyword=' + encodeURIComponent( keyword ) + '&cat_id=' + catId,
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
								alert( woostify_ajax_product_search_data.ajax_error );
								console.log( 'Status Code: ' + res.status );
								return;
							}

							res.json().then(
								function( r ) {
									if ( ! r.success ) {
										return;
									}

									// Append html content.
									results.innerHTML = r.data.content;

									var dialogSearchTitle = document.querySelector( '.dialog-search-title' ),
										searchCount       = document.querySelector( '.search-dialog-count' );

									if ( ! searchCount && dialogSearchTitle ) {
										dialogSearchTitle.insertAdjacentHTML( 'beforeend', '<span class="search-dialog-count aps-highlight">' + ( 0 == r.data.size ? '' : r.data.result ) + '</span>' );
									} else {
										searchCount.innerHTML = ( 0 == r.data.size ? '' : r.data.result );
									}
								}
							);
						}
					).finally(
						function() {
							// Remove class 'loading' to View.
							form.classList.remove( 'loading' );
						}
					);
			}

			// When user typing...
			search.addEventListener(
				'input',
				function() {
					woostifySearchDelay(
						function() {
							fetchApi();
						},
						500
					);
				}
			);

			// When user update select field.
			if ( category ) {
				category.addEventListener(
					'change',
					function() {
						fetchApi( true );
						search.focus();
					}
				);
			}

		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyAjaxProductSearch();
	}
);
