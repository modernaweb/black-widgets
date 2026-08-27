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


class GSAPInteractiveLinks extends \Elementor\Widget_Base {

    /**
     * Constructor for the Interactive Links widget.
     *
     * @since 1.4.0
     *
     * This method is automatically called when the widget is instantiated.
     * It registers the required CSS and JS files specific to the Interactive Links widget.
     *
     * @param array $data Optional. Widget data. Default empty array.
     * @param mixed $args Optional. Additional arguments. Default null.
     */
    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );

        wp_register_style(
            'black-widgets-gsap-interactive-links',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/css/interactive-links.css',
            [], // Dependencies
            BLACK_WIDGETS_VERSION // Version for cache busting
        );

        $this->register_interactive_links_script();
    }

    /**
     * Re-register the widget script with current GSAP deps (deregister first so deps update).
     *
     * Listing the GSAP handle unconditionally would make WordPress drop this script
     * whenever the CDN URL is missing or refused, so the JS guard never runs.
     *
     * @since 1.4.0
     */
    private function register_interactive_links_script() {
        $deps = [ 'jquery' ]; // ScrollTrigger not used by this widget.

        if ( \Modernaweb\BlackWidgets\Plugin_Options::has_gsap_core() ) {
            \Modernaweb\BlackWidgets\Plugin_Options::register_gsap_scripts();

            if ( wp_script_is( 'GSAP', 'registered' ) ) {
                $deps[] = 'GSAP';
            }
        }

        wp_deregister_script( 'black-widgets-gsap-interactive-links' );
        wp_register_script(
            'black-widgets-gsap-interactive-links',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/interactive-links.js',
            $deps,
            BLACK_WIDGETS_VERSION, // Version for cache busting
            true
        );
    }

    /**
     * Get widget name.
     *
     * Retrieve button widget name.
     *
     * @since 1.4.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'b_gsap_interactive_links';
    }

    /**
     * Get widget title.
     *
     * Retrieve button widget title.
     *
     * @since 1.4.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Black Interactive Links', 'blackwidgets' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve button widget icon.
     *
     * @since 1.4.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-menu-toggle';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
     *
     * @access public
     *
     * @return array Widget categories.
     * @since 1.4.0
     */
    public function get_categories() {
        return [ 'black_widgets' ];
    }

    /**
     * Returns an array of style handles to be enqueued with this widget.
     *
     * @return array List of style handles.
     * @since 1.4.0
     */
    public function get_style_depends() {
        return [ 'black-widgets-gsap-interactive-links' ];
    }

    /**
     * Returns an array of script handles to be enqueued with this widget.
     *
     * @return array List of script handles.
     * @since 1.4.0
     */
    public function get_script_depends() {
        $this->register_interactive_links_script();

        return [ 'black-widgets-gsap-interactive-links' ];
    }

    /**
     * Determines whether this widget includes dynamic content.
     *
     * @return bool False, because the widget has no dynamic content.
     * @since 1.4.0
     */
    protected function is_dynamic_content(): bool {
        return false;
    }

    /**
     * Register widget controls.
     *
     * This is the main entry point to register all controls for the widget in Elementor editor.
     * It delegates control grouping to specialized private methods for better modularity.
     *
     * @since 1.4.0
     * @access protected
     * @return void
     */
    protected function register_controls() {
        $this->register_settings_controls();
        $this->register_style_controls();
    }

    /**
     * Register widget settings controls.
     *
     * This method is responsible for registering the controls related to the widget's
     * content and settings, such as widget type, items list, title, description, link, and image.
     * The controls are grouped into a section with the tab "Content" in the Elementor editor.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_settings_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Items',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'widget_type',
            [
                'label' => esc_html__( 'Select Type', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'default', 'blackwidgets' ),
                ],
                'default' => 'default',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Item',
            ]
        );

        $repeater->add_control(
            'tag_type',
            [
                'label' => 'Tag Type Title',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p'  => 'P',
                    'div' => 'DIV',
                    'span' => 'SPAN',
                ],
            ]
        );

        $repeater->add_control(
            'short_description',
            [
                'label' => 'Short Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => 'Enter your short description...',
                'default' => '',
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => 'Link',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => 'Image',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/img/dsgn-in-black-widgets.jpg',
                ],
            ]
        );

        $this->add_control(
            'menu_items',
            [
                'label' => 'Items List',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['title' => 'Item1'],
                    ['title' => 'Item2'],
                    ['title' => 'Item3'],
                ],
                'condition' => [
                    'widget_type' => 'default',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register all style controls.
     *
     * This method organizes the style-related controls into separate modular sections
     * such as box, list, items, titles, descriptions, and image hover effects.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_style_controls() {
        $this->register_style_box_controls();
        $this->register_style_li_controls();
        $this->register_style_links_titles_controls();
        $this->register_style_description_controls();
        $this->register_style_image_container_controls();
        $this->register_style_hover_image_controls();
        $this->register_style_gsap_controls();
    }

    /**
     * Register style controls for the widget box.
     *
     * This method registers various style controls for the widget's box, such as background,
     * border, padding, margin, and box shadow for both normal and hover states. These controls
     * allow the user to customize the appearance of the widget box in the Elementor editor.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_style_box_controls() {
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Box Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('widget_box_style_tabs');

        $this->start_controls_tab(
            'widget_box_style_normal',
            [
                'label' => esc_html__( 'Normal', 'blackwidgets' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .bw-interactive-link-box',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_box_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-interactive-link-box',
            ]
        );

        $this->add_control(
            'widget_box_border_radius',
            [
                'label' => __('Border Radius', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_box_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_box_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-interactive-link-box',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'widget_box_style_hover',
            [
                'label' => esc_html__( 'Hover', 'blackwidgets' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_hover_background',
                'label' => esc_html__( 'Hover Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-interactive-link-box:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_box_hover_border',
                'label' => esc_html__( 'Hover Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-interactive-link-box:hover',
            ]
        );

        $this->add_control(
            'widget_box_hover_border_radius',
            [
                'label' => __('Border Radius', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_box_hover_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_box_hover_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_hover_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-interactive-link-box:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Register style controls for the list items.
     *
     * This method registers style controls for the individual list items (`li`) within the widget.
     * It allows users to customize the typography, text color, background color, and hover effects
     * (text color, background color, and box shadow) for the list items.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_style_li_controls() {
        $this->start_controls_section(
            'style_li',
            [
                'label' => __( 'List Items', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'li_typography',
                'selector' => '{{WRAPPER}} .bw-il-menu li',
            ]
        );

        $this->add_control(
            'li_gap',
            [
                'label' => __( 'Items Gap', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_li_style' );

        $this->start_controls_tab(
            'tab_li_normal',
            [
                'label' => __( 'Normal', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'li_text_color',
            [
                'label' => __( 'Text Color', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'li_background',
                'selector' => '{{WRAPPER}} .bw-il-menu li',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'li_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-il-menu li',
            ]
        );

        $this->add_control(
            'li_border_radius',
            [
                'label' => __('Border Radius', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'li_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'li_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'li_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-il-menu li',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_li_hover',
            [
                'label' => __( 'Hover', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'li_hover_color',
            [
                'label' => __( 'Hover Text Color', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'li_hover_bg',
            [
                'label' => __( 'Hover Background', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'li_hover_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-il-menu li:hover',
            ]
        );

        $this->add_control(
            'li_hover_border_radius',
            [
                'label' => __('Border Radius', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'li_hover_padding',
            [
                'label' => esc_html__( 'Padding', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'li_hover_margin',
            [
                'label' => esc_html__( 'Margin', 'blackwidgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'li_hover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-il-menu li:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Register style controls for links and titles.
     *
     * This method registers the style controls for links and titles within the widget.
     * It allows users to customize the typography, color, hover color, and padding for titles and link elements.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_style_links_titles_controls() {
        $this->start_controls_section(
            'style_links_titles',
            [
                'label' => __( 'Links & Titles', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bw-il-title, {{WRAPPER}} .bw-il-menu a',
            ]
        );

        $this->start_controls_tabs( 'tabs_title_style' );

        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => __( 'Normal', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-il-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label' => __( 'Hover', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Title Hover Color', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover .bw-il-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __( 'Title Padding', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls for description text.
     *
     * This method registers the style controls for the description text within the widget.
     * It allows users to customize the typography, color, background, text shadow, padding, margin, and text alignment
     * for the description text in both normal and hover states.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_style_description_controls() {

        $this->start_controls_section(
            'style_description',
            [
                'label' => __( 'Description', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .bw-il-description',
            ]
        );

        $this->start_controls_tabs( 'tabs_description_style' );

        $this->start_controls_tab(
            'tab_description_normal',
            [
                'label' => __( 'Normal', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Text Color', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'description_background',
                'selector' => '{{WRAPPER}} .bw-il-description',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'description_text_shadow',
                'selector' => '{{WRAPPER}} .bw-il-description',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_description_hover',
            [
                'label' => __( 'Hover', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'description_hover_color',
            [
                'label' => __( 'Hover Color', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover .bw-il-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'description_hover_background',
                'selector' => '{{WRAPPER}} .bw-il-menu li:hover .bw-il-description',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'description_hover_text_shadow',
                'selector' => '{{WRAPPER}} .bw-il-menu li:hover .bw-il-description',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'description_padding',
            [
                'label' => __( 'Padding', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => __( 'Margin', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_text_align',
            [
                'label' => __( 'Text Align', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bw-il-description' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls for the image container.
     *
     * This method registers the style controls for the image container within the widget.
     * It allows users to customize the background, border, box shadow, padding, and margin for the image container.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_style_image_container_controls() {

        $this->start_controls_section(
            'style_container_section',
            [
                'label' => __( 'Hover Image Container', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Use global .bw-il-uid-{{ID}} - hover layer is portaled to <body>.
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_bg',
                'selector' => '.bw-il-uid-{{ID}}',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '.bw-il-uid-{{ID}}',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '.bw-il-uid-{{ID}}',
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __( 'Padding', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '.bw-il-uid-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => __( 'Margin', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '.bw-il-uid-{{ID}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls for the hover image.
     *
     * This method registers the style controls for the hover image section of the widget.
     * It allows users to customize the opacity, border, box shadow, border radius,
     * margin, and padding for the hover image in both normal and hover states.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_style_hover_image_controls() {

        $this->start_controls_section(
            'style_hover_image_section',
            [
                'label' => __( 'Hover Image', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Global .bw-il-uid-{{ID}} - hover layer is portaled to <body> for clean follow.
        $this->add_responsive_control(
            'hover_image_width',
            [
                'label' => __('Image Width', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 80,
                        'max' => 520,
                    ],
                    'vw' => [
                        'min' => 10,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 280,
                ],
                'description' => __( 'Try 240px to 320px for the hover image size.', 'blackwidgets' ),
                'selectors' => [
                    '.bw-il-uid-{{ID}}' => 'width: {{SIZE}}{{UNIT}}; max-width: min(420px, {{SIZE}}{{UNIT}}, 42vw);',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_image_height',
            [
                'label' => __('Image Height', 'blackwidgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 80,
                        'max' => 720,
                    ],
                ],
                'description' => __( 'Leave empty to auto-scale height and show the full image at its own aspect ratio.', 'blackwidgets' ),
                'selectors' => [
                    '.bw-il-uid-{{ID}}' => 'height: {{SIZE}}{{UNIT}}; aspect-ratio: auto;',
                ],
            ]
        );

        $this->add_control(
            'hover_image_opacity',
            [
                'label' => __( 'Opacity', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ] ],
                'selectors' => [
                    '.bw-il-uid-{{ID}} .bw-il-hover-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'hover_image_border',
                'selector' => '.bw-il-uid-{{ID}} .bw-il-hover-image',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hover_image_shadow',
                'selector' => '.bw-il-uid-{{ID}} .bw-il-hover-image',
            ]
        );

        $this->add_responsive_control(
            'hover_image_border_radius',
            [
                'label' => __( 'Border Radius', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => 14,
                    'right' => 14,
                    'bottom' => 14,
                    'left' => 14,
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '.bw-il-uid-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '.bw-il-uid-{{ID}} .bw-il-hover-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_image_margin',
            [
                'label' => __( 'Margin', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '.bw-il-uid-{{ID}} .bw-il-hover-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_image_padding',
            [
                'label' => __( 'Padding', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '.bw-il-uid-{{ID}} .bw-il-hover-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register GSAP hover effect controls.
     *
     * This method registers controls for customizing GSAP-based hover effects, such as rotation, translation, brightness,
     * fade-in/out durations, and animation duration for the image and main widget elements.
     *
     * @since 1.4.0
     * @access private
     * @return void
     */
    private function register_style_gsap_controls() {

        $this->start_controls_section(
            'style_gsap_effects',
            [
                'label' => __( 'GSAP Hover Effects', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gsap_hover_tabs');

        $this->start_controls_tab(
            'gsap_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'blackwidgets' ),
            ]
        );

        $this->add_control('gsap_brightness_normal', [
            'label' => __( 'Brightness', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0.5, 'max' => 3, 'step' => 0.1 ] ],
            'default' => [ 'size' => 1 ],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'gsap_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'blackwidgets' ),
            ]
        );

        $this->add_control('gsap_max_rotation_x', [
            'label' => __( 'Max Rotation X', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -90, 'max' => 90 ] ],
            'default' => [ 'size' => 8 ],
        ]);

        $this->add_control('gsap_max_rotation_y', [
            'label' => __( 'Max Rotation Y', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -90, 'max' => 90 ] ],
            'default' => [ 'size' => 8 ],
        ]);

        $this->add_control('gsap_rotation_z_strength', [
            'label' => __( 'Rotation Z Strength', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -5, 'max' => 5, 'step' => 0.1 ] ],
            'default' => [ 'size' => 0.12 ],
        ]);

        $this->add_control('gsap_max_translate_x', [
            'label' => __( 'Max Translate X', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -300, 'max' => 300 ] ],
            'default' => [ 'size' => 14 ],
        ]);

        $this->add_control('gsap_max_translate_y', [
            'label' => __( 'Max Translate Y', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -300, 'max' => 300 ] ],
            'default' => [ 'size' => 14 ],
        ]);

        $this->add_control('gsap_brightness_strength', [
            'label' => __( 'Brightness on Hover', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0.5, 'max' => 3, 'step' => 0.1 ] ],
            'default' => [ 'size' => 1.12 ],
        ]);

        $this->add_control('gsap_fadein_duration', [
            'label' => __( 'Image Fade-In Duration', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ] ],
            'default' => [ 'size' => 0.35 ],
        ]);

        $this->add_control('gsap_fadeout_duration', [
            'label' => __( 'Image Fade-Out Duration', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ] ],
            'default' => [ 'size' => 0.25 ],
        ]);

        $this->add_control('gsap_animation_duration', [
            'label' => __( 'Main Animation Duration', 'blackwidgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.1 ] ],
            'default' => [ 'size' => 0.28 ],
        ]);

        $this->add_control('gsap_ease', [
            'label'   => __( 'Ease', 'blackwidgets' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'power2.out',
            'options' => [
                'power2.out'  => 'power2.out',
                'power2.in'   => 'power2.in',
                'power3.out'  => 'power3.out',
                'power3.inOut'=> 'power3.inOut',
                'none'        => 'none',
                'sine.out'    => 'sine.out',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render title widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.4.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $safe_number = function($key, $default = 0) use ($settings) {
            return isset($settings[$key]['size']) ? floatval($settings[$key]['size']) : $default;
        };

        $safe_string = function($key, $default = '') use ($settings) {
            return isset($settings[$key]) ? sanitize_text_field($settings[$key]) : $default;
        };

        $gsap_data = [
            'maxRotX' => $safe_number('gsap_max_rotation_x', 8),
            'maxRotY' => $safe_number('gsap_max_rotation_y', 8),
            'maxTransX' => $safe_number('gsap_max_translate_x', 14),
            'maxTransY' => $safe_number('gsap_max_translate_y', 14),
            'brightnessNormal' => $safe_number('gsap_brightness_normal', 1),
            'brightnessStrength' => $safe_number('gsap_brightness_strength', 1.12),
            'duration' => $safe_number('gsap_animation_duration', 0.28),
            'ease' => $safe_string('gsap_ease', 'power2.out'),
            'fadeIn' => $safe_number('gsap_fadein_duration', 0.35),
            'fadeOut' => $safe_number('gsap_fadeout_duration', 0.25),
            'rotZStrength' => $safe_number('gsap_rotation_z_strength', 0.12),
        ];

        $encoded_data = wp_json_encode( $gsap_data );

        $menu_items = ! empty( $settings['menu_items'] ) && is_array( $settings['menu_items'] ) ? $settings['menu_items'] : [];
        ?>

        <!-- Main container with encoded GSAP settings in a data attribute -->
        <div class="bw-interactive-link-box" data-gsap-settings="<?php echo esc_attr( $encoded_data ); ?>">
            <ul class="bw-il-menu">
                <?php foreach ( $menu_items as $item ) :
                    if ( ! is_array( $item ) ) {
                        continue;
                    }
                    $tag = tag_escape( $item['tag_type'] ?? 'h3' );
                    $title = esc_html( sanitize_text_field( $item['title'] ?? '' ) );
                    $description = esc_html( sanitize_text_field( $item['short_description'] ?? '' ) );
                    $img = esc_url_raw( $item['image']['url'] ?? '' );
                    $url = esc_url( $item['link']['url'] ?? '#' );
                    $target = !empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                    $nofollow = !empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                    ?>
                    <!-- Each menu item with data-image for hover preview -->
                    <li data-image="<?php echo esc_url( $img ); ?>">
                        <a href="<?php echo esc_url( $url ); ?>"<?php echo $target . $nofollow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                            <<?php echo esc_attr( $tag ); ?> class="bw-il-title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $tag ); ?>>
                        </a>
                        <?php if ( $description ) : ?>
                            <p class="bw-il-description"><?php echo esc_html( $description ); ?></p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Decorative mouse-follow preview; does not receive pointer events. -->
            <div class="bw-il-image-container" aria-hidden="true">
                <div class="bw-il-hover-wrapper bw-il-uid-<?php echo esc_attr( $this->get_id() ); ?>">
                    <img class="bw-il-hover-image" src="" alt="" /> <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
                </div>
            </div>
        </div>

        <?php
    }
}
