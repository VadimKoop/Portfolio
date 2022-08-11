/**
 * Countdown
 *
 * Base on jDoom JS
 *
 * @package Woositfy Pro
 */

'use strict';

var WoostifyCountdown = ( function( options ) {
	/********************************************
	 * Local vars – configuration
	 ********************************************/
	var addZero, callback, biDirectional, targetDateStr, adjustedOffset;

	/********************************************
	 * Local vars – DOM identifiers
	 ********************************************/
	var days, hours, mins, secs;

	/********************************************
	 * Local vars – interval
	 ********************************************/
	var interval, calledBack;

	/********************************************
	 * Initialize local vars
	 ********************************************/
	function init() {
		var targetDate = options.targetDate || null,
			targetTime = options.targetTime || '00:00:00';

		adjustedOffset = adjustOffset( options.utcOffset || null );
		days           = ( options.ids ) ? ( options.ids.days || 'days' ) : 'days';
		hours          = ( options.ids ) ? ( options.ids.hours || 'hours' ) : 'hours';
		mins           = ( options.ids ) ? ( options.ids.mins || 'mins' ) : 'mins';
		secs           = ( options.ids ) ? ( options.ids.secs || 'secs' ) : 'secs';
		addZero        = ( options.addZero === false ) ? false : true;
		callback       = options.callback || ( function() {} );
		biDirectional  = options.biDirectional || false;
		targetDateStr  = strToDate( [ targetDate, targetTime, adjustedOffset ].join( ' ' ) );
		calledBack     = false;
	}

	/********************************************
	 * Interface function
	 ********************************************/
	function run() {
		/* Don't wait for a second to pass before running currentCount */
		currentCount();
		interval = setInterval( currentCount, 1000 );
	}

	/********************************************
	 * Interval functions
	 ********************************************/
	function currentCount() {
		var diff,
			currentTime = strToDate( formatDateToStr( new Date() ) );

		/* If current time less than target time, the count down; otherwise, count up */
		diff = ( currentTime < targetDateStr ) ? targetDateStr - currentTime : currentTime - targetDateStr;
		refresh( diff );

		return;
	}

	/********************************************
	 * Change events
	 ********************************************/

	function refresh( diff ) {
		var timeParts   = getTimeParts( diff ),
			timeVars    = [ days, hours, mins, secs ],
			timeStrings = [ 'days', 'hours', 'mins', 'secs' ];

		/* If difference between current time and target time is less that one second, zero has been reached */
		if ( diff < 1000 ) {
			reachedZero();
		}

		for ( var i = 0, j = timeVars.length; i < j; i++ ) {
			var element = document.getElementById( timeVars[ i ] );
			if ( null !== element ) {
				element.innerHTML = timeParts[ timeStrings[ i ] + 'Part' ];
			}
		}
	}

	function reachedZero() {
		if ( ! biDirectional ) {
			clearInterval( interval );
		}

		if ( ! calledBack ) {
			callback();
			calledBack = true;
		}
	}

	/********************************************
	 * Date parsing functions
	 ********************************************/
	function getTimeParts( diff ) {
		return isNaN( diff ) ? NaN : {
			secsPart: formatZero( Math.floor( diff / 1000 % 60 ) ),
			minsPart: formatZero( Math.floor( diff / 60000 % 60 ) ),
			hoursPart: formatZero( Math.floor( diff / 3600000 % 24 ) ),
			daysPart: formatZero( Math.floor( diff / 86400000 ) )
		};
	}

	function formatZero( number ) {
		if ( ! addZero ) {
			return number;
		}

		return ( number.toString().length === 1 ) ? ( '0' + number ) : number;
	}

	function formatDateToStr( date ) {
		var secsToNow = date.getTime() / 1000,
			hours     = parseInt( secsToNow / 3600 ) % 24,
			mins      = parseInt( secsToNow / 60 ) % 60,
			secs      = Math.floor( secsToNow % 60 ),
			dateStr   = [ date.getMonth() + 1, date.getDate(), date.getFullYear() ].join( '/' ),
			timeStr   = [ hours, mins, secs ].join( ':' );

		return [ dateStr, timeStr ].join( ' ' );
	}

	function strToDate( dateStr ) {
		return ( new Date( Date.parse( dateStr ) ) );
	}

	/********************************************
	 * Timezone functions
	 ********************************************/
	function adjustOffset( utcOffset ) {
		var adjustedOffset;
		if ( utcOffset ) {
			var offsetDirection = utcOffset.charAt( 0 ),
				offsetTime      = utcOffset.substring( 1 ).split( ':' ),
				offsetSecsAbs   = ( +offsetTime[ 0 ] ) * 60 * 60 + ( +offsetTime[ 1 ] ) * 60,
				localOffset     = -Math.abs( date.getTimezoneOffset() * 60 ),
				adjustedSecs    = ( localOffset + eval( offsetDirection + offsetSecsAbs ) ),
				hours           = adjustedSecs / ( 60 * 60 ),
				mins            = Math.abs( adjustedSecs / 60 % 60 );

			adjustedOffset = [ hours, mins ].join( ':' );

		} else {
			adjustedOffset = null;
		}

		return adjustedOffset;
	}

	/********************************************
	 * Initialize
	 ********************************************/
	init();

	/********************************************
	 * Return interfacing function
	 ********************************************/
	return {
		setup: run
	};
} );
