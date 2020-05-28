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
class BW_Title extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve title widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'b_title';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve title widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black Title', 'bw' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve title widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-t-letter';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the title widget belongs to.
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
	 * Register title widget controls.
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
				'default' => 'bw-t-1',
				'options' => [
					'bw-t-1' => __( 'Type 1', 'bw' ),
					'bw-t-2' => __( 'Type 2', 'bw' ),
					'bw-t-3' => __( 'Type 3', 'bw' ),
					'bw-t-4' => __( 'Type 4', 'bw' ),
					'bw-t-5' => __( 'Type 5', 'bw' ),
					'bw-t-6' => __( 'Type 6', 'bw' ),
					'bw-t-7' => __( 'Type 7', 'bw' ),
					'bw-t-8' => __( 'Type 8', 'bw' ),
					'custom' => __( 'Custom', 'bw' ),
				],
				'description' => __( 'We create some skin before, you can use these or no! make a new custom type.', 'bw' ),
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Type title
		$this->add_control(
			'widget_title',
			[
				'label' => __( 'Title', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Black Widget Title', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
				'description' => __( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and  ...', 'bw' ),
			]
		);

		// Select tag
		$this->add_control(
			'widget_html_tag_title',
			[
				'label' => __( 'HTML Tag', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'div',
				'options' => [
					'div' => __( 'div', 'bw' ),
					'h1' => __( 'H1', 'bw' ),
					'h2' => __( 'H2', 'bw' ),
					'h3' => __( 'H3', 'bw' ),
					'h4' => __( 'H4', 'bw' ),
					'h5' => __( 'H5', 'bw' ),
					'h6' => __( 'H6', 'bw' ),
					'p' => __( 'p', 'bw' ),
					'span' => __( 'span', 'bw' ),
				],
				'description' => __( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'bw' ),
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Type subtitle
		$this->add_control(
			'widget_subtitle',
			[
				'label' => __( 'Subtitle', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Type your title here', 'bw' ),
				'default' => __( 'Black Widget Subtitle', 'bw' ),
				'description' => __( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and  ...', 'bw' ),
			]
		);

		// Select tag
		$this->add_control(
			'widget_html_tag_subtitle',
			[
				'label' => __( 'HTML Tag', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'div',
				'options' => [
					'div' => __( 'div', 'bw' ),
					'h1' => __( 'H1', 'bw' ),
					'h2' => __( 'H2', 'bw' ),
					'h3' => __( 'H3', 'bw' ),
					'h4' => __( 'H4', 'bw' ),
					'h5' => __( 'H5', 'bw' ),
					'h6' => __( 'H6', 'bw' ),
					'p' => __( 'p', 'bw' ),
					'span' => __( 'span', 'bw' ),
				],
				'description' => __( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'bw' ),
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
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
				'default'   => 'center',
				'toggle'    => true,
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Content section
		$this->start_controls_section(
			'custom_section',
			[
				'label' => __( 'Custom Content', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition'  => [
					'widget_type' => [
						'custom',
					],
				],
			]
		);

		// Shape
		$this->add_control(
			'custom_shape',
			[
				'label' => __( 'Choose Shape', 'bw' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					// 'url' => \Elementor\Utils::get_placeholder_image_src(),
					'url' => plugin_dir_url( __FILE__ ) . '../front/img/substract.svg',
				],
				'description' => __( 'We suggest you use the SVG files, but you can use PNG format event JPG file formats.', 'bw' ),
			]
		);

		// Rotate
		$this->add_control(
			'custom_widget_rotate',
			[
				'label' => __( 'Rotation', 'bw' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg'],
				'range' => [
					'px' => [
						'min' => -360,
						'max' => 360,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'transform: rotate( {{SIZE}}{{UNIT}} )',
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
				'label' => __( 'Box Style', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-box',
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
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
					'{{WRAPPER}} .bw-title-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-title-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-box',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-box',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'typography1_section',
			[
				'label' => __( 'Title Typography', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'widget_title_solid_color',
			[
				'label' => __( 'Title Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .bw-title .bw-div' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography1',
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-box .bw-title .bw-div',
			]
		);

		// Typography 8-2
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography1_8_2',
				'label' => __( 'First Letter Typography - type 8', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-box.bw-t-8 .bw-title div::first-letter',
				'condition'  => [
					'widget_type' => [
						'bw-t-8',
					],
				],
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow1',
				'label' => __( 'Text Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-box .bw-title .bw-div',
			]
		);

		$this->add_control(
			'hr6',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_typography_title_background',
				'label' => __( 'Title Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-box .bw-title .bw-div',
			]
		);

		$this->add_control(
			'hr7',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_typography_title_margin',
			[
				'label' => __( 'Title Margin', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .bw-title .bw-div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_typography_title_padding',
			[
				'label' => __( 'Title Padding', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .bw-title .bw-div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr8',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_typography_title_border',
				'label' => __( 'Title Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-box .bw-title .bw-div',
			]
		);

		// Border Radius
		$this->add_control(
			'widget_typography_title_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .bw-title .bw-div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_typography_title_box_shadow',
				'label' => __( 'Title Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-box .bw-title .bw-div',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'typography2_section',
			[
				'label' => __( 'Subtitle Typography', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'widget_subtitle_solid_color',
			[
				'label' => __( 'Subtitle Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography2',
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div',
			]
		);

		// Typography 8-2
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography2_8_2',
				'label' => __( 'First Letter Typography - type 8', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-title-box.bw-t-8 .bw-subtitle div::first-letter',
				'condition'  => [
					'widget_type' => [
						'bw-t-8',
					],
				],
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow2',
				'label' => __( 'Text Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div',
			]
		);

		$this->add_control(
			'hr9',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_typography_subtitle_background',
				'label' => __( 'Subtitle Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div',
			]
		);

		$this->add_control(
			'hr10',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_typography_subtitle_margin',
			[
				'label' => __( 'Subtitle Margin', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_typography_subtitle_padding',
			[
				'label' => __( 'Subtitle Padding', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr11',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_typography_subtitle_border',
				'label' => __( 'Subtitle Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div',
			]
		);

		// Border Radius
		$this->add_control(
			'widget_typography_subtitle_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_typography_subtitle_box_shadow',
				'label' => __( 'Subtitle Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div',
			]
		);

		$this->end_controls_section();
		// End
		
		// Start
		// Style section
		$this->start_controls_section(
			'shape_section',
			[
				'label' => __( 'Shape Settings', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'custom',
					],
				],
			]
		);

		// Position
		$this->add_control(
			'widget_shape_position',
			[
				'label' => __( 'Shape Poistion', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'relative',
				'options' => [
					'absolute' => __( 'Absolute', 'bw' ),
					'relative' => __( 'Relative', 'bw' ),
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'position: {{VALUE}};',
				],
			]
		);

		// Top position
		$this->add_control(
			'widget_shape_top_position',
			[
				'label' => __( 'Top Position', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'top: {{VALUE}};',
				],
				'condition'  => [
					'widget_shape_position' => [
						'absolute',
					],
				],
				'description' => __( 'Type position size with unit. e.g: 10px or auto or calc(50% - 24px)', 'bw' ),
			]
		);

		// Left position
		$this->add_control(
			'widget_shape_left_position',
			[
				'label' => __( 'Left Position', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'left: {{VALUE}};',
				],
				'condition'  => [
					'widget_shape_position' => [
						'absolute',
					],
				],
				'description' => __( 'Type position size with unit. e.g: 10px or auto or calc(50% - 24px)', 'bw' ),
			]
		);

		// Bottom position
		$this->add_control(
			'widget_shape_bottom_position',
			[
				'label' => __( 'Bottom Position', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'bottom: {{VALUE}};',
				],
				'condition'  => [
					'widget_shape_position' => [
						'absolute',
					],
				],
				'description' => __( 'Type position size with unit. e.g: 10px or auto or calc(50% - 24px)', 'bw' ),
			]
		);

		// Right position
		$this->add_control(
			'widget_shape_right_position',
			[
				'label' => __( 'Right Position', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'right: {{VALUE}};',
				],
				'condition'  => [
					'widget_shape_position' => [
						'absolute',
					],
				],
				'description' => __( 'Type position size with unit. e.g: 10px or auto or calc(50% - 24px)', 'bw' ),
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
		$type 			= isset($settings['widget_type']) 				? $settings['widget_type'] 												: '';
		$alignment 		= isset($settings['widget_alignment']) 			? $settings['widget_alignment'] 										: '';
		$title 			= isset($settings['widget_title']) 				? $settings['widget_title'] 											: '';
		$subtitle 		= isset($settings['widget_subtitle']) 			? $settings['widget_subtitle'] 											: '';
		$title_tag 		= isset($settings['widget_html_tag_title']) 	? $settings['widget_html_tag_title'] 									: '';
		$subtitle_tag   = isset($settings['widget_html_tag_subtitle']) 	? $settings['widget_html_tag_subtitle'] 								: '';
		$shape 			= isset($settings['custom_shape']) 				? '<img src="' . $settings['custom_shape']['url'] . '" class="shape">' 	: '';

		// Render
		echo '<div class="bw-title-box ' . $type . ' ' . $alignment . '">';
			echo '<div class="bw-title"><' . $title_tag . ' class="bw-div">' . $title . '</' . $title_tag . '></div>';
			echo $shape;
			echo '<div class="bw-subtitle"><' . $subtitle_tag . ' class="bw-div"> ' . $subtitle . '</' . $subtitle_tag . '></div>';
		echo '</div>';

	}

}	