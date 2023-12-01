/**
 * Woostify Pro Customizer Controls
 *
 * @package Woostify Pro
 */

'use strict';

( function( api ) {

	api.bind( 'ready', function() {
		/**
		 * Condition controls
		 *
		 * @param      string       id            The setting id
		 * @param      array        dependencies  The dependencies control
		 * @param      string/bool  value         The value with dependencies control
		 */
		var condition = function( id, dependencies, value ) {
			var value = undefined !== arguments[2] ? arguments[2] : false;

			api( id, function( setting ) {
				function dependency( control ) {
					function visibility() {
						var compare = false;

						// Support array || string || boolean.
						if ( Array.isArray( value ) ) {
							compare = value.includes( setting.get() );
						} else {
							compare = value === setting.get();
						}

						if ( compare ) {
							control.container.removeClass( 'hide' );
						} else {
							control.container.addClass( 'hide' );
						}
					}

					// Set initial active state.
					visibility();

					// Update activate state whenever the setting is changed.
					setting.bind( visibility );
				};

				// Call dependency on the controls when they exist.
				for ( var i = 0, j = dependencies.length; i < j; i++ ) {
					api.control( dependencies[i], dependency );
				}
			} );
		}

		/**
		 * Condition controls.
		 *
		 * @param string  id            Setting id.
		 * @param array   dependencies  Setting id dependencies.
		 * @param string  value         Setting value.
		 * @param array   parentvalue   Parent setting id and value.
		 * @param boolean operator      Operator.
		 * @param array   arr           The parent setting value.
		 */
		var subCondition = function( id, dependencies, value, operator, arr ) {
			var value    = undefined !== arguments[2] ? arguments[2] : false,
				operator = undefined !== arguments[3] ? arguments[3] : false,
				arr      = undefined !== arguments[4] ? arguments[4] : false;

			api( id, function( setting ) {

				/**
				 * Update a control's active setting value.
				 *
				 * @param {api.Control} control
				 */
				var dependency = function( control ) {
					var visibility = function() {
						// arr[0] = control setting id.
						// arr[1] = control setting value.
						if ( ! arr || arr[1] !== wp.customize.control( arr[0] ).setting.get() ) {
							return;
						}

						if ( operator ) {
							if ( value === setting.get() ) {
								control.container.removeClass( 'hide' );
							} else {
								control.container.addClass( 'hide' );
							}
						} else {
							if ( value === setting.get() ) {
								control.container.addClass( 'hide' );
							} else {
								control.container.removeClass( 'hide' );
							}
						}
					}

					// Set initial active state.
					visibility();

					// Update activate state whenever the setting is changed.
					setting.bind( visibility );
				};

				// Call dependency on the setting controls when they exist.
				for ( var i = 0, j = dependencies.length; i < j; i++ ) {
					api.control( dependencies[i], dependency );
				}
			} );
		}

		// Quick view.
		condition(
			'woostify_pro_options[shop_page_quick_view_position]',
			[
				'woostify_pro_options[shop_product_quick_view_icon]',
			],
			[ 'center-image', 'bottom-image' ]
		);

		// Header Layout-2.
		condition(
			'woostify_setting[header_layout]',
			[
				'woostify_pro_options[header_full_width]',
				'after_header_full_width_divider',
			],
			'layout-1'
		);

		// Header Layout-3.
		condition(
			'woostify_setting[header_layout]',
			[
				'woostify_pro_options[header_left_content]',
				'after_header_left_content_divider',
			],
			'layout-3'
		);

		// Header Layout-5.
		condition(
			'woostify_setting[header_layout]',
			[
				'woostify_pro_options[header_center_content]',
				'after_header_center_content_divider',
			],
			'layout-5'
		);

		// Header Layout-6.
		condition(
			'woostify_setting[header_layout]',
			[
				'woostify_pro_options[header_right_content]',
				'woostify_pro_options[header_content_bottom_background]',
			],
			'layout-6'
		);

		// Header Layout-7.
		condition(
			'woostify_setting[header_layout]',
			[
				'woostify_pro_options[header_sidebar_content_bottom]',
			],
			'layout-7'
		);

		// Header Layout-8.
		condition(
			'woostify_setting[header_layout]',
			[
				'woostify_pro_options[header_8_right_content]',
				'woostify_pro_options[header_8_content_right_text_color]',
				'woostify_pro_options[header_8_search_bar_background]',
				'woostify_pro_options[header_8_button_background]',
				'woostify_pro_options[header_8_button_color]',
				'woostify_pro_options[header_8_button_hover_background]',
				'woostify_pro_options[header_8_button_hover_color]',
				'woostify_pro_options[header_8_icon_color]',
				'woostify_pro_options[header_8_icon_hover_color]',
				'header_layout_8_button_text_divider',
				'header_layout_8_button_heading_divider',
				'woostify_pro_options[header_8_button_text]',
				'woostify_pro_header_layout_8_end',
				'woostify_pro_header_layout_8_start_content_right',
			],
			'layout-8'
		);

		// Sticky header.
		condition(
			'woostify_pro_options[sticky_header_display]',
			[
				'woostify_pro_options[sticky_header_disable_archive]',
				'woostify_pro_options[sticky_header_disable_index]',
				'woostify_pro_options[sticky_header_disable_page]',
				'woostify_pro_options[sticky_header_disable_post]',
				'woostify_pro_options[sticky_header_disable_shop]',
				'sticky_header_background_color_divider',
				'woostify_pro_options[sticky_header_background_color]',
				'woostify_pro_options[sticky_header_enable_on]',
				'sticky_header_border_divider',
				'woostify_pro_options[sticky_header_border_width]',
				'woostify_pro_options[sticky_header_border_color]',
			],
			true
		);

		// Sticky add to cart button on mobile.
		condition(
			'woostify_pro_options[sticky_single_add_to_cart_button]',
			[
				'woostify_pro_options[sticky_atc_button_on]',
			],
			[ 'top', 'bottom' ]
		);

		// Button Buy Now.
		condition(
			'woostify_pro_options[shop_single_buy_now_button]',
			[
				'woostify_pro_options[shop_single_background_buynow]',
				'woostify_pro_options[shop_single_color_button_buynow]',
				'woostify_pro_options[shop_single_background_hover]',
				'woostify_pro_options[shop_single_color_hover]',
				'woostify_pro_options[shop_single_border_radius_buynow]',
			],
			true
		);
	} );

}( wp.customize ) );
