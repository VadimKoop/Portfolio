<?php
/**
 * Woocommerce shop single customizer
 *
 * @package Woostify Pro
 */

// Default values.
$defaults = Woostify_Pro::get_instance()->default_options_value();

// SINGLE BUY NOW BUTTON.
if ( defined( 'WOOSTIFY_PRO_BUY_NOW_BUTTON' ) ) {
	// SHOP SINGLE STRUCTURE SECTION.
	$wp_customize->add_setting(
		'shop_single_addon_section',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Woostify_Section_Control(
			$wp_customize,
			'shop_single_addon_section',
			array(
				'label'      => __( 'Button Buy Now', 'woostify-pro' ),
				'section'    => 'woostify_shop_single',
				'dependency' => array(
					'woostify_pro_options[shop_single_buy_now_button]',
					'woostify_pro_options[shop_single_background_hover]',
					'woostify_pro_options[shop_single_color_hover]',
					'woostify_pro_options[shop_single_background_buynow]',
					'woostify_pro_options[shop_single_color_button_buynow]',
					'woostify_pro_options[shop_single_border_radius_buynow]',
				),
			)
		)
	);

	$wp_customize->add_setting(
		'woostify_pro_options[shop_single_buy_now_button]',
		array(
			'default'           => $defaults['shop_single_buy_now_button'],
			'type'              => 'option',
			'sanitize_callback' => 'woostify_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		new Woostify_Switch_Control(
			$wp_customize,
			'woostify_pro_options[shop_single_buy_now_button]',
			array(
				'label'    => __( 'Add Buy Now Button', 'woostify-pro' ),
				'settings' => 'woostify_pro_options[shop_single_buy_now_button]',
				'section'  => 'woostify_shop_single',
			)
		)
	);

	// Button Background.
	$wp_customize->add_setting(
		'woostify_pro_options[shop_single_background_buynow]',
		array(
			'default'           => $defaults['shop_single_background_buynow'],
			'type'              => 'option',
			'sanitize_callback' => 'woostify_sanitize_rgba_color',
			'transport'         => 'postMessage',

		)
	);

	$wp_customize->add_control(
		new Woostify_Color_Control(
			$wp_customize,
			'woostify_pro_options[shop_single_background_buynow]',
			array(
				'label'    => __( 'Background', 'woostify-pro' ),
				'section'  => 'woostify_shop_single',
				'settings' => 'woostify_pro_options[shop_single_background_buynow]',
			)
		)
	);

	// Button Color.
	$wp_customize->add_setting(
		'woostify_pro_options[shop_single_color_button_buynow]',
		array(
			'default'           => $defaults['shop_single_color_button_buynow'],
			'type'              => 'option',
			'sanitize_callback' => 'woostify_sanitize_rgba_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Woostify_Color_Control(
			$wp_customize,
			'woostify_pro_options[shop_single_color_button_buynow]',
			array(
				'label'    => __( 'Color', 'woostify-pro' ),
				'section'  => 'woostify_shop_single',
				'settings' => 'woostify_pro_options[shop_single_color_button_buynow]',
			)
		)
	);

	// Button Hover Background.
	$wp_customize->add_setting(
		'woostify_pro_options[shop_single_background_hover]',
		array(
			'default'           => $defaults['shop_single_background_hover'],
			'type'              => 'option',
			'sanitize_callback' => 'woostify_sanitize_rgba_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Woostify_Color_Control(
			$wp_customize,
			'woostify_pro_options[shop_single_background_hover]',
			array(
				'label'    => __( 'Hover Background', 'woostify-pro' ),
				'section'  => 'woostify_shop_single',
				'settings' => 'woostify_pro_options[shop_single_background_hover]',
			)
		)
	);

	// Button Hover Color.
	$wp_customize->add_setting(
		'woostify_pro_options[shop_single_color_hover]',
		array(
			'default'           => $defaults['shop_single_color_hover'],
			'type'              => 'option',
			'sanitize_callback' => 'woostify_sanitize_rgba_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new Woostify_Color_Control(
			$wp_customize,
			'woostify_pro_options[shop_single_color_hover]',
			array(
				'label'    => __( 'Hover Color', 'woostify-pro' ),
				'section'  => 'woostify_shop_single',
				'settings' => 'woostify_pro_options[shop_single_color_hover]',
			)
		)
	);

	// Border Radius.
	$wp_customize->add_setting(
		'woostify_pro_options[shop_single_border_radius_buynow]',
		array(
			'default'           => $defaults['shop_single_border_radius_buynow'],
			'type'              => 'option',
			'sanitize_callback' => 'esc_html',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new Woostify_Range_Slider_Control(
			$wp_customize,
			'woostify_pro_options[shop_single_border_radius_buynow]',
			array(
				'label'    => __( 'Border Radius', 'woostify-pro' ),
				'section'  => 'woostify_shop_single',
				'settings' => array(
					'desktop' => 'woostify_pro_options[shop_single_border_radius_buynow]',
				),
				'choices'  => array(
					'desktop' => array(
						'min'  => apply_filters( 'woostify_shop_single_border_radius_min_step', 0 ),
						'max'  => apply_filters( 'woostify_shop_single_border_radius_max_step', 50 ),
						'step' => 1,
						'edit' => true,
						'unit' => 'px',
					),
				),
			)
		)
	);
}


// STICKY SINGLE ADD TO CART.
if ( defined( 'WOOSTIFY_PRO_STICKY_SINGLE_ADD_TO_CART' ) ) {
	// SHOP SINGLE STRUCTURE SECTION.
	$wp_customize->add_setting(
		'shop_single_addon_section_sticky',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Woostify_Section_Control(
			$wp_customize,
			'shop_single_addon_section_sticky',
			array(
				'label'      => __( 'Button Sticky', 'woostify-pro' ),
				'section'    => 'woostify_shop_single',
				'dependency' => array(
					'woostify_pro_options[sticky_single_add_to_cart_button]',
					'woostify_pro_options[sticky_atc_button_on]',
				),
			)
		)
	);

	// Sticky add to cart button.
	$wp_customize->add_setting(
		'woostify_pro_options[sticky_single_add_to_cart_button]',
		array(
			'default'           => $defaults['sticky_single_add_to_cart_button'],
			'type'              => 'option',
			'sanitize_callback' => 'woostify_sanitize_choices',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'woostify_pro_options[sticky_single_add_to_cart_button]',
			array(
				'label'    => __( 'Sticky Add To Cart Button', 'woostify-pro' ),
				'settings' => 'woostify_pro_options[sticky_single_add_to_cart_button]',
				'section'  => 'woostify_shop_single',
				'type'     => 'select',
				'choices'  => apply_filters(
					'woostify_pro_options_sticky_single_add_to_cart_button_choices',
					array(
						'top'    => __( 'Top', 'woostify-pro' ),
						'bottom' => __( 'Bottom', 'woostify-pro' ),
					)
				),
			)
		)
	);

	// Sticky add to cart button on mobile.
	$wp_customize->add_setting(
		'woostify_pro_options[sticky_atc_button_on]',
		array(
			'default'           => $defaults['sticky_atc_button_on'],
			'type'              => 'option',
			'sanitize_callback' => 'woostify_sanitize_choices',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'woostify_pro_options[sticky_atc_button_on]',
			array(
				'label'    => __( 'Sticky On', 'woostify-pro' ),
				'settings' => 'woostify_pro_options[sticky_atc_button_on]',
				'section'  => 'woostify_shop_single',
				'type'     => 'select',
				'choices'  => apply_filters(
					'woostify_pro_options_sticky_single_add_to_cart_on_devices_choices',
					array(
						'desktop' => __( 'Desktop', 'woostify-pro' ),
						'mobile'  => __( 'Mobile', 'woostify-pro' ),
						'both'    => __( 'Desktop + Mobile', 'woostify-pro' ),
					)
				),
			)
		)
	);
}
