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

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BW_Social_Links extends \Elementor\Widget_Base {

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
		return __( 'Black Social Links', 'bw' );
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
		return [ 'bw' ];
	}

	/**
	 * Register social links widget controls.
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
				'label' => __( 'Content', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => __( 'Select Type', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bw-t1',
				'options' => [
					'bw-t1' => __( 'Type 1', 'bw' ),
					'bw-t2' => __( 'Type 2', 'bw' ),
					'bw-t3' => __( 'Type 3', 'bw' ),
					'bw-t4' => __( 'Type 4', 'bw' ),
					'bw-t5' => __( 'Type 5', 'bw' ),
					'bw-t6' => __( 'Type 6 (social name)', 'bw' ),
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'social_name', [
				'label' => __( 'Social Name', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Facebook' , 'bw' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'icon_widget',
			[
				'label' => __( 'Icon', 'bw' ),
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
				'label' => __( 'Link', 'bw' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'bw' ),
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
				'label' => __( 'Repeater List', 'bw' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'social_name' => __( 'Facebook', 'bw' ),
						'icon_widget' => [
							'value' => 'eicon-facebook',
						],
					],
					[
						'social_name' => __( 'Twitter', 'bw' ),
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
				'label' => __( 'Title', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Follow', 'bw' ),
				'placeholder' => __( 'Type your follow here', 'bw' ),
				'description' => __( 'Type text like it: Follow', 'bw' ),
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
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social',
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
					'{{WRAPPER}} .bw-social-links .bw-social' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
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
				'label' => __( 'Typography Styling', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_2_tab');
		$this->start_controls_tab(
			'tab_2_normal',
			[
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'style_main_social_name_color',
			[
				'label' => __( 'Social Name Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
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
				'label' => __( 'Social Name Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_social_name_text_shadow',
				'label' => __( 'Social Name Text Shadow', 'bw' ),
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
				'label' => __( 'Social Name Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
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
				'label' => __( 'Follow Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
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
				'label' => __( 'Follow Text Shadow', 'bw' ),
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
				'label' => __( 'Social Name Margin', 'bw' ),
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
				'label' => __( 'Social Name Padding', 'bw' ),
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
				'label' => __( 'Social Name Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-social-links .bw-social .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_social_name_border_radius', //param_name
			[
				'label' 		=> __( 'Social Name Border Radius', 'bw' ),
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
				'label' => __( 'Follow Text Margin', 'bw' ),
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
				'label' => __( 'Follow Text Padding', 'bw' ),
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
				'label' => __( 'Follow Text Border', 'bw' ),
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
				'label' 		=> __( 'Follow Text Border Radius', 'bw' ),
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
				'label' => __( 'Hover', 'bw' ),
			]
		);

// Color
$this->add_control(
	'style_hover_main_social_name_color',
	[
		'label' => __( 'Social Name Color', 'bw' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
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
		'label' => __( 'Social Name Typography', 'bw' ),
		'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover',
	]
);

// Text shadow
$this->add_group_control(
	Group_Control_Text_Shadow::get_type(),
	[
		'name' => 'style_hover_main_social_name_text_shadow',
		'label' => __( 'Social Name Text Shadow', 'bw' ),
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
		'label' => __( 'Social Name Color', 'bw' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
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
		'label' => __( 'Follow Typography', 'bw' ),
		'scheme' => Scheme_Typography::TYPOGRAPHY_1,
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
		'label' => __( 'Follow Text Shadow', 'bw' ),
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
		'label' => __( 'Social Name Margin', 'bw' ),
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
		'label' => __( 'Social Name Padding', 'bw' ),
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
		'label' => __( 'Social Name Border', 'bw' ),
		'selector' => '{{WRAPPER}} .bw-social-links .bw-social:hover .bw-social-name, {{WRAPPER}} .bw-social-links.bw-t6 .bw-social:hover',
	]
);

// Border Radius
$this->add_control(
	'style_hover_main_social_name_border_radius', //param_name
	[
		'label' 		=> __( 'Social Name Border Radius', 'bw' ),
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
		'label' => __( 'Follow Text Margin', 'bw' ),
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
		'label' => __( 'Follow Text Padding', 'bw' ),
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
		'label' => __( 'Follow Text Border', 'bw' ),
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
		'label' 		=> __( 'Follow Text Border Radius', 'bw' ),
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
				'label' => __( 'Icon Styling', 'bw' ),
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
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'style_main_icon_color',
			[
				'label' => __( 'Icon Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'style_main_icon',
			[
				'label' => __( 'Icon Size', 'bw' ),
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
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'style_hover_icon_color',
			[
				'label' => __( 'Icon Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-social-links .bw-social:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'style_hover_icon',
			[
				'label' => __( 'Icon Size', 'bw' ),
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
        $follow 	        = isset($settings['widget_follow']) ? $settings['widget_follow'] : '';

		// Render
		echo '<div class="bw-social-links ' . $type . '">';
			echo '<div class="bw-social-box">';
			foreach (  $settings['list'] as $item ) {
				$target 		= $item['website_link']['is_external'] ? ' target="_blank"' : '';
				$nofollow 		= $item['website_link']['nofollow'] ? ' rel="nofollow"' : '';
				if ( $type != 'bw-t6' && $type != 'bw-t1' ) {
					echo '<a href="' . $item['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-social">';
						\Elementor\Icons_Manager::render_icon( $item['icon_widget'], [ 'aria-hidden' => 'true' ] );
					echo '</a>';
				} elseif ( $type == 'bw-t1' ) {
					echo '<a href="' . $item['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-social">';
						\Elementor\Icons_Manager::render_icon( $item['icon_widget'], [ 'aria-hidden' => 'true' ] );
						echo '<span class="bw-social-name">' . $item['social_name'] . '</span>';
						echo '<span class="bw-follow-text">' . $follow . '</span>';
					echo '</a>';
				} else {
					echo '<a href="' . $item['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-social">';
						echo $item['social_name'];
					echo '</a>';
				}
			}
			echo '</div>';
		echo '</div>';

	}

}