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

class TextAnimate extends \Elementor\Widget_Base {


    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-text-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/text-animate.css', [], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-text-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/text-animate.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION );
    }

    public function get_name() {
        return 'b_text_animate';
    }

    public function get_title() {
        return __( 'Black Text Animate', 'black-widgets' );
    }

    public function get_icon() {
        return 'eicon-animation-text';
    }

    public function get_categories() {
        return [ 'black_widgets' ];
    }

    public function get_style_depends() {
        return [ 'black-widgets-text-animate' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-text-animate' ];
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
                'default' => esc_html__( 'Black Widget Text Animate', 'black-widgets' ),
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
            'widget_split',
            [
                'label' => esc_html__( 'Split by', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'   =>  esc_html__( 'None', 'black-widgets' ),
                    'letter'   =>  esc_html__( 'Letter', 'black-widgets' ),
                    'word'   =>  esc_html__( 'Word', 'black-widgets' ),
                ]
            ]
        );

        $this->add_control(
            'widget_animation',
            [
                'label' => esc_html__( 'Animation', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ftop',
                'options' => [
                    'ftop'   =>  esc_html__( 'From Top', 'black-widgets' ),
                    'fbottom'   =>  esc_html__( 'From Bottom', 'black-widgets' ),
                    'fleft'   =>  esc_html__( 'From Left', 'black-widgets' ),
                    'fright'   =>  esc_html__( 'From Right', 'black-widgets' ),
                    'fin'   =>  esc_html__( 'Fade In', 'black-widgets' ),
                ]
            ]
        );

        $this->add_control(
			'widget_delay',
			[
				'label' => esc_html__( 'Delay (ms)', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'step' => 100,
				'default' => 500,
			]
		);

        $this->add_control(
            'hr2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
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
                'selector' => '{{WRAPPER}} .bw-text-animate',
            ]
        );

        $this->add_control(
            'hr3',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'widget_margin',
            [
                'label' => esc_html__( 'Margin', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-animate' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_padding',
            [
                'label' => esc_html__( 'Padding', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-animate' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr4',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_border',
                'label' => esc_html__( 'Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-text-animate',
            ]
        );

        $this->add_control(
            'widget_border_radius',
            [
                'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-animate' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );	

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-text-animate',
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
            'widget_text_color',
            [
                'label' => esc_html__( 'Color', 'black-widgets' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-animate' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'widget_typography',
                'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-text-animate',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'widget_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-text-animate ',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $text = isset( $settings['widget_text'] ) ? esc_html( $settings['widget_text'] ) : '';
        $split = isset( $settings['widget_split'] ) ? esc_attr( $settings['widget_split'] ) : 'none';
        $animation = isset( $settings['widget_animation'] ) ? esc_attr( $settings['widget_animation'] ) : 'ftop';
        $delay = isset( $settings['widget_delay'] ) ? esc_attr( $settings['widget_delay'] ) : '500';
?>
        <div class="bw-text-animate" data-split="<?php echo $split; ?>" data-animation="<?php echo $animation; ?>" data-delay="<?php echo $delay; ?>"> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $text; ?> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
<?php
    }
}
