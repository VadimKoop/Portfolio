/**
 * Editor script
 *
 * @package woostify
 */

/* global woostify_woo_builder_editor */

'use strict';

// Set delay time when user typing.
var woostifySearchDelay = function( timer ) {
	if ( 'undefined' === typeof( timer ) ) {
		timer = 0;
	}

	return function( callback, ms ) {
		clearTimeout( timer );
		timer = setTimeout( callback, ms );
	};
}();

// Select product preview.
var woostifySelectProductPreview = function( id ) {
	NProgress.start();

	// Request.
	var data = {
		action: 'woo_builder_select_product_preview',
		ajax_nonce: woostify_woo_builder_editor.ajax_nonce,
		post_id: woostify_woo_builder_editor.post_id,
		selected_id: id
	};

	data = new URLSearchParams( data ).toString();

	var request = new Request(
		woostify_woo_builder_editor.ajax_url,
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
					throw res;
				}

				return res.json();
			}
		).then(
			function( json ) {
				if ( ! json.success ) {
					return;
				}
			}
		).catch(
			function( err ) {
				console.log( err );
			}
		).finally(
			function() {
				if ( 'undefined' !== typeof( elementor ) ) {
					elementor.reloadPreview();
				}

				NProgress.done();
			}
		);
}

// WooBuilder preview.
var woostifyWooBuilderPreview = function() {
	var elementorTool = document.getElementById( 'elementor-panel-footer-tools' );
	if ( ! elementorTool ) {
		return;
	}

	// Preview status.
	window.wooBuilderOpen = null;

	// Preview action.
	var preview = elementorTool.querySelector( '#elementor-panel-footer-saver-preview' );
	if ( preview && woostify_woo_builder_editor.is_product_template ) {
		// Override elementor preview ID.
		preview.setAttribute( 'id', 'woostify-tool-preview-button' );

		var previewButton = elementorTool.querySelector( '#elementor-panel-footer-saver-preview-label' );
		if ( previewButton ) {
			var previewSettings = '';

			previewSettings += '<div class="woostify-preview-settings-wrap">';
			previewSettings += '<div class="woostify-preview-settings-inner">';
			previewSettings += '<div class="woostify-preview-search">';
			previewSettings += '<div class="woostify-preview-search-result"></div>';
			previewSettings += '<input class="woostify-preview-search-field" placeholder="' + woostify_woo_builder_editor.search_placeholder + '">';
			previewSettings += '</div>';
			previewSettings += '<span class="woostify-preview-value" data-url>' + woostify_woo_builder_editor.select_preview + '</span>';
			previewSettings += '<span class="woostify-preview-button">' + woostify_woo_builder_editor.preview_text + '</span>';
			previewSettings += '</div>';
			previewSettings += '</div>';

			previewButton.insertAdjacentHTML( 'afterend', previewSettings );

			var selectProductPreviewState = 0,
				previewButtonState        = 0;

			previewButton.onclick = function( e ) {
				e.preventDefault();

				if ( 0 == previewButtonState ) {
					previewButtonState = 1;
					document.body.classList.add( 'hide-elementor-tooltip' );
					previewButton.parentNode.classList.add( 'elementor-open' );
				} else {
					previewButtonState = 0;
					document.body.classList.remove( 'hide-elementor-tooltip' );
					previewButton.parentNode.classList.remove( 'elementor-open' );
				}

				var wrapper             = elementorTool.querySelector( '.woostify-preview-settings-wrap' ),
					previewValue        = wrapper.querySelector( '.woostify-preview-value' ),
					previewSearch       = wrapper.querySelector( '.woostify-preview-search' ),
					previewSearchField  = wrapper.querySelector( '.woostify-preview-search-field' ),
					previewSearchResult = wrapper.querySelector( '.woostify-preview-search-result' ),
					previewBtn          = wrapper.querySelector( '.woostify-preview-button' );

				// Search product preview button.
				if ( previewValue ) {
					previewValue.onclick = function() {
						if ( previewSearch ) {
							if ( 0 == selectProductPreviewState ) {
								selectProductPreviewState = 1;
								previewSearch.classList.add( 'active' );
							} else {
								selectProductPreviewState = 0;
								previewSearch.classList.remove( 'active' );
							}
						}

						if ( previewSearchField ) {
							previewSearchField.focus();

							previewSearchField.oninput = function() {
								var searchFieldValue = previewSearchField.value.trim();
								if ( ! searchFieldValue || ! previewSearchResult ) {
									previewSearchResult.innerHTML = '';

									return;
								}

								woostifySearchDelay(
									function() {
										if ( previewSearchResult ) {
											previewSearchResult.innerHTML = '<span class="result-item">' + woostify_woo_builder_editor.searching_text + '</span>';
										}

										// Request.
										var data = {
											action: 'woo_builder_preview_search_data',
											ajax_nonce: woostify_woo_builder_editor.ajax_nonce,
											post_id: woostify_woo_builder_editor.post_id,
											keyword: searchFieldValue
										};

										data = new URLSearchParams( data ).toString();

										var request = new Request(
											woostify_woo_builder_editor.ajax_url,
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
														throw res;
													}

													return res.json();
												}
											).then(
												function( json ) {
													if ( ! json.success ) {
														return;
													}

													if ( previewSearchResult ) {
														previewSearchResult.innerHTML = json.data.content;
													}
												}
											).catch(
												function( err ) {
													console.log( err );
												}
											).finally(
												function() {
													var searchResultItem = previewSearchResult ? previewSearchResult.querySelectorAll( '.result-item' ) : [];
													if ( searchResultItem.length ) {
														searchResultItem.forEach(
															function( resultItem ) {
																resultItem.onclick = function() {
																	var dataUrl = resultItem.getAttribute( 'data-url' );
																	if ( ! dataUrl || ! previewValue ) {
																		return;
																	}

																	previewValue.innerHTML = resultItem.innerHTML;
																	previewValue.setAttribute( 'data-url', dataUrl );

																	// Select product preview.
																	var selectedId = resultItem.getAttribute( 'data-id' );
																	if ( selectedId ) {
																		woostifySelectProductPreview( selectedId );
																	}

																	// Trigger click to remove active state.
																	previewValue.click();
																}
															}
														);
													}
												}
											);
									},
									500
								);
							}
						}
					}
				}

				// Preview button.
				if ( previewBtn ) {
					previewBtn.onclick = function() {
						var previewUrl = previewValue ? previewValue.getAttribute( 'data-url' ) : '';

						previewUrl = previewUrl ? previewUrl : woostify_woo_builder_editor.preview_url;
						if ( window.wooBuilderOpen && window.wooBuilderOpen.parent ) {
							if ( previewUrl === window.wooBuilderOpen.location.href ) {
								window.wooBuilderOpen.focus();
							} else {
								window.wooBuilderOpen = window.open( previewUrl, '_blank' );
							}
						} else {
							window.wooBuilderOpen = window.open( previewUrl, '_blank' );
						}
					}
				}
			}
		}
	}

	// Conditions action.
	var templateMenu = elementorTool.querySelector( '#elementor-panel-footer-sub-menu-item-save-template' );
	if ( templateMenu ) {
		// Insert custom conditions.
		templateMenu.insertAdjacentHTML( 'beforebegin', '<div id="woostify-tool-display-conditions" class="elementor-panel-footer-sub-menu-item"><i class="elementor-icon eicon-flow" aria-hidden="true"></i><div class="elementor-title">' + woostify_woo_builder_editor.condition_text + '</div></div>' );

		var elementorPreview = document.getElementById( 'elementor-preview' ),
			displayCondition = elementorTool.querySelector( '#woostify-tool-display-conditions' ),
			conditionsHtml   = document.getElementById( 'woostify-woobuilder-conditions-html' );

		if ( elementorPreview && conditionsHtml ) {
			elementorPreview.insertAdjacentHTML( 'afterend', conditionsHtml.innerHTML );
		}

		var conditionsPopup = document.querySelector( '.woostify-woobuilder-conditions' );

		if ( displayCondition && conditionsPopup ) {
			displayCondition.onclick = function( e ) {
				e.preventDefault();

				// Show popup.
				conditionsPopup.classList.add( 'active' );

				// Hide popup.
				var hidePopup = function() {
					conditionsPopup.classList.remove( 'active' );
				}

				// Close popup via overlay.
				conditionsPopup.onclick = function( e ) {
					if ( this !== e.target ) {
						return;
					}

					hidePopup();
				}

				// Close popup via button.
				var closeBtn = conditionsPopup.querySelector( '.woostify-woobuilder-conditions-close-btn' );
				if ( closeBtn ) {
					closeBtn.onclick = hidePopup;
				}

				// Close popup via ESC key.
				document.body.addEventListener(
					'keyup',
					function( e ) {
						if ( 27 !== e.keyCode ) {
							return;
						}

						hidePopup();
					}
				);

				// Add or Remove condition.
				var innerCondition   = conditionsPopup.querySelector( '.woostify-woobuilder-conditions-content-inner' ),
					fieldWrapper     = innerCondition ? innerCondition.querySelector( '.woostify-woobuilder-condition-item-wrapper' ) : false,
					addCondition     = innerCondition ? innerCondition.querySelector( '.woostify-condition-add-button' ) : false,
					removeConditions = function() {
						var removeCondition = document.querySelectorAll( '.woostify-woobuilder-conditions .woostify-condition-item-remove' );
						if ( ! removeCondition.length ) {
							return;
						}

						removeCondition.forEach(
							function( ele ) {
								ele.onclick = function() {
									var parentWrap = ele.parentNode;
									// Can not remove last item condition.
									if ( ! parentWrap.nextElementSibling && ! parentWrap.previousElementSibling ) {
										return;
									}

									parentWrap.remove();
								}
							}
						);
					};

				// Add conditions.
				removeConditions();

				if ( addCondition ) {
					var dataType = addCondition.getAttribute( 'data-type' ) || '';

					addCondition.onclick = function() {
						var fieldHtml = document.getElementById( 'woostify-woobuilder-conditions-' + dataType + '-html' );

						if ( fieldHtml && fieldWrapper ) {
							fieldWrapper.insertAdjacentHTML( 'beforeend', fieldHtml.innerHTML );
						}

						// Remove conditions.
						removeConditions();

						// Update condition field when new condition item added.
						conditionField();
					}
				}
			}
		}
	}

	// Elementor Save button - For first publish time.
	var elementorSaverButton = document.getElementById( 'elementor-panel-saver-button-publish' );
	if ( elementorSaverButton && 'publish' != woostify_woo_builder_editor.post_status ) {
		elementorSaverButton.insertAdjacentHTML( 'beforebegin', '<button id="woostify-panel-saver-button-publish" class="elementor-button elementor-button-success">' + elementorSaverButton.innerHTML + '</button>' );

		var woostifyButtonUpdate = document.getElementById( 'woostify-panel-saver-button-publish' );

		if ( ! woostifyButtonUpdate ) {
			return;
		}

		woostifyButtonUpdate.onclick = function( e ) {
			var t = this;

			if ( t.classList.contains( 'elementor-disabled' ) ) {
				return;
			}

			// Show conditions popup.
			var woostifyShowConditions = document.getElementById( 'woostify-tool-display-conditions' );
			if ( woostifyShowConditions ) {
				woostifyShowConditions.click();
			}
		}
	}

	// Select item type.
	var conditionField = function() {
		if ( ! conditionsPopup ) {
			return;
		}

		var conditionItem = conditionsPopup.querySelectorAll( '.woostify-condition-item' );
		if ( ! conditionItem.length ) {
			return;
		}

		conditionItem.forEach(
			function( item ) {
				// Item field.
				var itemField = item.querySelector( '.woostify-condition-item-field' ),
					itemView  = item.querySelector( '.woostify-condition-item-search-view' );
				if ( itemField ) {
					itemField.onchange = function() {
						if ( [ 'in-cat', 'in-tag' ].includes( itemField.value ) ) {
							item.classList.add( 'has-search-field' );
						} else {
							item.classList.remove( 'has-search-field' );

							if ( itemView ) {
								itemView.setAttribute( 'data-id', 'all' );
								itemView.innerHTML = woostify_woo_builder_editor.all_text;
							}
						}
					}
				}

				// Search wrap.
				var searchWrap = item.querySelector( '.woostify-condition-item-search' );
				if ( searchWrap ) {
					var searchField = searchWrap.querySelector( '.woostify-condition-item-search-field' ),
						searchState = 0;

					searchWrap.onclick = function( e ) {
						var t       = this,
							tParent = t.closest( '.woostify-woobuilder-condition-item-wrapper' ),
							tSibs   = tParent ? tParent.querySelectorAll( '.woostify-condition-item-search.active' ) : [];

						if ( e.target === searchField ) {
							return;
						}

						if ( tSibs.length ) {
							tSibs.forEach(
								function( sibEle ) {
									if ( sibEle !== t ) {
										sibEle.classList.remove( 'active' );
									}
								}
							);
						}

						if ( 0 == searchState ) {
							t.classList.add( 'active' );
							searchState = 1;

							if ( searchField ) {
								searchField.focus();
							}
						} else {
							searchState = 0;
							t.classList.remove( 'active' );
						}

						document.onclick = function( e ) {
							var et = e.target;

							if ( et === t || et === searchField ) {
								return;
							}

							searchState = 0;
							searchWrap.classList.remove( 'active' );
						}
					}

					// Ajax with searching...
					if ( searchField ) {
						searchField.oninput = function() {
							var searchView       = searchWrap.querySelector( '.woostify-condition-item-search-view' ),
								searchFieldValue = searchField.value.trim(),
								result           = searchWrap.querySelector( '.woostify-condition-item-search-result' );
							if ( ! searchFieldValue || ! result ) {
								result.innerHTML = '';

								return;
							}

							woostifySearchDelay(
								function() {
									if ( result ) {
										result.innerHTML = '<span class="result-item">' + woostify_woo_builder_editor.searching_text + '</span>';
									}

									// Request.
									var data = {
										action: 'woo_builder_conditions_search_data',
										ajax_nonce: woostify_woo_builder_editor.ajax_nonce,
										post_id: woostify_woo_builder_editor.post_id,
										field_value: itemField.value,
										keyword: searchFieldValue
									};

									data = new URLSearchParams( data ).toString();

									var request = new Request(
										woostify_woo_builder_editor.ajax_url,
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
													throw res;
												}

												return res.json();
											}
										).then(
											function( json ) {
												if ( ! json.success ) {
													return;
												}

												var data = json.data;

												if ( result ) {
													result.innerHTML = data.content;
												}
											}
										).catch(
											function( err ) {
												console.log( err );
											}
										).finally(
											function() {
												var searchResultItem = result ? result.querySelectorAll( '.result-item' ) : [];
												if ( searchResultItem.length ) {
													searchResultItem.forEach(
														function( resultItem ) {
															resultItem.onclick = function() {
																var dataId = resultItem.getAttribute( 'data-id' );
																if ( ! dataId || ! searchView ) {
																	return;
																}

																searchView.innerHTML = resultItem.innerHTML;
																searchView.setAttribute( 'data-id', dataId );
															}
														}
													);
												}
											}
										);
								},
								500
							);
						}
					}
				}
			}
		);
	}
	conditionField();

	// Update conditions.
	var updateConditions = function() {
		if ( ! conditionsPopup ) {
			return;
		}

		var saveOptions = conditionsPopup.querySelector( '.save-options' );
		if ( ! saveOptions ) {
			return;
		}

		saveOptions.onclick = function() {
			var itemConds = conditionsPopup.querySelectorAll( '.woostify-condition-item' );
			if ( ! itemConds.length ) {
				return;
			}

			// Start animation.
			NProgress.start();

			// Close popup.
			conditionsPopup.click();

			// Elementor Save button.
			if ( elementorSaverButton ) {
				elementorSaverButton.classList.add( 'elementor-button-state' );
			}

			// Woostify Save button.
			var woostifyButtonUpdate = document.getElementById( 'woostify-panel-saver-button-publish' );
			if ( woostifyButtonUpdate ) {
				woostifyButtonUpdate.classList.add( 'disabled', 'elementor-button-state' );
			}

			// Conditions data.
			var dataCondition = [];

			itemConds.forEach(
				function( element, index ) {
					var conType  = element.querySelector( '.woostify-condition-item-type' ),
						conField = element.querySelector( '.woostify-condition-item-field' ),
						conView  = element.querySelector( '.woostify-condition-item-search-view' ),
						conId    = conView ? conView.getAttribute( 'data-id' ) : 'all';

					if ( ! conType || ! conField ) {
						return;
					}

					dataCondition.push(
						{
							data_type: conType.value,
							data_field: conField.value,
							data_id: conId
						}
					);
				}
			);

			// Request.
			var data = {
				action: 'save_woo_builder_conditions',
				ajax_nonce: woostify_woo_builder_editor.ajax_nonce,
				post_id: woostify_woo_builder_editor.post_id,
				conditions: JSON.stringify( dataCondition )
			};

			data = new URLSearchParams( data ).toString();

			var request = new Request(
				woostify_woo_builder_editor.ajax_url,
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
							throw res;
						}

						return res.json();
					}
				).then(
					function( json ) {
						if ( ! json.success ) {
							return;
						}
					}
				).catch(
					function( err ) {
						console.log( err );
					}
				).finally(
					function() {
						// Elementor Save button.
						if ( elementorSaverButton ) {
							elementorSaverButton.classList.remove( 'elementor-button-state' );
							elementorSaverButton.click();
						}

						// Woostify Save button.
						if ( woostifyButtonUpdate ) {
							woostifyButtonUpdate.classList.remove( 'elementor-button-state' );
						}

						// End animation.
						NProgress.done();
					}
				);
		}
	}
	updateConditions();

	// Update content.
	elementor.saver.on(
		'after:save',
		function( data ) {
			if ( window.wooBuilderOpen && window.wooBuilderOpen.parent ) {
				window.wooBuilderOpen.location.reload();
			}
		}
	);
}

window.addEventListener(
	'load',
	function() {
		woostifyWooBuilderPreview();
	}
);
