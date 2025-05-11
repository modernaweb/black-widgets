<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
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
class MagicLink extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-magic', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/magic.css', [], BLACK_WIDGETS_VERSION );
    }

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
		return 'b_magic';
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
		return __( 'Black Magic Link', 'black-widgets' );
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
		return 'eicon-editor-external-link';
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

    public function get_style_depends() {
        return [ 'black-widgets-magic' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-magic-link/" target="_blank">%s</a>',
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
				'default' => 'minimal',
				'options' => [
					'minimal' 	=> esc_html__( 'Minimal', 'black-widgets' ),
					'liner' 	=> esc_html__( 'Liner', 'black-widgets' ),
					'modern' 	=> esc_html__( 'Modern', 'black-widgets' ),
					'simple' 	=> esc_html__( 'Simple', 'black-widgets' ),
					'heart' 	=> esc_html__( 'Heart Beat', 'black-widgets' ),
					'pullltr' 	=> esc_html__( 'Pull Left To Right', 'black-widgets' ),
					'pullrtl' 	=> esc_html__( 'Pull Right To Left', 'black-widgets' ),
					'pullttb' 	=> esc_html__( 'Pull Top To Bottom', 'black-widgets' ),
					'pullbtt' 	=> esc_html__( 'Pull Bottom To Top', 'black-widgets' ),
					'arrow' 	=> esc_html__( 'Arrow', 'black-widgets' ),
					'anchor' 	=> esc_html__( 'Anchor', 'black-widgets' ),
					'wheel' 	=> esc_html__( 'Wheel', 'black-widgets' ),
				],
				'description' => esc_html__( 'We create some skin before, you can use these or no! make a new custom type.', 'black-widgets' ),
			]
		);

		$this->add_control(
			'widget_text',
			[
				'label' => esc_html__( 'Button Text', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Let\'s started', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
			]
		);
        
		$this->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'black-widgets' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
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

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => esc_html__( 'Normal', 'black-widgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'widget_box_alignment',
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
				'toggle'    => true,
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
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-magic-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_1_hover',
			[
				'label' => esc_html__( 'Hover', 'black-widgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
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
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_hover_box_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'style_section_back_link_typo',
			[
				'label' => esc_html__( 'Text Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'style_main_back_link_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_back_link_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
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
				'name' => 'style_main_back_link_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a',
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
			'style_main_back_link_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_back_link_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'name' => 'style_main_back_link_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_back_link_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_back_link_hover_typo',
			[
				'label' => esc_html__( 'Hover Text Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'style_main_back_link_hover_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_back_link_hover_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_hover_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
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
				'name' => 'style_main_back_link_hover_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
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
			'style_main_back_link_hover_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_back_link_hover_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'name' => 'style_main_back_link_hover_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_back_link_hover_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_hover_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_other',
			[
				'label' => esc_html__( 'Other Styles', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' 	=> [ 
					'widget_type!' 	=> [
						'simple',
					],
				],
			]
		);

		// Normal Height
		$this->add_control(
			'top_and_bottom_line_normal_height',
			[
				'label' => esc_html__( 'Normal Height', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 14,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
					],
				],
			]
		);

		// Hover Height
		$this->add_control(
			'top_and_bottom_line_hover_height',
			[
				'label' => esc_html__( 'Hover Height', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:hover:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:hover:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
					],
				],
			]
		);

		// Normal Width
		$this->add_control(
			'top_and_bottom_line_normal_width',
			[
				'label' => esc_html__( 'Normal Width', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 14,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullltr',
						'pullrtl',
					],
				],
			]
		);

		// Hover Width
		$this->add_control(
			'top_and_bottom_line_hover_width',
			[
				'label' => esc_html__( 'Hover Width', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:hover:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:hover:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullltr',
						'pullrtl',
					],
				],
			]
		);

		// Liner Height
		$this->add_control(
			'liner_line_height',
			[
				'label' => esc_html__( 'Liner Height', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 7,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-liner:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'liner',
					],
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'line_bg',
				'label' => esc_html__( 'Bg Color/Line Color', 'black-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a.bw-magic-modern:before, {{WRAPPER}} .bw-magic-link a.bw-magic-minimal:after, {{WRAPPER}} .bw-magic-link a.bw-magic-liner:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before, {{WRAPPER}} .bw-magic-link .bw-magic-heart span',
				'condition' 	=> [ 
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
						'pullltr',
						'pullrtl',
						'liner',
						'modern',
						'minimal',
						'heart',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'svg_heart_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-heart span svg' => 'fill: {{VALUE}}',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'heart',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'svg_wheel_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g line, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g polyline, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g circle' => 'stroke: {{VALUE}} !important',
				],
				'condition' 	=> [ 
					'widget_type' 	=> [
						'wheel',
					],
				],
			]
		);

		$this->add_control(
			'magic_link_bg_opacity',
			[
				'label' => esc_html__( 'Opacity', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g line, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g polyline, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g circle, {{WRAPPER}} .bw-magic-link a.bw-magic-modern:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before, {{WRAPPER}} .bw-magic-link .bw-magic-heart span svg' => 'opacity: {{SIZE}};',
				],
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
		$type 	        	= isset($settings['widget_type']) 				? $settings['widget_type']				: '';
        $text 	        	= isset($settings['widget_text']) 				? $settings['widget_text'] 				: '';
		$target         	= $settings['website_link']['is_external'] 		? 'target="_blank"' 					: '';
		$nofollow       	= $settings['website_link']['nofollow'] 		? ' rel="nofollow"'						: '';
		$alignment 		    = isset($settings['widget_box_alignment']) 	    ? esc_attr( $settings['widget_box_alignment'] )	: 'left';

        $type = esc_attr( $type );
        $text = esc_html( $text );

		// Render
		switch ($type) {
			case 'heart':
                echo '<div class="bw-magic-link ' . $alignment . '"><a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . ' <span><?xml version="1.0" ?><!DOCTYPE svg  PUBLIC \'-//W3C//DTD SVG 1.1//EN\'  \'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\'><svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="160,115.4 180.7,96 352,256 180.7,416 160,396.7 310.5,256 "/></svg></span></a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
			case 'liner':
				echo '<div class="bw-magic-link ' . $alignment . '"><a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '</a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
			case 'arrow':
				echo '<div class="bw-magic-link ' . $alignment . '"><a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '<span>  <i aria-hidden="true" class="fas fa-arrow-right"></i> </span></a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
			case 'wheel':
				echo '<div class="bw-magic-link ' . $alignment . '"><a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '"><svg><g><line x2="227.62" y1="31.28" y2="31.28"></line><polyline points="222.62 25.78 228.12 31.28 222.62 36.78"></polyline><circle cx="224.67" cy="30.94" r="30.5" transform="rotate(180 224.67 30.94) scale(1, -1) translate(0, -61)"></circle></g></svg><span>' . $text . '</span></a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
			default:
				echo '<div class="bw-magic-link ' . $alignment . '"><a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '</a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
		}

	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\MagicLink', 'Black_Widgets\BLACK_WIDGETS_Magic_Link');
