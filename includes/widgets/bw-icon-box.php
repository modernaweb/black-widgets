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
// use Elementor\Group_Control_Css_Filter;

use enshrined\svgSanitize\Sanitizer;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BLACK_WIDGETS_Icon_Box extends \Elementor\Widget_Base {

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
		return 'b_icon_box';
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
		return __( 'Black Icon Box Pro', 'blackwidgets' );
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
		return 'eicon-icon-box';
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
			'position_section',
			[
				'label' => esc_html__( 'Positions', 'blackwidgets' ),
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-icon-box-pro/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'blackwidgets' ),
					esc_html__( 'Demo', 'blackwidgets' )
				),
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_position',
			[
				'label' => esc_html__( 'Icon Box Position', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'position-5',
				'options' => [
					'position-1' 	=> esc_html__( 'Position 1', 'blackwidgets' ),
					'position-2' 	=> esc_html__( 'Position 2', 'blackwidgets' ),
					'position-3' 	=> esc_html__( 'Position 3', 'blackwidgets' ),
					'position-4' 	=> esc_html__( 'Position 4', 'blackwidgets' ),
					'position-5' 	=> esc_html__( 'Position 5', 'blackwidgets' ),
					'position-6' 	=> esc_html__( 'Position 6', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Select position of the Icon Box.', 'blackwidgets' ),
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Enable Title Section
		$this->add_control(
			'widget_title_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Title?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'title_enable',
				'default' 		=> 'title_enable',
			]
		);

		// Title
		$this->add_control(
			'widget_title',
			[
				'label' => esc_html__( 'Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'This is Title', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'description' => esc_html__( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and  ...', 'blackwidgets' ),
				'condition'  => [
					'widget_title_enable' => [
						'title_enable',
					],
				],
			]
		);

		// HTML Tag
		$this->add_control(
			'widget_html_tag_title',
			[
				'label' => esc_html__( 'HTML Tag', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'div' => esc_html__( 'div', 'blackwidgets' ),
					'h1' => esc_html__( 'H1', 'blackwidgets' ),
					'h2' => esc_html__( 'H2', 'blackwidgets' ),
					'h3' => esc_html__( 'H3', 'blackwidgets' ),
					'h4' => esc_html__( 'H4', 'blackwidgets' ),
					'h5' => esc_html__( 'H5', 'blackwidgets' ),
					'h6' => esc_html__( 'H6', 'blackwidgets' ),
					'p' => esc_html__( 'p', 'blackwidgets' ),
					'span' => esc_html__( 'span', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'blackwidgets' ),
				'condition'  => [
					'widget_title_enable' => [
						'title_enable',
					],
				],
				'separator' => 'after',
			]
		);

		// Enable Subtitle Section
		$this->add_control(
			'widget_subtitle_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Subtitle?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'subtitle_enable',
				'default' 		=> 'off',
			]
		);

		// Subtitle
		$this->add_control(
			'widget_subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'This is Subtitle', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your subtitle here', 'blackwidgets' ),
				'description' => esc_html__( 'You can use all other HTML tags into the subtitle field e.g. code, mark, abbr, blockquote and  ...', 'blackwidgets' ),
				'condition'  => [
					'widget_subtitle_enable' => [
						'subtitle_enable',
					],
				],
			]
		);

		// HTML Tag
		$this->add_control(
			'widget_html_tag_subtitle',
			[
				'label' => esc_html__( 'HTML Tag', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => [
					'div' => esc_html__( 'div', 'blackwidgets' ),
					'h1' => esc_html__( 'H1', 'blackwidgets' ),
					'h2' => esc_html__( 'H2', 'blackwidgets' ),
					'h3' => esc_html__( 'H3', 'blackwidgets' ),
					'h4' => esc_html__( 'H4', 'blackwidgets' ),
					'h5' => esc_html__( 'H5', 'blackwidgets' ),
					'h6' => esc_html__( 'H6', 'blackwidgets' ),
					'p' => esc_html__( 'p', 'blackwidgets' ),
					'span' => esc_html__( 'span', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'blackwidgets' ),
				'condition'  => [
					'widget_subtitle_enable' => [
						'subtitle_enable',
					],
				],
				'separator' => 'after',
			]
		);

		// Enable Paragraph Section
		$this->add_control(
			'widget_paragraph_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Paragraph?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'paragraph_enable',
				'default' 		=> 'off',
			]
		);

		$this->add_control(
			'widget_paragraph',
			[
				'label' => esc_html__( 'Paragraph', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 4,
				'default' => esc_html__( 'This is paragraph', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your paragraph here', 'blackwidgets' ),
				'condition'  => [
					'widget_paragraph_enable' => [
						'paragraph_enable',
					],
				],
				'separator' => 'after',
			]
		);

		// Enable Link Section
		$this->add_control(
			'widget_link_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Link?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'link_enable',
				'default' 		=> 'off',
			]
		);

		$this->add_control(
			'widget_link_text',
			[
				'label' => esc_html__( 'Link Text', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Read more', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_link_enable' => [
						'link_enable',
					],
				],
			]
		);

		$this->add_control(
			'widget_link_url',
			[
				'label' => esc_html__( 'Link', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'blackwidgets' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
                ],
				'condition'  => [
					'widget_link_enable' => [
						'link_enable',
					],
				],
				'separator' => 'after',
			]
        );

		// Enable Icon/Image/SVG Section
		$this->add_control(
			'widget_icon_image_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Icon or Image?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'icon_image_enable',
				'default' 		=> 'icon_image_enable',
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_icon_image_type',
			[
				'label' => esc_html__( 'How exactly does it work?', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'enable_icon',
				'options' => [
					'enable_icon' 			=> esc_html__( 'With ICON', 'blackwidgets' ),
					'enable_image'			=> esc_html__( 'With IMAGE or SVG', 'blackwidgets' ),
					'enable_code'			=> esc_html__( 'With SVG Code', 'blackwidgets' ),
				],
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
				],
			]
		);

		// Subtitle
		$this->add_control(
			'widget_code',
			[
				'label' => esc_html__( 'CODE', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'SVG CODE HERE', 'blackwidgets' ),
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_code',
					],
				],
			]
		);

		$this->add_control(
			'widget_icon',
			[
				'label' => esc_html__( 'Choose Icon', 'blackwidgets' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'eicon eicon-nerd',
					'library' => 'elementor',
				],
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_icon',
					],
				],
			]
		);

		$this->add_control(
			'widget_image',
			[
				'label' => esc_html__( 'Choose Image', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_image',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'full',
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_image',
					],
				],
			]
        );

		// Enable Paragraph Section
		$this->add_control(
			'widget_svg_animate',
			[
				'label' 		=> esc_html__( 'SVG Animate!', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'bw-iconbox-svg-animate',
				'default' 		=> 'off',
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Box Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Box Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Alignment
		$this->add_responsive_control(
			'box_text_alignment',
			[
				'label'     => esc_html__( 'Box Alignment', 'blackwidgets' ),
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
				'condition'  => [
					'widget_position' => [
						'position-1',
						'position-2',
					],
				],
			]
		);

		// Alignment
		$this->add_responsive_control(
			'box_b_text_alignment',
			[
				'label'     => esc_html__( 'Box Alignment', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'    => true,
				'condition'  => [
					'widget_position' => [
						'position-3',
						'position-4',
						'position-5',
						'position-6',
					],
				],
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-iconbox',
			]
		);

		$this->add_control(
			'widget_normal_style_blur',
			[
				'label' => esc_html__( 'Blur', 'blackwidgets' ),
				'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'blackwidgets' ),
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
					'{{WRAPPER}} .bw-iconbox' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
				],
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
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-iconbox' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_1_hover',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
			]
		);

		$this->add_control(
			'widget_hover_style_blur',
			[
				'label' => esc_html__( 'Blur', 'blackwidgets' ),
				'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'blackwidgets' ),
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
					'{{WRAPPER}} .bw-iconbox:hover' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
				],
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
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_hover_box_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Title Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_title_enable' => [
						'title_enable',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_title_tab'); // Start Tabs
		$this->start_controls_tab(
			'title_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_title_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_title_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-it-is-title' => 'color: {{VALUE}}',
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
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_title_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_title_text_alignment',
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

		// Margin
		$this->add_responsive_control(
			'content_title__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_title__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_title__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Border Radius
		$this->add_control(
			'content_title__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_title__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Subtitle Style section
		$this->start_controls_section(
			'subtitle_style_section',
			[
				'label' => esc_html__( 'Subtitle Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_subtitle_enable' => [
						'subtitle_enable',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_subtitle_tab'); // Start Tabs
		$this->start_controls_tab(
			'subtitle_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_subtitle_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'subtitle_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_subtitle_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-it-is-subtitle' => 'color: {{VALUE}}',
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
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_subtitle_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_subtitle_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_subtitle_text_alignment',
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

		// Margin
		$this->add_responsive_control(
			'content_subtitle__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_subtitle__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_subtitle__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Border Radius
		$this->add_control(
			'content_subtitle__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_subtitle__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		$this->end_controls_section();
        // End

        // Start
		// Paragraph Style section
		$this->start_controls_section(
			'paragraph_style_section',
			[
				'label' => esc_html__( 'Paragraph Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_paragraph_enable' => [
						'paragraph_enable',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_paragraph_tab'); // Start Tabs
		$this->start_controls_tab(
			'paragraph_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_paragraph_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'paragraph_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_paragraph_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-it-is-paragraph' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->add_control(
			'paragraph_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_paragraph_typographys',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_paragraph_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_paragraph_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_paragraph_text_alignment',
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

		// Margin
		$this->add_responsive_control(
			'content_paragraph__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_paragraph__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_paragraph__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Border Radius
		$this->add_control(
			'content_paragraph__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_paragraph__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		$this->end_controls_section();
        // End

        // Start
		// Link Style section
		$this->start_controls_section(
			'link_style_section',
			[
				'label' => esc_html__( 'Link Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_link_enable' => [
						'link_enable',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_link_tab'); // Start Tabs
		$this->start_controls_tab(
			'link_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_link_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'link_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_link_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn:hover' => 'color: {{VALUE}}',
				],
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
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_link_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_link_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_link_text_alignment',
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

		// Margin
		$this->add_responsive_control(
			'content_link__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_link__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_link__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Border Radius
		$this->add_control(
			'content_link__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_link__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Icon Style section
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__( 'Icon Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_icon_image_type' => [
						'enable_icon',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_icon_tab'); // Start Tabs
		$this->start_controls_tab(
			'icon_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_icon_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		// Color
		$this->add_control(
			'widget_svg_fill_icon_box_icon_normal_color',
			[
				'label' => esc_html__( 'SVG(fill) Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		// Color
		$this->add_control(
			'widget_svg_stroke_icon_box_icon_normal_color',
			[
				'label' => esc_html__( 'SVG(stroke) Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'stroke: {{VALUE}}',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_icon_normal_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'icon_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_icon_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'widget_svg_fill_icon_box_icon_hover_color',
			[
				'label' => esc_html__( 'SVG(fill) Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'widget_svg_stroke_icon_box_icon_hover_color',
			[
				'label' => esc_html__( 'SVG(stroke) Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon:hover svg' => 'stroke: {{VALUE}}',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_icon_hover_background',
				'label' => esc_html__( 'Hover Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon:hover i, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon:hover svg',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->add_control(
			'icon_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'content_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'blackwidgets' ),
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
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'content_svg_icon_size',
			[
				'label' => esc_html__( 'SVG Width', 'blackwidgets' ),
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
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
				],
			]
		);

		// Alignment
		$this->add_responsive_control(
			'the_icon_alignment',
			[
				'label'     => esc_html__( 'Icon Position', 'blackwidgets' ),
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

		// Margin
		$this->add_responsive_control(
			'content_icon__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_icon__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_icon__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i',
			]
		);

		// Border Radius
		$this->add_control(
			'content_icon__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_icon__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Image Style section
		$this->start_controls_section(
			'image_style_section',
			[
				'label' => esc_html__( 'Image Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_icon_image_type' => [
						'enable_image',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_image_tab'); // Start Tabs
		$this->start_controls_tab(
			'image_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_image_normal_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img img',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'image_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_image_hover_background',
				'label' => esc_html__( 'Hover Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img:hover img',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->add_control(
			'image_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'content_image_size',
			[
				'label' => esc_html__( 'Image Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto;',
				],
			]
		);

		// // Alignment
		// $this->add_responsive_control(
		// 	'content_image_text_alignment',
		// 	[
		// 		'label'     => esc_html__( 'Image Alignment', 'blackwidgets' ),
		// 		'type'      => \Elementor\Controls_Manager::CHOOSE,
		// 		'options'   => [
		// 			'left'   => [
		// 				'title' => esc_html__( 'Left', 'blackwidgets' ),
		// 				'icon'  => 'fas fa-long-arrow-alt-left',
		// 			],
		// 			'center' => [
		// 				'title' => esc_html__( 'Center', 'blackwidgets' ),
		// 				'icon'  => 'fas fa-compress-arrows-alt',
		// 			],
		// 			'right'  => [
		// 				'title' => esc_html__( 'Right', 'blackwidgets' ),
		// 				'icon'  => 'fas fa-long-arrow-alt-right',
		// 			],
		// 		],
		// 		'toggle'    => true,
		// 	]
		// );

		// Margin
		$this->add_responsive_control(
			'content_image_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_image_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_image_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img img',
			]
		);

		// Border Radius
		$this->add_control(
			'content_image_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_image_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img img',
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

		// Variables
		$settings   			= $this->get_settings_for_display();
		// Title
		$enable_title			= 'title_enable' === $settings['widget_title_enable']			? $settings['widget_title_enable'] 				: '';
		$allowed_tags 			= ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'];
		$title_tag 				= isset($settings['widget_html_tag_title']) 					? $settings['widget_html_tag_title']			: ''; // HTML Tag
		// Validate the HTML tag
		if (!in_array($title_tag, $allowed_tags)) {
			$title_tag 			= 'h3';
		}
		$title 					= isset($settings['widget_title']) 								? $settings['widget_title']						: '';
		// Subtitle
		$enable_subtitle		= 'subtitle_enable' === $settings['widget_subtitle_enable']		? $settings['widget_subtitle_enable'] 			: '';
		$subtitle_tag			= isset($settings['widget_html_tag_subtitle']) 					? $settings['widget_html_tag_subtitle']			: ''; // HTML Tag
		// Validate the HTML tag
		if (!in_array($subtitle_tag, $allowed_tags)) {
			$subtitle_tag 		= 'h4';
		}
		$subtitle				= isset($settings['widget_subtitle'])							? $settings['widget_subtitle']					: '';
		// Paragraph
		$enable_paragraph		= 'paragraph_enable' === $settings['widget_paragraph_enable']	? $settings['widget_paragraph_enable'] 			: '';
		$paragraph				= isset($settings['widget_paragraph'])							? $settings['widget_paragraph']					: '';
		// Image Or Icon
		$enable_icon_image		= 'icon_image_enable' === $settings['widget_icon_image_enable']	? $settings['widget_icon_image_enable'] 		: '';
		$icon_image				= isset($settings['widget_icon_image_type'])					? $settings['widget_icon_image_type']			: '';
		$iconset				= isset($settings['widget_icon'])								? $settings['widget_icon']						: ''; // Icon
		$svgcode				= isset($settings['widget_code'])								? $settings['widget_code']						: ''; // Icon
		// Link
		$enable_link			= 'link_enable' === $settings['widget_link_enable']				? $settings['widget_link_enable'] 				: '';
		$link					= isset($settings['widget_link_url']['url'])					? $settings['widget_link_url']['url']			: ''; // Link URL
		$link_text 	        	= isset($settings['widget_link_text'])							? $settings['widget_link_text'] 				: ''; // Link Text
		$link_target         	= isset($settings['widget_link_url']['is_external'])			? 'target="_blank"' 							: '';
		$link_nofollow       	= isset($settings['widget_link_url']['nofollow'] )				? ' rel="nofollow"'								: '';
		// Position Options
		$position				= isset( $settings['widget_position'] )							? $settings['widget_position']					: '';
		// Alignment
		$box_align				= isset($settings['box_text_alignment'])						? $settings['box_text_alignment']				: '';
		$box_b_align			= isset($settings['box_b_text_alignment'])						? $settings['box_b_text_alignment']				: '';
		$title_align			= isset($settings['content_title_text_alignment'])				? $settings['content_title_text_alignment']		: '';
		$subtitle_align			= isset($settings['content_subtitle_text_alignment'])			? $settings['content_subtitle_text_alignment']	: '';
		$paragraph_align		= isset($settings['content_paragraph_text_alignment'])			? $settings['content_paragraph_text_alignment']	: '';
		$link_align				= isset($settings['content_link_text_alignment'])				? $settings['content_link_text_alignment']		: '';
		// Icon Position:
		$icon_position			= isset($settings['the_icon_alignment'])						? $settings['the_icon_alignment']				: '';
		$svg_animate			= 'bw-iconbox-svg-animate' === $settings['widget_svg_animate']	? $settings['widget_svg_animate'] 				: '';
		$data_id				= 'bw_' . uniqid();
		$animate_id				= '#' . $data_id;

        $sanitizer = new Sanitizer();
        $svgcode = $sanitizer->sanitize( $svgcode );

		// Render 
		switch ($position) {
			case 'position-2':
				echo '<div id="' . $data_id . '" class="bw-iconbox bw-box-'. esc_attr( $box_align ).' bw-' . esc_attr( $position ) . ' ' . $svg_animate . '">';
					// Title
					if ( $enable_title ) echo '<' . $title_tag . ' class="bw-it-is-title ' . esc_attr( $title_align ) . '">' . esc_html($title) . '</' . $title_tag . '>';
					// Subtitle
					if ( $enable_subtitle ) echo '<' . $subtitle_tag . ' class="bw-it-is-subtitle ' . esc_attr( $subtitle_align ) . '">' . esc_html($subtitle) . '</' . $subtitle_tag . '>';
					// Image
					if ( $enable_icon_image ) if ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon ' . esc_attr( $icon_position ) . '">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] ); echo '</div>'; elseif ( $icon_image == 'enable_code' ): echo '<div class="bw-iconbox-img xcv--mw ' . esc_attr( $box_align ) . '">'.$svgcode.'</div>'; else: echo '<div class="bw-iconbox-img ' . esc_attr( $box_align ) . '"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>'; endif;
					// Paragraph
					if ( $enable_paragraph ) echo '<p class="bw-it-is-paragraph ' . esc_attr( $paragraph_align ) . '">' . esc_html($paragraph) . '</p>';
					// Link
					if ( $enable_link ) echo '<a href="' . esc_url( $link ) . '"' . $link_target . $link_nofollow . ' class="bw-btn ' . esc_attr( $link_align ) . '">' . esc_html($link_text) . '</a>';
				echo '</div>';
				break;

				case 'position-3':
				case 'position-4':
					echo '<div id="' . $data_id . '" class="bw-iconbox bw-box-'. esc_attr( $box_b_align ) .' bw-' . esc_attr( $position ) . ' ' . $svg_animate . '">';
						echo '<div class="bw-image-wrap">';
							// Image
							if ( $enable_icon_image ) if ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon  ' . esc_attr( $icon_position ) . '">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] ); echo '</div>'; elseif ( $icon_image == 'enable_code' ): echo '<div class="bw-iconbox-img xcv--mw">'.$svgcode.'</div>'; else: echo '<div class="bw-iconbox-img  ' . esc_attr( $box_align ) . '"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>'; endif;
						echo '</div>';
						echo '<div class="bw-content-wrap">';
							// Title
							if ( $enable_title ) echo '<' . $title_tag . ' class="bw-it-is-title ' . esc_attr( $title_align ) . '">' . esc_html($title) . '</' . $title_tag . '>';
							// Subtitle
							if ( $enable_subtitle ) echo '<' . $subtitle_tag . ' class="bw-it-is-subtitle ' . esc_attr( $subtitle_align ) . '">' . esc_html($subtitle) . '</' . $subtitle_tag . '>';
							// Paragraph
							if ( $enable_paragraph ) echo '<p class="bw-it-is-paragraph ' . esc_attr( $paragraph_align ) . '">' . esc_html($paragraph) . '</p>';
							// Link
							if ( $enable_link ) echo '<a href="' . esc_url( $link ) . '"' . $link_target . $link_nofollow . ' class="bw-btn ' . esc_attr( $link_align ) . '">' . esc_html($link_text) . '</a>';
						echo '</div>';
					echo '</div>';
					break;

			case 'position-5':
			case 'position-6':
				echo '<div id="' . $data_id . '" class="bw-iconbox bw-box-'. esc_attr( $box_b_align ) .' bw-' . esc_attr( $position ) . ' ' . $svg_animate . '">';
					echo '<div class="bw-wrapper">';
					// Image
					if ( $enable_icon_image ) if ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon  ' . esc_attr( $icon_position ) . '">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] ); echo '</div>'; elseif ( $icon_image == 'enable_code' ): echo '<div class="bw-iconbox-img xcv--mw">'.$svgcode.'</div>';else: echo '<div class="bw-iconbox-img ' . esc_attr( $box_align ) . '"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>'; endif;
						echo '<div class="bw-typography-wrapper">';
							echo '<div class="bw-icon-box-title">';
								// Title
								if ( $enable_title ) echo '<' . $title_tag . ' class="bw-it-is-title ' . esc_attr( $title_align ) . '">' . esc_html($title) . '</' . $title_tag . '>';
								// Subtitle
								if ( $enable_subtitle ) echo '<' . $subtitle_tag . ' class="bw-it-is-subtitle ' . esc_attr( $subtitle_align ) . '">' . esc_html($subtitle) . '</' . $subtitle_tag . '>';
							echo '</div>';
							// Paragraph
							if ( $enable_paragraph ) echo '<p class="bw-it-is-paragraph ' . esc_attr( $paragraph_align ) . '">' . esc_html($paragraph) . '</p>';
							// Link
							if ( $enable_link ) echo '<a href="' . esc_url( $link ) . '"' . $link_target . $link_nofollow . ' class="bw-btn ' . esc_attr( $link_align ) . '">' . esc_html($link_text) . '</a>';
						echo '</div>';	
					echo '</div>';
				echo '</div>';
				break;

			default:
				echo '<div id="' . $data_id . '" class="bw-iconbox bw-box-'. esc_attr( $box_align ) .' bw-' . esc_attr( $position ) . ' ' . $svg_animate . '">';
						// Image
						// echo '<div class="bw-iconbox-icon-svg-code"><div class="bw-iconbox-img xcv--mw">'.$svgcode.'</div></div>';
						if ( $enable_icon_image ) if ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon ' . esc_attr( $icon_position ) . '">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] );echo '</div>'; elseif ( $icon_image == 'enable_code' ): echo '<div class="bw-iconbox-img xcv--mw">'.$svgcode.'</div>'; else: echo '<div class="bw-iconbox-img ' . esc_attr( $box_align ) . '"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>'; endif;
						// elseif ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] ); echo '</div>';  else: echo '<div class="bw-iconbox-img"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>';
						// Title
						if ( $enable_title ) echo '<' . $title_tag . ' class="bw-it-is-title ' . esc_attr( $title_align ) . '">' . esc_html($title) . '</' . $title_tag . '>';
						// Subtitle
						if ( $enable_subtitle ) echo '<' . $subtitle_tag . ' class="bw-it-is-subtitle ' . esc_attr( $subtitle_align ) . '">' . esc_html($subtitle) . '</' . $subtitle_tag . '>';
						// Paragraph
						if ( $enable_paragraph ) echo '<p class="bw-it-is-paragraph ' . esc_attr( $paragraph_align ) . '">' . esc_html($paragraph) . '</p>';
						// Link
						if ( $enable_link ) echo '<a href="' . esc_url( $link ) . '"' . $link_target . $link_nofollow . ' class="bw-btn ' . $link_align . '">' . esc_html($link_text) . '</a>';
					echo '</div>';
					break;
		}
	
		echo '<script>
			jQuery(window).ready(function($) {
				var scroll = jQuery(window).scrollTop();
				var objectSelect = jQuery("'. $animate_id .'");
				var bottom = jQuery(window).height();
				var objectPosition = objectSelect.offset().top - bottom;
				if (scroll > objectPosition) {
					jQuery("'. $animate_id .'").addClass("run");
				} else {
					jQuery("'. $animate_id .'").removeClass("run");
				}
			});

			jQuery(window).scroll(function($) {
					var top = jQuery("body").offset();
					var scroll = jQuery(window).scrollTop();
					var objectSelect = jQuery("'. $animate_id .'");
					var bottom = jQuery(window).height();
					var objectPosition = objectSelect.offset().top - bottom;
					if (scroll > objectPosition) {
						jQuery("'. $animate_id .'").addClass("run");
					} else {
						jQuery("'. $animate_id .'").removeClass("run");
					}
			});
		</script>';

	}

}
