/**
 * Elementor product filter
 *
 * @package Woostify Pro
 */

/* global woostify_product_filter, woostify_datepicker_data */

'use strict';

// Search delay.
const woostifyFilterSearchDelay = function() {
	let timer = ( arguments.length > 0 && undefined !== arguments[0] ) ? arguments[0] : 0;

	return function( callback, ms ) {
		clearTimeout( timer );
		timer = setTimeout( callback, ms );
	};
}();

// Date picker.
const woostifyFilterDatePicker = function() {
	let dateRangeFilter = document.querySelector( '.w-product-filter-type-date-range' );
	if ( ! dateRangeFilter ) {
		return;
	}

	let options = {
		mode: 'dp-below'
	}

	if ( 'undefined' !== typeof( woostify_datepicker_data ) ) {
		options.lang        = {};
		options.lang.months = woostify_datepicker_data.months;
		options.lang.days   = woostify_datepicker_data.days;
		options.lang.today  = woostify_datepicker_data.today;
		options.lang.clear  = woostify_datepicker_data.clear;
		options.lang.close  = woostify_datepicker_data.close;
	}

	// Setup datepicker.
	let field = dateRangeFilter.querySelectorAll( '.w-filter-date-picker' );
	for ( let i = 0, j = field.length; i < j; i++ ) {
		let datePicker;
		if ( 'object' === datePicker ) {
			return;
		}

		datePicker = TinyDatePicker( field[i], options );
	}
}

// Filter.
const woostifyAjaxFilter = function() {
	let filter  = document.querySelectorAll( '.w-product-filter[data-type]' ),
		content = document.querySelector( '.w-result-filter' );

	if ( ! content ) {
		return;
	}

	let filterKey      = content.querySelector( '.w-filter-key' ),
		products       = content.querySelector( '.products' ),
		adminBar       = document.getElementById( 'wpadminbar' ),
		adminBarHeight = adminBar ? adminBar.offsetHeight : 0,
		dataFilter     = {},
		pagedVar       = 1,
		event          = new CustomEvent( 'filtered', { detail: true } );

	// Pagination.
	const productPagination = function() {
		let pagiList = content.querySelectorAll( '.woocommerce-pagination .page-numbers a.page-numbers' );
		if ( ! pagiList.length ) {
			return;
		}

		for ( let p = 0, g = pagiList.length; p < g; p++ ) {
			pagiList[p].onclick = function( e ) {
				e.preventDefault();

				let currentItem = content.querySelector( '.woocommerce-pagination .page-numbers .page-numbers.current' ),
					prevItem    = pagiList[p].classList.contains( 'prev' ),
					nextItem    = pagiList[p].classList.contains( 'next' ),
					paged       = 1;

				if ( prevItem && currentItem ) {
					paged = Number( currentItem.innerText ) - 1;
				}

				if ( nextItem && currentItem ) {
					paged = Number( currentItem.innerText ) + 1;
				}

				if ( ! prevItem && ! nextItem ) {
					paged = Number( pagiList[p].innerText );
				}

				pagedVar = paged;
				document.body.dispatchEvent( new CustomEvent( 'filtered', { detail: false } ) );
			}
		}
	}
	productPagination();

	// Filter data.
	if ( filter.length ) {
		filter.forEach(
			function( fi ) {
				let type   = fi.getAttribute( 'data-type' ),
					source = fi.getAttribute( 'data-source' ) || type;

				switch ( type ) {
					case 'date-range':
						let dateFrom   = fi.querySelector( '[data-from]' ),
							dateTo     = fi.querySelector( '[data-to]' ),
							dateSubmit = fi.querySelector( '.w-filter-item-submit' );

						if ( ! dateFrom || ! dateTo || ! dateSubmit ) {
							return;
						}

						dateSubmit.onclick = function() {
							if ( ! dateFrom.value || ! dateTo.value ) {
								return;
							}

							let date = [];

							date[0] = dateFrom.value;
							date[1] = dateTo.value;

							dataFilter.date_query = date;

							document.body.dispatchEvent( event );
						}

						break;
					case 'rating':
						let star = fi.querySelectorAll( '.w-filter-rating-star' );
						if ( ! star.length ) {
							break;
						}

						for ( let i = 0, j = star.length; i < j; i++ ) {
							star[i].onclick = function() {
								if ( star[i].classList.contains( 'selected' ) ) {
									return;
								}

								// Remove old active.
								let oldStar = fi.querySelector( '.w-filter-rating-star.selected' );
								if ( oldStar ) {
									oldStar.classList.remove( 'selected' );
								}

								// Add active.
								if ( ! fi.classList.contains( 'selected' ) ) {
									fi.classList.add( 'selected' );
								}

								star[i].classList.add( 'selected' );

								// Update object.
								dataFilter.rating = i + 1;

								document.body.dispatchEvent( event );
							}
						}
						break;
					case 'sort-order':
						let sortOrderField = fi.querySelector( '.w-product-filter-select-field' );
						if ( ! sortOrderField ) {
							return;
						}

						sortOrderField.onchange = function() {
							dataFilter[ source ] = sortOrderField.value;

							document.body.dispatchEvent( event );
						}
						break;
					case 'select':
						let selectField = fi.querySelector( '.w-product-filter-select-field' );
						if ( ! selectField ) {
							return;
						}

						selectField.onchange = function() {
							let selectFieldValue = Number( selectField.value );

							dataFilter[ source ] = selectFieldValue;

							document.body.dispatchEvent( event );
						}

						break;
					case 'radio':
						let radioField = fi.querySelectorAll( '[type="radio"]' );
						if ( ! radioField.length ) {
							return;
						}

						for ( let i = 0, j = radioField.length; i < j; i++ ) {
							radioField[i].onchange = function() {
								let radioFieldValue = Number( radioField[i].parentNode.getAttribute( 'data-id' ) );

								dataFilter[ source ] = radioFieldValue;

								document.body.dispatchEvent( event );
							}
						}

						break;
					case 'range-slider':
						if ( 'object' === typeof( fi.noUiSlider ) ) {
							return;
						}

						let from = Number( fi.getAttribute( 'data-from' ) ) || 0,
							to   = Number( fi.getAttribute( 'data-to' ) ) || 100;

						const slider = noUiSlider.create(
							fi,
							{
								tooltips: true,
								connect: true,
								start: [ from, to ],
								step: 1,
								range: {
									'min': from,
									'max': to
								},
								format: {
									from: function( value ) {
										return Math.round( value );
									},
									to: function( value ) {
										return Math.round( value );
									}
								}
							}
						);

						slider.on(
							'change',
							function( values ) {
								let rangSlider = 'range_slider' + source;

								dataFilter[ rangSlider ] = values;

								document.body.dispatchEvent( event );
							}
						);

						break;
					case 'check-range':
						let checkRangeInput = fi.querySelectorAll( '[type="checkbox"]' );
						if ( ! checkRangeInput.length ) {
							return;
						}

						for ( let i = 0, j = checkRangeInput.length; i < j; i++ ) {
							checkRangeInput[i].onclick = function() {
								let value      = checkRangeInput[i].parentNode.getAttribute( 'data-value' ),
									checkRange = 'check_range' + source;

								if ( ! window[ 'data_source_' + source ] ) {
									window[ 'data_source_' + source ] = [];
								}

								// For query filter.
								if ( window[ 'data_source_' + source ].includes( value ) ) {
									window[ 'data_source_' + source ] = window[ 'data_source_' + source ].filter(
										function( item ) {
											return item !== value;
										}
									);
								} else {
									window[ 'data_source_' + source ].push( value );
								}

								dataFilter[ checkRange ] = window[ 'data_source_' + source ];

								// Trigger.
								document.body.dispatchEvent( event );
							}
						}
						break;
					case 'checkbox':
						let checkList = fi.querySelectorAll( '[type="checkbox"]' );
						if ( ! checkList.length ) {
							return;
						}

						for ( let i = 0, j = checkList.length; i < j; i++ ) {
							checkList[i].onclick = function() {
								let id = Number( checkList[i].parentNode.getAttribute( 'data-id' ) );

								if ( ! window[ 'data_source_' + source ] ) {
									window[ 'data_source_' + source ] = [];
								}

								if ( window[ 'data_source_' + source ].includes( id ) ) {
									window[ 'data_source_' + source ] = window[ 'data_source_' + source ].filter(
										function( item ) {
											return item !== id;
										}
									);
								} else {
									window[ 'data_source_' + source ].push( id );
								}

								dataFilter[ source ] = window[ 'data_source_' + source ];

								// Trigger.
								document.body.dispatchEvent( event );
							}
						}
						break;
					case 'search':
						let searchField = fi.querySelector( '.w-product-filter-text-field' );
						if ( ! searchField ) {
							return;
						}

						searchField.oninput = function() {
							woostifyFilterSearchDelay(
								function() {
									let keyword   = searchField.value.trim(),
										prevValue = searchField.getAttribute( 'data-value' ) || '';
									if ( prevValue == keyword ) {
										return;
									}

									searchField.setAttribute( 'data-value', keyword );

									dataFilter.keyword = keyword;

									document.body.dispatchEvent( event );
								},
								500
							);
						}
						break;
				}
			}
		);
	}

	// Reset search field.
	const resetSearchField = function() {
		let element = document.querySelectorAll( '.w-product-filter-text-field' );
		if ( ! element.length ) {
			return;
		}

		element.forEach(
			function( el ) {
				el.value = '';
			}
		);
	}

	// Reset range slider.
	const resetRangeSlider = function() {
		let element = document.querySelectorAll( '.w-product-filter-type-range-slider' );
		if ( ! element.length ) {
			return;
		}

		element.forEach(
			function( el ) {
				if ( 'object' !== typeof( el.noUiSlider ) ) {
					return;
				}

				el.noUiSlider.set( el.noUiSlider.options.start );
			}
		);
	}

	// Reset date picker.
	const resetDatePicker = function() {
		let datePickerField = document.querySelectorAll( '.w-filter-date-picker' );
		if ( ! datePickerField.length ) {
			return;
		}

		datePickerField.forEach(
			function( el ) {
				el.value = '';
			}
		);
	}

	// Reset rating.
	const resetRating = function() {
		let element = document.querySelectorAll( '.w-product-filter-type-rating.selected .selected' );
		if ( ! element.length ) {
			return;
		}

		element.forEach(
			function( el ) {
				el.parentNode.classList.remove( 'selected' );
				el.classList.remove( 'selected' );
			}
		);
	}

	// Reset input field.
	const resetGeneralField = function() {
		let element = document.querySelectorAll( '.w-product-filter [type="checkbox"]:checked, .w-product-filter [type="radio"]:checked' );
		if ( ! element.length ) {
			return;
		}

		element.forEach(
			function( el ) {
				el.checked = false;
			}
		);
	}

	document.body.addEventListener(
		'filtered',
		function( e ) {
			// Scroll to top content.
			let bodyOffsetTop    = document.body.getBoundingClientRect().top,
				contentOffsetTop = content.getBoundingClientRect().top,
				scrToTop         = ( -1 * bodyOffsetTop ) - ( -1 * contentOffsetTop ) - adminBarHeight;
			if ( contentOffsetTop < adminBarHeight ) {
				window.scrollTo( { top: scrToTop, behavior: 'smooth' } );
			}

			// Reset pagination number.
			if ( e.detail ) {
				pagedVar = 1;
			}

			// Add animation.
			content.classList.add( 'filter-updating' );
			if ( filter.length ) {
				filter.forEach(
					function( fis ) {
						fis.classList.add( 'filter-updating' );
					}
				);
			}

			// Args.
			let args = {
				action: 'woostify_product_filter',
				ajax_nonce: woostify_product_filter.ajax_nonce,
				per_page: Number( content.getAttribute( 'data-posts' ) ),
				paged: pagedVar,
				data: JSON.stringify( dataFilter )
			};

			args = new URLSearchParams( args ).toString();

			// Request.
			let request = new Request(
				woostify_product_filter.ajax_url,
				{
					method: 'POST',
					body: args,
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
							throw res;
						}

						return res.json();
					}
				).then(
					function( json ) {
						if ( ! json.success ) {
							return;
						}

						let r          = json.data,
							products   = content.querySelector( '.products' ),
							pagination = content.querySelector( '.woocommerce-pagination' );

						// Products.
						if ( products ) {
							products.innerHTML = r.content;
						}

						// Pagination.
						if ( pagination ) {
							if ( r.pagination  ) {
								pagination.innerHTML = r.pagination;
							} else {
								pagination.innerHTML = '';
							}
						}

						// Filtered key.
						if ( filterKey ) {
							filterKey.innerHTML = r.filtered;
						}
					}
				).catch(
					function( err ) {
						console.log( err );
					}
				).finally(
					function() {
						// Remove animation.
						content.classList.remove( 'filter-updating' );
						if ( filter.length ) {
							filter.forEach(
								function( fis ) {
									fis.classList.remove( 'filter-updating' );
								}
							);
						}

						// Remove filter key.
						let filterRemove = filterKey ? filterKey.querySelectorAll( '.w-filter-key-remove' ) : [];
						if ( filterRemove.length ) {
							for ( let f = 0, s = filterRemove.length; f < s; f++ ) {
								filterRemove[f].onclick = function() {
									let filteredType   = filterRemove[f].getAttribute( 'data-type' ),
										filteredValue  = filterRemove[f].getAttribute( 'data-value' ),
										filteredSource = filterRemove[f].getAttribute( 'data-source' );

									if ( ! filteredType ) {
										return;
									}

									switch ( filteredType ) {
										case 'clear':
											// Reset all data.
											dataFilter = {};

											let clearAllData = filterRemove[f].parentNode.querySelectorAll( '[data-source]' );
											if ( clearAllData.length ) {
												clearAllData.forEach(
													function( cls ) {
														let clsSource = cls.getAttribute( 'data-source' );
														if ( window[ 'data_source_' + clsSource ] ) {
															window[ 'data_source_' + clsSource ] = [];
														}
													}
												);
											}

											// Reset html state.
											resetSearchField();
											resetRangeSlider();
											resetDatePicker();
											resetRating();
											resetGeneralField();
											break;
										case 'search':
											resetSearchField();
											delete dataFilter.keyword;
											break;
										case 'date-range':
											resetDatePicker();
											delete dataFilter.date_query;
											break;
										case 'check-range':
											if ( ! filteredValue || ! filteredSource ) {
												return;
											}

											let checkRangeFiltered = dataFilter[ 'check_range' + filteredSource ];

											if ( 'undefined' !== checkRangeFiltered ) {
												// Remove current check range data.
												checkRangeValue = checkRangeFiltered.filter(
													function( checkr ) {
														return checkr !== filteredValue;
													}
												);

												dataFilter[ 'check_range' + filteredSource ] = checkRangeValue;

												// Remove checked status on checkbox input.
												let checkedRangeInput = document.querySelector( '[data-source="' + filteredSource + '"] [data-value="' + filteredValue + '"] [type="checkbox"]:checked' );
												if ( checkedRangeInput ) {
													checkedRangeInput.checked = false;
												}
											}
											break;
										case 'range-slider':
											resetRangeSlider();
											if ( filteredSource ) {
												delete dataFilter[ 'range_slider' + filteredSource ];
											}
											break;
										case 'rating':
											resetRating();
											delete dataFilter.rating;
											break;
										case 'terms':
											let filteredId  = Number( filterRemove[f].getAttribute( 'data-id' ) ),
												isMultiTerm = filterRemove[f].getAttribute( 'data-multi' ),
												checkedTerm = document.querySelector( '[data-source="' + filteredSource + '"] [data-id="' + filteredId + '"] [type="radio"]:checked' );

											if ( isMultiTerm ) {
												let checkListFiltered = dataFilter[ filteredSource ];

												if ( 'undefined' !== checkListFiltered ) {
													// Remove current check list ID.
													if ( window[ 'data_source_' + filteredSource ] ) {
														window[ 'data_source_' + filteredSource ] = checkListFiltered.filter(
															function( checkl ) {
																return checkl !== filteredId;
															}
														);

														dataFilter[ filteredSource ] = window[ 'data_source_' + filteredSource ];
													}

													// Remove checked status on checkbox input.
													let checkedListInput = document.querySelector( '[data-source="' + filteredSource + '"] [data-id="' + filteredId + '"] [type="checkbox"]:checked' );
													if ( checkedListInput ) {
														checkedListInput.checked = false;
													}
												}
											} else {
												// Reset data.
												delete dataFilter[ filteredSource ];

												// Reset html state.
												if ( checkedTerm ) {
													checkedTerm.checked = false;
												}
											}
											break;
									}

									document.body.dispatchEvent( event );
								}
							}
						}

						// Re-init pagination.
						productPagination();

						// Re-init quick-view.
						if ( 'function' === typeof( woostifyQuickView ) ) {
							woostifyQuickView();
						}
					}
				);
		}
	);
}

// Toggle child term.
const woostifyToggleChildTerm = function() {
	let item = document.querySelectorAll( '.w-filter-item-cat.has-children' );
	if ( ! item.length ) {
		return;
	}

	item.forEach(
		function( el ) {
			let toggle = el.querySelector( '.toggle-child-cat' );
			if ( ! toggle ) {
				return;
			}

			toggle.onclick = function() {
				let inner = toggle.parentNode.querySelector( '.w-filter-item-inner' ),
					type  = toggle.getAttribute( 'data-toggle' );
				if ( ! inner ) {
					return;
				}

				if ( 'expand' === type ) {
					toggle.setAttribute( 'data-toggle', 'collapse' );
					toggle.innerText = '－';
					inner.classList.add( 'active' );
				} else {
					toggle.setAttribute( 'data-toggle', 'expand' );
					toggle.innerText = '＋';
					inner.classList.remove( 'active' );
				}
			}
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		// Frontend.
		woostifyAjaxFilter();
		woostifyFilterDatePicker();
		woostifyToggleChildTerm();

		// Preview.
		if ( 'function' === typeof( onElementorLoaded ) ) {
			onElementorLoaded(
				function() {
					// Date picker init.
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/woostify-filter-date-range.default',
						function() {
							woostifyFilterDatePicker();
						}
					);

					// Range slider init.
					window.elementorFrontend.hooks.addAction(
						'frontend/element_ready/woostify-filter-range-slider.default',
						function() {
							let rangeSlider = document.querySelectorAll( '.w-product-filter-type-range-slider' );
							if ( rangeSlider.length ) {
								rangeSlider.forEach(
									function( rs ) {
										if ( 'object' === typeof( rs.noUiSlider ) ) {
											return;
										}

										let from = Number( rs.getAttribute( 'data-from' ) ) || 0,
											to   = Number( rs.getAttribute( 'data-to' ) ) || 100;

										const slider = noUiSlider.create(
											rs,
											{
												tooltips: true,
												connect: true,
												start: [ from, to ],
												step: 1,
												range: {
													'min': from,
													'max': to
												},
												format: {
													from: function( value ) {
														return Math.round( value );
													},
													to: function( value ) {
														return Math.round( value );
													}
												}
											}
										);
									}
								);
							}
						}
					);
				}
			);
		}
	}
);
