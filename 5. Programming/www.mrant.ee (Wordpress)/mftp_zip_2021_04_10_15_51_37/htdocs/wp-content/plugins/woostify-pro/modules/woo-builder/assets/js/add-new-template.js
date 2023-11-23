/**
 * Edit Woobuilder
 *
 * @package Woostify Pro
 */

'use strict';

var woostifyAddNewWooBuilder = function() {
	var addNewButton = document.querySelector( '.page-title-action' );
	if ( ! addNewButton ) {
		return;
	}

	addNewButton.onclick = function( e ) {
		var template = document.querySelector( '.woostify-add-new-template-builder' );
		if ( ! template ) {
			return;
		}

		e.preventDefault();

		var closeBtn = template.querySelector( '.woostify-add-new-template-close-btn' );

		// Show dialog template.
		template.classList.add( 'active' );

		// Close via button.
		if ( closeBtn ) {
			closeBtn.onclick = function() {
				template.classList.remove( 'active' );
			}
		}

		// Close via ESC key.
		document.body.addEventListener(
			'keyup',
			function( e ) {
				if ( 27 === e.keyCode ) {
					template.classList.remove( 'active' );
				}
			}
		);

		// Close via overlay.
		template.onclick = function( e ) {
			if ( this !== e.target ) {
				return;
			}

			template.classList.remove( 'active' );
		}

		return;

		// Ajax.
		var sumbit     = template.querySelector( '.woostify-add-new-template-form-submit' ),
			nonce      = template.querySelector( 'input[name="_wpnonce"]' ),
			nonceValue = nonce ? nonce.value : '';

		if ( ! sumbit ) {
			return;
		}
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyAddNewWooBuilder();
	}
);
