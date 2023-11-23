/**
 * Edit Mega Menu
 *
 * @package Woostify Pro
 */

// @codingStandardsIgnoreStart

/* global ajaxurl, woostify_admin_mega_menu */

'use strict';

// Get all Prev element siblings.
var woostifyPrevSiblings = function( target ) {
	var siblings = [],
		n        = target;

	if ( n && n.previousElementSibling ) {
		while ( n = n.previousElementSibling ) {
			siblings.push( n );
		}
	}

	return siblings;
}

// Get all Next element siblings.
var woostifyNextSiblings = function( target ) {
	var siblings = [],
		n        = target;

	if ( n && n.nextElementSibling ) {
		while ( n = n.nextElementSibling ) {
			siblings.push( n );
		}
	}

	return siblings;
}

// Get all element siblings.
var woostifySiblings = function( target ) {
	var prev = woostifyPrevSiblings( target ) || [],
		next = woostifyNextSiblings( target ) || [];

	return prev.concat( next );
}

// Add button show popup.
var woostifyButtonShowPopup = function() {
	var buttons = document.querySelectorAll( '.menu-item-mega_menu.menu-item-depth-0' );
	if ( ! buttons.length ) {
		return;
	}

	buttons.forEach(
		function( button ) {
			var buttonId = button.getAttribute( 'id' ),
				input    = button.querySelector( '.edit-menu-item-title' ),
				wrapper  = input ? input.closest( 'p.description' ) : false;

			if ( ! wrapper || ! buttonId ) {
				return;
			}

			buttonId = buttonId.replace( 'menu-item-', '' );

			wrapper.insertAdjacentHTML( 'afterend', '<div class="description description-wide"><span class="woostify-mega-menu-options-button button" data-id="' + buttonId + '" data-depth="0">' + woostify_admin_mega_menu.button_label + '</span></div>' );
		}
	);
}

// Icon picker.
var woostifyIconPicker = function() {
	var picker = document.querySelector( '.woostify-icon-picker' );
	if ( ! picker ) {
		return;
	}

	var inner = picker.querySelector( '.woostify-icon-picker-inner' ),
		icons = picker.querySelectorAll( 'span' ),
		input = picker.querySelector( '.woostify-icon-picker-value' );

	if ( ! icons.length ) {
		return;
	}

	icons.forEach( function( element ) {
		element.onclick = function() {
			var sibs = woostifySiblings( element ),
				attr = element.getAttribute( 'class' );

			if ( element.classList.contains( 'selected' ) ) {
				element.classList.remove( 'selected' );
				input.value = '';
			} else {
				element.classList.add( 'selected' );
				input.value = attr;
			}

			if ( sibs ) {
				sibs.forEach( function( el ) {
					el.classList.remove( 'selected' );
				} );
			}
		}
	} );
}

// Save mega menu options.
var woostifySaveMenuOptions = function() {
	var popup   = document.querySelector( '.woostify-mega-menu-options-popup' ),
		popupId = popup.getAttribute( 'data-id' ),
		attr    = document.querySelector( '#menu-item-' + popupId + ' .woostify-mega-menu-options-button' ),
		_id     = attr.getAttribute( 'data-id' ),
		button  = popup.querySelector( '.save-options' );

	button.onclick = function() {
		var t        = this,
			menuItem = popup.querySelectorAll( '[name*=woostify_mega_menu_item_]' ),
			value    = {};

		t.classList.add( 'loading' );
		t.setAttribute( 'disabled', 'disabled' );

		if ( menuItem.length ) {
			menuItem.forEach( function( el ) {
				value[ el.name ] = el.value;
			} );
		}

		// Request.
		var request = new Request(
			ajaxurl,
			{
				method: 'POST',
				body: 'action=woostify_save_menu_options&security_nonce=' + woostify_admin_mega_menu.ajax_nonce + '&menu_item_id=' + _id + '&options=' + JSON.stringify( value ),
				credentials: 'same-origin',
				headers: new Headers({
					'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
				})
			}
		);

		// Fetch API.
		fetch( request )
			.then( function( res ) {
				if ( 200 !== res.status ) {
					console.log( 'Status Code: ' + res.status );
					return;
				}
			} )
			.catch( function( err ) {
				console.log( err );
			} )
			.finally( function() {
				t.classList.remove( 'loading' );
				t.removeAttribute( 'disabled' );
			} );
	}
}

// Toggle popup mega menu.
var woostifyEditMegaMenu = function() {
	var button = document.querySelectorAll( '.woostify-mega-menu-options-button' );
	if ( ! button.length ) {
		return;
	}

	button.forEach( function( element ) {
		var popup   = document.querySelector( '.woostify-mega-menu-options-popup' ),
			inner   = popup.querySelector( '.woostify-mega-menu-popup-inner' ),
			content = popup.querySelector( '.woostify-mega-menu-popup-content' ),
			label   = popup.querySelector( '.woostify-mega-menu-editing-label' ),
			close   = popup.querySelector( '.woostify-mega-menu-popup-button-close' ),
			_id     = element.getAttribute( 'data-id' ),
			_depth  = element.getAttribute( 'data-depth' ),
			hide    = function() {
				document.documentElement.classList.remove( 'has-mega-menu-open' );
			}

		element.onclick = function() {

			if ( _depth > 0 ) {
				return;
			}

			document.documentElement.classList.add( 'has-mega-menu-open' );

			var parent    = document.getElementById( 'menu-item-' + _id ),
				popupId   = popup.getAttribute( 'data-id' ),
				itemTitle = parent ? parent.querySelector( '.menu-item-title' ).innerHTML : '',
				itemType  = parent ? parent.querySelector( '.item-type' ).innerHTML : '';

			// Return if this popup clicked again.
			if ( popupId === _id ) {
				return;
			}

			// Set current editing label.
			label.innerHTML = label.getAttribute( 'data-label' ) + ' ' + itemType + ': ' + itemTitle;

			// Reset html markup.
			content.innerHTML = '';

			// Add loading animation.
			inner.classList.add( 'loading' );

			// Request.
			var request = new Request(
				ajaxurl,
				{
					method: 'POST',
					body: 'action=woostify_render_popup&security_nonce=' + woostify_admin_mega_menu.ajax_nonce + '&menu_item_id=' + _id + '&depth=' + _depth,
					credentials: 'same-origin',
					headers: new Headers({
						'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
					})
				}
			);

			// Fetch API.
			fetch( request )
				.then( function( res ) {
					if ( 200 !== res.status ) {
						console.log( 'Status Code: ' + res.status );
						return;
					}

					res.json().then( function( data ) {
						if ( data.success ) {
							content.innerHTML = data.data;
							woostifyIconPicker();
						}
					} );
				} )
				.catch( function( err ) {
					console.log( err );
				} )
				.finally( function() {
					popup.setAttribute( 'data-id', _id );
					inner.classList.remove( 'loading' );

					woostifySaveMenuOptions();

					popup.onclick = function( e ) {
						var _child = e.target.closest( '.woostify-mega-menu-popup-inner' );

						if ( _child ) {
							return;
						}

						hide();
					}

					close.onclick = function() {
						hide();
					}

					document.body.addEventListener( 'keyup', function( e ) {
						if ( 27 === e.keyCode ) {
							hide();
						}
					} );
				} );
		}
	});
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyButtonShowPopup();
		woostifyEditMegaMenu();
	}
);
