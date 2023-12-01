/**
 * Autocomplete control
 *
 * @package Woostify Pro
 */

/* global woostify_autocomplete */

'use strict';

// Set delay time when user typing.
const woostifyAutocompleteSearchDelay = function() {
	let timer = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : 0;

	return function( callback, ms ) {
		clearTimeout( timer );
		timer = setTimeout( callback, ms );
	};
}();

// Render.
const woostifyAutocompleteRender = function( type = 'post_type', name = 'product', control ) {
	let controlWrap = control.el.querySelector( '.wty-autocomplete' );
	if ( ! controlWrap || ! control.getControlValue().length ) {
		return;
	}

	let controlTitle = control.el.querySelector( '.elementor-control-title' ),
		selection    = controlWrap.querySelector( '.wty-autocomplete-selection' ),
		selected     = selection.querySelector( '.wty-autocomplete-selected' ),
		data         = {
			action: selected.name,
			security_nonce: woostify_autocomplete.nonce,
			selected_id: control.getControlValue(),
			query: type,
			value: name
	};

	// Add loading animation.
	if ( controlTitle ) {
		controlTitle.insertAdjacentHTML( 'beforeend', '<i class="eicon-spinner eicon-animation-spin"></i>' );
	}

	data = new URLSearchParams( data ).toString();

	let request = new Request(
		ajaxurl,
		{
			method: 'POST',
			body: data,
			credentials: 'same-origin',
			headers: new Headers(
				{
					'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
				}
			)
		}
	);

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
						if ( ! r.success || ! r.data ) {
							return;
						}

						selection.insertAdjacentHTML( 'afterbegin', r.data );
						woostifyAutocomplete( type, name, control );
					}
				);
			}
		).catch(
			function( err ) {
				console.log( err );
			}
		).finally(
			function() {
				if ( controlTitle ) {
					let iconLoading = controlTitle.querySelector( '.eicon-spinner' );
					if ( iconLoading ) {
						iconLoading.remove();
					}
				}
			}
		);
}

// Autocomplete.
const woostifyAutocomplete = function( type = 'post_type', name = 'product', control ) {
	let controlWrap = control.el.querySelector( '.wty-autocomplete' );
	if ( ! controlWrap ) {
		return;
	}

	let selection = controlWrap.querySelector( '.wty-autocomplete-selection' ),
		search    = selection.querySelector( '.wty-autocomplete-search' ),
		dropdown  = controlWrap.querySelector( '.wty-autocomplete-dropdown' );

	// Save value.
	const saveItem = function( echo = false ) {
		let selectionItem = selection.querySelectorAll( '.wty-autocomplete-id' ),
			data          = [];

		selectionItem.forEach(
			function( ele ) {
				let saveId = ele.getAttribute( 'data-id' );

				if ( ! saveId || data.includes( saveId ) ) {
					return;
				}

				data.push( saveId );
			}
		);

		if ( true === echo ) {
			return data;
		}

		control.setValue( data );
	}

	// Remove item.
	const removeItem = function() {
		let selectionItem = selection.querySelectorAll( '.wty-autocomplete-id' );
		if ( ! selectionItem.length ) {
			return;
		}

		selectionItem.forEach(
			function( el ) {
				let selectedId   = el.getAttribute( 'data-id' ),
					removeButton = el.querySelector( '.wty-autocomplete-remove-id' );

				if ( ! removeButton ) {
					return;
				}

				removeButton.onclick = function() {
					if ( ! el.parentNode ) {
						return;
					}

					// Show dropdown item.
					controlWrap.classList.add( 'active' );

					// Remove class 'disabled' on dropdown item.
					let isThis = dropdown.querySelector( '[data-id="' + selectedId + '"]' );
					if ( isThis ) {
						isThis.classList.remove( 'disabled' );
					}

					// Remove it.
					el.remove();

					// Save item.
					saveItem();
				}
			}
		);
	}
	removeItem();

	// Add item.
	const addItem = function() {
		let dropdownItem = dropdown.querySelectorAll( '.wty-autocomplete-id' );
		if ( ! dropdownItem.length ) {
			return;
		}

		for ( let i = 0, j = dropdownItem.length; i < j; i++ ) {
			dropdownItem[i].onclick = function() {
				let t        = this,
					disabled = t.classList.contains( 'disabled' ),
					dataId   = t.getAttribute( 'data-id' );

				if ( disabled ) {
					return;
				}

				// Reset state when selected field.
				dropdown.innerHTML = '';
				search.value       = '';

				t.classList.add( 'disabled' );

				let currentId = '<span class="wty-autocomplete-id" data-id="' + dataId + '">' + t.innerHTML + '<i class="wty-autocomplete-remove-id eicon-close-circle"></i></span>';

				selection.insertAdjacentHTML( 'afterbegin', currentId );

				// Save item.
				saveItem();

				// Remove item.
				removeItem();
			}
		}
	}

	if ( search ) {
		search.addEventListener(
			'input',
			function() {
				let searchValue = search.value.trim(),
					data        = {
						action: search.name,
						security_nonce: woostify_autocomplete.nonce,
						keyword: searchValue,
						query: type,
						value: name
				};

				data = new URLSearchParams( data ).toString();

				let request = new Request(
					ajaxurl,
					{
						method: 'POST',
						body: data,
						credentials: 'same-origin',
						headers: new Headers(
							{
								'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
							}
						)
					}
				);

				// Must enter one or more character.
				if ( searchValue.length < 1 ) {
					// Reset dropdown html.
					dropdown.innerHTML = '';

					return;
				}

				// Add searching text.
				dropdown.innerHTML = '<span class="wty-autocomplete-searching">' + woostify_autocomplete.searching + '</span>';

				// Fetch API.
				woostifyAutocompleteSearchDelay(
					function() {
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

											// Update category state.
											let parser       = new DOMParser(),
												doc          = parser.parseFromString( r.data, 'text/html' ),
												ajaxDropdown = doc.querySelectorAll( '.wty-autocomplete-id' );

											if ( ajaxDropdown.length ) {
												ajaxDropdown.forEach(
													function( ajaxItem ) {
														let ajaxItemId = ajaxItem.getAttribute( 'data-id' ) || '',
															saveValue  = saveItem( true ) || [];

														if ( saveValue.length ) {
															if ( saveValue.includes( ajaxItemId ) ) {
																ajaxItem.classList.add( 'disabled' );
															} else {
																ajaxItem.classList.remove( 'disabled' );
															}
														}
													}
												);
											}

											// Append updated html.
											dropdown.innerHTML = doc.body.innerHTML;

											// Add item.
											addItem();
										}
									);
								}
							).catch(
								function( err ) {
									dropdown.innerHTML = '';
									console.log( err );
								}
							);
					},
					500
				);
			}
		);
	}
}

const woostifyAutocompleteData = {
	onReady: function() {
		let control = this,
			query   = control.model.get( 'query' ),
			type    = query.type,
			name    = query.name;

		// For first render.
		woostifyAutocompleteRender( type, name, control );
		// Main func.
		woostifyAutocomplete( type, name, control );
	}
}

// Add Autocomplete control.
const woostifyAutocompleteControl = elementor.modules.controls.BaseData.extend( woostifyAutocompleteData );
elementor.addControlView( 'autocomplete', woostifyAutocompleteControl );
