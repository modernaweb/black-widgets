<?php
namespace Elementor;
namespace BLACK_WIDGETS_Modernaweb\Includes\Widgets;
namespace Black_Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Color;
use Elementor\Group_Control_Text_Shadow;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BLACK_WIDGETS_GSAP_Trigger extends \Elementor\Widget_Base {

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
		return 'b_gsap_trigger';
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
		return __( 'Black Trigger', 'blackwidgets' );
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
		return 'eicon-scroll';
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

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		// Start
		// Content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => __( 'Select Content', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'html',
				'options' => [
					'html' 				=> __( 'HTML', 'blackwidgets' ),
					// 'text' 				=> __( 'Text', 'blackwidgets' ),
					// 'shape'				=> __( 'Shape', 'blackwidgets' ),
					// 'nested_div' 		=> __( 'Nested Div', 'blackwidgets' ),
					// 'image_sequence' 	=> __( 'Image Sequence', 'blackwidgets' ),
					// 'video_sequence' 	=> __( 'Video Sequence', 'blackwidgets' ),
				],
				'description' => __( 'We create some skin before, you can use these or no! make a new custom type.', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_html_content',
			[
				'label' => __( 'HTML Code', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 21,
				'default' =>
'
<h3 class="bw-eg" style="font-size: 170px;">
GROW YOUR
</h3>
<h3 class="bw-eg" style="font-size: 170px;">
BUSINESS
</h3>
<h3 class="bw-eg" style="font-size: 170px;">
ONLINE
</h3>
',
				'description' => __( 'Put your HTML tags here, please check the example structure', 'blackwidgets' ),
			]
		);

        // Message of the Alert
		$this->add_control(
			'widget_html_class',
			[
				'label' => __( 'Shared Class', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'bw-eg', 'blackwidgets' ),
                'description' => __( 'use the shared class for each line, like the example', 'blackwidgets' ),
			]
		);


		$this->end_controls_section();
		// End

		// Start
		// Content section
		$this->start_controls_section(
			'content_html_js',
			[
				'label' => __( 'JS for HTML Content', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'widget_html_js_gsap_timeline_scroll_trigger',
			[
				'label' => __( 'JS: GSAP timeline > scrollTrigger', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 21,
				'default' =>
'
trigger: ".bw-eg",
start: "top bottom",
end: "top",
toggleActions: "restart none none reset",
start: "top center",
end: "bottom",
// markers: true, // enable it to to show the markers
',
				'description' => __( 'Put your transform properties tags here for "from"', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_html_js_tl_from',
			[
				'label' => __( 'JS: GSAP tl.from', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 21,
				'default' => 
'
opacity: 1,
rotation: 3,
scaleX: 1,
scaleY: 0,
scaleZ: 0,
skewX: "26",
skewY: "-42",
x: 0,
y: 0,
z: 0,
x: "0",
y: "25",
autoAlpha:1,
transformStyle: "preserve-3d",
',
				'description' => __( 'Put your transform properties tags here for "from"', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_html_js_tl_to',
			[
				'label' => __( 'JS: GSAP tl.to', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 21,
				'default' => 
'
opacity: 1,
rotation: 0,
scaleX: 1,
scaleY: 1,
scaleZ: 0,
skewX: 0,
skewY: 0,
x: 0,
y: 0,
z: 0,
x: "0",
y: "0",
transformStyle: "preserve-3d",
',
				'description' => __( 'Put your transform properties tags here for "to"', 'blackwidgets' ),
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Box Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-gsap-code-box',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-gsap-code-box',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-gsap-code-box',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-gsap-code-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-gsap-code-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_box_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-gsap-code-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
        // End

		// Start
		// Typography section
		$this->start_controls_section(
			'typo_section',
			[
				'label' => __( 'Typography Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
        );

		// Color
		$this->add_control(
			'style_alert_color',
			[
				'label' => __( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-gsap-code-box' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_alert_typography1',
				'label' => __( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-gsap-code-box',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_alert_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-gsap-code-box',
			]
        );
        
		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'style_icon_alert_size',
			[
				'label' => __( 'Icon Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-gsap-code-box i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		// Color
		$this->add_control(
			'style_icon_alert_color',
			[
				'label' => __( 'Icon Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-gsap-code-box i' => 'color: {{VALUE}}',
				],
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_Icon_alert_text_shadow',
				'label' => __( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-gsap-code-box i',
			]
        );

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

		$settings   	= $this->get_settings_for_display();

		// Variables
        $type 	        = isset($settings['widget_type']) ? $settings['widget_type'] : '';
        $message        = isset($settings['widget_text']) ? $settings['widget_text'] : '';

		$options 		= get_option('plugin_options') ? get_option('plugin_options') : '';
        $gsap_options  	= isset($options['gsap_options']) ? $options['gsap_options'] : '';

		// Content - HTML
		$html_code		= isset($settings['widget_html_content'])	? $settings['widget_html_content']		: '';
		$shared_class	= isset($settings['widget_html_class'])	? $settings['widget_html_class']		: '';
		$html_trigger	= isset($settings['widget_html_js_gsap_timeline_scroll_trigger'])	? $settings['widget_html_js_gsap_timeline_scroll_trigger']		: '';
		$html_js_from	= isset($settings['widget_html_js_tl_from'])	? $settings['widget_html_js_tl_from']		: '';
		$html_js_to		= isset($settings['widget_html_js_tl_to'])	? $settings['widget_html_js_tl_to']		: '';

		$data_id                	= 'bw_' . uniqid();
		$script_id              	= '#' . $data_id;


		// Render
		echo '<div class="bw-gsap-code-box" id="'. $script_id .'">';
			echo $html_code;
		echo '</div>';


		if ( isset($gsap_options) && !empty($gsap_options) ) {
			echo '<script>
				jQuery(window).ready(function($) {
					gsap.registerPlugin(ScrollTrigger);
					const tl = gsap.timeline({
						scrollTrigger: {
						'. $html_trigger .'
					}});
					tl.from(".'. $shared_class .'", {
						'. $html_js_from .'
					})
					tl.to(".'. $shared_class .'", {
						'. $html_js_to .'
					})
				});
				</script>';
		}

	}

}
