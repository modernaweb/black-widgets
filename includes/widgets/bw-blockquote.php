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
class BLACK_WIDGETS_Block_Quote extends \Elementor\Widget_Base {

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
		return 'b_quote';
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
		return __( 'Black Blockquote', 'blackwidgets' );
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
		return 'eicon-blockquote';
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
				'label' => esc_html__( 'Content', 'blackwidgets' ),
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-blockquote/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'blackwidgets' ),
					esc_html__( 'Demo', 'blackwidgets' )
				),
			]
		);

		$this->add_control(
			'widget_description',
			[
				'label' => esc_html__( 'Quote', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 5,
				'default' => esc_html__( 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your description here', 'blackwidgets' ),
			]
		);

		// Type title
		$this->add_control(
			'widget_title',
			[
				'label' => esc_html__( 'Name', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Black Widget', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'description' => esc_html__( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and  ...', 'blackwidgets' ),
			]
		);

		// Select type of the title
		$this->add_control(
			'quote_type',
			[
				'label' => esc_html__( 'Select Quote Icon', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'svg-2',
				'options' => [
					'svg-1' 	=> esc_html__( 'SVG 1', 'blackwidgets' ),
					'svg-2' 	=> esc_html__( 'SVG 2', 'blackwidgets' ),
					'svg-3' 	=> esc_html__( 'SVG 3', 'blackwidgets' ),
					'svg-4' 	=> esc_html__( 'SVG 4', 'blackwidgets' ),
					'icon' 		=> esc_html__( 'Icon', 'blackwidgets' ),
					'custom' 	=> esc_html__( 'Image/SVG', 'blackwidgets' ),
				],
			]
		);

		$this->add_control(
			'icon_widget',
			[
				'label' => esc_html__( 'Icon', 'blackwidgets' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'eicon eicon-nerd',
					'library' => 'elementor',
				],
				'condition'  => [
					'quote_type' => [
						'icon',
					],
				],
			]
		);

		$this->add_control(
			'image_widget',
			[
				'label' => esc_html__( 'Choose Image', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition'  => [
					'quote_type' => [
						'custom',
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
					'quote_type' => [
						'custom',
					],
				],
			]
        );


		// Alignment
		$this->add_responsive_control(
			'widget_alignment',
			[
				'label'     => esc_html__( 'Text Alignment', 'blackwidgets' ),
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
				'selector' => '{{WRAPPER}} .bw-blockquote',
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
					'{{WRAPPER}} .bw-blockquote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-blockquote',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-blockquote:hover',
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
					'{{WRAPPER}} .bw-blockquote:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-blockquote:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-blockquote:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
        // End



		// Start
		// Box Style section
		$this->start_controls_section(
			'content_style_section',
			[
				'label' => esc_html__( 'Content Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Margin
		$this->add_responsive_control(
			'content_style_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_style_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_style_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .content',
			]
		);

		// Border Radius
		$this->add_control(
			'content_style_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Quote Style section
		$this->start_controls_section(
			'quote_style_section',
			[
				'label' => esc_html__( 'Quote Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('inner_quote_tab'); // Start Tabs
		$this->start_controls_tab(
			'quote_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_quote_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content .bw-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'quote_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_quote_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote:hover .content .bw-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->add_control(
			'quote_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_quote_typographys',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-description',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_quote_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-description',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_quote_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-description',
			]
		);

		// Margin
		$this->add_responsive_control(
			'content_quote__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content .bw-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_quote__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content .bw-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_quote__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-description',
			]
		);

		// Border Radius
		$this->add_control(
			'content_quote__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content .bw-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_quote__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-description',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Name Style section
		$this->start_controls_section(
			'name_style_section',
			[
				'label' => esc_html__( 'Name Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('inner_name_tab'); // Start Tabs
		$this->start_controls_tab(
			'name_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_name_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content .bw-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'name_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Color
		$this->add_control(
			'widget_icon_box_name_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote:hover .content .bw-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->add_control(
			'name_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_name_typographys',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-name',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_name_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-name',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_name_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-name',
			]
		);

		// Margin
		$this->add_responsive_control(
			'content_name__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content .bw-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'content_name__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content .bw-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_name__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-name',
			]
		);

		// Border Radius
		$this->add_control(
			'content_name__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .content .bw-name' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_name__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .content .bw-name',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// SVG Image Icon Style section
		$this->start_controls_section(
			'icon_svg_image_style_section',
			[
				'label' => esc_html__( ' SVG/Image/Icon Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style_icon_quote_size',
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
					'{{WRAPPER}} .bw-blockquote .bw-icon-into i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'quote_type' => [
						'icon',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_icon_quote_color',
			[
				'label' => esc_html__( 'Icon Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .bw-icon-into i' => 'color: {{VALUE}}',
				],
				'condition'  => [
					'quote_type' => [
						'icon',
					],
				],
			]
		);

		$this->add_control(
			'style_svg_quote_size',
			[
				'label' => esc_html__( 'SVG Size', 'blackwidgets' ),
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
					'{{WRAPPER}} .bw-blockquote .bw-icon-into svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; max-width: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'quote_type' => [
						'svg-1',
						'svg-2',
						'svg-3',
						'svg-4',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_svg_quote_color',
			[
				'label' => esc_html__( 'SVG Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .bw-icon-into svg, {{WRAPPER}} .bw-blockquote .bw-icon-into svg path' => 'fill: {{VALUE}} !important;',
				],
				'condition'  => [
					'quote_type' => [
						'svg-1',
						'svg-2',
						'svg-3',
						'svg-4',
					],
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'custom__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .bw-icon-into i, {{WRAPPER}} .bw-blockquote .bw-icon-into img, {{WRAPPER}} .bw-blockquote .bw-icon-into svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'custom__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .bw-icon-into i, {{WRAPPER}} .bw-blockquote .bw-icon-into img, {{WRAPPER}} .bw-blockquote .bw-icon-into svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'custom__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .bw-icon-into i, {{WRAPPER}} .bw-blockquote .bw-icon-into img, {{WRAPPER}} .bw-blockquote .bw-icon-into svg',
			]
		);

		// Border Radius
		$this->add_control(
			'custom__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-blockquote .bw-icon-into i, {{WRAPPER}} .bw-blockquote .bw-icon-into img, {{WRAPPER}} .bw-blockquote .bw-icon-into svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'custom__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-blockquote .bw-icon-into i, {{WRAPPER}} .bw-blockquote .bw-icon-into img, {{WRAPPER}} .bw-blockquote .bw-icon-into svg',
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
		$description	= isset($settings['widget_description'])		? $settings['widget_description']			: '';
		$type 	        = isset($settings['quote_type']) 				? $settings['quote_type']					: '';
		$alignment		= isset($settings['widget_alignment']) 			? $settings['widget_alignment']				: '';
        $image_URL		= isset($settings['image_widget']['url']) 		?  $settings['image_widget']['url']			: '';
        $image_link		= isset($settings['image_link']) 				? $settings['image_link']					: '';
        $name			= isset($settings['widget_title']) 				? $settings['widget_title']					: '';

		// Render
		echo '<div class="bw-blockquote bw-blockquote-'. esc_attr( $alignment ) .'">';
			echo '<div class="bw-icon-into">';
				switch ($type) {
					case 'svg-1':
						echo '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512px" height="512px" class=""><g><g transform="matrix(-1 1.22465e-16 -1.22465e-16 -1 512 512)"><g><g><g><path d="M0,288h92.48l-32,160h128.624l34.576-172.8L224,64H0V288z M32,96h160v174.4L162.896,416H99.52l32-160H32V96z" data-original="#000000" class="active-path" fill="#000000"/><path d="M288,64v224h92.48l-32,160h128.624l34.576-172.8L512,64H288z M480,270.4L450.896,416H387.52l32-160H320V96h160V270.4z" data-original="#000000" class="active-path" fill="#000000"/></g></g></g></g></g></svg>';
						break;
					case 'svg-2':
						echo '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512px" height="512px"><g transform="matrix(-1 1.22465e-16 -1.22465e-16 -1 512 512)"><g><g><path d="M120,77C53.832,77,0,130.832,0,197c0,31.641,12.208,61.497,34.374,84.069c21.458,21.851,49.904,34.501,80.375,35.812 c0.144,1.904,0.251,4.528,0.251,8.119c0,14.712-11.188,59.665-18.241,84.544L89.542,435h87.584l5.867-9.453 C185.32,421.797,240,332.148,240,197C240,130.832,186.168,77,120,77z M154.189,395h-12.13C147.806,372.416,155,340.959,155,325 c0-12.664-0.94-22.838-5.531-31.33c-4.089-7.564-12.641-16.573-30.183-16.669l-0.873-0.022C75.176,276.141,40,240.263,40,197 c0-44.112,35.888-80,80-80s80,35.888,80,80C200,296.32,167.164,369.834,154.189,395z" data-original="#000000" class="active-path" fill="#000000"/></g></g><g><g><path d="M392,77c-66.168,0-120,53.832-120,120c0,31.641,12.208,61.497,34.374,84.069c21.458,21.851,49.904,34.501,80.375,35.812 c0.144,1.904,0.251,4.528,0.251,8.119c0,14.712-11.188,59.665-18.241,84.544L361.542,435h87.584l5.867-9.453 C457.32,421.797,512,332.148,512,197C512,130.832,458.168,77,392,77z M426.189,395h-12.13C419.806,372.416,427,340.959,427,325 c0-12.664-0.94-22.838-5.531-31.33c-4.089-7.564-12.641-16.573-30.183-16.669l-0.873-0.022C347.176,276.141,312,240.263,312,197 c0-44.112,35.888-80,80-80s80,35.888,80,80C472,296.32,439.164,369.834,426.189,395z" data-original="#000000" class="active-path" fill="#000000"/></g></g></g> </svg>';
						break;
					case 'svg-3':
						echo '<?xml version="1.0" encoding="iso-8859-1"?><!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  --><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><g><path d="M122.418,195.03l-0.917-0.023c-11.045-0.245-20.218,8.451-20.495,19.493c-0.276,11.042,8.451,20.218,19.493,20.495 l1.088,0.026C164.824,235.858,200,271.737,200,315c0,44.112-35.888,80-80,80s-80-35.888-80-80 c0-55.098,13.104-105.94,38.951-151.115c19.317-33.765,38.961-51.6,39.328-51.93c8.238-7.313,9.012-19.921,1.718-28.188 c-7.309-8.281-19.946-9.072-28.229-1.765c-0.967,0.854-23.946,21.349-46.566,60.335C24.58,177.878,0,236.683,0,315 c0,66.168,53.832,120,120,120s120-53.832,120-120c0-31.641-12.208-61.497-34.374-84.069 C183.504,208.405,153.954,195.656,122.418,195.03z"/><path d="M477.626,230.931c-22.123-22.527-51.671-35.276-83.209-35.901l-0.916-0.023c-11.009-0.245-20.218,8.451-20.495,19.493 s8.451,20.218,19.493,20.495l1.088,0.026C436.824,235.859,472,271.738,472,315c0,44.112-35.888,80-80,80s-80-35.888-80-80 c0-55.098,13.104-105.941,38.95-151.114c19.317-33.765,38.961-51.6,39.328-51.93c8.239-7.314,9.012-19.921,1.718-28.188 c-7.308-8.282-19.946-9.073-28.229-1.765c-0.966,0.854-23.946,21.349-46.566,60.335C296.58,177.878,272,236.683,272,315 c0,66.168,53.832,120,120,120s120-53.832,120-120C512,283.359,499.792,253.503,477.626,230.931z"/></g></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>';
						break;
					case 'svg-4':
						echo '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 46.195 46.195" style="enable-background:new 0 0 46.195 46.195;" xml:space="preserve" width="512px" height="512px" class=""><g transform="matrix(-1 -1.22465e-16 1.22465e-16 -1 46.195 46.195)"><g><path d="M35.765,8.264c-5.898,0-10.555,4.782-10.555,10.68s4.844,10.68,10.742,10.68   c0.059,0,0.148-0.008,0.207-0.009c-2.332,1.857-5.261,2.976-8.467,2.976c-1.475,0-2.662,1.196-2.662,2.67s0.949,2.67,2.424,2.67   c10.469-0.001,18.741-8.518,18.741-18.987c0-0.002,0-0.004,0-0.007C46.195,13.042,41.661,8.264,35.765,8.264z" data-original="#010002" class="active-path" data-old_color="#010002" fill="#010002"/><path d="M10.75,8.264c-5.898,0-10.563,4.782-10.563,10.68s4.84,10.68,10.739,10.68   c0.059,0,0.146-0.008,0.205-0.009c-2.332,1.857-5.262,2.976-8.468,2.976C1.188,32.591,0,33.787,0,35.261s0.964,2.67,2.439,2.67   c10.469-0.001,18.756-8.518,18.756-18.987c0-0.002,0-0.004,0-0.007C21.195,13.042,16.646,8.264,10.75,8.264z" data-original="#010002" class="active-path" data-old_color="#010002" fill="#010002"/></g></g></svg>';
						break;
					case 'icon':
						\Elementor\Icons_Manager::render_icon( $settings['icon_widget'], [ 'aria-hidden' => 'true' ] );
						break;
					default:
						echo '<img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['image_widget']['id'], 'thumbnail', $settings ) . '" class="bw-img-quote">';
						break;
				}
			echo '</div>';
			echo '<div class="content">';
				echo '<p class="bw-description"> ' . esc_html( $description ) . ' </p>';
				echo '<p class="bw-name"><span>' . esc_html( $name ) . '</span></p>';
			echo '</div>';
        echo '</div>';

	}

}
