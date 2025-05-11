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

class ImageCarousel extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-image-carousel', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/image-carousel.css', [], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-image-carousel', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/image-carousel.js', [ 'jquery', 'swiper' ], BLACK_WIDGETS_VERSION );
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
        return [ 'black-widgets-image-carousel' ];
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
                'condition' => [
                    'widget_type' => [ 'type3', 'type4' ],
                ],
            ]
		);

        $this->add_control(
			'widget_inactive_bullets_color',
			[
				'label' => esc_html__( 'Inactive Bullets Color', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
			]
		);

        $this->add_control(
			'widget_active_bullets_color',
			[
				'label' => esc_html__( 'Active Bullets Color', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
					'{{WRAPPER}} .bw-swiper-type3 .bw-swiper-pagination .swiper-pagination-bullet-active svg' => 'stroke: {{VALUE}}',
					'{{WRAPPER}} .bw-swiper-type4 .swiper-pagination-bullet-active' => 'background: {{VALUE}}',
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
					'{{WRAPPER}} .bw-swiper-type4 .bw-swiper-pagination .swiper-pagination-bullet-active' => 'border-color: {{VALUE}}',
				],
                'condition' => [
                    'widget_type' => 'type4',
                ],
			]
		);

        $this->add_control(
            'hr4',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
                'condition' => [
                    'widget_type' => 'type3',
                ],
            ]
        );

        $this->add_control(
			'widget_arrows_background_color',
			[
				'label' => esc_html__( 'Arrows Background Color', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
					'{{WRAPPER}} .bw-swiper-type3 .bw-swiper-button-prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .bw-swiper-type3 .bw-swiper-button-next' => 'background-color: {{VALUE}}',
				],
                'condition' => [
                    'widget_type' => 'type3',
                ],
			]
		);

        $this->add_control(
			'widget_arrows_color',
			[
				'label' => esc_html__( 'Arrows Background Color', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
					'{{WRAPPER}} .bw-swiper-type3 .bw-swiper-button-prev' => 'color: {{VALUE}}',
					'{{WRAPPER}} .bw-swiper-type3 .bw-swiper-button-next' => 'color: {{VALUE}}',
				],
                'condition' => [
                    'widget_type' => 'type3',
                ],
			]
		);

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $images = isset( $settings['widget_images'] ) ? $settings['widget_images'] : [];
        $type = isset( $settings['widget_type'] ) ? $settings['widget_type'] : 'type1';

        if ( ! in_array( $type, [ 'type1', 'type2', 'type3', 'type4' ] ) ) {
            $type = 'type1';
        }

        $inactive_bullets_color = isset( $settings['widget_inactive_bullets_color'] ) ? esc_attr( $settings['widget_inactive_bullets_color'] ) : '#fff' ;
        $active_bullets_color = isset( $settings['widget_active_bullets_color'] ) ? esc_attr( $settings['widget_active_bullets_color'] ) : '#fff' ;

?>
    <div class="bw-swiper swiper <?php echo 'bw-swiper-' . $type; ?>" style="--swiper-pagination-bullet-inactive-color:<?php echo $inactive_bullets_color ?>; --swiper-theme-color:<?php echo $active_bullets_color ?>;"> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <div class="bw-swiper-wrapper swiper-wrapper">
            <?php foreach( $images as $image ): ?>
            <?php $alt = get_post_meta( $image['id'], '_wp_attachment_image_alt', true ); ?>
            <div class="bw-swiper-slide swiper-slide">
                <div class="bw-swiper-mask">
                    <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" data-swiper-parallax-x="-50">
                </div>
                <?php if ( $type == 'type1' ): ?>
                <?php $caption = wp_get_attachment_caption( $image['id'] ); ?>
                <div class="bw-swiper-caption"><?php echo esc_html( $caption ); ?></div>
                <?php endif;?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if ( $type == 'type3' || $type == 'type4' ): ?>
        <div class="bw-swiper-pagination swiper-pagination"></div>
        <?php endif; ?>

        <?php if ( $type == 'type3' ): ?>
        <div class="bw-swiper-button-prev swiper-button-prev"></div>
        <div class="bw-swiper-button-next swiper-button-next"></div>
        <?php endif; ?>
    </div>
<?php
    }
}
