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
use Elementor\Group_Control_Image_Size;
// use Elementor\Group_Control_Css_Filter;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BW_Icon_Box extends \Elementor\Widget_Base {

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
		return __( 'Black Icon Box Pro', 'bw' );
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
			'position_section',
			[
				'label' => __( 'Positions', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_position',
			[
				'label' => __( 'Icon Box Position(s)', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'position-1',
				'options' => [
					'position-1' 	=> __( 'Position 1', 'bw' ),
					'position-2' 	=> __( 'Position 2', 'bw' ),
					'position-3' 	=> __( 'Position 3', 'bw' ),
					'position-4' 	=> __( 'Position 4', 'bw' ),
					'position-5' 	=> __( 'Position 5', 'bw' ),
					'position-6' 	=> __( 'Position 6', 'bw' ),
				],
				'description' => __( 'Select position of the Icon Box.', 'bw' ),
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Enable Title Section
		$this->add_control(
			'widget_title_enable',
			[
				'label' 		=> __( 'Do You Need Title?', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'bw' ),
				'label_off' 	=> __( 'No !', 'bw' ),
				'return_value' 	=> 'title_enable',
				'default' 		=> 'title_enable',
			]
		);

		// Title
		$this->add_control(
			'widget_title',
			[
				'label' => __( 'Title', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'This is Title', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
				'description' => __( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and  ...', 'bw' ),
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
				'label' => __( 'HTML Tag', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h3',
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
				'label' 		=> __( 'Do You Need Subtitle?', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'bw' ),
				'label_off' 	=> __( 'No !', 'bw' ),
				'return_value' 	=> 'subtitle_enable',
				'default' 		=> 'off',
			]
		);

		// Subtitle
		$this->add_control(
			'widget_subtitle',
			[
				'label' => __( 'Subtitle', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'This is Subtitle', 'bw' ),
				'placeholder' => __( 'Type your subtitle here', 'bw' ),
				'description' => __( 'You can use all other HTML tags into the subtitle field e.g. code, mark, abbr, blockquote and  ...', 'bw' ),
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
				'label' => __( 'HTML Tag', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h4',
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
				'label' 		=> __( 'Do You Need Paragraph?', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'bw' ),
				'label_off' 	=> __( 'No !', 'bw' ),
				'return_value' 	=> 'paragraph_enable',
				'default' 		=> 'off',
			]
		);

		$this->add_control(
			'widget_paragraph',
			[
				'label' => __( 'Paragraph', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 4,
				'default' => __( 'This is paragraph', 'bw' ),
				'placeholder' => __( 'Type your paragraph here', 'bw' ),
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
				'label' 		=> __( 'Do You Need Link?', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'bw' ),
				'label_off' 	=> __( 'No !', 'bw' ),
				'return_value' 	=> 'link_enable',
				'default' 		=> 'off',
			]
		);

		$this->add_control(
			'widget_link_text',
			[
				'label' => __( 'Link Text', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Read more', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
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
				'label' => __( 'Link', 'bw' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'bw' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
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
				'label' 		=> __( 'Do You Need Icon or Image?', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Yes', 'bw' ),
				'label_off' 	=> __( 'No !', 'bw' ),
				'return_value' 	=> 'icon_image_enable',
				'default' 		=> 'icon_image_enable',
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_icon_image_type',
			[
				'label' => __( 'How exactly does it work?', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'enable_icon',
				'options' => [
					'enable_icon' 			=> __( 'With ICON', 'bw' ),
					'enable_image'			=> __( 'With IMAGE or SVG', 'bw' ),
				],
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
				],
			]
		);

		$this->add_control(
			'widget_icon',
			[
				'label' => __( 'Choose Icon', 'bw' ),
				'type' => Controls_Manager::ICONS,
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
				'label' => __( 'Choose Image', 'bw' ),
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

		$this->end_controls_section();
		// End

		// Start
		// Box Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Box Style', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-iconbox',
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Title Styles', 'bw' ),
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
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_title_normal_color',
			[
				'label' => __( 'Normal Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_tab_2',
			[
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_title_hover_color',
			[
				'label' => __( 'Hover Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
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
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_title_text_shadow',
				'label' => __( 'Text Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_title_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_title_text_alignment',
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

		// Margin
		$this->add_responsive_control(
			'content_title__margin',
			[
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Border Radius
		$this->add_control(
			'content_title__border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Subtitle Styles', 'bw' ),
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
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_subtitle_normal_color',
			[
				'label' => __( 'Normal Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'subtitle_tab_2',
			[
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_subtitle_hover_color',
			[
				'label' => __( 'Hover Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
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
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_subtitle_text_shadow',
				'label' => __( 'Text Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_subtitle_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_subtitle_text_alignment',
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

		// Margin
		$this->add_responsive_control(
			'content_subtitle__margin',
			[
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Border Radius
		$this->add_control(
			'content_subtitle__border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Paragraph Styles', 'bw' ),
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
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_paragraph_normal_color',
			[
				'label' => __( 'Normal Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'paragraph_tab_2',
			[
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_paragraph_hover_color',
			[
				'label' => __( 'Hover Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
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
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_paragraph_text_shadow',
				'label' => __( 'Text Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_paragraph_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_paragraph_text_alignment',
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

		// Margin
		$this->add_responsive_control(
			'content_paragraph__margin',
			[
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Border Radius
		$this->add_control(
			'content_paragraph__border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Link Styles', 'bw' ),
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
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_link_normal_color',
			[
				'label' => __( 'Normal Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'link_tab_2',
			[
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_link_hover_color',
			[
				'label' => __( 'Hover Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
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
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_link_text_shadow',
				'label' => __( 'Text Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_link_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_link_text_alignment',
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

		// Margin
		$this->add_responsive_control(
			'content_link__margin',
			[
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Border Radius
		$this->add_control(
			'content_link__border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Icon Styles', 'bw' ),
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
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_icon_normal_color',
			[
				'label' => __( 'Normal Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i' => 'color: {{VALUE}}',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_icon_normal_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'icon_tab_2',
			[
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_icon_hover_color',
			[
				'label' => __( 'Hover Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_icon_hover_background',
				'label' => __( 'Hover Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon:hover i',
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
				'label' => __( 'Icon Size', 'bw' ),
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

		// // Alignment
		// $this->add_responsive_control(
		// 	'content_icon_text_alignment',
		// 	[
		// 		'label'     => __( 'Icon Alignment', 'bw' ),
		// 		'type'      => \Elementor\Controls_Manager::CHOOSE,
		// 		'options'   => [
		// 			'left'   => [
		// 				'title' => __( 'Left', 'bw' ),
		// 				'icon'  => 'fas fa-long-arrow-alt-left',
		// 			],
		// 			'center' => [
		// 				'title' => __( 'Center', 'bw' ),
		// 				'icon'  => 'fas fa-compress-arrows-alt',
		// 			],
		// 			'right'  => [
		// 				'title' => __( 'Right', 'bw' ),
		// 				'icon'  => 'fas fa-long-arrow-alt-right',
		// 			],
		// 		],
		// 		'toggle'    => true,
		// 	]
		// );

		// Margin
		$this->add_responsive_control(
			'content_icon__margin',
			[
				'label' => __( 'Margin', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_icon__padding',
			[
				'label' => __( 'Padding', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_icon__border',
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i',
			]
		);

		// Border Radius
		$this->add_control(
			'content_icon__border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_icon__box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Image Style section
		$this->start_controls_section(
			'image_style_section',
			[
				'label' => __( 'Image Styles', 'bw' ),
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
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_image_normal_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img img',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'image_tab_2',
			[
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_image_hover_background',
				'label' => __( 'Hover Background', 'bw' ),
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
				'label' => __( 'Image Size', 'bw' ),
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
		// 		'label'     => __( 'Image Alignment', 'bw' ),
		// 		'type'      => \Elementor\Controls_Manager::CHOOSE,
		// 		'options'   => [
		// 			'left'   => [
		// 				'title' => __( 'Left', 'bw' ),
		// 				'icon'  => 'fas fa-long-arrow-alt-left',
		// 			],
		// 			'center' => [
		// 				'title' => __( 'Center', 'bw' ),
		// 				'icon'  => 'fas fa-compress-arrows-alt',
		// 			],
		// 			'right'  => [
		// 				'title' => __( 'Right', 'bw' ),
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img img',
			]
		);

		// Border Radius
		$this->add_control(
			'content_image_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Box Shadow', 'bw' ),
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
		$title 					= isset($settings['widget_title']) 								? $settings['widget_title']						: '';
		$title_tag 				= isset($settings['widget_html_tag_title']) 					? $settings['widget_html_tag_title']			: ''; // HTML Tag
		// Subtitle
		$enable_subtitle		= 'subtitle_enable' === $settings['widget_subtitle_enable']		? $settings['widget_subtitle_enable'] 			: '';
		$subtitle				= isset($settings['widget_subtitle'])							? $settings['widget_subtitle']					: '';
		$subtitle_tag			= isset($settings['widget_html_tag_subtitle']) 					? $settings['widget_html_tag_subtitle']			: ''; // HTML Tag
		// Paragraph
		$enable_paragraph		= 'paragraph_enable' === $settings['widget_paragraph_enable']	? $settings['widget_paragraph_enable'] 			: '';
		$paragraph				= isset($settings['widget_paragraph'])							? $settings['widget_paragraph']					: '';
		// Image Or Icon
		$enable_icon_image		= 'icon_image_enable' === $settings['widget_icon_image_enable']	? $settings['widget_icon_image_enable'] 		: '';
		$icon_image				= isset( $settings['widget_icon_image_type'] )					? $settings['widget_icon_image_type']			: '';
		$iconset				= isset($settings['widget_icon'])								? $settings['widget_icon']						: ''; // Icon
		// Link
		$enable_link			= 'link_enable' === $settings['widget_link_enable']				? $settings['widget_link_enable'] 				: '';
		$link					= isset($settings['widget_link_url']['url'])					? $settings['widget_link_url']['url']			: ''; // Link URL
		$link_text 	        	= isset($settings['widget_link_text'])							? $settings['widget_link_text'] 				: ''; // Link Text
		$link_target         	= $settings['widget_link_url']['is_external'] 					? 'target="_blank"' 							: '';
		$link_nofollow       	= $settings['widget_link_url']['nofollow'] 						? ' rel="nofollow"'								: '';
		// Position Options
		$position				= isset( $settings['widget_position'] )							? $settings['widget_position']					: '';
		// Alignment
		$title_align			= isset($settings['content_title_text_alignment'])				? $settings['content_title_text_alignment']		: '';
		$subtitle_align			= isset($settings['content_subtitle_text_alignment'])			? $settings['content_subtitle_text_alignment']	: '';
		$paragraph_align		= isset($settings['content_paragraph_text_alignment'])			? $settings['content_paragraph_text_alignment']	: '';
		$link_align				= isset($settings['content_link_text_alignment'])				? $settings['content_link_text_alignment']		: '';

		// Render 
		switch ($position) {
			case 'position-2':
				echo '<div class="bw-iconbox bw-' . $position . '">';
					// Title
					if ( $enable_title ) echo '<' . $title_tag . ' class="bw-it-is-title ' . $title_align . '">' . $title . '</' . $title_tag . '>';
					// Subtitle
					if ( $enable_subtitle ) echo '<' . $subtitle_tag . ' class="bw-it-is-subtitle ' . $subtitle_align . '">' . $subtitle . '</' . $subtitle_tag . '>';
					// Image
					if ( $enable_icon_image ) if ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] ); echo '</div>'; else: echo '<div class="bw-iconbox-img"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>'; endif;
					// Paragraph
					if ( $enable_paragraph ) echo '<p class="bw-it-is-paragraph ' . $paragraph_align . '">' . $paragraph . '</p>';
					// Link
					if ( $enable_link ) echo '<a href="' . $link . '"' . $link_target . $link_nofollow . ' class="bw-btn ' . $link_align . '">' . $link_text . '</a>';
				echo '</div>';
				break;

				case 'position-3':
				case 'position-4':
					echo '<div class="bw-iconbox bw-' . $position . '">';
						echo '<div class="bw-image-wrap">';
							// Image
							if ( $enable_icon_image ) if ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] ); echo '</div>'; else: echo '<div class="bw-iconbox-img"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>'; endif;
						echo '</div>';
						echo '<div class="bw-content-wrap">';
							// Title
							if ( $enable_title ) echo '<' . $title_tag . ' class="bw-it-is-title ' . $title_align . '">' . $title . '</' . $title_tag . '>';
							// Subtitle
							if ( $enable_subtitle ) echo '<' . $subtitle_tag . ' class="bw-it-is-subtitle ' . $subtitle_align . '">' . $subtitle . '</' . $subtitle_tag . '>';
							// Paragraph
							if ( $enable_paragraph ) echo '<p class="bw-it-is-paragraph ' . $paragraph_align . '">' . $paragraph . '</p>';
							// Link
							if ( $enable_link ) echo '<a href="' . $link . '"' . $link_target . $link_nofollow . ' class="bw-btn ' . $link_align . '">' . $link_text . '</a>';
						echo '</div>';
					echo '</div>';
					break;

			case 'position-5':
			case 'position-6':
				echo '<div class="bw-iconbox bw-' . $position . '">';
					echo '<div class="bw-wrapper">';
						// Image
						if ( $enable_icon_image ) if ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] ); echo '</div>'; else: echo '<div class="bw-iconbox-img"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>'; endif;
						echo '<div class="bw-icon-box-title">';
							// Title
							if ( $enable_title ) echo '<' . $title_tag . ' class="bw-it-is-title ' . $title_align . '">' . $title . '</' . $title_tag . '>';
							// Subtitle
							if ( $enable_subtitle ) echo '<' . $subtitle_tag . ' class="bw-it-is-subtitle ' . $subtitle_align . '">' . $subtitle . '</' . $subtitle_tag . '>';
						echo '</div>';
					echo '</div>';
					// Paragraph
					if ( $enable_paragraph ) echo '<p class="bw-it-is-paragraph ' . $paragraph_align . '">' . $paragraph . '</p>';
					// Link
					if ( $enable_link ) echo '<a href="' . $link . '"' . $link_target . $link_nofollow . ' class="bw-btn ' . $link_align . '">' . $link_text . '</a>';
				echo '</div>';
				break;

			default:
				echo '<div class="bw-iconbox bw-' . $position . '">';
					// Image
					if ( $enable_icon_image ) if ( $icon_image == 'enable_icon' ): echo '<div class="bw-iconbox-icon">'; \Elementor\Icons_Manager::render_icon( $iconset, [ 'aria-hidden' => 'true' ] ); echo '</div>'; else: echo '<div class="bw-iconbox-img"><img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['widget_image']['id'], 'thumbnail', $settings ) . '" class="bw-iconbox-image"></div>'; endif;
					// Title
					if ( $enable_title ) echo '<' . $title_tag . ' class="bw-it-is-title ' . $title_align . '">' . $title . '</' . $title_tag . '>';
					// Subtitle
					if ( $enable_subtitle ) echo '<' . $subtitle_tag . ' class="bw-it-is-subtitle ' . $subtitle_align . '">' . $subtitle . '</' . $subtitle_tag . '>';
					// Paragraph
					if ( $enable_paragraph ) echo '<p class="bw-it-is-paragraph ' . $paragraph_align . '">' . $paragraph . '</p>';
					// Link
					if ( $enable_link ) echo '<a href="' . $link . '"' . $link_target . $link_nofollow . ' class="bw-btn ' . $link_align . '">' . $link_text . '</a>';
				echo '</div>';
				break;
		}

	}

}