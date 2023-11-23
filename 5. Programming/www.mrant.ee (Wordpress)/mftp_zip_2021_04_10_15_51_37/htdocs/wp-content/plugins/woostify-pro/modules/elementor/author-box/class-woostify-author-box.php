<?php
/**
 * Elementor Author Box Widget
 *
 * @package Woostify Pro
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class widget.
 */
class Woostify_Author_Box extends Widget_Base {
	/**
	 * Category
	 */
	public function get_categories() {
		return array( 'woostify-theme' );
	}

	/**
	 * Name
	 */
	public function get_name() {
		return 'woostify-author-box';
	}

	/**
	 * Gets the title.
	 */
	public function get_title() {
		return __( 'Woostify - Author Box', 'woostify-pro' );
	}

	/**
	 * Gets the icon.
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Gets the keywords.
	 */
	public function get_keywords() {
		return array( 'woostify', 'author', 'person' );
	}

	/**
	 * Controls
	 */
	protected function _register_controls() { // phpcs:ignore
		$this->start_controls_section(
			'start',
			array(
				'label' => __( 'General', 'woostify-pro' ),
			)
		);

		// Avatar.
		$this->add_control(
			'author_avatar',
			array(
				'label' => __( 'Author Avatar', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		// Size.
		$this->add_control(
			'size',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Width', 'woostify-pro' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 30,
						'max'  => 500,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 120,
				),
			)
		);

		// Border radius.
		$this->add_control(
			'radius',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Border Radius', 'woostify-pro' ),
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .post-author-box .author-ava img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Space.
		$this->add_control(
			'margin',
			array(
				'label'      => __( 'Space', 'woostify-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .post-author-box .author-ava' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Infor.
		$this->add_control(
			'author_info',
			array(
				'label' => __( 'Author Infor', 'woostify-pro' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		// Label.
		$this->add_control(
			'author_label',
			array(
				'label'   => __( 'Label', 'woostify-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Written by', 'woostify-pro' ),
			)
		);

		// Label color.
		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Label Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-author-box .author-name-before' => 'color: {{VALUE}}',
				),
			)
		);

		// Label typo.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_label_typo',
				'label'    => __( 'Label Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .post-author-box .author-name-before',
			)
		);

		// Author name color.
		$this->add_control(
			'author_name_color',
			array(
				'label'     => __( 'Author Name Color', 'woostify-pro' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .post-author-box .author-name' => 'color: {{VALUE}}',
				),
			)
		);

		// Author name.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_name_typo',
				'label'    => __( 'Author Name Typography', 'woostify-pro' ),
				'selector' => '{{WRAPPER}} .post-author-box .author-name',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render
	 */
	public function render() {
		$settings = $this->get_settings_for_display();

		$user_id = get_the_author_meta( 'ID' );

		if ( class_exists( 'Woostify_Woo_Builder' ) ) {
			$woo_builder    = \Woostify_Woo_Builder::init();
			$single_builder = $woo_builder->template_exist( 'woostify_product_page' );
			$user_id        = get_post_field( 'post_author', $woo_builder->get_product_id() );
		}

		$avatar_args['size']    = empty( $settings['size']['size'] ) ? 300 : $settings['size']['size'];
		$author['avatar']       = get_avatar_url( $user_id, $avatar_args );
		$author['display_name'] = get_the_author_meta( 'display_name', $user_id );
		$author['website']      = get_the_author_meta( 'user_url', $user_id );
		$author['bio']          = get_the_author_meta( 'description', $user_id );
		$author['posts_url']    = get_author_posts_url( $user_id );
		?>
		<div class="post-author-box">
			<a class="author-ava" href="<?php echo esc_url( $author['posts_url'] ); ?>">
				<img src="<?php echo esc_url( $author['avatar'] ); ?>" alt="<?php esc_attr_e( 'Author Avatar', 'woostify' ); ?>">
			</a>

			<div class="author-content">
				<span class="author-name-before"><?php echo esc_html( $settings['author_label'] ); ?></span>
				<a class="author-name" href="<?php echo esc_url( $author['posts_url'] ); ?>"><?php echo esc_html( $author['display_name'] ); ?></a>

				<?php if ( ! empty( $author['bio'] ) ) { ?>
					<div class="author-bio"><?php echo wp_kses_post( $author['bio'] ); ?></div>
				<?php } ?>
			</div>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Woostify_Author_Box() );
