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

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_script( 'swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/swiper-bundle.min.js', [], BLACK_WIDGETS_VERSION, true );
        wp_register_style( 'swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/libraries/swiper-bundle.min.css', [], BLACK_WIDGETS_VERSION );

        wp_register_style( 'black-widgets-image-carousel', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/image-carousel.css', ['swiper'], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-image-carousel', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/image-carousel.js', [ 'jquery', 'swiper' ], BLACK_WIDGETS_VERSION, true );
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
        return [ 'black-widgets-image-carousel', 'swiper' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-image-carousel','swiper' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

    protected function register_controls() {
        $elementor_tpl = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
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
                        'caption' => esc_html__('caption', 'black-widgets'),
                    ]
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

        // Slides Per View
        $this->add_control(
            'slides_per_view',
            [
                'label' => __( 'Slides Per View', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'step' => 2,
                'description' => __( 'Recommended: Use odd numbers like 1, 3, 5 to center active slide.', 'black-widgets' ),
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

        // Autoplay
        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'On', 'black-widgets' ),
                'label_off' => __( 'Off', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
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

        // Loop
        $this->add_control(
            'loop',
            [
                'label' => __( 'Infinite Loop', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'black-widgets' ),
                'label_off' => __( 'No', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
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

        // Pagination Dots
        $this->add_control(
            'pagination_dots',
            [
                'label' => __( 'Show Pagination Dots', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'black-widgets' ),
                'label_off' => __( 'Hide', 'black-widgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
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
            'widget_type',
            [
                'label' => esc_html__( 'Carousel Type', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'type1',
                'options' => [
                    'type1'   =>  esc_html__( 'Type 1', 'black-widgets' ),
                    'type2'   =>  esc_html__( 'Type 2', 'black-widgets' ),
                    'type3'   =>  esc_html__( 'Type 3', 'black-widgets' ),
                    'type4'   =>  esc_html__( 'Type 4', 'black-widgets' ),
                ]
            ]
        );

        $this->add_control(
            'arrow_prev_svg',
            [
                'label' => esc_html__( 'Previous Arrow SVG', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => [ 'svg' ],
                'condition' => [
                    'widget_type' => 'type3',
                ],
            ]
        );

        $this->add_control(
            'arrow_next_svg',
            [
                'label' => esc_html__( 'Next Arrow SVG', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => [ 'svg' ],
                'condition' => [
                    'widget_type' => 'type3',
                ],
            ]
        );

        $this->add_control(
            'custom_dot_svg',
            [
                'label' => esc_html__( 'Custom Dot SVG', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => [ 'svg' ],
                'condition' => [
                    'widget_type' => 'type3',
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
                'types' => [ 'classic', 'gradient', 'video' ],
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
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-button-prev' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .bw-swiper-button-next' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'widget_arrows_color',
            [
                'label' => esc_html__( 'Arrows Icon Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-button-prev:after' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-swiper-button-next:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_arrows_size',
            [
                'label' => esc_html__( 'Arrow Icon Size (px)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-button-prev, {{WRAPPER}} .bw-swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_bullets_size',
            [
                'label' => esc_html__( 'Bullets Icon Size (px)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => '--swiper-pagination-bullet-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'widget_inactive_bullets_color',
            [
                'label' => esc_html__( 'Inactive Bullets Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet svg' => 'stroke: {{VALUE}}',
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'widget_active_bullets_color',
            [
                'label' => esc_html__( 'Active Bullets Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active svg' => 'stroke: {{VALUE}}',
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'widget_active_bullets_border_color',
            [
                'label' => esc_html__( 'Active Bullets Border Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .bw-swiper-type4 .swiper-pagination-bullet-active' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'widget_type' => [ 'type3', 'type4' ],
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $images = isset( $settings['items'] ) ? $settings['items'] : [];
        $type = isset( $settings['widget_type'] ) ? $settings['widget_type'] : 'type1';

        if ( ! in_array( $type, [ 'type1', 'type2', 'type3', 'type4' ] ) ) {
            $type = 'type1';
        }

        $prev_icon = $settings['arrow_prev_svg']['url'] ?? '';
        $next_icon = $settings['arrow_next_svg']['url'] ?? '';
        $dot_icon  = $settings['custom_dot_svg']['url'] ?? '';

        if(!empty($prev_icon) && !empty($next_icon)){
            ?>
            <style>
                .bw-swiper .swiper-button-next:after, .swiper-rtl .swiper-button-prev:after,
                .bw-swiper .swiper-button-prev:after, .swiper-rtl .swiper-button-prev:after {
                    content: '';
                    display: none;
                }
                .bw-swiper-button-prev, .bw-swiper-button-next {
                    padding: 5px;
                    width: 30px;
                    height: 30px;
                }
            </style>
            <?php
        }

        if(!empty($dot_icon)){
            ?>
            <style>
                .swiper-pagination-bullet {
                    width: 12px;
                    height: 12px;
                    display: flex;
                }
            </style>
            <?php
        }

        $inactive_bullets_color = isset( $settings['widget_inactive_bullets_color'] ) ? esc_attr( $settings['widget_inactive_bullets_color'] ) : '#fff' ;
        $active_bullets_color = isset( $settings['widget_active_bullets_color'] ) ? esc_attr( $settings['widget_active_bullets_color'] ) : '#fff' ;

        $slides_per_view = $settings['slides_per_view'] ?? 3;
        $slides_to_scroll = $settings['slides_to_scroll'] ?? 1;
        $autoplay = $settings['autoplay'] === 'yes';
        $autoplay_speed = $settings['autoplay_speed'] ?? 3000;
        $loop = $settings['loop'] === 'yes';
        $pause_on_hover = $settings['pause_on_hover'] === 'yes';
        $pagination = $settings['pagination_dots'] === 'yes';
        $navigation = $settings['navigation_arrows'] === 'yes';

        $id = 'bw-swiper-' . $this->get_id();
        ?>
        <div id="<?php echo esc_attr($id); ?>" class="bw-swiper swiper <?php echo 'bw-swiper-' . $type; ?>" <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             data-custom-dot-icon="<?php echo esc_url($dot_icon); ?>" style="--swiper-pagination-bullet-inactive-color:<?php echo $inactive_bullets_color ?>; --swiper-theme-color:<?php echo $active_bullets_color ?>;" <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
             data-slides-per-view="<?php echo esc_attr($slides_per_view); ?>"
             data-type="<?php echo esc_attr($type); ?>"
             data-slides-to-scroll="<?php echo esc_attr($slides_to_scroll); ?>"
             data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
             data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>"
             data-loop="<?php echo $loop ? 'true' : 'false'; ?>"
             data-pause-on-hover="<?php echo $pause_on_hover ? 'true' : 'false'; ?>"
             data-pagination="<?php echo $pagination ? 'true' : 'false'; ?>"
             data-navigation="<?php echo $navigation ? 'true' : 'false'; ?>"
        > <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <div class="bw-swiper-wrapper swiper-wrapper">
                <?php foreach ( $images as $item ): ?>
                <?php
                $item_type = isset( $item['item_type'] ) ? $item['item_type'] : 'image';
                ?>

                <div class="bw-swiper-slide swiper-slide <?php echo ($item_type == 'image' ) ? 'has-image' : 'has-template'; ?>"">
                <?php if ( $item_type === 'image' && ! empty( $item['image']['url'] ) ): ?>
                    <?php $alt = get_post_meta( $item['image']['id'], '_wp_attachment_image_alt', true ); ?>
                    <div class="bw-swiper-mask">
                        <img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" data-swiper-parallax-x="-50"> <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
                    </div>
                    <?php if ( $type == 'type1' && ! empty( $item['caption'] ) ): ?>
                        <div class="bw-swiper-caption"><?php echo esc_html( $item['caption'] ); ?></div>
                    <?php endif; ?>
                <?php elseif ( $item_type === 'template' && ! empty( $item['template_id'] ) && $item['template_id'] !== '0' ): ?>
                    <div class="bw-swiper-mask">
                        <?php
                        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item['template_id'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
        <?php if ( $pagination ): ?>
            <div class="bw-swiper-pagination swiper-pagination"></div>
        <?php endif; ?>

        <?php if ( $navigation ): ?>
            <div class="bw-swiper-button-prev swiper-button-prev">
                <?php if ( $prev_icon ) : ?>
                    <img src="<?php echo esc_url( $prev_icon ); ?>" alt="Prev" class="custom-swiper-arrow" /> <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
                <?php endif; ?>
            </div>
            <div class="bw-swiper-button-next swiper-button-next">
                <?php if ( $next_icon ) : ?>
                    <img src="<?php echo esc_url( $next_icon ); ?>" alt="Next" class="custom-swiper-arrow" /> <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        </div>
        <?php
    }
}
