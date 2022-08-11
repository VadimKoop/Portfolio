/**
 * Elementor my account
 *
 * @package Woostify Pro
 */

'use strict';

// My account widget.
var woostifyMyAccountWidget = function() {
	var element = document.querySelectorAll( '.woostify-my-account-widget' );
	if ( ! element.length ) {
		return;
	}

	element.forEach(
		function( ele ) {
			var navHead = ele.querySelectorAll( '.woostify-my-account-tab-head a' );
			if ( ! navHead.length ) {
				return;
			}

			for ( var i = 0, j = navHead.length; i < j; i++ ) {
				navHead[i].onclick = function( e ) {
					var t      = this,
						dataId = t.getAttribute( 'data-id' ),
						sibNav = 'function' === typeof( siblings ) ? siblings( t.parentNode ) : [],
						tabId  = ele.querySelector( '#' + dataId ),
						sibTab = 'function' === typeof( siblings ) && tabId ? siblings( tabId ) : [];

					t.parentNode.classList.add( 'active' );
					if ( sibNav.length ) {
						sibNav.forEach(
							function( sn ) {
								sn.classList.remove( 'active' );
							}
						);
					}

					if ( ! tabId ) {
						return;
					}

					e.preventDefault();

					tabId.classList.add( 'active' );
					if ( sibTab.length ) {
						sibTab.forEach(
							function( st ) {
								st.classList.remove( 'active' );
							}
						);
					}
				}
			}
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyMyAccountWidget();

		// For preview mode.
		if ( 'function' === typeof( onElementorLoaded ) ) {
			onElementorLoaded(
				function() {
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/global',
						function() {
							woostifyMyAccountWidget();
						}
					);
				}
			);
		}
	}
);
