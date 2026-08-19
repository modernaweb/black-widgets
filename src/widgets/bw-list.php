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

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class ListItems extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-list', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/list-items.css', [], BLACK_WIDGETS_VERSION );
    }

	/**
	 * Get widget name.
	 *
	 * Retrieve list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'b_list';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black List', 'black-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the list widget belongs to.
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
        return [ 'black-widgets-list' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

	/**
	 * Register list widget controls.
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-list/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'black-widgets' ),
					esc_html__( 'Demo', 'black-widgets' )
				),
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'black-widgets' ),
				'label_block' => true,
				'description' => esc_html__( 'Inline HTML allowed: br, strong, b, em, i, u, span, mark, small, sub, sup.', 'black-widgets' ),
			]
		);

		$repeater->add_control(
			'icon_widget',
			[
				'label' => esc_html__( 'Icon', 'black-widgets' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'eicon eicon-check',
					'library' => 'elementor',
				],
			]
		);

		$repeater->add_control(
			'link_list',
			[
				'label' => esc_html__( 'Link', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'black-widgets' ),
				'show_external' => true,
			]
		);

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' 	=> esc_html__( 'Item content #1', 'black-widgets' ),
						'icon_widget' 	=> [
                            'value' => 'eicon eicon-check',
                        ],
					],
					[
                        'list_title' 	=> esc_html__( 'Item content #2', 'black-widgets' ),
						'icon_widget' 	=> [
                            'value' => 'eicon eicon-check',
                        ],
					],
					[
                        'list_title' 	=> esc_html__( 'Item content #3', 'black-widgets' ),
						'icon_widget' 	=> [
                            'value' => 'eicon eicon-check',
                        ],
					],
				],
				'title_field' => '{{{ list_title }}}',
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
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-list',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .bw-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
        // End

		// Style section
		$this->start_controls_section(
			'style_section_list_items',
			[
				'label' => esc_html__( 'List Items', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'black-widgets' ),
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
				'default'   => 'left',
				'toggle'    => true,
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-content' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'list_items_gap',
			[
				'label' => esc_html__( 'Items Gap', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-list' => 'display: flex; flex-direction: column; gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'list_divider',
			[
				'label' => esc_html__( 'Divider', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'black-widgets' ),
				'label_off' => esc_html__( 'Off', 'black-widgets' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'list_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e5e5e5',
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'list_divider_weight',
			[
				'label' => esc_html__( 'Divider Weight', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'condition' => [
					'list_divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:not(:last-child)' => 'border-bottom-style: solid; border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'list_items_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item',
			]
		);

		// Margin
		$this->add_responsive_control(
			'list_items_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'list_items_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_items_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item',
			]
		);

		// Border Radius
		$this->add_control(
			'list_items_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'list_items_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item',
			]
		);

		$this->end_controls_section();
        // End

		// Style section
		$this->start_controls_section(
			'style_section_icon',
			[
				'label' => esc_html__( 'Icon Settings', 'black-widgets' ),
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

		// Color
		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section svg' => 'fill: {{VALUE}}',
				],
			]
        );

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'black-widgets' ),
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
					'{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section',
			]
		);

		// Margin
		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section',
			]
		);

		// Border Radius
		$this->add_control(
			'icon_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item .bw-icon-section',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_2_hover',
			[
				'label' => esc_html__( 'Hover', 'black-widgets' ),
			]
		);


		// Color
		$this->add_control(
			'icon_hover_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section svg' => 'fill: {{VALUE}}',
				],
			]
        );

		$this->add_control(
			'icon_hover_size',
			[
				'label' => esc_html__( 'Icon Size', 'black-widgets' ),
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
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_hover_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section',
			]
		);

		// Margin
		$this->add_responsive_control(
			'icon_hover_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'icon_hover_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_hover_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section',
			]
		);

		// Border Radius
		$this->add_control(
			'icon_hover_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_hover_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item:hover .bw-icon-section',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs		

		$this->end_controls_section();
        // End

		// Style section
		$this->start_controls_section(
			'style_section_text',
			[
				'label' => esc_html__( 'Text Settings', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_2_tab');
		$this->start_controls_tab(
			'tab_3_normal',
			[
				'label' => esc_html__( 'Normal', 'black-widgets' ),
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item .bw-list-content > span:not(.bw-icon-section)',
			]
		);

		// Color
		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item .bw-list-content > span:not(.bw-icon-section)' => 'color: {{VALUE}}',
				],
			]
        );

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'text_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item .bw-list-content > span:not(.bw-icon-section)',
			]
		);

		// Margin
		$this->add_responsive_control(
			'text_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item .bw-list-content > span:not(.bw-icon-section)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item .bw-list-content > span:not(.bw-icon-section)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'text_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item .bw-list-content > span:not(.bw-icon-section)',
			]
		);

		// Border Radius
		$this->add_control(
			'text_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item .bw-list-content > span:not(.bw-icon-section)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'text_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item .bw-list-content > span:not(.bw-icon-section)',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_4_hover',
			[
				'label' => esc_html__( 'Hover', 'black-widgets' ),
			]
		);


		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_hover_typography',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item:hover .bw-list-content > span:not(.bw-icon-section)',
			]
		);

		// Color
		$this->add_control(
			'text_hover_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-list-content > span:not(.bw-icon-section)' => 'color: {{VALUE}}',
				],
			]
        );

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'text_hover_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item:hover .bw-list-content > span:not(.bw-icon-section)',
			]
		);

		// Margin
		$this->add_responsive_control(
			'text_hover_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-list-content > span:not(.bw-icon-section)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'text_hover_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-list-content > span:not(.bw-icon-section)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'text_hover_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item:hover .bw-list-content > span:not(.bw-icon-section)',
			]
		);

		// Border Radius
		$this->add_control(
			'text_hover_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-list .bw-list-item:hover .bw-list-content > span:not(.bw-icon-section)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'text_hover_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-list .bw-list-item:hover .bw-list-content > span:not(.bw-icon-section)',
			]
		);


		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs		

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
		$allowed_inline_html = [
			'br'     => [],
			'strong' => [],
			'b'      => [],
			'em'     => [],
			'i'      => [],
			'u'      => [],
			'span'   => [ 'class' => true ],
			'mark'   => [],
			'small'  => [],
			'sub'    => [],
			'sup'    => [],
		];

		// Render
        if ( $settings['list'] ) {
			echo '<div class="bw-list">';
			foreach (  $settings['list'] as $item ) {

					$link			= isset($item['link_list']['url'])			? $item['link_list']['url']				: '';
					$target			= ! empty( $item['link_list']['is_external'] )	? 'target="_blank"' 					: '';
					$nofollow		= ! empty( $item['link_list']['nofollow'] ) 		? ' rel="nofollow"'						: '';
					$repeater_id	= black_widgets_sanitize_repeater_id( $item['_id'] ?? '' );
					$safe_title		= wp_kses( (string) ( $item['list_title'] ?? '' ), $allowed_inline_html );

					if( $link ) {
						echo '<div class="bw-list-item">';
							echo '<a href="' . esc_url( $item['link_list']['url'] ) . '"' . $target . $nofollow . ' class="bw-list-item-link">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo '<div class="bw-list-content">';
									echo '<span class="bw-icon-section">';
										\Elementor\Icons_Manager::render_icon( $item['icon_widget'], [ 'aria-hidden' => 'true' ] );
									echo '</span>';
									echo '<span class="elementor-repeater-item-' . esc_attr( $repeater_id ) . '">';
										echo $safe_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses()'d
									echo '</span>';
								echo '</div>';
							echo '</a>';
						echo '</div>';
					} else {
						echo '<div class="bw-list-item">';
							echo '<div class="bw-list-content">';
								echo '<span class="bw-icon-section">';
									\Elementor\Icons_Manager::render_icon( $item['icon_widget'], [ 'aria-hidden' => 'true' ] );
								echo '</span>';
								echo '<span class="elementor-repeater-item-' . esc_attr( $repeater_id ) . '">';
									echo $safe_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses()'d
								echo '</span>';
							echo '</div>';
						echo '</div>';
					}
			}
			echo '</div>';
		}

	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\ListItems', 'Black_Widgets\BLACK_WIDGETS_ListItems');
