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
use Elementor\Group_Control_Image_Size;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Fade extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-fade', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/fade.css', [], BLACK_WIDGETS_VERSION );
    }

	/**
	 * Get widget name.
	 *
	 * Retrieve line widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'b_fade';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve line widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black Fade', 'black-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve line widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-animation';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the line widget belongs to.
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
        return [ 'black-widgets-fade' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

	/**
	 * Register line widget controls.
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
				'label' => esc_html__( 'Content', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_panel_alert',
			[
				'type' => \Elementor\Controls_Manager::ALERT,
				'alert_type' => 'info',     /* info, success, warning, danger */
				'heading' => esc_html__( 'Feel free to edit. Check this widget\'s demo.', 'black-widgets' ),
				'content' => sprintf(
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-fade/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'black-widgets' ),
					esc_html__( 'Demo', 'black-widgets' )
				),
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => esc_html__( 'Select Type', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bw-t-2',
				'options' => [
					'bw-t-1' => esc_html__( 'Image', 'black-widgets' ),
					'bw-t-2' => esc_html__( 'Line 1', 'black-widgets' ),
					'bw-t-3' => esc_html__( 'Line 2', 'black-widgets' ),
				],
				'description' => esc_html__( 'It just work with scroll down and does not work on first section. it works once', 'black-widgets' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
        );

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'full_size',
                'include' => [ 'thumbnail', 'medium', 'large', 'full' ],
                'default' => 'full',
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
        );

		// Link?
		$this->add_control(
			'image_link',
			[
				'label' => esc_html__( 'Add link for Image?', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'no' => esc_html__( 'No', 'black-widgets' ),
					'yes' => esc_html__( 'Yes', 'black-widgets' ),
				],
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
		);

		$this->add_control(
			'image_link_url',
			[
				'label' => esc_html__( 'Link', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'black-widgets' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
                ],
				'condition'  => [
					'image_link' => [
						'yes',
					],
				],
			]
        );

		$this->add_control(
			'img_grow',
			[
				'label' => esc_html__( 'Image Grow From', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'rtl',
				'options' => [
					'rtl' => esc_html__( 'Right To Left', 'black-widgets' ),
					'ltr' => esc_html__( 'Left To Right', 'black-widgets' ),
					'ttb' => esc_html__( 'Top To Bottom', 'black-widgets' ),
					'btt' => esc_html__( 'Bottom To Top', 'black-widgets' ),
				],
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
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
				'label' => esc_html__( 'Box Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-line, {{WRAPPER}} .bw-load-img',
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-line, {{WRAPPER}} .bw-load-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_box_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-line, {{WRAPPER}} .bw-load-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-line, {{WRAPPER}} .bw-load-img',
			]
		);

		$this->add_responsive_control(
			'widget_box_border_radius_for_fade', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-load-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-line, {{WRAPPER}} .bw-load-img',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'line_style_section',
			[
				'label' => esc_html__( 'Image & line  Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'start_width',
			[
				'label' => esc_html__( 'Start line width', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-line' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type!' => [
						'bw-t-1',
					],
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'line_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
                'selector' => '{{WRAPPER}} .bw-line',
				'condition'  => [
					'widget_type' => [
						'bw-t-3',
					],
				],
			]
		);

		$this->add_responsive_control(
			'img_width',
			[
				'label' => esc_html__( 'Image Width', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 10,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-load-img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'img_background',
				'label' => esc_html__( 'Image Cover X', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-load-img .bw-image-grow-cover ',
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
				'description' => esc_html__( 'After change style, scroll down to see changes', 'black-widgets' ),
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Image Hover Animation', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
				'prefix_class' => 'elementor-animation-',
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
		);

		$this->end_controls_section();
        // End


		// Start
		// Style section
		$this->start_controls_section(
			'transform_section',
			[
				'label' => esc_html__( '2D & 3D Normal Transform Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Enable Image Movement Animate
		$this->add_control(
			'transform_normal_option',
			[
				'label' 		=> esc_html__( 'Transform Style', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Enable', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'Disable', 'black-widgets' ),
				'return_value' 	=> 'normal_transform',
				'default' 		=> 'off',
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('transform_tabs', [
			'condition' 	=> [
				'transform_normal_option' 	=> [
					'normal_transform',
				],
			],
		]);
		$this->start_controls_tab(
			'transform_tab_move',
			[
				'label' => esc_html__( 'Move', 'black-widgets' ),
			]
		);

		// Start normal tab content

		$this->add_responsive_control(
			'move_normal_x',
			[
				'label' => esc_html__( 'Move on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'movement on the diagram X - do not leave empty!', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'move_normal_y',
			[
				'label' => esc_html__( 'Move on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'movement on the diagram Y - do not leave empty!', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'move_normal_z',
			[
				'label' => esc_html__( 'Move on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'movement on the diagram Z - do not leave empty!', 'black-widgets' ),
			]
		);

		// end normal tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_scale',
			[
				'label' => esc_html__( 'Scale', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'scale_normal_x',
			[
				'label' => esc_html__( 'Scale on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'set 1.1 to scale on left and right - do not set 0', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'scale_normal_y',
			[
				'label' => esc_html__( 'Scale on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'set 1.1 to scale on top and bottom - do not set 0', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'scale_normal_z',
			[
				'label' => esc_html__( 'Scale on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'perspective',
			[
				'label' => esc_html__( 'Self Perspective ◊', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ps' ],
				'range' => [
					'px' => [
						'min' => -3000,
						'max' => 3000,
						'step' => 50,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'perspective_child',
			[
				'label' => esc_html__( 'Children Perspective ◊', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ps' ],
				'range' => [
					'px' => [
						'min' => -3000,
						'max' => 3000,
						'step' => 50,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_rotate',
			[
				'label' => esc_html__( 'Rotate', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'rotate_normal_x',
			[
				'label' => esc_html__( 'Rotate on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'rotate_normal_y',
			[
				'label' => esc_html__( 'Rotate on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'rotate_normal_z',
			[
				'label' => esc_html__( 'Rotate on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_skew',
			[
				'label' => esc_html__( 'Skew', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'skew_normal_x',
			[
				'label' => esc_html__( 'Skew on → X ▱', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'skew_normal_y',
			[
				'label' => esc_html__( 'Skew on ↑ Y ▱', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
		// End


		// Start
		// Style section
		$this->start_controls_section(
			'image_pro_cursor_animate',
			[
				'label' => esc_html__( 'Cursor & Hover Animate', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_cursor',
			[
				'label' => esc_html__( 'Select Cursor Style', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'auto' 			=> esc_html__( 'auto', 'black-widgets' ),
					'default' 		=> esc_html__( 'default', 'black-widgets' ),
					'none' 			=> esc_html__( 'none', 'black-widgets' ),
					'pointer' 		=> esc_html__( 'pointer', 'black-widgets' ),
					'not-Allowed' 	=> esc_html__( 'not-Allowed', 'black-widgets' ),
					'wait' 			=> esc_html__( 'wait', 'black-widgets' ),
					'progress' 		=> esc_html__( 'progress', 'black-widgets' ),
					'help' 			=> esc_html__( 'help', 'black-widgets' ),
					'context-menu' 	=> esc_html__( 'context-menu', 'black-widgets' ),
					'cell' 			=> esc_html__( 'cell', 'black-widgets' ),
					'crosshair' 	=> esc_html__( 'crosshair', 'black-widgets' ),
					'text' 			=> esc_html__( 'text', 'black-widgets' ),
					'wetical-text' 	=> esc_html__( 'wetical-text', 'black-widgets' ),
					'grab' 			=> esc_html__( 'grab', 'black-widgets' ),
					'grabbing' 		=> esc_html__( 'grabbing', 'black-widgets' ),
					'alias' 		=> esc_html__( 'alias', 'black-widgets' ),
					'copy' 			=> esc_html__( 'copy', 'black-widgets' ),
					'move' 			=> esc_html__( 'move', 'black-widgets' ),
					'zoom-in'		=> esc_html__( 'zoom-in', 'black-widgets' ),
					'zoom-out' 		=> esc_html__( 'zoom-out', 'black-widgets' ),
					'col-resize' 	=> esc_html__( 'col-resize', 'black-widgets' ),
					'row-resize' 	=> esc_html__( 'row-resize', 'black-widgets' ),
					'nesw-resize' 	=> esc_html__( 'nesw-resize', 'black-widgets' ),
					'newse-resize' 	=> esc_html__( 'newse-resize', 'black-widgets' ),
					'ew-resize' 	=> esc_html__( 'ew-resize', 'black-widgets' ),
					'ns-resize' 	=> esc_html__( 'ns-resize', 'black-widgets' ),
					'n-resize' 		=> esc_html__( 'n-resize', 'black-widgets' ),
					'w-resize' 		=> esc_html__( 'w-resize', 'black-widgets' ),
					's-resize' 		=> esc_html__( 's-resize', 'black-widgets' ),
					'e-resize' 		=> esc_html__( 'e-resize', 'black-widgets' ),
					'nw-resize' 	=> esc_html__( 'nw-resize', 'black-widgets' ),
					'ne-resize' 	=> esc_html__( 'ne-resize', 'black-widgets' ),
					'sw-resize' 	=> esc_html__( 'sw-resize', 'black-widgets' ),
					'se-resize'		=> esc_html__( 'se-resize', 'black-widgets' ),
				],
				'description' => esc_html__( 'It just work with scroll down and does not work on first section. it works once', 'black-widgets' ),
			]
		);

		$this->add_control(
			'image_pro_anime',
			[
				'label' => esc_html__( 'Type Custom Animate', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'all 0.23s ease', 'black-widgets' ),
			]
		);

		$this->end_controls_section();
		// End


		// Start
		// Style section
		$this->start_controls_section(
			'transform_section_hover',
			[
				'label' => esc_html__( '2D & 3D Hover Transform Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
			]
		);

		// Enable Image Movement Animate
		$this->add_control(
			'transform_hover_option',
			[
				'label' 		=> esc_html__( 'Transform Style', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Enable', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'Disable', 'black-widgets' ),
				'return_value' 	=> 'hover_transform',
				'default' 		=> 'off',
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('transform_hover_tabs', [
			'condition' 	=> [
				'transform_hover_option' 	=> [
					'hover_transform',
				],
			],
		]);
		$this->start_controls_tab(
			'transform_tab_move_hover',
			[
				'label' => esc_html__( 'Move', 'black-widgets' ),
			]
		);

		// Start normal tab content

		$this->add_responsive_control(
			'move_hover_x',
			[
				'label' => esc_html__( 'Move on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'movement on the diagram X - do not leave empty!', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'move_hover_y',
			[
				'label' => esc_html__( 'Move on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'movement on the diagram Y - do not leave empty!', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'move_hover_z',
			[
				'label' => esc_html__( 'Move on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'Movement on the diagram Z - do not leave empty!', 'black-widgets' ),
			]
		);

		// end normal tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_scale_hover',
			[
				'label' => esc_html__( 'Scale', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'scale_hover_x',
			[
				'label' => esc_html__( 'Scale on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'set 1.1 to scale on left and right - do not set 0', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'scale_hover_y',
			[
				'label' => esc_html__( 'Scale on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'set 1.1 to scale on top and bottom - do not set 0', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'scale_hover_z',
			[
				'label' => esc_html__( 'Scale on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'perspective_hover',
			[
				'label' => esc_html__( 'Self Perspective ◊', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ps' ],
				'range' => [
					'px' => [
						'min' => -3000,
						'max' => 3000,
						'step' => 50,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'perspective_child_hover',
			[
				'label' => esc_html__( 'Children Perspective ◊', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ps' ],
				'range' => [
					'px' => [
						'min' => -3000,
						'max' => 3000,
						'step' => 50,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_rotate_hover',
			[
				'label' => esc_html__( 'Rotate', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'rotate_hover_x',
			[
				'label' => esc_html__( 'Rotate on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'rotate_hover_y',
			[
				'label' => esc_html__( 'Rotate on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'rotate_hover_z',
			[
				'label' => esc_html__( 'Rotate on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_skew_hover',
			[
				'label' => esc_html__( 'Skew', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'skew_hover_x',
			[
				'label' => esc_html__( 'Skew on → X ▱', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'skew_hover_y',
			[
				'label' => esc_html__( 'Skew on ↑ Y ▱', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
		// End

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

		$settings   				= $this->get_settings_for_display();
		// Variables
        $type 	        			= isset($settings['widget_type']) 						? $settings['widget_type'] 							: '';
		$img_grow					= isset($settings['img_grow']) 							? $settings['img_grow']								: '';
        $image_URL 	       			= isset( $settings['image']['url']) 					?  $settings['image']['url'] 						: '';
        $image_link 	   			= isset($settings['image_link']) 						? $settings['image_link'] 							: '';
        $image_size 	   			= isset($settings['full_size']) 						? $settings['full_size'] 							: 'full';
		$target            			= isset($settings['image_link_url']['is_external']) 	? 'target="_blank"' 								: '';
        $nofollow          			= isset($settings['image_link_url']['nofollow']) 		? ' rel="nofollow"' 								: '';
		// Transform Option
		$normal_transform			= isset($settings['transform_normal_option'])			? $settings['transform_normal_option']				: '';
		$hover_transform			= isset($settings['transform_hover_option'])			? $settings['transform_hover_option']				: '';
		//Transform Styles
		$move_normal_x				= isset($settings['move_normal_x'])						? $settings['move_normal_x']						: '0px';
		$move_normal_y				= isset($settings['move_normal_y'])						? $settings['move_normal_y']						: '0px';
		$move_normal_z				= isset($settings['move_normal_z'])						? $settings['move_normal_z']						: '0px';
		$scale_normal_x				= isset($settings['scale_normal_x'])					? $settings['scale_normal_x']						: '1';
		$scale_normal_y				= isset($settings['scale_normal_y'])					? $settings['scale_normal_y']						: '1';
		$scale_normal_z				= isset($settings['scale_normal_z'])					? $settings['scale_normal_z']						: '1';
		$rotate_normal_x			= isset($settings['rotate_normal_x'])					? $settings['rotate_normal_x']						: '0deg';
		$rotate_normal_y			= isset($settings['rotate_normal_y'])					? $settings['rotate_normal_y']						: '0deg';
		$rotate_normal_z			= isset($settings['rotate_normal_z'])					? $settings['rotate_normal_z']						: '0deg';
		$skew_normal_x				= isset($settings['skew_normal_x'])						? $settings['skew_normal_x']						: '0deg';
		$skew_normal_y				= isset($settings['skew_normal_y'])						? $settings['skew_normal_y']						: '0deg';
		$perspective				= isset($settings['perspective'])						? $settings['perspective']							: '0px';
		$perspective_child			= isset($settings['perspective_child'])					? $settings['perspective_child']					: '0px';
		// Cursor and Animate
		$cursor						= isset($settings['widget_cursor'])						? $settings['widget_cursor']						: 'auto';
		$animate					= isset($settings['image_pro_anime'])					? $settings['image_pro_anime']						: 'all 0.3s ease';
		//Transform Styles
		$move_hover_x				= isset($settings['move_hover_x'])						? $settings['move_hover_x']							: '0px';
		$move_hover_y				= isset($settings['move_hover_y'])						? $settings['move_hover_y']							: '0px';
		$move_hover_z				= isset($settings['move_hover_z'])						? $settings['move_hover_z']							: '0px';
		$scale_hover_x				= isset($settings['scale_hover_x'])						? $settings['scale_hover_x']						: '1';
		$scale_hover_y				= isset($settings['scale_hover_y'])						? $settings['scale_hover_y']						: '1';
		$scale_hover_z				= isset($settings['scale_hover_z'])						? $settings['scale_hover_z']						: '1';
		$rotate_hover_x				= isset($settings['rotate_hover_x'])					? $settings['rotate_hover_x']						: '0deg';
		$rotate_hover_y				= isset($settings['rotate_hover_y'])					? $settings['rotate_hover_y']						: '0deg';
		$rotate_hover_z				= isset($settings['rotate_hover_z'])					? $settings['rotate_hover_z']						: '0deg';
		$skew_hover_x				= isset($settings['skew_hover_x'])						? $settings['skew_hover_x']							: '0deg';
		$skew_hover_y				= isset($settings['skew_hover_y'])						? $settings['skew_hover_y']							: '0deg';
		$perspective_hover			= isset($settings['perspective_hover'])					? $settings['perspective_hover']					: '0px';
		$perspective_child_hover	= isset($settings['perspective_child_hover'])			? $settings['perspective_child_hover']				: '0px';
		//ID Settings
		$data_id                	= 'bw_' . uniqid();
		//Transform Normal Styles
		// Normal Move
		$translatex 				= isset( $move_normal_x["size"] ) 						? $move_normal_x["size"] . $move_normal_x["unit"] : '';
		$translatey 				= isset( $move_normal_y["size"] ) 						? $move_normal_y["size"] . $move_normal_y["unit"] : '';
		$translatez 				= isset( $move_normal_z["size"] ) 						? $move_normal_z["size"] . $move_normal_z["unit"] : '';
		$translate3d 				= 'translate3d(' . $translatex . ', ' . $translatey . ', ' . $translatez . ')';
		// Normal Scale
		$scalex 					= isset( $scale_normal_x["size"] ) 						? $scale_normal_x["size"] : '';
		$scaley 					= isset( $scale_normal_y["size"] ) 						? $scale_normal_y["size"] : '';
		$scalez 					= isset( $scale_normal_z["size"] ) 						? $scale_normal_z["size"] : '';
		$scale3dx 					= isset( $scale_normal_z["size"] ) 						? '-webkit-transform-style: preserve-3d; transform-style: preserve-3d;' : '';
		$scale3d 					= 'scale3d(' . $scalex . ', ' . $scaley . ', ' . $scalez . ')';
		// Normal Rotate
		$rotatex 					= isset( $rotate_normal_x["size"] ) 					? 'rotateX(' . $rotate_normal_x["size"] . $rotate_normal_x["unit"] . ')' : '';
		$rotatey 					= isset( $rotate_normal_y["size"] ) 					? 'rotateY(' . $rotate_normal_y["size"] . $rotate_normal_y["unit"] . ')' : '';
		$rotatez 					= isset( $rotate_normal_z["size"] ) 					? 'rotateZ(' . $rotate_normal_z["size"] . $rotate_normal_z["unit"] . ')' : '';
		// Normal Skew
		$skewx 						= isset( $skew_normal_x["size"] ) 						? $skew_normal_x["size"] . $skew_normal_x["unit"] : '';
		$skewy 						= isset( $skew_normal_y["size"] ) 						? $skew_normal_y["size"] . $skew_normal_y["unit"] : '';
		$skew 						= 'skew(' . $skewx . ', ' . $skewy . ')';
		// Normal Self perspective
		$perspective 				=  isset( $perspective["size"] ) 						? 'perspective:' . $perspective["size"] . $perspective["unit"] . '; -webkit-perspective:' . $perspective["size"] . $perspective["unit"] . ';': '';
		// Normal Child perspective
		$perspective_child 			=  isset( $perspective_child["size"] ) 					? 'perspective(' . $perspective_child["size"] . $perspective_child["unit"] . ')': '';
		//Transform Hover Styles
		//Hover Move
		$translatex_hover			= isset( $move_hover_x["size"] ) 						? $move_hover_x["size"] . $move_hover_x["unit"] : '';
		$translatey_hover			= isset( $move_hover_y["size"] ) 						? $move_hover_y["size"] . $move_hover_y["unit"] : '';
		$translatez_hover			= isset( $move_hover_z["size"] ) 						? $move_hover_z["size"] . $move_hover_z["unit"] : '';
		$translate3d_hover			= 'translate3d(' . $translatex_hover . ', ' . $translatey_hover . ', ' . $translatez_hover . ')';
		// Hover Scale
		$scalex_hover 				= isset( $scale_hover_x["size"] ) 						? $scale_hover_x["size"] : '';
		$scaley_hover 				= isset( $scale_hover_y["size"] ) 						? $scale_hover_y["size"] : '';
		$scalez_hover 				= isset( $scale_hover_z["size"] ) 						? $scale_hover_z["size"] : '';
		$scale3dx_hover 			= isset( $scale_hover_z["size"] ) 						?'-webkit-transform-style: preserve-3d; transform-style: preserve-3d;' : '';
		$scale3d_hover 				= 'scale3d(' . $scalex_hover . ', ' . $scaley_hover . ', ' . $scalez_hover . ')';
		// Hover Rotate
		$rotatex_hover 				= isset( $rotate_hover_x["size"] ) 						? 'rotateX(' . $rotate_hover_x["size"] . $rotate_hover_x["unit"] . ')' : '';
		$rotatey_hover 				= isset( $rotate_hover_y["size"] ) 						? 'rotateY(' . $rotate_hover_y["size"] . $rotate_hover_y["unit"] . ')' : '';
		$rotatez_hover 				= isset( $rotate_hover_z["size"] ) 						? 'rotateZ(' . $rotate_hover_z["size"] . $rotate_hover_z["unit"] . ')' : '';
		// Hover Skew
		$skewx_hover 				= isset( $skew_hover_x["size"] ) 						? $skew_hover_x["size"] . $skew_hover_x["unit"] : '';
		$skewy_hover 				= isset( $skew_hover_y["size"] ) 						? $skew_hover_y["size"] . $skew_hover_y["unit"] : '';
		$skew_hover 				= 'skew(' . $skewx_hover . ', ' . $skewy_hover . ')';
		// Hover Self perspective
		$perspective_hover 			= isset( $perspective_hover["size"] ) 					? 'perspective:' . $perspective_hover["size"] . $perspective_hover["unit"] . '; -webkit-perspective:' . $perspective_hover["size"] . $perspective_hover["unit"] . ';' : '';
		// Hover Child perspective
		$perspective_child_hover	= isset( $perspective_child_hover["size"] ) 			? 'perspective(' . $perspective_child_hover["size"] . $perspective_child_hover["unit"] . ')' : '';
		// Create a Custom CSS for Normal and Hover Styles
		$normal_transform_style 	= ($normal_transform == 'normal_transform') 			? "#$data_id { -webkit-transition: $animate; -o-transition: $animate; transition: $animate; $perspective transform: $perspective_child $skew $rotatex $rotatey $rotatez $scale3d $translate3d; -webkit-transform: $perspective_child $skew $rotatex $rotatey $rotatez $scale3d $translate3d; $scale3dx }" : '';
		$hover_transform_style 		= ($hover_transform == 'hover_transform') 				? "#$data_id:hover { $perspective_hover transform: $perspective_child_hover $skew_hover $rotatex_hover $rotatey_hover $rotatez_hover $scale3d_hover $translate3d_hover; -webkit-transform: $perspective_child_hover $skew_hover $rotatex_hover $rotatey_hover $rotatez_hover $scale3d_hover $translate3d_hover; $scale3dx_hover }" : '';
		//Return all of the styles
		echo "<style>" . esc_html( $normal_transform_style ) . " " . esc_html( $hover_transform_style ) . "</style>";

		// Render
        switch ($type) {
			case 'bw-t-1':
				echo '<div class="bw-load-img bw-cursor-' . esc_attr( $cursor ) .'" id="'. $data_id .'">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<div class="bw-img ' . esc_attr( $settings['hover_animation'] ) . '">';
						if ( $image_link == 'yes') { echo '<a href="' . esc_url( $settings['image_link_url']['url'] ) . '"' . $target . $nofollow . ' class="bw-image-link">'; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<div class="bw-image-grow-cover ' . esc_attr( $img_grow ) . '"></div>';
                              $image_url = Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], $image_size, $settings );
                              if ( $image_url ) {
                                  echo '<img src="' . esc_url( $image_url ) . '" class="' . esc_attr( $img_grow ) . '">'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
                              }
						if ( $image_link == 'yes'){ echo '</a>'; }
					echo '</div>';
				echo '</div>';
				break;
			default:
				echo '<div class="bw-line ' . esc_attr( $type ) . '"></div>';
				break;
		}

	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\Fade', 'Black_Widgets\BLACK_WIDGETS_Fade');
