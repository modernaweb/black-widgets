<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Title extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-title', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/title.css', [], BLACK_WIDGETS_VERSION );
    }

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
		return __( 'Black Title', 'black-widgets' );
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
		return [ 'black_widgets' ];
	}

    public function get_style_depends() {
        return [ 'black-widgets-title' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

	/**
	 * Register title widget controls.
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
				'label' => esc_html__( 'Content', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_panel_alert',
			[
				'type' => \Elementor\Controls_Manager::ALERT,
				'alert_type' => 'info',     /* info, success, warning, danger */
				'heading' => esc_html__( 'Feel free to edit. Check this widget\'s demo.', 'black-widgets' ),
				'content' => sprintf(
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-title/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'black-widgets' ),
					esc_html__( 'Demo', 'black-widgets' )
				),
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => esc_html__( 'Select Type', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bw-t-1',
				'options' => [
					'bw-t-1' => esc_html__( 'Type 1', 'black-widgets' ),
					'bw-t-2' => esc_html__( 'Type 2', 'black-widgets' ),
					'bw-t-3' => esc_html__( 'Type 3', 'black-widgets' ),
					'bw-t-4' => esc_html__( 'Type 4', 'black-widgets' ),
					'bw-t-5' => esc_html__( 'Type 5', 'black-widgets' ),
					'bw-t-6' => esc_html__( 'Type 6', 'black-widgets' ),
					'bw-t-7' => esc_html__( 'Type 7', 'black-widgets' ),
					'bw-t-8' => esc_html__( 'Type 8', 'black-widgets' ),
					'custom' => esc_html__( 'Custom', 'black-widgets' ),
				],
				'description' => esc_html__( 'We created some skin before, you can use these or no! make a new custom type.', 'black-widgets' ),
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
				'label' => esc_html__( 'Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Black Widget Title', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'description' => esc_html__( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and  ...', 'black-widgets' ),
			]
		);

		// Select tag
		$this->add_control(
			'widget_html_tag_title',
			[
				'label' => esc_html__( 'HTML Tag', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'div',
				'options' => [
					'div' => esc_html__( 'div', 'black-widgets' ),
					'h1' => esc_html__( 'H1', 'black-widgets' ),
					'h2' => esc_html__( 'H2', 'black-widgets' ),
					'h3' => esc_html__( 'H3', 'black-widgets' ),
					'h4' => esc_html__( 'H4', 'black-widgets' ),
					'h5' => esc_html__( 'H5', 'black-widgets' ),
					'h6' => esc_html__( 'H6', 'black-widgets' ),
					'p' => esc_html__( 'p', 'black-widgets' ),
					'span' => esc_html__( 'span', 'black-widgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'black-widgets' ),
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
				'label' => esc_html__( 'Subtitle', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'default' => esc_html__( 'Black Widget Subtitle', 'black-widgets' ),
				'description' => esc_html__( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and  ...', 'black-widgets' ),
			]
		);

		// Select tag
		$this->add_control(
			'widget_html_tag_subtitle',
			[
				'label' => esc_html__( 'HTML Tag', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'div',
				'options' => [
					'div' => esc_html__( 'div', 'black-widgets' ),
					'h1' => esc_html__( 'H1', 'black-widgets' ),
					'h2' => esc_html__( 'H2', 'black-widgets' ),
					'h3' => esc_html__( 'H3', 'black-widgets' ),
					'h4' => esc_html__( 'H4', 'black-widgets' ),
					'h5' => esc_html__( 'H5', 'black-widgets' ),
					'h6' => esc_html__( 'H6', 'black-widgets' ),
					'p' => esc_html__( 'p', 'black-widgets' ),
					'span' => esc_html__( 'span', 'black-widgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'black-widgets' ),
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
				'label'     => esc_html__( 'Text Alignment', 'black-widgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'black-widgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'black-widgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'black-widgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Content section
		$this->start_controls_section(
			'custom_section',
			[
				'label' => esc_html__( 'Custom Content', 'black-widgets' ),
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
				'label' => esc_html__( 'Choose Shape', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
                    'url' => BLACK_WIDGETS_PLUGIN_URL . 'assets/img/substract.svg',
				],
				'description' => esc_html__( 'We suggest you use the SVG files, but you can use PNG format event JPG file formats.', 'black-widgets' ),
			]
		);

		// Rotate
		$this->add_control(
			'custom_widget_rotate',
			[
				'label' => esc_html__( 'Rotation', 'black-widgets' ),
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
				'label' => esc_html__( 'Box Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-box',
			]
		);

		$this->add_control(
			'widget_normal_style_blur',
			[
				'label' => esc_html__( 'Blur', 'black-widgets' ),
				'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-box' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
				],
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
				'label' => esc_html__( 'Margin', 'black-widgets' ),
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
				'label' => esc_html__( 'Padding', 'black-widgets' ),
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
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-box',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
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
				'label' => esc_html__( 'Title Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'widget_title_solid_color',
			[
				'label' => esc_html__( 'Title Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
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
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-title-box .bw-title .bw-div',
			]
		);

		// Typography 8-2
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography1_8_2',
				'label' => esc_html__( 'First Letter Typography - type 8', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
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
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
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
				'label' => esc_html__( 'Title Background', 'black-widgets' ),
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
				'label' => esc_html__( 'Title Margin', 'black-widgets' ),
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
				'label' => esc_html__( 'Title Padding', 'black-widgets' ),
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
				'label' => esc_html__( 'Title Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-box .bw-title .bw-div',
			]
		);

		// Border Radius
		$this->add_control(
			'widget_typography_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
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
				'label' => esc_html__( 'Title Box Shadow', 'black-widgets' ),
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
				'label' => esc_html__( 'Subtitle Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'widget_subtitle_solid_color',
			[
				'label' => esc_html__( 'Subtitle Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
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
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div',
			]
		);

		// Typography 8-2
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography2_8_2',
				'label' => esc_html__( 'First Letter Typography - type 8', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
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
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
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
				'label' => esc_html__( 'Subtitle Background', 'black-widgets' ),
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
				'label' => esc_html__( 'Subtitle Margin', 'black-widgets' ),
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
				'label' => esc_html__( 'Subtitle Padding', 'black-widgets' ),
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
				'label' => esc_html__( 'Subtitle Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-box .bw-subtitle .bw-div',
			]
		);

		// Border Radius
		$this->add_control(
			'widget_typography_subtitle_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
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
				'label' => esc_html__( 'Subtitle Box Shadow', 'black-widgets' ),
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
				'label' => esc_html__( 'Shape Settings', 'black-widgets' ),
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
				'label' => esc_html__( 'Shape Poistion', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'relative',
				'options' => [
					'absolute' => esc_html__( 'Absolute', 'black-widgets' ),
					'relative' => esc_html__( 'Relative', 'black-widgets' ),
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
				'label' => esc_html__( 'Top Position', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'top: {{VALUE}};',
				],
				'condition'  => [
					'widget_shape_position' => [
						'absolute',
					],
				],
				'description' => esc_html__( 'Type position size with unit. e.g: 10px or auto or calc(50% - 24px)', 'black-widgets' ),
			]
		);

		// Left position
		$this->add_control(
			'widget_shape_left_position',
			[
				'label' => esc_html__( 'Left Position', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'left: {{VALUE}};',
				],
				'condition'  => [
					'widget_shape_position' => [
						'absolute',
					],
				],
				'description' => esc_html__( 'Type position size with unit. e.g: 10px or auto or calc(50% - 24px)', 'black-widgets' ),
			]
		);

		// Bottom position
		$this->add_control(
			'widget_shape_bottom_position',
			[
				'label' => esc_html__( 'Bottom Position', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'bottom: {{VALUE}};',
				],
				'condition'  => [
					'widget_shape_position' => [
						'absolute',
					],
				],
				'description' => esc_html__( 'Type position size with unit. e.g: 10px or auto or calc(50% - 24px)', 'black-widgets' ),
			]
		);

		// Right position
		$this->add_control(
			'widget_shape_right_position',
			[
				'label' => esc_html__( 'Right Position', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'selectors' => [
					'{{WRAPPER}} .bw-title-box .shape' => 'right: {{VALUE}};',
				],
				'condition'  => [
					'widget_shape_position' => [
						'absolute',
					],
				],
				'description' => esc_html__( 'Type position size with unit. e.g: 10px or auto or calc(50% - 24px)', 'black-widgets' ),
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
		//$alignment 		= '';
		$title 			= isset($settings['widget_title']) 				? $settings['widget_title'] 											: '';
		$subtitle 		= isset($settings['widget_subtitle']) 			? $settings['widget_subtitle'] 											: '';

		// Added this for XSS — Vulnerable to Cross Site Scripting (XSS)
		$allowed_tags 			= ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'];
		$title_tag 				= isset($settings['widget_html_tag_title'])				 ? $settings['widget_html_tag_title']					: '';
		$subtitle_tag   		= isset($settings['widget_html_tag_subtitle']) 			 ? $settings['widget_html_tag_subtitle']				: '';
		// Validate the HTML tag
		if (!in_array($title_tag, $allowed_tags)) {
			$title_tag = 'div';
		}
		if (!in_array($subtitle_tag, $allowed_tags)) {
			$subtitle_tag = 'div';
		}
        $title 			        = isset($settings['widget_title'])                       ? $settings['widget_title']					: '';
		$shape 			= isset($settings['custom_shape']) 				? '<img src="' . esc_url( $settings['custom_shape']['url'] ) . '" class="shape">' 	: '';

		// Render
		echo '<div class="bw-title-box ' . esc_attr( $type ) . ' ' . esc_html( $alignment ) . '">';
			echo '<div class="bw-title"><' . $title_tag . ' class="bw-div">' .esc_html($title). '</' . $title_tag . '></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $shape; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<div class="bw-subtitle"><' . $subtitle_tag . ' class="bw-div"> ' .esc_html($subtitle). '</' . $subtitle_tag . '></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';

	}

}	

class_alias('Modernaweb\BlackWidgets\Widgets\Title', 'Black_Widgets\BLACK_WIDGETS_Title');
