<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Button extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-button', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/button.css', [], BLACK_WIDGETS_VERSION );
        wp_register_style( 'black-widgets-button-effects', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/button-effects.css', [ 'black-widgets-button' ], BLACK_WIDGETS_VERSION );

        wp_register_script( 'black-widgets-button', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/button.js', [ 'jquery', 'black-widgets-tilt' ], BLACK_WIDGETS_VERSION, true );
        $this->register_effects_script();
    }

    /**
     * Get widget name.
     *
     * Retrieve button widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'b_button';
    }

    /**
     * Get widget title.
     *
     * Retrieve button widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Black Button', 'blackwidgets' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve button widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-button';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'black_widgets' ];
    }

    public function get_style_depends() {
        // Avoid reading instance settings during early Elementor enqueue.
        return [ 'black-widgets-button', 'black-widgets-button-effects' ];
    }

    public function get_script_depends() {
        // Always register effects script; JS only activates on effect-type markup.
        $this->register_effects_script();

        return [ 'black-widgets-button', 'black-widgets-button-effects' ];
    }

    /**
     * Re-register effects script with current GSAP deps (deregister first so deps update).
     */
    private function register_effects_script() {
        $fx_deps = [ 'jquery' ];
        if ( $this->is_gsap_core_enabled() ) {
            $fx_deps[] = 'GSAP';
            if ( $this->is_splittext_enabled() ) {
                $fx_deps[] = 'GSAP-SplitText';
            }
        }

        wp_deregister_script( 'black-widgets-button-effects' );
        wp_register_script(
            'black-widgets-button-effects',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/button-effects.js',
            $fx_deps,
            BLACK_WIDGETS_VERSION,
            true
        );
    }

    /**
     * Current widget_type from settings (empty when unavailable).
     */
    private function get_current_type() {
        $settings = $this->get_early_settings();
        return isset( $settings['widget_type'] ) ? (string) $settings['widget_type'] : '';
    }

    /**
     * Raw settings safe when Elementor data is not initialized yet.
     *
     * @return array
     */
    private function get_early_settings(): array {
        return black_widgets_elementor_raw_settings( $this );
    }

    /**
     * @param string $type Widget type key.
     */
    private function is_effect_type( $type ) {
        return in_array(
            $type,
            [ 'effect_marquee', 'effect_swap', 'effect_arrow', 'effect_flip' ],
            true
        );
    }

    /**
     * GSAP core available via admin CDN1.
     */
    private function is_gsap_core_enabled() {
        return \Modernaweb\BlackWidgets\Plugin_Options::has_gsap_core();
    }

    /**
     * SplitText available via admin CDN3.
     */
    private function is_splittext_enabled() {
        return \Modernaweb\BlackWidgets\Plugin_Options::has_gsap_core()
            && \Modernaweb\BlackWidgets\Plugin_Options::has_split_text();
    }

    /**
     * Map BW effect type keys to data-effect / CSS modifier used by JS.
     *
     * @param string $type Widget type.
     * @return string
     */
    private function get_effect_key( $type ) {
        $map = [
            'effect_marquee' => 'marquee',
            'effect_swap'    => 'swap',
            'effect_arrow'   => 'arrow',
            'effect_flip'    => 'split',
        ];
        return $map[ $type ] ?? '';
    }

    /**
     * Built-in arrow SVG for Arrow Slide.
     */
    private function get_effect_arrow_svg() {
        return '<svg class="bw-ab__svg" width="16" height="14" viewBox="0 0 16 14" fill="none" aria-hidden="true"><path d="M1 7H15M15 7L9 1M15 7L9 13" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

    /**
     * Register button widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        // Start
        // Content section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'custom_panel_alert',
            [
                'type' => \Elementor\Controls_Manager::ALERT,
                'alert_type' => 'info',     /* info, success, warning, danger */
                'heading' => esc_html__( 'Feel free to edit. Check this widget\'s demo.', 'blackwidgets' ),
                'content' => sprintf(
                    '%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-button/" target="_blank">%s</a>',
                    esc_html__( 'Check ', 'blackwidgets' ),
                    esc_html__( 'Demo', 'blackwidgets' )
                ),
            ]
        );

        // Select type of the title
        $type_options = [
            'minimal' 	=> esc_html__( 'Minimal', 'blackwidgets' ),
            'modern' 	=> esc_html__( 'Modern', 'blackwidgets' ),
            'noise' 	=> esc_html__( 'Noise', 'blackwidgets' ),
            'fancy' 	=> esc_html__( 'Fancy', 'blackwidgets' ),
            'abstract' 	=> esc_html__( 'Abstract', 'blackwidgets' ),
            'gradient' 	=> esc_html__( 'Gradient', 'blackwidgets' ),
            'simple' 	=> esc_html__( 'Simple', 'blackwidgets' ),
            'custom' 	=> esc_html__( 'Custom', 'blackwidgets' ),
        ];

        $effect_labels = [
            'effect_marquee' => esc_html__( 'Effect Marquee', 'blackwidgets' ),
            'effect_swap'    => esc_html__( 'Vertical Swap', 'blackwidgets' ),
            'effect_arrow'   => esc_html__( 'Arrow Slide', 'blackwidgets' ),
            'effect_flip'    => esc_html__( 'Perspective Flip', 'blackwidgets' ),
        ];

        $current_type = $this->get_current_type();

        // New GSAP effects - offer when CDN ready; keep current effect visible if already saved.
        if ( $this->is_gsap_core_enabled() ) {
            $type_options['effect_marquee'] = $effect_labels['effect_marquee'];
            $type_options['effect_swap']    = $effect_labels['effect_swap'];
            $type_options['effect_arrow']   = $effect_labels['effect_arrow'];
            if ( $this->is_splittext_enabled() || 'effect_flip' === $current_type ) {
                $type_options['effect_flip'] = $effect_labels['effect_flip'];
            }
        } elseif ( isset( $effect_labels[ $current_type ] ) ) {
            $type_options[ $current_type ] = $effect_labels[ $current_type ];
        }

        $this->add_control(
            'widget_type',
            [
                'label' => esc_html__( 'Select Type', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'minimal',
                'options' => $type_options,
                'description' => esc_html__( 'We create some skin before, you can use these or no! make a new custom type.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'widget_hover_text',
            [
                'label' => esc_html__( 'Hover Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => esc_html__( 'Same as button text if empty', 'blackwidgets' ),
                'condition' => [
                    'widget_type' => 'effect_swap',
                ],
            ]
        );

        $this->add_control(
            'widget_effect_gsap_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-panel-alert elementor-panel-alert-info">'
                    . esc_html__( 'These effects need GSAP enabled in Black Widgets settings (GSAP CDN). Perspective Flip also needs the SplitText CDN.', 'blackwidgets' )
                    . '</div>',
                'condition' => [
                    'widget_type' => [ 'effect_marquee', 'effect_swap', 'effect_arrow', 'effect_flip' ],
                ],
            ]
        );

        $this->add_control(
            'widget_modern_type',
            [
                'label' => esc_html__( 'Modern Skin', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'm-1',
                'options' => [
                    'm-1' 	=> esc_html__( 'Type 1', 'blackwidgets' ),
                    'm-2' 	=> esc_html__( 'Type 2', 'blackwidgets' ),
                    'm-3' 	=> esc_html__( 'Type 3', 'blackwidgets' ),
                    'm-4' 	=> esc_html__( 'Type 4', 'blackwidgets' ),
                    'm-5' 	=> esc_html__( 'Type 5', 'blackwidgets' ),
                ],
                'condition'  => [
                    'widget_type' => [
                        'modern',
                    ],
                ],
            ]
        );

        $this->add_control(
            'widget_noise_type',
            [
                'label' => esc_html__( 'Noise Skin', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'n-1',
                'options' => [
                    'n-1' 	=> esc_html__( 'Type 1', 'blackwidgets' ),
                    'n-2' 	=> esc_html__( 'Type 2', 'blackwidgets' ),
                    'n-3' 	=> esc_html__( 'Type 3', 'blackwidgets' ),
                ],
                'condition'  => [
                    'widget_type' => [
                        'noise',
                    ],
                ],
            ]
        );

        $this->add_control(
            'widget_fancy_type',
            [
                'label' => esc_html__( 'Fancy Skin', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'f-1',
                'options' => [
                    'f-1' 	=> esc_html__( 'Type 1', 'blackwidgets' ),
                    'f-2' 	=> esc_html__( 'Type 2', 'blackwidgets' ),
                    'f-3' 	=> esc_html__( 'Type 3', 'blackwidgets' ),
                    'f-4' 	=> esc_html__( 'Type 4', 'blackwidgets' ),
                    'f-5' 	=> esc_html__( 'Type 5', 'blackwidgets' ),
                ],
                'condition'  => [
                    'widget_type' => [
                        'fancy',
                    ],
                ],
            ]
        );

        $this->add_control(
            'widget_abstract_type',
            [
                'label' => esc_html__( 'Abstract Skin', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'a-1',
                'options' => [
                    'a-1' 	=> esc_html__( 'Type 1', 'blackwidgets' ),
                    'a-2' 	=> esc_html__( 'Type 2', 'blackwidgets' ),
                ],
                'condition'  => [
                    'widget_type' => [
                        'abstract',
                    ],
                ],
            ]
        );

        $this->add_control(
            'widget_text',
            [
                'label' => esc_html__( 'Button Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'Lets started', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'website_link',
            [
                'label' => esc_html__( 'Link', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'blackwidgets' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'widget_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'blackwidgets' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'default'	=> 'left',
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
                'toggle'    => true,
                'selectors' => [
                    '{{WRAPPER}} .bw-button-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        // End

        // Start
        // Content section
        $this->start_controls_section(
            'custom_section',
            [
                'label' => esc_html__( 'Custom Content', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition'  => [
                    'widget_type' => [
                        'custom',
                    ],
                ],
            ]
        );

        // Enable Title Section
        $this->add_control(
            'custom_btn_show',
            [
                'label' 		=> esc_html__( 'Do You Need Icon/shape?', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::SWITCHER,
                'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
                'label_off' 	=> esc_html__( 'No!', 'blackwidgets' ),
                'return_value' 	=> 'enablenow',
                'default' 		=> 'false',
            ]
        );

        $this->add_control(
            'custom_icon_widget',
            [
                'label' => esc_html__( 'Icon', 'blackwidgets' ),
                'type' => Controls_Manager::ICONS,
                'condition'  => [
                    'custom_btn_show' => [
                        'enablenow',
                    ],
                ],
            ]
        );

        // Select type of the title
        $this->add_control(
            'custom_icon_position',
            [
                'label' => esc_html__( 'Select Type', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after'			=> esc_html__( 'After', 'blackwidgets' ),
                    'before'		=> esc_html__( 'Before', 'blackwidgets' ),
                    'up' 			=> esc_html__( 'Up', 'blackwidgets' ),
                    'down' 			=> esc_html__( 'Down ', 'blackwidgets' ),
                ],
                'condition'  => [
                    'custom_btn_show' => [
                        'enablenow',
                    ],
                ],
            ]
        );



        $this->end_controls_section();
        // End

        // Start
        // Style section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Box Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Style Subtitle Tabs
        $this->start_controls_tabs('black_widget_1_tab');
        $this->start_controls_tab(
            'tab_1_normal',
            [
                'label' => esc_html__( 'Normal', 'blackwidgets' ),
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-button-box .bw-btn',
            ]
        );

        $this->add_control(
            'widget_normal_style_blur',
            [
                'label' => esc_html__( 'Blur', 'blackwidgets' ),
                'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
                ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_widget_box_background',
                'label' => esc_html__( 'Wrapper Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-button-box.modern.m-4 .btn-wrapper',
                'condition'  => [
                    'widget_modern_type' => [
                        'm-4',
                    ],
                ],
            ]
        );

        $this->add_control(
            'widget_wrapper_normal_style_blur',
            [
                'label' => esc_html__( 'Blur', 'blackwidgets' ),
                'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition'  => [
                    'widget_modern_type' => [
                        'm-4',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box.modern.m-4 .btn-wrapper' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
                ],
            ]
        );

        $this->add_control(
            'hr1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        // Margin
        $this->add_responsive_control(
            'widget_box_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'widget_box_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-button-box.abstract.a-2 .bw-btn .btx-a1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    // Only the visible Noise text layer - never all nested glitch divs.
                    '{{WRAPPER}} .bw-button-box.noise.n-1 .bw-btn > div:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-button-box.noise.n-2 .bw-btn > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-button-box.noise.n-3 .bw-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_box_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-button-box .bw-btn',
            ]
        );

        // Box shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-button-box .bw-btn, {{WRAPPER}} .bw-button-box.modern.m-1',
            ]
        );

        $this->add_control(
            'widget_box_border_radius', //param_name
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_1_hover',
            [
                'label' => esc_html__( 'Hover', 'blackwidgets' ),
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_hover_box_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient', ],
                'selector' => '{{WRAPPER}} .bw-button-box .bw-btn:hover',
            ]
        );

        $this->add_control(
            'widget_hover_style_blur',
            [
                'label' => esc_html__( 'Blur', 'blackwidgets' ),
                'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn:hover' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
                ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_widget_hover_box_background',
                'label' => esc_html__( 'Wrapper Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-button-box.modern.m-4 .btn-wrapper:hover',
                'condition'  => [
                    'widget_modern_type' => [
                        'm-4',
                    ],
                ],
            ]
        );

        $this->add_control(
            'widget_wrapper_hover_style_blur',
            [
                'label' => esc_html__( 'Blur', 'blackwidgets' ),
                'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition'  => [
                    'widget_modern_type' => [
                        'm-4',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box.modern.m-4 .btn-wrapper:hover' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
                ],
            ]
        );

        $this->add_control(
            'hr3',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        // Margin
        $this->add_responsive_control(
            'widget_hover_box_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'widget_hover_box_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box:not(.abstract.a-1) .bw-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-button-box.abstract.a-2 .bw-btn:hover .btx-a1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-button-box.noise.n-1 .bw-btn:hover > div:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .bw-button-box.noise.n-2 .bw-btn:hover > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hr4',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_hover_box_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-button-box .bw-btn:hover',
            ]
        );

        // Box shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_hover_box_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-button-box .bw-btn:hover',
            ]
        );

        $this->add_control(
            'widget_hover_box_border_radius', //param_name
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
                'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs(); // End Tabs

        $this->end_controls_section();
        // End

        // Start
        // Style section
        $this->start_controls_section(
            'typography1_section',
            [
                'label' => esc_html__( 'Button Typography', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Style Subtitle Tabs
        $this->start_controls_tabs('black_widget_2_tab');
        $this->start_controls_tab(
            'tab_2_normal',
            [
                'label' => esc_html__( 'Normal', 'blackwidgets' ),
            ]
        );

        // Color
        $this->add_control(
            'widget_btn_solid_color',
            [
                'label' => esc_html__( 'Button Text Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                // 'scheme' => [
                // 	'type' => Color::get_type(),
                // 	'value' => Color::COLOR_1,
                // ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box .bw-ab__txt' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box .bw-ab__marquee-item' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box .bw-ab__swap-face' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.modern.m-5 .bw-btn span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.abstract.a-2 .bw-btn .btx-a1' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.noise.n-1' => '--bw-noise-text: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.noise.n-1 .bw-btn > div:first-child' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.noise.n-2 .bw-btn > div:nth-child(2)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_btn_background',
                'label' => esc_html__( 'Wrapper Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-button-box.noise.n-1 .bw-btn > div:first-child, {{WRAPPER}} .bw-button-box.noise.n-2, {{WRAPPER}} .bw-button-box.noise.n-3 .bw-btn, {{WRAPPER}} .bw-button-box.abstract .bw-btn',
                'condition' => [
                    'widget_type' => ['noise','abstract'],
                ],
            ]
        );

        $this->add_control(
            'widget_btn_background_color1',
            [
                'label' => esc_html__( 'Color 1(Gradiant)', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'condition' => [
                    'widget_type' => 'gradient',
                ],
                // Only set the CSS var - keep the 4-stop gradient from button.css
                // (#000, #444, #f7f7f7, #f9f9f9).
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box.gradient .bw-btn' => '--bw-gradient-color-1: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'widget_btn_background_color2',
            [
                'label' => esc_html__( 'Color 2(Gradiant)', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f9f9f9',
                'condition' => [
                    'widget_type' => 'gradient',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box.gradient .bw-btn' => '--bw-gradient-color-2: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_typography1_height',
            [
                'label' => esc_html__( 'Height', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box.abstract .bw-btn'=> 'height: {{SIZE}}{{UNIT}} ;'
                ],
                'condition' => [
                    'widget_type' => ['abstract'],
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_typography1_size_icon',
            [
                'label' => esc_html__( 'Size Icon', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box.abstract .bw-btn svg'=> 'width:{{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}} ;'
                ],
                'condition' => [
                    'widget_type' => ['abstract'],
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography1',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                // 'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .bw-button-box .bw-btn, {{WRAPPER}} .bw-button-box .bw-ab__txt, {{WRAPPER}} .bw-button-box .bw-ab__marquee-item, {{WRAPPER}} .bw-button-box .bw-ab__swap-face, {{WRAPPER}} .bw-button-box.modern.m-5 .bw-btn span, {{WRAPPER}} .bw-button-box.abstract.a-2 .bw-btn .btx-a1',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_2_hover',
            [
                'label' => esc_html__( 'Hover', 'blackwidgets' ),
            ]
        );

        // Color
        $this->add_control(
            'widget_btn_hover_solid_color',
            [
                'label' => esc_html__( 'Button Text Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                // 'scheme' => [
                // 	'type' => Color::get_type(),
                // 	'value' => Color::COLOR_1,
                // ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-btn:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box .bw-ab__btn:hover .bw-ab__txt' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box .bw-ab__btn:hover .bw-ab__marquee-item' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box .bw-ab__btn:hover .bw-ab__swap-face' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.modern.m-5 .bw-btn:hover span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.abstract.a-2 .bw-btn:hover .btx-a1' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.noise.n-1' => '--bw-noise-hover-text: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.noise.n-1 .bw-btn:hover > div:first-child' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-button-box.noise.n-2 .bw-btn:hover > div:nth-child(2)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_btn_hover_background',
                'label' => esc_html__( 'Wrapper Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-button-box.noise.n-1 .bw-btn:hover > div:first-child, {{WRAPPER}} .bw-button-box.noise.n-2:hover, {{WRAPPER}} .bw-button-box.noise.n-3 .bw-btn:hover, {{WRAPPER}} .bw-button-box.abstract .bw-btn:hover',
                'condition' => [
                    'widget_type' => ['noise','abstract'],
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_hover_typography1',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                // 'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .bw-button-box .bw-btn:hover, {{WRAPPER}} .bw-button-box .bw-ab__btn:hover .bw-ab__txt, {{WRAPPER}} .bw-button-box .bw-ab__btn:hover .bw-ab__marquee-item, {{WRAPPER}} .bw-button-box .bw-ab__btn:hover .bw-ab__swap-face, {{WRAPPER}} .bw-button-box.modern.m-5 .bw-btn:hover span, {{WRAPPER}} .bw-button-box.abstract.a-2 .bw-btn:hover .btx-a1',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs(); // End Tabs

        $this->end_controls_section();
        // End


        // Start
        // Typography section
        $this->start_controls_section(
            'icon_section',
            [
                'label' => esc_html__( 'Icon Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition'  => [
                    'widget_type' => [
                        'custom',
                    ],
                ],
            ]
        );

        // Style Subtitle Tabs
        $this->start_controls_tabs('black_widget_3_tab');
        $this->start_controls_tab(
            'tab_3_normal',
            [
                'label' => esc_html__( 'Normal', 'blackwidgets' ),
            ]
        );

        $this->add_responsive_control(
            'style_icon_size_normal',
            [
                'label' => esc_html__( 'Icon Size', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape svg,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape i' => 'font-size: {{SIZE}}{{UNIT}} !important; width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        // Color
        $this->add_control(
            'style_icon_color_normal',
            [
                'label' => esc_html__( 'Icon Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                // 'scheme' => [
                // 	'type' => Color::get_type(),
                // 	'value' => Color::COLOR_1,
                // ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape svg,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape i' => 'color: {{VALUE}}; fill: {{VALUE}}',
                ],
            ]
        );

        // Text shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_icon_text_shadow_normal',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape svg,
							   {{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape i',
            ]
        );

        // Padding
        $this->add_responsive_control(
            'style_icon_text_padding_normal',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape svg,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'style_wrapper_padding_normal',
            [
                'label' => esc_html__( 'Padding For Wrapper', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // margin
        $this->add_responsive_control(
            'style_wrapper_margin_normal',
            [
                'label' => esc_html__( 'Margin For Wrapper', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn .bw-custom-icon-shape' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_3_hover',
            [
                'label' => esc_html__( 'Hover', 'blackwidgets' ),
            ]
        );

        $this->add_responsive_control(
            'style_icon_size_hover',
            [
                'label' => esc_html__( 'Icon Size', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape svg,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape i' => 'font-size: {{SIZE}}{{UNIT}} !important; width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        // Color
        $this->add_control(
            'style_icon_color_hover',
            [
                'label' => esc_html__( 'Icon Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                // 'scheme' => [
                // 	'type' => Color::get_type(),
                // 	'value' => Color::COLOR_1,
                // ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape svg,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape i' => 'color: {{VALUE}}; fill: {{VALUE}}',
                ],
            ]
        );

        // Text shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_icon_text_shadow_hover',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape svg,
							   {{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape i',
            ]
        );

        // Padding
        $this->add_responsive_control(
            'style_icon_text_padding_hover',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape svg,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'style_wrapper_padding_hover',
            [
                'label' => esc_html__( 'Padding For Wrapper', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // margin
        $this->add_responsive_control(
            'style_wrapper_margin_hover',
            [
                'label' => esc_html__( 'Margin For Wrapper', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape,
					 {{WRAPPER}} .bw-button-box .bw-custom-btn:hover .bw-custom-icon-shape' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs(); // End Tabs


        $this->end_controls_section();

    }

    /**
     * Render title widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings   			= $this->get_settings_for_display();
        // Variables
        $type 	        		= isset($settings['widget_type']) 				? $settings['widget_type'] 				: '';
        $modern_type			= isset($settings['widget_modern_type']) 		? $settings['widget_modern_type'] 		: '';
        $noise_type				= isset($settings['widget_noise_type']) 		? $settings['widget_noise_type'] 		: '';
        $fancy_type				= isset($settings['widget_fancy_type']) 		? $settings['widget_fancy_type'] 		: '';
        $abstract_type			= isset($settings['widget_abstract_type']) 		? $settings['widget_abstract_type'] 	: '';
        $text 	        		= isset($settings['widget_text']) 				? $settings['widget_text'] 				: '';
        // Escape attribute values only - never esc_attr() a full attribute string.
        $link_attrs = '';
        if ( ! empty( $settings['website_link']['is_external'] ) ) {
            $link_attrs .= ' target="' . esc_attr( '_blank' ) . '"';
        }
        if ( ! empty( $settings['website_link']['nofollow'] ) ) {
            $link_attrs .= ' rel="' . esc_attr( 'nofollow' ) . '"';
        }
        $alignment 				= isset( $settings['widget_alignment'] ) ? $settings['widget_alignment'] : '';

        $custom_icon_position	= isset($settings['custom_icon_position'])		? $settings['custom_icon_position']		: '';
        // custom_btn_show lives in a section conditioned on widget_type=custom - often unset.
        $enable_custom_shape	= isset( $settings['custom_btn_show'] ) && 'enablenow' === $settings['custom_btn_show'] ? 'enablenow' : '';
        $widget_id				= $this->get_id();

        // Render
        echo '<div class="bw-button-wrapper"><div class="bw-button-box ' . esc_attr( $type ) . ' ' . esc_attr( $modern_type ) . ' ' . esc_attr( $fancy_type ) . ' ' . esc_attr( $noise_type ) . ' ' . esc_attr( $abstract_type ) . ' ' . esc_attr( $alignment ) . '">';

        if ( $this->is_effect_type( $type ) ) {
            $this->render_effect_button( $settings, $type, $text, $link_attrs );
            echo '</div></div>';
            return;
        }

        switch ($type) {
            case 'modern':
                switch ($modern_type) {
                    case 'm-4':
                        echo '<div class="btn-wrapper"><a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . ' class="bw-btn bw-btn-' . esc_attr( $modern_type ) . '">' . esc_html( $text ) . '</a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '<!-- symbols -->
							<svg xmlns="http://www.w3.org/2000/svg" style="display: none;" data-bw-symbols="' . esc_attr( $widget_id ) . '">
								<symbol id="donut-' . esc_attr( $widget_id ) . '" viewBox="0 0 14 14"><path fill="#000" fill-rule="nonzero" d="M7 12c2.76 0 5-2.24 5-5S9.76 2 7 2 2 4.24 2 7s2.24 5 5 5zm0 2c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></symbol>
								<symbol id="circle-' . esc_attr( $widget_id ) . '" viewBox="0 0 10 10"><circle cx="5" cy="5" r="5" fill="#000" fill-rule="evenodd"/></symbol>
								<symbol id="tri_hollow-' . esc_attr( $widget_id ) . '" viewBox="0 0 12 11"><path fill="#000" fill-rule="nonzero" d="M3.4 8.96h5.2L6 4.2 3.4 8.95zM6 0l6 11H0L6 0z"/></symbol>
								<symbol id="triangle-' . esc_attr( $widget_id ) . '" viewBox="0 0 10 9"><path fill="#000" fill-rule="evenodd" d="M5 0l5 9H0"/></symbol>
								<symbol id="square-' . esc_attr( $widget_id ) . '" viewBox="0 0 8 8"><path fill="#000" fill-rule="evenodd" d="M0 0h8v8H0z"/></symbol>
								<symbol id="squ_hollow-' . esc_attr( $widget_id ) . '" viewBox="0 0 8 8"><path fill="#000" fill-rule="nonzero" d="M1.5 1.5v5h5v-5h-5zM0 0h8v8H0V0z"/></symbol>
							</svg>';
                        break;
                    case 'm-5':
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn"><span>%s</span></a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_html( $text )
                        );
                        break;
                    default:
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn">%s</a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_html( $text )
                        );
                        break;
                }
                break;
            case 'noise':
                switch ( $noise_type ) {
                    case 'n-1':
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn bw-btn-%s">
				<div>%4$s</div>
				<div>
					<div>%4$s</div>
					<div>%4$s</div>
					<div>%4$s</div>
				</div>
			</a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_attr( $noise_type ),
                            esc_html( $text )
                        );
                        break;

                    case 'n-2':
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn bw-btn-%s">
				<div></div>
				<div>%s</div>
			</a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_attr( $noise_type ),
                            esc_html( $text )
                        );
                        break;

                    case 'n-3':
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn bw-btn-%s" data-text="%s">
				<div>%s</div>
			</a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_attr( $noise_type ),
                            esc_attr( $text ),
                            esc_html( $text )
                        );
                        break;

                    default:
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn">%s</a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_html( $text )
                        );
                        break;
                }
                break;
            case 'abstract':
                switch ( $abstract_type ) {
                    case 'a-1':
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn">%s<span>%s</span></a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_html( $text ),
                            $this->bw_get_inline_svg_arrow() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        );
                        break;

                    case 'a-2':
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn"><div class="btx-a1" data-text="%s">%s</div></a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_attr( $text ),
                            esc_html( $text )
                        );
                        break;

                    default:
                        echo sprintf(
                            '<a href="%s"%s class="bw-btn">%s</a>',
                            esc_url( $settings['website_link']['url'] ),
                            $link_attrs,
                            esc_html( $text )
                        );
                        break;
                }
                break;
            case'fancy':
                switch ( $fancy_type ) {
                    case 'f-2':
                        echo '<a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . ' class="bw-btn"><span></span><span></span><span></span><span></span>' . esc_html( $text ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        break;

                    case 'f-3':
                    case 'f-4':
                        echo '<a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . '><div class="bw-btn"><div>' . esc_html( $text ) . '</div><div>' . esc_html( $text ) . '</div></div></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        break;

                    case 'f-5':
                        echo '<a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . ' class="bw-btn"><svg><rect x="0" y="0" fill="none" width="100%" height="100%"/></svg>' . esc_html( $text ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        break;

                    default:
                        echo '<a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . ' class="bw-btn">' . esc_html( $text ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        break;
                }
                break;
            case'custom':
                switch ( $custom_icon_position ) {
                    case 'before':
                    case 'up':
                        echo '<a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . ' class="bw-btn bw-custom-btn ' . esc_attr( $custom_icon_position ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        if ( $enable_custom_shape ) {
                            echo '<span class="bw-custom-icon-shape">';
                            \Elementor\Icons_Manager::render_icon( $settings['custom_icon_widget'], [ 'aria-hidden' => 'true' ] );
                            echo '</span>';
                        }
                        echo esc_html( $text );
                        echo '</a>';
                        break;

                    case 'after':
                    case 'down':
                        echo '<a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . ' class="bw-btn bw-custom-btn ' . esc_attr( $custom_icon_position ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo esc_html( $text );
                        if ( $enable_custom_shape ) {
                            echo '<span class="bw-custom-icon-shape">';
                            \Elementor\Icons_Manager::render_icon( $settings['custom_icon_widget'], [ 'aria-hidden' => 'true' ] );
                            echo '</span>';
                        }
                        echo '</a>';
                        break;

                    default:
                        echo '<a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . ' class="bw-btn bw-custom-btn">' . esc_html( $text ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        break;
                }
                break;
            default:
                echo '<a href="' . esc_url( $settings['website_link']['url'] ) . '"' . $link_attrs . ' class="bw-btn">' . esc_html( $text ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                break;
        }
        echo '</div></div>';

    }

    /**
     * Render GSAP effect button markup (Marquee / Swap / Arrow / Flip).
     *
     * @param array  $settings   Widget settings.
     * @param string $type       Widget type key.
     * @param string $text       Button label.
     * @param string $link_attrs Escaped target/rel attributes string.
     */
    private function render_effect_button( $settings, $type, $text, $link_attrs ) {
        $effect = $this->get_effect_key( $type );
        if ( '' === $effect ) {
            $effect = 'marquee';
        }

        $hover_text = ! empty( $settings['widget_hover_text'] ) ? (string) $settings['widget_hover_text'] : $text;
        $url        = ! empty( $settings['website_link']['url'] ) ? $settings['website_link']['url'] : '';
        $tag        = $url ? 'a' : 'button';
        $type_attr  = ( 'button' === $tag ) ? ' type="button"' : '';
        $href_attr  = $url ? ' href="' . esc_url( $url ) . '"' : '';

        $btn_classes = [
            'bw-btn',
            'bw-ab__btn',
            'bw-ab__btn--' . $effect,
        ];

        echo '<div class="bw-ab" data-bw-ab data-effect="' . esc_attr( $effect ) . '">';
        echo '<' . esc_html( $tag ) . ' class="' . esc_attr( implode( ' ', $btn_classes ) ) . '"' . $type_attr . $href_attr . $link_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        switch ( $effect ) {
            case 'arrow':
                echo '<span class="bw-ab__txt">' . esc_html( $text ) . '</span>';
                echo '<span class="bw-ab__arrow-track" aria-hidden="true">';
                echo '<span class="bw-ab__arrow">' . $this->get_effect_arrow_svg() . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '<span class="bw-ab__arrow">' . $this->get_effect_arrow_svg() . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</span>';
                break;

            case 'marquee':
                echo '<span class="bw-ab__marquee-mask">';
                echo '<span class="bw-ab__marquee-track">';
                echo '<span class="bw-ab__marquee-item">' . esc_html( $text ) . '</span>';
                echo '<span class="bw-ab__marquee-item" aria-hidden="true">' . esc_html( $text ) . '</span>';
                echo '</span></span>';
                break;

            case 'swap':
                echo '<span class="bw-ab__swap-inner">';
                echo '<span class="bw-ab__swap-face bw-ab__swap-top">' . esc_html( $text ) . '</span>';
                echo '<span class="bw-ab__swap-face bw-ab__swap-bottom" aria-hidden="true">' . esc_html( $hover_text ) . '</span>';
                echo '</span>';
                break;

            case 'split':
            default:
                echo '<span class="bw-ab__txt">' . esc_html( $text ) . '</span>';
                break;
        }

        echo '</' . esc_html( $tag ) . '>';
        echo '</div>';
    }

    /**
     * Returns an inline SVG arrow icon used in buttons or UI elements.
     *
     * This method outputs a right-pointing arrow icon as an inline SVG.
     * The SVG is lightweight and styled with fixed width and height.
     *
     * @return string SVG HTML markup as a string.
     */
    public function bw_get_inline_svg_arrow() {
        return '<svg height="512px" viewBox="0 0 512 512" width="512px" xmlns="http://www.w3.org/2000/svg"><polygon points="160,115.4 180.7,96 352,256 180.7,416 160,396.7 310.5,256"/></svg>';
    }

}

class_alias('Modernaweb\BlackWidgets\Widgets\Button', 'Black_Widgets\BLACK_WIDGETS_Button');
