/**
 * Sale notification
 *
 * @package Woostify Pro
 */

/* global woostify_sale_notification */

'use strict';

// Timer.
var woostifyTimer = function( callback, delay ) {
	setTimeout( callback, delay );
}

// Sale notification.
var woostifySaleNotification = function() {
	var content = document.getElementById( 'woostify-sale-notification-content' ),
		box     = document.querySelector( '.woostify-sale-notification-box' ),
		mobile  = box ? box.classList.contains( 'display-on-mobile' ) : true;
	if ( ! content || ! box || ( ! mobile && window.outerWidth <= 600 ) ) {
		return;
	}

	var closeBtn         = box.querySelector( '.sale-notification-close-button' ),
		inner            = box.querySelector( '.sale-notification-inner' ),
		firstTimeDisplay = parseInt( woostify_sale_notification.initial_display ) * 1000,
		timeDisplay      = parseInt( woostify_sale_notification.display_time ) * 1000,
		nextTimeDisplay  = parseInt( woostify_sale_notification.next_time_display ) * 1000,
		closeNotiDetect  = new Event( 'notificationClosed' );

	// Hide notification.
	closeBtn.onclick = function() {
		box.classList.remove( 'active' );
		document.documentElement.dispatchEvent( closeNotiDetect );
	}

	// Update notification content.
	var parser       = new DOMParser(),
		doc          = parser.parseFromString( content.innerHTML, 'text/html' ),
		contentInner = doc.querySelectorAll( '.content' );

	// Return first.
	if ( ! contentInner.length ) {
		return;
	}

	// Foreach.
	contentInner.forEach( function( ele, index ) {
		// Loop.
		if ( window.cacheForLoop ) {
			firstTimeDisplay = nextTimeDisplay + timeDisplay;
		}

		// Timer.
		var timeToShow = 0;

		switch ( index ) {
			case 0:
				timeToShow = firstTimeDisplay;
				break;
			default:
				timeToShow = firstTimeDisplay + index * ( timeDisplay + nextTimeDisplay );
				break;
		}

		// Show notification.
		woostifyTimer(
			function() {
				inner.innerHTML = ele.innerHTML;
				box.classList.add( 'active' );

				// Hide notification.
				woostifyTimer(
					function() {
						box.classList.remove( 'active' );
					},
					timeDisplay
				);

				// Enable notification loop.
				if ( '1' == woostify_sale_notification.loop && contentInner.length == index + 1 ) {
					window.cacheForLoop = true;

					var trigger = new Event( 'lastNotification' );
					document.documentElement.dispatchEvent( trigger );
				}
			},
			timeToShow
		);
	} );

	
	// Need more space.
	var addToCartSection = document.querySelector( '.sticky-add-to-cart-section.from-bottom' );
	if ( addToCartSection ) {
		// When add to cart section sticked.
		document.documentElement.addEventListener( 'stickedAddToCart', function() {
			box.classList.add( 'need-more-space' );
		} );

		// When add to cart section unsticked.
		document.documentElement.addEventListener( 'unStickedAddToCart', function() {
			box.classList.remove( 'need-more-space' );
		} );
	}
}

document.addEventListener( 'DOMContentLoaded', function() {
	woostifySaleNotification();

	document.documentElement.addEventListener( 'lastNotification', function() {
		woostifySaleNotification();
	} );
} );
