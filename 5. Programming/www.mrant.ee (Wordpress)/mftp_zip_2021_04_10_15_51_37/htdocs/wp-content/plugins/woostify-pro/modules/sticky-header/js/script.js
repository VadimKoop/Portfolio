/**
 * Sticky header
 *
 * @package Woostify Pro
 */

'use strict';

// Sticky header.
var stikyHeader = function() {
	var position = undefined !== window.pageYOffset ? window.pageYOffset : ( document.documentElement || document.body.parentNode || document.body ).scrollTop,
		header   = document.getElementById( 'masthead' );

	// Disabled on Layout-7.
	if ( ! header || header.classList.contains( 'header-layout-7' ) ) {
		return;
	}

	var headerInner       = header ? header.getElementsByClassName( 'site-header-inner' )[0] : false,
		headerInnerHeight = headerInner ? headerInner.offsetHeight : 0,
		headerInnerTop    = headerInner ? headerInner.getBoundingClientRect().top : 0,

		// Navigation box.
		hasNavBox         = header ? header.classList.contains( 'has-navigation-box' ) : false,
		navBox            = hasNavBox ? header.getElementsByClassName( 'navigation-box' )[0] : false,
		navBoxInner       = hasNavBox ? header.getElementsByClassName( 'navigation-box-inner' )[0] : false,
		navBoxInnerTop    = navBoxInner ? navBoxInner.getBoundingClientRect().top : 0,
		navBoxInnerHeight = navBoxInner ? navBoxInner.offsetHeight : 0,
		navAboveHeight    = 0,
		navBoxCondition   = navBox && navBoxInner && window.matchMedia( '( min-width: 992px )' ).matches,

		// Admin bar.
		adminBar       = document.getElementById( 'wpadminbar' ),
		adminBarHeight = adminBar ? adminBar.offsetHeight : 0,

		// Topbar.
		topbar       = document.getElementsByClassName( 'topbar' )[0],
		topbarHeight = topbar ? topbar.offsetHeight : 0,

		// User logged in on mobile.
		isHeaderMobile = document.querySelector( '.has-sticky-header.logged-in.admin-bar .site-header-inner' ),

		// Sticky header variables.
		sticky, stickyInner, stickyTop, stickyHeight;

	// For specific Header Layout has Navigation box.
	if ( navBoxCondition ) {
		sticky         = navBox;
		stickyInner    = navBoxInner;
		stickyHeight   = navBoxInnerHeight;
		stickyTop      = navBoxInnerTop;
		navAboveHeight = headerInnerHeight - navBoxInnerHeight;

		// Reset style for Header.
		header.style.height = '';
		headerInner.classList.remove( 'fixed' );

		// Remove stickyTop if Adminbar not display.
		if ( 0 == adminBarHeight ) {
			stickyTop = 0;
		}
	} else {
		sticky       = header;
		stickyInner  = headerInner;
		stickyHeight = headerInnerHeight;
		stickyTop    = headerInnerTop;

		// Reset style for Navigation box.
		if ( hasNavBox ) {
			navBox.style.height = '';
			navBoxInner.classList.remove( 'fixed' );
		}
	}

	// Return.
	if ( ! document.body.classList.contains( 'has-sticky-header' ) || ! sticky || ! stickyInner ) {
		return;
	}

	// Set dynamic height for Header.
	sticky.style.height = stickyHeight + 'px';

	// Remove Adminbar height on Mobile device ( max-width: 600px ) and User logged in.
	if ( window.matchMedia( '( max-width: 600px )' ).matches ) {
		adminBarHeight = ( - adminBarHeight );
	}

	// Add or remove 'fixed' class.
	if ( position + adminBarHeight - parseInt( stickyTop ) - topbarHeight - navAboveHeight > 0 ) {
		stickyInner.classList.add( 'fixed' );
	} else {
		stickyInner.classList.remove( 'fixed' );
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		window.addEventListener( 'load', stikyHeader );
		window.addEventListener( 'resize', stikyHeader );
		window.addEventListener( 'scroll', stikyHeader );
	}
);
