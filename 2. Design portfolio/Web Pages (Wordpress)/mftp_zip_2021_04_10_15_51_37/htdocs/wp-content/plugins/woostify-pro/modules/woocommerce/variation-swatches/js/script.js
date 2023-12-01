/**
 * Variation Swatches
 *
 * @package Woostify Pro
 */

/* global woostify_variation_swatches_admin */

'use strict';

// Check variation is available.
var woostifyAvailableVariations = function( selects, variations ) {
	var selects    = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : [],
		variations = ( arguments.length > 0 && undefined !== arguments[1] ) ? arguments[1] : false;

	if ( selects.length ) {
		var arr = [];

		selects.forEach(
			function( a ) {
				var tdValue  = a.closest( '.value' ),
					selector = a.querySelectorAll( 'option' );

				if ( selector.length ) {
					selector.forEach(
						function( s ) {
							var sValue = s.getAttribute( 'value' );
							if ( ! sValue ) {
								return;
							}

							arr.push( sValue );
						}
					);
				}
			}
		);

		var swatches = variations ? variations.querySelectorAll( '.woostify-variation-swatches .swatch' ) : [];
		if ( arr.length && swatches.length ) {
			swatches.forEach(
				function( sw ) {
					var swValue = sw.getAttribute( 'data-value' );
					if ( ! swValue ) {
						return;
					}

					if ( ! arr.includes( swValue ) ) {
						sw.classList.add( 'unavailable' );
					} else {
						sw.classList.remove( 'unavailable' );
					}
				}
			);
		}
	}
}

// Variation swatches.
var woostifyVariationSwatches = function() {
	var form = document.querySelectorAll( 'form.variations_form' );
	if ( ! form.length ) {
		return;
	}

	for ( var i = 0, j = form.length; i < j; i ++ ) {
		var element = form[i],
			swatch  = element.querySelectorAll( '.swatch' );

		if ( ! swatch.length ) {
			return;
		}

		var selected   = [],
			change     = new Event( 'change', { bubbles: true } ),
			noMatching = new Event( 'woostify_no_matching_variations' );

		swatch.forEach(
			function( el ) {
				el.onclick = function( e ) {
					e.preventDefault();

					if ( el.classList.contains( 'unavailable' ) ) {
						return;
					}

					var variations = el.closest( '.variations' ),
						parent     = el.closest( '.value' ),
						allSelect  = variations.querySelectorAll( 'select' ),
						select     = parent.querySelector( 'select' ),
						attribute  = select.getAttribute( 'data-attribute_name' ) || select.getAttribute( 'name' ),
						value      = el.getAttribute( 'data-value' ),
						combi      = select ? select.querySelectorAll( 'option[value="' + value + '"]' ) : [],
						sibs       = siblings( el );

					// Check if this combination is available.
					if ( ! combi.length ) {
						element.dispatchEvent( noMatching, el );

						return;
					}

					if ( -1 === selected.indexOf( attribute ) ) {
						selected.push( attribute );
					}

					// Highlight swatch.
					if ( el.classList.contains( 'selected' ) ) {
						select.value = '';
						el.classList.remove( 'selected' );

						delete selected[ selected.indexOf( attribute ) ];
					} else {
						el.classList.add( 'selected' );

						if ( sibs.length ) {
							sibs.forEach(
								function( sb ) {
									sb.classList.remove( 'selected' );
								}
							);
						}

						select.value = value;
					}

					// Trigger 'change' event.
					select.dispatchEvent( change );

					// Check if this combination is available.
					woostifyAvailableVariations( allSelect, variations );
				}
			}
		);

		// Reset variations.
		var reset = element.querySelector( '.reset_variations' );
		if ( reset ) {
			reset.addEventListener(
				'click',
				function() {
					var resetSwatches = element.querySelectorAll( '.swatch' );
					if ( resetSwatches.length ) {
						resetSwatches.forEach(
							function( rs ) {
								// Remove all 'unavailable', 'selected' class.
								rs.classList.remove( 'unavailable', 'selected' );
							}
						);
					}

					// Reset selected.
					selected = [];
				}
			);
		}

		// Warning if no matching variations.
		element.addEventListener(
			'woostify_no_matching_variations',
			function() {
				window.alert( wc_add_to_cart_variation_params.i18n_no_matching_variations_text );
			}
		);

		// Check if found variation.
		jQuery( document.body ).on(
			'found_variation',
			jQuery( element ),
			function( event, variation ) {
				var vars = event.target,
					sels = vars ? vars.querySelectorAll( 'select' ) : false;

				if ( ! sels ) {
					return;
				}

				woostifyAvailableVariations( sels, vars );
			}
		);
	}
}

// Swatch list.
var woostifySwatchList = function() {
	var list = document.querySelectorAll( '.swatch-list' );
	if ( ! list.length ) {
		return;
	}

	list.forEach(
		function( element ) {
			var parent    = element.closest( '.product' ),
				imageWrap = parent.querySelector( '.product-loop-image-wrapper' ),
				image     = parent.querySelector( '.product-loop-image' ),
				imageSrc  = image.getAttribute( 'data-src' ),
				items     = element.querySelectorAll( '.swatch' );

			if ( ! items.length ) {
				return;
			}

			items.forEach(
				function( item ) {
					var sib = siblings( item ),
						src = item.getAttribute( 'data-src' );

					// Set selected swatch.
					if ( item.classList.contains( 'selected' ) ) {
						image.setAttribute( 'srcset', '' );
						image.src = src;
					}

					item.onclick = function() {
						imageWrap.classList.add( 'circle-loading' );

						// Remove srcset attribute.
						image.setAttribute( 'srcset', '' );

						// For siblings.
						if ( sib.length ) {
							sib.forEach(
								function( el ) {
									el.classList.remove( 'selected' );
								}
							);
						}

						// Highlight.
						if ( item.classList.contains( 'selected' ) ) {
							item.classList.remove( 'selected' );
							image.src = imageSrc;
						} else {
							item.classList.add( 'selected' );
							image.src = src;
						}

						// Image loading.
						var img = new Image();
						img.src = src;

						img.onload = function() {
							imageWrap.classList.remove( 'circle-loading' );
						};
					}
				}
			);
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyVariationSwatches();
		woostifySwatchList();
	}
);
