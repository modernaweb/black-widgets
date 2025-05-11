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
class TitleAnimate extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-title-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/title-animate.css', [], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-title-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/title-animate.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION );
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
		return 'b_TitleAnimate';
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
		return __( 'Black Title Animate', 'black-widgets' );
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
		return 'eicon-animated-headline';
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
        return [ 'black-widgets-title-animate' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-title-animate' ];
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-title-animate/" target="_blank">%s</a>',
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
				'default' => 'simple',
				'options' => [
					'simple' 		=> esc_html__( 'Simple', 'black-widgets' ),
					'classic' 		=> esc_html__( 'Classic', 'black-widgets' ),
					'liner'			=> esc_html__( 'Liner', 'black-widgets' ),
					'effective' 	=> esc_html__( 'Effective', 'black-widgets' ),
					'typing' 		=> esc_html__( 'Typing', 'black-widgets' ),
					'fft' 			=> esc_html__( 'Fade From Top', 'black-widgets' ),
					'ffl' 			=> esc_html__( 'Fade From Left', 'black-widgets' ),
					'ffr' 			=> esc_html__( 'Fade From Right', 'black-widgets' ),
					'ffb' 			=> esc_html__( 'Fade From Bottom', 'black-widgets' ),
					'fade_in' 		=> esc_html__( 'Fade In', 'black-widgets' ),
					'fade_out' 		=> esc_html__( 'Fade Out', 'black-widgets' ),
					'glitch_one' 	=> esc_html__( 'Glitch 1', 'black-widgets' ),
					'glitch_two' 	=> esc_html__( 'Glitch 2', 'black-widgets' ),
					'ffb2' 			=> esc_html__( 'Fade From Bottom2', 'black-widgets' ),
					'rotator' 		=> esc_html__( 'Rotator', 'black-widgets' ),
					'svg' 		    => esc_html__( 'SVG', 'black-widgets' ),
				],
				'description' => esc_html__( 'We create some skin before, you can use these or no! make a new custom type.', 'black-widgets' ),
			]
		);

		$this->add_control(
			'widget_rotator_animation',
			[
				'label' => esc_html__( 'Select Animation', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'clip',
				'options' => [
					'clip' 	    	=> esc_html__( 'Clip', 'black-widgets' ),
					'frotate' 		=> esc_html__( 'Flip Rotate', 'black-widgets' ),
					'lfin' 		    => esc_html__( 'Latter FadeIn', 'black-widgets' ),
					'lrotate' 		=> esc_html__( 'Latter Rotate', 'black-widgets' ),
					'tlatter' 		=> esc_html__( 'Typing Latter', 'black-widgets' ),
					'bloading' 		=> esc_html__( 'Bar Loading', 'black-widgets' ),
					'stop' 	        => esc_html__( 'Slide Top', 'black-widgets' ),
					'zout' 	        => esc_html__( 'Zoom Out', 'black-widgets' ),
					'sin' 	        => esc_html__( 'Scale In', 'black-widgets' ),
					'pleft' 	    => esc_html__( 'Push Left', 'black-widgets' ),
					'ceffect' 	    => esc_html__( 'Color Effect', 'black-widgets' ),
					'beffect' 	    => esc_html__( 'Bouncing Effect', 'black-widgets' ),
				],
                'condition' => [
			        'widget_type' => 'rotator',
		        ],
			]
		);

        $this->add_control(
			'widget_rotator_duration',
			[
				'label' => esc_html__( 'Duration (ms)', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 700,
                'condition' => [
			        'widget_type' => 'rotator',
		        ],
			]
		);

        $this->add_control(
			'widget_rotator_delay',
			[
				'label' => esc_html__( 'Delay (ms)', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
                'condition' => [
			        'widget_type' => 'rotator',
		        ],
			]
		);

		$this->add_control(
			'widget_rotator_text',
			[
				'label' => esc_html__( 'Rotator Text', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Hello Goodbye', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'rotator',
					],
				],
			]
		);

        $this->add_control(
			'widget_rotator_items',
			[
				'label' => esc_html__( 'Animation List', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'text',
						'label' => esc_html__( 'Text', 'black-widgets' ),
						'type' => \Elementor\Controls_Manager::TEXT,
					],
				],
				'default' => [
					[
						'text' => esc_html__( 'World', 'black-widgets' ),
					],
				],
                'condition' => [
			        'widget_type' => 'rotator',
		        ],
			]
		);

		$this->add_control(
			'widget_svg',
			[
				'label' => esc_html__( 'Select SVG', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'line1',
				'options' => [
					'line1' 	    => esc_html__( 'Line 1', 'black-widgets' ),
					'line2' 	    => esc_html__( 'Line 2', 'black-widgets' ),
					'line3' 	    => esc_html__( 'Line 3', 'black-widgets' ),
					'circle1' 	    => esc_html__( 'Circle 1', 'black-widgets' ),
					'circle2' 	    => esc_html__( 'Circle 2', 'black-widgets' ),
				],
                'condition' => [
			        'widget_type' => 'svg',
		        ],
			]
		);

		$this->add_control(
			'widget_svg_text',
			[
				'label' => esc_html__( 'Text', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Hello', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'svg',
					],
				],
			]
		);

		$this->add_control(
			'widget_svg_animation_text',
			[
				'label' => esc_html__( 'Animation Text', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'World', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'svg',
					],
				],
			]
		);

        $this->add_control(
			'widget_svg_duration',
			[
				'label' => esc_html__( 'Duration (ms)', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 2000,
                'condition' => [
			        'widget_type' => 'svg',
		        ],
			]
		);

        $this->add_control(
			'widget_svg_delay',
			[
				'label' => esc_html__( 'Delay (ms)', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
                'condition' => [
			        'widget_type' => 'svg',
		        ],
			]
		);

        $this->add_control(
			'widget_svg_loop',
			[
				'label' => esc_html__( 'Loop', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'True', 'black-widgets' ),
				'label_off' => esc_html__( 'False', 'black-widgets' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		// Type title
		$this->add_control(
			'widget_title_before',
			[
				'label' => esc_html__( 'Before Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Before', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'simple',
						'classic',
						'fft',
						'ffl',
						'ffr',
						'ffb',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_main_text',
			[
				'label' => esc_html__( 'Main Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Content', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'simple',
						'fft',
						'ffl',
						'ffr',
						'ffb',
						'ffb2',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_main_text_classic',
			[
				'label' => esc_html__( 'Symbol (Just Single Character) ', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '&', 'black-widgets' ),
				'placeholder' => esc_html__( 'Set symbol', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'classic',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_glitch',
			[
				'label' => esc_html__( 'Glitch Text', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Glitch•', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'glitch_one',
						'glitch_two',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_for_liner',
			[
				'label' => esc_html__( 'Main Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Find Your Element', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'liner',
					],
				],
			]
		);

		$this->add_control(
			'widget_title_for_liner_top',
			[
				'label' => esc_html__( 'Start Range From Bottom', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -400,
						'max' => 400,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-animate-text' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'ffb2',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_la',
			[
				'label' => esc_html__( 'Text 1', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Set', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'effective',
						'fade_in',
						'fade_out',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_lb',
			[
				'label' => esc_html__( 'Text 2', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Ready', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'effective',
						'fade_in',
						'fade_out',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_lc',
			[
				'label' => esc_html__( 'Text 3', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Go', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'effective',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_typing',
			[
				'label' => esc_html__( 'Typing Text', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Hello Goodbye', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'typing',
					],
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title_after',
			[
				'label' => esc_html__( 'After Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'After', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'condition'  => [
					'widget_type' => [
						'simple',
						'classic',
						'fft',
						'ffl',
						'ffr',
						'ffb',
					],
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Tag section
		$this->start_controls_section(
			'tag_section',
			[
				'label' => esc_html__( 'HTML Tag Setting', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select tag
		$this->add_control(
			'widget_html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'div' 	=> esc_html__( 'div', 'black-widgets' ),
					'h1' 	=> esc_html__( 'H1', 'black-widgets' ),
					'h2' 	=> esc_html__( 'H2', 'black-widgets' ),
					'h3' 	=> esc_html__( 'H3', 'black-widgets' ),
					'h4' 	=> esc_html__( 'H4', 'black-widgets' ),
					'h5' 	=> esc_html__( 'H5', 'black-widgets' ),
					'h6' 	=> esc_html__( 'H6', 'black-widgets' ),
					'p' 	=> esc_html__( 'p', 'black-widgets' ),
					'span' 	=> esc_html__( 'span', 'black-widgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'black-widgets' ),
				// 'selectors' => [
				// 	'{{WRAPPER}} .bw-button-wrapper' => 'text-align: {{VALUE}};',
				// ],
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

		// Margin
		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-title-animate' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-title-animate',
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
				'toggle'    => true,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate',
			]
		);

		// Border Radius
		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate',
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Before Text Typography
		$this->start_controls_section(
			'style_section_before_typo',
			[
				'label' => esc_html__( 'Before Text Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type!' => [
						'liner',
						'effective',
						'typing',
						'fade_in',
						'fade_out',
						'glitch_one',
						'glitch_two',
						'ffb2',
                        'rotator',
                        'svg',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_before_title_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-before' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_before_title_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_before_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_before_title_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_before_title_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_before_title_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_before_title_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		// Border Radius
		$this->add_control(
			'style_before_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_before_title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
			]
		);

		$this->end_controls_section();
        // End

        // Start
        // Main Text Typography
		$this->start_controls_section(
			'style_section_main_text_typo',
			[
				'label' => esc_html__( 'Main Text Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'rotator',
                        'svg',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_main_text_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-rotator-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .bw-title-animate .bw-svg-text' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_text_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text, {{WRAPPER}} .bw-title-animate .bw-rotator-text',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_text_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text, {{WRAPPER}} .bw-title-animate .bw-rotator-text',
			]
		);

		$this->add_control(
			'hr13',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_main_text_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text, {{WRAPPER}} .bw-title-animate .bw-rotator-text',
			]
		);

		$this->add_control(
			'hr14',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_text_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-svg-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bw-title-animate .bw-rotator-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_text_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-svg-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bw-title-animate .bw-rotator-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr15',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_text_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text',
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-rotator-text',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_text_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-svg-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bw-title-animate .bw-rotator-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_text_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text, {{WRAPPER}} .bw-title-animate .bw-rotator-text',
			]
		);

		$this->end_controls_section();
        // End
        
        
        // Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_main_typo',
			[
				'label' => esc_html__( 'Animate Text Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type!' => [
						'classic',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_main_title_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_title_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_main_title_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_title_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_title_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr6',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_title_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// symbol Text Typography
		$this->start_controls_section(
			'style_section_symbol_typo',
			[
				'label' => esc_html__( 'Symbol Text Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'classic',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_symbol_title_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-symbol' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_symbol_title_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_symbol_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		$this->add_control(
			'hr7',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_symbol_title_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		$this->add_control(
			'hr8',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_symbol_title_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-symbol' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_symbol_title_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-symbol' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr9',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_symbol_title_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		// Border Radius
		$this->add_control(
			'style_symbol_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-symbol' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_symbol_title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
			]
		);

		$this->end_controls_section();
		// End	
		

		// Start
		// After Text Typography
		$this->start_controls_section(
			'style_section_after_typo',
			[
				'label' => esc_html__( 'After Text Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type!' => [
						'liner',
						'effective',
						'typing',
						'fade_in',
						'fade_out',
						'glitch_one',
						'glitch_two',
						'ffb2',
                        'rotator',
                        'svg'
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_after_title_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-after' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_after_title_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [                                                                                                 
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_after_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		$this->add_control(
			'hr10',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_after_title_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		$this->add_control(
			'hr11',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_after_title_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_after_title_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr12',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_after_title_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		// Border Radius
		$this->add_control(
			'style_after_title_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_after_title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Line Text Typography
		$this->start_controls_section(
			'style_section_symbol_line',
			[
				'label' => esc_html__( 'Line Color', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'classic',
						'liner',
						'typing',
                        'rotator',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_after_line_color',
			[
				'label' => esc_html__( 'Line Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-line' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'style_after_line_width',
			[
				'label' => esc_html__( 'Line Width', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-line' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'style_after_line_height',
			[
				'label' => esc_html__( 'Line Height', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-line' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();
        // End

		$this->start_controls_section(
			'style_section_symbol_svg',
			[
				'label' => esc_html__( 'SVG Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'svg',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'style_svg_color',
			[
				'label' => esc_html__( 'SVG Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-title-animate .bw-svg-wrapper svg' => 'stroke: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'style_svg_width',
			[
				'label' => esc_html__( 'Width', 'black-widgets' ),
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
					'{{WRAPPER}} .bw-title-animate .bw-svg-wrapper svg' => 'stroke-width: {{SIZE}}{{UNIT}} !important;',
				],
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

		$settings   	= $this->get_settings_for_display();

		// Variables
		$type 	        = isset($settings['widget_type'])						? $settings['widget_type']						: '';
		$title 			= isset($settings['widget_title']) 						? $settings['widget_title']						: '';
		$before_text	= isset($settings['widget_title_before'])				? $settings['widget_title_before']				: '';
		$main_text_text	= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$symbol			= isset($settings['widget_title_main_text_classic'])	? $settings['widget_title_main_text_classic']	: '';
		$liner			= isset($settings['widget_title_for_liner'])			? $settings['widget_title_for_liner']			: '';
		$la				= isset($settings['widget_title_la'])					? $settings['widget_title_la']					: '';
		$lb				= isset($settings['widget_title_lb'])					? $settings['widget_title_lb']					: '';
		$lc				= isset($settings['widget_title_lc'])					? $settings['widget_title_lc']					: '';
		$fadein1		= isset($settings['widget_title_la'])					? $settings['widget_title_la']					: '';
		$fadein2		= isset($settings['widget_title_lb'])					? $settings['widget_title_lb']					: '';
		$fadeout1		= isset($settings['widget_title_la'])					? $settings['widget_title_la']					: '';
		$fadeout2		= isset($settings['widget_title_lb'])					? $settings['widget_title_lb']					: '';
		$typetext		= isset($settings['widget_title_typing'])				? $settings['widget_title_typing']				: '';
		$ffl			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$fft			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$ffr			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$ffb			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$ffb2			= isset($settings['widget_title_main_text'])			? $settings['widget_title_main_text']			: '';
		$after_text		= isset($settings['widget_title_after'])				? $settings['widget_title_after']				: '';
		$glitch			= isset($settings['widget_title_glitch'])				? $settings['widget_title_glitch']				: '';

		$HTML   		= isset($settings['widget_html_tag']) 					? $settings['widget_html_tag']					: '';
		$alignment 		= isset($settings['widget_alignment']) 					? $settings['widget_alignment']					: '';

        // Rotator related
        $rotator_text = isset( $settings['widget_rotator_text'] ) ? esc_html( $settings['widget_rotator_text'] ) : '';
        $rotator_items = isset( $settings['widget_rotator_items'] ) ? $settings['widget_rotator_items'] : [];
        $rotator_anim = isset( $settings['widget_rotator_animation'] ) ? esc_attr( $settings['widget_rotator_animation'] ) : 'clip';
        $rotator_duration = isset( $settings['widget_rotator_duration'] ) ? esc_attr( $settings['widget_rotator_duration'] ) : '700';
        $rotator_delay = isset( $settings['widget_rotator_delay'] ) ? esc_attr( $settings['widget_rotator_delay'] ) : '1000';

        $svg = isset( $settings['widget_svg'] ) ? esc_attr( $settings['widget_svg'] ) : 'line1';
        $svg_text = isset( $settings['widget_svg_text'] ) ? esc_html( $settings['widget_svg_text'] ) : '';
        $svg_anim_text = isset( $settings['widget_svg_animation_text'] ) ? esc_html( $settings['widget_svg_animation_text'] ) : '';
        $svg_duration = isset( $settings['widget_svg_duration'] ) ? esc_attr( $settings['widget_svg_duration'] ) : '2000';
        $svg_delay = isset( $settings['widget_svg_delay'] ) ? esc_attr( $settings['widget_svg_delay'] ) : '1000';
        $svg_loop = isset( $settings['widget_svg_loop'] ) ? esc_attr( $settings['widget_svg_loop'] ) : 'yes';

		$data_id                = 'bw-' . uniqid();

        // wp_kses is rather slow so we manullay sanitize the html
        if ( ! in_array( $HTML, ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'] ) ) {
            $HTML = 'h1';
        }



echo '<div class="bw-title-animate ' . esc_attr( $alignment ) . '">';
	switch ($type) {
        case 'svg':
        ?>
            <div class="bw-title-anime bw-svg bw-svg-<?php echo $svg; ?> <?php echo $data_id; ?>" data-duration="<?php echo $svg_duration; ?>" data-delay="<?php echo $svg_delay; ?>" data-loop="<?php echo $svg_loop; ?>"> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <<?php echo $HTML; ?> class="bw-text-wrapper"> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span class="bw-svg-text"><?php echo $svg_text; ?></span> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <span class="bw-svg-wrapper bw-animate-text">
                    <span class="bw-svg-content"><?php echo $svg_anim_text; ?></span> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        <?php if ( $svg == 'line1' ): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 248 37" preserveAspectRatio="none">
	                            <path d="M50.5 7.49725C108.5 -2.5 170.262 5.97356 247 4.99725L1 21.9972C49.3333 12.6639 130 1.99726 237 34.9973"></path>
                            </svg>
                        <?php elseif ( $svg == 'line2' ): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 250 30" preserveAspectRatio="none">
                                <path d="M2 2C37.9961 23.9663 414.996 -13.0346 159.996 27.9648"></path>
                            </svg>
                        <?php elseif ( $svg == 'line3' ): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 287 29" preserveAspectRatio="none">
                            	<path d="M2 26.6047L20.8766 8.60606C27.0909 2.68078 36.8743 2.71705 43.0445 8.68823L52.5266 17.8644C58.4621 23.6085 67.7946 23.8904 74.066 18.5149L86.934 7.4851C93.2054 2.10964 102.538 2.39148 108.473 8.13558L118.375 17.7173C124.373 23.5227 133.828 23.7404 140.087 18.2171L152.088 7.62843C158.274 2.1696 167.598 2.31026 173.617 7.95324L184.535 18.1893C190.49 23.7722 199.693 23.9771 205.891 18.6648L219.276 7.1921C225.402 1.94075 234.481 2.07326 240.451 7.50118L250.776 16.887C257.463 22.9661 267.854 22.3045 273.716 15.4263L285 2.18605"></path>
                            </svg>
                        <?php elseif ( $svg == 'circle1' ): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 280 104" preserveAspectRatio="none">
                                <path d="M9.47006 2C84.4701 47 80.4701 113 199.47 100C320.017 86.831 304.47 26.0004 129.47 31.0004C-45.5299 36.0004 -21.5296 110 88.4718 16"></path>
                            </svg>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 319 85" preserveAspectRatio="none">
                                <path d="M211.5 2.45728C-64.0001 11.8469 -63.622 108.405 194.5 75.7379C309.501 61.184 450 17.9906 60.5 8.13129"></path>
                            </svg>
                        <?php endif; ?>
                    </span>
                </<?php echo $HTML; ?>>
            </div>
        <?php
            break;
        case 'rotator':
        ?>
            <div class="bw-title-anime bw-rotator <?php echo $data_id; ?>" data-duration="<?php echo $rotator_duration; ?>" data-delay="<?php echo $rotator_delay; ?>">
                <<?php echo $HTML; ?> class="bw-text-wrapper">
                <span class="bw-rotator-text"><?php echo $rotator_text; ?></span>
                <span class="bw-rotator-wrapper bw-animate-text bw-rotator-<?php echo $rotator_anim; ?>">
                    <?php if ( $rotator_anim == 'clip' || $rotator_anim == 'tlatter' ): ?>
				    <span class="bw-line"></span>
                    <?php endif; ?>
                    <?php foreach( $rotator_items as $item ): ?>
                    <span class="bw-rotator-item"><?php echo esc_html( $item['text'] ); ?></span>
                    <?php endforeach; ?>
                    <?php if ( $rotator_anim == 'bloading' ): ?>
                    <hr class="bw-rotator-bloading-line">
                    <?php endif; ?>
                </span>
                </<?php echo $HTML; ?>>
            </div>
        <?php
            break;
		case 'simple':
			?>
			<div class="bw-title-anime bw-simple-wrap  <?php echo $data_id ?>">
				<<?php echo $HTML; ?> class="bw-heading-animate">
					<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-simple <?php echo $data_id ?> bw-animate-text"><?php echo esc_html( $main_text_text ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</<?php echo $HTML; ?>>
			</div>
			<?php
			break;
		case 'classic':
			?>
			<<?php echo $HTML; ?> class="bw-title-anime bw-classic bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
					<span class="bw-line bw-line1"></span>
					<span class="bw-letters bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-symbol"><?php echo esc_html( $symbol ); ?></span>
					<span class="bw-letters bw-after"><?php echo esc_html( $after_text ); ?></span>
					<span class="bw-line bw-line2"></span>
				</span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'liner':
			?>
			<<?php echo $HTML; ?> class="bw-liner bw-heading-animate <?php echo $data_id ?>">
			<span class="bw-text-wrapper">
				<span class="bw-letters bw-animate-text"><?php echo esc_html( $liner ); ?></span>
				<span class="bw-line"></span>
			</span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'effective':
			?>
			<<?php echo $HTML; ?> class="bw-effective bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-letters bw-letters-1 bw-animate-text"><?php echo esc_html( $la ); ?></span>
				<span class="bw-letters bw-letters-2 bw-animate-text"><?php echo esc_html( $lb ); ?></span>
				<span class="bw-letters bw-letters-3 bw-animate-text"><?php echo esc_html( $lc ); ?></span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'typing':
			?>
			<<?php echo $HTML; ?> class="bw-typing bw-heading-animate <?php echo $data_id ?>">
			<span class="bw-text-wrapper">
				<span class="bw-line bw-line1"></span>
				<span class="bw-letters bw-animate-text"><?php echo esc_html( $typetext ); ?></span>
			</span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'fft': // Fade From Top
			?>
			<<?php echo $HTML; ?> class="bw-fft bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
					<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-animate-text"><?php echo esc_html( $fft ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'ffb': // Fade From Bottom
			?>
			<<?php echo $HTML; ?> class="bw-ffb bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
				<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-animate-text"><?php echo esc_html( $ffb ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'ffb2': // Fade From Bottom
			?>
			<<?php echo $HTML; ?> class="bw-fade-text <?php echo $data_id ?>">
				<span class="bw-animate-text"><?php echo esc_html( $ffb2 ); ?></span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'ffl': // Fade From Left
			?>
			<<?php echo $HTML; ?> class="bw-ffl bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
					<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-animate-text"><?php echo esc_html( $ffl ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'ffr': // Fade From Right
			?>
			<<?php echo $HTML; ?> class="bw-ffr bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-text-wrapper">
				<span class="bw-before"><?php echo esc_html( $before_text ); ?></span>
					<span class="bw-letters bw-animate-text"><?php echo esc_html( $ffr ); ?></span>
					<span class="bw-after"><?php echo esc_html( $after_text ); ?></span>
				</span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'fade_in':
			?>
			<<?php echo $HTML; ?> class="bw-fin bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-word bw-animate-text"><?php echo esc_html( $fadein1 ); ?></span>
				<span class="bw-word bw-animate-text"><?php echo esc_html( $fadein2 ); ?></span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'fade_out':
			?>
			<<?php echo $HTML; ?> class="bw-fout bw-heading-animate <?php echo $data_id ?>">
				<span class="bw-word bw-animate-text"><?php echo esc_html( $fadeout1 ); ?></span>
				<span class="bw-word bw-animate-text"><?php echo esc_html( $fadeout2 ); ?></span>
			</<?php echo $HTML; ?>>		
			<?php
			break;
		case 'glitch_one':
			?>
			<<?php echo $HTML; ?> class="bw-glitch bw-heading-animate">
				<span class="bw-glitch bw-animate-text" data-text="<?php echo esc_attr( $glitch ); ?>"><?php echo esc_html( $glitch ); ?></span>
			</<?php echo $HTML; ?>>
			<?php
			break;
		case 'glitch_two':
			?>
			<div class="bw-glitch-wrapper">
				<<?php echo $HTML; ?> class="bw-glitch bw-animate-text" data-text="<?php echo esc_attr( $glitch ); ?>"><?php echo esc_html( $glitch ); ?></<?php echo $HTML; ?>>
			</div>
			<?php
			break;
		default: //Glitch Two is default
			?>
			<div class="bw-glitch-wrapper">
				<<?php echo $HTML; ?> class="bw-glitch bw-heading-animate bw-animate-text" data-text="<?php echo esc_attr( $glitch ); ?>"><?php echo esc_html( $glitch ); ?></<?php echo $HTML; ?>> 
			</div>
			<?php
			break;
	}
echo '</div>';

	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\TitleAnimate', 'Black_Widgets\BLACK_WIDGETS_Title_Animate');
