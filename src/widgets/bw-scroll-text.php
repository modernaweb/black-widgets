<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * Black Scroll Text - extracted On Scroll + Movement features from Typography.
 *
 * Existing Typography Type-4 instances keep working (BC). New projects should use this widget.
 *
 * @since 1.4.0
 */
class ScrollText extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        // Reuse Typography styles + FX script (same markup contract).
        wp_register_style( 'black-widgets-typography', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/typography.css', [], BLACK_WIDGETS_VERSION );

        if ( $this->is_gsap_enabled() && ! wp_script_is( 'black-widgets-typography', 'registered' ) ) {
            \Modernaweb\BlackWidgets\Plugin_Options::register_gsap_scripts();
            $typo_deps = [ 'jquery', 'GSAP', 'GSAP-ScrollTrigger' ];
            if ( \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_split_ready() ) {
                $typo_deps[] = 'GSAP-SplitText';
            }
            wp_register_script(
                'black-widgets-typography',
                BLACK_WIDGETS_PLUGIN_URL . 'assets/js/typography.js',
                $typo_deps,
                BLACK_WIDGETS_VERSION,
                true
            );
        }
    }

    public function get_name() {
        return 'b_scroll_text';
    }

    public function get_title() {
        return __( 'Black Scroll Text', 'blackwidgets' );
    }

    public function get_icon() {
        return 'eicon-editor-paragraph';
    }

    public function get_categories() {
        return [ 'black_widgets' ];
    }

    public function get_keywords() {
        return [ 'scroll', 'text', 'gsap', 'typography', 'animate' ];
    }

    public function get_style_depends() {
        return [ 'black-widgets-typography' ];
    }

    public function get_script_depends() {
        if ( ! $this->is_gsap_enabled() ) {
            return [];
        }

        \Modernaweb\BlackWidgets\Plugin_Options::register_gsap_scripts();

        // Do not deregister shared handles. List GSAP explicitly so Elementor
        // enqueues the chain even if this widget registered before CDN handles.
        $deps = [ 'black-widgets-typography', 'GSAP', 'GSAP-ScrollTrigger' ];
        if ( \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_split_ready() && wp_script_is( 'GSAP-SplitText', 'registered' ) ) {
            $deps[] = 'GSAP-SplitText';
        }
        return $deps;
    }

    public function is_gsap_enabled() {
        return \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_ready();
    }

    /**
     * @return array<string, string>
     */
    protected function get_scroll_anim_options() {
        $options = [
            'none'          => esc_html__( 'None', 'blackwidgets' ),
            'bw-scroll-e-1' => esc_html__( 'Animate 1', 'blackwidgets' ),
            'bw-scroll-e-2' => esc_html__( 'Animate 2', 'blackwidgets' ),
            'bw-scroll-e-3' => esc_html__( 'Animate 3', 'blackwidgets' ),
            'bw-scroll-e-4' => esc_html__( 'Animate 4', 'blackwidgets' ),
            'bw-scroll-e-5' => esc_html__( 'Animate 5', 'blackwidgets' ),
            'bw-scroll-e-6' => esc_html__( 'Animate 6', 'blackwidgets' ),
            'bw-scroll-e-7' => esc_html__( 'Animate 7', 'blackwidgets' ),
            'bw-scroll-e-8' => esc_html__( 'Animate 8', 'blackwidgets' ),
        ];

        if ( \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_split_ready() ) {
            $options['bw-scroll-e-9']  = esc_html__( 'Mask Rise (SplitText)', 'blackwidgets' );
            $options['bw-scroll-e-10'] = esc_html__( 'Word Cascade Blur', 'blackwidgets' );
            $options['bw-scroll-e-11'] = esc_html__( 'Char Wave 3D', 'blackwidgets' );
            $options['bw-scroll-e-12'] = esc_html__( 'Clip Wipe', 'blackwidgets' );
        }

        return $options;
    }

    /**
     * Allowed inline HTML inside the title field.
     *
     * @return array<string, array<string, bool>>
     */
    private function allowed_inline_html() {
        return [
            'br'     => [],
            'hr'     => [],
            'strong' => [],
            'b'      => [],
            'em'     => [],
            'i'      => [],
            'u'      => [],
            'span'   => [ 'class' => true ],
            'mark'   => [],
            'small'  => [],
            'sub'    => [],
            'sup'    => [],
        ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'blackwidgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'scroll_text_info',
            [
                'type'       => Controls_Manager::ALERT,
                'alert_type' => 'info',
                'heading'    => esc_html__( 'Scroll Text', 'blackwidgets' ),
                'content'    => esc_html__( 'Dedicated On Scroll + Movement widget extracted from Typography. Existing Typography “On Scroll” instances keep working.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'widget_title',
            [
                'label'       => esc_html__( 'Title', 'blackwidgets' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 4,
                'default'     => esc_html__( 'Black Scroll Text', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here (HTML + line breaks allowed)', 'blackwidgets' ),
                'description' => esc_html__( 'Allowed inline tags: strong, b, i, em, u, br, hr, span, mark, small, sub, sup.', 'blackwidgets' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'widget_html_tag_title',
            [
                'label'   => esc_html__( 'HTML Tag', 'blackwidgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'div'  => esc_html__( 'div', 'blackwidgets' ),
                    'h1'   => esc_html__( 'H1', 'blackwidgets' ),
                    'h2'   => esc_html__( 'H2', 'blackwidgets' ),
                    'h3'   => esc_html__( 'H3', 'blackwidgets' ),
                    'h4'   => esc_html__( 'H4', 'blackwidgets' ),
                    'h5'   => esc_html__( 'H5', 'blackwidgets' ),
                    'h6'   => esc_html__( 'H6', 'blackwidgets' ),
                    'p'    => esc_html__( 'p', 'blackwidgets' ),
                    'span' => esc_html__( 'span', 'blackwidgets' ),
                ],
                'description' => esc_html__( 'Wrapper tag for SEO and document structure.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'scroll_text_link',
            [
                'label'       => esc_html__( 'Link', 'blackwidgets' ),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'blackwidgets' ),
                'options'     => [ 'url', 'is_external', 'nofollow' ],
                'default'     => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'widget_type_4',
            [
                'label'   => esc_html__( 'Animate On Scroll', 'blackwidgets' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => $this->get_scroll_anim_options(),
            ]
        );

        $this->add_control(
            'animate_split_unit',
            [
                'label'       => esc_html__( 'Animate By', 'blackwidgets' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'chars',
                'options'     => [
                    'chars' => esc_html__( 'Characters', 'blackwidgets' ),
                    'words' => esc_html__( 'Words', 'blackwidgets' ),
                ],
                'description' => esc_html__( 'Split and stagger the same animation by character or by word.', 'blackwidgets' ),
                'condition'   => [
                    'widget_type_4!' => [ 'none', 'bw-scroll-e-10', 'bw-scroll-e-12' ],
                ],
            ]
        );

        $this->add_control(
            'animate_start_delay',
            [
                'label'       => esc_html__( 'Animation Delay', 'blackwidgets' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 100,
                'min'         => 0,
                'max'         => 5000,
                'step'        => 50,
                'description' => esc_html__( 'Wait (ms) after the text enters the viewport before the whole animation starts — not per character/word. 100 is subtle; try 300 for a clearer pause. Ignored while Scrub is on.', 'blackwidgets' ),
                'condition'   => [
                    'widget_type_4!' => 'none',
                    'title_scrub!'   => 'scrub_mode',
                ],
            ]
        );

        $this->add_control(
            'title_scrub',
            [
                'label'        => esc_html__( 'Scrub on scrolling up and down', 'blackwidgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Enable', 'blackwidgets' ),
                'label_off'    => esc_html__( 'Disable', 'blackwidgets' ),
                'return_value' => 'scrub_mode',
                'default'      => 'off',
                'condition'    => [
                    'widget_type_4!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'title_replay',
            [
                'label'        => esc_html__( 'Replay when entering view again', 'blackwidgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Enable', 'blackwidgets' ),
                'label_off'    => esc_html__( 'Disable', 'blackwidgets' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'Play the animation again each time the text re-enters the viewport (for example after scrolling back to the top). Separate from Scrub.', 'blackwidgets' ),
                'condition'    => [
                    'widget_type_4!' => 'none',
                    'title_scrub!'   => 'scrub_mode',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'movement_section',
            [
                'label' => esc_html__( 'Text Movement', 'blackwidgets' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'text_movement2',
            [
                'label'        => esc_html__( 'Text Movement Animate → From', 'blackwidgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Enable', 'blackwidgets' ),
                'label_off'    => esc_html__( 'Disable', 'blackwidgets' ),
                'return_value' => 'on',
                'default'      => 'off',
            ]
        );

        $this->add_control(
            'duration2',
            [
                'label'       => esc_html__( 'Duration', 'blackwidgets' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '0.4',
                'default'     => '0.4',
                'condition'   => [ 'text_movement2' => 'on' ],
            ]
        );

        $this->add_control(
            'vertical_movement2',
            [
                'label'     => esc_html__( 'Vertical Movement', 'blackwidgets' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [ 'text_movement2' => 'on' ],
            ]
        );

        $this->add_control(
            'horizontal_movement2',
            [
                'label'     => esc_html__( 'Horizontal Movement', 'blackwidgets' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [ 'text_movement2' => 'on' ],
            ]
        );

        $this->add_control(
            'opacity2',
            [
                'label'     => esc_html__( 'Opacity at End', 'blackwidgets' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [ 'text_movement2' => 'on' ],
            ]
        );

        $this->add_control(
            'rotation2',
            [
                'label'     => esc_html__( 'Rotation at End', 'blackwidgets' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [ 'text_movement2' => 'on' ],
            ]
        );

        $this->add_control(
            'text_movement',
            [
                'label'        => esc_html__( 'Text Movement Animate → To', 'blackwidgets' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Enable', 'blackwidgets' ),
                'label_off'    => esc_html__( 'Disable', 'blackwidgets' ),
                'return_value' => 'on',
                'default'      => 'off',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'duration',
            [
                'label'       => esc_html__( 'Duration', 'blackwidgets' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '0.4',
                'default'     => '0.4',
                'condition'   => [ 'text_movement' => 'on' ],
            ]
        );

        $this->add_control(
            'vertical_movement',
            [
                'label'     => esc_html__( 'Vertical Movement', 'blackwidgets' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [ 'text_movement' => 'on' ],
            ]
        );

        $this->add_control(
            'horizontal_movement',
            [
                'label'     => esc_html__( 'Horizontal Movement', 'blackwidgets' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [ 'text_movement' => 'on' ],
            ]
        );

        $this->add_control(
            'opacity',
            [
                'label'     => esc_html__( 'Opacity at End', 'blackwidgets' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [ 'text_movement' => 'on' ],
            ]
        );

        $this->add_control(
            'rotation',
            [
                'label'     => esc_html__( 'Rotation at End', 'blackwidgets' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [ 'text_movement' => 'on' ],
            ]
        );

        $this->end_controls_section();

        // Box Style
        $this->start_controls_section(
            'box_style_section',
            [
                'label' => esc_html__( 'Box Style', 'blackwidgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'scroll_box_tabs' );

        $this->start_controls_tab(
            'scroll_box_normal',
            [ 'label' => esc_html__( 'Normal', 'blackwidgets' ) ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'scroll_box_background',
                'label'    => esc_html__( 'Background', 'blackwidgets' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-scroll-text-box',
            ]
        );

        $this->add_responsive_control(
            'scroll_box_margin',
            [
                'label'      => esc_html__( 'Margin', 'blackwidgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-scroll-text-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'scroll_box_padding',
            [
                'label'      => esc_html__( 'Padding', 'blackwidgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-scroll-text-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'scroll_box_border',
                'label'    => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-scroll-text-box',
            ]
        );

        $this->add_responsive_control(
            'scroll_box_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'blackwidgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-scroll-text-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'scroll_box_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-scroll-text-box',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'scroll_box_hover',
            [ 'label' => esc_html__( 'Hover', 'blackwidgets' ) ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'scroll_box_background_hover',
                'label'    => esc_html__( 'Background', 'blackwidgets' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-scroll-text-box:hover',
            ]
        );

        $this->add_control(
            'scroll_box_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'blackwidgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-scroll-text-box:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'scroll_box_box_shadow_hover',
                'label'    => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-scroll-text-box:hover',
            ]
        );

        $this->add_control(
            'scroll_box_transition',
            [
                'label'      => esc_html__( 'Transition Duration (ms)', 'blackwidgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range'      => [
                    'ms' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                ],
                'default'    => [
                    'unit' => 'ms',
                    'size' => 300,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-scroll-text-box' => 'transition: background {{SIZE}}{{UNIT}} ease, border-color {{SIZE}}{{UNIT}} ease, box-shadow {{SIZE}}{{UNIT}} ease;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        // Text Style
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Text Style', 'blackwidgets' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'scroll_text_tabs' );

        $this->start_controls_tab(
            'scroll_text_normal',
            [ 'label' => esc_html__( 'Normal', 'blackwidgets' ) ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'scroll_text_typography',
                'label'    => esc_html__( 'Typography', 'blackwidgets' ),
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-animate .bw-line, {{WRAPPER}} .bw-typograpgy-animate .bw-word, {{WRAPPER}} .bw-typograpgy-animate .bw-char, {{WRAPPER}} .bw-typograpgy-animate .char',
            ]
        );

        $this->add_control(
            'scroll_text_color',
            [
                'label'     => esc_html__( 'Color', 'blackwidgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-typograpgy-animate' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-typograpgy-animate .bw-line' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-typograpgy-animate .bw-word' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-typograpgy-animate .bw-char' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-typograpgy-animate .char' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-scroll-text-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'scroll_text_shadow',
                'label'    => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-animate .bw-char, {{WRAPPER}} .bw-typograpgy-animate .bw-word, {{WRAPPER}} .bw-typograpgy-animate .char',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'scroll_text_background',
                'label'    => esc_html__( 'Text Background', 'blackwidgets' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-typograpgy-animate',
            ]
        );

        $this->add_responsive_control(
            'scroll_text_margin',
            [
                'label'      => esc_html__( 'Margin', 'blackwidgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-typograpgy-animate' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'scroll_text_padding',
            [
                'label'      => esc_html__( 'Padding', 'blackwidgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-typograpgy-animate' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'scroll_text_border',
                'label'    => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-typograpgy-animate',
            ]
        );

        $this->add_responsive_control(
            'scroll_text_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'blackwidgets' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-typograpgy-animate' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'scroll_text_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-typograpgy-animate',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'scroll_text_hover',
            [ 'label' => esc_html__( 'Hover', 'blackwidgets' ) ]
        );

        $this->add_control(
            'scroll_text_color_hover',
            [
                'label'     => esc_html__( 'Color', 'blackwidgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate .bw-line' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate .bw-word' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate .bw-char' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate .char' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-scroll-text-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'scroll_text_shadow_hover',
                'label'    => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate, {{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate .bw-char, {{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate .char',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'scroll_text_background_hover',
                'label'    => esc_html__( 'Text Background', 'blackwidgets' ),
                'types'    => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate',
            ]
        );

        $this->add_control(
            'scroll_text_border_color_hover',
            [
                'label'     => esc_html__( 'Border Color', 'blackwidgets' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'scroll_text_box_shadow_hover',
                'label'    => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-scroll-text-box:hover .bw-typograpgy-animate',
            ]
        );

        $this->add_control(
            'scroll_text_transition',
            [
                'label'      => esc_html__( 'Transition Duration (ms)', 'blackwidgets' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'ms' ],
                'range'      => [
                    'ms' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                ],
                'default'    => [
                    'unit' => 'ms',
                    'size' => 300,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .bw-typograpgy-animate' => 'transition: color {{SIZE}}{{UNIT}} ease, background {{SIZE}}{{UNIT}} ease, border-color {{SIZE}}{{UNIT}} ease, box-shadow {{SIZE}}{{UNIT}} ease, text-shadow {{SIZE}}{{UNIT}} ease;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'scroll_text_align',
            [
                'label'     => esc_html__( 'Alignment', 'blackwidgets' ),
                'type'      => Controls_Manager::CHOOSE,
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
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .bw-typograpgy' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .bw-typograpgy-wrap' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .bw-typograpgy-animate' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .bw-scroll-text-box' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Collect one side (from/to) of the Text Movement settings.
     *
     * @param array  $settings Widget settings.
     * @param string $suffix   '' for the "to" group, '2' for the "from" group.
     * @return array<string, string>|null
     */
    private function movement_config( $settings, $suffix ) {
        $config = [];

        foreach ( [
            'x'        => 'horizontal_movement',
            'y'        => 'vertical_movement',
            'opacity'  => 'opacity',
            'rotation' => 'rotation',
        ] as $key => $control ) {
            $value = isset( $settings[ $control . $suffix ] ) ? trim( (string) $settings[ $control . $suffix ] ) : '';
            if ( '' !== $value ) {
                $config[ $key ] = $value;
            }
        }

        if ( empty( $config ) ) {
            return null;
        }

        $duration           = isset( $settings[ 'duration' . $suffix ] ) ? (float) $settings[ 'duration' . $suffix ] : 0.4;
        $config['duration'] = $duration > 0 ? $duration : 0.4;

        return $config;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $title = isset( $settings['widget_title'] ) ? $settings['widget_title'] : '';
        $type4 = isset( $settings['widget_type_4'] ) ? $settings['widget_type_4'] : 'none';
        if ( '' === $type4 ) {
            $type4 = 'none';
        }
        $has_scroll_fx = ( 'none' !== $type4 );
        $split_unit    = isset( $settings['animate_split_unit'] ) ? $settings['animate_split_unit'] : 'chars';
        if ( ! in_array( $split_unit, [ 'chars', 'words' ], true ) ) {
            $split_unit = 'chars';
        }
        // Word Cascade always animates by word; Clip Wipe is line-based (Animate By hidden).
        if ( 'bw-scroll-e-10' === $type4 ) {
            $split_unit = 'words';
        }

        $title_scrub = 'data-no-scrub';
        $replay_attr = '';
        $delay_attr  = '';
        if ( $has_scroll_fx ) {
            $title_scrub = ( empty( $settings['title_scrub'] ) || $settings['title_scrub'] === 'off' )
                ? 'data-no-scrub'
                : $settings['title_scrub'];
            $replay_attr = ( ! empty( $settings['title_replay'] ) && 'yes' === $settings['title_replay'] && 'scrub_mode' !== $title_scrub )
                ? ' data-bw-replay'
                : '';
            $anim_delay_ms = isset( $settings['animate_start_delay'] ) ? (float) $settings['animate_start_delay'] : 100;
            if ( $anim_delay_ms < 0 ) {
                $anim_delay_ms = 0;
            }
            if ( $anim_delay_ms > 5000 ) {
                $anim_delay_ms = 5000;
            }
            // Keep attribute always so JS can read 0 explicitly.
            $delay_attr = ' data-bw-anim-delay="' . esc_attr( (string) round( $anim_delay_ms ) ) . '"';
        }

        $allowed_tags = [ 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span' ];
        $title_tag    = isset( $settings['widget_html_tag_title'] ) ? $settings['widget_html_tag_title'] : 'h2';
        if ( ! in_array( $title_tag, $allowed_tags, true ) ) {
            $title_tag = 'h2';
        }

        // Elementor element ids are unique per instance and stable across
        // re-renders, so several Scroll Text widgets can share one page.
        $data_id = 'bw_' . $this->get_id();

        $movement = [];
        if ( $this->is_gsap_enabled() ) {
            if ( isset( $settings['text_movement2'] ) && 'on' === $settings['text_movement2'] ) {
                $from = $this->movement_config( $settings, '2' );
                if ( $from ) {
                    $movement['from'] = $from;
                }
            }
            if ( isset( $settings['text_movement'] ) && 'on' === $settings['text_movement'] ) {
                $to = $this->movement_config( $settings, '' );
                if ( $to ) {
                    $movement['to'] = $to;
                }
            }
        }

        // The movement timeline is built by typography.js from this attribute.
        // An inline <script> would never run in the editor, because Elementor
        // injects re-rendered widget markup with innerHTML.
        $movement_attr = empty( $movement )
            ? ''
            : ' data-bw-move="' . esc_attr( wp_json_encode( $movement ) ) . '"';

        // Allow safe inline HTML; keep hard breaks for SplitText line FX.
        $title_html = wp_kses( $title, $this->allowed_inline_html() );
        // Turn remaining plain newlines into <br> without touching existing tags.
        $title_html = preg_replace( "/\r\n|\r|\n/", '<br />', $title_html );
        $plain_title = preg_replace( '/\s+/u', ' ', trim( wp_strip_all_tags( $title ) ) );

        // Elementor preview iframe: mark markup so JS can skip ScrollTrigger reliably.
        $editor_attr = '';
        if ( class_exists( '\Elementor\Plugin' ) ) {
            $elementor = \Elementor\Plugin::$instance;
            $in_editor = false;
            if ( ! empty( $elementor->editor ) && method_exists( $elementor->editor, 'is_edit_mode' ) && $elementor->editor->is_edit_mode() ) {
                $in_editor = true;
            }
            if ( ! $in_editor && ! empty( $elementor->preview ) && method_exists( $elementor->preview, 'is_preview_mode' ) && $elementor->preview->is_preview_mode() ) {
                $in_editor = true;
            }
            if ( $in_editor ) {
                $editor_attr = ' data-bw-elementor-editor';
            }
        }

        $has_link = ! empty( $settings['scroll_text_link']['url'] );
        if ( $has_link ) {
            $this->add_link_attributes( 'scroll_text_link', $settings['scroll_text_link'] );
        }

        echo '<div class="bw-typograpgy bw-t-4 bw-scroll-text"' . $editor_attr . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<div class="bw-scroll-text-box">';

        if ( $has_link ) {
            echo '<a class="bw-scroll-text-link" ' . $this->get_render_attribute_string( 'scroll_text_link' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        echo '<div class="bw-typograpgy-wrap" id="' . esc_attr( $data_id ) . '"' . $movement_attr . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        if ( $has_scroll_fx ) {
            echo '<' . esc_attr( $title_tag ) . ' class="bw-typograpgy-animate words chars splitting" ' . esc_attr( $title_scrub ) . $replay_attr . $delay_attr . ' data-bw-splitting data-bw-split-unit="' . esc_attr( $split_unit ) . '" data-bw-' . esc_attr( $type4 ) . ' id="scrub' . esc_attr( $data_id ) . '" data-scrub="true">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '<span class="word" data-word="' . esc_attr( $plain_title ) . '" style="--word-index:0;">';
            echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized via wp_kses above
            echo '</span>';
            echo '</' . esc_attr( $title_tag ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            // Static text: semantic tag + content only, no FX wrappers.
            echo '<' . esc_attr( $title_tag ) . ' class="bw-typograpgy-animate bw-scroll-text-static" data-bw-no-animate id="scrub' . esc_attr( $data_id ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $title_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized via wp_kses above
            echo '</' . esc_attr( $title_tag ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        echo '</div>';

        if ( $has_link ) {
            echo '</a>';
        }

        echo '</div>';
        echo '</div>';
    }
}

class_alias( 'Modernaweb\BlackWidgets\Widgets\ScrollText', 'Black_Widgets\BLACK_WIDGETS_Scroll_Text' );
