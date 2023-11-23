/**
 * Dashboard script
 *
 * @package Woostify Pro
 */

/* global ajaxurl, woostify_pro_dashboard */

'use strict';

// Check licenses.
var checkLicenses = function() {
	var field  = document.getElementById( 'woostify_license_key_field' ),
		button = document.getElementById( 'woostify_pro_license_key_submit' );

	if ( ! field || ! button ) {
		return;
	}

	button.onclick = function( e ) {
		e.preventDefault();

		var license = field.value.trim(),
			message = document.querySelector( '.license-key-message' );

		if ( ! license.length ) {
			alert( woostify_pro_dashboard.license_empty );
			return;
		}

		button.classList.add( 'updating-message' );

		// Request.
		var request = new Request(
			ajaxurl,
			{
				method: 'POST',
				body: 'action=woostify_pro_check_licenses&ajax_nonce=' + woostify_pro_dashboard.ajax_nonce + '&woostify_license_key=' + license,
				credentials: 'same-origin',
				headers: new Headers(
					{
						'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
					}
				)
			}
		);

		// Receiving Update.
		var receivingUpdate = function() {
			if ( message ) {
				message.innerHTML = woostify_pro_dashboard.receiving;
				message.classList.remove( 'not-receiving-updates' );
				message.classList.add( 'receiving-updates' );
			}

			button.innerHTML = woostify_pro_dashboard.deactivate_label;
			field.disabled   = true;
		}

		// Not Receiving Update.
		var notReceivingUpdate = function() {
			if ( message ) {
				message.innerHTML = woostify_pro_dashboard.not_receiving;
				message.classList.add( 'not-receiving-updates' );
				message.classList.remove( 'receiving-updates' );
			}

			button.innerHTML = woostify_pro_dashboard.activate_label;
			field.disabled   = false;
		}

		// Fetch API.
		fetch( request )
			.then(
				function( res ) {
					if ( 200 !== res.status ) {
						console.log( 'Status Code: ' + res.status );
						button.classList.remove( 'updating-message' );
						return;
					}

					res.json().then(
						function( data ) {
							var success = false,
								action  = 'activate';

							for ( var i = 0, j = data.length; i < j; i++ ) {
								var res = JSON.parse( data[i] );

								if ( ! res.success ) {
									continue;
								}

								if ( res.success && 'valid' === res.license ) {
									success = true;
								} else if ( res.success && 'deactivated' === res.license ) {
									// Deactivate success.
									success = true;
									action  = 'deactivate';
								}
							}

							if ( success ) {
								if ( 'activate' == action ) {
									alert( woostify_pro_dashboard.activate_success_message );
									receivingUpdate();
								} else {
									alert( woostify_pro_dashboard.deactivate_success_message );
									notReceivingUpdate();
								}
							} else {
								notReceivingUpdate();

								alert( woostify_pro_dashboard.failure_message );
							}
						}
					);
				}
			)
			.catch(
				function( err ) {
					console.log( err );
				}
			).finally(
				function() {
					// Remove button loading animation.
					button.classList.remove( 'updating-message' );
				}
			);
	}
}

// Select all module item.
var moduleCheckbox = function() {
	var selectorAll = document.getElementById( 'woostify-select-all' );
	if ( ! selectorAll ) {
		return;
	}

	selectorAll.addEventListener(
		'click',
		function() {
			var checkboxs = document.querySelectorAll( '.module-checkbox' );
			if ( ! checkboxs.length ) {
				return;
			}

			checkboxs.forEach(
				function( el ) {
					if ( el.closest( '.module-item.disabled' ) ) {
						return;
					}

					// Trigger checked.
					if ( selectorAll.checked ) {
						el.checked = true;
					} else {
						el.checked = false;
					}

					// Remove checkbox on Select All.
					el.addEventListener(
						'click',
						function() {
							selectorAll.checked = false;
						},
						{ once: true }
					);
				}
			);
		}
	);
}

// Detect all featured are activated.
var detectFeature = function() {
	var list      = document.querySelectorAll( '.module-item' ),
		activated = document.querySelectorAll( '.module-item.activated' );

	if ( ! list.length ) {
		return;
	}

	var size    = ( list.length == activated.length ) ? 'yes' : '',
		request = new Request(
			ajaxurl,
			{
				method: 'POST',
				body: 'action=all_feature_activated&detect=' + size + '&ajax_nonce=' + woostify_pro_dashboard.ajax_nonce,
				credentials: 'same-origin',
				headers: new Headers(
					{
						'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
					}
				)
			}
		);

	// Fetch API.
	fetch( request );
}

// Activate or Deactive mudule.
var moduleAction = function() {
	var list = document.querySelector( '.woostify-module-list' );
	if ( ! list ) {
		return;
	}

	var item = list.querySelectorAll( '.module-item' );
	if ( ! item.length ) {
		return;
	}

	item.forEach(
		function( element ) {
			var button = element.querySelector( '.module-action-button' );

			if ( ! button ) {
				return;
			}

			button.onclick = function() {
				var parent = button.closest( '.module-item' ),
					option = button.getAttribute( 'data-name' ),
					status = button.getAttribute( 'data-value' ),
					label  = woostify_pro_dashboard.activating;

				if ( 'activated' === status ) {
					label = woostify_pro_dashboard.deactivating;
				}

				// Request.
				var request = new Request(
					ajaxurl,
					{
						method: 'POST',
						body: 'action=module_action&name=' + option + '&status=' + status + '&ajax_nonce=' + woostify_pro_dashboard.ajax_nonce,
						credentials: 'same-origin',
						headers: new Headers(
							{
								'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
							}
						)
					}
				);

				// Set button label when process running.
				button.innerHTML = label;

				// Add .loading class to parent item.
				parent.classList.add( 'loading' );

				// Fetch API.
				fetch( request )
					.then(
						function( res ) {
							if ( 200 !== res.status ) {
								console.log( 'Status Code: ' + res.status );
								return;
							}

							res.json().then(
								function( r ) {
									if ( ! r.success ) {
										return;
									}

									// Update button label.
									button.setAttribute( 'data-value', r.data.status );
									button.innerHTML = 'activated' === r.data.status ? 'deactivate' : 'activate';

									// Update parent class name.
									parent.className = '';
									parent.classList.add( 'module-item', r.data.status );

									// Detect all featured are activated.
									detectFeature();
								}
							);
						}
					).finally(
						function() {
							// Remove .loading class to parent item.
							parent.classList.remove( 'loading' );
						}
					);
			}
		}
	);
}

// Multi Activate or Deactivate module.
var multiModuleAction = function() {
	var action = document.querySelector( '.multi-module-action' ),
		submit = document.querySelector( '.multi-module-action-button' ),
		items  = document.querySelectorAll( '.module-item:not(.disabled)' );

	if ( ! action || ! submit || ! items.length ) {
		return;
	}

	submit.addEventListener(
		'click',
		function() {
			var actionValue = action.value.trim();
			if ( ! actionValue ) {
				return;
			}

			items.forEach(
				function( element, index ) {
					var checkbox    = element.querySelector( '.module-checkbox' ),
						button      = element.querySelector( '.module-action-button' ),
						buttonValue = button.getAttribute( 'data-value' ).trim();

					// Return if process busy.
					if ( element.classList.contains( '.loading' ) ) {
						alert( 'Process running.' );
						return;
					}

					// Return if same Action or Not checked.
					if ( actionValue === buttonValue || ! checkbox.checked ) {
						return;
					}

					// Trigger click.
					var time = 200 * index;
					setTimeout(
						function() {
							button.click();
						},
						time
					);
				}
			);
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		checkLicenses();
		moduleCheckbox();
		moduleAction();
		multiModuleAction();
	}
);
