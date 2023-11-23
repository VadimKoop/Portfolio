/**
 * Size guide
 *
 * @package Woostify Pro
 */

'use strict';

// Close Size guide.
var woostifySizeGuideClose = function() {
	var closeBtn = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : false,
		table    = ( arguments.length > 0 && undefined !== arguments[1] ) ? arguments[1] : false;

	if ( ! closeBtn || ! table ) {
		return;
	}

	var closeSideGuide = function() {
		document.documentElement.classList.remove( 'size-guide-open' );
		closeBtn.classList.remove( 'active' );
		table.classList.remove( 'active' );
	}

	closeBtn.addEventListener( 'click', closeSideGuide );

	table.addEventListener(
		'click',
		function( e ) {
			if ( e.target != table ) {
				return;
			}
			closeSideGuide();
		}
	);

	document.body.addEventListener(
		'keyup',
		function( e ) {
			if ( 27 === e.keyCode ) {
				closeSideGuide();
			}
		}
	);
}

// Size guide open.
var woostifySizeGuide = function() {
	var button = document.querySelectorAll( '.woostify-size-guide-button' );
	if ( ! button.length ) {
		return;
	}

	for ( var i = 0, j = button.length; i < j; i++ ) {
		button[i].addEventListener(
			'click',
			function() {
				var t        = this,
					parent   = t.closest( '.woostify-size-guide-table-wrapper' ),
					closeBtn = parent ? parent.querySelector( '.woostify-size-guide-close-button' ) : false,
					table    = parent ? parent.querySelector( '.woostify-size-guide-table' ) : false;

				document.documentElement.classList.add( 'size-guide-open' );

				if ( ! closeBtn || ! table ) {
					return;
				}

				table.classList.add( 'active' );
				closeBtn.classList.add( 'active' );

				// Close size guide.
				woostifySizeGuideClose( closeBtn, table );
			}
		);
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifySizeGuide();
	}
);
