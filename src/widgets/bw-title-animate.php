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
 * Title animate widget.
 *
 * Title with animated text effects (fade, rotate, SVG and rotator types).
 *
 * @since 1.0.0
 */
class TitleAnimate extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-title-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/title-animate.css', [], $this->title_animate_asset_ver( 'assets/css/title-animate.css' ) );
        wp_register_script( 'black-widgets-title-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/title-animate.js', [ 'jquery', 'black-widgets-anime', 'bw-public' ], $this->title_animate_asset_ver( 'assets/js/title-animate.js' ), true );
    }

    /**
     * Get widget name.
     *
     * Retrieve the title animate widget name.
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
     * Retrieve the title animate widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Black Title Animate', 'blackwidgets' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve the title animate widget icon.
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
     * Retrieve the list of categories the title animate widget belongs to.
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
     * Cache-bust CSS/JS when the file changes (same plugin version).
     *
     * @param string $rel Path relative to the plugin root.
     * @return string
     */
    private function title_animate_asset_ver( $rel ) {
        $ver  = BLACK_WIDGETS_VERSION;
        $path = BLACK_WIDGETS_PLUGIN_PATH . ltrim( $rel, '/\\' );
        if ( is_readable( $path ) ) {
            $ver .= '.' . (int) filemtime( $path );
        }
        return $ver;
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
     * Register the title animate widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

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
                    '%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-title-animate/" target="_blank">%s</a>',
                    esc_html__( 'Check ', 'blackwidgets' ),
                    esc_html__( 'Demo', 'blackwidgets' )
                ),
            ]
        );

        // Select type of the title
        $this->add_control(
            'widget_type',
            [
                'label' => esc_html__( 'Select Type', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'simple',
                'options' => [
                    'simple' 		=> esc_html__( 'Simple', 'blackwidgets' ),
                    'classic' 		=> esc_html__( 'Classic', 'blackwidgets' ),
                    'liner'			=> esc_html__( 'Liner', 'blackwidgets' ),
                    'effective' 	=> esc_html__( 'Effective', 'blackwidgets' ),
                    'typing' 		=> esc_html__( 'Typing', 'blackwidgets' ),
                    'fft' 			=> esc_html__( 'Fade From Top', 'blackwidgets' ),
                    'ffl' 			=> esc_html__( 'Fade From Left', 'blackwidgets' ),
                    'ffr' 			=> esc_html__( 'Fade From Right', 'blackwidgets' ),
                    'ffb' 			=> esc_html__( 'Fade From Bottom', 'blackwidgets' ),
                    'fade_in' 		=> esc_html__( 'Fade In', 'blackwidgets' ),
                    'fade_out' 		=> esc_html__( 'Fade Out', 'blackwidgets' ),
                    'glitch_one' 	=> esc_html__( 'Glitch 1', 'blackwidgets' ),
                    'glitch_two' 	=> esc_html__( 'Glitch 2', 'blackwidgets' ),
                    'ffb2' 			=> esc_html__( 'Fade From Bottom2', 'blackwidgets' ),
                    'rotator' 		=> esc_html__( 'Rotator', 'blackwidgets' ),
                    'svg' 		    => esc_html__( 'SVG', 'blackwidgets' ),
                ],
                'description' => esc_html__( 'We create some skin before, you can use these or no! make a new custom type.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'widget_rotator_animation',
            [
                'label' => esc_html__( 'Select Animation', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'clip',
                'options' => [
                    'clip' 	    	=> esc_html__( 'Clip', 'blackwidgets' ),
                    'frotate' 		=> esc_html__( 'Flip Rotate', 'blackwidgets' ),
                    'lfin' 		    => esc_html__( 'Latter FadeIn', 'blackwidgets' ),
                    'lrotate' 		=> esc_html__( 'Latter Rotate', 'blackwidgets' ),
                    'tlatter' 		=> esc_html__( 'Typing Latter', 'blackwidgets' ),
                    'bloading' 		=> esc_html__( 'Bar Loading', 'blackwidgets' ),
                    'stop' 	        => esc_html__( 'Slide Top', 'blackwidgets' ),
                    'zout' 	        => esc_html__( 'Zoom Out', 'blackwidgets' ),
                    'sin' 	        => esc_html__( 'Scale In', 'blackwidgets' ),
                    'pleft' 	    => esc_html__( 'Push Left', 'blackwidgets' ),
                    'ceffect' 	    => esc_html__( 'Color Effect', 'blackwidgets' ),
                    'beffect' 	    => esc_html__( 'Bouncing Effect', 'blackwidgets' ),
                ],
                'condition' => [
                    'widget_type' => 'rotator',
                ],
            ]
        );

        $this->add_control(
            'widget_rotator_duration',
            [
                'label' => esc_html__( 'Duration (ms)', 'blackwidgets' ),
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
                'label' => esc_html__( 'Delay (ms)', 'blackwidgets' ),
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
                'label' => esc_html__( 'Rotator Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Hello Goodbye', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Animation List', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'text',
                        'label' => esc_html__( 'Text', 'blackwidgets' ),
                        'type' => \Elementor\Controls_Manager::TEXT,
                    ],
                ],
                'default' => [
                    [
                        'text' => esc_html__( 'World', 'blackwidgets' ),
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
                'label' => esc_html__( 'Select SVG', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'vector1',
                'options' => [
                    'vector1' => esc_html__( 'Vector 1', 'blackwidgets' ),
                    'vector2' => esc_html__( 'Vector 2', 'blackwidgets' ),
                    'vector3' => esc_html__( 'Vector 3', 'blackwidgets' ),
                    'vector4' => esc_html__( 'Vector 4', 'blackwidgets' ),
                    'vector5' => esc_html__( 'Vector 5', 'blackwidgets' ),
                    'vector6' => esc_html__( 'Vector 6', 'blackwidgets' ),
                    'vector7' => esc_html__( 'Vector 7', 'blackwidgets' ),
                    'vector8' => esc_html__( 'Vector 8', 'blackwidgets' ),
                    'vector9' => esc_html__( 'Vector 9', 'blackwidgets' ),
                ],
                'condition' => [
                    'widget_type' => 'svg',
                ],
            ]
        );

        $this->add_control(
            'widget_svg_before_text',
            [
                'label' => esc_html__( 'Before Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Before', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Animation Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Content', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
                'condition'  => [
                    'widget_type' => [
                        'svg',
                    ],
                ],
            ]
        );

        $this->add_control(
            'widget_svg_after_text',
            [
                'label' => esc_html__( 'After Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Duration (ms)', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 100,
                'default' => 4000,
                'condition' => [
                    'widget_type' => 'svg',
                ],
            ]
        );

        $this->add_control(
            'widget_svg_delay',
            [
                'label' => esc_html__( 'Delay (ms)', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 100,
                'default' => 2000,
                'description' => esc_html__( 'When looping, used as hold time before the stroke resets.', 'blackwidgets' ),
                'condition' => [
                    'widget_type' => 'svg',
                ],
            ]
        );

        $this->add_control(
            'widget_svg_loop',
            [
                'label' => esc_html__( 'Loop', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'True', 'blackwidgets' ),
                'label_off' => esc_html__( 'False', 'blackwidgets' ),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'widget_type' => 'svg',
                    'widget_anim_controls!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'widget_anim_controls',
            [
                'label' => esc_html__( 'Custom Playback', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'blackwidgets' ),
                'label_off' => esc_html__( 'Off', 'blackwidgets' ),
                'return_value' => 'yes',
                'default' => '',
                'description' => esc_html__( 'Off keeps the default playback. On lets you set play once, loop, and speed.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'widget_anim_repeat',
            [
                'label' => esc_html__( 'Play Mode', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'once',
                'options' => [
                    'once'     => esc_html__( 'Play Once', 'blackwidgets' ),
                    'infinite' => esc_html__( 'Infinite Loop', 'blackwidgets' ),
                ],
                'condition' => [
                    'widget_anim_controls' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'widget_anim_speed',
            [
                'label' => esc_html__( 'Animation Speed', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0.25,
                'max' => 3,
                'step' => 0.05,
                'default' => 1,
                'description' => esc_html__( '1 = normal. Higher is faster, lower is slower.', 'blackwidgets' ),
                'condition' => [
                    'widget_anim_controls' => 'yes',
                ],
            ]
        );

        // Type title
        $this->add_control(
            'widget_title_before',
            [
                'label' => esc_html__( 'Before Title', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Before', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Main Title', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Content', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Symbol (Just Single Character) ', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '&', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Set symbol', 'blackwidgets' ),
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
                'label' => esc_html__( 'Glitch Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Glitch•', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Main Title', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Find Your Element', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Start Range From Bottom', 'blackwidgets' ),
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
                'label' => esc_html__( 'Text 1', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Set', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Text 2', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Ready', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Text 3', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Go', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Typing Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Hello Goodbye', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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
                'label' => esc_html__( 'After Title', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'After', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
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

        // Tag section
        $this->start_controls_section(
            'tag_section',
            [
                'label' => esc_html__( 'HTML Tag Setting', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Select tag
        $this->add_control(
            'widget_html_tag',
            [
                'label' => esc_html__( 'HTML Tag', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'div' 	=> esc_html__( 'div', 'blackwidgets' ),
                    'h1' 	=> esc_html__( 'H1', 'blackwidgets' ),
                    'h2' 	=> esc_html__( 'H2', 'blackwidgets' ),
                    'h3' 	=> esc_html__( 'H3', 'blackwidgets' ),
                    'h4' 	=> esc_html__( 'H4', 'blackwidgets' ),
                    'h5' 	=> esc_html__( 'H5', 'blackwidgets' ),
                    'h6' 	=> esc_html__( 'H6', 'blackwidgets' ),
                    'p' 	=> esc_html__( 'p', 'blackwidgets' ),
                    'span' 	=> esc_html__( 'span', 'blackwidgets' ),
                ],
                'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'blackwidgets' ),
                // 'selectors' => [
                // 	'{{WRAPPER}} .bw-button-wrapper' => 'text-align: {{VALUE}};',
                // ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Box Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'widget_box_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_box_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient', ],
                'selector' => '{{WRAPPER}} .bw-title-animate',
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
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_box_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate',
            ]
        );

        $this->add_control(
            'widget_box_border_radius', //param_name
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate',
            ]
        );

        $this->end_controls_section();

        // Before Text Typography
        $this->start_controls_section(
            'style_section_before_typo',
            [
                'label' => esc_html__( 'Before Text Typography', 'blackwidgets' ),
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

        $this->add_control(
            'style_before_title_color',
            [
                'label' => esc_html__( 'Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_before_title_typography1',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_before_title_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
            ]
        );

        $this->add_control(
            'hr1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'style_before_title_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
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

        $this->add_responsive_control(
            'style_before_title_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_before_title_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'style_before_title_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
            ]
        );

        $this->add_control(
            'style_before_title_border_radius', //param_name
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_before_title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section_main_text_typo',
            [
                'label' => esc_html__( 'Main Text Typography', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'  => [
                    'widget_type' => [
                        'rotator',
                        'svg',
                    ],
                ],
            ]
        );

        $this->add_control(
            'style_main_text_color',
            [
                'label' => esc_html__( 'Color', 'blackwidgets' ),
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_main_text_typography1',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text, {{WRAPPER}} .bw-title-animate .bw-rotator-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_main_text_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text, {{WRAPPER}} .bw-title-animate .bw-rotator-text',
            ]
        );

        $this->add_control(
            'hr13',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'style_main_text_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
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

        $this->add_responsive_control(
            'style_main_text_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-svg-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-title-animate .bw-rotator-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_main_text_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'style_main_text_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text',
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-rotator-text',
            ]
        );

        $this->add_control(
            'style_main_text_border_radius', //param_name
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-svg-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-title-animate .bw-rotator-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_main_text_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-svg-text, {{WRAPPER}} .bw-title-animate .bw-rotator-text',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'style_section_main_typo',
            [
                'label' => esc_html__( 'Animate Text Typography', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'  => [
                    'widget_type!' => [
                        'classic',
                    ],
                ],
            ]
        );

        $this->add_control(
            'style_main_title_color',
            [
                'label' => esc_html__( 'Color', 'blackwidgets' ),
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
                'label' => esc_html__( 'Color 1(Color Effect)', 'blackwidgets' ),
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
                'label' => esc_html__( 'Color 2(Color Effect)', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#0000ff',
                'condition' => [
                    'widget_rotator_animation' => 'ceffect',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_main_title_typography1',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_main_title_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
            ]
        );

        $this->add_control(
            'hr4',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'style_main_title_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
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

        $this->add_responsive_control(
            'style_main_title_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_main_title_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'style_main_title_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
            ]
        );

        $this->add_control(
            'style_main_title_border_radius', //param_name
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-animate-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_main_title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-animate-text',
            ]
        );

        $this->end_controls_section();

        // symbol Text Typography
        $this->start_controls_section(
            'style_section_symbol_typo',
            [
                'label' => esc_html__( 'Symbol Text Typography', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'  => [
                    'widget_type' => [
                        'classic',
                    ],
                ],
            ]
        );

        $this->add_control(
            'style_symbol_title_color',
            [
                'label' => esc_html__( 'Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-symbol' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_symbol_title_typography1',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_symbol_title_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
            ]
        );

        $this->add_control(
            'hr7',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'style_symbol_title_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
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

        $this->add_responsive_control(
            'style_symbol_title_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-symbol' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_symbol_title_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'style_symbol_title_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
            ]
        );

        $this->add_control(
            'style_symbol_title_border_radius', //param_name
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-symbol' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_symbol_title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-symbol',
            ]
        );

        $this->end_controls_section();


        // After Text Typography
        $this->start_controls_section(
            'style_section_after_typo',
            [
                'label' => esc_html__( 'After Text Typography', 'blackwidgets' ),
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

        $this->add_control(
            'style_after_title_color',
            [
                'label' => esc_html__( 'Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'style_after_title_typography1',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_after_title_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
            ]
        );

        $this->add_control(
            'hr10',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'style_after_title_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
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

        $this->add_responsive_control(
            'style_after_title_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'style_after_title_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'style_after_title_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
            ]
        );

        $this->add_control(
            'style_after_title_border_radius', //param_name
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'style_after_title_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-title-animate .bw-after',
            ]
        );

        $this->end_controls_section();

        // Line Text Typography
        $this->start_controls_section(
            'style_section_symbol_line',
            [
                'label' => esc_html__( 'Line Color (Type)', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'widget_type' => [ 'classic', 'liner', 'typing' ],
                ],
            ]
        );

        $this->add_control(
            'style_after_line_color',
            [
                'label' => esc_html__( 'Line Color', 'blackwidgets' ),
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
                'label' => esc_html__( 'Line Width', 'blackwidgets' ),
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
                'label' => esc_html__( 'Line Height', 'blackwidgets' ),
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
                'label' => esc_html__( 'Line Color (Animation)', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'widget_type' => [ 'rotator' ],
                    'widget_rotator_animation' => [ 'clip', 'tlatter' ],
                ],
            ]
        );

        $this->add_control(
            'style_after_line_color1',
            [
                'label' => esc_html__( 'Line Color', 'blackwidgets' ),
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
                'label' => esc_html__( 'Line Width', 'blackwidgets' ),
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
                'label' => esc_html__( 'Line Height', 'blackwidgets' ),
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
            'style_section_symbol_svg',
            [
                'label' => esc_html__( 'SVG Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'  => [
                    'widget_type' => [
                        'svg',
                    ],
                ],
            ]
        );

        $this->add_control(
            'style_svg_color',
            [
                'label' => esc_html__( 'SVG Color', 'blackwidgets' ),
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
                'label' => esc_html__( 'Stroke Width', 'blackwidgets' ),
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

        $this->add_responsive_control(
            'svg_translate_x',
            [
                'label' => esc_html__( 'Horizontal Offset', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200, 'step' => 1 ],
                    'em' => [ 'min' => -10, 'max' => 10, 'step' => 0.1 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 0 ],
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-svg-wrapper' => '--bw-svg-offset-x: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'svg_translate_y',
            [
                'label' => esc_html__( 'Vertical Offset', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => -200, 'max' => 200, 'step' => 1 ],
                    'em' => [ 'min' => -10, 'max' => 10, 'step' => 0.1 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 0 ],
                'description' => esc_html__( 'Positive values push the SVG lower than the text, negative values pull it up. 40px moves it 40px down.', 'blackwidgets' ),
                'selectors' => [
                    '{{WRAPPER}} .bw-title-animate .bw-svg-wrapper' => '--bw-svg-offset-y: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'svg_viewbox_heading',
            [
                'label' => esc_html__( 'View Box (advanced)', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'svg_viewbox_width',
            [
                'label' => esc_html__( 'View Box Width', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 1,
            ]
        );

        $this->add_control(
            'svg_viewbox_height',
            [
                'label' => esc_html__( 'View Box Height', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 1,
            ]
        );

        $this->add_control(
            'svg_viewbox_min_x',
            [
                'label' => esc_html__( 'View Box Min-X', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 0.01,
            ]
        );

        $this->add_control(
            'svg_viewbox_min_y',
            [
                'label' => esc_html__( 'View Box Min-Y', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'step' => 0.01,
            ]
        );

        $this->end_controls_section();
    }

    private function resolve_elementor_color( $color_value ) {
        if ( empty( $color_value ) ) {
            return '';
        }

        if ( strpos( $color_value, 'var(' ) !== 0 ) {
            return $color_value;
        }

        // Prefer a real Elementor API if it exists; otherwise keep the CSS variable (valid in gradients).
        if ( class_exists( '\Elementor\Utils' )
            && method_exists( '\Elementor\Utils', 'get_global_color' ) ) {
            return \Elementor\Utils::get_global_color( $color_value );
        }

        return $color_value;
    }

    /**
     * Render the title animate widget output on the frontend.
     *
     * Outputs the widget HTML.
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
        if ( is_array( $alignment ) ) {
            $alignment = '';
        }
        if ( ! in_array( $alignment, [ 'left', 'center', 'right' ], true ) ) {
            $alignment = '';
        }

        // Rotator related
        $rotator_text = isset( $settings['widget_rotator_text'] ) ? esc_html( $settings['widget_rotator_text'] ) : '';
        $rotator_items = isset( $settings['widget_rotator_items'] ) ? $settings['widget_rotator_items'] : [];
        $rotator_anim = isset( $settings['widget_rotator_animation'] ) ? esc_attr( $settings['widget_rotator_animation'] ) : 'clip';
        $rotator_duration = isset( $settings['widget_rotator_duration'] ) ? esc_attr( $settings['widget_rotator_duration'] ) : '700';
        $rotator_delay = isset( $settings['widget_rotator_delay'] ) ? esc_attr( $settings['widget_rotator_delay'] ) : '1000';
        $title_color1 = isset( $settings['style_main_title_color1'] ) ? esc_attr( $this->resolve_elementor_color($settings['style_main_title_color1']) ) : '#ff5b5b';
        $title_color2 = isset( $settings['style_main_title_color2'] ) ? esc_attr( $this->resolve_elementor_color($settings['style_main_title_color2']) ) : 'blue';

        $svg_known = [ 'vector1', 'vector2', 'vector3', 'vector4', 'vector5', 'vector6', 'vector7', 'vector8', 'vector9' ];
        $svg = isset( $settings['widget_svg'] ) ? (string) $settings['widget_svg'] : 'vector1';
        if ( ! in_array( $svg, $svg_known, true ) ) {
            $svg = 'vector1';
        }
        $svg = esc_attr( $svg );
        $svg_before_text = isset( $settings['widget_svg_before_text'] ) ? esc_html( $settings['widget_svg_before_text'] ) : '';
        $svg_after_text = isset( $settings['widget_svg_after_text'] ) ? esc_html( $settings['widget_svg_after_text'] ) : '';
        $svg_anim_text = isset( $settings['widget_svg_animation_text'] ) ? esc_html( $settings['widget_svg_animation_text'] ) : '';
        $svg_duration = isset( $settings['widget_svg_duration'] ) ? esc_attr( $settings['widget_svg_duration'] ) : '4000';
        $svg_delay = isset( $settings['widget_svg_delay'] ) ? esc_attr( $settings['widget_svg_delay'] ) : '2000';

        // Custom Playback is opt-in. SVG loop defaults OFF; other types keep legacy loop ON.
        $anim_controls = isset( $settings['widget_anim_controls'] ) ? $settings['widget_anim_controls'] : '';
        $widget_type_for_loop = isset( $settings['widget_type'] ) ? $settings['widget_type'] : '';
        if ( $anim_controls === 'yes' ) {
            $anim_repeat = isset( $settings['widget_anim_repeat'] ) ? $settings['widget_anim_repeat'] : 'once';
            $loop = ( $anim_repeat === 'infinite' ) ? 'yes' : 'no';
            $anim_speed = isset( $settings['widget_anim_speed'] ) ? floatval( $settings['widget_anim_speed'] ) : 1;
            if ( is_array( $settings['widget_anim_speed'] ?? null ) && isset( $settings['widget_anim_speed']['size'] ) ) {
                $anim_speed = floatval( $settings['widget_anim_speed']['size'] );
            }
            if ( $anim_speed <= 0 ) {
                $anim_speed = 1;
            }
            $anim_speed = max( 0.25, min( 3, $anim_speed ) );
        } elseif ( $widget_type_for_loop === 'svg' ) {
            $loop = ( ( $settings['widget_svg_loop'] ?? '' ) === 'yes' ) ? 'yes' : 'no';
            $anim_speed = 1;
        } else {
            // Legacy non-SVG: loop on when Custom Playback is off.
            $loop = 'yes';
            $anim_speed = 1;
        }
        $anim_speed_attr = esc_attr( (string) $anim_speed );

        $min_x  = isset( $settings['svg_viewbox_min_x'] ) ? $settings['svg_viewbox_min_x'] : '';
        $min_y  = isset( $settings['svg_viewbox_min_y'] ) ? $settings['svg_viewbox_min_y'] : '';
        $vb_w   = isset( $settings['svg_viewbox_width'] ) ? $settings['svg_viewbox_width'] : '';
        $vb_h   = isset( $settings['svg_viewbox_height'] ) ? $settings['svg_viewbox_height'] : '';

        if ( empty($min_x) && empty($vb_w) ) {
            switch ( $svg ) {
                case 'vector1':
                    $min_x = 0; $min_y = 0; $vb_w = 223; $vb_h = 6;
                    break;
                case 'vector2':
                    $min_x = 0; $min_y = 0; $vb_w = 223; $vb_h = 31;
                    break;
                case 'vector3':
                    $min_x = 0; $min_y = 0; $vb_w = 223; $vb_h = 17;
                    break;
                case 'vector4':
                    $min_x = 0; $min_y = 0; $vb_w = 195; $vb_h = 17;
                    break;
                case 'vector5':
                    $min_x = 0; $min_y = 0; $vb_w = 223; $vb_h = 14;
                    break;
                case 'vector6':
                    $min_x = 0; $min_y = 0; $vb_w = 191; $vb_h = 39;
                    break;
                case 'vector7':
                    $min_x = 0; $min_y = 0; $vb_w = 223; $vb_h = 43;
                    break;
                case 'vector8':
                    $min_x = 0; $min_y = 0; $vb_w = 214; $vb_h = 29;
                    break;
                case 'vector9':
                    $min_x = 0; $min_y = 0; $vb_w = 217; $vb_h = 22;
                    break;
                default:
                    $min_x = 0; $min_y = 0; $vb_w = 223; $vb_h = 6;
                    break;
            }
        }

        $viewBox = "{$min_x} {$min_y} {$vb_w} {$vb_h}";

        // Horizontal/vertical offsets are applied through the --bw-svg-offset-* custom
        // properties (see the svg_translate_* controls) so they survive the animation
        // reset, which strips inline transforms from every node inside the widget.

        $data_id                = 'bw-' . uniqid();

        // wp_kses is slow here, so the HTML tag is validated manually.
        if ( ! in_array( $HTML, ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'] ) ) {
            $HTML = 'h1';
        }
        ?>
        <?php
        echo '<div class="bw-title-animate ' . esc_attr( $alignment ) . '">';
        switch ($type) {
            case 'svg':
                $svg_paths = [
                    'vector1' => 'M1.25 3.65841C31.25 0.658408 51.25 6.65841 81.25 2.65841C111.25 -1.34159 131.25 6.65841 161.25 2.65841C191.25 -1.34159 209.25 4.65841 221.25 2.65841',
                    'vector2' => 'M1.25 29.7252C1.25 11.7252 9.25 -0.274806 19.25 5.72519C29.25 11.7252 31.25 27.7252 43.25 19.7252C55.25 11.7252 63.25 -0.274806 73.25 5.72519C83.25 11.7252 85.25 27.7252 99.25 19.7252C141.25 3.72519 186.25 -0.274807 221.25 1.72519',
                    'vector3' => 'M1.25 15.25C21.25 13.25 29.25 1.25 37.25 1.25C45.25 1.25 45.25 13.25 51.25 13.25C71.25 13.25 79.25 1.25 87.25 1.25C95.25 1.25 95.25 13.25 103.25 13.25C123.25 13.25 131.25 1.25 139.25 1.25C147.25 1.25 147.25 13.25 153.25 13.25C173.25 13.25 187.25 13.25 221.25 11.25',
                    'vector4' => 'M1.25049 8.25C33.2505 -1.08333 65.2505 -1.08333 97.2505 8.25C129.25 17.5833 161.25 17.5833 193.25 8.25',
                    'vector5' => 'M1.25 3.25C51.25 1.25 81.25 3.25 101.25 7.25C121.25 11.25 131.25 15.25 151.25 9.25C171.25 3.25 196.25 1.25 221.25 1.25',
                    'vector6' => 'M95.5 1.5C160.5 1.5 189.5 13.5 189.5 19.5C189.5 25.5 160.5 37.5 95.5 37.5C30.5 37.5 1.5 25.5 1.5 19.5C1.5 13.5 30.5 1.5 95.5 1.5Z',
                    'vector7' => 'M1.25024 16.6104C51.2502 8.61036 111.25 6.61036 146.25 20.6104C165.25 28.6104 161.25 42.6104 135.25 40.6104C101.25 38.6104 63.2502 28.6104 71.2502 12.6104C83.2502 -5.38964 151.25 0.610361 221.25 12.6104',
                    'vector8' => 'M2.3445 9.63819C4.3445 1.63819 12.3445 -0.361807 16.3445 7.63819C20.3445 15.6382 14.3445 19.6382 6.3445 15.6382C-1.6555 11.6382 0.344505 3.63819 8.3445 1.63819C16.3445 -0.361807 24.3445 5.63819 26.3445 15.6382C28.3445 25.6382 22.3445 29.6382 16.3445 25.6382C32.3445 19.6382 82.3445 11.6382 212.344 9.63819',
                    'vector9' => 'M14.7857 12.5317C12.7857 2.53172 6.78574 -1.46828 2.78574 4.53172C-1.21426 10.5317 2.78574 20.5317 12.7857 20.5317C22.7857 20.5317 28.7857 10.5317 24.7857 4.53172C20.7857 -1.46828 12.7857 0.531721 12.7857 12.5317C14.7857 20.5317 64.7857 14.5317 214.786 10.5317',
                ];
                $svg_path_d = isset( $svg_paths[ $svg ] ) ? $svg_paths[ $svg ] : $svg_paths['vector1'];
                ?>
            <div class="bw-title-anime bw-svg bw-svg-<?php echo $svg; ?> <?php echo $data_id; ?>" data-duration="<?php echo $svg_duration; ?>" data-delay="<?php echo $svg_delay; ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>"> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <<?php echo $HTML; ?> class="bw-text-wrapper"> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <span class="bw-svg-text"><?php echo $svg_before_text; ?></span> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <span class="bw-svg-wrapper bw-animate-text">
                    <span class="bw-svg-content"><?php echo $svg_anim_text; ?></span> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    <svg
                        viewBox="<?php echo esc_attr( $viewBox ); ?>"
                        preserveAspectRatio="none"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="<?php echo esc_attr( $svg_path_d ); ?>"/>
                    </svg>
                    </span>
                    <span class="bw-svg-text"><?php echo $svg_after_text; ?></span> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
                 data-delay="<?php echo esc_attr($rotator_delay); ?>"
                 data-loop="<?php echo $loop; ?>"
                 data-speed="<?php echo $anim_speed_attr; ?>"
                >

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
                <div class="bw-title-anime bw-simple-wrap <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
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
                    <<?php echo esc_html( $HTML ); ?> class="bw-title-anime bw-classic bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
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
                <<?php echo esc_html( $HTML ); ?> class="bw-liner bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
                <span class="bw-text-wrapper">
            <span class="bw-letters bw-animate-text"><?php echo esc_html($liner); ?></span>
            <span class="bw-line"></span>
        </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'effective':
                ?>
                    <<?php echo esc_html( $HTML ); ?> class="bw-effective bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
                <span class="bw-letters bw-letters-1 bw-animate-text"><?php echo esc_html($la); ?></span>
                <span class="bw-letters bw-letters-2 bw-animate-text"><?php echo esc_html($lb); ?></span>
                <span class="bw-letters bw-letters-3 bw-animate-text"><?php echo esc_html($lc); ?></span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'typing':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-typing bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
                <span class="bw-text-wrapper">
            <span class="bw-line bw-line1"></span>
            <span class="bw-letters bw-animate-text"><?php echo esc_html($typetext); ?></span>
            </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'fft': // Fade From Top
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-fft bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
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
                <<?php echo esc_html( $HTML ); ?> class="bw-ffb bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
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
                <<?php echo esc_html( $HTML ); ?> class="bw-ffl bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
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
                <<?php echo esc_html( $HTML ); ?> class="bw-ffr bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
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
                <<?php echo esc_html( $HTML ); ?> class="bw-fin bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
                <span class="bw-word bw-animate-text"><?php echo esc_html($fadein1); ?></span>
                <span class="bw-word bw-animate-text"><?php echo esc_html($fadein2); ?></span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'fade_out':
                ?>
                <<?php echo esc_html( $HTML ); ?> class="bw-fout bw-heading-animate <?php echo esc_attr($data_id); ?>" data-loop="<?php echo $loop; ?>" data-speed="<?php echo $anim_speed_attr; ?>">
                <span class="bw-word bw-animate-text"><?php echo esc_html($fadeout1); ?></span>
                <span class="bw-word bw-animate-text"><?php echo esc_html($fadeout2); ?></span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'glitch_one':
                ?>
                    <<?php echo esc_html( $HTML ); ?> class="bw-glitch bw-heading-animate <?php echo $loop === 'yes' ? '' : 'bw-no-loop' ?>" style="--bw-anim-speed: <?php echo $anim_speed_attr; ?>">
                <span class="bw-glitch bw-animate-text" data-text="<?php echo esc_attr($glitch); ?>">
            <?php echo esc_html($glitch); ?>
            </span>
                </<?php echo esc_html( $HTML ); ?>>
                <?php
                break;
            case 'glitch_two':
                ?>
                <div class="bw-glitch-wrapper">
                <<?php echo esc_html( $HTML ); ?> class="bw-glitch bw-animate-text <?php echo $loop === 'yes' ? '' : 'bw-no-loop' ?>" style="--bw-anim-speed: <?php echo $anim_speed_attr; ?>" data-text="<?php echo esc_attr($glitch); ?>">
                <?php echo esc_html($glitch); ?>
                </<?php echo esc_html( $HTML ); ?>>
                </div>
                <?php
                break;
            default: // Glitch Two is default
                ?>
                <div class="bw-glitch-wrapper">
                <<?php echo esc_html( $HTML ); ?> class="bw-glitch bw-heading-animate bw-animate-text <?php echo $loop === 'yes' ? '' : 'bw-no-loop' ?>" style="--bw-anim-speed: <?php echo $anim_speed_attr; ?>" data-text="<?php echo esc_attr($glitch); ?>">
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