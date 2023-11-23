/**
 * Vertical Menu
 *
 * @package Woostify Pro
 */

'use strict';

// Toggle vertical menu.
var woostifyToggleVerticalMenu = function() {
	var button = document.querySelectorAll( '.toggle-vertical-menu-button' );
	if ( ! button.length ) {
		return;
	}

	button.forEach(
		function( element ) {
			var state = 1;

			element.onclick = function() {
				var t      = this,
					parent = t.closest( '.vertical-menu-wrapper' ),
					menu   = parent.querySelector( '.site-vertical-menu' );

				parent.classList.add( 'active' );

				if ( 1 == state ) {
					state = 2;
				} else {
					state = 1;
				}

				document.addEventListener(
					'click',
					function( e ) {
						var isMenu = e.target.closest( '.site-vertical-menu' );

						if ( t === e.target && 1 == state ) {
							parent.classList.remove( 'active' );
						} else if ( t != e.target && ! isMenu ) {
							parent.classList.remove( 'active' );
							state = 1;
						}
					}
				);
			}
		}
	);
}

// Performance for menu-content-width.
var woostifyMegaMenuContentWidth = function() {
	var mega = document.querySelectorAll( '.has-mega-menu-content-width' ),
		_ww  = window.innerWidth;
	if ( ! mega.length ) {
		return;
	}

	mega.forEach(
		function( item ) {
			var sub       = item.querySelector( '.mega-menu-wrapper' ),
				subWidth  = sub ? sub.offsetWidth : 0,
				subRect   = sub ? sub.getBoundingClientRect() : false,
				itemWidth = item.offsetWidth,
				itemRect  = item.getBoundingClientRect(),
				space     = _ww - itemRect.right - ( itemWidth / 2 ) - ( subWidth / 2 );

			if ( ! sub ) {
				return;
			}

			if ( _ww < 992 ) {
				sub.style.left  = '0px';
				sub.style.right = '';
				return;
			}

			if ( space > 0 ) {
				sub.style.left  = - ( ( subWidth / 2 ) - ( itemWidth / 2 ) ) + 'px';
				sub.style.right = 'auto';
			} else {
				sub.style.left  = 'auto';
				sub.style.right = '0px';
			}
		}
	);
}

// Mega menu container width.
var woostifyMegaMenuContainerWidth = function() {
	var mega = document.querySelectorAll( '.has-mega-menu-container-width' );
	if ( ! mega.length ) {
		return;
	}

	mega.forEach(
		function( element ) {
			// Do not run on sidebar-menu.
			if ( element.closest( '.sidebar-menu' ) ) {
				return;
			}

			var siteHeader = element.closest( '.site-header-inner' ) || element.closest( '.elementor-section' ),
				container  = siteHeader ? siteHeader.querySelector( '.woostify-container' ) : false;

			if ( ! container ) {
				container = siteHeader ? siteHeader.querySelector( '.elementor-container' ) : false;
			}

			var	containerL  = container ? parseInt( window.getComputedStyle( container ).paddingLeft ) : 0,
				containerR  = container ? parseInt( window.getComputedStyle( container ).paddingRight ) : 0,
				containerW  = container ? container.offsetWidth : 1170,
				wrapper     = element.querySelector( '.mega-menu-wrapper' ),
				windowWidth = window.innerWidth;

			if ( windowWidth < 992 ) {
				wrapper.style.left  = '';
				wrapper.style.right = '';

				return;
			}

			containerW = container.offsetWidth - containerL - containerR;

			var calc = ( windowWidth - containerW ) / 2;

			wrapper.style.width = containerW + 'px';
			wrapper.style.left  = containerL + 'px';
		}
	);
}

// Mega menu full width.
var woostifyMegaMenuFullWidth = function() {
	var mega = document.querySelectorAll( '.has-mega-menu-full-width' );
	if ( ! mega.length ) {
		return;
	}

	mega.forEach(
		function( element ) {
			// Do not run on sidebar-menu.
			if ( element.closest( '.sidebar-menu' ) ) {
				return;
			}

			var siteHeader = element.closest( '.site-header-inner' ) || element.closest( '.elementor-section' ),
				container  = siteHeader ? siteHeader.querySelector( '.woostify-container' ) : false;

			if ( ! container ) {
				container = siteHeader ? siteHeader.querySelector( '.elementor-container' ) : false;
			}

			var windowWidth = window.innerWidth,
				containerW  = container ? container.offsetWidth : 1170,
				wrapper     = element.querySelector( '.mega-menu-wrapper' ),
				rect        = wrapper.getBoundingClientRect(),
				wrapperL    = rect.left,
				wrapperR    = rect.right;

			if ( windowWidth < 992 ) {
				wrapper.style.left  = '';
				wrapper.style.right = '';

				return;
			}

			if ( wrapperL <= 0 ) {
				wrapper.style.left = '0px';
			} else {
				wrapper.style.left = -wrapperL + 'px';
			}

			if ( wrapperR >= windowWidth ) {
				wrapper.style.right = '0px';
			} else {
				wrapper.style.right = -( windowWidth - wrapperR ) + 'px';
			}
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyToggleVerticalMenu();

		window.addEventListener(
			'load',
			function() {
				woostifyMegaMenuContentWidth();
				woostifyMegaMenuContainerWidth();
				woostifyMegaMenuFullWidth();
			}
		);

		window.addEventListener(
			'resize',
			function() {
				woostifyMegaMenuContentWidth();
				woostifyMegaMenuContainerWidth();
				woostifyMegaMenuFullWidth();
			}
		);
	}
);
