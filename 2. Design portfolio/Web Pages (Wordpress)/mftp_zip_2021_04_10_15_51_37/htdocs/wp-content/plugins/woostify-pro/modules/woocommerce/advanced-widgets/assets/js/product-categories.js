/**
 * Product categories accordion
 *
 * @package Woostify Pro
 */

'use strict';

// Product categories accordion.
var woostifyProductCategoriesAccordion = function() {
	var accordion       = jQuery( '.advanced-product-categories' ),
		isTypeAccordion = accordion ? accordion.find( '.product-categories.type-accordion' ) : [];
	if ( ! accordion.length || ! isTypeAccordion.length ) {
		return;
	}

	accordion.each( function( index ) {
		var hasChild  = jQuery( this ).find( '.cat-parent .children' );
		if ( ! hasChild.length ) {
			return;
		}

		var catParent = jQuery( this ).find( '.cat-parent' );

		catParent.each( function( i ) {
			// Create Toggle Button.
			var toggle = jQuery( '<span class="accordion-cat-toggle ti-angle-right"></span>' );

			// Append Toggle Button.
			var parent = jQuery( this );
			jQuery( parent ).append( toggle );

			// Toggle Button click.
			toggle.on( 'click', function() {
				var button       = jQuery( this ),
					buttonParent = button.parent(),
					child        = buttonParent.find( '>ul' ),
					state        = button.data( 'state' ) || 1;

				// State update.
				switch ( state ) {
					case 1:
						button.data( 'state', 2 );
						break;
					case 2:
						button.data( 'state', 1 );
						break;
				}

				// Toggle child category.
				child.slideToggle( 300 );

				// Add active class.
				if ( 1 === state ) {
					button.addClass( 'active' );
					buttonParent.addClass( 'active' );
				} else {
					button.removeClass( 'active' );
					buttonParent.removeClass( 'active' );
				}
			} );
		} );
	});
}

document.addEventListener( 'DOMContentLoaded', woostifyProductCategoriesAccordion );
