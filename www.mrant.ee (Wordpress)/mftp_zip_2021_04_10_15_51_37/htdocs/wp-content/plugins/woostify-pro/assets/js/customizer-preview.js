/**
 * Woostify Pro Customizer Preview
 *
 * @package Woostify Pro
 */

'use strict';

// Colors.
function woostify_pro_colors_live_update( id, selector, property ) {
	var setting = 'woostify_pro_options[' + id + ']';

	wp.customize(
		setting,
		function( value ) {
			value.bind(
				function( newval ) {
					if ( jQuery( 'style#' + id ).length ) {
						jQuery( 'style#' + id ).html( selector + '{' + property + ':' + newval + ';}' );
					} else {
						jQuery( 'head' ).append( '<style id="' + id + '">' + selector + '{' + property + ':' + newval + '}</style>' );

						setTimeout(
							function() {
								jQuery( 'style#' + id ).not( ':last' ).remove();
							},
							1000
						);
					}
				}
			);
		}
	);
}

// Update element class.
function woostify_pro_update_element_class( id, selector, prefix ) {
	var setting = 'woostify_pro_options[' + id + ']';

	wp.customize(
		setting,
		function( value ) {
			value.bind(
				function( newval ) {
					var newClass = '';
					switch ( newval ) {
						case true:
							newClass = prefix;
							break;
						case false:
							newClass = '';
							break;
						default:
							newClass = prefix + newval;
							break;
					}
					jQuery( selector ).removeClassPrefix( prefix ).addClass( newClass );
				}
			);
		}
	);
}

// Html.
function woostify_pro_html_live_update( id, selector ) {
	var setting = 'woostify_pro_options[' + id + ']';

	wp.customize(
		setting,
		function( value ) {
			value.bind(
				function( newval ) {
					var element = document.querySelectorAll( selector );
					if ( ! element.length ) {
						return;
					}

					element.forEach(
						function( ele ) {
							ele.innerHTML = newval;
						}
					);
				}
			);
		}
	);
}

// Units.
function woostify_pro_unit_live_update( id, selector, property, unit, fullId ) {
	var unit    = 'undefined' !== typeof( unit ) ? unit : 'px',
		setting = 'woostify_pro_options[' + id + ']';

	// Wordpress customize.
	wp.customize(
		setting,
		function( value ) {
			value.bind(
				function( newval ) {
					// Sometime 'unit' is not use.
					if ( ! unit ) {
						unit = '';
					}

					// Get style.
					var data = '';
					if ( Array.isArray( property ) ) {
						for ( var i = 0, j = property.length; i < j; i ++ ) {
							data += newval ? selector + '{' + property[i] + ': ' + newval + unit + '}' : '';
						}
					} else {
						data += newval ? selector + '{' + property + ': ' + newval + unit + '}' : '';
					}

					// Append style.
					if ( jQuery( 'style#' + id ).length ) {
						jQuery( 'style#' + id ).html( data );
					} else {
						jQuery( 'head' ).append( '<style id="' + id + '">' + data + '</style>' );

						setTimeout(
							function() {
								jQuery( 'style#' + id ).not( ':last' ).remove();
							},
							100
						);
					}
				}
			);
		}
	);
}

document.addEventListener(
	'DOMContentLoaded',
	function() {
		// UPDATE ELEMENT CLASS NAME.
		// Layout 1.
		woostify_pro_update_element_class( 'header_full_width', '.header-layout-1', 'header-full-width' );

		// HTML LIVE UPDATE.
		// Layout 3.
		woostify_pro_html_live_update( 'header_left_content', '.header-layout-3 .left-content' );
		// Layout 5.
		woostify_pro_html_live_update( 'header_center_content', '.header-layout-5 .center-content' );
		// Layout 8.
		woostify_pro_html_live_update( 'header_8_button_text', '.header-layout-8 .vertical-menu-button' );

		// COLOR LIVE UPDATE.
		// Layout 6.
		woostify_pro_colors_live_update( 'header_content_bottom_background', '.header-layout-6 .header-content-bottom', 'background-color' );
		// Layout 8.
		woostify_pro_colors_live_update( 'header_8_search_bar_background', '.header-layout-8 .header-content-bottom', 'background-color' );
		woostify_pro_colors_live_update( 'header_8_button_background', '.header-layout-8 .vertical-menu-wrapper .vertical-menu-button', 'background-color' );
		woostify_pro_colors_live_update( 'header_8_button_color', '.header-layout-8 .vertical-menu-wrapper .vertical-menu-button', 'color' );
		woostify_pro_colors_live_update( 'header_8_button_hover_background', '.header-layout-8 .vertical-menu-wrapper .vertical-menu-button:hover', 'background-color' );
		woostify_pro_colors_live_update( 'header_8_button_hover_color', '.header-layout-8 .vertical-menu-wrapper .vertical-menu-button:hover', 'color' );
		woostify_pro_colors_live_update( 'header_8_icon_color', '.header-layout-8 .woostify-total-price, .header-layout-8 .tools-icon', 'color' );
		woostify_pro_colors_live_update( 'header_8_icon_hover_color', '.site-header.header-layout-8 .tools-icon:hover, .header-layout-8 .tools-icon.my-account:hover > a', 'color' );
		woostify_pro_colors_live_update( 'header_8_content_right_text_color', '.header-layout-8 .content-top-right *', 'color' );

		// STICKY HEADER.
		woostify_pro_colors_live_update( 'sticky_header_background_color', '.has-sticky-header .site-header-inner.fixed', 'background-color' );
		woostify_pro_colors_live_update( 'sticky_header_border_color', '.has-sticky-header .site-header-inner.fixed', 'border-bottom-color' );

		// Quickview Hover.
		woostify_pro_colors_live_update( 'shop_product_quick_view_bg_hover', '.quick-view-with-text.product-quick-view-btn:hover, .product-loop-action .quick-view-with-icon:hover', 'background-color' );
		woostify_pro_colors_live_update( 'shop_product_quick_view_c_hover', '.quick-view-with-text.product-quick-view-btn:hover, .product-loop-action .quick-view-with-icon:hover', 'color' );

		// Quickview.
		woostify_pro_colors_live_update( 'shop_product_quick_view_background', '.quick-view-with-text.product-quick-view-btn, .product-loop-action .quick-view-with-icon', 'background-color' );
		woostify_pro_colors_live_update( 'shop_product_quick_view_color', '.quick-view-with-text.product-quick-view-btn, .product-loop-action .quick-view-with-icon', 'color' );

		// Button Buy Now Hover.
		woostify_pro_colors_live_update( 'shop_single_background_hover', '.woostify-buy-now.button:hover', 'background-color' );
		woostify_pro_colors_live_update( 'shop_single_color_hover', '.woostify-buy-now.button:hover', 'color' );

		// Button Buy Now.
		woostify_pro_colors_live_update( 'shop_single_background_buynow', '.woostify-buy-now.button', 'background-color' );
		woostify_pro_colors_live_update( 'shop_single_color_button_buynow', '.woostify-buy-now.button', 'color' );

		// UNIT LIVE UPDATE.
		woostify_pro_unit_live_update( 'sticky_header_border_width', '.has-sticky-header .site-header-inner.fixed', 'border-bottom-width', 'px' );
		woostify_pro_unit_live_update( 'shop_single_border_radius_buynow', '.woostify-buy-now.button', 'border-radius', 'px' );
		woostify_pro_unit_live_update( 'shop_product_quick_view_radius', '.quick-view-with-text.product-quick-view-btn', 'border-radius', 'px' );
		woostify_pro_unit_live_update( 'shop_product_quick_view_radius', '.product-quick-view-btn.quick-view-with-icon, .product-loop-action .quick-view-with-icon', 'border-radius', 'px' );
	}
);
