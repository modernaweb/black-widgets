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
use Elementor\Group_Control_Image_Size;
// use Elementor\Group_Control_Css_Filter;

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
	protected function register_controls() {




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
					'image' 				=> __( 'Image', 'blackwidgets' ),
					// 'image_sequence' 	=> __( 'Image Sequence', 'blackwidgets' ),
					// 'video_sequence' 	=> __( 'Video Sequence', 'blackwidgets' ),
					// 'video_sequence' 	=> __( 'Elementor Template', 'blackwidgets' ),
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
				'condition'  => [
					'widget_type' => [
						'html',
					],
				],
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
				'condition'  => [
					'widget_type' => [
						'html',
					],
				],
			]
		);


		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition'  => [
					'widget_type' => [
						'image',
					],
				],
			]
        );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'full',
				'condition'  => [
					'widget_type' => [
						'image',
					],
				],
			]
        );


		$this->end_controls_section();
		// End

		// Start
		// Content section
		$this->start_controls_section(
			'content_image_js',
			[
				'label' => __( 'JS for Image', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition'  => [
					'widget_type' => [
						'image',
					],
				],
			]
		);

		$this->add_control(
			'image_trigger_class',
			[
				'label' => __( 'Add A Unique Trigger Class', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'bw-image-1', 'blackwidgets' ),
				'placeholder' => __( 'bw-image-1', 'blackwidgets' ),
				'description' => __( 'it should be unique and add manually', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'image_start_point',
			[
				'label' => __( 'Start Point', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'center bottom', 'blackwidgets' ),
				'placeholder' => __( 'top bottom', 'blackwidgets' ),
				'description' => __( '----', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'image_end_point',
			[
				'label' => __( 'End Point', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'center top', 'blackwidgets' ),
				'placeholder' => __( 'top bottom', 'blackwidgets' ),
				'description' => __( '----', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'image_toggle_actions',
			[
				'label' => __( 'Toggle Actions', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'none none none none', 'blackwidgets' ),
				'placeholder' => __( 'none none none none', 'blackwidgets' ),
				'description' => __( '----', 'blackwidgets' ),
				]
			);

		$this->add_control(
			'image_scrub',
			[
				'label' => __( 'Scrub', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'true', 'blackwidgets' ),
				'placeholder' => __( 'false', 'blackwidgets' ),
				'description' => __( '----', 'blackwidgets' ),
				]
			);
			
		// // Enable Title Section
		// $this->add_control(
		// 	'image_markers',
		// 	[
		// 		'label' 		=> __( 'Markers', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::SWITCHER,
		// 		'label_on' 		=> __( 'true', 'blackwidgets' ),
		// 		'label_off' 	=> __( 'false', 'blackwidgets' ),
		// 		'default' 		=> 'false',
		// 		'description' => __( 'enable it to to show the markers', 'blackwidgets' ),
		// 	]
		// );


		$this->add_control(
			'hr_image_1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'element_opacity_from',
			[
				'label' => __( 'Opacity', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'blackwidgets' ),
				'placeholder' => __( '1', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_duration_from',
			[
				'label' => __( 'Duration', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0.9', 'blackwidgets' ),
				'placeholder' => __( '0.4', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_rotationx_from',
			[
				'label' => __( 'RotationX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '3', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_rotationy_from',
			[
				'label' => __( 'RotationY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '3', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_scalex_from',
			[
				'label' => __( 'ScaleX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'blackwidgets' ),
				'placeholder' => __( '1', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_scaley_from',
			[
				'label' => __( 'ScaleY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'blackwidgets' ),
				'placeholder' => __( '1', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_scalez_from',
			[
				'label' => __( 'ScaleZ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_skewx_from',
			[
				'label' => __( 'SkewX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '26', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_skewy_from',
			[
				'label' => __( 'SkewY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '-42', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_movex_from',
			[
				'label' => __( 'MoveX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_movey_from',
			[
				'label' => __( 'MoveY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_movez_from',
			[
				'label' => __( 'MoveZ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_autoalpha_from',
			[
				'label' => __( 'Auto Alpha', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'blackwidgets' ),
				'placeholder' => __( '1', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_perspective_from',
			[
				'label' => __( 'Preserve', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '50', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_transformstyle_from',
			[
				'label' => __( 'Transform Style', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'preserve-3d', 'blackwidgets' ),
				'placeholder' => __( 'preserve-3d', 'blackwidgets' ),
			]
		);


		$this->add_control(
			'hr_element_2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'element_opacity_to',
			[
				'label' => __( 'Opacity', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'blackwidgets' ),
				'placeholder' => __( '1', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_duration_to',
			[
				'label' => __( 'Duration', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0.5', 'blackwidgets' ),
				'placeholder' => __( '0.4', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_rotationx_to',
			[
				'label' => __( 'RotationX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_rotationy_to',
			[
				'label' => __( 'RotationY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_scalex_to',
			[
				'label' => __( 'ScaleX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_scaley_to',
			[
				'label' => __( 'ScaleY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'blackwidgets' ),
				'placeholder' => __( '1', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_scalez_to',
			[
				'label' => __( 'ScaleZ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_skewx_to',
			[
				'label' => __( 'SkewX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_skewy_to',
			[
				'label' => __( 'SkewY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_movex_to',
			[
				'label' => __( 'MoveX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_movey_to',
			[
				'label' => __( 'MoveY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_movez_to',
			[
				'label' => __( 'MoveZ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_autoalpha_to',
			[
				'label' => __( 'Auto Alpha', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'blackwidgets' ),
				'placeholder' => __( '1', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_perspective_to',
			[
				'label' => __( 'Preserve', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0', 'blackwidgets' ),
				'placeholder' => __( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_transformstyle_to',
			[
				'label' => __( 'Transform Style', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'preserve-3d', 'blackwidgets' ),
				'placeholder' => __( 'preserve-3d', 'blackwidgets' ),
			]
		);


		$this->end_controls_section();

		// Start
		// Content section
		$this->start_controls_section(
			'content_html_js',
			[
				'label' => __( 'JS for HTML Content', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition'  => [
					'widget_type' => [
						'html',
					],
				],
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
				'selector' => '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'condition'  => [
					'widget_type' => [
						'html',
					],
				],
			]
        );

		// Color
		$this->add_control(
			'style_alert_color',
			[
				'label' => __( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
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

		$settings   					= $this->get_settings_for_display();

		// Variables
        $type 	        				= isset($settings['widget_type']) 									? $settings['widget_type'] 									: '';
        $message        				= isset($settings['widget_text']) 									? $settings['widget_text'] 									: '';
		$options 						= get_option('plugin_options') 										? get_option('plugin_options') 								: '';
        $gsap_options  					= isset($options['gsap_options']) 									? $options['gsap_options'] 									: '';
		// Content - HTML
		$html_code						= isset($settings['widget_html_content'])							? $settings['widget_html_content']							: '';
		$shared_class					= isset($settings['widget_html_class'])								? $settings['widget_html_class']							: '';
		$html_trigger					= isset($settings['widget_html_js_gsap_timeline_scroll_trigger'])	? $settings['widget_html_js_gsap_timeline_scroll_trigger']	: '';
		$html_js_from					= isset($settings['widget_html_js_tl_from'])						? $settings['widget_html_js_tl_from']						: '';
		$html_js_to						= isset($settings['widget_html_js_tl_to'])							? $settings['widget_html_js_tl_to']							: '';
		// Content - Image
		$img_class						= isset($settings['image_trigger_class'])							? $settings['image_trigger_class']							: '';

		$image_trigger_class			= isset($settings['image_trigger_class']) 							? $settings['image_trigger_class'] 							: '';
		$image_start_point				= isset($settings['image_start_point']) 							? $settings['image_start_point'] 							: '';
		$image_end_point				= isset($settings['image_end_point']) 								? $settings['image_end_point'] 								: '';
		$image_toggle_actions			= isset($settings['image_toggle_actions'])							? $settings['image_toggle_actions'] 						: '';
		$image_scrub					= isset($settings['image_scrub']) 									? $settings['image_scrub'] 									: '';
		$image_markers					= isset($settings['image_markers']) 								? $settings['image_markers'] 								: '';

		$element_opacity_from           = isset($settings['element_opacity_from'])              			? $settings['element_opacity_from'] 						: '';
		$element_duration_from          = isset($settings['element_duration_from'])             			? $settings['element_duration_from'] 						: '';
		$element_rotationx_from			= isset($settings['element_rotationx_from'])             			? $settings['element_rotationx_from'] 						: '';
		$element_rotationy_from			= isset($settings['element_rotationy_from'])             			? $settings['element_rotationy_from'] 						: '';
		$element_scalex_from            = isset($settings['element_scalex_from'])               			? $settings['element_scalex_from'] 							: '';
		$element_scaley_from            = isset($settings['element_scaley_from'])               			? $settings['element_scaley_from'] 							: '';
		$element_scalez_from            = isset($settings['element_scalez_from'])               			? $settings['element_scalez_from'] 							: '';
		$element_skewx_from             = isset($settings['element_skewx_from'])                			? $settings['element_skewx_from'] 							: '';
		$element_skewy_from             = isset($settings['element_skewy_from'])                			? $settings['element_skewy_from'] 							: '';
		$element_movex_from             = isset($settings['element_movex_from'])                			? $settings['element_movex_from'] 							: '';
		$element_movey_from             = isset($settings['element_movey_from'])                			? $settings['element_movey_from'] 							: '';
		$element_movez_from             = isset($settings['element_movez_from'])                			? $settings['element_movez_from'] 							: '';
		$element_autoalpha_from         = isset($settings['element_autoalpha_from'])            			? $settings['element_autoalpha_from'] 						: '';
		$element_perspective_from		= isset($settings['element_perspective_from'])						? $settings['element_perspective_from']						: '';
		$element_transformstyle_from    = isset($settings['element_transformstyle_from'])       			? $settings['element_transformstyle_from'] 					: '';

		$element_opacity_to           	= isset($settings['element_opacity_to'])              				? $settings['element_opacity_to'] 							: '';
		$element_duration_to          	= isset($settings['element_duration_to'])             				? $settings['element_duration_to'] 							: '';
		$element_rotationx_to          	= isset($settings['element_rotationx_to'])             				? $settings['element_rotationx_to']							: '';
		$element_rotationy_to          	= isset($settings['element_rotationy_to'])             				? $settings['element_rotationy_to']							: '';
		$element_scalex_to            	= isset($settings['element_scalex_to'])               				? $settings['element_scalex_to'] 							: '';
		$element_scaley_to            	= isset($settings['element_scaley_to'])               				? $settings['element_scaley_to'] 							: '';
		$element_scalez_to            	= isset($settings['element_scalez_to'])               				? $settings['element_scalez_to'] 							: '';
		$element_skewx_to             	= isset($settings['element_skewx_to'])                				? $settings['element_skewx_to'] 							: '';
		$element_skewy_to             	= isset($settings['element_skewy_to'])                				? $settings['element_skewy_to'] 							: '';
		$element_movex_to             	= isset($settings['element_movex_to'])                				? $settings['element_movex_to'] 							: '';
		$element_movey_to             	= isset($settings['element_movey_to'])                				? $settings['element_movey_to'] 							: '';
		$element_movez_to             	= isset($settings['element_movez_to'])                				? $settings['element_movez_to'] 							: '';
		$element_autoalpha_to         	= isset($settings['element_autoalpha_to'])            				? $settings['element_autoalpha_to'] 						: '';
		$element_perspective_to			= isset($settings['element_perspective_to'])						? $settings['element_perspective_to'] 						: '';
		$element_transformstyle_to    	= isset($settings['element_transformstyle_to'])       				? $settings['element_transformstyle_to'] 					: '';



		$data_id		= 'bw_' . uniqid();
		$script_id		= '#' . $data_id;


		// Render
		switch ($type) {
			case 'html':
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
				break;
			
			case 'image':
				echo '<div class="bw-gsap-img" id="'. $script_id .'"><div class="'. $image_trigger_class .'">';
					echo '<img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'thumbnail', $settings ) . '" class="bw-img-trigger-x">';
				echo '</div></div>';
				if ( isset($gsap_options) && !empty($gsap_options) ) {
					echo '<script>
						jQuery(window).ready(function($) {
							gsap.registerPlugin(ScrollTrigger);
							const tl = gsap.timeline({
								scrollTrigger: {
								trigger:		".'. $image_trigger_class .'",
								start:			"'. $image_start_point .'",
								end:			"'. $image_end_point .'",
								toggleActions:	"'. $image_toggle_actions .'",
								scrub: 			"'. $image_scrub .'",
								markers: 		false,
							}});
							tl.from(".'. $img_class .'", {
								opacity:				'. $element_opacity_from .',
								duration:				'. $element_duration_from .',
								rotateX:				'. $element_rotationx_from .',
								rotateY:				'. $element_rotationy_from .',
								scaleX:					'. $element_scalex_from .',
								scaleY:					'. $element_scaley_from .',
								scaleZ:					'. $element_scalez_from .',
								skewX:					'. $element_skewx_from .',
								skewY:					'. $element_skewy_from .',
								x:						'. $element_movex_from .',
								y:						'. $element_movey_from .',
								z:						'. $element_movez_from .',
								autoAlpha:				'. $element_autoalpha_from .',
								transformPerspective:	'. $element_perspective_from .',
								transformStyle:			"'. $element_transformstyle_from .'",
							})
							tl.to(".'. $img_class .'", {
								opacity:				'. $element_opacity_to .',
								duration:				'. $element_duration_to .',
								rotateX:				'. $element_rotationx_to .',
								rotateY:				'. $element_rotationy_to .',
								scaleX:					'. $element_scalex_to .',
								scaleY:					'. $element_scaley_to .',
								scaleZ:					'. $element_scalez_to .',
								skewX:					'. $element_skewx_to .',
								skewY:					'. $element_skewy_to .',
								x:						'. $element_movex_to .',
								y:						'. $element_movey_to .',
								z:						'. $element_movez_to .',
								autoAlpha:				'. $element_autoalpha_to .',
								transformPerspective:	'. $element_perspective_to .',
								transformStyle:			"'. $element_transformstyle_to .'",
							})
						});
						</script>';
				}
				break;

			default:
				echo 'There is not content available now! SOON!!!!';
				break;
		}


	}

}
