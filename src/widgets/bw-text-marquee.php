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

class TextMarquee extends \Elementor\Widget_Base {
    /**
     * Constructor.
     *
     * Registers styles and scripts required for the Text Marquee widget.
     *
     * @since 1.4.0
     *
     * @param array $data Optional. Widget data. Default empty array.
     * @param mixed $args Optional. Additional arguments. Default null.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        // Register widget stylesheet.
        wp_register_style(
            'black-widgets-text-marquee',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/css/text-marquee.css',
            [],
            BLACK_WIDGETS_VERSION
        );

        // GSAP is added conditionally in get_script_depends() when Move on Scroll is on.
        wp_register_script(
            'black-widgets-text-marquee',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/text-marquee.js',
            [ 'jquery' ],
            BLACK_WIDGETS_VERSION,
            true
        );
    }

    /**
     * Get widget name.
     *
     * Returns the unique name of the widget used internally by Elementor.
     *
     * @since 1.4.0
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'b_text_marquee';
    }

    /**
     * Get widget title.
     *
     * Returns the display title for the widget shown in the Elementor editor.
     *
     * @since 1.4.0
     *
     * @return string Translatable widget title.
     */
    public function get_title() {
        return __( 'Black Text Marquee', 'blackwidgets' );
    }

    /**
     * Get widget icon.
     *
     * Returns the Elementor icon class for displaying in the widget list.
     *
     * @since 1.4.0
     *
     * @return string Elementor icon class name.
     */
    public function get_icon() {
        return 'eicon-font';
    }

    /**
     * Get widget categories.
     *
     * Specifies the custom categories the widget belongs to in Elementor.
     *
     * @since 1.4.0
     *
     * @return array List of category slugs.
     */
    public function get_categories() {
        return [ 'black_widgets' ];
    }

    /**
     * Get style dependencies.
     *
     * Registers styles to be enqueued when the widget is used on the frontend.
     *
     * @since 1.4.0
     *
     * @return array List of style handles.
     */
    public function get_style_depends() {
        return [ 'black-widgets-text-marquee' ];
    }

    /**
     * Get script dependencies.
     *
     * Registers scripts to be enqueued when the widget is used on the frontend.
     *
     * @since 1.4.0
     *
     * @return array List of script handles.
     */
    public function get_script_depends() {
        $deps = [ 'black-widgets-text-marquee' ];

        // Avoid reading instance settings during early Elementor enqueue.
        if ( $this->is_gsap_enabled() ) {
            $deps[] = 'GSAP';
            $deps[] = 'GSAP-ScrollTrigger';
        }

        return $deps;
    }

    /**
     * Raw settings safe when Elementor data is not initialized yet.
     *
     * @return array
     */
    protected function get_early_settings(): array {
        return black_widgets_elementor_raw_settings( $this );
    }

    /**
     * Check if widget uses dynamic content.
     *
     * Indicates whether this widget supports dynamic content or not.
     *
     * @since 1.4.0
     *
     * @return bool False since the widget only displays static content.
     */
    protected function is_dynamic_content(): bool {
        return false;
    }

    /**
     * Check if GSAP is enabled based on plugin options.
     *
     * Retrieves the plugin options from the database, then verifies
     * if the GSAP options and both CDN URLs are set and not empty.
     *
     * @since 1.4.0
     *
     * @return bool True if GSAP is enabled, false otherwise.
     */
    public function is_gsap_enabled() {
        return \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_ready();
    }

    /**
     * Register widget controls by calling settings and style control registration methods.
     *
     * @since 1.4.0
     * @return void
     */
    protected function register_controls() {
        $this->register_settings_controls();
        $this->register_style_controls();
    }

    /**
     * Register the content-related settings controls for the widget.
     *
     * @since 1.4.0
     * @return void
     */
    private function register_settings_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'widget_text',
            [
                'label' => 'Text',
                'type' => Controls_Manager::TEXT,
                'rows' => 10,
                'default' => esc_html__( 'Black Widget Text Marquee', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your text here', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'hr1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'widget_type',
            [
                'label' => esc_html__( 'Type', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'type1',
                'options' => [
                    'type1'   =>  esc_html__( 'Type 1', 'blackwidgets' ),
                    'type2'   =>  esc_html__( 'Type 2', 'blackwidgets' ),
                ]
            ]
        );

        $this->add_control(
            'widget_direction',
            [
                'label' => esc_html__( 'Direction', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__( 'Left To Right', 'blackwidgets' ),
                    'right' => esc_html__( 'Right To Left', 'blackwidgets' ),
                ],
            ]
        );

        $this->add_control(
            'widget_speed',
            [
                'label' => esc_html__( 'Speed', 'blackwidgets' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 1,
                'max' => 1000,
            ]
        );

        $this->add_control(
            'widget_gap',
            [
                'label' => esc_html__( 'GAP (px)', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'description' => esc_html__( 'Space between repeating text units.', 'blackwidgets' ),
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee-wrapper' => '--bw-marquee-gap: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'widget_pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'blackwidgets'),
                'label_off' => esc_html__('No', 'blackwidgets'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );


        $this->add_control(
            'hr7',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        if ( \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_toggle_on() ) {

            $this->add_control(
                'widget_mos',
                [
                    'label' => esc_html__( 'Move on Scroll', 'blackwidgets' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'On', 'blackwidgets' ),
                    'label_off' => esc_html__( 'Off', 'blackwidgets' ),
                    'return_value' => 'yes',
                    'default' => '',
                    'condition' => [
                        'widget_type' => 'type2',
                    ],
                ]
            );

            $this->add_control(
                'widget_gsap_speed',
                [
                    'label' => esc_html__( 'Speed Scroll', 'blackwidgets' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 2,
                    'condition' => [
                        'widget_type' => 'type2',
                        'widget_mos' => 'yes',
                    ],
                ]
            );

//            $this->add_control(
//                'widget_gsap_start',
//                [
//                    'label' => esc_html__( 'GSAP start condition', 'blackwidgets' ),
//                    'type' => \Elementor\Controls_Manager::TEXT,
//                    'default' => 'top center',
//                    'condition' => [
//                        'widget_type' => 'type2',
//                        'widget_mos' => 'yes',
//                    ],
//                ]
//            );
//
//            $this->add_control(
//                'widget_gsap_end',
//                [
//                    'label' => __('GSAP End Condition', 'blackwidgets'),
//                    'type' => \Elementor\Controls_Manager::TEXT,
//                    'default' => 'bottom center',
//                    'condition' => [
//                        'widget_type' => 'type2',
//                        'widget_mos' => 'yes',
//                    ],
//                ]
//            );
        }

        $this->end_controls_section();
    }

    /**
     * Register all style controls including box and content styles.
     *
     * @since 1.4.0
     * @return void
     */
    private function register_style_controls() {
        $this->register_style_box_controls();
        $this->register_style_content_controls();
    }

    /**
     * Register style controls related to the box wrapper.
     *
     * @since 1.4.0
     * @return void
     */
    private function register_style_box_controls() {
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Box Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .bw-text-marquee-wrapper',
            ]
        );

        $this->add_control(
            'hr2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'widget_box_width',
            [
                'label' => esc_html__( 'Width', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                    'em' => [ 'min' => 0, 'max' => 100 ],
                    'vw' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'label_block' => true,
            ]
        );


        $this->add_responsive_control(
            'widget_box_height',
            [
                'label' => esc_html__( 'Height', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'vw' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 2000 ],
                    '%' => [ 'min' => 0, 'max' => 100 ],
                    'em' => [ 'min' => 0, 'max' => 100 ],
                    'vw' => [ 'min' => 0, 'max' => 100 ],
                ],
                // Empty / auto by default - fixed height clips large typography.
                'description' => esc_html__( 'Leave empty for auto height. A fixed value can clip text.', 'blackwidgets' ),
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'widget_box_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .bw-text-marquee-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'name' => 'widget_box_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-text-marquee-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-text-marquee-wrapper',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls related to the text content and typography.
     *
     * @since 1.4.0
     * @return void
     */
    private function register_style_content_controls() {
        $this->start_controls_section(
            'typography_section',
            [
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widget_typography_text_color',
            [
                'label' => esc_html__( 'Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'widget_typography_typography',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-text-marquee-content',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'widget_typography_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-text-marquee-content',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the marquee widget output.
     *
     * Retrieves widget settings, sanitizes content, and outputs
     * the marquee HTML with data attributes for frontend behavior.
     *
     * Supports GSAP animation, pause on hover, and configurable direction,
     * speed, gap, and display modes.
     *
     * @since 1.4.0
     *
     * @return void Outputs the widget HTML.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $direction       = sanitize_text_field( $settings['widget_direction'] ?? '' );
        $speed           = (int) ( $settings['widget_speed'] ?? 20 );
        if ( $speed <= 0 ) {
            $speed = 20;
        }
        $pause           = ( $settings['widget_pause_on_hover'] ?? '' ) === 'yes' ? 'pause-on-hover' : '';
        $mode            = sanitize_text_field( $settings['widget_type'] ?? 'type1' );
        $gap             = (int) ( $settings['widget_gap'] ?? 0 );
        $scrollControlled = false;
        $gsap_start      = 'top bottom';
        $gsap_end        = 'bottom top';
        $speedScroll        = 2;

        if ( $this->is_gsap_enabled() ) {
            $scrollControlled = ( $settings['widget_mos'] ?? '' ) === 'yes';
            $speedScroll      = sanitize_text_field( $settings['widget_gsap_speed'] ?? $speedScroll );
        }

        $inner_class = ( $mode === 'type1' ) ? 'bw-marquee-inner-single' : 'bw-marquee-inner';

        $allowed_tags = [
            'a'      => [ 'href' => [], 'target' => [], 'rel' => [], 'style' => [] ],
            'strong' => [],
            'em'     => [],
            'span'   => [ 'style' => [] ],
            'b'      => [],
            'i'      => [],
            'u'      => [],
            'br'     => [],
        ];

        ?>
        <div class="bw-text-marquee-wrapper <?php echo esc_attr( $pause ); ?>"
             data-direction="<?php echo esc_attr( $direction ); ?>"
             data-speed="<?php echo esc_attr( $speed ); ?>"
             data-gap="<?php echo esc_attr( $gap ); ?>"
             data-scroll-controlled="<?php echo esc_attr( $scrollControlled ? 'yes' : 'no' ); ?>"
             data-gsap-start="<?php echo esc_attr( $gsap_start ); ?>"
             data-gsap-end="<?php echo esc_attr( $gsap_end ); ?>"
             data-speed-scroll="<?php echo esc_attr( $speedScroll ); ?>"
             style="--bw-marquee-gap: <?php echo esc_attr( (string) $gap ); ?>px;"
        >
            <div class="bw-text-marquee-content <?php echo esc_attr( $inner_class ); ?>">
                <div class="bw-text-marquee-text<?php echo ( $mode === 'type2' ) ? ' bw-marquee-template' : ''; ?>">
                    <?php echo wp_kses( $settings['widget_text'] ?? '', $allowed_tags ); ?>
                </div>
            </div>
        </div>
        <?php
    }
}
