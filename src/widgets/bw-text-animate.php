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

    /**
     * Constructor.
     *
     * Registers CSS and JS files for the widget.
     *
     * @param array $data Optional. Widget data. Default empty array.
     * @param mixed $args Optional. Additional arguments. Default null.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-text-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/text-animate.css', [], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-text-animate', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/text-animate.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION, true );
    }

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'b_text_animate';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Black Text Animate', 'blackwidgets' );
    }

    /**
     * Get widget icon.
     *
     * @return string Elementor icon class.
     */
    public function get_icon() {
        return 'eicon-text';
    }

    /**
     * Get widget categories.
     *
     * @return array Categories where the widget will be displayed.
     */
    public function get_categories() {
        return [ 'black_widgets' ];
    }

    /**
     * Get style dependencies.
     *
     * @return array List of style handles to enqueue.
     */
    public function get_style_depends() {
        return [ 'black-widgets-text-animate' ];
    }

    /**
     * Get script dependencies.
     *
     * @return array List of script handles to enqueue.
     */
    public function get_script_depends() {
        return [ 'black-widgets-text-animate' ];
    }

    /**
     * Whether widget uses dynamic content.
     *
     * @return bool False for static content.
     */
    protected function is_dynamic_content(): bool {
        return false;
    }

    /**
     * Register widget controls.
     *
     * Adds input fields for content and styling in Elementor panel.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'widget_text',
            [
                'label' => esc_html__( 'Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'rows' => 10,
                'default' => esc_html__( 'Black Widget Text Animate', 'blackwidgets' ),
                'placeholder' => esc_html__( 'Type your text here', 'blackwidgets' ),
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
                'label' => esc_html__( 'Split by', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'   =>  esc_html__( 'None', 'blackwidgets' ),
                    'letter'   =>  esc_html__( 'Letter', 'blackwidgets' ),
                    'word'   =>  esc_html__( 'Word', 'blackwidgets' ),
                ]
            ]
        );

        $this->add_control(
            'widget_animation',
            [
                'label' => esc_html__( 'Animation', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'fbottom',
                'options' => [
                    'ftop'   =>  esc_html__( 'From Top', 'blackwidgets' ),
                    'fbottom'   =>  esc_html__( 'From Bottom', 'blackwidgets' ),
                    'fleft'   =>  esc_html__( 'From Left', 'blackwidgets' ),
                    'fright'   =>  esc_html__( 'From Right', 'blackwidgets' ),
                    'fin'   =>  esc_html__( 'Fade In', 'blackwidgets' ),
                ]
            ]
        );

        $this->add_control(
            'widget_delay',
            [
                'label' => esc_html__( 'Duration (ms)', 'blackwidgets' ),
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

        // Select tag - wraps the full sentence once (not each word/letter).
        $this->add_control(
            'widget_html_tag_title',
            [
                'label' => esc_html__( 'HTML Tag', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'div' => esc_html__( 'div', 'blackwidgets' ),
                    'h1' => esc_html__( 'H1', 'blackwidgets' ),
                    'h2' => esc_html__( 'H2', 'blackwidgets' ),
                    'h3' => esc_html__( 'H3', 'blackwidgets' ),
                    'h4' => esc_html__( 'H4', 'blackwidgets' ),
                    'h5' => esc_html__( 'H5', 'blackwidgets' ),
                    'h6' => esc_html__( 'H6', 'blackwidgets' ),
                    'p' => esc_html__( 'p', 'blackwidgets' ),
                    'span' => esc_html__( 'span', 'blackwidgets' ),
                ],
                'description' => esc_html__( 'Semantic tag for the whole text. Words/letters are wrapped in span for animation.', 'blackwidgets' ),
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'widget_alignment',
            [
                'label'     => esc_html__( 'Text Alignment', 'blackwidgets' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
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
                'default'   => 'left',
                'toggle'    => true,
                'selectors' => [
                    '{{WRAPPER}} .bw-text-animate' => 'text-align: {{VALUE}};',
                ],
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();


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
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
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
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
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
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-text-animate',
            ]
        );

        $this->add_control(
            'widget_border_radius',
            [
                'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
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
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-text-animate',
            ]
        );


        $this->end_controls_section();


        $this->start_controls_section(
            'typography_section',
            [
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widget_text_color',
            [
                'label' => esc_html__( 'Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-text-animate' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate div' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate p' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate span' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate h1' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate h2' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate h3' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate h4' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate h5' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .bw-text-animate h6' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'widget_typography',
                'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .bw-text-animate, {{WRAPPER}} .bw-text-animate div, {{WRAPPER}} .bw-text-animate p, {{WRAPPER}} .bw-text-animate span, {{WRAPPER}} .bw-text-animate h1, {{WRAPPER}} .bw-text-animate h2, {{WRAPPER}} .bw-text-animate h3, {{WRAPPER}} .bw-text-animate h4, {{WRAPPER}} .bw-text-animate h5, {{WRAPPER}} .bw-text-animate h6',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'widget_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-text-animate, {{WRAPPER}} .bw-text-animate div, {{WRAPPER}} .bw-text-animate p, {{WRAPPER}} .bw-text-animate span, {{WRAPPER}} .bw-text-animate h1, {{WRAPPER}} .bw-text-animate h2, {{WRAPPER}} .bw-text-animate h3, {{WRAPPER}} .bw-text-animate h4, {{WRAPPER}} .bw-text-animate h5, {{WRAPPER}} .bw-text-animate h6',
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * Outputs the HTML based on the widget settings.
     */
    protected function render() {
        $settings  = $this->get_settings_for_display();

        $text      = isset( $settings['widget_text'] ) ? $settings['widget_text'] : '';
        $split     = isset( $settings['widget_split'] ) ? $settings['widget_split'] : 'none';
        $animation = isset( $settings['widget_animation'] ) ? $settings['widget_animation'] : 'ftop';
        $delay     = isset( $settings['widget_delay'] ) ? $settings['widget_delay'] : '500';
        $allowed_tags = [ 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span' ];
        $title_tag    = isset( $settings['widget_html_tag_title'] ) ? $settings['widget_html_tag_title'] : 'span';
        if ( ! in_array( $title_tag, $allowed_tags, true ) ) {
            $title_tag = 'span';
        }

        $text_html = $this->get_modified_text( $text, $split, $title_tag );

        echo '<div class="bw-text-animate" data-split="' . esc_attr( $split ) . '" data-animation="' . esc_attr( $animation ) . '" data-delay="' . esc_attr( $delay ) . '">';
        // Built from escaped pieces in get_modified_text(); allow only our wrapper + spans.
        echo wp_kses(
            $text_html,
            [
                $title_tag => [ 'class' => true ],
                'span'     => [ 'class' => true ],
            ]
        );
        echo '</div>';
    }

    /**
     * Build accessible markup: one semantic outer tag, spans for animated pieces, real spaces.
     *
     * @param string $text      The original text.
     * @param string $split     Split method: 'none', 'letter', or 'word'.
     * @param string $title_tag Semantic wrapper tag for the full sentence.
     * @return string
     */
    private function get_modified_text( $text, $split = 'none', $title_tag = 'span' ) {
        // Plain text field - strip any accidental markup; avoid DOMDocument (<p> injection).
        $text = html_entity_decode( (string) $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
        $text = wp_strip_all_tags( $text );
        $text = preg_replace( "/\r\n|\r|\n/", ' ', $text );
        $text = trim( preg_replace( '/[ \t]+/u', ' ', $text ) );

        if ( $text === '' ) {
            return '';
        }

        if ( $split === 'none' ) {
            return '<' . $title_tag . ' class="bw-text-animate-content">' . esc_html( $text ) . '</' . $title_tag . '>';
        }

        if ( $split === 'word' ) {
            $parts = preg_split( '/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
            $inner = '';

            foreach ( $parts as $part ) {
                if ( preg_match( '/^\s+$/u', $part ) ) {
                    // Keep a real space between word spans (SEO + copy/paste).
                    $inner .= ' ';
                    continue;
                }
                $inner .= '<span class="bw-text-animate-content">' . esc_html( $part ) . '</span>';
            }

            return '<' . $title_tag . '>' . $inner . '</' . $title_tag . '>';
        }

        if ( $split === 'letter' ) {
            $letters = preg_split( '//u', $text, -1, PREG_SPLIT_NO_EMPTY );
            $inner   = '';

            foreach ( $letters as $letter ) {
                if ( preg_match( '/^\s$/u', $letter ) ) {
                    $inner .= ' ';
                    continue;
                }
                $inner .= '<span class="bw-text-animate-content">' . esc_html( $letter ) . '</span>';
            }

            return '<' . $title_tag . '>' . $inner . '</' . $title_tag . '>';
        }

        return '<' . $title_tag . '>' . esc_html( $text ) . '</' . $title_tag . '>';
    }

}
