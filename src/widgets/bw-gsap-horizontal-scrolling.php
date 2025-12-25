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

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class GSAPHorizontalScrolling extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-gsap-horizontal-scrolling', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/gsap-horizontal-scrolling.css', [], BLACK_WIDGETS_VERSION );

        wp_register_script( 'black-widgets-gsap-horizontal-scrolling', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/gsap-horizontal-scrolling.js', [ 'jquery', 'GSAP', 'GSAP-ScrollTrigger', 'TimelineMax' ], BLACK_WIDGETS_VERSION, true );
    }

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
		return __( 'Black Horizontal', 'black-widgets' );
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
		return 'eicon-slider-push';
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

    public function get_style_depends() {
        return [ 'black-widgets-gsap-horizontal-scrolling' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-gsap-horizontal-scrolling' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-horizontal/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'black-widgets' ),
					esc_html__( 'Demo', 'black-widgets' )
				),
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => esc_html__( 'Select Content', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1' 				=> esc_html__( 'Style 1', 'black-widgets' ),
				],
				'description' => esc_html__( 'Do not forget every template and this section should be full-screen', 'black-widgets' ),
			]
		);

		$repeater = new \Elementor\Repeater();

		$elementor_tpl = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
		$elementor_tpl_opts = [ '0' => esc_html__( 'Elementor template is not defined yet.', 'black-widgets' ) ];

		if ( ! empty( $elementor_tpl ) ) {
			$elementor_tpl_opts = [ '0' => esc_html__( 'Select elementor template', 'black-widgets' ) ];

			foreach ( $elementor_tpl as $template ) {
				$elementor_tpl_opts[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
		}

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'black-widgets' ),
				'label_block' => true,
			]
		);

        $repeater->add_control(
            'custom_template',
            [
                'label'			=> esc_html__( 'Custom Template', 'black-widgets' ),
                'type'			=> \Elementor\Controls_Manager::SELECT,
                'default'		=> '0',
                'options'		=> $elementor_tpl_opts,
                'label_block'	=> true,
            ]
        );

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Item content #1', 'black-widgets' ),
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
				'label' => esc_html__( 'General Typography Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
        );

		// Color
		$this->add_control(
			'style_alert_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
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
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
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
                    echo  \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item['custom_template'], true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                echo '</div>';
			echo '</div>';
		}
	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\GSAPHorizontalScrolling', 'Black_Widgets\BLACK_WIDGETS_GSAP_HORIZONTAL_SCROLLING');
