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

class ImageMarquee extends \Elementor\Widget_Base {

    protected $gsap_enabled = false;

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-image-marquee', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/image-marquee.css', [], BLACK_WIDGETS_VERSION );

        $deps = [ 'jquery' ];

        $options = get_option( 'plugin_options' ) ? get_option( 'plugin_options' ) : '';
        $gsap_options  = isset( $options['gsap_options'] ) ? $options['gsap_options'] : '';
        $bw_gsap_cdn1  = isset( $options['bw_gsap_cdn1'] ) ? $options['bw_gsap_cdn1'] : '';
        $bw_gsap_cdn2  = isset( $options['bw_gsap_cdn2'] ) ? $options['bw_gsap_cdn2'] : '';

        if( isset( $gsap_options ) && ! empty( $gsap_options) ) {
            if ( isset( $bw_gsap_cdn1 ) && ! empty( $bw_gsap_cdn1 ) &&
                isset( $bw_gsap_cdn2 ) && ! empty( $bw_gsap_cdn2 ) ) {
                array_push( $deps, 'GSAP' );
                array_push( $deps, 'GSAP-ScrollTrigger' );
                $this->gsap_enabled = true;
            }
        }

        wp_register_script( 'black-widgets-image-marquee', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/image-marquee.js', $deps, BLACK_WIDGETS_VERSION );
    }

    public function get_name() {
        return 'b_image_marquee';
    }

    public function get_title() {
        return __( 'Black Image Marquee', 'black-widgets' );
    }

    public function get_icon() {
        return 'eicon-image-before-after';
    }

    public function get_categories() {
        return [ 'black_widgets' ];
    }

    public function get_style_depends() {
        return [ 'black-widgets-image-marquee' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-image-marquee' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'widget_images',
            [
                'label' => esc_html__( 'Add Images', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'show_label' => false,
                'default' => [],
            ]
        );

        $this->add_control(
            'hr1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'widget_animation_type',
            [
                'label' => esc_html__( 'Animation Type', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'type1',
                'options' => [
                    'type1'   =>  esc_html__( 'Type 1', 'black-widgets' ),
                    'type2'   =>  esc_html__( 'Type 2', 'black-widgets' ),
                    'type3'   =>  esc_html__( 'Type 3', 'black-widgets' ),
                ]
            ]
        );

        $this->add_control(
            'widget_direction',
            [
                'label' => esc_html__( 'Direction', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ltr',
                'options' => [
                    'ltr'   =>  esc_html__( 'Left To Right (Up To Down)', 'black-widgets' ),
                    'rtl'   =>  esc_html__( 'Right To Left (Down To Up)', 'black-widgets' ),
                ]
            ]
        );

        $this->add_control(
            'widget_max_height',
            [
                'label' => esc_html__( 'Max Height', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-thumb' => 'max-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bw-image-marquee-ver' => 'max-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr7',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        if ( $this->gsap_enabled ) {
            $this->add_control(
                'widget_mos',
                [
                    'label' => esc_html__( 'Move on Scroll', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'On', 'black-widgets' ),
                    'label_off' => esc_html__( 'Off', 'black-widgets' ),
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'widget_gsap_start',
                [
                    'label' => esc_html__( 'GSAP start condition', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'top center', 'black-widgets' ),
                ]
            );

            $this->add_control(
                'widget_gsap_end',
                [
                    'label' => esc_html__( 'GSAP end condition', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'bottom center', 'black-widgets' ),
                ]
            );
        }

        $this->add_control(
            'widget_duration',
            [
                'label' => esc_html__( 'Duration (s)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
            ]
        );

        $this->add_control(
            'widget_gap',
            [
                'label' => esc_html__( 'GAP (px)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'selectors' => [
                    '{{WRAPPER}} .bw-image-marquee-content' => 'gap: {{VALUE}}px;',
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
                'selector' => '{{WRAPPER}} .bw-image-marquee',
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
                    '{{WRAPPER}} .bw-image-marquee' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .bw-image-marquee' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .bw-image-marquee',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-image-marquee',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $images = isset( $settings['widget_images'] ) ? $settings['widget_images'] : [];

        $direction = isset( $settings['widget_direction'] ) ? $settings['widget_direction'] : 'ltr';
        $duration = isset( $settings['widget_duration'] ) ? (float) $settings['widget_duration'] : '10';

        $start = isset( $settings['widget_gsap_start'] ) ? esc_attr( $settings['widget_gsap_start'] ) : '';
        $end = isset( $settings['widget_gsap_end'] ) ? esc_attr( $settings['widget_gsap_end'] ) : '';

        $animation = isset( $settings['widget_animation_type'] ) ? esc_attr( $settings['widget_animation_type'] ) : 'type1' ;

        $classes = 'bw-image-marquee';
        $mos = false;
        if ( isset( $settings['widget_mos'] ) && $settings['widget_mos'] == 'yes' ) {
            $mos = true;
        }

        if ( $mos ) {
            $classes .= ' bw-mos'; // mos: Move on Scroll
        }

        if ( ! in_array( $direction, ['ltr', 'rtl'] ) ) {
            $direction = 'ltr';
        }

        if ( $animation == 'type3' ) {
            $classes .= ' bw-image-marquee-ver';
            $movement = 'vertical';
        } else {
            $classes .= ' bw-image-marquee-hor';
            $movement = 'horizontal';
        }

        if ( $direction == 'ltr' ) {
            $classes .= ' bw-reverse';
        }

?>
        <div class="<?php echo $classes; ?>" data-start="<?php echo $start;?>" data-end="<?php echo $end;?>" data-direction="<?php echo $direction;?>" data-duration="<?php echo $duration; ?>"> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="bw-image-marquee-content" data-movement="<?php echo $movement; ?>">
                <?php foreach ( $images as $index => $image ): ?>
                <?php if ( $index % 2 == 0 && isset($images[$index + 1]) && $animation == 'type2' ): ?>
                <div class="bw-image-marquee-thumb bw-image-marquee-type2">
                    <img alt="" src="<?php echo esc_url( $image['url'] ); ?>"/>
                    <img alt="" src="<?php echo esc_url( $images[$index + 1]['url'] ); ?>"/>
                </div>
                <?php $index++; ?>
                <?php else: ?>
                <div class="bw-image-marquee-thumb">
                    <img alt="" src="<?php echo esc_url( $image['url'] ); ?>"/>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php if ( ! $mos ): ?>
                <?php foreach ( $images as $index => $image ): ?>
                <?php if ( $index % 2 == 0 && isset($images[$index + 1]) && $animation == 'type2' ): ?>
                <div class="bw-image-marquee-thumb bw-image-marquee-type2">
                    <img alt="" src="<?php echo esc_url( $image['url'] ); ?>"/>
                    <img alt="" src="<?php echo esc_url( $images[$index + 1]['url'] ); ?>"/>
                </div>
                <?php $index++; ?>
                <?php else: ?>
                <div class="bw-image-marquee-thumb">
                    <img alt="" src="<?php echo esc_url( $image['url'] ); ?>"/>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
<?php
    }
}
