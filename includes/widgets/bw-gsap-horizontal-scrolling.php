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
use Elementor\Group_Control_Css_Filter;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BLACK_WIDGETS_GSAP_HORIZONTAL_SCROLLING extends \Elementor\Widget_Base {

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
		return 'b_gsap_horizontal_scrolling';
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
		return __( 'Black Horizontal', 'blackwidgets' );
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
				'default' => 'style_1',
				'options' => [
					'style_1' 				=> __( 'Style 1', 'blackwidgets' ),
				],
				'description' => __( 'Do not forget every template and this section should be full-screen', 'blackwidgets' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$elementor_tpl = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();        
		$elementor_tpl_opts = [ '0' => __( 'Elementor template is not defined yet.', 'blackwidgets' ) ];

		if ( ! empty( $elementor_tpl ) ) {
			$elementor_tpl_opts = [ '0' => __( 'Select elementor template', 'blackwidgets' ) ];

			foreach ( $elementor_tpl as $template ) {
				$elementor_tpl_opts[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
		}

		$repeater->add_control(
			'list_title', [
				'label' => __( 'Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'blackwidgets' ),
				'label_block' => true,
			]
		);

        $repeater->add_control(
            'custom_template',
            [
                'label'			=> __( 'Custom Template', 'blackwidgets' ),
                'type'			=> \Elementor\Controls_Manager::SELECT,
                'default'		=> '0',
                'options'		=> $elementor_tpl_opts,
                'label_block'	=> true,
            ]
        );

		$this->add_control(
			'list',
			[
				'label' => __( 'Repeater List', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Item content #1', 'blackwidgets' ),
						'custom_template' => [
                            'value' => '170',
                        ],
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Typography section
		$this->start_controls_section(
			'typo_section',
			[
				'label' => __( 'General Typography Style', 'blackwidgets' ),
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
					'{{WRAPPER}} .bw-x-section > .elementor' => 'color: {{VALUE}}',
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
				'selector' => '{{WRAPPER}} .bw-x-section > .elementor',
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

		// Section Template
		// $ctsection		= isset( $settings['custom_template']) ? $settings['custom_template'] : '';
        // $returnx        =  \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $ctsection );
        // echo  \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['custom_template'] );

		$options = get_option('plugin_options') ? get_option('plugin_options') : '';
        $gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';

		$data_id                	= 'bw_' . uniqid();
		$script_id              	= '#' . $data_id;


		// Render
        if ( $settings['list'] ) {
            echo '<div class="bw-horizontal-section">';
			echo '<div class="bw-section">';
                foreach (  $settings['list'] as $item ) {
                    echo  \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item['custom_template'], true );
                }
                echo '</div>';
			echo '</div>';
		}

			echo '<script>
				jQuery(window).ready(function($) {
					gsap.registerPlugin(ScrollTrigger);
					let sections = gsap.utils.toArray(".bw-section > .elementor");
					gsap.to(sections, {
					xPercent: -100 * (sections.length - 1),
					ease: "none",
					scrollTrigger: {
						trigger: ".bw-section",
						pin: true,
						scrub: 1,
						snap: directionalSnap(1 / (sections.length - 1)),
						// base vertical scrolling on how wide the container is so it feels more natural.
						end: "+=2700"
					}
					});
					// helper function for causing the sections to always snap in the direction of the scroll (next section) rather than whichever section is "closest" when scrolling stops.
					function directionalSnap(increment) {
					let snapFunc = gsap.utils.snap(increment);
					return (raw, self) => {
						let n = snapFunc(raw);
						return Math.abs(n - raw) < 1e-4 || (n < raw) === self.direction < 0 ? n : self.direction < 0 ? n - increment : n + increment;
					};
					}
				});
            </script>';
	}

}