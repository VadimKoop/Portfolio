/**
 * General script
 *
 * @package Woostify Pro
 */

'use strict';

// Add to wishlist button.
var addToWishlistBtn = function() {
	var addToWishlistBtn = document.getElementsByClassName( 'add_to_wishlist' );

	if ( ! addToWishlistBtn.length ) {
		return;
	}

	for ( var i = 0, j  = addToWishlistBtn.length; i < j; i++ ) {
		addToWishlistBtn[i].addEventListener( 'click', function() {
			var t = this;
			t.classList.add( 'loading' );

			jQuery( document.body ).on( 'added_to_wishlist', function() {
				t.classList.remove( 'loading' );
			} );
		} );
	}
}


// Header Builder Sticky.
var headerSticky = function() {
	var header = document.querySelector( '.woostify-header-template-builder' );
	if ( ! header || ! header.classList.contains( 'has-sticky' ) ) {
		return;
	}

	var sticky         = header.classList.contains( 'sticky-on-all-device' ),
		stickyDesktop  = header.classList.contains( 'sticky-on-desktop' ),
		stickyMobile   = header.classList.contains( 'sticky-on-mobile' ),
		shrink         = header.classList.contains( 'has-shrink' ),
		inner          = header.querySelector( '.woostify-header-template-builder-inner' ),
		offsetTop      = header.getBoundingClientRect().top || 0,
		adminBar       = document.getElementById( 'wpadminbar' ),
		adminBarHeight = adminBar ? adminBar.offsetHeight : 0,
		sticked        = document.querySelector( '.woostify-header-template-builder-inner.active' );

	if (
		sticky ||
		( stickyDesktop && window.matchMedia( '( min-width: 992px )' ).matches ) ||
		( stickyMobile && window.matchMedia( '( max-width: 991px )' ).matches )
	) {
		if ( offsetTop - adminBarHeight >= 0 ) {
			// Reset state.
			inner.classList.remove( 'active' );
			header.style.height = '';
		} else {
			inner.classList.add( 'active' );
			header.style.height = inner.offsetHeight + 'px';
		}

		// For user logged in on Mobile.
		if ( ! sticked || ! adminBar ) {
			return;
		}

		if ( ( sticky || stickyMobile ) && window.matchMedia( '( max-width: 600px )' ).matches ) {
			if ( window.scrollY < adminBarHeight ) {
				sticked.style.top = ( adminBarHeight - window.scrollY ) + 'px';
			} else {
				sticked.style.top = '0px';
			}
		} else {
			// Reset state.
			sticked.style.top = '';
		}
	} else {
		// Reset state.
		inner.classList.remove( 'active' );
		header.style.height = '';
	}
}

document.addEventListener( 'DOMContentLoaded', function() {
	addToWishlistBtn();

	window.addEventListener( 'load', headerSticky );
	window.addEventListener( 'scroll', headerSticky );
	window.addEventListener( 'resize', headerSticky );
} );
