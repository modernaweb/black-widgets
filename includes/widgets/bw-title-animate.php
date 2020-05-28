<?php
namespace Elementor;
namespace BW_Modernaweb\Includes\Widgets;
use Elementor\Plugin;

use Elementor\Widget_Base;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
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
class BW_Title_Animate extends \Elementor\Widget_Base {

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
		return __( 'Black Title Animate', 'bw' );
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
		return 'eicon-t-letter-bold';
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
		return [ 'bw' ];
	}

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		// Start
		// Content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => __( 'Select Type', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'simple',
				'options' => [
					'simple' 		=> __( 'Simple', 'bw' ),
					'classic' 		=> __( 'Classic', 'bw' ),
					'liner'			=> __( 'Liner', 'bw' ),
					'effective' 	=> __( 'Effective', 'bw' ),
					'typing' 		=> __( 'Typing', 'bw' ),
					'fft' 			=> __( 'Fade From Top', 'bw' ),
					'ffl' 			=> __( 'Fade From Left', 'bw' ),
					'ffr' 			=> __( 'Fade From Right', 'bw' ),
					'ffb' 			=> __( 'Fade From Bottom', 'bw' ),
					'fade_in' 		=> __( 'Fade In', 'bw' ),
					'fade_out' 		=> __( 'Fade Out', 'bw' ),
					'glitch_one' 	=> __( 'Glitch 1', 'bw' ),
					'glitch_two' 	=> __( 'Glitch 2', 'bw' ),
					'ffb2' 			=> __( 'Fade From Bottom2', 'bw' ),
				],
				'description' => __( 'We create some skin before, you can use these or no! make a new custom type.', 'bw' ),
			]
		);

		// Type title
		$this->add_control(
			'widget_title_before',
			[
				'label' => __( 'Before Title', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Before', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'Main Title', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Content', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'Symbol (Just Single Character) ', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '&', 'bw' ),
				'placeholder' => __( 'Set symbol', 'bw' ),
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
				'label' => __( 'Glitch Text', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Glitch•', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'Main Title', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Find Your Element', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'Start Range From Bottom', 'bw' ),
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
				'label' => __( 'Text 1', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Set', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'Text 2', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Ready', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'Text 3', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Go', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'Typing Text', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Hello Goodbye', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'After Title', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'After', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'HTML Tag Setting', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select tag
		$this->add_control(
			'widget_html_tag',
			[
				'label' => __( 'HTML Tag', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'div' 	=> __( 'div', 'bw' ),
					'h1' 	=> __( 'H1', 'bw' ),
					'h2' 	=> __( 'H2', 'bw' ),
					'h3' 	=> __( 'H3', 'bw' ),
					'h4' 	=> __( 'H4', 'bw' ),
					'h5' 	=> __( 'H5', 'bw' ),
					'h6' 	=> __( 'H6', 'bw' ),
					'p' 	=> __( 'p', 'bw' ),
					'span' 	=> __( 'span', 'bw' ),
				],
				'description' => __( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'bw' ),
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Box Style', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-title-animate',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'widget_alignment',
			[
				'label'     => __( 'Text Alignment', 'bw' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'bw' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'bw' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'bw' ),
						'icon'  => 'fa fa-align-right',
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-animate',
			]
		);

		// Border Radius
		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Before Text Typography', 'bw' ),
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
				'label' => __( 'Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
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
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_before_title_text_shadow',
				'label' => __( 'Text Shadow', 'bw' ),
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
				'label' => __( 'Background', 'bw' ),
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		// Border Radius
		$this->add_control(
			'style_before_title_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Animate Text Typography', 'bw' ),
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
				'label' => __( 'Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
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
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_title_text_shadow',
				'label' => __( 'Text Shadow', 'bw' ),
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
				'label' => __( 'Background', 'bw' ),
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_title_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Symbol Text Typography', 'bw' ),
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
				'label' => __( 'Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
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
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_symbol_title_text_shadow',
				'label' => __( 'Text Shadow', 'bw' ),
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
				'label' => __( 'Background', 'bw' ),
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		// Border Radius
		$this->add_control(
			'style_symbol_title_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'After Text Typography', 'bw' ),
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
				'label' => __( 'Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
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
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_after_title_text_shadow',
				'label' => __( 'Text Shadow', 'bw' ),
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
				'label' => __( 'Background', 'bw' ),
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		// Border Radius
		$this->add_control(
			'style_after_title_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Line Color', 'bw' ),
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
				'label' => __( 'Line Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-line' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'style_after_line_width',
			[
				'label' => __( 'Line Width', 'bw' ),
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
				'label' => __( 'Line Height', 'bw' ),
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
		// wp_enqueue_style( '', BW_URL . 'xxx.css' );

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

		$data_id                = 'bw-' . uniqid();






echo '<div class="bw-title-animate ' . $alignment . '">';
	switch ($type) {
		case 'simple':
			?>
			<div class="bw-title-anime bw-simple-wrap  <?php echo $data_id ?>">
				<<?php echo $HTML; ?> class="bw-heading-animate">
					<span class="bw-before"><?php echo $before_text; ?></span>
					<span class="bw-simple <?php echo $data_id ?> bw-animate-text"><?php echo $main_text_text; ?></span>
					<span class="bw-after"><?php echo $after_text; ?></span>
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
					<span class="bw-letters bw-before"><?php echo $before_text; ?></span>
					<span class="bw-letters bw-symbol"><?php echo $symbol; ?></span>
					<span class="bw-letters bw-after"><?php echo $after_text; ?></span>
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
				<span class="bw-letters bw-animate-text"><?php echo $liner; ?></span>
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
				<span class="bw-letters bw-letters-1 bw-animate-text"><?php echo $la; ?></span>
				<span class="bw-letters bw-letters-2 bw-animate-text"><?php echo $lb; ?></span>
				<span class="bw-letters bw-letters-3 bw-animate-text"><?php echo $lc; ?></span>
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
				<span class="bw-letters bw-animate-text"><?php echo $typetext; ?></span>
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
					<span class="bw-before"><?php echo $before_text; ?></span>
					<span class="bw-letters bw-animate-text"><?php echo $fft; ?></span>
					<span class="bw-after"><?php echo $after_text; ?></span>
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
				<span class="bw-before"><?php echo $before_text; ?></span>
					<span class="bw-letters bw-animate-text"><?php echo $ffb; ?></span>
					<span class="bw-after"><?php echo $after_text; ?></span>
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
				<span class="bw-animate-text"><?php echo $ffb2; ?></span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'ffl': // Fade From Left
			?>
			<<?php echo $HTML; ?> class="bw-ffl bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
					<span class="bw-before"><?php echo $before_text; ?></span>
					<span class="bw-letters bw-animate-text"><?php echo $ffl; ?></span>
					<span class="bw-after"><?php echo $after_text; ?></span>
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
				<span class="bw-before"><?php echo $before_text; ?></span>
					<span class="bw-letters bw-animate-text"><?php echo $ffr; ?></span>
					<span class="bw-after"><?php echo $after_text; ?></span>
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
				<span class="bw-word bw-animate-text"><?php echo $fadein1; ?></span>
				<span class="bw-word bw-animate-text"><?php echo $fadein2; ?></span>
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
				<span class="bw-word bw-animate-text"><?php echo $fadeout1; ?></span>
				<span class="bw-word bw-animate-text"><?php echo $fadeout2; ?></span>
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
				<span class="bw-glitch bw-animate-text" data-text="<?php echo $glitch; ?>"><?php echo $glitch; ?></span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'glitch_two':
			?>
			<div class="bw-glitch-wrapper">
				<<?php echo $HTML; ?> class="bw-glitch bw-animate-text" data-text="<?php echo $glitch; ?>"><?php echo $glitch; ?></<?php echo $HTML; ?>>
			</div>
			<?php
			break;
		default: //Glitch Two is default
			?>
			<div class="bw-glitch-wrapper">
				<<?php echo $HTML; ?> class="bw-glitch bw-heading-animate bw-animate-text" data-text="<?php echo $glitch; ?>"><?php echo $glitch; ?></<?php echo $HTML; ?>>
			</div>
			<?php
			break;
	}
echo '</div>';

	}

}