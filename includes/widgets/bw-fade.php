<?php
namespace Elementor;
namespace BW_Modernaweb\Includes\Widgets;
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
class BW_Fade extends \Elementor\Widget_Base {

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
		return __( 'Black Fade', 'bw' );
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
		return 'fa fa-window-minimize';
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
		return [ 'bw' ];
	}

	/**
	 * Register line widget controls.
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
				'label' => __( 'Content', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => __( 'Select Type', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bw-t-2',
				'options' => [
					'bw-t-1' => __( 'Image', 'bw' ),
					'bw-t-2' => __( 'Line 1', 'bw' ),
					'bw-t-3' => __( 'Line 2', 'bw' ),
				],
				'description' => __( 'It just work with scroll down and does not work on first section. it works once', 'bw' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'bw' ),
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
				'name' => 'thumbnail', //
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'exclude' => [ 'custom' ],
				'include' => [],
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
				'label' => __( 'Add link for Image?', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'no' => __( 'No', 'bw' ),
					'yes' => __( 'Yes', 'bw' ),
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
				'label' => __( 'Link', 'bw' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'bw' ),
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

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Box Style', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => __( 'Background', 'bw' ),
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
				'label' => __( 'Margin', 'bw' ),
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
				'label' => __( 'Padding', 'bw' ),
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-line, {{WRAPPER}} .bw-load-img',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
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
				'label' => __( 'Image & line  Style', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'start_width',
			[
				'label' => __( 'Start line width', 'bw' ),
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
					'widget_type' => [
						'!bw-t-1',
					],
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'line_border',
				'label' => __( 'Border', 'bw' ),
                'selector' => '{{WRAPPER}} .bw-line',
				'condition'  => [
					'widget_type' => [
						'bw-t-3',
					],
				],
			]
		);

		$this->add_control(
			'img_width',
			[
				'label' => __( 'Image Width', 'bw' ),
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
				'label' => __( 'Image Cover X', 'bw' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-load-img .bw-image-grow-cover ',
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
				'description' => __( 'After change style, scroll down to see changes', 'bw' ),
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Image Hover Animation', 'plugin-domain' ),
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
        $type 	        		= isset($settings['widget_type']) 				? $settings['widget_type'] 							: '';
        $image_URL 	       		= isset( $settings['image']['url']) 			?  $settings['image']['url'] 						: '';
        $image_link 	   		= isset($settings['image_link']) 				? $settings['image_link'] 							: '';
		$target            		= $settings['image_link_url']['is_external'] 	? 'target="_blank"' 								: '';
        $nofollow          		= $settings['image_link_url']['nofollow'] 		? ' rel="nofollow"' 								: '';

		// Render
        switch ($type) {
			case 'bw-t-1':
				echo '<div class="bw-load-img">';
					echo '<div class="bw-img ' . $settings['hover_animation'] . '">';
						if ( $image_link == 'yes') { echo '<a href="' . $settings['image_link_url']['url'] . '"' . $target . $nofollow . ' class="bw-image-link">'; }
						echo '<div class="bw-image-grow-cover"></div>';
							echo '<img src="' . Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'thumbnail', $settings ) . '" class="">';
						if ( $image_link == 'yes'){ echo '</a>'; }
					echo '</div>';
				echo '</div>';
				break;
			default:
				echo '<div class="bw-line ' . $type . '"></div>';
				break;
		}

	}

}