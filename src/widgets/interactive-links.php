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


class InteractiveLinks extends \Elementor\Widget_Base {

    /**
     * Constructor for the Interactive Links widget.
     *
     * @since ?.?.?
     *
     * This method is automatically called when the widget is instantiated.
     * It registers the required CSS and JS files specific to the Interactive Links widget.
     *
     * @param array $data Optional. Widget data. Default empty array.
     * @param mixed $args Optional. Additional arguments. Default null.
     */
    public function __construct( $data = [], $args = null ) {
        // Call parent Elementor widget constructor
        parent::__construct( $data, $args );

        // Register custom widget stylesheet
        wp_register_style(
            'black-widgets-gsap-interactive-links',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/css/interactive-links.css',
            [], // Dependencies
            BLACK_WIDGETS_VERSION // Version for cache busting
        );

        // Register custom widget script with GSAP dependency
        wp_register_script(
            'black-widgets-gsap-interactive-links',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/interactive-links.js',
            [ 'jquery', 'GSAP' ], // Script dependencies
            BLACK_WIDGETS_VERSION // Version for cache busting
        );
    }

    /**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since ?.?.?
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
	 * @since ?.?.?
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black Interactive Links', 'black-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since ?.?.?
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
     * @since ?.?.?
	 */
	public function get_categories() {
		return [ 'black_widgets' ];
	}

    /**
     * Returns an array of style handles to be enqueued with this widget.
     *
     * @return array List of style handles.
     * @since ?.?.?
     */
    public function get_style_depends() {
        return [ 'black-widgets-gsap-interactive-links' ];
    }

    /**
     * Returns an array of script handles to be enqueued with this widget.
     *
     * @return array List of script handles.
     * @since ?.?.?
     */
    public function get_script_depends() {
        return [ 'black-widgets-gsap-interactive-links' ];
    }

    /**
     * Determines whether this widget includes dynamic content.
     *
     * @return bool False, because the widget has no dynamic content.
     * @since ?.?.?
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
     * @since ?.?.?
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
     * @since ?.?.?
     * @access private
     * @return void
     */
    private function register_settings_controls() {
        // Start controls section for content
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Items',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Control for widget type selection
        $this->add_control(
            'widget_type',
            [
                'label' => esc_html__( 'Select Type', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'default', 'black-widgets' ),
                ],
                'default' => 'default',
            ]
        );

        // Initialize repeater for creating multiple items
        $repeater = new \Elementor\Repeater();

        // Control for title of each item
        $repeater->add_control(
            'title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Item',
            ]
        );

        // Control for tag type selection for the title
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

        // Control for short description of each item
        $repeater->add_control(
            'short_description',
            [
                'label' => 'Short Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => 'Enter your short description...',
                'default' => '',
            ]
        );

        // Control for adding a link to each item
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

        // Control for adding an image to each item
        $repeater->add_control(
            'image',
            [
                'label' => 'Image',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => ['url' => BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/img/dsgn-in-black-widgets.jpg'],
            ]
        );

        // Control to list the items (repeater field)
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

        // End controls section
        $this->end_controls_section();
    }

    /**
     * Register all style controls.
     *
     * This method organizes the style-related controls into separate modular sections
     * such as box, list, items, titles, descriptions, and image hover effects.
     *
     * @since ?.?.?
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
     * @since ?.?.?
     * @access private
     * @return void
     */
    private function register_style_box_controls() {
        // Start controls section for widget box styling
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Box Style', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Start tab for normal state of the widget box
        $this->start_controls_tabs('widget_box_style_tabs');

        // Normal state tab: for regular widget box styling
        $this->start_controls_tab(
            'widget_box_style_normal',
            [
                'label' => esc_html__( 'Normal', 'black-widgets' ),
            ]
        );

        // Control for background styling (classic, gradient, or video background)
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_background',
                'label' => esc_html__( 'Background', 'black-widgets' ),
                'types' => [ 'classic', 'gradient', 'video' ],
                'selector' => '{{WRAPPER}} .bw-interactive-link-box',
            ]
        );

        // Control for border styling (width, color, radius, etc.)
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_box_border',
                'label' => esc_html__( 'Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-interactive-link-box',
            ]
        );

        $this->add_control(
            'widget_box_border_radius',
            [
                'label' => __('Border Radius', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for padding (responsive, accepts px, %, em units)
        $this->add_responsive_control(
            'widget_box_padding',
            [
                'label' => esc_html__( 'Padding', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for margin (responsive, accepts px, %, em units)
        $this->add_responsive_control(
            'widget_box_margin',
            [
                'label' => esc_html__( 'Margin', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for box shadow (includes spread, blur, and color settings)
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-interactive-link-box',
            ]
        );

        // End normal state tab
        $this->end_controls_tab();

        // Start hover state tab: for widget box hover effects
        $this->start_controls_tab(
            'widget_box_style_hover',
            [
                'label' => esc_html__( 'Hover', 'black-widgets' ),
            ]
        );

        // Control for hover background styling (classic, gradient)
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_hover_background',
                'label' => esc_html__( 'Hover Background', 'black-widgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-interactive-link-box:hover',
            ]
        );

        // Control for hover border styling
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_box_hover_border',
                'label' => esc_html__( 'Hover Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-interactive-link-box:hover',
            ]
        );

        $this->add_control(
            'widget_box_hover_border_radius',
            [
                'label' => __('Border Radius', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for hover padding (responsive, accepts px, %, em units)
        $this->add_responsive_control(
            'widget_box_hover_padding',
            [
                'label' => esc_html__( 'Padding', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for hover margin (responsive, accepts px, %, em units)
        $this->add_responsive_control(
            'widget_box_hover_margin',
            [
                'label' => esc_html__( 'Margin', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-interactive-link-box:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for hover box shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_hover_shadow',
                'label' => esc_html__( 'Hover Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-interactive-link-box:hover',
            ]
        );

        // End hover state tab
        $this->end_controls_tab();

        // End control tabs
        $this->end_controls_tabs();

        // End controls section
        $this->end_controls_section();
    }

    /**
     * Register style controls for the list items.
     *
     * This method registers style controls for the individual list items (`li`) within the widget.
     * It allows users to customize the typography, text color, background color, and hover effects
     * (text color, background color, and box shadow) for the list items.
     *
     * @since ?.?.?
     * @access private
     * @return void
     */
    private function register_style_li_controls() {
        // Start controls section for list item styling
        $this->start_controls_section(
            'style_li',
            [
                'label' => __( 'List Items', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography control for list items
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'li_typography',
                'selector' => '{{WRAPPER}} .bw-il-menu li',
            ]
        );

        // Control for setting the gap between items in the list
        $this->add_control(
            'li_gap',
            [
                'label' => __( 'Items Gap', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Start tabs for normal and hover styles
        $this->start_controls_tabs( 'tabs_li_style' );

        // Normal state controls for list items
        $this->start_controls_tab(
            'tab_li_normal',
            [
                'label' => __( 'Normal', 'black-widgets' ),
            ]
        );

        // Control for setting the text color of list items
        $this->add_control(
            'li_text_color',
            [
                'label' => __( 'Text Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Control for setting the background of list items
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'li_background',
                'selector' => '{{WRAPPER}} .bw-il-menu li',
            ]
        );

        // Control for border styling (width, color, radius, etc.)
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'li_border',
                'label' => esc_html__( 'Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-il-menu li',
            ]
        );

        $this->add_control(
            'li_border_radius',
            [
                'label' => __('Border Radius', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for padding (responsive, accepts px, %, em units)
        $this->add_responsive_control(
            'li_padding',
            [
                'label' => esc_html__( 'Padding', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for margin (responsive, accepts px, %, em units)
        $this->add_responsive_control(
            'li_margin',
            [
                'label' => esc_html__( 'Margin', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for box shadow (includes spread, blur, and color settings)
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'li_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-il-menu li',
            ]
        );

        $this->end_controls_tab();

        // Hover state controls for list items
        $this->start_controls_tab(
            'tab_li_hover',
            [
                'label' => __( 'Hover', 'black-widgets' ),
            ]
        );

        // Control for setting the hover text color of list items
        $this->add_control(
            'li_hover_color',
            [
                'label' => __( 'Hover Text Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Control for setting the hover background color of list items
        $this->add_control(
            'li_hover_bg',
            [
                'label' => __( 'Hover Background', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Control for border styling (width, color, radius, etc.)
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'li_hover_border',
                'label' => esc_html__( 'Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-il-menu li:hover',
            ]
        );

        $this->add_control(
            'li_hover_border_radius',
            [
                'label' => __('Border Radius', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for padding (responsive, accepts px, %, em units)
        $this->add_responsive_control(
            'li_hover_padding',
            [
                'label' => esc_html__( 'Padding', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for margin (responsive, accepts px, %, em units)
        $this->add_responsive_control(
            'li_hover_margin',
            [
                'label' => esc_html__( 'Margin', 'black-widgets' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Control for box shadow (includes spread, blur, and color settings)
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'li_hover_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-il-menu li:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // End controls section for list item styling
        $this->end_controls_section();
    }

    /**
     * Register style controls for links and titles.
     *
     * This method registers the style controls for links and titles within the widget.
     * It allows users to customize the typography, color, hover color, and padding for titles and link elements.
     *
     * @since ?.?.?
     * @access private
     * @return void
     */
    private function register_style_links_titles_controls() {
        // Start controls section for links and titles styling
        $this->start_controls_section(
            'style_links_titles',
            [
                'label' => __( 'Links & Titles', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography control for titles and links
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bw-il-title, {{WRAPPER}} .bw-il-menu a',
            ]
        );

        // Start tabs for normal and hover styles
        $this->start_controls_tabs( 'tabs_title_style' );

        // Normal state controls for title and link
        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => __( 'Normal', 'black-widgets' ),
            ]
        );

        // Control for setting the color of the title and link in normal state
        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bw-il-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover state controls for title and link
        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label' => __( 'Hover', 'black-widgets' ),
            ]
        );

        // Control for setting the hover color of the title
        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Title Hover Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover .bw-il-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Control for setting padding around titles
        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __( 'Title Padding', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // End controls section for links and titles styling
        $this->end_controls_section();
    }

    /**
     * Register style controls for description text.
     *
     * This method registers the style controls for the description text within the widget.
     * It allows users to customize the typography, color, background, text shadow, padding, margin, and text alignment
     * for the description text in both normal and hover states.
     *
     * @since ?.?.?
     * @access private
     * @return void
     */
    private function register_style_description_controls() {

        // Start controls section for description text styling
        $this->start_controls_section(
            'style_description',
            [
                'label' => __( 'Description', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography control for description text
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .bw-il-description',
            ]
        );

        // Start tabs for normal and hover styles
        $this->start_controls_tabs( 'tabs_description_style' );

        // Normal state controls for description text
        $this->start_controls_tab(
            'tab_description_normal',
            [
                'label' => __( 'Normal', 'black-widgets' ),
            ]
        );

        // Control for setting the color of the description text in normal state
        $this->add_control(
            'description_color',
            [
                'label' => __( 'Text Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Background control for description text in normal state
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'description_background',
                'selector' => '{{WRAPPER}} .bw-il-description',
            ]
        );

        // Text shadow control for description text in normal state
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'description_text_shadow',
                'selector' => '{{WRAPPER}} .bw-il-description',
            ]
        );

        $this->end_controls_tab();

        // Hover state controls for description text
        $this->start_controls_tab(
            'tab_description_hover',
            [
                'label' => __( 'Hover', 'black-widgets' ),
            ]
        );

        // Control for setting the hover color of the description text
        $this->add_control(
            'description_hover_color',
            [
                'label' => __( 'Hover Color', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-menu li:hover .bw-il-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Background control for description text in hover state
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'description_hover_background',
                'selector' => '{{WRAPPER}} .bw-il-menu li:hover .bw-il-description',
            ]
        );

        // Text shadow control for description text in hover state
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'description_hover_text_shadow',
                'selector' => '{{WRAPPER}} .bw-il-menu li:hover .bw-il-description',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Padding control for description text
        $this->add_responsive_control(
            'description_padding',
            [
                'label' => __( 'Padding', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin control for description text
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => __( 'Margin', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Text alignment control for description text
        $this->add_responsive_control(
            'description_text_align',
            [
                'label' => __( 'Text Align', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'black-widgets' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'black-widgets' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'black-widgets' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bw-il-description' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // End controls section for description text styling
        $this->end_controls_section();
    }

    /**
     * Register style controls for the image container.
     *
     * This method registers the style controls for the image container within the widget.
     * It allows users to customize the background, border, box shadow, padding, and margin for the image container.
     *
     * @since ?.?.?
     * @access private
     * @return void
     */
    private function register_style_image_container_controls() {

        // Start controls section for image container styling
        $this->start_controls_section(
            'style_container_section',
            [
                'label' => __( 'Hover Image Container', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Background control for the image container
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_bg',
                'selector' => '{{WRAPPER}} .bw-il-hover-wrapper',
            ]
        );

        // Border control for the image container
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} ..bw-il-hover-wrapper',
            ]
        );

        // Box shadow control for the image container
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .bw-il-hover-wrapper',
            ]
        );

        // Padding control for the image container
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __( 'Padding', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-hover-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin control for the image container
        $this->add_responsive_control(
            'container_margin',
            [
                'label' => __( 'Margin', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-hover-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // End controls section for image container styling
        $this->end_controls_section();
    }

    /**
     * Register style controls for the hover image.
     *
     * This method registers the style controls for the hover image section of the widget.
     * It allows users to customize the opacity, border, box shadow, border radius,
     * margin, and padding for the hover image in both normal and hover states.
     *
     * @since ?.?.?
     * @access private
     * @return void
     */
    private function register_style_hover_image_controls() {

        // Start the section for hover image styling
        $this->start_controls_section(
            'style_hover_image_section',
            [
                'label' => __( 'Hover Image', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'hover_image_width',
            [
                'label' => __('Image Width', 'black-widgets'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-hover-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Opacity control for normal state
        $this->add_control(
            'hover_image_opacity',
            [
                'label' => __( 'Opacity', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bw-il-hover-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        // Border control for normal state
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'hover_image_border',
                'selector' => '{{WRAPPER}} .bw-il-hover-image',
            ]
        );

        // Box shadow control for normal state
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'hover_image_shadow',
                'selector' => '{{WRAPPER}} .bw-il-hover-image',
            ]
        );

        // Border radius control for the hover image
        $this->add_responsive_control(
            'hover_image_border_radius',
            [
                'label' => __( 'Border Radius', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-hover-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin control for the hover image
        $this->add_responsive_control(
            'hover_image_margin',
            [
                'label' => __( 'Margin', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-hover-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding control for the hover image
        $this->add_responsive_control(
            'hover_image_padding',
            [
                'label' => __( 'Padding', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .bw-il-hover-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // End the section for hover image styling
        $this->end_controls_section();
    }

    /**
     * Register GSAP hover effect controls.
     *
     * This method registers controls for customizing GSAP-based hover effects, such as rotation, translation, brightness,
     * fade-in/out durations, and animation duration for the image and main widget elements.
     *
     * @since ?.?.?
     * @access private
     * @return void
     */
    private function register_style_gsap_controls() {

        // Start the section for GSAP hover effects styling
        $this->start_controls_section(
            'style_gsap_effects',
            [
                'label' => __( 'GSAP Hover Effects', 'black-widgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Start the tabs for normal and hover states
        $this->start_controls_tabs('gsap_hover_tabs');

        // Normal state tab
        $this->start_controls_tab(
            'gsap_tab_normal',
            [
                'label' => esc_html__( 'Normal', 'black-widgets' ),
            ]
        );

        // Brightness control for normal state
        $this->add_control('gsap_brightness_normal', [
            'label' => __( 'Brightness', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0.5, 'max' => 3, 'step' => 0.1 ] ],
            'default' => [ 'size' => 1 ],
        ]);

        // End the normal state tab
        $this->end_controls_tab();

        // Hover state tab
        $this->start_controls_tab(
            'gsap_tab_hover',
            [
                'label' => esc_html__( 'Hover', 'black-widgets' ),
            ]
        );

        // Max Rotation X control for hover state
        $this->add_control('gsap_max_rotation_x', [
            'label' => __( 'Max Rotation X', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -90, 'max' => 90 ] ],
            'default' => [ 'size' => 15 ],
        ]);

        // Max Rotation Y control for hover state
        $this->add_control('gsap_max_rotation_y', [
            'label' => __( 'Max Rotation Y', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -90, 'max' => 90 ] ],
            'default' => [ 'size' => 15 ],
        ]);

        // Rotation Z strength control for hover state
        $this->add_control('gsap_rotation_z_strength', [
            'label' => __( 'Rotation Z Strength', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -5, 'max' => 5, 'step' => 0.1 ] ],
            'default' => [ 'size' => 0.5 ],
        ]);

        // Max Translate X control for hover state
        $this->add_control('gsap_max_translate_x', [
            'label' => __( 'Max Translate X', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -300, 'max' => 300 ] ],
            'default' => [ 'size' => 40 ],
        ]);

        // Max Translate Y control for hover state
        $this->add_control('gsap_max_translate_y', [
            'label' => __( 'Max Translate Y', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => -300, 'max' => 300 ] ],
            'default' => [ 'size' => 40 ],
        ]);

        // Brightness on hover control
        $this->add_control('gsap_brightness_strength', [
            'label' => __( 'Brightness on Hover', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0.5, 'max' => 3, 'step' => 0.1 ] ],
            'default' => [ 'size' => 1.5 ],
        ]);

        // Image fade-in duration control for hover state
        $this->add_control('gsap_fadein_duration', [
            'label' => __( 'Image Fade-In Duration', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ] ],
            'default' => [ 'size' => 0.4 ],
        ]);

        // Image fade-out duration control for hover state
        $this->add_control('gsap_fadeout_duration', [
            'label' => __( 'Image Fade-Out Duration', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ] ],
            'default' => [ 'size' => 0.4 ],
        ]);

        // Main animation duration control for hover state
        $this->add_control('gsap_animation_duration', [
            'label' => __( 'Main Animation Duration', 'black-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => [ 'px' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.1 ] ],
            'default' => [ 'size' => 0.3 ],
        ]);

        // End the hover state tab
        $this->end_controls_tab();

        // End the tabs section
        $this->end_controls_tabs();

        // End the GSAP hover effects section
        $this->end_controls_section();
    }

    /**
	 * Render title widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since ?.?.?
	 * @access protected
	 */
    protected function render() {
        // Get the settings from the Elementor widget panel
        $settings = $this->get_settings_for_display();

        // Helper function to safely retrieve numeric values from settings
        $safe_number = function($key, $default = 0) use ($settings) {
            return isset($settings[$key]['size']) ? floatval($settings[$key]['size']) : $default;
        };

        // Helper function to safely retrieve string values from settings
        $safe_string = function($key, $default = '') use ($settings) {
            return isset($settings[$key]) ? sanitize_text_field($settings[$key]) : $default;
        };

        // Build the GSAP settings array with sanitized and validated data
        $gsap_data = [
            'maxRotX' => $safe_number('gsap_max_rotation_x', 0),
            'maxRotY' => $safe_number('gsap_max_rotation_y', 0),
            'maxTransX' => $safe_number('gsap_max_translate_x', 0),
            'maxTransY' => $safe_number('gsap_max_translate_y', 0),
            'brightnessStrength' => $safe_number('gsap_brightness_strength', 1),
            'duration' => $safe_number('gsap_animation_duration', 0.3),
            'ease' => $safe_string('gsap_ease', 'power3.out'),
            'fadeIn' => $safe_number('gsap_fadein_duration', 0.4),
            'fadeOut' => $safe_number('gsap_fadeout_duration', 0.4),
            'rotZStrength' => $safe_number('gsap_rotation_z_strength', 0.5),
        ];

        // Encode the GSAP settings as JSON and escape for safe HTML output
        $encoded_data = esc_attr( wp_json_encode( $gsap_data ) );
        ?>

        <!-- Main container with encoded GSAP settings in a data attribute -->
        <div class="bw-interactive-link-box" data-gsap-settings="<?php echo $encoded_data; ?>">
            <ul class="bw-il-menu">
                <?php foreach ( $settings['menu_items'] as $item ) :
                    // Sanitize individual item fields
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
                        <a href="<?php echo $url; ?>"<?php echo $target . $nofollow; ?>>
                            <<?php echo $tag; ?> class="bw-il-title"><?php echo $title; ?></<?php echo $tag; ?>>
                        </a>
                        <?php if ( $description ) : ?>
                            <p class="bw-il-description"><?php echo $description; ?></p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Container for the hover image effect -->
            <div class="bw-il-image-container">
                <div class="bw-il-hover-wrapper">
                    <img class="bw-il-hover-image" src="" alt="Hover Preview" />
                </div>
            </div>
        </div>

        <?php
    }
}

class_alias('Modernaweb\BlackWidgets\Widgets\InteractiveLinks', 'Black_Widgets\BLACK_WIDGETS_INTERACTIVE_LINKS');
