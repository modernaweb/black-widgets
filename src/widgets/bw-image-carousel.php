<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Utils;

class ImageCarousel extends \Elementor\Widget_Base {

    /**
     * Simple built-in default arrow icons (used unless a custom SVG is uploaded).
     * Hardcoded, trusted markup - not derived from user input.
     */
    private const DEFAULT_ARROW_PREV_SVG = '<svg class="bw-swiper-arrow-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10.6,12.71a1,1,0,0,1,0-1.42l4.59-4.58a1,1,0,0,0,0-1.42,1,1,0,0,0-1.41,0L9.19,9.88a3,3,0,0,0,0,4.24l4.59,4.59a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.42Z"/></svg>';
    private const DEFAULT_ARROW_NEXT_SVG = '<svg class="bw-swiper-arrow-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M15.4,9.88,10.81,5.29a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L14,11.29a1,1,0,0,1,0,1.42L9.4,17.29a1,1,0,0,0,1.41,1.42l4.59-4.59A3,3,0,0,0,15.4,9.88Z"/></svg>';

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_script( 'black-widgets-swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/swiper-bundle.min.js', [], BLACK_WIDGETS_VERSION, true );
        wp_register_style( 'black-widgets-swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/libraries/swiper-bundle.min.css', [], BLACK_WIDGETS_VERSION );

        wp_register_style( 'black-widgets-image-carousel', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/image-carousel.css', [ 'black-widgets-swiper' ], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-image-carousel', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/image-carousel.js', [ 'jquery', 'black-widgets-swiper' ], BLACK_WIDGETS_VERSION, true );
    }


    public function get_name() {
        return 'b_image_carousel';
    }

    public function get_title() {
        return __( 'Black Image Carousel', 'black-widgets' );
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return [ 'black_widgets' ];
    }

    public function get_style_depends() {
        return [ 'black-widgets-image-carousel', 'black-widgets-swiper' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-image-carousel', 'black-widgets-swiper' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

    protected function register_controls() {
        $source = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' );
        $elementor_tpl = $source ? $source->get_items() : [];
        $elementor_tpl_opts = [ '0' => esc_html__( 'Select Elementor template', 'black-widgets' ) ];

        if ( ! empty( $elementor_tpl ) ) {
            foreach ( $elementor_tpl as $template ) {
                $elementor_tpl_opts[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            }
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'item_type',
            [
                'label' => esc_html__( 'Item Type', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image'    => esc_html__( 'Image', 'black-widgets' ),
                    'template' => esc_html__( 'Elementor Template', 'black-widgets' ),
                ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Choose Image', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'condition' => [
                    'item_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'caption',
            [
                'label' => esc_html__( 'Caption', 'black-widgets' ),
                'description' => esc_html__( 'Shown on the slide - position/style depends on the Carousel Type and the Caption style section.', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__( 'Enter caption', 'black-widgets' ),
                'label_block' => true,
                'condition' => [
                    'item_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://example.com', 'black-widgets' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'description' => esc_html__( 'Optional - makes this slide clickable.', 'black-widgets' ),
                'condition' => [
                    'item_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'alt_text',
            [
                'label' => esc_html__( 'Alt Text (override)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__( 'Leave empty to use the image\'s own alt text', 'black-widgets' ),
                'label_block' => true,
                'condition' => [
                    'item_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'template_id',
            [
                'label' => esc_html__( 'Select Template', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $elementor_tpl_opts,
                'default' => '0',
                'condition' => [
                    'item_type' => 'template',
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__( 'Items', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_type' => 'image',
                        'image' => [
                            'url' => Utils::get_placeholder_image_src(),
                            'id' => '0',
                        ],
                        'caption' => esc_html__( 'Caption', 'black-widgets' ),
                    ],
                ],
                'title_field' => '{{{ item_type }}}',
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
                'label' => esc_html__( 'Carousel Type', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'type1',
                'options' => [
                    'type1' => esc_html__( 'Classic Caption', 'black-widgets' ),
                    'type2' => esc_html__( 'Clean Cover', 'black-widgets' ),
                    'type3' => esc_html__( 'Editorial', 'black-widgets' ),
                    'type4' => esc_html__( 'Peek Focus', 'black-widgets' ),
                ],
                'render_type' => 'template',
            ]
        );

        // Peek Focus only - emphasize the centered slide instead of just dimming the neighbors.
        $this->add_control(
            'peek_center_emphasis',
            [
                'label' => __( 'Center Slide Emphasis', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'black-widgets' ),
                'label_off' => __( 'No', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Scales up the centered slide. Only applies when there is a genuine single center slide (i.e. an odd number of visible slides); has no effect on breakpoints showing an even count.', 'black-widgets' ),
                'condition' => [
                    'widget_type' => 'type4',
                ],
            ]
        );

        $this->add_control(
            'peek_center_scale',
            [
                'label' => __( 'Center Slide Scale', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 1, 'max' => 1.3, 'step' => 0.01 ] ],
                'default' => [ 'unit' => 'px', 'size' => 1.1 ],
                'condition' => [
                    'widget_type' => 'type4',
                    'peek_center_emphasis' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-peek-center-scale: {{SIZE}};',
                    '{{WRAPPER}} .bw-swiper.bw-swiper-center-single .swiper-slide-active' => '--bw-peek-center-scale: {{SIZE}};',
                ],
            ]
        );

        // Slides Per View
        $this->add_responsive_control(
            'slides_per_view',
            [
                'label' => __( 'Slides Per View', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 0.1,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'description' => __( 'For Peek Focus try 1.2 to 1.5. For Editorial try about 1.', 'black-widgets' ),
                'render_type' => 'template',
            ]
        );

        // Slides To Scroll
        $this->add_control(
            'slides_to_scroll',
            [
                'label' => __( 'Slides to Scroll', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between (px)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 80,
                'step' => 1,
                'description' => esc_html__( 'Leave empty to use the type default gap.', 'black-widgets' ),
            ]
        );

        $this->add_control(
            'slider_height',
            [
                'label' => esc_html__( 'Height', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 450,
                ],
            ]
        );

        $this->add_control(
            'slider_image_fit',
            [
                'label' => esc_html__( 'Image Fit', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'contain' => esc_html__( 'Contain', 'black-widgets' ),
                    'cover'  => esc_html__( 'Cover', 'black-widgets' ),
                ],
                'selectors' => [
                    // Extra ".bw-swiper" ancestor class keeps this more specific than any
                    // ".bw-swiper-typeN ..." default rule, regardless of CSS source order.
                    '{{WRAPPER}} .bw-swiper .bw-swiper-slide.has-image img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        // Autoplay
        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'On', 'black-widgets' ),
                'label_off' => __( 'Off', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        // Autoplay Speed
        $this->add_control(
            'autoplay_speed',
            [
                'label' => __( 'Autoplay Speed (ms)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 100,
                'step' => 100,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label'       => __( 'Transition Speed (ms)', 'black-widgets' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'default'     => 750,
                'min'         => 200,
                'max'         => 2500,
                'step'        => 50,
                'description' => __( 'How long each slide change takes. Higher = smoother / softer (recommended 700–1000 for centered layouts like Editorial & Peek Focus).', 'black-widgets' ),
            ]
        );

        // Infinite Loop - works even when slides ≤ slidesPerView (JS clones as needed).
        $this->add_control(
            'loop',
            [
                'label' => __( 'Infinite Loop', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'black-widgets' ),
                'label_off' => __( 'No', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Continues from the first slide after the last (seamless wrap). With few items this wrap can look like it “goes and comes back” — that is expected, not a bounce. Turn off to stop at both ends.', 'black-widgets' ),
            ]
        );

        // Pause on Hover
        $this->add_control(
            'pause_on_hover',
            [
                'label' => __( 'Pause on Hover', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'black-widgets' ),
                'label_off' => __( 'No', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        // Pagination
        $this->add_control(
            'pagination_dots',
            [
                'label' => __( 'Show Pagination', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'black-widgets' ),
                'label_off' => __( 'Hide', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label' => __( 'Pagination Style', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => esc_html__( 'Type Default', 'black-widgets' ),
                    'dots' => esc_html__( 'Dots', 'black-widgets' ),
                    'fraction' => esc_html__( 'Fraction (1 / 5)', 'black-widgets' ),
                    'progressbar' => esc_html__( 'Progress Bar', 'black-widgets' ),
                ],
                'description' => __( 'Type Default: Classic Caption / Clean Cover use Dots, Editorial uses Fraction, Peek Focus uses a Progress Bar.', 'black-widgets' ),
                'condition' => [
                    'pagination_dots' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_position',
            [
                'label' => __( 'Pagination Position', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => esc_html__( 'Inside (Overlay on Slider)', 'black-widgets' ),
                    'outside' => esc_html__( 'Outside (Below Slider)', 'black-widgets' ),
                ],
                'condition' => [
                    'pagination_dots' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pagination_align',
            [
                'label' => __( 'Pagination Alignment', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => '',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'black-widgets' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'black-widgets' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'black-widgets' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'description' => __( 'Leave unset to keep each carousel type\'s own default alignment.', 'black-widgets' ),
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_dots' => 'yes',
                ],
            ]
        );

        // Navigation Arrows
        $this->add_control(
            'navigation_arrows',
            [
                'label' => __( 'Show Navigation Arrows', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'black-widgets' ),
                'label_off' => __( 'Hide', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'arrows_position',
            [
                'label' => __( 'Arrows Position', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => esc_html__( 'Inside (Overlay on Slider)', 'black-widgets' ),
                    'outside' => esc_html__( 'Outside (Below Slider)', 'black-widgets' ),
                ],
                'condition' => [
                    'navigation_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_prev_svg',
            [
                'label' => esc_html__( 'Previous Arrow Icon (custom SVG)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => [ 'svg' ],
                'description' => esc_html__( 'Optional - overrides the built-in arrow icon. Still respects the Arrows Icon Color setting below.', 'black-widgets' ),
                'condition' => [
                    'navigation_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_next_svg',
            [
                'label' => esc_html__( 'Next Arrow Icon (custom SVG)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => [ 'svg' ],
                'description' => esc_html__( 'Optional - overrides the built-in arrow icon. Still respects the Arrows Icon Color setting below.', 'black-widgets' ),
                'condition' => [
                    'navigation_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_dot_svg',
            [
                'label' => esc_html__( 'Custom Dot Icon (SVG)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => [ 'svg' ],
                'description' => esc_html__( 'Optional - replaces the simple circle bullet with your icon. Still respects the Bullet Color settings below (only applies to the Dots pagination style).', 'black-widgets' ),
                'condition' => [
                    'pagination_dots' => 'yes',
                    'pagination_type!' => [ 'fraction', 'progressbar' ],
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Box Style', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_background',
                'label' => esc_html__( 'Background', 'black-widgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-swiper',
            ]
        );

        $this->add_control(
            'hr2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'widget_box_margin',
            [
                'label' => esc_html__( 'Margin', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_box_padding',
            [
                'label' => esc_html__( 'Padding', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label' => esc_html__( 'Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-swiper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-swiper',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_slide_section',
            [
                'label' => esc_html__( 'Slide Style', 'black-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'slide_style_info',
            [
                'type'            => \Elementor\Controls_Manager::RAW_HTML,
                'raw'             => esc_html__( 'Applies to every image and template slide (the slide content card).', 'black-widgets' ),
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'slide_background',
                'label'    => esc_html__( 'Background', 'black-widgets' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-swiper .bw-swiper-mask',
            ]
        );

        $this->add_responsive_control(
            'slide_padding',
            [
                'label'      => esc_html__( 'Padding', 'black-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper .bw-swiper-mask' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'slide_border',
                'label'    => esc_html__( 'Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-swiper .bw-swiper-mask',
            ]
        );

        $this->add_responsive_control(
            'slide_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'black-widgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper .bw-swiper-mask' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-swiper .bw-swiper-mask img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'slide_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-swiper .bw-swiper-mask',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'carousel_style_section',
            [
                'label' => esc_html__( 'Bullet Points and Arrows Style', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widget_arrows_background_color',
            [
                'label' => esc_html__( 'Arrows Background Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    // ".bw-swiper-outer" ancestor (present whether the buttons render
                    // inside the slider or outside it) keeps these more specific than
                    // any ".bw-swiper-typeN ..." default rule, regardless of source order.
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-prev' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-next' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'widget_arrows_color',
            [
                'label' => esc_html__( 'Arrows Icon Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-prev' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-next' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'widget_arrows_hover_background_color',
            [
                'label' => esc_html__( 'Arrows Hover Background Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-prev:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-next:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'widget_arrows_hover_color',
            [
                'label' => esc_html__( 'Arrows Hover Icon Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-prev:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-next:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_arrows_size',
            [
                'label' => esc_html__( 'Arrow Size', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 24,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-prev, {{WRAPPER}} .bw-swiper-outer .bw-swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_arrows_border_radius',
            [
                'label' => esc_html__( 'Arrows Border Radius', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-button-prev, {{WRAPPER}} .bw-swiper-outer .bw-swiper-button-next' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_arrows_offset',
            [
                'label' => esc_html__( 'Arrows Edge Offset', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'description' => esc_html__( 'Distance from the slider edge. Only applies when Arrows Position is set to Inside.', 'black-widgets' ),
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper' => '--bw-swiper-nav-inset: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'arrows_position' => 'inside',
                ],
            ]
        );

        $this->add_control(
            'hr_bullets',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'widget_bullets_size',
            [
                'label' => esc_html__( 'Bullets Size', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 48,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_bullets_gap',
            [
                'label' => esc_html__( 'Bullets Gap', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-bullet-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_bullets_border_radius',
            [
                'label' => esc_html__( 'Bullets Border Radius', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 24,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'description' => esc_html__( 'Set to 0 for square/pill-shaped dots instead of circles.', 'black-widgets' ),
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--swiper-pagination-bullet-border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'widget_inactive_bullets_color',
            [
                'label' => esc_html__( 'Inactive Bullets Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-bullet-inactive: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widget_active_bullets_color',
            [
                'label' => esc_html__( 'Active Bullets Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-bullet-active: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widget_active_bullets_border_color',
            [
                'label' => esc_html__( 'Active Bullets Border Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-outer.bw-swiper-type4' => '--bw-swiper-bullet-border: {{VALUE}}',
                ],
                'condition' => [
                    'widget_type' => 'type4',
                ],
            ]
        );

        $fraction_style_condition = [
            'relation' => 'and',
            'terms'    => [
                [
                    'name'     => 'pagination_dots',
                    'operator' => '===',
                    'value'    => 'yes',
                ],
                [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => 'pagination_type',
                            'operator' => '===',
                            'value'    => 'fraction',
                        ],
                        [
                            'relation' => 'and',
                            'terms'    => [
                                [
                                    'name'     => 'pagination_type',
                                    'operator' => '===',
                                    'value'    => 'auto',
                                ],
                                [
                                    'name'     => 'widget_type',
                                    'operator' => '===',
                                    'value'    => 'type3',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->add_control(
            'hr_fraction',
            [
                'type'       => \Elementor\Controls_Manager::DIVIDER,
                'conditions' => $fraction_style_condition,
            ]
        );

        $this->add_control(
            'fraction_style_heading',
            [
                'label'      => esc_html__( 'Fraction Style', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::HEADING,
                'conditions' => $fraction_style_condition,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'       => 'fraction_typography',
                'label'      => esc_html__( 'Typography', 'black-widgets' ),
                'selector'   => '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination.swiper-pagination-fraction',
                'conditions' => $fraction_style_condition,
            ]
        );

        $this->add_control(
            'fraction_color',
            [
                'label'       => esc_html__( 'Color', 'black-widgets' ),
                'type'        => \Elementor\Controls_Manager::COLOR,
                'description' => esc_html__( 'Base color (also used for the “/” separator).', 'black-widgets' ),
                'selectors'   => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-fraction-color: {{VALUE}};',
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination.swiper-pagination-fraction' => 'color: {{VALUE}};',
                ],
                'conditions'  => $fraction_style_condition,
            ]
        );

        $this->add_control(
            'fraction_current_color',
            [
                'label'      => esc_html__( 'Current Number Color', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::COLOR,
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-fraction-current: {{VALUE}};',
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination .swiper-pagination-current' => 'color: {{VALUE}};',
                ],
                'conditions' => $fraction_style_condition,
            ]
        );

        $this->add_control(
            'fraction_total_color',
            [
                'label'      => esc_html__( 'Total Number Color', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::COLOR,
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-fraction-total: {{VALUE}};',
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination .swiper-pagination-total' => 'color: {{VALUE}};',
                ],
                'conditions' => $fraction_style_condition,
            ]
        );

        $this->add_responsive_control(
            'fraction_margin',
            [
                'label'      => esc_html__( 'Margin', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination.swiper-pagination-fraction' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => $fraction_style_condition,
            ]
        );

        $this->add_responsive_control(
            'fraction_padding',
            [
                'label'      => esc_html__( 'Padding', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination.swiper-pagination-fraction' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => $fraction_style_condition,
            ]
        );

        $this->add_responsive_control(
            'fraction_gap',
            [
                'label'      => esc_html__( 'Gap Around Separator', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                    'em' => [
                        'min'  => 0,
                        'max'  => 2,
                        'step' => 0.05,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-fraction-gap: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => $fraction_style_condition,
            ]
        );

        $progress_style_condition = [
            'relation' => 'and',
            'terms'    => [
                [
                    'name'     => 'pagination_dots',
                    'operator' => '===',
                    'value'    => 'yes',
                ],
                [
                    'relation' => 'or',
                    'terms'    => [
                        [
                            'name'     => 'pagination_type',
                            'operator' => '===',
                            'value'    => 'progressbar',
                        ],
                        [
                            'relation' => 'and',
                            'terms'    => [
                                [
                                    'name'     => 'pagination_type',
                                    'operator' => '===',
                                    'value'    => 'auto',
                                ],
                                [
                                    'name'     => 'widget_type',
                                    'operator' => '===',
                                    'value'    => 'type4',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->add_control(
            'hr_progress',
            [
                'type'       => \Elementor\Controls_Manager::DIVIDER,
                'conditions' => $progress_style_condition,
            ]
        );

        $this->add_control(
            'progress_style_heading',
            [
                'label'      => esc_html__( 'Progress Bar Style', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::HEADING,
                'conditions' => $progress_style_condition,
            ]
        );

        $this->add_control(
            'progress_track_color',
            [
                'label'      => esc_html__( 'Track Color', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::COLOR,
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-progress-track: {{VALUE}};',
                ],
                'conditions' => $progress_style_condition,
            ]
        );

        $this->add_control(
            'progress_fill_color',
            [
                'label'      => esc_html__( 'Fill Color', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::COLOR,
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-progress-fill: {{VALUE}};',
                ],
                'conditions' => $progress_style_condition,
            ]
        );

        $this->add_responsive_control(
            'progress_height',
            [
                'label'      => esc_html__( 'Height', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 24,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-progress-height: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => $progress_style_condition,
            ]
        );

        $this->add_responsive_control(
            'progress_width',
            [
                'label'      => esc_html__( 'Width', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 24,
                        'max' => 800,
                    ],
                    '%' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 72,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-progress-width: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => $progress_style_condition,
            ]
        );

        $this->add_responsive_control(
            'progress_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer' => '--bw-swiper-progress-radius: {{SIZE}}{{UNIT}};',
                ],
                'conditions' => $progress_style_condition,
            ]
        );

        $this->add_responsive_control(
            'progress_margin',
            [
                'label'      => esc_html__( 'Margin', 'black-widgets' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-swiper-outer .bw-swiper-pagination.swiper-pagination-progressbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => $progress_style_condition,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_caption_section',
            [
                'label' => esc_html__( 'Caption', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .bw-swiper .bw-swiper-caption',
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label' => esc_html__( 'Text Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper .bw-swiper-caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'caption_background_color',
            [
                'label' => esc_html__( 'Background Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => esc_html__( 'Classic Caption has no backdrop by default; Clean Cover, Editorial and Peek Focus overlay the caption on the image with a flat translucent backdrop by default.', 'black-widgets' ),
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper .bw-swiper-caption' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_padding',
            [
                'label' => esc_html__( 'Padding', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper .bw-swiper-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_text_align',
            [
                'label' => esc_html__( 'Text Align', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'black-widgets' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'black-widgets' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'black-widgets' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper .bw-swiper-caption' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Whether a repeater item has renderable content.
     *
     * @param array $item Repeater item.
     * @return bool
     */
    private function item_has_content( $item ) {
        if ( ! is_array( $item ) ) {
            return false;
        }

        $item_type = isset( $item['item_type'] ) ? $item['item_type'] : 'image';

        if ( 'image' === $item_type ) {
            return ! empty( $item['image']['url'] ?? '' );
        }

        if ( 'template' === $item_type ) {
            $template_id = isset( $item['template_id'] ) ? (string) $item['template_id'] : '0';
            return '' !== $template_id && '0' !== $template_id;
        }

        return false;
    }

    /**
     * Safe media URL from an Elementor MEDIA control value.
     *
     * @param mixed $media Control value.
     * @return string
     */
    private function media_url( $media ) {
        if ( ! is_array( $media ) || empty( $media['url'] ) || ! is_string( $media['url'] ) ) {
            return '';
        }

        $url = esc_url_raw( $media['url'] );
        if ( '' === $url ) {
            return '';
        }

        // Allow http(s), protocol-relative, and root-relative URLs only (blocks javascript:/data:).
        if ( preg_match( '#^(https?:)?//#i', $url ) || 0 === strpos( $url, '/' ) ) {
            return $url;
        }

        return '';
    }

    /**
     * Resolve the effective pagination style for a given carousel type.
     *
     * @param string $type     Carousel type (type1-type4).
     * @param string $selected User-selected pagination_type setting.
     * @return string One of 'dots', 'fraction', 'progressbar'.
     */
    private function resolve_pagination_type( $type, $selected ) {
        if ( in_array( $selected, [ 'dots', 'fraction', 'progressbar' ], true ) ) {
            return $selected;
        }

        switch ( $type ) {
            case 'type3':
                return 'fraction';
            case 'type4':
                return 'progressbar';
            default:
                return 'dots';
        }
    }

    /**
     * Output a nav arrow's icon markup: custom uploaded SVG (as a recolorable
     * CSS mask) when provided, otherwise the built-in default SVG.
     *
     * @param string $direction  'prev' or 'next'.
     * @param string $custom_url Sanitized custom SVG URL, or ''.
     * @return void
     */
    private function render_nav_arrow_icon( $direction, $custom_url ) {
        if ( '' !== $custom_url ) {
            // Defensive: a literal single quote in the URL (however unlikely) could
            // otherwise break out of the CSS url('...') wrapper below.
            $safe_url = str_replace( "'", '%27', esc_url( $custom_url ) );
            $style = sprintf( "--bw-mask-url:url('%s');", $safe_url );
            printf(
                '<span class="bw-swiper-arrow-icon bw-swiper-arrow-icon--mask" style="%1$s" aria-hidden="true"></span>',
                esc_attr( $style )
            );
            return;
        }

        echo 'prev' === $direction ? self::DEFAULT_ARROW_PREV_SVG : self::DEFAULT_ARROW_NEXT_SVG; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Output the prev/next nav <button> pair (used for both the "Inside" overlay
     * placement and the "Outside" below-slider placement).
     *
     * @param string $prev_icon Sanitized custom prev arrow SVG URL, or ''.
     * @param string $next_icon Sanitized custom next arrow SVG URL, or ''.
     * @return void
     */
    private function render_nav_buttons( $prev_icon, $next_icon ) {
        ?>
        <button
            type="button"
            class="bw-swiper-button-prev swiper-button-prev"
            aria-label="<?php echo esc_attr__( 'Previous slide', 'black-widgets' ); ?>"
        >
            <?php $this->render_nav_arrow_icon( 'prev', $prev_icon ); ?>
        </button>
        <button
            type="button"
            class="bw-swiper-button-next swiper-button-next"
            aria-label="<?php echo esc_attr__( 'Next slide', 'black-widgets' ); ?>"
        >
            <?php $this->render_nav_arrow_icon( 'next', $next_icon ); ?>
        </button>
        <?php
    }

    /**
     * Resolve the alt text for a slide image: explicit override, then the
     * attachment's own alt text, then the caption, else empty.
     *
     * @param array  $item          Repeater item.
     * @param int    $attachment_id Attachment ID (0 if none).
     * @return string
     */
    private function resolve_slide_alt( $item, $attachment_id ) {
        $alt = isset( $item['alt_text'] ) ? trim( (string) $item['alt_text'] ) : '';

        if ( '' === $alt && $attachment_id > 0 ) {
            $alt = (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
        }

        if ( '' === $alt && ! empty( $item['caption'] ) ) {
            $alt = $item['caption'];
        }

        return $alt;
    }

    /**
     * Sanitize a CSS color used in inline custom properties.
     *
     * @param mixed $color Raw color.
     * @return string
     */
    private function sanitize_inline_css_color( $color ) {
        $color = trim( (string) $color );
        if ( '' === $color ) {
            return '#111111';
        }

        if ( function_exists( 'sanitize_hex_color' ) ) {
            $hex = sanitize_hex_color( $color );
            if ( $hex ) {
                return $hex;
            }
        }

        // Elementor global colors / safe CSS variables.
        if ( preg_match( '/^var\(\s*--[a-zA-Z0-9_-]+\s*(?:,[^;{}]*)?\)$/', $color ) ) {
            return $color;
        }

        // Allow rgb/rgba/hsl/hsla without breakout characters.
        if ( preg_match( '/^(rgb|hsl)a?\(\s*[\d.%\s,\/]+\)$/i', $color ) ) {
            return $color;
        }

        return '#111111';
    }

    /**
     * Sanitize slider height for CSS custom property.
     *
     * @param mixed $height Elementor slider control value.
     * @return string
     */
    private function sanitize_slider_height( $height ) {
        if ( ! is_array( $height ) || ! isset( $height['size'], $height['unit'] ) ) {
            return '450px';
        }

        $size = is_numeric( $height['size'] ) ? (float) $height['size'] : 450;
        $unit = in_array( $height['unit'], [ 'px', '%' ], true ) ? $height['unit'] : 'px';

        if ( $size < 0 ) {
            $size = 0;
        }

        if ( 'px' === $unit ) {
            return (string) (int) round( $size ) . 'px';
        }

        return rtrim( rtrim( number_format( $size, 4, '.', '' ), '0' ), '.' ) . '%';
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $images = isset( $settings['items'] ) ? $settings['items'] : [];
        $type = isset( $settings['widget_type'] ) ? $settings['widget_type'] : 'type1';

        if ( ! in_array( $type, [ 'type1', 'type2', 'type3', 'type4' ], true ) ) {
            $type = 'type1';
        }

        $prev_icon = $this->media_url( $settings['arrow_prev_svg'] ?? null );
        $next_icon = $this->media_url( $settings['arrow_next_svg'] ?? null );
        $dot_icon  = $this->media_url( $settings['custom_dot_svg'] ?? null );

        $resolved_pagination_type = $this->resolve_pagination_type( $type, $settings['pagination_type'] ?? 'auto' );

        $peek_center_emphasis = 'type4' === $type && ( ! isset( $settings['peek_center_emphasis'] ) || 'yes' === $settings['peek_center_emphasis'] );

        $inactive_bullets_color = $this->sanitize_inline_css_color( $settings['widget_inactive_bullets_color'] ?? '#111111' );
        $active_bullets_color   = $this->sanitize_inline_css_color( $settings['widget_active_bullets_color'] ?? '#111111' );
        $slider_height          = $this->sanitize_slider_height( $settings['slider_height'] ?? null );

        $valid_items = array_values(
            array_filter(
                is_array( $images ) ? $images : [],
                [ $this, 'item_has_content' ]
            )
        );
        $slides_count = count( $valid_items );

        $slides_per_view_raw = isset( $settings['slides_per_view'] ) ? (float) $settings['slides_per_view'] : 3;
        $slides_per_view_desktop = (float) ( $settings['slides_per_view'] ?? $slides_per_view_raw );
        $slides_per_view_tablet  = (float) ( $settings['slides_per_view_tablet'] ?? 2 );
        $slides_per_view_mobile  = (float) ( $settings['slides_per_view_mobile'] ?? 1 );

        // Soft clamp only - JS also caps per breakpoint so 1-2 items never conflict with desktop=3 etc.
        if ( $slides_count > 0 ) {
            $slides_per_view_desktop = max( 1, min( $slides_per_view_desktop, (float) $slides_count ) );
            $slides_per_view_tablet  = max( 1, min( $slides_per_view_tablet, (float) $slides_count ) );
            $slides_per_view_mobile  = max( 1, min( $slides_per_view_mobile, (float) $slides_count ) );
        }

        $slides_to_scroll = isset( $settings['slides_to_scroll'] ) ? (int) $settings['slides_to_scroll'] : 1;
        if ( $slides_count > 0 ) {
            $slides_to_scroll = max( 1, min( $slides_to_scroll, $slides_count ) );
        }
        $autoplay = isset( $settings['autoplay'] ) && 'yes' === $settings['autoplay'];
        $autoplay_speed = isset( $settings['autoplay_speed'] ) ? (int) $settings['autoplay_speed'] : 3000;
        $transition_speed = isset( $settings['transition_speed'] ) ? (int) $settings['transition_speed'] : 750;
        if ( $transition_speed < 200 ) {
            $transition_speed = 200;
        }
        if ( $transition_speed > 2500 ) {
            $transition_speed = 2500;
        }
        $loop = isset( $settings['loop'] ) && 'yes' === $settings['loop'];
        $pause_on_hover = isset( $settings['pause_on_hover'] ) && 'yes' === $settings['pause_on_hover'];
        $pagination = isset( $settings['pagination_dots'] ) && 'yes' === $settings['pagination_dots'];
        $navigation = isset( $settings['navigation_arrows'] ) && 'yes' === $settings['navigation_arrows'];
        $space_between = isset( $settings['space_between'] ) && '' !== $settings['space_between'] && null !== $settings['space_between']
            ? (int) $settings['space_between']
            : '';

        $arrows_outside = $navigation && 'outside' === ( $settings['arrows_position'] ?? 'inside' );
        $pagination_outside = $pagination && 'outside' === ( $settings['pagination_position'] ?? 'inside' );

        $id = 'bw-swiper-' . $this->get_id();
        $classes = [
            'bw-swiper',
            'swiper',
            'bw-swiper-' . $type,
        ];

        // The outer wrapper also carries the exact same "bw-swiper-{type}" class as
        // the Swiper root so type-specific CSS still reaches nav/pagination even
        // when they render outside the Swiper root (Swiper's own bundled CSS forces
        // overflow:hidden on .swiper, which would otherwise clip anything meant to
        // sit visibly below the slider).
        $outer_classes = [
            'bw-swiper-outer',
            'bw-swiper-' . $type,
        ];
        if ( $arrows_outside ) {
            $outer_classes[] = 'bw-swiper-outer--nav-outside';
        }
        if ( $pagination_outside ) {
            $outer_classes[] = 'bw-swiper-outer--pagination-outside';
        }

        $style_vars = sprintf(
            '--bw-swiper-bullet-inactive:%1$s; --bw-swiper-bullet-active:%2$s; --bw-swiper-height:%3$s; --bw-swiper-speed:%4$sms;',
            $inactive_bullets_color,
            $active_bullets_color,
            $slider_height,
            $transition_speed
        );
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $outer_classes ) ); ?>" style="<?php echo esc_attr( $style_vars ); ?>">
        <div
            id="<?php echo esc_attr( $id ); ?>"
            class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
            role="region"
            aria-roledescription="<?php echo esc_attr__( 'carousel', 'black-widgets' ); ?>"
            aria-label="<?php echo esc_attr__( 'Image carousel', 'black-widgets' ); ?>"
            data-custom-dot-icon="<?php echo esc_url( $dot_icon ); ?>"
            data-slide-count="<?php echo esc_attr( (string) $slides_count ); ?>"
            data-slides-per-view-desktop="<?php echo esc_attr( $slides_per_view_desktop ); ?>"
            data-slides-per-view-tablet="<?php echo esc_attr( $slides_per_view_tablet ); ?>"
            data-slides-per-view-mobile="<?php echo esc_attr( $slides_per_view_mobile ); ?>"
            data-type="<?php echo esc_attr( $type ); ?>"
            data-slides-to-scroll="<?php echo esc_attr( $slides_to_scroll ); ?>"
            data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
            data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>"
            data-transition-speed="<?php echo esc_attr( $transition_speed ); ?>"
            data-loop="<?php echo $loop ? 'true' : 'false'; ?>"
            data-pause-on-hover="<?php echo $pause_on_hover ? 'true' : 'false'; ?>"
            data-pagination="<?php echo $pagination ? 'true' : 'false'; ?>"
            data-pagination-type="<?php echo esc_attr( $resolved_pagination_type ); ?>"
            data-navigation="<?php echo $navigation ? 'true' : 'false'; ?>"
            data-peek-center-emphasis="<?php echo $peek_center_emphasis ? 'true' : 'false'; ?>"
            data-prev-label="<?php echo esc_attr__( 'Previous slide', 'black-widgets' ); ?>"
            data-next-label="<?php echo esc_attr__( 'Next slide', 'black-widgets' ); ?>"
            data-bullet-label="<?php echo esc_attr__( 'Go to slide {{index}}', 'black-widgets' ); ?>"
            <?php if ( '' !== $space_between ) : ?>
                data-space-between="<?php echo esc_attr( $space_between ); ?>"
            <?php endif; ?>
        >
            <div class="bw-swiper-wrapper swiper-wrapper">
                <?php foreach ( $valid_items as $slide_index => $item ) : ?>
                    <?php
                    $item_type = isset( $item['item_type'] ) ? $item['item_type'] : 'image';
                    $slide_class = ( 'image' === $item_type ) ? 'has-image' : 'has-template';
                    ?>
                    <div
                        class="bw-swiper-slide swiper-slide <?php echo esc_attr( $slide_class ); ?>"
                        role="group"
                        aria-roledescription="<?php echo esc_attr__( 'slide', 'black-widgets' ); ?>"
                        aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number 2: total slides */ __( '%1$d of %2$d', 'black-widgets' ), $slide_index + 1, $slides_count ) ); ?>"
                    >
                        <?php
                        $image_url = $this->media_url( $item['image'] ?? null );
                        $template_id = isset( $item['template_id'] ) ? absint( $item['template_id'] ) : 0;
                        ?>
                        <?php if ( 'image' === $item_type && '' !== $image_url ) : ?>
                            <?php
                            $attachment_id = isset( $item['image']['id'] ) ? absint( $item['image']['id'] ) : 0;
                            $alt = $this->resolve_slide_alt( $item, $attachment_id );
                            $link_url = isset( $item['link']['url'] ) ? trim( (string) $item['link']['url'] ) : '';
                            $has_link = '' !== $link_url;
                            if ( $has_link ) {
                                $link_target = ! empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                                $link_rel = ! empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                                printf(
                                    '<a class="bw-swiper-slide-link" href="%1$s"%2$s%3$s>',
                                    esc_url( $link_url ),
                                    $link_target, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    $link_rel // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                );
                            }
                            ?>
                            <div class="bw-swiper-mask">
                                <?php if ( $attachment_id > 0 ) : ?>
                                    <?php
                                    echo wp_get_attachment_image(
                                        $attachment_id,
                                        'large',
                                        false,
                                        [
                                            'alt' => $alt,
                                            'draggable' => 'false',
                                            'decoding' => 'async',
                                            'loading' => 0 === $slide_index ? 'eager' : 'lazy',
                                            'fetchpriority' => 0 === $slide_index ? 'high' : 'auto',
                                        ]
                                    );
                                    ?>
                                <?php else : ?>
                                    <img
                                        src="<?php echo esc_url( $image_url ); ?>"
                                        alt="<?php echo esc_attr( $alt ); ?>"
                                        draggable="false"
                                        loading="<?php echo 0 === $slide_index ? 'eager' : 'lazy'; ?>"
                                        decoding="async"
                                    /> <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
                                <?php endif; ?>
                            </div>
                            <?php if ( ! empty( $item['caption'] ) ) : ?>
                                <div class="bw-swiper-caption"><?php echo esc_html( $item['caption'] ); ?></div>
                            <?php endif; ?>
                            <?php if ( $has_link ) : ?>
                                </a>
                            <?php endif; ?>
                        <?php elseif ( 'template' === $item_type && $template_id > 0 ) : ?>
                            <div class="bw-swiper-mask">
                                <?php
                                echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ( $pagination && ! $pagination_outside ) : ?>
                <div class="bw-swiper-pagination swiper-pagination bw-swiper-pagination--<?php echo esc_attr( $resolved_pagination_type ); ?>"><?php
                if ( 'progressbar' === $resolved_pagination_type ) :
                    ?><span class="swiper-pagination-progressbar-fill"></span><?php
                endif;
                ?></div>
            <?php endif; ?>

            <?php if ( $navigation && ! $arrows_outside ) : ?>
                <?php $this->render_nav_buttons( $prev_icon, $next_icon ); ?>
            <?php endif; ?>
        </div>
        <?php if ( $pagination && $pagination_outside ) : ?>
            <div class="bw-swiper-pagination swiper-pagination bw-swiper-pagination--<?php echo esc_attr( $resolved_pagination_type ); ?>"><?php
            if ( 'progressbar' === $resolved_pagination_type ) :
                ?><span class="swiper-pagination-progressbar-fill"></span><?php
            endif;
            ?></div>
        <?php endif; ?>
        <?php if ( $navigation && $arrows_outside ) : ?>
            <div class="bw-swiper-nav-outside">
                <?php $this->render_nav_buttons( $prev_icon, $next_icon ); ?>
            </div>
        <?php endif; ?>
        </div>
        <?php
    }
}
