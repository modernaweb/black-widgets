<?php
namespace Modernaweb\BlackWidgets\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

/**
 * Class ImageMarquee
 *
 * Custom Elementor widget for displaying an animated image marquee.
 *
 * @since 1.4.0
 */
class ImageMarquee extends \Elementor\Widget_Base {

    /**
     * Constructor for the widget.
     *
     * Registers the widget styles and scripts, and includes GSAP dependencies if enabled.
     *
     * @since 1.4.0
     *
     * @param array $data Widget data.
     * @param array|null $args Optional arguments.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'black-widgets-image-marquee',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/css/image-marquee.css',
            [],
            BLACK_WIDGETS_VERSION
        );

        // GSAP is added conditionally in get_script_depends() when Move on Scroll is on.
        wp_register_script(
            'black-widgets-image-marquee',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/image-marquee.js',
            [ 'jquery' ],
            BLACK_WIDGETS_VERSION,
            true
        );
    }

    /**
     * Get widget name.
     *
     * Returns the unique widget identifier.
     *
     * @since 1.4.0
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'b_image_marquee';
    }

    /**
     * Get widget title.
     *
     * Returns the widget title to be displayed in Elementor editor.
     *
     * @since 1.4.0
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Black Image Marquee', 'blackwidgets' );
    }

    /**
     * Get widget icon.
     *
     * Returns the icon for the widget in the editor.
     *
     * @since 1.4.0
     *
     * @return string Icon class name.
     */
    public function get_icon() {
        return 'eicon-image-before-after';
    }

    /**
     * Get widget categories.
     *
     * Returns the categories this widget belongs to.
     *
     * @since 1.4.0
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'black_widgets' ];
    }

    /**
     * Get style dependencies.
     *
     * Returns an array of style handles that need to be enqueued.
     *
     * @since 1.4.0
     *
     * @return array List of style handles.
     */
    public function get_style_depends() {
        return [ 'black-widgets-image-marquee' ];
    }

    /**
     * Get script dependencies.
     *
     * Returns an array of script handles that need to be enqueued.
     *
     * @since 1.4.0
     *
     * @return array List of script handles.
     */
    public function get_script_depends() {
        $deps = [ 'black-widgets-image-marquee' ];

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
     * Determine whether the widget uses dynamic content.
     *
     * This widget is static and doesn't rely on dynamic data.
     *
     * @since 1.4.0
     *
     * @return bool False always.
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
     * Register the content and settings controls.
     *
     * Allows users to add images and set marquee behavior settings.
     *
     * @since 1.4.0
     *
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
        $repeater = new Repeater();

        $repeater->add_control('image', [
            'label' => esc_html__( 'Images', 'blackwidgets' ),
            'type' => Controls_Manager::MEDIA,
        ]);

        $this->add_control('images', [
            'label' => esc_html__('Marquee Images', 'blackwidgets'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [],
        ]);

        $this->add_control(
            'hr1_settings',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'widget_type',
            [
                'label' => esc_html__( 'Type', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal'   =>  esc_html__( 'Type 1', 'blackwidgets' ),
                    'vertical'   =>  esc_html__( 'Type 2', 'blackwidgets' ),
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
                    'left' => esc_html__( 'Left To Right / Up', 'blackwidgets' ),
                    'right' => esc_html__( 'Right To Left / Down', 'blackwidgets' ),
                ],
            ]
        );

        $this->add_control(
            'widget_speed',
            [
                'label' => esc_html__( 'Duration (s)', 'blackwidgets' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 1,
                'max' => 1000,
            ]
        );

        $this->add_control(
            'widget_gap',
            [
                'label' => esc_html__( 'GAP', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 40,
                'description' => esc_html__( 'Space between images.', 'blackwidgets' ),
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-wrapper' => '--bw-marquee-gap: {{VALUE}}px;',
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
            'hr7_settings',
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
                ]
            );

            $this->add_control(
                'widget_gsap_speed',
                [
                    'label' => esc_html__( 'Speed Scroll', 'blackwidgets' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'default' => 1,
                    'condition' => [
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
//                        'widget_mos' => 'yes',
//                    ],
//                ]
//            );
        }

        $this->end_controls_section();
    }

    /**
     * Register box style controls.
     *
     * Controls for border, box shadow, and background of the marquee container.
     *
     * @since 1.4.0
     *
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
                'selector' => '{{WRAPPER}} .bw-image-marquee-wrapper',
            ]
        );

        $this->add_control(
            'hr2_box',
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
                    '{{WRAPPER}} .bw-image-marquee-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'widget_box_height',
            [
                'label' => esc_html__( 'Height', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'vw' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 800 ],
                    'em' => [ 'min' => 0, 'max' => 50 ],
                    'vw' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-wrapper' => 'height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .bw-image-marquee-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .bw-image-marquee-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr3_box',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_box_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-image-marquee-wrapper',
            ]
        );

        $this->add_control(
            'widget_box_border_radius',
            [
                'label' => __('Border Radius', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-image-marquee-wrapper',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls for individual items.
     *
     * Controls to style the images inside the marquee (e.g., size, margin).
     *
     * @since 1.4.0
     *
     * @return void
     */
    private function register_style_content_controls() {
        $this->start_controls_section(
            'style_image_section',
            [
                'label' => esc_html__( 'Image Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_image_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .bw-image-marquee-item img',
            ]
        );

        $this->add_control(
            'hr2_image',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'widget_image_width',
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
                    '{{WRAPPER}} .bw-image-marquee-item img' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'widget_image_height',
            [
                'label' => esc_html__( 'Height', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'vw' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 800 ],
                    'em' => [ 'min' => 0, 'max' => 50 ],
                    'vw' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-item img' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'widget_image_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-item img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_image_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-item img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr3_image',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_image_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-image-marquee-item img',
            ]
        );

        $this->add_control(
            'widget_image_border_radius',
            [
                'label' => __('Border Radius', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'widget_image_css_filters',
                'label' => __('Css Filters', 'blackwidgets'),
                'selector' => '{{WRAPPER}} .bw-image-marquee-item img',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'widget_image_hover_css_filters',
                'label' => __('Css Filters Hover', 'blackwidgets'),
                'selector' => '{{WRAPPER}} .bw-image-marquee-item img:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_image_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-image-marquee-item img',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * Outputs the HTML for the image marquee, including GSAP data attributes if enabled.
     *
     * @since 1.4.0
     *
     * @return void
     */
    protected function render() {
        $settings        = $this->get_settings_for_display();
        $images          = $settings['images'] ?? [];
        $speed           = $settings['widget_speed'] ?? '20';
        $direction       = $settings['widget_direction'] ?? 'left';
        $marquee_type    = $settings['widget_type'] ?? 'horizontal';
        $gap             = isset( $settings['widget_gap'] ) ? (int) $settings['widget_gap'] : 40;
        $pause_on_hover  = ($settings['widget_pause_on_hover'] ?? '') === 'yes' ? 'true' : 'false';
        $use_gsap_attr   = 'false';
        $gsap_start      = 'top bottom';
        $gsap_end        = 'bottom top';
        // Align with widget_gsap_speed control default (1).
        $speedScroll     = 1;

        if ( $this->is_gsap_enabled() ) {
            $use_gsap_scroll = $settings['widget_mos'] ?? '';
            $use_gsap_attr   = $use_gsap_scroll === 'yes' ? 'true' : 'false';
            $speedScroll     = sanitize_text_field( $settings['widget_gsap_speed'] ?? $speedScroll );
        }

        if ( empty( $images ) ) return;

        $type_class  = $marquee_type === 'vertical' ? 'vertical' : 'horizontal';
        ?>
        <div class="bw-image-marquee-wrapper <?php echo esc_attr( $type_class ); ?>"
             data-speed="<?php echo esc_attr( $speed ); ?>"
             data-direction="<?php echo esc_attr( $direction ); ?>"
             data-type="<?php echo esc_attr( $marquee_type ); ?>"
             data-gap="<?php echo esc_attr( (string) $gap ); ?>"
             data-gsap-scroll="<?php echo esc_attr( $use_gsap_attr ); ?>"
             data-pause-hover="<?php echo esc_attr( $pause_on_hover ); ?>"
             data-gsap-start="<?php echo esc_attr( $gsap_start ); ?>"
             data-gsap-end="<?php echo esc_attr( $gsap_end ); ?>"
             data-speed-scroll="<?php echo esc_attr( $speedScroll ); ?>"
             style="--bw-marquee-gap: <?php echo esc_attr( (string) $gap ); ?>px;"
        >
            <div class="bw-image-marquee-track">
                <?php if ( count( $images ) >= 1 && ! empty( $images[0]['image']['url'] ) ): ?>
                    <div class="bw-image-marquee-group">
                        <?php foreach ( $images as $item ):
                            $img_id = $item['image']['id'] ?? '';
                            if ( $img_id ) :
                                ?>
                                <div class="bw-image-marquee-item">
                                    <?php echo wp_get_attachment_image( $img_id, 'full', false, [ 'loading' => 'lazy', 'alt' => ''  ] ); ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

}
