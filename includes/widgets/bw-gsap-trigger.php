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
				'label' => esc_html__( 'Content', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_panel_alert',
			[
				'type' => \Elementor\Controls_Manager::ALERT,
				'alert_type' => 'info',     /* info, success, warning, danger */
				'heading' => esc_html__( 'This For Expert', 'blackwidgets' ),
				'content' => sprintf(
					'%s <a href="https://gsap.com/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'blackwidgets' ),
					esc_html__( 'gsap.com', 'blackwidgets' )
				),
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => esc_html__( 'Select Content', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'html',
				'options' => [
					'html' 				=> esc_html__( 'HTML', 'blackwidgets' ),
					// 'text' 				=> esc_html__( 'Text', 'blackwidgets' ),
					// 'shape'				=> esc_html__( 'Shape', 'blackwidgets' ),
					// 'nested_div' 		=> esc_html__( 'Nested Div', 'blackwidgets' ),
					'image' 				=> esc_html__( 'Image', 'blackwidgets' ),
					// 'image_sequence' 	=> esc_html__( 'Image Sequence', 'blackwidgets' ),
					// 'video_sequence' 	=> esc_html__( 'Video Sequence', 'blackwidgets' ),
					// 'video_sequence' 	=> esc_html__( 'Elementor Template', 'blackwidgets' ),
				],
				'description' => esc_html__( 'We create some skin before, you can use these or no! make a new custom type.', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_html_content',
			[
				'label' => esc_html__( 'HTML Code', 'blackwidgets' ),
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
				'description' => esc_html__( 'Put your HTML tags here, please check the example structure', 'blackwidgets' ),
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
				'label' => esc_html__( 'Shared Class', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'bw-eg', 'blackwidgets' ),
                'description' => esc_html__( 'use the shared class for each line, like the example', 'blackwidgets' ),
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
				'label' => esc_html__( 'Choose Image', 'blackwidgets' ),
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
				'label' => esc_html__( 'JS for Image', 'blackwidgets' ),
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
				'label' => esc_html__( 'Add A Unique Trigger Class', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'bw-image-1', 'blackwidgets' ),
				'placeholder' => esc_html__( 'bw-image-1', 'blackwidgets' ),
				'description' => esc_html__( 'it should be unique and add manually', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'image_start_point',
			[
				'label' => esc_html__( 'Start Point', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'center bottom', 'blackwidgets' ),
				'placeholder' => esc_html__( 'top bottom', 'blackwidgets' ),
				'description' => esc_html__( '----', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'image_end_point',
			[
				'label' => esc_html__( 'End Point', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'center top', 'blackwidgets' ),
				'placeholder' => esc_html__( 'top bottom', 'blackwidgets' ),
				'description' => esc_html__( '----', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'image_toggle_actions',
			[
				'label' => esc_html__( 'Toggle Actions', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'none none none none', 'blackwidgets' ),
				'placeholder' => esc_html__( 'none none none none', 'blackwidgets' ),
				'description' => esc_html__( '----', 'blackwidgets' ),
				]
			);

		$this->add_control(
			'image_scrub',
			[
				'label' => esc_html__( 'Scrub', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'true', 'blackwidgets' ),
				'placeholder' => esc_html__( 'false', 'blackwidgets' ),
				'description' => esc_html__( '----', 'blackwidgets' ),
				]
			);
			
		// // Enable Title Section
		// $this->add_control(
		// 	'image_markers',
		// 	[
		// 		'label' 		=> esc_html__( 'Markers', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::SWITCHER,
		// 		'label_on' 		=> esc_html__( 'true', 'blackwidgets' ),
		// 		'label_off' 	=> esc_html__( 'false', 'blackwidgets' ),
		// 		'default' 		=> 'false',
		// 		'description' => esc_html__( 'enable it to to show the markers', 'blackwidgets' ),
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
				'label' => esc_html__( 'Opacity', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1', 'blackwidgets' ),
				'placeholder' => esc_html__( '1', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_duration_from',
			[
				'label' => esc_html__( 'Duration', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0.9', 'blackwidgets' ),
				'placeholder' => esc_html__( '0.4', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_rotationx_from',
			[
				'label' => esc_html__( 'RotationX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '3', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_rotationy_from',
			[
				'label' => esc_html__( 'RotationY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '3', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_scalex_from',
			[
				'label' => esc_html__( 'ScaleX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1', 'blackwidgets' ),
				'placeholder' => esc_html__( '1', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_scaley_from',
			[
				'label' => esc_html__( 'ScaleY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1', 'blackwidgets' ),
				'placeholder' => esc_html__( '1', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_scalez_from',
			[
				'label' => esc_html__( 'ScaleZ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_skewx_from',
			[
				'label' => esc_html__( 'SkewX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '26', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_skewy_from',
			[
				'label' => esc_html__( 'SkewY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '-42', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_movex_from',
			[
				'label' => esc_html__( 'MoveX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_movey_from',
			[
				'label' => esc_html__( 'MoveY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_movez_from',
			[
				'label' => esc_html__( 'MoveZ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_autoalpha_from',
			[
				'label' => esc_html__( 'Auto Alpha', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1', 'blackwidgets' ),
				'placeholder' => esc_html__( '1', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_perspective_from',
			[
				'label' => esc_html__( 'Preserve', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '50', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_transformstyle_from',
			[
				'label' => esc_html__( 'Transform Style', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'preserve-3d', 'blackwidgets' ),
				'placeholder' => esc_html__( 'preserve-3d', 'blackwidgets' ),
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
				'label' => esc_html__( 'Opacity', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1', 'blackwidgets' ),
				'placeholder' => esc_html__( '1', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_duration_to',
			[
				'label' => esc_html__( 'Duration', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0.5', 'blackwidgets' ),
				'placeholder' => esc_html__( '0.4', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_rotationx_to',
			[
				'label' => esc_html__( 'RotationX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_rotationy_to',
			[
				'label' => esc_html__( 'RotationY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_scalex_to',
			[
				'label' => esc_html__( 'ScaleX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_scaley_to',
			[
				'label' => esc_html__( 'ScaleY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1', 'blackwidgets' ),
				'placeholder' => esc_html__( '1', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_scalez_to',
			[
				'label' => esc_html__( 'ScaleZ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_skewx_to',
			[
				'label' => esc_html__( 'SkewX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_skewy_to',
			[
				'label' => esc_html__( 'SkewY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_movex_to',
			[
				'label' => esc_html__( 'MoveX', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_movey_to',
			[
				'label' => esc_html__( 'MoveY', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_movez_to',
			[
				'label' => esc_html__( 'MoveZ', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_autoalpha_to',
			[
				'label' => esc_html__( 'Auto Alpha', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1', 'blackwidgets' ),
				'placeholder' => esc_html__( '1', 'blackwidgets' ),
			]
		);
		
		$this->add_control(
			'element_perspective_to',
			[
				'label' => esc_html__( 'Preserve', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '0', 'blackwidgets' ),
				'placeholder' => esc_html__( '0', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'element_transformstyle_to',
			[
				'label' => esc_html__( 'Transform Style', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'preserve-3d', 'blackwidgets' ),
				'placeholder' => esc_html__( 'preserve-3d', 'blackwidgets' ),
			]
		);


		$this->end_controls_section();

		// Start
		// Content section
		$this->start_controls_section(
			'content_html_js',
			[
				'label' => esc_html__( 'JS for HTML Content', 'blackwidgets' ),
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
				'label' => esc_html__( 'JS: GSAP timeline > scrollTrigger', 'blackwidgets' ),
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
				'description' => esc_html__( 'Put your transform properties tags here for "from"', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_html_js_tl_from',
			[
				'label' => esc_html__( 'JS: GSAP tl.from', 'blackwidgets' ),
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
				'description' => esc_html__( 'Put your transform properties tags here for "from"', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_html_js_tl_to',
			[
				'label' => esc_html__( 'JS: GSAP tl.to', 'blackwidgets' ),
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
				'description' => esc_html__( 'Put your transform properties tags here for "to"', 'blackwidgets' ),
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

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box',
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
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
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
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
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
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
				'label' => esc_html__( 'Typography Style', 'blackwidgets' ),
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
				'label' => esc_html__( 'Color', 'blackwidgets' ),
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
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-gsap-code-box',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_alert_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
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
				'label' => esc_html__( 'Icon Size', 'blackwidgets' ),
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
				'label' => esc_html__( 'Icon Color', 'blackwidgets' ),
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
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
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
							tl.from(".'. esc_js( $shared_class ) .'", {
								'. $html_js_from .'
							})
							tl.to(".'. esc_js( $shared_class ) .'", {
								'. $html_js_to .'
							})
						});
					</script>';
				}
				break;
			
			case 'image':
				echo '<div class="bw-gsap-img" id="'. $script_id .'"><div class="'. esc_attr( $image_trigger_class ) .'">';
					echo '<img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'thumbnail', $settings ) . '" class="bw-img-trigger-x">';
				echo '</div></div>';
				if ( isset($gsap_options) && !empty($gsap_options) ) {
                    echo '<script>';
                    echo esc_js( '
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
							tl.to(".'. esc_attr( $img_class ) .'", {
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
						});' );
					echo '</script>';
				}
				break;

			default:
				echo 'There is not content available now! SOON!!!!';
				break;
		}


	}

}
