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
class BLACK_WIDGETS_Magic_Link extends \Elementor\Widget_Base {

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
		return 'b_magic';
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
		return __( 'Black Magic Link', 'blackwidgets' );
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
		return 'eicon-link';
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
				'label' => __( 'Content', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => __( 'Select Type', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'minimal',
				'options' => [
					'minimal' 	=> __( 'Minimal', 'blackwidgets' ),
					'liner' 	=> __( 'Liner', 'blackwidgets' ),
					'modern' 	=> __( 'Modern', 'blackwidgets' ),
					'simple' 	=> __( 'Simple', 'blackwidgets' ),
					'heart' 	=> __( 'Heart Beat', 'blackwidgets' ),
					'pullltr' 	=> __( 'Pull Left To Right', 'blackwidgets' ),
					'pullrtl' 	=> __( 'Pull Right To Left', 'blackwidgets' ),
					'pullttb' 	=> __( 'Pull Top To Bottom', 'blackwidgets' ),
					'pullbtt' 	=> __( 'Pull Bottom To Top', 'blackwidgets' ),
					'arrow' 	=> __( 'Arrow', 'blackwidgets' ),
					'anchor' 	=> __( 'Anchor', 'blackwidgets' ),
					'wheel' 	=> __( 'Wheel', 'blackwidgets' ),
				],
				'description' => __( 'We create some skin before, you can use these or no! make a new custom type.', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_text',
			[
				'label' => __( 'Button Text', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Let\'s started', 'blackwidgets' ),
				'placeholder' => __( 'Type your title here', 'blackwidgets' ),
			]
		);
        
		$this->add_control(
			'website_link',
			[
				'label' => __( 'Link', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'blackwidgets' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Box Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => __( 'Normal', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_box_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_1_hover',
			[
				'label' => __( 'Hover', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_hover_box_margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_hover_box_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_hover_box_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
        // End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_back_link_typo',
			[
				'label' => __( 'Text Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'style_main_back_link_color',
			[
				'label' => __( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_back_link_typography1',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_main_back_link_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		$this->add_control(
			'hr6',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_back_link_margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_back_link_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr7',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_back_link_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_back_link_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_back_link_hover_typo',
			[
				'label' => __( 'Hover Text Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'style_main_back_link_hover_color',
			[
				'label' => __( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_back_link_hover_typography1',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_hover_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		$this->add_control(
			'hr8',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_main_back_link_hover_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		$this->add_control(
			'hr9',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_back_link_hover_margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_back_link_hover_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr10',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_back_link_hover_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_back_link_hover_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_hover_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_other',
			[
				'label' => __( 'Other Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' 	=> [ 
					'widget_type!' 	=> [
						'simple',
					],
				],
			]
		);

		// Normal Height
		$this->add_control(
			'top_and_bottom_line_normal_height',
			[
				'label' => __( 'Normal Height', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 14,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
					],
				],
			]
		);

		// Hover Height
		$this->add_control(
			'top_and_bottom_line_hover_height',
			[
				'label' => __( 'Hover Height', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:hover:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:hover:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
					],
				],
			]
		);

		// Normal Width
		$this->add_control(
			'top_and_bottom_line_normal_width',
			[
				'label' => __( 'Normal Width', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 14,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullltr',
						'pullrtl',
					],
				],
			]
		);

		// Hover Width
		$this->add_control(
			'top_and_bottom_line_hover_width',
			[
				'label' => __( 'Hover Width', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:hover:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:hover:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullltr',
						'pullrtl',
					],
				],
			]
		);

		// Liner Height
		$this->add_control(
			'liner_line_height',
			[
				'label' => __( 'Liner Height', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 7,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-liner:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'liner',
					],
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'line_bg',
				'label' => __( 'Bg Color/Line Color', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a.bw-magic-modern:before, {{WRAPPER}} .bw-magic-link a.bw-magic-minimal:after, {{WRAPPER}} .bw-magic-link a.bw-magic-liner:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before, {{WRAPPER}} .bw-magic-link .bw-magic-heart span',
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
						'pullltr',
						'pullrtl',
						'liner',
						'modern',
						'minimal',
						'heart',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'svg_heart_color',
			[
				'label' => __( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-heart span svg' => 'fill: {{VALUE}}',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'heart',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'svg_wheel_color',
			[
				'label' => __( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g line, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g polyline, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g circle' => 'stroke: {{VALUE}} !important',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'wheel',
					],
				],
			]
		);

		$this->add_control(
			'magic_link_bg_opacity',
			[
				'label' => __( 'Opacity', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g line, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g polyline, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g circle, {{WRAPPER}} .bw-magic-link a.bw-magic-modern:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before, {{WRAPPER}} .bw-magic-link .bw-magic-heart span svg' => 'opacity: {{SIZE}};',
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

		// Variables
		$type 	        	= isset($settings['widget_type']) 				? $settings['widget_type']				: '';
        $text 	        	= isset($settings['widget_text']) 				? $settings['widget_text'] 				: '';
		$target         	= $settings['website_link']['is_external'] 		? 'target="_blank"' 					: '';
		$nofollow       	= $settings['website_link']['nofollow'] 		? ' rel="nofollow"'						: '';

		// Render
		switch ($type) {
			case 'heart':
				echo '<div class="bw-magic-link"><a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . ' <span><?xml version="1.0" ?><!DOCTYPE svg  PUBLIC \'-//W3C//DTD SVG 1.1//EN\'  \'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\'><svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="160,115.4 180.7,96 352,256 180.7,416 160,396.7 310.5,256 "/></svg></span></a></div>';
			break;
			case 'liner':
				echo '<div class="bw-magic-link"><a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '</a></div>';
			break;
			case 'arrow':
				echo '<div class="bw-magic-link"><a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '<span>  <i aria-hidden="true" class="fas fa-arrow-right"></i> </span></a></div>';
			break;
			case 'wheel':
				echo '<div class="bw-magic-link"><a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '"><svg><g><line x2="227.62" y1="31.28" y2="31.28"></line><polyline points="222.62 25.78 228.12 31.28 222.62 36.78"></polyline><circle cx="224.67" cy="30.94" r="30.5" transform="rotate(180 224.67 30.94) scale(1, -1) translate(0, -61)"></circle></g></svg><span>' . $text . '</span></a></div>';
			break;
			default:
				echo '<div class="bw-magic-link"><a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '</a></div>';
			break;
		}

	}

}