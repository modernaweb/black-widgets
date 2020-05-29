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
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Color;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BLACK_WIDGETS_Image_Pro extends \Elementor\Widget_Base {

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
		return 'b_image';
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
		return __( 'Black Image', 'blackwidgets' );
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
		return 'eicon-image';
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

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
        );
		
		// Alignment
		$this->add_responsive_control(
			'widget_alignment',
			[
				'label'     => __( 'Text Alignment', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'blackwidgets' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blackwidgets' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'blackwidgets' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'full',
			]
        );

		// Link?
		$this->add_control(
			'image_link',
			[
				'label' => __( 'Add link for Image?', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'no' => __( 'No', 'blackwidgets' ),
					'yes' => __( 'Yes', 'blackwidgets' ),
                ],
			]
		);

		$this->add_control(
			'image_link_url',
			[
				'label' => __( 'Link', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'blackwidgets' ),
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

		// // Enable Image Movement Animate
		// $this->add_control(
		// 	'image_movement',
		// 	[
		// 		'label' 		=> __( 'Image Movement Animate', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::SWITCHER,
		// 		'label_on' 		=> __( 'Enable', 'blackwidgets' ),
		// 		'label_off' 	=> __( 'Disable', 'blackwidgets' ),
		// 		'return_value' 	=> 'on',
		// 		'default' 		=> 'off',
		// 	]
		// );

		// // Start From
		// $this->add_control(
		// 	'trigger_hook',
		// 	[
		// 		'label' 		=> __( 'Start Point', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::TEXT,
		// 		'placeholder'   => '0.1',
		// 		'default'       => '0.1',
		// 		'condition' 	=> [ 
		// 			'image_movement' 	=> [
		// 				'on',
		// 			],
		// 		],
		// 	]
		// );

		// // Length
		// $this->add_control(
		// 	'duration',
		// 	[
		// 		'label' 		=> __( 'Length Movement', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::TEXT,
		// 		'placeholder'   => '100%',
		// 		'default'       => '100%',
		// 		'condition' 	=> [ 
		// 			'image_movement' 	=> [
		// 				'on',
		// 			],
		// 		],
		// 	]
		// );

		// // Vertical Movement
		// $this->add_control(
		// 	'vertical_movement',
		// 	[
		// 		'label' 		=> __( 'Vertical Movement', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::TEXT,
		// 		'condition' 	=> [ 
		// 			'image_movement' 	=> [
		// 				'on',
		// 			],
		// 		],
		// 	]
		// );

		// // Horizontal Movement
		// $this->add_control(
		// 	'horizontal_movement',
		// 	[
		// 		'label' 		=> __( 'Horizontal Movement', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::TEXT,
		// 		'condition' 	=> [ 
		// 			'image_movement' 	=> [
		// 				'on',
		// 			],
		// 		],
		// 	]
		// );

		// // Opacity at End
		// $this->add_control(
		// 	'opacity',
		// 	[
		// 		'label' 		=> __( 'Opacity at End', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::TEXT,
		// 		'condition' 	=> [ 
		// 			'image_movement' 	=> [
		// 				'on',
		// 			],
		// 		],
		// 	]
		// );

		// // Rotation at End
		// $this->add_control(
		// 	'rotation',
		// 	[
		// 		'label' 		=> __( 'Rotation at End', 'blackwidgets' ),
		// 		'type' 			=> \Elementor\Controls_Manager::TEXT,
		// 		'condition' 	=> [ 
		// 			'image_movement' 	=> [
		// 				'on',
		// 			],
		// 		],
		// 	]
		// );

		// Enable Image Movement Animate
		$this->add_control(
			'image_parllax',
			[
				'label' 		=> __( 'Extra Image Parallax', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Enable', 'blackwidgets' ),
				'label_off' 	=> __( 'Disable', 'blackwidgets' ),
				'return_value' 	=> 'bw-parallax',
				'default' 		=> 'off',
			]
		);

		
		$this->end_controls_section();
        // End

		// Start
		// Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Image Box Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => __( 'Normal', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-image img',
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
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-image img',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-image img',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filter',
				'selector' => 
					'{{WRAPPER}} .bw-image img'
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_1_hover',
			[
				'label' => __( 'Hover', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => __( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-image img:hover',
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
				'label' => __( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-image img:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_hover_box_padding',
			[
				'label' => __( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-image img:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => __( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-image img:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => __( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-image img:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-image img:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filter_hover',
				'selector' => 
					'{{WRAPPER}} .bw-image img:hover'
			]
		);
		
		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
				'prefix_class' => 'elementor-animation-',
			]
		);

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

		$settings   	= $this->get_settings_for_display();

		// Variables
        $type 	           		= isset($settings['widget_type']) 				? $settings['widget_type'] 							: 'type';
        $alignment 	       		= isset($settings['widget_alignment']) 			? $settings['widget_alignment'] 					: '';
        $image_URL 	       		= isset( $settings['image']['url']) 			?  $settings['image']['url'] 						: '';
        $image_link 	   		= isset($settings['image_link']) 				? $settings['image_link'] 							: '';
		$target            		= $settings['image_link_url']['is_external'] 	? 'target="_blank"' 								: '';
        $nofollow          		= $settings['image_link_url']['nofollow'] 		? ' rel="nofollow"' 								: '';
		$duration	            = !empty($settings['duration']) 				?  $settings['duration'] 							: '';
		$trigger_hook	        = !empty($settings['trigger_hook']) 			?  $settings['trigger_hook'] 						: '';
		$horizontal_movement	= !empty($settings['horizontal_movement']) 		? 'x: "' . $settings['horizontal_movement'] . '",' 	: '';
		$vertical_movement		= !empty($settings['vertical_movement']) 		? 'y: "' . $settings['vertical_movement'] . '",' 	: '';
		$opacity				= !empty( $settings['opacity'] ) 			? 'opacity: "' . $settings['opacity'] . '",' 		: '';
		$rotation				= !empty($settings['rotation']) 				? 'rotation: "' . $settings['rotation'] . '",' 		: '';
		$parallax				= isset($settings['image_parllax'])				? $settings['image_parllax']						: '';
		$data_id                = 'bw_' . uniqid();
		$script_id              = '#' . $data_id;

		$image_movement = ''; //$settings['image_movement']

        // Render
        echo '<div class="bw-image" style="text-align: ' . $alignment . ';">';

            echo '<div class="bw-img-' . $type . ' ' . $settings['hover_animation'] . '" id="'. $data_id .'">';

            if ( $image_link == 'yes') { echo '<a href="' . $settings['bw_image_link']['url'] . '"' . $target . $nofollow . ' class="bw-image-link">'; }

				echo '<img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'thumbnail', $settings ) . '" class=" ' . $parallax . ' bw-img-tag">';

            if ( $image_link == 'yes'){ echo '</a>'; }

            echo '</div>';

		echo '</div>';

		if ( $image_movement == 'on') {
			echo '<script>
				( function( $ ) {
					$( document ).ready( function() {
						var attr = "' . $script_id . '",
							duration = "'.$duration.'",
							trigger_hook = "'.$trigger_hook.'";
						if (typeof attr !== typeof undefined && attr !== false) {
							var controller = new ScrollMagic.Controller();
							var data_id = attr;
							var scr_img = data_id + " img";
							console.log(data_id);
							console.log(scr_img);
							new ScrollMagic.Scene({
								triggerElement: attr,
								duration: duration,
								triggerHook: trigger_hook
							})
							.setTween( scr_img , {  ' . $opacity . $rotation . $horizontal_movement . $vertical_movement . '  } ).addTo( controller );
						}
					}); // end document ready
				})( jQuery );
				</script>';
		}

		if ( $parallax == 'bw-parallax') {
			echo '<script>
			jQuery(document).ready(function () {

					var image = document.getElementsByClassName("bw-parallax");
					new simpleParallax(image);

				});
			</script>';
		}

	}

}