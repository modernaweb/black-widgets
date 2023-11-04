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
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BLACK_WIDGETS_Sentence extends \Elementor\Widget_Base {

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
		return 'b_sentence';
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
		return __( 'Black Sentence', 'blackwidgets' );
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
		return 'bw-ic-ele eicon-heading';
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

		// Select tag
		$this->add_control(
			'widget_html_tag_title',
			[
				'label' => __( 'HTML Tag', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h1',
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
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'sentence_type',
			[
				'label' => __( 'Select Type', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bw-t-1',
				'options' => [
					'bw-t-1' 	=> __( 'Text', 'blackwidgets' ),
					'bw-t-2' 	=> __( 'Image', 'blackwidgets' ),
					// 'bw-t-3' 	=> __( 'SVG/Shape', 'blackwidgets' ),
					// 'bw-t-4' 	=> __( 'video', 'blackwidgets' ),
				],
			]
		);

		$repeater->add_control(
			'sentence_title', [
				'label' => __( 'Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Make it look amazing ;)' , 'blackwidgets' ),
				'label_block' => true,
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		$repeater->add_control(
			'sentence_image',
			[
				'label' => __( 'Choose Image', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition'  => [
					'sentence_type' => [
						'bw-t-2',
					],
				],
			]
		);

		// Enable Title Section
		$repeater->add_control(
			'item_link',
			[
				'label' 		=> __( 'Link', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'blackwidgets' ),
				'label_off' 	=> __( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'link_enable',
			]
		);

		$repeater->add_control(
			'widget_link_url',
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
				'condition'  => [
					'item_link' => [
						'link_enable',
					],
				],
				'separator' => 'after',
			]
        );

		// Z-Index
		$repeater->add_control(
			'sentence_z_index',
			[
				'label' => __( 'z-index', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'number' ],
				'range' => [
					'number' => [
						'min' => -2000,
						'max' => 2000,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}' => 'z-index: {{SIZE}};',
				],
			]
		);

		$repeater->add_responsive_control(
			'sentence_image_width',
			[
				'label' => __( 'Image width', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'sentence_type' => [
						'bw-t-2',
					],
				],
			]
		);

		$repeater->start_controls_tabs('inner_tab'); // Start Tabs

		$repeater->start_controls_tab(
			'normal_style',
			[
				'label' => __( 'Normal', 'blackwidgets' ),
			]
		);

		// Background Color
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'unique_widget_bg',
				'label' => __( 'Title Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}',
			]
		);

		$repeater->add_control(
			'bg_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Color
		$repeater->add_control(
			'sentence_title_solid_color_normal',
			[
				'label' => __( 'Title Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}} a' => 'color: {{VALUE}}',
				],
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Typography
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sentence_title_typography_normal',
				'label' => __( 'Typography', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}} a',
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Text Stroke
		// Text Gradient/Image/Video
		// Text Shadow

		$repeater->add_control(
			'stroke1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Enable Title Section
		$repeater->add_control(
			'widget_stroke_title_enable',
			[
				'label' 		=> __( 'Text Stroke', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'blackwidgets' ),
				'label_off' 	=> __( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'stroke_enable',
				// 'default' 		=> 'false',
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		$repeater->add_control(
			'widget_stroke_stroke_color',
			[
				'label' => __( 'Text Stroke Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}} a' => '-webkit-text-stroke-color: {{VALUE}}',
				],
				'condition'  => [
					'widget_stroke_title_enable' => [
						'stroke_enable',
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'widget_stroke_stroke_width',
			[
				'label' => __( 'Text Stroke Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}} a' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'widget_stroke_title_enable' => [
						'stroke_enable',
					],
				],
			]
		);

		$repeater->add_control(
			'gradient_color1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		$repeater->add_control(
			'gradient_color_title_enable',
			[
				'label' 		=> __( 'Text Gradient/Image', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'blackwidgets' ),
				'label_off' 	=> __( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'gradient_enable',
				// 'default' 		=> 'false',
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Background
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'unique_widget_typography_title_gradient',
				'label' => __( 'Title Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}} a',
				'condition'  => [
					'gradient_color_title_enable' => [
						'gradient_enable',
					],
				],
			]
		);

		$repeater->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Margin
		$repeater->add_responsive_control(
			'widget_box_margin_normal',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$repeater->add_responsive_control(
			'widget_box_padding_normal',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border_normal',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}',
			]
		);

		$repeater->add_control(
			'widget_box_border_radius_normal', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow_normal',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}',
			]
		);

		$repeater->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition'  => [
					'sentence_type' => [
						'bw-t-2',
					],
				],
			]
		);

		$repeater->add_control(
			'sentence_image_position_normal',
			[
				'label' => __( 'Position', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' 	=> __( 'Normal', 'blackwidgets' ),
					'absolut' 	=> __( 'Absolut', 'blackwidgets' ),
				],
				'condition'  => [
					'sentence_type' => [
						'bw-t-2',
					],
				],
			]
		);

		// Alignment
		$repeater->add_responsive_control(
			'sentence_image_horizontal_position_normal',
			[
				'label'     => __( 'Horizontal Orientation', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'blackwidgets' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right'  => [
						'title' => __( 'Right', 'blackwidgets' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'toggle'    => true,
				'condition'  => [
					'sentence_image_position_normal' => [
						'absolut',
					],
				],
			]
		);

		// Left
		$repeater->add_control(
			'sentence_image_position_left_normal',
			[
				'label' => __( 'Offset', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -2000,
						'max' => 2000,
						'step' => 10,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'sentence_image_horizontal_position_normal' => [
						'left',
					],
				],
			]
		);

		// Right
		$repeater->add_control(
			'sentence_image_position_right_normal',
			[
				'label' => __( 'Offset', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -2000,
						'max' => 2000,
						'step' => 10,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'sentence_image_horizontal_position_normal' => [
						'right',
					],
				],
			]
		);

		// Alignment
		$repeater->add_responsive_control(
			'sentence_image_vertical_position_normal',
			[
				'label'     => __( 'Vertical Orientation', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'top'   => [
						'title' => __( 'Top', 'blackwidgets' ),
						'icon'  => 'eicon-v-align-top',
					],
					'bottom'  => [
						'title' => __( 'Bottom', 'blackwidgets' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'toggle'    => true,
				'condition'  => [
					'sentence_image_position_normal' => [
						'absolut',
					],
				],
			]
		);

		// Top
		$repeater->add_control(
			'sentence_image_position_top_normal',
			[
				'label' => __( 'Offset', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -2000,
						'max' => 2000,
						'step' => 10,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'sentence_image_vertical_position_normal' => [
						'top',
					],
				],
			]
		);

		// Bottom
		$repeater->add_control(
			'sentence_image_position_bottom_normal',
			[
				'label' => __( 'Offset', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -2000,
						'max' => 2000,
						'step' => 10,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'sentence_image_vertical_position_normal' => [
						'bottom',
					],
				],
			]
		);

		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'hover_style',
			[
				'label' => __( 'Hover', 'blackwidgets' ),
			]
		);

		// Background Color
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'unique_widget_bg_hover',
				'label' => __( 'Title Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}:hover, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}:hover',
			]
		);

		$repeater->add_control(
			'bg_hr_hover',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Color
		$repeater->add_control(
			'sentence_title_solid_color_hover',
			[
				'label' => __( 'Title Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}:hover, {{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}}',
				],
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Typography
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sentence_title_typography_hover',
				'label' => __( 'Typography', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}:hover, {{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}} a:hover',
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		$repeater->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition'  => [
					'sentence_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Margin
		$repeater->add_responsive_control(
			'widget_box_margin_hover',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}:hover, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$repeater->add_responsive_control(
			'widget_box_padding_hover',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}:hover, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border_hover',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}:hover, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}:hover',
			]
		);

		$repeater->add_control(
			'widget_box_border_radius_hover', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}:hover, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow_hover',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1{{CURRENT_ITEM}}:hover, {{WRAPPER}} .bw-sentence .bw-t-2{{CURRENT_ITEM}}:hover',
			]
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs(); // End Tabs

		$this->add_control(
			'sentence',
			[
				'label' => __( 'Sentence Repeater', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'sentence_title' 	=> __( 'Hello', 'blackwidgets' ),
					],
					[
                        'sentence_title' 	=> __( 'everybody,', 'blackwidgets' ),
					],
					[
                        'sentence_title' 	=> __( 'this is', 'blackwidgets' ),
					],
					[
                        'sentence_title' 	=> __( 'the black sentence.', 'blackwidgets' ),
					],
				],
				'title_field' => '{{{ sentence_title }}}',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Typography section
		$this->start_controls_section(
			'all_general_typography_section',
			[
				'label' => __( 'General Settings', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
        );

		// Color
		$this->add_control(
			'all_general_color_sentence',
			[
				'label' => __( 'Title Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-sentence .bw-t-1' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'all_general_typography_sentence',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-sentence .bw-t-1',
			]
		);

		//Flex Box Style:
		// Alignment
		$this->add_responsive_control(
			'justify_positioning',
			[
				'label'     => __( 'Sentence Alignment', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'From Start', 'blackwidgets' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'On Center', 'blackwidgets' ),
						'icon'  => 'eicon-h-align-center',
					],
					'strech' => [
						'title' => __( 'Strech', 'blackwidgets' ),
						'icon'  => 'eicon-h-align-stretch',
					],
					'right'  => [
						'title' => __( 'From Right', 'blackwidgets' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
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

		$settings   			= $this->get_settings_for_display();
		// Variables
		$type 	                = isset($settings['sentence_type'])				? $settings['sentence_type']				: '';
		$title_tag 				= isset($settings['widget_html_tag_title']) 	? $settings['widget_html_tag_title']		: '';
		$justify 	            = isset($settings['justify_positioning'])		? $settings['justify_positioning']			: '';
		
		$data_id				= 'bw_' . uniqid();
		$script_id				= '#' . $data_id;

		// Render
        if ( $settings['sentence'] ) {
			echo '<div class="bw-sentence bw-showcase">';
				echo '<' . $title_tag . ' class="bw-sentence-items bw-'.$justify.'">';
					foreach (  $settings['sentence'] as $item ) {

						// inner settings for each item
						if(isset($item['widget_link_url'])) {
							$link			= isset($item['widget_link_url']['url'])			? $item['widget_link_url']['url']		: '';
							$target			= $item['widget_link_url']['is_external']			? 'target="_blank"' 					: '';
							$nofollow		= $item['widget_link_url']['nofollow'] 				? ' rel="nofollow"'						: '';
						}

						$gradient			= isset($item['gradient_color_title_enable'])	? $item['gradient_color_title_enable']	: '';
						$gradient_txt		= ($gradient == 'gradient_enable')				? 'bw-gradient'							: '';

						if( $item['sentence_type'] == 'bw-t-1' ) {
							echo '<span class="elementor-repeater-item-' . $item['_id'] . ' '.$item['sentence_type'].' '.$gradient_txt.'">';
								if(isset($item['widget_link_url'])) { echo '<a href="' . $item['widget_link_url']['url'] . '"' . $target . $nofollow . ' class="bw-item-link' . $type . '">'; }
									echo $item['sentence_title'];
								if(isset($item['widget_link_url'])) { echo '</a>'; }
							echo '</span>';	
						}

						if( $item['sentence_type'] == 'bw-t-2' ) {
							$position = isset($item['sentence_image_position'])			? $item['sentence_image_position']		: '';
							echo '<span class="elementor-repeater-item-' . $item['_id'] . ' '.$item['sentence_type'].' bw-sentence-'.$position.'">';
								// echo '<img src="' . Group_Control_Image_Size::get_attachment_image_src( $item['sentence_image']['id'], 'thumbnail', $settings ) . '">';
								if(isset($item['widget_link_url'])) { echo '<a href="' . $item['widget_link_url']['url'] . '"' . $target . $nofollow . ' class="bw-item-link' . $type . '">'; }
									echo '<img src="' . $item['sentence_image']['url'] . '">';
								if(isset($item['widget_link_url'])) { echo '</a>'; }
							echo '</span>';	
						}
	
					}
				echo '</' . $title_tag . '>';
			echo '</div>';
		}

	}

}