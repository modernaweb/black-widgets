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
class BLACK_WIDGETS_Call_To_Action extends \Elementor\Widget_Base {

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
		return 'b_cta';
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
		return __( 'Black Call To Action', 'blackwidgets' );
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
		return 'eicon-call-to-action';
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

		// Title
		$this->add_control(
			'widget_title',
			[
				'label' => __( 'Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Let\'s get started today, <br /> Ever thought about joining us?', 'blackwidgets' ),
				'placeholder' => __( 'Type your title here', 'blackwidgets' ),
				'description' => __( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and  ...', 'blackwidgets' ),
			]
		);

		// HTML Tag
		$this->add_control(
			'widget_html_tag_title',
			[
				'label' => __( 'HTML Tag', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'div' => __( 'div', 'blackwidgets' ),
					'h1' => __( 'H1', 'blackwidgets' ),
					'h2' => __( 'H2', 'blackwidgets' ),
					'h3' => __( 'H3', 'blackwidgets' ),
					'h4' => __( 'H4', 'blackwidgets' ),
					'h5' => __( 'H5', 'blackwidgets' ),
					'h6' => __( 'H6', 'blackwidgets' ),
					'p' => __( 'p', 'blackwidgets' ),
					'span' => __( 'span', 'blackwidgets' ),
				],
				'description' => __( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'blackwidgets' ),
				'separator' => 'after',
			]
		);

		// Subtitle
		$this->add_control(
			'widget_subtitle',
			[
				'label' => __( 'Subtitle', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Create a High Quality UI/UX Design from San Francisco.', 'blackwidgets' ),
				'placeholder' => __( 'Type your subtitle here', 'blackwidgets' ),
				'description' => __( 'You can use all other HTML tags into the subtitle field e.g. code, mark, abbr, blockquote and  ...', 'blackwidgets' ),
			]
		);

		// HTML Tag
		$this->add_control(
			'widget_html_tag_subtitle',
			[
				'label' => __( 'HTML Tag', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'p',
				'options' => [
					'div' => __( 'div', 'blackwidgets' ),
					'h1' => __( 'H1', 'blackwidgets' ),
					'h2' => __( 'H2', 'blackwidgets' ),
					'h3' => __( 'H3', 'blackwidgets' ),
					'h4' => __( 'H4', 'blackwidgets' ),
					'h5' => __( 'H5', 'blackwidgets' ),
					'h6' => __( 'H6', 'blackwidgets' ),
					'p' => __( 'p', 'blackwidgets' ),
					'span' => __( 'span', 'blackwidgets' ),
				],
				'description' => __( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'blackwidgets' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'widget_link_text',
			[
				'label' => __( 'Button Text', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Find More', 'blackwidgets' ),
				'placeholder' => __( 'Type your title here', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_link_url',
			[
				'label' => __( 'Button URL', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( '#', 'blackwidgets' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
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
				'selector' => '{{WRAPPER}} .bw-cta',
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
					'{{WRAPPER}} .bw-cta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-cta',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-cta:hover',
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
					'{{WRAPPER}} .bw-cta:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-cta:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-cta:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
        // End

		// Start
		// Title Style section
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => __( 'Title Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('inner_title_tab'); // Start Tabs
		$this->start_controls_tab(
			'title_tab_1',
			[
				'label' => __( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_title_normal_color',
			[
				'label' => __( 'Normal Color', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_tab_2',
			[
				'label' => __( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_title_hover_color',
			[
				'label' => __( 'Hover Color', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-cta:hover .bw-cta-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->add_control(
			'title_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typographys',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-title',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_title_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-title',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_title_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-title',
			]
		);

		// Margin
		$this->add_responsive_control(
			'content_title__margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_title__padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_title__border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-title',
			]
		);

		// Border Radius
		$this->add_control(
			'content_title__border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_title__box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-title',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Subtitle Style section
		$this->start_controls_section(
			'subtitle_style_section',
			[
				'label' => __( 'Subtitle Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('inner_subtitle_tab'); // Start Tabs
		$this->start_controls_tab(
			'subtitle_tab_1',
			[
				'label' => __( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_subtitle_normal_color',
			[
				'label' => __( 'Normal Color', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'subtitle_tab_2',
			[
				'label' => __( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_subtitle_hover_color',
			[
				'label' => __( 'Hover Color', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-cta:hover .bw-cta-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->add_control(
			'subtitle_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_subtitle_typographys',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-subtitle',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_subtitle_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-subtitle',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_subtitle_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-subtitle',
			]
		);

		// Margin
		$this->add_responsive_control(
			'content_subtitle__margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_subtitle__padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_subtitle__border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-subtitle',
			]
		);

		// Border Radius
		$this->add_control(
			'content_subtitle__border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_subtitle__box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-subtitle',
			]
		);

		$this->end_controls_section();
        // End

        // Start
		// Link Style section
		$this->start_controls_section(
			'link_style_section',
			[
				'label' => __( 'Link Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('inner_link_tab'); // Start Tabs
		$this->start_controls_tab(
			'link_tab_1',
			[
				'label' => __( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_link_normal_color',
			[
				'label' => __( 'Normal Color', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-btn' => 'color: {{VALUE}}',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_link_normal_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-btn',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'link_tab_2',
			[
				'label' => __( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_link_hover_color',
			[
				'label' => __( 'Hover Color', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_link_hover_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-btn:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->add_control(
			'link_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_link_typographys',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-btn',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_link_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-btn',
			]
		);

		// Margin
		$this->add_responsive_control(
			'content_link_margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_link_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_link_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-btn',
			]
		);

		// Border Radius
		$this->add_control(
			'content_link_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-cta .bw-cta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_link_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-cta .bw-cta-btn',
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
		// $type 	        		= isset($settings['widget_type']) 								? $settings['widget_type'] 						: ''; // Widget Type
		// Title
		$title 					= isset($settings['widget_title']) 								? $settings['widget_title']						: '';
		$title_tag 				= isset($settings['widget_html_tag_title']) 					? $settings['widget_html_tag_title']			: ''; // HTML Tag
		// Subtitle
		$subtitle				= isset($settings['widget_subtitle'])							? $settings['widget_subtitle']					: '';
		$subtitle_tag			= isset($settings['widget_html_tag_subtitle']) 					? $settings['widget_html_tag_subtitle']			: ''; // HTML Tag
		// Button
		$link					= isset($settings['widget_link_url']['url'])					? $settings['widget_link_url']['url']			: ''; // Link URL
		$link_text 	        	= isset($settings['widget_link_text'])							? $settings['widget_link_text'] 				: ''; // Link Text
		$link_target         	= $settings['widget_link_url']['is_external'] 					? 'target="_blank"' 							: '';
		$link_nofollow       	= $settings['widget_link_url']['nofollow'] 						? ' rel="nofollow"'								: '';
		$icon_arrow				= '<i class="demo-icon eicon-arrow-right"></i>';

		// Render
		echo '<div class="bw-cta">';
			echo '<div class="bw-cta-content">';
				echo '<' . $title_tag . ' class="bw-cta-title">' . $title . '</' . $title_tag . '>'; // Title
				echo '<' . $subtitle_tag . ' class="bw-cta-subtitle">' . $subtitle . '</' . $subtitle_tag . '>'; // Subtitle
			echo '</div>';
			echo '<div class="bw-cta-button">';
				echo '<a href="' . $link . '"' . $link_target . $link_nofollow . ' class="bw-cta-btn">' . $link_text . ' ' . $icon_arrow . '</a>'; // Link
			echo '</div>';
        echo '</div>';





	}

}