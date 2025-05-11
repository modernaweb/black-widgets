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
class SocialLinks extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-social-links', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/social-links.css', [], BLACK_WIDGETS_VERSION );
    }

	/**
	 * Get widget name.
	 *
	 * Retrieve social links widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'b_social_links';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve social links widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black Social Links', 'black-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve social links widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-social-icons';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the social links widget belongs to.
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
        return [ 'black-widgets-social-links' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

	/**
	 * Register social links widget controls.
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-social-links/" target="_blank">%s</a>',
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
				'default' => 'bw-t1',
				'options' => [
					'bw-t1' => esc_html__( 'Type 1', 'black-widgets' ),
					'bw-t2' => esc_html__( 'Type 2', 'black-widgets' ),
					'bw-t3' => esc_html__( 'Type 3', 'black-widgets' ),
					'bw-t4' => esc_html__( 'Type 4', 'black-widgets' ),
					'bw-t5' => esc_html__( 'Type 5', 'black-widgets' ),
					'bw-t6' => esc_html__( 'Type 6 (social name)', 'black-widgets' ),
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'social_name', [
				'label' => esc_html__( 'Social Name', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Facebook' , 'black-widgets' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'icon_widget',
			[
				'label' => esc_html__( 'Icon', 'black-widgets' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'eicon-facebook',
					'library' => 'elementor',
				],
			]
		);

		$repeater->add_control(
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

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'social_name' => esc_html__( 'Facebook', 'black-widgets' ),
						'icon_widget' => [
							'value' => 'eicon-facebook',
						],
					],
					[
						'social_name' => esc_html__( 'Twitter', 'black-widgets' ),
						'icon_widget' => [
							'value' => 'eicon-twitter',
						],
					],
				],
				'title_field' => '{{{ social_name }}}',
			]
		);

		// Type title
		$this->add_control(
			'widget_follow',
			[
				'label' => esc_html__( 'Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Follow', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your follow here', 'black-widgets' ),
				'description' => esc_html__( 'Type text like it: Follow', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
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
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social',
			]
		);

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
					'{{WRAPPER}} .bw-social-links .bw-social' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-social-links .bw-social' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover',
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
					'{{WRAPPER}} .bw-social-links .bw-social:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-social-links .bw-social:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'style_section_typo',
			[
				'label' => esc_html__( 'Typography Styling', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_2_tab');
		$this->start_controls_tab(
			'tab_2_normal',
			[
				'label' => esc_html__( 'Normal', 'black-widgets' ),
			]
		);

		// Color
		$this->add_control(
			'style_main_social_name_color',
			[
				'label' => esc_html__( 'Social Name Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_social_name_typography',
				'label' => esc_html__( 'Social Name Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_social_name_text_shadow',
				'label' => esc_html__( 'Social Name Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social',
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Color
		$this->add_control(
			'style_main_follow_text_color',
			[
				'label' => esc_html__( 'Follow Link Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,     
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social .bw-follow-text' => 'color: {{VALUE}}',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_follow_text_typography',
				'label' => esc_html__( 'Follow Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social .bw-follow-text',
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_follow_text_text_shadow',
				'label' => esc_html__( 'Follow Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social .bw-follow-text',
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
				'separator' => 'after',
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_social_name_margin',
			[
				'label' => esc_html__( 'Social Name Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_social_name_padding',
			[
				'label' => esc_html__( 'Social Name Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_social_name_border',
				'label' => esc_html__( 'Social Name Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_social_name_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Social Name Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_follow_text_margin',
			[
				'label' => esc_html__( 'Follow Text Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social .bw-follow-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
				'separator' => 'before',
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_follow_text_padding',
			[
				'label' => esc_html__( 'Follow Text Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social .bw-follow-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_follow_text_border',
				'label' => esc_html__( 'Follow Text Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social .bw-follow-text',
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_follow_text_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Follow Text Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social .bw-follow-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
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
			'style_hover_main_social_name_color',
			[
				'label' => esc_html__( 'Social Name Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_hover_main_social_name_typography',
				'label' => esc_html__( 'Social Name Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_hover_main_social_name_text_shadow',
				'label' => esc_html__( 'Social Name Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover',
			]
		);

		$this->add_control(
			'hr6',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Color
		$this->add_control(
			'style_hover_main_follow_text_color',
			[
				'label' => esc_html__( 'Follow link Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover .bw-follow-text' => 'color: {{VALUE}}',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_hover_main_follow_text_typography',
				'label' => esc_html__( 'Follow Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover .bw-follow-text',
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_hover_main_follow_text_text_shadow',
				'label' => esc_html__( 'Follow Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover .bw-follow-text',
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
				'separator' => 'after',
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_hover_main_social_name_margin',
			[
				'label' => esc_html__( 'Social Name Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_hover_main_social_name_padding',
			[
				'label' => esc_html__( 'Social Name Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_hover_main_social_name_border',
				'label' => esc_html__( 'Social Name Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover',
			]
		);

		// Border Radius
		$this->add_control(
			'style_hover_main_social_name_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Social Name Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_hover_main_follow_text_margin',
			[
				'label' => esc_html__( 'Follow Text Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover .bw-follow-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
				'separator' => 'before',
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_hover_main_follow_text_padding',
			[
				'label' => esc_html__( 'Follow Text Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover .bw-follow-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_hover_main_follow_text_border',
				'label' => esc_html__( 'Follow Text Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover .bw-follow-text',
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		// Border Radius
		$this->add_control(
			'style_hover_main_follow_text_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Follow Text Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover .bw-follow-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t1',
					],
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'style_section_for_icon',
			[
				'label' => esc_html__( 'Icon Styling', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type!' => [
						'bw-t6',
					],
				],
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_3_tab');
		$this->start_controls_tab(
			'tab_3_normal',
			[
				'label' => esc_html__( 'Normal', 'black-widgets' ),
			]
		);

		// Color
		$this->add_control(
			'style_main_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'style_main_icon',
			[
				'label' => esc_html__( 'Icon Size', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 3,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_3_hover',
			[
				'label' => esc_html__( 'Hover', 'black-widgets' ),
			]
		);

		// Color
		$this->add_control(
			'style_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'style_hover_icon',
			[
				'label' => esc_html__( 'Icon Size', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 3,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
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

		// Variables
        $type 	        = isset($settings['widget_type']) ? $settings['widget_type'] : '';
        $follow			= isset($settings['widget_follow']) ? $settings['widget_follow'] : '';
		$alignment 		= isset($settings['widget_box_alignment']) ? esc_attr( $settings['widget_box_alignment'] ) : 'left';

		// Render
		echo '<div class="bw-social-links ' . esc_attr( $type ) . '">';
			echo '<div class="bw-social-box ' . $alignment . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			foreach (  $settings['list'] as $item ) {
				$target 		= $item['website_link']['is_external'] ? ' target="_blank"' : '';
				$nofollow 		= $item['website_link']['nofollow'] ? ' rel="nofollow"' : '';
				if ( $type != 'bw-t6' && $type != 'bw-t1' ) {
					echo '<a href="' . esc_url( $item['website_link']['url'] ) . '"' . $target . $nofollow . ' class="bw-social">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						\Elementor\Icons_Manager::render_icon( $item['icon_widget'], [ 'aria-hidden' => 'true' ] );
					echo '</a>';
				} elseif ( $type == 'bw-t1' ) {
					echo '<a href="' . esc_url( $item['website_link']['url'] ) . '"' . $target . $nofollow . ' class="bw-social">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						\Elementor\Icons_Manager::render_icon( $item['icon_widget'], [ 'aria-hidden' => 'true' ] );
						echo '<span class="bw-social-name">' . esc_html( $item['social_name'] ) . '</span>';
						echo '<span class="bw-follow-text">' . esc_html( $follow ) . '</span>';
					echo '</a>';
				} else {
					echo '<a href="' . $item['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-social">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo esc_html( $item['social_name'] );
					echo '</a>';
				}
			}
			echo '</div>';
		echo '</div>';

	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\SocialLinks', 'Black_Widgets\BLACK_WIDGETS_Social_Links');
