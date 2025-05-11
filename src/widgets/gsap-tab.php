<?php

namespace Modernaweb\BlackWidgets\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Black Tab Widget
 *
 * A tab-based content widget powered by GSAP animations.
 * Includes automatic tab switching, image/video display, and responsive layout.
 *
 * @since 1.3.6
 */
class GSAPTab extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.3.6
	 * @access public
	 */
	public function get_name(): string {
		return 'b_gsap_tab';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.3.6
	 * @access public
	 */
	public function get_title(): string {
		return __( 'Black Tab', 'black-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon class.
	 * @since 1.3.6
	 * @access public
	 */
	public function get_icon(): string {
		return 'eicon-tabs';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Categories array.
	 * @since 1.3.6
	 * @access public
	 */
	public function get_categories(): array {
		return [ 'black_widgets' ];
	}

	/**
	 * Determines whether the content of this widget is dynamic.
	 *
	 * @return bool True if the content is dynamic, false otherwise.
	 */
	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Returns an array of CSS styles that this widget depends on.
	 *
	 * @return array An array of CSS style handles.
	 */
	public function get_style_depends(): array {
		return [ 'black-widgets-gsap-tab' ];
	}

	/**
	 * Returns an array of script dependencies for this widget.
	 *
	 * @return array An array of script handles that this widget depends on.
	 */
	public function get_script_depends(): array {
		return [ 'black-widgets-gsap-tab' ];
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 * @since 1.3.6
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_settings_controls();

		$this->register_default_style_controls();

        $this->register_vertical_style_controls();
	}

	/**
	 * Settings Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_settings_controls() {
        $this->start_controls_section(
                'section_type',
                [
                        'label' => __( 'Type', 'black-widgets' ),
                        'tab'   => Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_control(
			'custom_panel_alert',
			[
				'type' => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'heading' => __( 'Feel free to edit.', 'black-widgets' ),
				'content' => __('We created some skin before, you can use these or no! make a new custom type.', 'black-widgets'),
			]
		);

        $this->add_control(
			'tab_type',
			[
				'label'   => __( 'Tab Type', 'black-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'      => __( 'Default', 'black-widgets' ),
					'vertical_tab' => __( 'Vertical Tab', 'black-widgets' ),
				],
				'default' => 'default',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'black-widgets' ),
				'condition' => [
                    'tab_type' => 'default',
                ],
			]
		);

		$this->add_control(
			'content',
			[
				'label'   => __( 'Content', 'black-widgets' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'Enter your content here', 'black-widgets' ),
				'condition' => [
                    'tab_type' => 'default',
                ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs',
			[
				'label' => __( 'Tabs', 'black-widgets' ),
			]
		);

		$this->register_default_repeater();
		$this->register_vertical_repeater();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'black-widgets' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Enable Autoplay', 'black-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'black-widgets' ),
				'label_off'    => __( 'No', 'black-widgets' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'autoplay_delay',
			[
				'label'     => __( 'Autoplay Delay (s)', 'black-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'step'      => 1,
				'default'   => 5,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Default Repeater Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
    private function register_default_repeater() {
    $repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label'       => __( 'Tab Title', 'black-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Tab Title', 'black-widgets' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tab_title_tag',
			[
				'label'   => __( 'Tab Title HTML Tag', 'black-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'DIV',
					'span' => 'SPAN',
					'p'    => 'P',
				],
				'default' => 'h3',
			]
		);

		$repeater->add_control(
			'tab_description',
			[
				'label'   => __( 'Short Description', 'black-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Tab short description goes here.', 'black-widgets' ),
			]
		);

		$repeater->add_control(
			'media_type',
			[
				'label'   => __( 'Media Type', 'black-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'image' => __( 'Image', 'black-widgets' ),
					'video' => __( 'Video', 'black-widgets' ),
				],
				'default' => 'image',
			]
		);

		$repeater->add_control(
			'tab_image',
			[
				'label'      => __( 'Select Image', 'black-widgets' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'image',
				'default'    => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'  => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'tab_video',
			[
				'label'      => __( 'Select Video', 'black-widgets' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'video',
				'default'    => [
					'url' => '',
				],
				'condition'  => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'read_more_text',
			[
				'label'   => __( 'Button Text', 'black-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Read More', 'black-widgets' ),
			]
		);

		$repeater->add_control(
			'read_more_link',
			[
				'label'       => __( 'Button Link', 'black-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'black-widgets' ),
			]
		);


		$repeater->add_control(
			'read_more_icon',
			[
				'label'   => __( 'Button Icon', 'black-widgets' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'tabs_default',
			[
				'label'       => __( 'Tab Items', 'black-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [],
				'title_field' => '{{{ tab_title }}}',
				'condition'   => [
					'tab_type' => 'default',
				],
			]
		);
    }

	/**
	 * Vertical Repeater Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
    private function register_vertical_repeater() {

        $repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			[
				'label'       => __( 'Tab Title', 'black-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Tab Title', 'black-widgets' ),
				'label_block' => true,
			]
		);

        $repeater->add_control(
		    'tab_content',
		    [
			    'label'   => __( 'Short Description', 'black-widgets' ),
			    'type'    => Controls_Manager::WYSIWYG,
			    'default' => __( 'Tab description goes here.', 'black-widgets' ),
		    ]
	    );

		$repeater->add_control(
			'media_type',
			[
				'label'   => __( 'Media Type', 'black-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'image' => __( 'Image', 'black-widgets' ),
					'video' => __( 'Video', 'black-widgets' ),
				],
				'default' => 'image',
			]
		);

		$repeater->add_control(
			'tab_image',
			[
				'label'      => __( 'Select Image', 'black-widgets' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'image',
				'default'    => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'  => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'tab_video',
			[
				'label'      => __( 'Select Video', 'black-widgets' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'video',
				'default'    => [
					'url' => '',
				],
				'condition'  => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'read_more_text',
			[
				'label'   => __( 'Button Text', 'black-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Read More', 'black-widgets' ),
			]
		);

		$repeater->add_control(
			'read_more_link',
			[
				'label'       => __( 'Button Link', 'black-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'black-widgets' ),
			]
		);


		$repeater->add_control(
			'read_more_icon',
			[
				'label'   => __( 'Button Icon', 'black-widgets' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'tabs_vertical',
			[
				'label'       => __( 'Tab Items', 'black-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [],
				'title_field' => '{{{ tab_title }}}',
				'condition'   => [
					'tab_type' => 'vertical_tab',
				],
			]
		);
    }

	/**
	 * Default Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
    private function register_default_style_controls() {
	    $this->register_default_box_style_controls();

	    $this->register_default_tabs_style_controls();

	    $this->register_default_timeline_style_controls();

	    $this->register_default_media_style_controls();

	    $this->register_default_read_more_button_style_controls();
    }
    
	/**
	 * Default Box style controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_default_box_style_controls() {
		$this->start_controls_section(
			'section_default_box_style',
			[
				'label' => __( 'Box Style', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
	                'tab_type' => 'default',
                ],
			]
		);

		$this->add_responsive_control(
			'default_box_padding',
			[
				'label'      => __( 'Padding', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'default_box_margin',
			[
				'label'      => __( 'Margin', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'default_column_gap',
			[
				'label'      => __( 'Gap Between Columns', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 200 ],
					'%'  => [ 'min' => 0, 'max' => 20 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'default_column_width_ratio',
			[
				'label'     => __( 'Left / Right Column Width (%)', 'black-widgets' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'%' => [ 'min' => 10, 'max' => 90 ],
				],
				'default'   => [
					'size' => 50,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .black-tab__inner' => 'grid-template-columns: {{SIZE}}% calc(100% - {{SIZE}}%);',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Default Tabs Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_default_tabs_style_controls() {
		$this->start_controls_section(
			'section_default_tabs_style',
			[
				'label' => __( 'Tabs List', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_type' => 'default',
				],
			]
		);

		$this->add_control(
			'default_tabs_title_heading',
			[
				'label'     => __( 'Titles', 'black-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'default_tabs_title_typography',
				'selector' => '{{WRAPPER}} .black-tab__tab-title',
			]
		);

		$this->start_controls_tabs(
			'default_tabs_title_color_tabs'
		);

		$this->start_controls_tab(
			'default_tabs_title_normal_color_tab',
			[
				'label' => esc_html__( 'Normal', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_tabs_title_normal_color',
			[
				'label'     => __( 'Title Normal Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'default_tabs_title_hover_color_tab',
			[
				'label' => esc_html__( 'Hover', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_tabs_title_hover_color',
			[
				'label'     => __( 'Title Hover Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab-title:not(.black-tab__tab-title--active):hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'default_tabs_title_active_color_tab',
			[
				'label' => esc_html__( 'Active', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_tabs_title_active_color',
			[
				'label'     => __( 'Title Active Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab-title.black-tab__tab-title--active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'default_tabs_title_margin',
			[
				'label'      => __( 'Title Margin', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'default_tabs_description_heading',
			[
				'label'     => __( 'Description', 'black-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'default_tabs_description_typography',
				'selector' => '{{WRAPPER}} .black-tab__tab-description',
			]
		);

		$this->add_control(
			'default_tabs_description_color',
			[
				'label'     => __( 'Description Text Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'default_tabs_description_margin',
			[
				'label'      => __( 'Description Margin', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'default_tabs_item_heading',
			[
				'label'     => __( 'Tab Item', 'black-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs(
			'default_tabs_item_background_tabs'
		);

		$this->start_controls_tab(
			'default_tabs_item_normal_background_tab',
			[
				'label' => esc_html__( 'Normal', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_tab_item_background',
			[
				'label'     => __( 'Tab Item Normal Background', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'default_tabs_item_hover_background_tab',
			[
				'label' => esc_html__( 'Hover', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_tab_item_hover_background',
			[
				'label'     => __( 'Tab Item Hover Background', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'default_tab_item_border',
				'label'    => __( 'Tab Item Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .black-tab__tab',
			]
		);

		$this->add_control(
			'default_tab_item_border_radius',
			[
				'label'      => __( 'Tab Item Border Radius', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'default_tab_item_box_shadow',
				'label'    => __( 'Tab Item Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .black-tab__tab',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Default Timeline Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_default_timeline_style_controls() {

		$this->start_controls_section(
			'section_default_timeline_style',
			[
				'label' => __( 'Timeline Indicator', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_type' => 'default',
				],
			]
		);

		$this->start_controls_tabs(
			'default_timeline_thickness_tabs'
		);

		$this->start_controls_tab(
			'default_timeline_active_thickness_tabs',
			[
				'label' => esc_html__( 'Active', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'default_timeline_indicator_active_thickness',
			[
				'label'      => __( 'Active Timeline Indicator Thickness', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab--active .black-tab__tab-timeline' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'default_timeline_not_active_thickness_tabs',
			[
				'label' => esc_html__( 'Not Active', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'default_timeline_indicator_not_active_thickness',
			[
				'label'      => __( 'Not Active Timeline Indicator Thickness', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab-timeline' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'default_timeline_track_color',
			[
				'label'     => __( 'Timeline Track Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab-timeline' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'default_timeline_fill_color',
			[
				'label'     => __( 'Timeline Fill Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__timeline-fill' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'default_timeline_border_radius',
			[
				'label'      => __( 'Timeline Border Radius', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab-timeline' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'default_timeline_gap_offset',
			[
				'label'      => __( 'Timeline Gap / Offset', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'%'  => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab-timeline' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Default Media Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_default_media_style_controls() {
		$this->start_controls_section(
			'section_default_media_style',
			[
				'label' => __( 'Media', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_type' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'default_media_max_width',
			[
				'label'      => __( 'Max Width', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__media' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'default_media_max_height',
			[
				'label'      => __( 'Max Height', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__media' => 'max-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'default_media_border',
				'label'    => __( 'Media Area Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .black-tab__media',
			]
		);

		$this->add_control(
			'default_media_border_radius',
			[
				'label'      => __( 'Media Area Border Radius', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__right, {{WRAPPER}} .black-tab__media, {{WRAPPER}} .black-tab__image, {{WRAPPER}} .black-tab__video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'default_media_box_shadow',
				'label'    => __( 'Media Area Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .black-tab__media',
			]
		);

		$this->add_control(
			'default_media_object_fit',
			[
				'label'     => __( 'Object Fit', 'black-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'contain'    => __( 'Contain', 'black-widgets' ),
					'cover'      => __( 'Cover', 'black-widgets' ),
					'fill'       => __( 'Fill', 'black-widgets' ),
					'none'       => __( 'None', 'black-widgets' ),
					'scale-down' => __( 'Scale Down', 'black-widgets' ),
				],
				'default'   => 'contain',
				'selectors' => [
					'{{WRAPPER}} .black-tab__image, {{WRAPPER}} .black-tab__video' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'default_media_background_color',
			[
				'label'     => __( 'Background Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__right' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Default Read More Button Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_default_read_more_button_style_controls() {
		$this->start_controls_section(
			'section_default_read_more_button_style',
			[
				'label' => __( 'Read More Button', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_type' => 'default',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'default_read_more_button_typography',
				'selector' => '{{WRAPPER}} .black-tab__read-more',
			]
		);

		$this->start_controls_tabs(
			'default_tabs_read_more_button_text_color'
		);

		$this->start_controls_tab(
			'default_tab_read_more_button_text_color_normal',
			[
				'label' => __( 'Normal', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_read_more_button_text_color_normal',
			[
				'label'     => __( 'Text Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'default_tab_read_more_button_text_color_hover',
			[
				'label' => __( 'Hover', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_read_more_button_text_color_hover',
			[
				'label'     => __( 'Text Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs(
			'default_tabs_read_more_button_background_color'
		);

		$this->start_controls_tab(
			'default_tab_read_more_button_background_color_normal',
			[
				'label' => __( 'Normal', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_read_more_button_background_color_normal',
			[
				'label'     => __( 'Background Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'default_tab_read_more_button_background_color_hover',
			[
				'label' => __( 'Hover', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_read_more_button_background_color_hover',
			[
				'label'     => __( 'Background Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'default_read_more_button_padding',
			[
				'label'      => __( 'Padding', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'default_read_more_button_border',
				'label'    => __( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .black-tab__read-more',
			]
		);

		$this->add_control(
			'default_read_more_button_border_radius',
			[
				'label'      => __( 'Border Radius', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'default_read_more_button_box_shadow',
				'label'    => __( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .black-tab__read-more',
			]
		);

		$this->add_control(
			'default_read_more_button_icon_spacing',
			[
				'label'      => __( 'Icon Spacing', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__read-more' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'default_read_more_button_icon_size',
			[
				'label'      => __( 'Icon Size', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__read-more svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'default_tabs_read_more_button_icon_color'
		);

		$this->start_controls_tab(
			'default_tab_read_more_button_icon_color_normal',
			[
				'label' => __( 'Normal', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_read_more_button_icon_color_normal',
			[
				'label'     => __( 'Icon Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'default_tab_read_more_button_icon_color_hover',
			[
				'label' => __( 'Hover', 'black-widgets' ),
			]
		);

		$this->add_control(
			'default_read_more_button_icon_color_hover',
			[
				'label'     => __( 'Icon Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more:hover svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Vertical Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_vertical_style_controls() {
		$this->register_vertical_box_style_controls();

		$this->register_vertical_tab_titles_style_controls();

		$this->register_vertical_content_area_style_controls();

		$this->register_vertical_media_style_controls();

		$this->register_vertical_read_more_button_style_controls();
	}

	/**
	 * Wrapper Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_vertical_box_style_controls() {
		$this->start_controls_section(
			'section_vertical_wrapper_style',
			[
				'label' => __( 'Box Styles', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_type' => 'vertical_tab',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'vertical_wrapper_background',
				'label'    => __( 'Background', 'black-widgets' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .black-tab',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'vertical_wrapper_border',
				'label'    => __( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .black-tab',
			]
		);

		$this->add_control(
			'vertical_wrapper_border_radius',
			[
				'label'      => __( 'Border Radius', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'vertical_wrapper_box_shadow',
				'label'    => __( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .black-tab',
			]
		);

		$this->add_responsive_control(
			'vertical_wrapper_padding',
			[
				'label'      => __( 'Padding', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'vertical_wrapper_margin',
			[
				'label'      => __( 'Margin', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Vertical Tab Titles Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_vertical_tab_titles_style_controls() {
		$this->start_controls_section(
			'section_vertical_tab_titles_style',
			[
				'label' => __( 'Tab Titles', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                  'tab_type' => 'vertical_tab'
                ],
			]
		);

		$this->add_responsive_control(
			'vertical_tabs_container_width',
			[
				'label' => __( 'Tabs Container Width', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 1920,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .black-tab__tabs-wrapper .black-tab__tabs' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_wrapper_heading',
			[
				'label' => __( 'Wrapper', 'black-widgets' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'vertical_tab_titles_wrapper_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .black-tab__tabs-wrapper',
			]
		);

		$this->add_control(
			'vertical_tab_titles_container_heading',
			[
				'label' => __( 'Container', 'black-widgets' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'vertical_tab_titles_container_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .black-tab__tabs',
			]
		);

		$this->add_responsive_control(
			"vertical_tab_titles_container_padding",
			[
				'label'      => __( 'Padding', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tabs' => "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
				],
			]
		);

		$this->add_responsive_control(
			"vertical_tab_titles_container_margin",
			[
				'label'      => __( 'Margin', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tabs' => "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_heading',
			[
				'label'     => __( 'Tab Item', 'black-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'vertical_tab_titles_typography',
				'selector' => '{{WRAPPER}} .black-tab__tab',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'vertical_tab_titles_item_border',
				'selector' => '{{WRAPPER}} .black-tab__tab',
			]
		);

		$this->start_controls_tabs(
			'vertical_tabs_title_item_text_color_tabs'
		);

		$this->start_controls_tab(
			'vertical_tabs_title_item_normal_text_color_tab',
			[
				'label' => esc_html__( 'Normal', 'black-widgets' ),
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_text_color',
			[
				'label'     => __( 'Text Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_normal_background_color',
			[
				'label'     => __( 'Background Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tabs .black-tab__tab' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_normal_border_color',
			[
				'label'     => __( 'Border Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'vertical_tab_titles_item_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .black-tab__tabs .black-tab__tab' => 'border-color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_tab();

		$this->start_controls_tab(
			'vertical_tabs_title_item_active_text_color_tab',
			[
				'label' => esc_html__( 'Active', 'black-widgets' ),
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_active_text_color',
			[
				'label'     => __( 'Text Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab.black-tab__tab--active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_active_background_color',
			[
				'label'     => __( 'Background Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab.black-tab__tab--active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_active_border_color',
			[
				'label'     => __( 'Border Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'vertical_tab_titles_item_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab.black-tab__tab--active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'vertical_tabs_title_item_hover_text_color_tab',
			[
				'label' => esc_html__( 'Hover', 'black-widgets' ),
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_hover_text_color',
			[
				'label'     => __( 'Text Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tabs .black-tab__tab:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_hover_background_color',
			[
				'label'     => __( 'Background Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__tabs .black-tab__tab:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_hover_border_color',
			[
				'label'     => __( 'Border Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'vertical_tab_titles_item_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .black-tab__tabs .black-tab__tab:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'vertical_tab_titles_text_align',
			[
				'label'     => __( 'Text Align', 'black-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'black-widgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'black-widgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'black-widgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .black-tab__tab' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			"vertical_tab_titles_item_padding",
			[
				'label'      => __( 'Padding', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab' => "padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
				],
			]
		);

		$this->add_responsive_control(
			"vertical_tab_titles_item_margin",
			[
				'label'      => __( 'Margin', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab' => "margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
				],
			]
		);

		$this->add_control(
			'vertical_tab_titles_item_border_radius',
			[
				'label'      => __( 'Border Radius', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__tab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'vertical_tab_titles_item_box_shadow',
				'selector' => '{{WRAPPER}} .black-tab__tab',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Vertical Tab Content Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_vertical_content_area_style_controls() {
		$this->start_controls_section(
			'section_vertical_content_area_style',
			[
				'label' => __( 'Content Area', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                        'tab_type' => 'vertical_tab',
                ],
			]
		);

		$this->add_responsive_control(
			'vertical_content_area_padding',
			[
				'label'      => __( 'Padding', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__content-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_content_area_margin',
			[
				'label'      => __( 'Margin', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__content-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_content_area_text_align',
			[
				'label'     => __( 'Text Align', 'black-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'black-widgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'black-widgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'black-widgets' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'black-widgets' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .black-tab__content-area' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Vertical Media Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	private function register_vertical_media_style_controls() {
		$this->start_controls_section(
			'section_vertical_media_style',
			[
				'label' => __( 'Media', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
	                'tab_type' => 'vertical_tab',
                ],
			]
		);

		$this->add_responsive_control(
			'vertical_media_max_width',
			[
				'label'      => __( 'Max Width', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 2000 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
					'vw' => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__media' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_media_max_height',
			[
				'label'      => __( 'Max Height', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 2000 ],
					'%'  => [ 'min' => 0, 'max' => 100 ],
					'vh' => [ 'min' => 0, 'max' => 100 ],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__media' => 'max-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'vertical_media_border',
				'selector' => '{{WRAPPER}} .black-tab__media',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'vertical_media_box_shadow',
				'selector' => '{{WRAPPER}} .black-tab__media',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Vertical Read More Style Controls
	 *
	 * @return void
	 * @since 1.3.6
	 * @access private
	 */
	protected function register_vertical_read_more_button_style_controls() {
		$this->start_controls_section(
			'section_vertical_read_more_button_style',
			[
				'label' => __( 'Read More Button', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
	                'tab_type' => 'vertical_tab',
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'vertical_read_more_typography',
				'selector' => '{{WRAPPER}} .black-tab__read-more',
			]
		);

		$this->add_control(
			'vertical_read_more_text_color',
			[
				'label'     => __( 'Text Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_read_more_background_color',
			[
				'label'     => __( 'Background Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'vertical_read_more_border',
				'selector' => '{{WRAPPER}} .black-tab__read-more',
			]
		);

		$this->add_responsive_control(
			'vertical_read_more_border_radius',
			[
				'label'      => __( 'Border Radius', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_read_more_padding',
			[
				'label'      => __( 'Padding', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_read_more_margin',
			[
				'label'      => __( 'Margin', 'black-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_vertical_read_more_icon_style',
			[
				'label' => __( 'Read More Icon', 'black-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'vertical_read_more_icon_color',
			[
				'label'     => __( 'Icon Color', 'black-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .black-tab__read-more-icon svg' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_read_more_icon_size',
			[
				'label'      => __( 'Icon Size', 'black-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .black-tab__read-more-icon, {{WRAPPER}} .black-tab__read-more-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_read_more_icon_spacing',
			[
				'label'     => __( 'Icon Spacing', 'black-widgets' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .black-tab__read-more' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 * @since 1.3.6
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['tabs_default'] ) && empty( $settings['tabs_vertical'] ) ) {
			return;
		}

		$type = $settings['tab_type'] ?? 'default';

		switch ( $type ) {
			case 'default':
				$this->render_default_tab( $settings );
				break;
			case 'vertical_tab':
				$this->render_vertical_tab( $settings );
				break;
		}
	}

	private function render_default_tab( $settings ) {
		$tabs           = $settings['tabs_default'];
		$is_autoplay    = $settings['autoplay'] === 'yes';
		$autoplay_delay = is_numeric( $settings['autoplay_delay'] ) ? (int) $settings['autoplay_delay'] : 5;
		?>

        <div class="black-tab black-tab--default <?php echo $is_autoplay ? 'black-tab--autoplay' : ''; ?>"
			<?php if ( $is_autoplay ): ?> data-delay="<?php echo esc_attr( $autoplay_delay * 1000 ); ?>"<?php endif; ?>>
            <div class="black-tab__inner">
                <div class="black-tab__left">
                    <div class="black-tab__content">
						<?php echo wp_kses_post( $settings['content'] ); ?>
                    </div>

                    <div class="black-tab__tabs">
						<?php foreach ( $tabs

						as $index => $tab ): ?>
                        <div class="black-tab__tab" data-tab-index="<?php echo esc_attr( $index ); ?>">
                            <div class="black-tab__tab-header">
                                <<?php echo esc_html( $tab['tab_title_tag'] ?: 'h3' ); ?>
                                class="black-tab__tab-title<?php echo $index === 0 ? ' black-tab__tab-title--active' : ''; ?>
                                ">
                                    <?php echo esc_html( $tab['tab_title'] ); ?>
                                </<?php echo esc_html( $tab['tab_title_tag'] ?: 'h3' ); ?>>
                                <p class="black-tab__tab-description" <?php echo $index === 0 ? '' : 'style="height: 0; opacity: 0;"'; ?>>
                                    <?php echo esc_html( $tab['tab_description'] ); ?>
                                </p>
                            </div>
                            <div class="black-tab__tab-timeline">
                                <div class="black-tab__timeline-fill"></div>
                            </div>
                        </div>
					    <?php endforeach; ?>
                    </div>
                </div>
                <div class="black-tab__right">
                    <?php foreach ( $tabs as $index => $tab ): ?>
                        <div class="black-tab__media" data-tab-index="<?php echo esc_attr( $index ); ?>"
                            <?php echo $index === 0 ? '' : 'style="translate: none; rotate: none; scale: none; opacity: 0; transform: translate(0px, 0px); height: 0;"'; ?>>
                            <?php if ( $tab['media_type'] === 'image' && ! empty( $tab['tab_image']['url'] ) ): ?>
                                <img src="<?php echo esc_url( $tab['tab_image']['url'] ); ?>"
                                     alt="<?php echo esc_attr( $tab['tab_title'] ); ?>" class="black-tab__image"/>
                            <?php elseif ( $tab['media_type'] === 'video' && ! empty( $tab['tab_video']['url'] ) ): ?>
                                <video class="black-tab__video" src="<?php echo esc_url( $tab['tab_video']['url'] ); ?>"
                                       controls></video>
                            <?php endif; ?>

                            <?php if ( ! empty( $tab['read_more_text'] ) ): ?>
                                <a href="<?php echo esc_url( $tab['read_more_link']['url'] ?? '#' ); ?>"
                                   class="black-tab__read-more"
                                   target="<?php echo esc_attr( $tab['read_more_link']['is_external'] ? '_blank' : '_self' ); ?>"
                                   rel="<?php echo esc_attr( $tab['read_more_link']['nofollow'] ? 'nofollow noopener' : 'noopener' ); ?>">
                                    <?php echo esc_html( $tab['read_more_text'] ); ?>
                                    <?php if ( ! empty( $tab['read_more_icon']['value'] ) ):
                                        Icons_Manager::render_icon( $tab['read_more_icon'], [ 'aria-hidden' => 'true' ] );
                                    endif; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

		<?php
	}

	private function render_vertical_tab( $settings ) {
		$is_autoplay    = $settings['autoplay'] === 'yes';
		$autoplay_delay = is_numeric( $settings['autoplay_delay'] ) ? (int) $settings['autoplay_delay'] : 5;
		?>
        <div class="black-tab black-tab--vertical <?php echo $is_autoplay ? 'black-tab--autoplay' : ''; ?>"
             <?php if ( $is_autoplay ): ?> data-delay="<?php echo esc_attr( $autoplay_delay * 1000 ); ?>"<?php endif; ?>>
            <div class="black-tab__tabs-wrapper">
                <div class="black-tab__tabs">
					<?php foreach ( $settings['tabs_vertical'] as $index => $tab ) : ?>
                        <div class="black-tab__tab" data-tab-index="<?php echo esc_attr( $index ); ?>">
							<?php echo esc_html( $tab['tab_title'] ); ?>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>

            <div class="black-tab__content-area">
				<?php foreach ( $settings['tabs_vertical'] as $index => $tab ) :
					$media_type = $tab['media_type'];
					$media_url = '';

					if ( $media_type === 'image' && ! empty( $tab['tab_image']['url'] ) ) {
						$media_url = esc_url( $tab['tab_image']['url'] );
					} elseif ( $media_type === 'video' && ! empty( $tab['tab_video']['url'] ) ) {
						$media_url = esc_url( $tab['tab_video']['url'] );
					}

					?>
                    <div class="black-tab__panel" data-tab-panel="<?php echo esc_attr( $index ); ?>">
                        <div class="black-tab__text-content">
							<?php if ( ! empty( $tab['tab_content'] ) ) : ?>
                                <div class="black-tab__description">
									<?php echo wp_kses_post( $tab['tab_content'] ); ?>
                                </div>
							<?php endif; ?>

                        </div>

						<?php if ( $media_url ) : ?>
                            <div class="black-tab__media">
								<?php if ( ! empty( $tab['read_more_link']['url'] ) ) : ?>
                                    <a href="<?php echo esc_url( $tab['read_more_link']['url'] ); ?>"
                                       class="black-tab__read-more"
                                       target="<?php echo esc_attr( $tab['read_more_link']['is_external'] ? '_blank' : '_self' ); ?>"
                                       rel="<?php echo esc_attr( $tab['read_more_link']['nofollow'] ? 'nofollow' : '' ); ?>">
										<?php
										if ( ! empty( $tab['read_more_icon']['value'] ) ) {
											echo '<span class="black-tab__read-more-icon">';
											Icons_Manager::render_icon( $tab['read_more_icon'], [ 'aria-hidden' => 'true' ] );
											echo '</span>';
										}
										?>
                                        <span class="black-tab__read-more-text">
                                            <?php echo esc_html( $tab['read_more_text'] ); ?>
                                        </span>
                                    </a>
								<?php endif; ?>
								<?php if ( $media_type === 'image' ) : ?>
                                    <img src="<?php echo esc_url( $media_url ); ?>" alt="<?php echo esc_html($tab['tab_title'])?>"/>
								<?php elseif ( $media_type === 'video' ) : ?>
                                    <video src="<?php echo esc_url( $media_url ); ?>" controls></video>
								<?php endif; ?>
                            </div>
						<?php endif; ?>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
        <?php
	}
}
