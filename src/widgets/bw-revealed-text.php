<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

class RevealedText extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-revealed-text', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/revealed-text.css', [], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-revealed-text', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/revealed-text.js', ['jquery'], BLACK_WIDGETS_VERSION, true );
    }

    public function get_name() {
        return 'b_revealed_text';
    }

    public function get_title() {
        return __( 'Black Revealed Text', 'black-widgets' );
    }

    public function get_icon() {
        return 'eicon-wordart';
    }

    public function get_categories() {
        return [ 'black_widgets' ];
    }

    public function get_style_depends() {
        return [ 'black-widgets-revealed-text' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-revealed-text' ];
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
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
                'default' => esc_html__( 'Black Widget Revealed Text', 'black-widgets' ),
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
            'widget_animation_mode',
            [
                'label' => esc_html__('Animation Mode', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'word',
                'options' => [
                    'word' => esc_html__('Word by Word', 'black-widgets'),
                    'letter' => esc_html__('Letter by Letter', 'black-widgets'),
                ],
            ]
        );

        $this->add_control(
            'widget_effect',
            [
                'label' => esc_html__('Animation Effect', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'fade'   => esc_html__('Fade (only opacity)', 'black-widgets'),
                    'zoom'   => esc_html__('Zoom In', 'black-widgets'),
                    'blur'   => esc_html__('Unblur', 'black-widgets'),
                    'rotate' => esc_html__('Rotate', 'black-widgets'),
                ],
            ]
        );


        $this->add_control(
            'hr2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

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
                'default'   => 'center',
                'toggle'    => true,
                'selectors' => [
                    '{{WRAPPER}} .bw-revealed-text' => 'text-align: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .bw-revealed-text',
            ]
        );

        $this->add_control(
            'hr3',
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
                    '{{WRAPPER}} .bw-revealed-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .bw-revealed-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'name' => 'widget_box_border',
                'label' => esc_html__( 'Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-revealed-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-revealed-text',
            ]
        );

        $this->end_controls_section();

        // Start
        // Style section
        $this->start_controls_section(
            'typography1_section',
            [
                'label' => esc_html__( 'Typography', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widget_title_solid_color',
            [
                'label' => esc_html__( 'Title Color', 'black-widgets' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-revealed-text p' => 'color: {{VALUE}}; -webkit-text-fill-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography1',
                'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-revealed-text p',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow1',
                'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-revealed-text p',
            ]
        );

        $this->add_control(
            'hr5',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_typography_title_background',
                'label' => esc_html__( 'Title Background', 'black-widgets' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .bw-revealed-text p',
            ]
        );

        $this->add_control(
            'hr6',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        // Margin
        $this->add_responsive_control(
            'widget_typography_title_margin',
            [
                'label' => esc_html__( 'Title Margin', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-revealed-text p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .bw-revealed-text p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr7',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_typography_title_border',
                'label' => esc_html__( 'Title Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-revealed-text p',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'widget_typography_title_border_radius',
            [
                'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-revealed-text p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Box shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_typography_title_box_shadow',
                'label' => esc_html__( 'Title Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-revealed-text p',
            ]
        );

        $this->add_control(
            'stroke1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        // Enable Title Section
        $this->add_control(
            'widget_stroke_title_enable',
            [
                'label' 		=> esc_html__( 'Text Stroke', 'black-widgets' ),
                'type' 			=> \Elementor\Controls_Manager::SWITCHER,
                'label_on' 		=> esc_html__( 'Yes', 'black-widgets' ),
                'label_off' 	=> esc_html__( 'No !', 'black-widgets' ),
                'return_value' 	=> 'stroke_enable',
                // 'default' 		=> 'false',
            ]
        );

        $this->add_control(
            'widget_stroke_stroke_color',
            [
                'label' => esc_html__( 'Text Stroke Color', 'black-widgets' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-revealed-text p' => '-webkit-text-stroke-color: {{VALUE}}',
                ],
                'condition'  => [
                    'widget_stroke_title_enable' => [
                        'stroke_enable',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_stroke_stroke_width',
            [
                'label' => esc_html__( 'Text Stroke Size', 'black-widgets' ),
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
                    '{{WRAPPER}} .bw-revealed-text p' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition'  => [
                    'widget_stroke_title_enable' => [
                        'stroke_enable',
                    ],
                ],
            ]
        );

        $this->add_control(
            'gradient_color1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'gradient_color_title_enable',
            [
                'label'        => esc_html__( 'Text Gradient/Image', 'black-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'black-widgets' ),
                'label_off'    => esc_html__( 'No', 'black-widgets' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'unique_widget_typography_title_gradient',
                'label' => esc_html__( 'Title Background', 'black-widgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-revealed-text p span',
                'condition'  => [
                    'gradient_color_title_enable' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
        // End
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $text = isset($settings['widget_text']) ? $settings['widget_text'] : '';
        $mode = isset($settings['widget_animation_mode']) ? $settings['widget_animation_mode'] : 'word';
        $effect = isset($settings['widget_effect']) ? $settings['widget_effect'] : 'fade';
        $gradient_class = $settings['gradient_color_title_enable'] === 'yes' ? 'bw-gradient-text' : '';
        ?>
        <div class="bw-revealed-text <?php echo esc_attr($gradient_class); ?>"
             data-mode="<?php echo esc_attr($mode); ?>"
             data-effect="<?php echo esc_attr($effect); ?>">
            <p><?php echo esc_html($text); ?></p>
        </div>
        <?php
    }
}
