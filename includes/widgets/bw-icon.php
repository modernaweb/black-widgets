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
class BLACK_WIDGETS_Icon extends \Elementor\Widget_Base {

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
		return 'b_icon';
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
		return __( 'Black Icon', 'blackwidgets' );
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
		return 'eicon-favorite';
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
	protected function _register_controls() {

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
		// $this->add_control(
		// 	'widget_type',
		// 	[
		// 		'label' => __( 'Select Type', 'blackwidgets' ),
		// 		'type' => \Elementor\Controls_Manager::SELECT,
		// 		'default' => 'bw-t-2',
		// 		'options' => [
		// 			'bw-t-1' 	=> __( 'Type 1', 'blackwidgets' ),
		// 			'bw-t-2' 	=> __( 'Type 2', 'blackwidgets' ),
		// 			'bw-t-3' 	=> __( 'Type 3', 'blackwidgets' ),
		// 			'bw-t-4' 	=> __( 'Type 4', 'blackwidgets' ),
		// 		],
		// 	]
		// );

		$this->add_control(
			'icon_widget',
			[
				'label' => __( 'Icon', 'blackwidgets' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'eicon eicon-nerd',
					'library' => 'elementor',
				],
			]
		);

		// Alignment
		$this->add_responsive_control(
			'widget_alignment',
			[
				'label'     => __( 'Text Alignment', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'blackwidgets' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blackwidgets' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'blackwidgets' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
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

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-icon-box .bw-icon-into',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-icon-box .bw-icon-into',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-icon-box .bw-icon-into',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-icon-box .bw-icon-into' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .bw-icon-box .bw-icon-into' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-icon-box .bw-icon-into' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Typography section
		$this->start_controls_section(
			'icon_section',
			[
				'label' => __( 'Icon Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
        );

		$this->add_control(
			'style_icon_alert_size',
			[
				'label' => __( 'Icon Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-icon-box .bw-icon-into i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		// Color
		$this->add_control(
			'style_icon_alert_color',
			[
				'label' => __( 'Icon Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-icon-box .bw-icon-into i' => 'color: {{VALUE}}',
				],
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_Icon_alert_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-icon-box .bw-icon-into i',
			]
        );

		$this->end_controls_section();

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
		// $type 	        = isset($settings['widget_type']) 				? $settings['widget_type'] : '';
		$alignment		= isset($settings['widget_alignment']) 			? $settings['widget_alignment'] 					: '';

		// Render
		echo '<div class="bw-icon-box" style="text-align: ' . $alignment . ';">';
		
		echo '<div class="bw-icon-into">';
				\Elementor\Icons_Manager::render_icon( $settings['icon_widget'], [ 'aria-hidden' => 'true' ] );
			echo '</div>';

        echo '</div>';

	}

}