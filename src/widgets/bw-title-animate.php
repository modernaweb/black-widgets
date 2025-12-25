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
        wp_register_script( 'black-widgets-title-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/title-animate.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION, true );
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
                    'line4' 	    => esc_html__( 'Line 4', 'black-widgets' ),
                    'line5' 	    => esc_html__( 'Line 5', 'black-widgets' ),
                    'circle1' 	    => esc_html__( 'Circle 1', 'black-widgets' ),
                    'circle2' 	    => esc_html__( 'Circle 2', 'black-widgets' ),
                    'circle3' 	    => esc_html__( 'Circle 3', 'black-widgets' ),
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
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate' => 'text-align: {{VALUE}};',
                ],
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
                'condition' => [
                    'widget_type' => 'rotator',
                ],
            ]
        );

        $this->add_control(
            'style_main_title_color1',
            [
                'label' => esc_html__( 'Color 1(Color Effect)', 'black-widgets' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff5b5b',
                'condition' => [
                    'widget_rotator_animation' => 'ceffect',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'style_main_title_color2',
            [
                'label' => esc_html__( 'Color 2(Color Effect)', 'black-widgets' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#0000ff',
                'condition' => [
                    'widget_rotator_animation' => 'ceffect',
                ],
                'render_type' => 'template',
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
                'label' => esc_html__( 'Line Color (Type)', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'widget_type' => [ 'classic', 'liner', 'typing' ],
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

        $this->add_responsive_control(
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

        $this->add_responsive_control(
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

        $this->start_controls_section(
            'style_section_symbol_line_1',
            [
                'label' => esc_html__( 'Line Color (Animation)', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'widget_type' => [ 'rotator' ],
                    'widget_rotator_animation' => [ 'clip', 'tlatter' ],
                ],
            ]
        );

        // Color
        $this->add_control(
            'style_after_line_color1',
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

        $this->add_responsive_control(
            'style_after_line_width1',
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

        $this->add_responsive_control(
            'style_after_line_height1',
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
            'style_svg_stroke_width',
            [
                'label' => esc_html__( 'Stroke Width', 'black-widgets' ),
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

        $this->add_control(
            'style_svg_width',
            [
                'label' => esc_html__( 'Width', 'black-widgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-svg-wrapper svg' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'style_svg_height',
            [
                'label' => esc_html__( 'Height', 'black-widgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-svg-wrapper svg' => 'height: {{SIZE}}{{UNIT}} !important;',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'svg_viewbox_width',
            [
                'label' => __( 'View Box Width', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 1,
            ]
        );

        $this->add_control(
            'svg_viewbox_height',
            [
                'label' => __( 'View Box Height', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 1,
            ]
        );

        $this->add_control(
            'svg_viewbox_min_x',
            [
                'label' => __( 'View Box Min-X', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 0.01,
            ]
        );

        $this->add_control(
            'svg_viewbox_min_y',
            [
                'label' => __( 'View Box Min-Y', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 0.01,
            ]
        );

        $this->add_responsive_control(
            'svg_translate_x',
            [
                'label' => __('Offset X', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => -200, 'max' => 200],
                ],
                'default' => ['size' => 0],
            ]
        );

        $this->add_responsive_control(
            'svg_translate_y',
            [
                'label' => __('Offset Y', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => -200, 'max' => 200],
                ],
                'default' => ['size' => 0],
            ]
        );

        $this->end_controls_section();
    }

    private function resolve_elementor_color( $color_value ) {
        if ( empty( $color_value ) ) {
            return '';
        }

        if ( strpos( $color_value, 'var(' ) === 0 ) {
            return Utils::get_global_color( $color_value );
        }

        return $color_value;
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
        $title_color1 = isset( $settings['style_main_title_color1'] ) ? esc_attr( $this->resolve_elementor_color($settings['style_main_title_color1']) ) : '#ff5b5b';
        $title_color2 = isset( $settings['style_main_title_color2'] ) ? esc_attr( $this->resolve_elementor_color($settings['style_main_title_color2']) ) : 'blue';

        $svg = isset( $settings['widget_svg'] ) ? esc_attr( $settings['widget_svg'] ) : 'line1';
        $svg_text = isset( $settings['widget_svg_text'] ) ? esc_html( $settings['widget_svg_text'] ) : '';
        $svg_anim_text = isset( $settings['widget_svg_animation_text'] ) ? esc_html( $settings['widget_svg_animation_text'] ) : '';
        $svg_duration = isset( $settings['widget_svg_duration'] ) ? esc_attr( $settings['widget_svg_duration'] ) : '2000';
        $svg_delay = isset( $settings['widget_svg_delay'] ) ? esc_attr( $settings['widget_svg_delay'] ) : '1000';
        $svg_loop = isset( $settings['widget_svg_loop'] ) ? esc_attr( $settings['widget_svg_loop'] ) : 'yes';

        $min_x  = $settings['svg_viewbox_min_x'];
        $min_y  = $settings['svg_viewbox_min_y'];
        $vb_w   = $settings['svg_viewbox_width'];
        $vb_h   = $settings['svg_viewbox_height'];

        if ( empty($min_x) && empty($vb_w) ) {
            switch ( $svg ) {
                case 'line1':
                    $min_x = 0; $min_y = 0; $vb_w = 316.08; $vb_h = 47.57;
                    break;
                case 'line2':
                    $min_x = 0; $min_y = 0; $vb_w = 328;    $vb_h = 37;
                    break;
                case 'line3':
                    $min_x = 0; $min_y = 0; $vb_w = 376;    $vb_h = 42;
                    break;
                case 'line4':
                    $min_x = 0; $min_y = 0; $vb_w = 577;    $vb_h = 86;
                    break;
                case 'line5':
                    $min_x = 0; $min_y = 0; $vb_w = 476;    $vb_h = 41;
                    break;
                case 'circle1':
                    $min_x = 0; $min_y = 0; $vb_w = 429;    $vb_h = 110;
                    break;
                case 'circle2':
                    $min_x = 0; $min_y = 0; $vb_w = 340;    $vb_h = 85;
                    break;
                default:
                    $min_x = 0; $min_y = 0; $vb_w = 430;    $vb_h = 121;
                    break;
            }
        }

        $viewBox = "{$min_x} {$min_y} {$vb_w} {$vb_h}";

        $offset_x = $settings['svg_translate_x']['size'] ?? 0;
        $offset_y = $settings['svg_translate_y']['size'] ?? 0;

        $inline_transform = "transform: translate({$offset_x}px, {$offset_y}px);";

        $data_id                = 'bw-' . uniqid();

        // wp_kses is rather slow so we manullay sanitize the html
        if ( ! in_array( $HTML, ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'] ) ) {
            $HTML = 'h1';
        }
        ?>
        <style>
            .bw-title-animate .bw-svg-wrapper svg{
            <?php echo $inline_transform;?> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            }
        </style>
        <?php
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="<?php echo esc_attr($viewBox); ?>" preserveAspectRatio="none">
                              <path d="M1.68,49.56c4.26-5.75,8.53-11.49,12.87-17.18,2.18-2.83,4.33-5.7,6.67-8.41s4.73-5.38,7.32-7.86a30.55,30.55,0,0,1,4.17-3.43,10.87,10.87,0,0,1,5.08-1.95A7.25,7.25,0,0,1,43,12.46a8.61,8.61,0,0,1,2.13,5,40,40,0,0,1-.38,10.77,92.21,92.21,0,0,1-2.22,10.54h0v0a3.09,3.09,0,0,0,0,2.12,2,2,0,0,0,1.71,1,12.09,12.09,0,0,0,4.59-.78,59.62,59.62,0,0,0,8.74-4c5.66-3,11.12-6.46,16.54-9.92s10.81-7,16.36-10.32A89.89,89.89,0,0,1,99,12.38a28.74,28.74,0,0,1,4.61-1.67,8.5,8.5,0,0,1,5,0A14.48,14.48,0,0,1,110.74,12l2,1.36,4,2.75A43.44,43.44,0,0,0,125,20.7a15.79,15.79,0,0,0,9.18,1,36.58,36.58,0,0,0,4.54-1.42c1.51-.55,3-1.13,4.49-1.73C155.19,13.78,166.87,8.31,179,3.91c1.53-.54,3.07-1.05,4.65-1.45A14.82,14.82,0,0,1,186.11,2a3.41,3.41,0,0,1,1.34.12,5.53,5.53,0,0,1,1.2.5,11.12,11.12,0,0,1,3.47,3.56,43.51,43.51,0,0,1,2.41,4.24c1.48,2.87,2.85,5.78,4.55,8.49a22,22,0,0,0,6.36,7,14.59,14.59,0,0,0,9,2.28,28.43,28.43,0,0,0,9.2-2.35,135.24,135.24,0,0,1,18.21-6.53c6.19-1.81,12.46-3.35,18.75-4.74s12.61-2.65,19-3.78q19-3.4,38.22-5.76-19.15,2.53-38.15,6.13C267,13.56,254.35,16.21,242,19.83a135.5,135.5,0,0,0-18.11,6.57,29.17,29.17,0,0,1-9.42,2.43,15.28,15.28,0,0,1-9.42-2.36,22.73,22.73,0,0,1-6.61-7.16c-1.73-2.75-3.13-5.67-4.6-8.52a42.15,42.15,0,0,0-2.38-4.15,10.34,10.34,0,0,0-3.21-3.3,5.47,5.47,0,0,0-1-.43,2.79,2.79,0,0,0-1.05-.08,13.35,13.35,0,0,0-2.29.43c-1.54.4-3.06.91-4.57,1.44-12.07,4.43-23.74,9.93-35.69,14.78-1.5.6-3,1.19-4.52,1.75a34.25,34.25,0,0,1-4.67,1.46,16.73,16.73,0,0,1-9.74-1.1,43.86,43.86,0,0,1-8.5-4.75l-4-2.77-2-1.36a13.84,13.84,0,0,0-2-1.2,7.61,7.61,0,0,0-4.48.05,28.09,28.09,0,0,0-4.47,1.61,86.24,86.24,0,0,0-8.49,4.5c-5.53,3.27-10.92,6.8-16.37,10.24s-10.92,6.85-16.62,9.9a61.24,61.24,0,0,1-8.86,4,12.63,12.63,0,0,1-4.87.8,3.63,3.63,0,0,1-1.27-.37,2.24,2.24,0,0,1-1-1,3.67,3.67,0,0,1,0-2.61v0a92.94,92.94,0,0,0,2.24-10.45,39.3,39.3,0,0,0,.41-10.6,8.06,8.06,0,0,0-2-4.7,6.66,6.66,0,0,0-4.75-1.61A10.5,10.5,0,0,0,33,13.07a30.75,30.75,0,0,0-4.16,3.32c-2.61,2.43-5,5.09-7.42,7.73s-4.52,5.54-6.72,8.35C10.29,38.13,6,43.84,1.68,49.56Z" fill="none"/>
                            </svg>
                    <?php elseif ( $svg == 'line2' ): ?>
                        <svg viewBox="<?php echo esc_attr($viewBox); ?>" preserveAspectRatio="none">
                                <path d="M44.3525 5.74382C30.486 11.7513 8.14436 26.7042 0.86674 34.8649C-0.776934 36.7272 -0.114052 36.4263 3.12274 33.7202C15.7479 23.4491 37.538 10.9036 50.7058 6.31447C59.9361 3.12042 72.2493 2.2982 77.9333 4.48503C83.2911 6.51822 85.1576 8.86903 91.626 21.757C100.098 38.593 102.585 38.3238 138.204 18.1588C148.465 12.3848 153.571 10.007 157.414 9.33379L162.641 8.45445L167.226 16.1633C169.684 20.4481 172.864 24.8276 174.242 25.9367C179.627 30.1901 189.518 28.7175 205.626 21.4665C215.456 16.8688 230.85 10.9372 233.62 10.5249C234.807 10.3482 237.391 12.0866 240.478 15.1655C249.398 24.1499 256.993 24.2324 288.92 15.9405C296.616 13.9859 309.159 11.3097 316.859 10.0622C330.494 7.93112 330.593 7.91644 322.266 8.34739C317.558 8.64387 301.549 11.1283 286.702 13.9454C253.363 20.2232 253.264 20.2379 243.403 12.4047C236.64 7.04215 236.2 6.80434 231.764 7.5659C229.306 8.03296 219.23 11.6563 209.509 15.631C189.489 23.7679 182.06 25.4805 177.534 22.9191C175.873 21.9531 173.237 18.5038 170.922 14.501C168.963 10.8497 166.154 6.92051 164.874 5.79667C160.739 2.46931 160.457 2.61239 120.08 24.0924C109.858 29.4561 103.151 31.2634 100.538 29.3272C99.6287 28.6537 96.5587 23.6513 93.7264 18.209C87.5483 6.59213 85.4693 4.17189 78.8068 1.52414C74.2844 -0.330126 72.6577 -0.391242 63.3307 0.79516C54.8792 1.75012 51.2931 2.78956 44.3525 5.74382Z" fill="none"/>
                            </svg>
                    <?php elseif ( $svg == 'line3' ): ?>
                        <svg viewBox="<?php echo esc_attr($viewBox); ?>" preserveAspectRatio="none">
                                <path d="M196.719 0.493638C120.619 3.89364 67.5187 11.1936 20.2187 24.8936C13.4187 26.8936 -0.581313 32.7936 0.0186868 33.3936C0.218687 33.5936 3.41869 32.6936 7.21869 31.2936C12.3187 29.4936 14.5187 29.1936 15.4187 29.9936C17.2187 31.4936 27.2187 30.5936 62.7187 25.8936C147.219 14.5936 199.419 10.2936 253.219 10.3936C293.319 10.3936 304.819 11.1936 334.719 16.0936L348.219 18.2936L261.219 18.8936C161.619 19.5936 100.619 21.5936 97.9187 24.2936C97.6187 24.5936 97.8187 25.2936 98.4187 25.8936C99.2187 26.6936 108.619 26.5936 131.319 25.6936C182.319 23.6936 255.819 24.0936 253.519 26.2936C252.819 27.0936 194.219 29.7936 178.919 29.7936C159.019 29.7936 155.519 30.2936 155.919 33.1936C156.419 36.3936 161.219 36.6936 191.219 35.5936C216.819 34.6936 233.119 34.8936 234.519 36.1936C234.819 36.5936 234.519 37.3936 233.919 37.9936C233.219 38.6936 232.719 39.7936 232.719 40.4936C232.719 41.9936 241.819 42.0936 252.219 40.7936C257.819 40.0936 257.519 40.0936 248.019 39.8936C239.019 39.7936 237.819 39.5936 237.919 37.9936C237.919 37.0936 237.719 35.5936 237.319 34.6936C236.719 33.2936 238.119 32.9936 248.019 32.4936C259.819 31.9936 266.719 30.0936 266.719 27.3936C266.719 25.3936 264.219 24.6936 248.219 22.7936L234.219 21.1936L290.419 21.5936C334.319 21.9936 347.319 21.7936 349.619 20.7936C351.319 20.0936 352.719 19.1936 352.719 18.6936C352.719 15.7936 312.019 10.3936 275.919 8.69362C226.719 6.29362 134.119 12.5936 53.8187 23.8936C42.1187 25.4936 31.7187 26.6936 30.8187 26.5936C26.7187 25.6936 86.4187 14.8936 109.219 12.2936C165.319 6.09363 235.419 3.19363 294.219 4.79363C314.019 5.29363 331.819 5.99363 333.819 6.29363C338.819 6.99363 374.819 6.99363 375.519 6.39363C375.819 6.09363 375.819 5.49362 375.519 5.19362C374.419 4.09362 347.219 1.89363 322.719 0.893632C301.219 -0.106368 214.419 -0.306362 196.719 0.493638ZM28.4187 27.3936C28.1187 27.6936 27.2187 27.7936 26.5187 27.4936C25.7187 27.1936 26.0187 26.8936 27.1187 26.8936C28.2187 26.7936 28.8187 27.0936 28.4187 27.3936Z" fill="none"/>
                            </svg>
                    <?php elseif ( $svg == 'line4' ): ?>
                        <svg viewBox="<?php echo esc_attr($viewBox); ?>" preserveAspectRatio="none">
                                <path d="M550.58 16.4571C490.697 13.836 453.14 13.1266 418.308 14.1332L395.382 14.8709L393.85 12.3838C389.906 6.304 363.522 0.211729 339.493 0.0201282C303.504 -0.31136 257.492 3.46081 208.781 10.6522C169.988 16.4454 168.342 16.7631 132.506 24.9019C95.3324 33.2927 84.2183 36.3677 63.976 43.6471C30.53 55.5775 9.81603 67.936 2.50882 80.0943C-1.21126 86.3169 -0.661214 87.6871 3.27861 82.1937C13.4928 68.3503 50.4868 54.4045 133.586 33.1749C198.247 16.6103 285.851 3.63699 336.418 3.19582C355.158 2.99276 374.159 5.83803 383.032 10.1585C386.662 11.8758 389.475 13.7022 389.309 14.2873C389.153 14.7729 381.686 15.8437 372.641 16.6359C331.003 19.9952 287.68 27.5924 240.518 39.7888C198.637 50.6681 180.877 59.1329 173.426 71.6776C169.728 77.7014 172.161 82.9065 179.633 85.3585C190.031 88.6433 262.293 76.9605 307.048 64.6942C342.456 54.9976 371.972 42.2219 385.097 31.1216C388.879 27.9249 392.297 24.3853 392.818 23.337C393.25 22.1782 394.046 21.3622 394.543 21.4182C395.04 21.4742 403.632 21.134 413.561 20.5419C437.35 19.2975 494.797 18.5246 564.933 18.5774C584.457 18.5633 578.02 17.6368 550.58 16.4571ZM385.275 23.2923C384.973 25.9753 364.989 37.2086 347.784 44.327C337.132 48.6616 313.83 56.3008 299.623 60.0336C279.406 65.3032 237.816 73.5988 218.806 76.1868C198.472 78.9273 180.714 79.3417 180.96 77.1555C181.027 76.5593 184.366 73.7153 188.324 70.7397C197.335 64.0062 216.661 56.8248 249.66 47.9638C296.978 35.2818 333.011 28.0708 369.096 23.9852C386.216 22.09 385.409 22.0998 385.275 23.2923Z" fill="none"/>
                            </svg>
                    <?php elseif ( $svg == 'line5' ): ?>
                        <svg viewBox="<?php echo esc_attr($viewBox); ?>" preserveAspectRatio="none">
                                <path d="M233.754 0.77944C183.758 2.04294 158.133 4.20898 120.322 9.62399C76.24 16.122 19.9726 29.2985 4.92012 36.5185C0.619413 38.6845 -1.17264 39.948 0.798522 39.226C5.09923 37.9625 57.0662 27.674 86.6336 22.259C108.675 18.288 115.484 18.1075 100.252 22.0785C86.4543 25.6885 89.3214 29.479 105.807 29.479C110.646 29.479 137.346 27.1325 165.121 24.064C230.17 17.205 260.991 15.039 295.576 15.039C333.924 15.039 379.799 19.01 379.799 22.259C379.799 25.3275 367.076 26.0495 290.2 27.674C216.73 29.118 176.052 32.006 172.289 35.616C170.497 37.6015 175.873 40.309 181.787 40.4895C191.105 40.8505 386.966 41.2115 385.533 40.8505C377.827 39.0455 318.872 35.977 272.281 34.894L214.042 33.6305L283.929 33.45C353.815 33.089 371.914 31.8255 382.128 26.591C386.608 24.064 386.966 23.342 384.995 21.176C381.053 17.3855 367.434 15.4 330.34 13.234C283.929 10.5265 234.291 12.8729 142.722 22.4394C123.369 24.4249 105.628 25.869 103.299 25.508C95.5932 24.2445 142.005 15.039 194.33 7.45796C220.314 3.66746 316.721 4.56996 378.903 9.26296C407.574 11.429 437.858 13.5949 446.459 14.3169C475.131 16.3024 487.316 10.707 462.945 6.55546C433.557 1.68196 311.346 -1.56706 233.754 0.77944ZM406.32 5.47251C403.453 5.83351 399.152 5.83351 396.464 5.47251C393.597 5.11151 395.926 4.75046 401.302 4.75046C406.678 4.75046 409.008 5.11151 406.32 5.47251ZM436.783 7.27751C434.095 7.63851 429.257 7.63851 426.031 7.27751C422.806 6.91651 424.956 6.55546 430.869 6.55546C436.783 6.55546 439.471 6.91651 436.783 7.27751Z" fill="none"/>
                            </svg>
                    <?php elseif ( $svg == 'circle1' ): ?>
                        <svg viewBox="<?php echo esc_attr($viewBox); ?>" preserveAspectRatio="none">
                                <path d="M380.812 28.0931C359.653 22.1123 317.24 12.9757 292.128 9.02035C232.442 -0.425813 166.268 -2.57113 110.617 3.14367C70.8259 7.17855 41.7303 12.0434 25.4633 17.4102C13.8601 21.1758 4.82592 26.5986 3.45224 30.571C3.0101 31.9579 3.06112 31.976 4.40027 30.3362C8.20608 25.6847 19.0067 22.5487 47.8617 17.7127C56.0872 16.2916 69.1985 14.0917 76.9428 12.7282C103.701 8.11884 140.303 4.15044 164.098 3.29126C193.074 2.27286 228.797 3.13918 253.104 5.49307C286.853 8.8028 302.673 11.2266 335.449 18.1938C369.078 25.35 372.639 26.3305 392.2 33.2302C408.988 39.2006 409.378 39.3965 414.413 42.6744C424.248 49.0889 427.122 52.4558 427.661 58.3093C428.356 64.8475 426.655 68.1314 420.458 72.39C415.497 75.7729 400.487 82.4446 391.962 85.0172C350.648 97.4323 300.628 104.292 225.552 107.845C184.916 109.749 150.294 109.503 97.1795 106.741C54.5602 104.566 31.3408 100.311 14.2711 91.6094C5.18604 86.9485 2.24286 83.6141 1.51119 77.1773C1.09463 73.5405 1.21552 72.7257 2.62197 68.8222C4.02841 64.9187 4.45546 64.2128 7.43238 61.3825C15.856 53.3406 30.0726 45.3572 47.3575 38.9226C61.2365 33.7931 85.2075 27.3345 101.232 24.3979C110.526 22.7277 127.509 20.6465 138.516 19.8142C148.56 19.0972 185.159 17.5302 206.516 16.8901C215.349 16.6575 222.608 16.3223 222.524 16.2353C222.098 15.3404 151.43 17.2013 132.148 18.6361C103.2 20.694 69.0606 28.0544 44.4239 37.479C21.9131 46.1155 4.4344 58.2002 1.12835 67.3759C-1.56045 74.9982 0.668028 83.5115 6.62171 87.9166C19.4217 97.4445 43.0079 103.717 76.0352 106.313C99.9928 108.199 153.247 110.096 180.625 109.996C222.163 109.9 285.859 106.302 316.047 102.398C348.97 98.0939 364.207 95.106 387.858 88.4189C400.883 84.7014 409.985 80.8469 419.924 74.8308C426.493 70.8188 429.5 65.8263 428.932 59.7337C428.274 52.2936 424.985 48.3215 413.995 41.439C409.141 38.4546 407.257 37.6129 396.138 33.4866C389.267 30.986 382.379 28.5362 380.812 28.0931Z" fill="none"/>
                            </svg>
                    <?php elseif ( $svg == 'circle2' ): ?>
                        <svg viewBox="<?php echo esc_attr($viewBox); ?>" preserveAspectRatio="none">
                                <path d="M243.818 0.800003C217.118 1.9 194.918 3.5 175.818 5.8C153.718 8.3 154.318 8.20001 79.2179 22.6C58.7179 26.6 41.3179 31.6 31.8179 36.3C29.1179 37.6 27.7179 38.4 28.8179 38.1C29.9179 37.7 36.4179 35.4 43.3179 33.1C54.6179 29.2 85.3179 22.2 87.6179 23C88.2179 23.2 81.0179 25 71.7179 27.2C22.3179 38.4 -3.18206 52.2 0.31794 65.7C1.91794 72 14.0179 77.4 32.5179 80.1C55.9179 83.5 68.9179 84.2 114.818 84.1C209.718 84 281.918 76.6 315.318 63.5C327.818 58.6 337.618 50.1 338.918 42.8C340.418 34.8 333.318 28.7 314.718 22.4C297.118 16.4 277.618 13.1 247.418 10.7C230.518 9.4 181.718 9.4 165.818 10.7C148.818 12.0333 141.985 12.3333 145.318 11.6C162.918 7.80001 210.818 3.6 262.318 1.3C285.418 0.300003 288.618 0 276.818 0C268.618 0 253.718 0.400003 243.818 0.800003ZM236.318 11.7C277.418 13.9 308.418 19.7 326.418 28.8C339.518 35.3 340.818 43.6 330.218 53.2C322.218 60.4 309.018 65.1 279.818 71.2C222.918 83.1 64.1179 86.8 25.3179 77.2C21.0179 76.1 20.7179 75.9 23.8179 76.3C70.2179 81.7 185.218 77.2 243.818 67.7C288.118 60.5 314.318 49.7 314.318 38.6C314.318 34.3 307.218 27.6 299.718 24.8C292.218 21.9 276.718 18.3 263.818 16.3C225.618 10.5 176.718 11 126.418 17.7C118.418 18.8 111.118 19.6 110.318 19.6C106.218 19.3 123.018 16.6 140.318 14.7C176.618 10.8 202.418 10 236.318 11.7ZM234.318 14.7C253.518 16 268.118 18.1 283.218 21.8C301.418 26.1 309.318 30.1 311.818 36.1C312.918 38.8 312.918 39.6 311.518 42C303.718 55.3 257.118 67.1 191.818 72.2C151.418 75.3 140.918 75.8 96.7179 76.4C40.4179 77.1 19.3179 75.7 9.01794 70.6C0.91794 66.5 6.41794 60.3 28.1179 49C54.1179 35.4 78.9179 26.8 103.718 22.7C150.118 15 196.818 12.1 234.318 14.7ZM108.018 20.3C107.718 20.6 106.818 20.7 106.118 20.4C105.318 20.1 105.618 19.8 106.718 19.8C107.818 19.7 108.418 20 108.018 20.3ZM92.0179 22.3C91.7179 22.6 90.8179 22.7 90.1179 22.4C89.3179 22.1 89.6179 21.8 90.7179 21.8C91.8179 21.7 92.4179 22 92.0179 22.3ZM63.3179 31.4C42.2179 39.3 16.1179 52.6 8.31794 59.5C4.01794 63.2 3.21794 64.4 3.61794 66.6C4.11794 69 4.01794 69.1 2.71794 67.2C-2.98206 59.4 9.71794 48.1 34.3179 39.3C43.6179 36 61.3179 30.8 63.3179 30.9C64.1846 30.9 64.1846 31.0667 63.3179 31.4Z" fill="none"/>
                            </svg>
                    <?php else: ?>
                        <svg viewBox="<?php echo esc_attr($viewBox); ?>" preserveAspectRatio="none">
                                <path d="M164.917 1.30621C122.49 4.10807 69.9245 12.2468 48.1776 19.3182C24.6964 26.9232 6.95202 38.6643 1.48196 50.0052C-3.05419 59.745 2.94955 69.885 17.7588 77.4901C33.5019 85.7622 58.0504 92.2999 91.1377 97.37C104.613 99.3713 112.484 101.373 120.222 104.575C146.372 115.649 172.255 119.918 218.95 120.852C262.978 121.786 294.998 118.317 324.483 109.778C330.62 108.044 337.291 107.777 364.774 108.311C389.323 108.711 392.792 108.711 378.383 107.91C367.709 107.377 353.968 106.843 347.697 106.71L336.357 106.576L351.299 99.2379C359.571 95.3687 368.51 90.4319 371.178 88.4306C374.78 85.6288 379.717 83.8943 391.057 81.6261C399.329 80.0251 408.802 77.4901 412.137 76.0225C420.009 72.5535 428.681 64.6816 429.748 59.8785C434.418 38.7978 373.713 15.3155 286.993 4.50837C250.303 -0.0279778 203.608 -1.22881 164.917 1.30621ZM226.288 3.44093C153.71 5.44226 97.9419 13.3142 52.1801 27.8572C21.361 37.7304 16.1578 44.0012 27.765 56.6763C30.8335 60.0119 36.3036 64.2813 39.7724 66.0158C46.8435 69.6182 65.6551 74.8217 79.9307 77.3567C86.6015 78.4241 89.2698 79.4914 90.3371 81.6261C91.1376 83.0938 94.2062 86.8297 97.0079 89.8984L102.211 95.502L83.9332 92.2999C45.3759 85.6288 19.0929 76.6896 8.68647 66.6829C1.61541 59.8784 0.548044 56.1426 4.15028 49.3381C9.35351 39.0646 31.7674 25.8558 54.8484 19.3182C75.528 13.4476 119.422 6.90993 157.579 3.97465C165.717 3.44096 187.331 2.90721 205.609 2.90721C224.02 3.04063 233.226 3.17408 226.288 3.44093ZM257.374 4.77515C255.64 5.04199 252.705 5.04199 250.703 4.77515C248.569 4.5083 250.036 4.24143 253.639 4.24143C257.241 4.24143 258.975 4.5083 257.374 4.77515ZM271.383 6.10936C270.049 6.37621 267.647 6.37621 266.046 6.10936C264.312 5.84252 265.379 5.57564 268.314 5.57564C271.25 5.44222 272.584 5.7091 271.383 6.10936ZM273.651 9.5783C282.857 11.3128 282.723 11.4462 262.177 10.7791C217.483 9.44491 171.054 17.7171 130.896 34.128C100.877 46.4028 87.5354 57.0765 87.5354 69.0844C87.5354 72.9537 87.2686 73.0871 83.2661 72.2866C69.6576 69.7516 48.4445 63.7477 42.1739 60.5456C33.7687 56.4095 24.8298 48.404 24.8298 45.0685C24.8298 39.4648 41.5068 32.3935 74.8609 23.8545C103.946 16.2495 137.566 11.046 180.927 7.31019C195.336 6.1094 264.579 7.7104 273.651 9.5783ZM306.071 10.5124C348.497 17.7171 378.116 25.8559 401.864 37.1967C418.408 44.9352 426.413 51.7396 426.413 57.7436C426.413 60.0118 425.479 63.2139 424.411 64.9484C421.876 68.4174 412.537 74.1546 406.133 76.0225C397.328 78.6909 378.383 82.1599 378.383 81.226C378.383 80.6923 379.317 78.424 380.384 76.4226C387.722 62.2799 376.115 43.0672 352.1 29.5916C342.094 23.9879 312.075 14.2481 294.331 10.6457C273.784 6.64309 274.452 6.77651 278.587 6.64309C280.455 6.64309 292.863 8.3776 306.071 10.5124ZM300.334 19.1848C327.151 23.054 334.222 24.9218 348.097 32.26C366.108 41.4661 379.717 56.9432 379.717 67.8838C379.583 73.4875 374.914 82.4266 371.445 83.0937C352.1 87.3632 258.442 89.3647 208.277 86.6962C179.459 85.0952 135.832 80.6921 107.148 76.4226C94.4731 74.4213 92.4718 73.8876 91.271 71.0858C89.2698 66.6828 94.3397 56.6762 101.411 51.6062C108.615 46.2693 130.896 35.5957 144.237 31.1927C193.201 15.0487 244.833 11.046 300.334 19.1848ZM133.564 84.5615C183.328 90.0318 237.495 92.0331 291.662 90.5655C332.888 89.3647 357.97 87.8971 367.709 85.8957L371.045 85.2286L367.709 88.0304C363.307 91.7663 349.165 98.8376 337.557 102.974L328.085 106.309L273.518 105.108C174.123 102.974 121.69 99.7716 112.751 95.2353C106.481 92.0331 98.4756 85.4954 96.7412 82.2932C95.407 79.8916 95.6738 79.7582 99.1426 80.4253C101.144 80.959 116.754 82.827 133.564 84.5615ZM184.929 105.242C200.672 105.909 237.629 106.843 266.98 107.377C300.468 107.91 318.879 108.711 316.344 109.511C306.605 112.18 278.988 115.916 257.908 117.25C221.085 119.651 182.394 116.716 151.842 109.245C128.628 103.507 126.893 102.44 142.903 103.241C150.241 103.641 169.186 104.441 184.929 105.242Z" fill="none"/>
                            </svg>
                    <?php endif; ?>
                    </span>
                </<?php echo esc_html( $HTML ); ?>>
                </div>
                <?php
                break;
            case 'rotator':
                ?>
                <style>
                    .bw-title-animate .bw-rotator-ceffect .bw-rotator-item {
                    <?php echo 'background-image: linear-gradient(90deg, '.$title_color1.','.$title_color2.');'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
                    }
                </style>
            <div class="bw-title-anime bw-rotator <?php echo esc_attr($data_id); ?>"
                 data-duration="<?php echo esc_attr($rotator_duration); ?>"
                 data-delay="<?php echo esc_attr($rotator_delay); ?>">

                <<?php echo esc_html( $HTML ); ?> class="bw-text-wrapper">
                <span class="bw-rotator-text"><?php echo esc_html($rotator_text); ?></span>
                <span class="bw-rotator-wrapper bw-animate-text bw-rotator-<?php echo esc_attr($rotator_anim); ?>">

                <?php if (in_array($rotator_anim, ['clip', 'tlatter'], true)): ?>
                    <span class="bw-line"></span>
                <?php endif; ?>

                    <?php foreach ($rotator_items as $item): ?>
                        <?php if (isset($item['text'])): ?>
                            <span class="bw-rotator-item"><?php echo esc_html($item['text']); ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($rotator_anim === 'bloading'): ?>
                        <hr class="bw-rotator-bloading-line">
                    <?php endif; ?>

            </span>
                </<?php echo esc_html( $HTML ); ?>>
                </div>
                <?php
                break;
            case 'simple':
                ?>
            <div class="bw-title-anime bw-simple-wrap <?php echo esc_attr($data_id); ?>">
                <<?php echo esc_html( $HTML ); ?> class="bw-heading-animate">
                <span class="bw-before"><?php echo esc_html($before_text); ?></span>
                <span class="bw-simple <?php echo esc_attr($data_id); ?> bw-animate-text">
                <?php echo esc_html($main_text_text); ?>
            </span>
                <span class="bw-after"><?php echo esc_html($after_text); ?></span>
                </<?php echo esc_html( $HTML ); ?>>
                </div>
                <?php
                break;
            case 'classic':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-title-anime bw-classic bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-text-wrapper">
            <span class="bw-line bw-line1"></span>
            <span class="bw-letters bw-before"><?php echo esc_html($before_text); ?></span>
            <span class="bw-letters bw-symbol"><?php echo esc_html($symbol); ?></span>
            <span class="bw-letters bw-after"><?php echo esc_html($after_text); ?></span>
            <span class="bw-line bw-line2"></span>
        </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'liner':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-liner bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-text-wrapper">
            <span class="bw-letters bw-animate-text"><?php echo esc_html($liner); ?></span>
            <span class="bw-line"></span>
        </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'effective':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-effective bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-letters bw-letters-1 bw-animate-text"><?php echo esc_html($la); ?></span>
                <span class="bw-letters bw-letters-2 bw-animate-text"><?php echo esc_html($lb); ?></span>
                <span class="bw-letters bw-letters-3 bw-animate-text"><?php echo esc_html($lc); ?></span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'typing':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-typing bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-text-wrapper">
            <span class="bw-line bw-line1"></span>
            <span class="bw-letters bw-animate-text"><?php echo esc_html($typetext); ?></span>
            </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'fft': // Fade From Top
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-fft bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-text-wrapper">
            <span class="bw-before"><?php echo esc_html($before_text); ?></span>
            <span class="bw-letters bw-animate-text"><?php echo esc_html($fft); ?></span>
            <span class="bw-after"><?php echo esc_html($after_text); ?></span>
            </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'ffb': // Fade From Bottom
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-ffb bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-text-wrapper">
            <span class="bw-before"><?php echo esc_html($before_text); ?></span>
            <span class="bw-letters bw-animate-text"><?php echo esc_html($ffb); ?></span>
            <span class="bw-after"><?php echo esc_html($after_text); ?></span>
            </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'ffb2': // Fade From Bottom 2
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-fade-text <?php echo esc_attr($data_id); ?>">
                <span class="bw-animate-text"><?php echo esc_html($ffb2); ?></span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'ffl': // Fade From Left
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-ffl bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-text-wrapper">
            <span class="bw-before"><?php echo esc_html($before_text); ?></span>
            <span class="bw-letters bw-animate-text"><?php echo esc_html($ffl); ?></span>
            <span class="bw-after"><?php echo esc_html($after_text); ?></span>
            </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'ffr': // Fade From Right
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-ffr bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-text-wrapper">
            <span class="bw-before"><?php echo esc_html($before_text); ?></span>
            <span class="bw-letters bw-animate-text"><?php echo esc_html($ffr); ?></span>
            <span class="bw-after"><?php echo esc_html($after_text); ?></span>
            </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'fade_in':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-fin bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-word bw-animate-text"><?php echo esc_html($fadein1); ?></span>
                <span class="bw-word bw-animate-text"><?php echo esc_html($fadein2); ?></span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'fade_out':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-fout bw-heading-animate <?php echo esc_attr($data_id); ?>">
                <span class="bw-word bw-animate-text"><?php echo esc_html($fadeout1); ?></span>
                <span class="bw-word bw-animate-text"><?php echo esc_html($fadeout2); ?></span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'glitch_one':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-glitch bw-heading-animate">
                <span class="bw-glitch bw-animate-text" data-text="<?php echo esc_attr($glitch); ?>">
            <?php echo esc_html($glitch); ?>
            </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'glitch_two':
                ?>
                <div class="bw-glitch-wrapper">
                <<?php echo esc_html( $HTML ); ?> class="bw-glitch bw-animate-text" data-text="<?php echo esc_attr($glitch); ?>">
                <?php echo esc_html($glitch); ?>
                </<?php echo esc_html( $HTML ); ?>>
                </div>
                <?php
                break;
            default: // Glitch Two is default
                ?>
                <div class="bw-glitch-wrapper">
                <<?php echo esc_html( $HTML ); ?> class="bw-glitch bw-heading-animate bw-animate-text" data-text="<?php echo esc_attr($glitch); ?>">
                <?php echo esc_html($glitch); ?>
                </<?php echo esc_html( $HTML ); ?>>
                </div>
                <?php
                break;
        }
        echo '</div>';

    }

}

class_alias('Modernaweb\BlackWidgets\Widgets\TitleAnimate', 'Black_Widgets\BLACK_WIDGETS_Title_Animate');
