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
        return __( 'Black Text Animate', 'black-widgets' );
    }

    /**
     * Get widget icon.
     *
     * @return string Elementor icon class.
     */
    public function get_icon() {
        return 'eicon-animation-text';
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

        // Select tag
        $this->add_control(
            'widget_html_tag_title',
            [
                'label' => esc_html__( 'HTML Tag', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'div' => esc_html__( 'div', 'black-widgets' ),
                    'h1' => esc_html__( 'H1', 'black-widgets' ),
                    'h2' => esc_html__( 'H2', 'black-widgets' ),
                    'h3' => esc_html__( 'H3', 'black-widgets' ),
                    'h4' => esc_html__( 'H4', 'black-widgets' ),
                    'h5' => esc_html__( 'H5', 'black-widgets' ),
                    'h6' => esc_html__( 'H6', 'black-widgets' ),
                    'p' => esc_html__( 'p', 'black-widgets' ),
                    'span' => esc_html__( 'span', 'black-widgets' ),
                ],
                'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'black-widgets' ),
            ]
        );

        // Alignment
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
                'label' => esc_html__( 'Typography', 'black-widgets' ),
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
                'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
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
        $split     = isset( $settings['widget_split'] ) ? esc_attr( $settings['widget_split'] ) : 'none';
        $animation = isset( $settings['widget_animation'] ) ? esc_attr( $settings['widget_animation'] ) : 'ftop';
        $delay     = isset( $settings['widget_delay'] ) ? esc_attr( $settings['widget_delay'] ) : '500';
        $allowed_tags 	= ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'];
        $title_tag 		= isset($settings['widget_html_tag_title']) ? $settings['widget_html_tag_title'] : '';
        if (!in_array($title_tag, $allowed_tags)) {
            $title_tag = 'span';
        }

        $text = $this->get_modified_text( $text, $split, $title_tag );

        echo '<div class="bw-text-animate" data-split="' . esc_attr( $split ) . '" data-animation="' . esc_attr( $animation ) . '" data-delay="' . esc_attr( $delay ) . '">';
        echo wp_kses_post( $text );
        echo '</div>';
    }

    /**
     * Modify input text based on splitting type.
     *
     * @param string $text  The original text.
     * @param string $split Split method: 'none', 'letter', or 'word'.
     * @param string $title_tag Tag for Text.
     * @return string Modified HTML with spans for animation.
     */
    private function get_modified_text( $text, $split = 'none', $title_tag ='span' ) {
        if ( empty( $text ) ) {
            return '';
        }

        if ( $split === 'none' ) {
            return '<'. $title_tag .' class="bw-text-animate-content">' . force_balance_tags( $text ) . '</' . $title_tag . '>';
        }

        if ( $split === 'letter' || $split === 'word' ) {
            libxml_use_internal_errors( true );
            $doc = new \DOMDocument();
            $doc->loadHTML( '<?xml encoding="utf-8" ?>' . $text, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

            if ( $split === 'letter' ) {
                $this->wrap_letters_in_spans( $doc, $title_tag);
            } else {
                $this->wrap_words_in_spans( $doc, $title_tag );
            }

            $html = $doc->saveHTML();
            $html = preg_replace( '/^<!DOCTYPE.+?>/', '', $html );
            $html = str_replace( [ '<html>', '</html>', '<body>', '</body>' ], '', $html );

            return $html;
        }

        return $text;
    }

    /**
     * Wrap each word of text nodes in <span> elements.
     *
     * @param \DOMNode $node The DOM node to process.
     */
    private function wrap_words_in_spans( \DOMNode $node, $title_tag ) {
        foreach ( iterator_to_array( $node->childNodes ) as $child ) {
            if ( $child->nodeType === XML_TEXT_NODE ) {
                $words = preg_split( '/(\s+)/u', $child->nodeValue, -1, PREG_SPLIT_DELIM_CAPTURE );
                $fragment = $node->ownerDocument->createDocumentFragment();

                foreach ( $words as $word ) {
                    if ( trim( $word ) === '' ) {
                        $fragment->appendChild( $node->ownerDocument->createTextNode( $word ) );
                    } else {
                        $span = $node->ownerDocument->createElement( $title_tag );
                        $span->setAttribute( 'class', 'bw-text-animate-content' );
                        $span->appendChild( $node->ownerDocument->createTextNode( $word ) );
                        $fragment->appendChild( $span );
                    }
                }

                $node->replaceChild( $fragment, $child );
            } elseif ( $child->hasChildNodes() ) {
                $this->wrap_words_in_spans( $child, $title_tag );
            }
        }
    }

    /**
     * Wrap each letter of text nodes in <span> elements.
     *
     * @param \DOMNode $node The DOM node to process.
     */
    private function wrap_letters_in_spans( \DOMNode $node, $title_tag ) {
        foreach ( iterator_to_array( $node->childNodes ) as $child ) {
            if ( $child->nodeType === XML_TEXT_NODE ) {
                $letters = preg_split( '//u', $child->nodeValue, -1, PREG_SPLIT_NO_EMPTY );
                $fragment = $node->ownerDocument->createDocumentFragment();

                foreach ( $letters as $letter ) {
                    if ( trim($letter) === '' ) {
                        $fragment->appendChild( $node->ownerDocument->createTextNode( $letter ) );
                    } else {
                        $span = $node->ownerDocument->createElement( $title_tag );
                        $span->setAttribute( 'class', 'bw-text-animate-content' );
                        $span->appendChild( $node->ownerDocument->createTextNode( $letter ) );
                        $fragment->appendChild( $span );
                    }
                }

                $node->replaceChild( $fragment, $child );
            } elseif ( $child->hasChildNodes() ) {
                $this->wrap_letters_in_spans( $child, $title_tag );
            }
        }
    }

}
