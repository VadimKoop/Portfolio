/**
 * Edit Screen
 *
 * @package Woostify Pro
 */

/* global ajaxurl, woostify_edit_screen */

'use strict';

// Set delay time when user typing.
var woostifySearchDelay = function() {
	var timer = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : 0;

	return function( callback, ms ) {
		clearTimeout( timer );
		timer = setTimeout( callback, ms );
	};
}();

// Multi select.
var woostifyMultiSelect = function() {
	var meta = document.querySelectorAll( '.woostify-multi-selection' );
	if ( ! meta.length ) {
		return;
	}

	meta.forEach(
		function( element ) {
			var input     = element.querySelector( '.woostify-multi-select-value' ),
				selection = element.querySelector( '.woostify-multi-select-selection' ),
				search    = selection.querySelector( '.woostify-multi-select-search' ),
				dropdown  = element.querySelector( '.woostify-multi-select-dropdown' );

			// Save value.
			var saveItem = function( echo ) {
				var selectionItem = selection.querySelectorAll( '.woostify-multi-select-id' );
				if ( ! selectionItem.length ) {
					input.value = 'default';
					return;
				}

				var data = [];
				selectionItem.forEach(
					function( ele ) {
						var saveId = ele.getAttribute( 'data-id' );

						if ( ! saveId || data.includes( saveId ) ) {
							return;
						}

						data.push( saveId );
					}
				);

				if ( true === echo ) {
					return data;
				}

				input.value = data.join( '|' );
			}

			// Remove item.
			var removeItem = function() {
				var selectionItem = selection.querySelectorAll( '.woostify-multi-select-id' );
				if ( ! selectionItem.length ) {
					return;
				}

				selectionItem.forEach(
					function( el ) {
						var selectedId   = el.getAttribute( 'data-id' ),
							removeButton = el.querySelector( '.woostify-multi-remove-id' );

						if ( ! removeButton ) {
							return;
						}

						removeButton.onclick = function() {
							if ( ! el.parentNode ) {
								return;
							}

							// Show dropdown item.
							element.classList.add( 'active' );

							// Remove class 'disabled' on dropdown item.
							var isThis = dropdown.querySelector( '[data-id="' + selectedId + '"]' );
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
			var addItem = function() {
				var dropdownItem = dropdown.querySelectorAll( '.woostify-multi-select-id' );
				if ( ! dropdownItem.length ) {
					return;
				}

				for ( var i = 0, j = dropdownItem.length; i < j; i++ ) {
					dropdownItem[i].onclick = function() {
						var t        = this,
							disabled = t.classList.contains( 'disabled' ),
							dataId   = t.getAttribute( 'data-id' );

						if ( disabled ) {
							return;
						}

						t.classList.add( 'disabled' );

						var currentId = '<span class="woostify-multi-select-id" data-id="' + dataId + '">' + t.innerHTML + '<i class="woostify-multi-remove-id dashicons dashicons-no-alt"></i></span>';

						selection.insertAdjacentHTML( 'afterbegin', currentId );

						// Save item.
						saveItem();

						// Remove item.
						removeItem();
					}
				}
			}

			// Show dropdown item.
			if ( search ) {
				search.addEventListener(
					'input',
					function() {
						var that        = this,
							nonce       = that.getAttribute( 'data-nonce' ),
							searchValue = that.value.trim(),
							searchName  = that.name, // Action name.
							request     = new Request(
								ajaxurl,
								{
									method: 'POST',
									body: 'action=' + searchName + '&security_nonce=' + nonce + '&keyword=' + searchValue,
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

						// Fetch API.
						woostifySearchDelay(
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
													var parser       = new DOMParser(),
														doc          = parser.parseFromString( r.data, 'text/html' ),
														ajaxDropdown = doc.querySelectorAll( '.woostify-multi-select-id' );

													if ( ajaxDropdown.length ) {
														ajaxDropdown.forEach(
															function( ajaxItem ) {
																var ajaxItemId = ajaxItem.getAttribute( 'data-id' ) || '',
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

				search.addEventListener(
					'focus',
					function( e ) {
						element.classList.add( 'active' );
						removeItem();
					}
				);
			}

			// Hide dropdown item.
			document.addEventListener(
				'click',
				function( e ) {
					var current  = e.target,
						isParent = current.closest( '.woostify-multi-selection' ),
						isRemove = current.classList.contains( 'woostify-multi-remove-id' ),
						isSelect = current.classList.contains( 'woostify-multi-select-id' ),
						isSearch = current.classList.contains( 'woostify-multi-select-search' );

					// Not same parent, remove active, return.
					if ( isParent != element ) {
						element.classList.remove( 'active' );

						return;
					}

					if ( selection === current || isRemove || isSelect || isSearch ) {
						return;
					}

					element.classList.remove( 'active' );

					return;
				}
			);
		}
	);
}

// Get all Prev element siblings.
var prevSiblings = function( target ) {
	var siblings = [],
		n        = target;

	while ( n = n.previousElementSibling ) {
		siblings.push( n );
	}

	return siblings;
}

// Get all Next element siblings.
var nextSiblings = function( target ) {
	var siblings = [],
		n        = target;

	while ( n = n.nextElementSibling ) {
		siblings.push( n );
	}

	return siblings;
}

// Get all element siblings.
var siblings = function( target ) {
	var prev = prevSiblings( target ) || [],
		next = nextSiblings( target ) || [];

	return prev.concat( next );
}

// Multi Dependency settings.
var woostifyMultiDependency = function() {
	var dep = document.querySelectorAll( '.woostify-multi-dependency' );
	if ( ! dep.length ) {
		return;
	}

	dep.forEach(
		function( element ) {
			var item = element.querySelectorAll( '.woostify-multi-dependency-item [name]' );
			if ( ! item.length ) {
				return;
			}

			item.forEach(
				function( ele, index ) {
					var parent      = ele.closest( '.woostify-multi-dependency-item' ),
						parentStart = parent.getAttribute( 'data-dependency' );

					if ( ! parentStart ) {
						return;
					}

					ele.addEventListener(
						'change',
						function() {
							var currentValue  = ele.value,
								currentChild  = parseInt( index ) + 1,
								allDependency = element.querySelectorAll( '.woostify-multi-dependency-item:nth-child(' + currentChild + ') ~ .woostify-multi-dependency-item[data-required="' + currentValue + '"]' ),
								allNext       = siblings( parent );

							// First, remove all child if not equal value.
							if ( allNext.length ) {
								allNext.forEach(
									function( en ) {
										var nextRequired = en.getAttribute( 'data-required' );
										if ( ! nextRequired ) {
											return;
										}

										en.classList.remove( 'active' );
									}
								);
							}

							// Then, update child if equal value.
							if ( allDependency.length ) {
								allDependency.forEach(
									function( el ) {
										var required = el.getAttribute( 'data-required' ),
											updated  = el.getAttribute( 'data-value' ),
											onChange = new Event( 'change' ),
											_name    = el.querySelector( '[name]' );

										_name.dispatchEvent( onChange );

										if ( 'checkbox' == ele.type ) {
											if ( ele.checked ) {
												el.classList.add( 'active' );
											} else {
												el.classList.remove( 'active' );
											}
										} else {
											if ( currentValue == required ) {
												el.classList.add( 'active' );
											} else {
												el.classList.remove( 'active' );
											}
										}
									}
								);
							}
						}
					);
				}
			);
		}
	);
}

// Update value for checkbox filed.
var woostifyUpdateValue = function() {
	var box = document.querySelector( '.woostify-featured-setting' );
	if ( ! box ) {
		return;
	}

	var field = box.querySelectorAll( '[type="checkbox"]' );
	if ( ! field.length ) {
		return;
	}

	field.forEach(
		function( el ) {
			el.onchange = function() {
				el.value = 1 == el.value ? 0 : 1;
			}
		}
	);
}

// COUNTDOWN URGENCY FEATURED SETTINGS.
var woostifyDependency = function() {
	var filter = document.querySelectorAll( '.woostify-filter-value' );
	if ( ! filter.length ) {
		return;
	}

	filter.forEach(
		function( fil ) {
			fil.addEventListener(
				'change',
				function() {
					var parent   = fil.closest( '.woostify-filter-item' ),
						siblings = nextSiblings( parent );

					if ( ! siblings.length || ! fil.value.trim() ) {
						return;
					}

					siblings.forEach(
						function( sib ) {
							var type = sib.getAttribute( 'data-type' );
							if ( ! type ) {
								return;
							}

							if ( 'checkbox' === fil.type ) {
								if ( fil.checked && type == fil.value  ) {
									sib.classList.remove( 'hidden' );
								} else {
									sib.classList.add( 'hidden' );
								}
							} else {
								if ( type.includes( fil.value )  ) {
									sib.classList.remove( 'hidden' );
								} else {
									sib.classList.add( 'hidden' );
								}
							}
						}
					);
				}
			);
		}
	);
}

// SECTION TAB SETTINGS.
var woostifyTabSettings = function() {
	var section = document.querySelector( '.woostify-settings-section-tab' );
	if ( ! section ) {
		return;
	}

	var button = section.querySelectorAll( '.tab-head-button' );
	if ( ! button.length ) {
		return;
	}

	button.forEach(
		function( element ) {
			element.onclick = function() {
				var id          = element.hash ? element.hash.substr( 1 ) : '',
					idSiblings  = siblings( element ),
					tab         = section.querySelector( '.woostify-setting-tab-content[data-tab="' + id + '"]' ),
					tabSiblings = siblings( tab );

				// Active current tab heading.
				element.classList.add( 'active' );
				if ( idSiblings.length ) {
					idSiblings.forEach(
						function( el ) {
							el.classList.remove( 'active' );
						}
					);
				}

				// Active current tab content.
				tab.classList.add( 'active' );
				if ( tabSiblings.length ) {
					tabSiblings.forEach(
						function( el ) {
							el.classList.remove( 'active' );
						}
					);
				}
			}
		}
	);

	// Trigger first click. Active tab.
	window.addEventListener(
		'load',
		function() {
			var currentTab = section.querySelector( 'a[href="' + window.location.hash + '"]' ),
				generalTab = section.querySelector( 'a[href="#general"]' );

			if ( currentTab ) {
				currentTab.click();
			} else if ( generalTab ) {
				generalTab.click();
			}
		}
	);
}

// AJAX SAVE OPTIONS.
var woostifySaveOptions = function() {
	var box = document.querySelector( '.woostify-featured-setting' );
	if ( ! box ) {
		return;
	}

	var _id    = box.getAttribute( 'data-id' ) || '',
		_nonce = box.getAttribute( 'data-nonce' ) || '',
		button = box.querySelector( '.save-options' );

	if ( ! button || ! _id || ! _nonce ) {
		return;
	}

	button.onclick = function() {
		var t        = this,
			likeId   = _id.replace( /-/g, '_' ), // Same id but replare '-' to '_'.
			options  = box.querySelectorAll( '[name*=woostify_' + likeId + ']' ),
			value    = {},
			required = box.querySelectorAll( '[required="required"]' ),
			isEmpty  = false;

		// For Ajax search setting only.
		if ( box.classList.contains( 'woostify-ajax-search-product-setting' ) ) {
			var mustChooseOption = box.querySelector( '.must-choose-one-option' ),
				checkboxInput    = mustChooseOption ? mustChooseOption.querySelector( '[type="checkbox"]' ) : false,
				checkedOption    = mustChooseOption ? mustChooseOption.querySelectorAll( '[type="checkbox"]:checked' ) : [];

			if ( ! checkedOption.length && checkboxInput ) {
				checkboxInput.focus();

				return;
			}
		}

		// Required field must not be empty.
		if ( required.length ) {
			required.forEach(
				function( field ) {
					var fieldValue  = field.value.trim(),
						fieldParent = field.closest( '.woostify-setting-tab-content' ),
						isHidden    = field.closest( '.woostify-filter-item.hidden' ),
						fieldInTab  = fieldParent ? fieldParent.getAttribute( 'data-tab' ) : '';

					// If field in hidden setting. No need check.
					if ( isHidden ) {
						return;
					}

					if ( ! fieldValue ) {
						// Focus tab have required empty field.
						var emptyTab = box.querySelector( 'a[href="#' + fieldInTab + '"]' );
						if ( emptyTab && ! emptyTab.classList.contains( 'active' ) ) {
							emptyTab.click();
						}

						// Focus required empty field.
						isEmpty = true;
						field.focus();

						return;
					}
				}
			);
		}

		// If has required field empty, return.
		if ( isEmpty ) {
			return;
		}

		// Loading animation.
		box.classList.add( 'loading' );
		t.setAttribute( 'disabled', 'disabled' );
		t.innerHTML = woostify_edit_screen.saving + '...';

		// Get all value.
		if ( options.length ) {
			options.forEach(
				function( el, index ) {
					if ( ! el.value.trim() ) {
						return;
					}

					if ( el.name.includes( '[]' ) ) {
						value[ el.name + '[' + index + ']' ] = el.value;
					} else {
						value[ el.name ] = el.value;
					}
				}
			);
		}

		// Request.
		var request = new Request(
			ajaxurl,
			{
				method: 'POST',
				body: 'action=woostify_save_' + likeId + '_options&security_nonce=' + _nonce + '&setting_id=' + _id + '&options=' + JSON.stringify( value ),
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
				}
			).catch(
				function( err ) {
					console.log( err );
				}
			).finally(
				function() {
					box.classList.remove( 'loading' );

					// Update button html.
					t.innerHTML = woostify_edit_screen.saved;
					setTimeout(
						function() {
							t.innerHTML = woostify_edit_screen.save;
							t.removeAttribute( 'disabled' );
						},
						500
					);
				}
			);
	}
}

// SALE NOTIFICATION SETTINGS.
// Remove message.
var woostifySaleNotificationRemoveMessage = function() {
	var messages = document.querySelector( '.woostify-sale-notification-box-message' );

	if ( ! messages ) {
		return;
	}

	var removeMessageButton = messages.querySelectorAll( '.woostify-sale-notification-remove-message' );
	if ( ! removeMessageButton.length ) {
		return;
	}

	removeMessageButton.forEach(
		function( element ) {
			element.onclick = function() {
				var t        = this,
					disabled = t.getAttribute( 'disabled' ) || '';

				if ( 'disabled' === disabled ) {
					return;
				}

				t.parentNode.remove();

				if ( messages.querySelectorAll( '.woostify-sale-notification-remove-message' ).length <= 1 ) {
					var lastEvent = new Event( 'lastMessage' );
					document.documentElement.dispatchEvent( lastEvent );
				}
			}
		}
	);

	// Set disaled attribute if only one message available.
	document.documentElement.addEventListener(
		'lastMessage',
		function() {
			var lastMessage = messages.querySelector( '.woostify-sale-notification-remove-message' );
			if ( ! lastMessage ) {
				return;
			}

			lastMessage.setAttribute( 'disabled', 'disabled' );
		}
	);
}

// Add new message.
var woostifySaleNotificationAddNewMessage = function() {
	var box          = document.querySelector( '.woostify-featured-setting' ),
		messages     = box ? document.querySelector( '.woostify-sale-notification-box-message' ) : false,
		firstMessage = messages ? messages.querySelector( '.woostify-sale-notification-message-inner' ) : '',
		removeLabel  = firstMessage ? firstMessage.querySelector( '.woostify-sale-notification-remove-message' ).innerHTML : '',

		// Markup message html.
		markupMessage  = '<div class="woostify-sale-notification-message-inner">';
		markupMessage += '<textarea name="woostify_sale_notification_message[]" required="required"></textarea>';
		markupMessage += '<span class="woostify-sale-notification-remove-message button">' + removeLabel + '</span>';
		markupMessage += '</div>';

	if ( ! box || ! messages || ! firstMessage ) {
		return;
	}

	var addNewButton = box.querySelector( '.woostify-sale-notification-add-message' );
	if ( ! addNewButton ) {
		return;
	}

	addNewButton.onclick = function() {
		// Insert markup message html.
		messages.insertAdjacentHTML( 'beforeend', markupMessage );

		// Run remove message script.
		woostifySaleNotificationRemoveMessage();

		// Focus last message added.
		var lastMessage = messages.querySelector( '.woostify-sale-notification-message-inner:last-of-type' ),
			lastField   = lastMessage ? lastMessage.querySelector( 'textarea' ) : false;

		if ( lastField ) {
			lastField.focus();
		}

		// Remove disabled attribute for remove button.
		var removeDisabledAttr = messages.querySelector( '.woostify-sale-notification-remove-message[disabled="disabled"]' );
		if ( removeDisabledAttr ) {
			removeDisabledAttr.removeAttribute( 'disabled' );
		}
	}
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		woostifyMultiSelect();
		woostifyDependency();
		woostifyMultiDependency();
		woostifyUpdateValue();
		woostifyTabSettings();
		woostifySaveOptions();
		woostifySaleNotificationAddNewMessage();
		woostifySaleNotificationRemoveMessage();
	}
);
