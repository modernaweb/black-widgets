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
class BLACK_WIDGETS_Dropcap extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve dropcap widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'b_dropcap';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve dropcap widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black Drop Cap', 'blackwidgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve dropcap widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-font';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the dropcap widget belongs to.
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
	 * Register dropcap widget controls.
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

		$this->add_control(
			'widget_dropcap',
			[
				'label' => __( 'Description (limit character 159)', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 14,
				'default' => __( 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old, Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old, Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', 'blackwidgets' ),
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
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap',
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
					'{{WRAPPER}} .bw-dropcap-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-dropcap-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap:hover',
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
					'{{WRAPPER}} .bw-dropcap-wrap:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-dropcap-wrap:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'style_section_back_first_letter_paragraph_typo',
			[
				'label' => __( 'Back Dropcap Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'style_main_back_first_letter_paragraph_color',
			[
				'label' => __( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_back_first_letter_paragraph_typography1',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_back_first_letter_paragraph_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter',
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
				'name' => 'style_main_back_first_letter_paragraph_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter',
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
			'style_main_back_first_letter_paragraph_margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_back_first_letter_paragraph_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'name' => 'style_main_back_first_letter_paragraph_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_back_first_letter_paragraph_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_back_first_letter_paragraph_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap::first-letter',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_back_paragraph_typo',
			[
				'label' => __( 'Back Paragraph Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'style_main_back_paragraph_color',
			[
				'label' => __( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_back_paragraph_typography1',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_back_paragraph_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap',
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
				'name' => 'style_main_back_paragraph_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap',
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
			'style_main_back_paragraph_margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_back_paragraph_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'name' => 'style_main_back_paragraph_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_back_paragraph_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_back_paragraph_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-dropcap-wrap .bw-dropcap',
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
        $description	= isset($settings['widget_dropcap'])	? $settings['widget_dropcap']		: '';

		// Render
        echo '<div class="bw-dropcap-wrap"><p class="bw-dropcap">' . $description . '</p></div>';

	}

}