<?php
namespace Elementor;
namespace BLACK_WIDGETS_Modernaweb\Includes\Widgets;
namespace Black_Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Color;
use Elementor\Group_Control_Text_Shadow;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BLACK_WIDGETS_Title_Animate extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'b_TitleAnimate';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black Title Animate', 'blackwidgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-animated-headline';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the button widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'black_widgets' ];
	}

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		// Start
		// Content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_panel_alert',
			[
				'type' => \Elementor\Controls_Manager::ALERT,
				'alert_type' => 'info',     /* info, success, warning, danger */
				'heading' => esc_html__( 'Feel free to edit. Check this widget\'s demo.', 'blackwidgets' ),
				'content' => sprintf(
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-title-animate/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'blackwidgets' ),
					esc_html__( 'Demo', 'blackwidgets' )
				),
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => esc_html__( 'Select Type', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'simple',
				'options' => [
					'simple' 		=> esc_html__( 'Simple', 'blackwidgets' ),
					'classic' 		=> esc_html__( 'Classic', 'blackwidgets' ),
					'liner'			=> esc_html__( 'Liner', 'blackwidgets' ),
					'effective' 	=> esc_html__( 'Effective', 'blackwidgets' ),
					'typing' 		=> esc_html__( 'Typing', 'blackwidgets' ),
					'fft' 			=> esc_html__( 'Fade From Top', 'blackwidgets' ),
					'ffl' 			=> esc_html__( 'Fade From Left', 'blackwidgets' ),
					'ffr' 			=> esc_html__( 'Fade From Right', 'blackwidgets' ),
					'ffb' 			=> esc_html__( 'Fade From Bottom', 'blackwidgets' ),
					'fade_in' 		=> esc_html__( 'Fade In', 'blackwidgets' ),
					'fade_out' 		=> esc_html__( 'Fade Out', 'blackwidgets' ),
					'glitch_one' 	=> esc_html__( 'Glitch 1', 'blackwidgets' ),
					'glitch_two' 	=> esc_html__( 'Glitch 2', 'blackwidgets' ),
					'ffb2' 			=> esc_html__( 'Fade From Bottom2', 'blackwidgets' ),
				],
				'description' => esc_html__( 'We create some skin before, you can use these or no! make a new custom type.', 'blackwidgets' ),
			]
		);

		// Type title
		$this->add_control(
			'widget_title_before',
			[
				'label' => esc_html__( 'Before Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Before', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'simple',
						'classic',
						'fft',
						'ffl',
						'ffr',
						'ffb',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_main_text',
			[
				'label' => esc_html__( 'Main Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Content', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'simple',
						'fft',
						'ffl',
						'ffr',
						'ffb',
						'ffb2',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_main_text_classic',
			[
				'label' => esc_html__( 'Symbol (Just Single Character) ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '&', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Set symbol', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'classic',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_glitch',
			[
				'label' => esc_html__( 'Glitch Text', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Glitch•', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'glitch_one',
						'glitch_two',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_for_liner',
			[
				'label' => esc_html__( 'Main Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Find Your Element', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'liner',
					],
				],
			]
		);

		$this->add_control(
			'widget_title_for_liner_top',
			[
				'label' => esc_html__( 'Start Range From Bottom', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -400,
						'max' => 400,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-animate-text' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'ffb2',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_la',
			[
				'label' => esc_html__( 'Text 1', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Set', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'effective',
						'fade_in',
						'fade_out',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_lb',
			[
				'label' => esc_html__( 'Text 2', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Ready', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'effective',
						'fade_in',
						'fade_out',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_lc',
			[
				'label' => esc_html__( 'Text 3', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Go', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'effective',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_typing',
			[
				'label' => esc_html__( 'Typing Text', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Hello Goodbye', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'typing',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_after',
			[
				'label' => esc_html__( 'After Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'After', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_type' => [
						'simple',
						'classic',
						'fft',
						'ffl',
						'ffr',
						'ffb',
					],
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Tag section
		$this->start_controls_section(
			'tag_section',
			[
				'label' => esc_html__( 'HTML Tag Setting', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select tag
		$this->add_control(
			'widget_html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'div' 	=> esc_html__( 'div', 'blackwidgets' ),
					'h1' 	=> esc_html__( 'H1', 'blackwidgets' ),
					'h2' 	=> esc_html__( 'H2', 'blackwidgets' ),
					'h3' 	=> esc_html__( 'H3', 'blackwidgets' ),
					'h4' 	=> esc_html__( 'H4', 'blackwidgets' ),
					'h5' 	=> esc_html__( 'H5', 'blackwidgets' ),
					'h6' 	=> esc_html__( 'H6', 'blackwidgets' ),
					'p' 	=> esc_html__( 'p', 'blackwidgets' ),
					'span' 	=> esc_html__( 'span', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'blackwidgets' ),
				// 'selectors' => [
				// 	'{{WRAPPER}} .bw-button-wrapper' => 'text-align: {{VALUE}};',
				// ],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Box Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_box_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-title-animate',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'widget_alignment',
			[
				'label'     => esc_html__( 'Text Alignment', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'    => true,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate',
			]
		);

		// Border Radius
		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Before Text Typography
		$this->start_controls_section(
			'style_section_before_typo',
			[
				'label' => esc_html__( 'Before Text Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type!' => [
						'liner',
						'effective',
						'typing',
						'fade_in',
						'fade_out',
						'glitch_one',
						'glitch_two',
						'ffb2',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_before_title_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-before' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_before_title_typography1',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_before_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_before_title_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_before_title_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_before_title_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_before_title_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		// Border Radius
		$this->add_control(
			'style_before_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_before_title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_main_typo',
			[
				'label' => esc_html__( 'Animate Text Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type!' => [
						'classic',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_main_title_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_title_typography1',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_main_title_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_title_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_title_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr6',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_title_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// symbol Text Typography
		$this->start_controls_section(
			'style_section_symbol_typo',
			[
				'label' => esc_html__( 'Symbol Text Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'classic',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_symbol_title_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-symbol' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_symbol_title_typography1',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_symbol_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		$this->add_control(
			'hr7',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_symbol_title_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		$this->add_control(
			'hr8',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_symbol_title_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-symbol' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_symbol_title_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-symbol' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr9',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_symbol_title_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		// Border Radius
		$this->add_control(
			'style_symbol_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-symbol' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_symbol_title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		$this->end_controls_section();
		// End	
		

		// Start
		// After Text Typography
		$this->start_controls_section(
			'style_section_after_typo',
			[
				'label' => esc_html__( 'After Text Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type!' => [
						'liner',
						'effective',
						'typing',
						'fade_in',
						'fade_out',
						'glitch_one',
						'glitch_two',
						'ffb2',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_after_title_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-after' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_after_title_typography1',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_after_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		$this->add_control(
			'hr10',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_after_title_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		$this->add_control(
			'hr11',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_after_title_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_after_title_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr12',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_after_title_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		// Border Radius
		$this->add_control(
			'style_after_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_after_title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Line Text Typography
		$this->start_controls_section(
			'style_section_symbol_line',
			[
				'label' => esc_html__( 'Line Color', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'classic',
						'liner',
						'typing',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_after_line_color',
			[
				'label' => esc_html__( 'Line Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-line' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'style_after_line_width',
			[
				'label' => esc_html__( 'Line Width', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-line' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'style_after_line_height',
			[
				'label' => esc_html__( 'Line Height', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-line' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();
        // End

	}

	/**
	 * Render title widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings   	= $this->get_settings_for_display();
		// wp_enqueue_style( '', BLACK_WIDGETS_URL . 'xxx.css' );

		// Variables
		$type 	        = isset($settings['widget_type'])						? $settings['widget_type']						: '';
		$title 			= isset($settings['widget_title']) 						? $settings['widget_title']						: '';
		$before_text	= isset($settings['widget_title_before'])				? $settings['widget_title_before']				: '';
		$main_text_text	= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$symbol			= isset($settings['widget_title_main_text_classic'])	? $settings['widget_title_main_text_classic']	: '';
		$liner			= isset($settings['widget_title_for_liner'])			? $settings['widget_title_for_liner']			: '';
		$la				= isset($settings['widget_title_la'])					? $settings['widget_title_la']					: '';
		$lb				= isset($settings['widget_title_lb'])					? $settings['widget_title_lb']					: '';
		$lc				= isset($settings['widget_title_lc'])					? $settings['widget_title_lc']					: '';
		$fadein1		= isset($settings['widget_title_la'])					? $settings['widget_title_la']					: '';
		$fadein2		= isset($settings['widget_title_lb'])					? $settings['widget_title_lb']					: '';
		$fadeout1		= isset($settings['widget_title_la'])					? $settings['widget_title_la']					: '';
		$fadeout2		= isset($settings['widget_title_lb'])					? $settings['widget_title_lb']					: '';
		$typetext		= isset($settings['widget_title_typing'])				? $settings['widget_title_typing']				: '';
		$ffl			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$fft			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$ffr			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$ffb			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$ffb2			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$after_text		= isset($settings['widget_title_after'])				? $settings['widget_title_after']				: '';
		$glitch			= isset($settings['widget_title_glitch'])				? $settings['widget_title_glitch']				: '';

		$HTML   		= isset($settings['widget_html_tag']) 					? $settings['widget_html_tag']					: '';
		$alignment 		= isset($settings['widget_alignment']) 					? $settings['widget_alignment']					: '';
		// $alignment 		=  '';

		$data_id                = 'bw-' . uniqid();

        // wp_kses is rather slow so we manullay sanitize the html
        if ( ! in_array( $HTML, ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'] ) ) {
            $HTML = 'h1';
        }




echo '<div class="bw-title-animate ' . esc_attr( $alignment ) . '">';
	switch ($type) {
		case 'simple':
			?>
			<div class="bw-title-anime bw-simple-wrap  <?php echo $data_id ?>">
				<<?php echo $HTML; ?> class="bw-heading-animate">
					<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-simple <?php echo $data_id ?> bw-animate-text"><?php echo esc_html( $main_text_text ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</<?php echo $HTML; ?>>
			</div>
			<script>
				jQuery(document).ready(function () {
					/* Sunny mornings */
					// Wrap every letter in a span
					var textWrapper = document.querySelector('.bw-simple<?php echo '.' . $data_id ?>');
					if ( textWrapper ) {
						textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
					}
					anime.timeline({loop: true})
					.add({ targets: '.bw-simple-wrap<?php echo '.' . $data_id ?> .bw-letter', scale: [3,1], opacity: [0,1], translateZ: 0, easing: "easeOutExpo", duration: 1379, delay: (el, i) => 70*i })
					.add({ targets: '.bw-simple<?php echo '.' . $data_id ?>', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });
				});
			</script>
			<?php
			break;
		case 'classic':
			?>
			<<?php echo $HTML; ?> class="bw-title-anime bw-classic bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
					<span class="bw-line bw-line1"></span>
					<span class="bw-letters bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-symbol"><?php echo esc_html( $symbol ); ?></span>
					<span class="bw-letters bw-after"><?php echo esc_html( $after_text ); ?></span>
					<span class="bw-line bw-line2"></span>
				</span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					/* Signal & Noise */
					anime.timeline({loop: true})
					.add({ targets: '.bw-classic<?php echo '.' . $data_id ?> .bw-line', opacity: [0.5,1], scaleX: [0, 1], easing: "easeInOutExpo", duration: 700 })
					.add({ targets: '.bw-classic<?php echo '.' . $data_id ?> .bw-line', duration: 600, easing: "easeOutExpo", translateY: (el, i) => (-0.625 + 0.625*2*i) + "em" })
					.add({ targets: '.bw-classic<?php echo '.' . $data_id ?> .bw-symbol', opacity: [0,1], scaleY: [0.5, 1], easing: "easeOutExpo", duration: 600, offset: '-=600' })
					.add({ targets: '.bw-classic<?php echo '.' . $data_id ?> .bw-before', opacity: [0,1], translateX: ["0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=300' })
					.add({ targets: '.bw-classic<?php echo '.' . $data_id ?> .bw-after', opacity: [0,1], translateX: ["-0.5em", 0], easing: "easeOutExpo", duration: 600, offset: '-=600' })
					.add({ targets: '.bw-classic<?php echo '.' . $data_id ?>', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });
				});
			</script>
			<?php
			break;
		case 'liner':
			?>
			<<?php echo $HTML; ?> class="bw-liner bw-heading-animate <?php echo $data_id ?>">
			<span class="bw-text-wrapper">
				<span class="bw-letters bw-animate-text"><?php echo esc_html( $liner ); ?></span>
				<span class="bw-line"></span>
			</span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					// Wrap every letter in a span
					var textWrapper = document.querySelector('.bw-liner<?php echo '.' . $data_id ?> .bw-letters');
					if ( textWrapper ) {
						textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
					}
					anime.timeline({loop: true})
					.add({ targets: '.bw-liner<?php echo '.' . $data_id ?> .bw-line', scaleX: [0,1], opacity: [0.5,1], easing: "easeInOutExpo", duration: 900 })
					.add({ targets: '.bw-liner<?php echo '.' . $data_id ?> .bw-letter', opacity: [0,1], translateX: [40,0], translateZ: 0, scaleX: [0.3, 1], easing: "easeOutExpo", duration: 800, offset: '-=600', delay: (el, i) => 150 + 25 * i })
					.add({ targets: '.bw-liner<?php echo '.' . $data_id ?>', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });
				});
			</script>
			<?php
			break;
		case 'effective':
			?>
			<<?php echo $HTML; ?> class="bw-effective bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-letters bw-letters-1 bw-animate-text"><?php echo esc_html( $la ); ?></span>
				<span class="bw-letters bw-letters-2 bw-animate-text"><?php echo esc_html( $lb ); ?></span>
				<span class="bw-letters bw-letters-3 bw-animate-text"><?php echo esc_html( $lc ); ?></span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					/* Ready Set Go! */
					var bwEffective = {};
					bwEffective.opacityIn = [0,1];
					bwEffective.scaleIn = [0.2, 1];
					bwEffective.scaleOut = 3;
					bwEffective.durationIn = 800;
					bwEffective.durationOut = 600;
					bwEffective.delay = 500;
					anime.timeline({loop: true})
					.add({ targets: '.bw-effective<?php echo '.' . $data_id ?> .bw-letters-1', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
					.add({ targets: '.bw-effective<?php echo '.' . $data_id ?> .bw-letters-1', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
					.add({ targets: '.bw-effective<?php echo '.' . $data_id ?> .bw-letters-2', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
					.add({ targets: '.bw-effective<?php echo '.' . $data_id ?> .bw-letters-2', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
					.add({ targets: '.bw-effective<?php echo '.' . $data_id ?> .bw-letters-3', opacity: bwEffective.opacityIn, scale: bwEffective.scaleIn, duration: bwEffective.durationIn })
					.add({ targets: '.bw-effective<?php echo '.' . $data_id ?> .bw-letters-3', opacity: 0, scale: bwEffective.scaleOut, duration: bwEffective.durationOut, easing: "easeInExpo", delay: bwEffective.delay })
					.add({ targets: '.bw-effective<?php echo '.' . $data_id ?>', opacity: 0, duration: 500, delay: 500 });
				});
			</script>
			<?php
			break;
		case 'typing':
			?>
			<<?php echo $HTML; ?> class="bw-typing bw-heading-animate <?php echo $data_id ?>">
			<span class="bw-text-wrapper">
				<span class="bw-line bw-line1"></span>
				<span class="bw-letters bw-animate-text"><?php echo esc_html( $typetext ); ?></span>
			</span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					// Wrap every letter in a span
					var textWrapper = document.querySelector('.bw-typing<?php echo '.' . $data_id ?> .bw-letters');
					if ( textWrapper ) {
						textWrapper.innerHTML = textWrapper.textContent.replace(/([^\x00-\x80]|\w)/g, "<span class='bw-letter'>$&</span>");
					}
					anime.timeline({loop: true})
					.add({ targets: '.bw-typing<?php echo '.' . $data_id ?> .bw-line', scaleY: [0,1], opacity: [0.5,1], easing: "easeOutExpo", duration: 700 })
					.add({ targets: '.bw-typing<?php echo '.' . $data_id ?> .bw-line', translateX: [0, document.querySelector('.bw-typing .bw-letters').getBoundingClientRect().width + 10], easing: "easeOutExpo", duration: 700, delay: 600 })
					.add({ targets: '.bw-typing<?php echo '.' . $data_id ?> .bw-letter', opacity: [0,1], easing: "easeOutExpo", duration: 600, offset: '-=775', delay: (el, i) => 34 * (i+1) })
					.add({ targets: '.bw-typing<?php echo '.' . $data_id ?>', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1000 });
				});
			</script>
			<?php
			break;
		case 'fft': // Fade From Top
			?>
			<<?php echo $HTML; ?> class="bw-fft bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
					<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-animate-text"><?php echo esc_html( $fft ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					/* Domino Dreams */
					// Wrap every letter in a span
					var textWrapper = document.querySelector('.bw-fft<?php echo '.' . $data_id ?> .bw-letters');
					if ( textWrapper ) {
						textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
					}
					anime.timeline({loop: true})
					.add({ targets: '.bw-fft<?php echo '.' . $data_id ?> .bw-letter', translateY: [-90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
					.add({ targets: '.bw-fft<?php echo '.' . $data_id ?> .bw-letter', translateY: [0, 90], translateZ: 0, opacity: [1,0], easing: "easeInExpo", duration: 1200, delay: (el, i) => 500 + 30 * i });
				});	
			</script>
			<?php
			break;
		case 'ffb': // Fade From Bottom
			?>
			<<?php echo $HTML; ?> class="bw-ffb bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
				<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-animate-text"><?php echo esc_html( $ffb ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					/* Domino Dreams */
					// Wrap every letter in a span
					var textWrapper = document.querySelector('.bw-ffb<?php echo '.' . $data_id ?> .bw-letters');
					if ( textWrapper ) {
						textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
					}
					anime.timeline({loop: true})
					.add({ targets: '.bw-ffb<?php echo '.' . $data_id ?> .bw-letter', translateY: [90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
					.add({ targets: '.bw-ffb<?php echo '.' . $data_id ?> .bw-letter', translateY: [0, -90], translateZ: 0, opacity: [1,0], easing: "easeInExpo", duration: 1200, delay: (el, i) => 500 + 30 * i });
				});
			</script>
			<?php
			break;
		case 'ffb2': // Fade From Bottom
			?>
			<<?php echo $HTML; ?> class="bw-fade-text <?php echo $data_id ?>">
				<span class="bw-animate-text"><?php echo esc_html( $ffb2 ); ?></span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'ffl': // Fade From Left
			?>
			<<?php echo $HTML; ?> class="bw-ffl bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
					<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-animate-text"><?php echo esc_html( $ffl ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					/* Domino Dreams */
					// Wrap every letter in a span
					var textWrapper = document.querySelector('.bw-ffl<?php echo '.' . $data_id ?> .bw-letters');
					if ( textWrapper ) {
						textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
					}
					anime.timeline({loop: true})
					.add({ targets: '.bw-ffl<?php echo '.' . $data_id ?> .bw-letter', translateX: [-90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
					.add({ targets: '.bw-ffl<?php echo '.' . $data_id ?> .bw-letter', translateX: [0,90], opacity: [1,0], easing: "easeInExpo", duration: 1100, delay: (el, i) => 100 + 30 * i });
				});
			</script>
			<?php
			break;
		case 'ffr': // Fade From Right
			?>
			<<?php echo $HTML; ?> class="bw-ffr bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
				<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-animate-text"><?php echo esc_html( $ffr ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					/* Domino Dreams */
					// Wrap every letter in a span
					var textWrapper = document.querySelector('.bw-ffr<?php echo '.' . $data_id ?> .bw-letters');
					if ( textWrapper ) {
						textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='bw-letter'>$&</span>");
					}
					anime.timeline({loop: true})
					.add({ targets: '.bw-ffr<?php echo '.' . $data_id ?> .bw-letter', translateX: [90,0], translateZ: 0, opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: (el, i) => 500 + 30 * i })
					.add({ targets: '.bw-ffr<?php echo '.' . $data_id ?> .bw-letter', translateX: [0,-90], opacity: [1,0], easing: "easeInExpo", duration: 1100, delay: (el, i) => 100 + 30 * i });
				});
			</script>
			<?php
			break;
		case 'fade_in':
			?>
			<<?php echo $HTML; ?> class="bw-fin bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-word bw-animate-text"><?php echo esc_html( $fadein1 ); ?></span>
				<span class="bw-word bw-animate-text"><?php echo esc_html( $fadein2 ); ?></span>
			</<?php echo $HTML; ?>>
			<script>
				jQuery(document).ready(function () {
					anime.timeline({loop: true})
					.add({ targets: '.bw-fin<?php echo '.' . $data_id ?> .bw-word', scale: [3,1], opacity: [0,1], easing: "easeInOutExpo", duration: 800, delay: (el, i) => 800 * i })
					.add({ targets: '.bw-fin<?php echo '.' . $data_id ?>', opacity: 0, duration: 1000, easing: "easeOutExpo", delay: 1200 });
				});
			</script>
			<?php
			break;
		case 'fade_out':
			?>
			<<?php echo $HTML; ?> class="bw-fout bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-word bw-animate-text"><?php echo esc_html( $fadeout1 ); ?></span>
				<span class="bw-word bw-animate-text"><?php echo esc_html( $fadeout2 ); ?></span>
			</<?php echo $HTML; ?>>		
			<script>
				jQuery(document).ready(function () {
					anime.timeline({loop: true})
					.add({ targets: '.bw-fout<?php echo '.' . $data_id ?>', opacity: [0,1], easing: "easeOutExpo", duration: 1200, delay: 400, })
					.add({ targets: '.bw-fout<?php echo '.' . $data_id ?> .bw-word', opacity: 0, scale: [1,3], duration: 800, easing: "easeOutExpo", delay: (el, i) => 800 * i });
				});
			</script>
			<?php
			break;
		case 'glitch_one':
			?>
			<<?php echo $HTML; ?> class="bw-glitch bw-heading-animate">
				<span class="bw-glitch bw-animate-text" data-text="<?php echo esc_attr( $glitch ); ?>"><?php echo esc_html( $glitch ); ?></span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'glitch_two':
			?>
			<div class="bw-glitch-wrapper">
				<<?php echo $HTML; ?> class="bw-glitch bw-animate-text" data-text="<?php echo esc_attr( $glitch ); ?>"><?php echo esc_html( $glitch ); ?></<?php echo $HTML; ?>>
			</div>
			<?php
			break;
		default: //Glitch Two is default
			?>
			<div class="bw-glitch-wrapper">
				<<?php echo $HTML; ?> class="bw-glitch bw-heading-animate bw-animate-text" data-text="<?php echo esc_attr( $glitch ); ?>"><?php echo esc_html( $glitch ); ?></<?php echo $HTML; ?>>
			</div>
			<?php
			break;
	}
echo '</div>';

	}

}
