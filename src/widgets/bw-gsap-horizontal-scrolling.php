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
 * Horizontal scrolling widget.
 *
 * Horizontal scrolling section driven by GSAP ScrollTrigger.
 *
 * @since 1.0.0
 */
class GSAPHorizontalScrolling extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-gsap-horizontal-scrolling', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/gsap-horizontal-scrolling.css', [], BLACK_WIDGETS_VERSION );

        $this->register_horizontal_scrolling_script();
    }

	/**
	 * Get widget name.
	 *
	 * Retrieve the horizontal scrolling widget name.
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
	 * Retrieve the horizontal scrolling widget title.
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
	 * Retrieve the horizontal scrolling widget icon.
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
	 * Retrieve the list of categories the horizontal scrolling widget belongs to.
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
        $this->register_horizontal_scrolling_script();

        return [ 'black-widgets-gsap-horizontal-scrolling' ];
    }

    /**
     * Re-register the widget script with current GSAP deps (deregister first so deps update).
     *
     * Listing GSAP handles unconditionally would make WordPress drop this script
     * whenever the CDN URLs are missing or refused, so the JS guard never runs.
     */
    private function register_horizontal_scrolling_script() {
        $deps = [ 'jquery' ];

        if ( \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_ready() ) {
            \Modernaweb\BlackWidgets\Plugin_Options::register_gsap_scripts();

            if ( wp_script_is( 'GSAP', 'registered' ) ) {
                $deps[] = 'GSAP';
            }
            if ( wp_script_is( 'GSAP-ScrollTrigger', 'registered' ) ) {
                $deps[] = 'GSAP-ScrollTrigger';
            }
        }

        wp_deregister_script( 'black-widgets-gsap-horizontal-scrolling' );
        wp_register_script(
            'black-widgets-gsap-horizontal-scrolling',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/gsap-horizontal-scrolling.js',
            $deps,
            BLACK_WIDGETS_VERSION,
            true
        );
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

	/**
	 * Register the horizontal scrolling widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

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
				'heading' => esc_html__( 'Feel free to edit. Check this widget\'s demo.', 'blackwidgets' ),
				'content' => sprintf(
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-horizontal/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'blackwidgets' ),
					esc_html__( 'Demo', 'blackwidgets' )
				),
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => esc_html__( 'Select Content', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1' 				=> esc_html__( 'Style 1', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Do not forget every template and this section should be full-screen', 'blackwidgets' ),
			]
		);

		$repeater = new \Elementor\Repeater();

        $source = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' );
        $elementor_tpl = $source ? $source->get_items() : [];
		$elementor_tpl_opts = [ '0' => esc_html__( 'Elementor template is not defined yet.', 'blackwidgets' ) ];

		if ( ! empty( $elementor_tpl ) ) {
			$elementor_tpl_opts = [ '0' => esc_html__( 'Select elementor template', 'blackwidgets' ) ];

			foreach ( $elementor_tpl as $template ) {
				$elementor_tpl_opts[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
		}

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'blackwidgets' ),
				'label_block' => true,
			]
		);

        $repeater->add_control(
            'custom_template',
            [
                'label'			=> esc_html__( 'Custom Template', 'blackwidgets' ),
                'type'			=> \Elementor\Controls_Manager::SELECT,
                'default'		=> '0',
                'options'		=> $elementor_tpl_opts,
                'label_block'	=> true,
            ]
        );

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Repeater List', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Item content #1', 'blackwidgets' ),
						'custom_template' => [
                            'value' => '170',
                        ],
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'layout_section',
			[
				'label' => esc_html__( 'Layout', 'blackwidgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'section_height',
			[
				'label'   => esc_html__( 'Height', 'blackwidgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto'     => esc_html__( 'Auto', 'blackwidgets' ),
					'viewport' => esc_html__( 'Fit Viewport', 'blackwidgets' ),
				],
			]
		);

		$this->add_control(
			'stack_below',
			[
				'label'       => esc_html__( 'Stack Below', 'blackwidgets' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'mobile',
				'options'     => [
					'mobile' => esc_html__( 'Mobile', 'blackwidgets' ),
					'tablet' => esc_html__( 'Tablet', 'blackwidgets' ),
					'never'  => esc_html__( 'Never', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Below this breakpoint, panels stack vertically.', 'blackwidgets' ),
			]
		);

		$this->end_controls_section();

		// Typography section
		$this->start_controls_section(
			'typo_section',
			[
				'label' => esc_html__( 'General Typography Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
        );

		$this->add_control(
			'style_alert_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
				'selectors' => [
					'{{WRAPPER}} .bw-x-section > .elementor' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_alert_typography1',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-x-section > .elementor',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty( $settings['list'] ) ) {
			return;
		}

		$height = isset( $settings['section_height'] ) ? (string) $settings['section_height'] : 'auto';
		if ( 'viewport' !== $height ) {
			$height = 'auto';
		}

		$stack_below = isset( $settings['stack_below'] ) ? (string) $settings['stack_below'] : 'mobile';
		if ( ! in_array( $stack_below, [ 'mobile', 'tablet', 'never' ], true ) ) {
			$stack_below = 'mobile';
		}

		$this->add_render_attribute(
			'hscroll',
			[
				'class'            => 'bw-horizontal-section',
				'data-height'      => $height,
				'data-stack-below' => $stack_below,
			]
		);

		echo '<div ' . $this->get_render_attribute_string( 'hscroll' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="bw-section">';
		echo '<div class="bw-hscroll-track">';
		foreach ( $settings['list'] as $item ) {
			$template_id = isset( $item['custom_template'] ) ? $item['custom_template'] : 0;
			if ( is_array( $template_id ) ) {
				$template_id = isset( $template_id['value'] ) ? $template_id['value'] : 0;
			}
			$template_id = absint( $template_id );
			if ( ! $template_id ) {
				continue;
			}
			echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\GSAPHorizontalScrolling', 'Black_Widgets\BLACK_WIDGETS_GSAP_HORIZONTAL_SCROLLING');