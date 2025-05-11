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

    protected $gsap_enabled = false;

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-text-marquee', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/text-marquee.css', [], BLACK_WIDGETS_VERSION );

        $deps = [ 'jquery' ];

        $options = get_option( 'plugin_options' ) ? get_option( 'plugin_options' ) : '';
        $gsap_options  = isset( $options['gsap_options'] ) ? $options['gsap_options'] : '';
        $bw_gsap_cdn1  = isset( $options['bw_gsap_cdn1'] ) ? $options['bw_gsap_cdn1'] : '';
        $bw_gsap_cdn2  = isset( $options['bw_gsap_cdn2'] ) ? $options['bw_gsap_cdn2'] : '';

        if( isset( $gsap_options ) && ! empty( $gsap_options ) ) {
            if ( isset( $bw_gsap_cdn1 ) && ! empty( $bw_gsap_cdn1 ) &&
                isset( $bw_gsap_cdn2 ) && ! empty( $bw_gsap_cdn2 ) ) {
                array_push( $deps, 'GSAP' );
                array_push( $deps, 'GSAP-ScrollTrigger' );
                $this->gsap_enabled = true;
            }
        }

        wp_register_script( 'black-widgets-text-marquee', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/text-marquee.js', $deps, BLACK_WIDGETS_VERSION );
    }

    public function get_name() {
        return 'b_text_marquee';
    }

    public function get_title() {
        return __( 'Black Text Marquee', 'black-widgets' );
    }

    public function get_icon() {
        return 'eicon-font';
    }

    public function get_categories() {
        return [ 'black_widgets' ];
    }

    public function get_style_depends() {
        return [ 'black-widgets-text-marquee' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-text-marquee' ];
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
            'widget_text',
            [
                'label' => esc_html__( 'Text', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'rows' => 10,
                'default' => esc_html__( 'Black Widget Text Marquee', 'black-widgets' ),
                'placeholder' => esc_html__( 'Type your text here', 'black-widgets' ),
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
                'label' => esc_html__( 'Type', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'type1',
                'options' => [
                    'type1'   =>  esc_html__( 'Type 1', 'black-widgets' ),
                    'type2'   =>  esc_html__( 'Type 2', 'black-widgets' ),
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
                    'ltr'   =>  esc_html__( 'Left To Right', 'black-widgets' ),
                    'rtl'   =>  esc_html__( 'Right To Left', 'black-widgets' ),
                ]
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
        }

        $this->add_control(
            'widget_duration',
            [
                'label' => esc_html__( 'Duration (s)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'condition' => [
                    'widget_mos!' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'widget_gap',
            [
                'label' => esc_html__( 'GAP (%)', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'widget_mos!' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee' => 'gap: {{VALUE}}%;',
                ],
            ]
        );


        $this->add_control(
            'widget_gsap_start',
            [
                'label' => esc_html__( 'GSAP start condition', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '0', 'black-widgets' ),
                'condition' => [
                    'widget_mos' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'widget_gsap_end',
            [
                'label' => esc_html__( 'GSAP end condition', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '0', 'black-widgets' ),
                'condition' => [
                    'widget_mos' => 'yes',
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
                'selector' => '{{WRAPPER}} .bw-text-marquee',
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
                    '{{WRAPPER}} .bw-text-marquee' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .bw-text-marquee' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .bw-text-marquee',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-text-marquee',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'typography_section',
            [
                'label' => esc_html__( 'Typography', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widget_typography_text_color',
            [
                'label' => esc_html__( 'Color', 'black-widgets' ),
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
                'label' => esc_html__( 'Typography', 'black-widgets' ),
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
                'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-text-marquee-content ',
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
                'name' => 'widget_typography_text_background',
                'label' => esc_html__( 'Text Background', 'black-widgets' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .bw-text-marquee-content',
            ]
        );

        $this->add_control(
            'hr5',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'widget_typography_text_margin',
            [
                'label' => esc_html__( 'Text Margin', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_typography_title_padding',
            [
                'label' => esc_html__( 'Title Padding', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'name' => 'widget_typography_title_border',
                'label' => esc_html__( 'Title Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-text-marquee-content',
            ]
        );

        $this->add_control(
            'widget_typography_title_border_radius',
            [
                'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-marquee-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );	

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_typography_title_box_shadow',
                'label' => esc_html__( 'Title Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-text-marquee-content',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $text = isset( $settings['widget_text'] ) ? esc_html( $settings['widget_text'] ) : '';
        $direction = isset( $settings['widget_direction'] ) ? $settings['widget_direction'] : 'ltr';
        $duration = isset( $settings['widget_duration'] ) ? (float) $settings['widget_duration'] : '10';
        $gap = isset( $settings['widget_gap'] ) ? (float) $settings['widget_gap'] : '0';

        $start = isset( $settings['widget_gsap_start'] ) ? esc_attr( $settings['widget_gsap_start'] ) : '';
        $end = isset( $settings['widget_gsap_end'] ) ? esc_attr( $settings['widget_gsap_end'] ) : '';

        $type = isset( $settings['widget_type'] ) ? $settings['widget_type'] : 'type1';

        $classes = 'bw-text-marquee';
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

        if ( $direction == 'ltr' ) {
            $classes .= ' bw-reverse';
        }

        if ( $type !== 'type1' ) {
            $type = 'type2';
        }

?>
        <div class="<?php echo $classes; ?>" data-start="<?php echo $start;?>" data-end="<?php echo $end;?>" data-direction="<?php echo $direction;?>" data-duration="<?php echo $duration; ?>" data-gap="<?php echo $gap; ?>"> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <div class="bw-text-marquee-content bw-text-marquee-content-<?php echo $type; ?>">
                <span class="bw-text-marquee-text"><?php echo $text; ?></span>
            </div>            
            <?php if ( ! $mos ): ?>
            <div class="bw-text-marquee-content bw-text-marquee-content-<?php echo $type; ?>" aria-hidden="true">
                <span class="bw-text-marquee-text"><?php echo $text; ?></span>
            </div>
            <?php endif; ?>
        </div>
<?php
    }
}
