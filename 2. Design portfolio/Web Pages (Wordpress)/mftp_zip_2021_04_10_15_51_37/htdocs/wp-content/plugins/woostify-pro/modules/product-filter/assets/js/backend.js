/**
 * Product filter
 *
 * @package Woostify Pro
 */

/* global woostify_product_filter */

'use strict';

// Check range.
const woostifyRangeValue = function() {
	let list = document.querySelector( '.w-filter-check-range' );
	if ( ! list ) {
		return;
	}

	const removeItem = function() {
		let item = list.querySelectorAll( '.w-filter-range-item' );
		if ( ! item.length ) {
			return;
		}

		item.forEach(
			function( i ) {
				let removeButton = i.querySelector( '.w-filter-range-item-remove' );

				if ( ! removeButton ) {
					return;
				}

				removeButton.onclick = function() {
					i.remove();
				}
			}
		);
	}

	const addItem = function() {
		let itemWrap  = list.querySelector( '.w-filter-range-item-wrap' ),
			addButton = list.querySelector( '.w-filter-range-item-add' );
		if ( ! itemWrap || ! addButton ) {
			return;
		}

		addButton.onclick = function() {
			itemWrap.insertAdjacentHTML( 'beforeend', woostify_product_filter.item_node );
			removeItem();
		}
	}

	removeItem();
	addItem();
}

// Value must not empty.
const woostifyUpdateAttr = function() {
	let type = document.querySelector( '[name="woostify_product_filter_type"]' );
	if ( ! type ) {
		return;
	}

	type.addEventListener(
		'change',
		function() {
			let required = document.querySelectorAll( '.w-filter-check-range .w-filter-required' );
			if ( ! required.length ) {
				return;
			}

			if ( 'check_range' === type.value ) {
				required.forEach(
					function( ele ) {
						ele.setAttribute( 'required', 'required' );
					}
				);
			} else {
				required.forEach(
					function( ele ) {
						ele.removeAttribute( 'required' );

						if ( ! ele.value.trim() ) {
							ele.closest( '.w-filter-range-item' ).remove();
						}
					}
				);
			}
		}
	);
}

// For product category data.
const woostifyProductCatData = function() {
	let filter = document.querySelector( '[name="woostify_product_filter_data"]' ),
		data   = document.querySelector( '.w-filter-category-data' );
	if ( ! filter || ! data ) {
		return;
	}

	// Init.
	if ( 'product_cat' == filter.value ) {
		data.classList.add( 'active' );
	} else {
		data.classList.remove( 'active' );
	}

	// Update when change.
	filter.addEventListener(
		'change',
		function() {
			if ( 'product_cat' == filter.value ) {
				data.classList.add( 'active' );
			} else {
				data.classList.remove( 'active' );
			}
		}
	);

	// Denpendency toggle.
	let includeChild = data.querySelector( '#w-filter-source-category-child' ),
		expandChild  = data.querySelector( '#w-filter-source-category-expand' );

	if ( includeChild && expandChild ) {
		includeChild.addEventListener(
			'click',
			function() {
				let expandParent = expandChild.closest( '.w-filter-category-item' );
				if ( '1' === includeChild.value ) {
					includeChild.value = '0';
					expandParent.classList.add( 'hidden' );
				} else {
					includeChild.value = '1';
					expandParent.classList.remove( 'hidden' );
				}
			}
		);
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyRangeValue();
		woostifyUpdateAttr();
		woostifyProductCatData();
	}
);
