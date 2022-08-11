/**
 * Countdown Urgency
 *
 * @package Woostify Pro
 */

'use strict';

// Sub.
var sub = function( n ) {
	return ( n < 10 ) ? '0' + n : n;
}

// Countdown timer.
var woostifyCountdownUrgency = function() {
	var selector = document.querySelectorAll( '.woostify-countdown-urgency' );
	if ( ! selector.length ) {
		return;
	}

	for ( var i = 0, j = selector.length; i < j; i ++ ) {
		let element = selector[i];
		if ( element.classList.contains( 'active' ) ) {
			continue;
		}

		let now         = new Date().getTime(),
			duration    = element.getAttribute( 'data-duration' ),
			timeUp      = element.getAttribute( 'data-time-up' ),
			deadline    = new Date( now + parseInt( duration ) ).getTime(),
			content     = element.querySelector( '.woostify-countdown-urgency-timer' ),
			daysHtml    = element.querySelector( '[data-time=days]' ),
			hoursHtml   = element.querySelector( '[data-time=hours]' ),
			minutesHtml = element.querySelector( '[data-time=minutes]' ),
			secondsHtml = element.querySelector( '[data-time=seconds]' );

			element.classList.add( 'active' );

			setInterval(
				function() {
					var nows    = new Date().getTime(),
						live    = deadline - nows + 1000,
						oneDay  = 24 * 60 * 60 * 1000,
						oneHour = 60 * 60 * 1000,
						days    = parseInt( live / oneDay ),
						hours   = parseInt( ( live - days * oneDay ) / oneHour ),
						minutes = parseInt( ( live - days * oneDay - hours * oneHour ) / 60000 ),
						seconds = parseInt( ( live % 60000 ) / 1000 );

					if ( live < 0 ) {
						clearInterval();

						if ( timeUp ) {
							element.remove();
						}

						return;
					}

					daysHtml.innerHTML    = sub( days );
					hoursHtml.innerHTML   = sub( hours );
					minutesHtml.innerHTML = sub( minutes );
					secondsHtml.innerHTML = sub( seconds );
				},
				1000
			);
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		window.onload = function() {
			setTimeout(
				function() {
					woostifyCountdownUrgency();
				},
				100
			);
		}
	}
);
