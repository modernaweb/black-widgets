<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class MagicLink extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-magic', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/magic.css', [], BLACK_WIDGETS_VERSION );
        $this->register_magic_link_script();
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
		return 'b_magic';
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
		return __( 'Black Magic Link', 'blackwidgets' );
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
		return 'eicon-editor-external-link';
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
        return [ 'black-widgets-magic' ];
    }

    public function get_script_depends() {
        // Avoid reading instance settings during early Elementor enqueue.
        if ( ! $this->is_splittext_enabled() ) {
            return [];
        }

        $this->register_magic_link_script();

        return [ 'black-widgets-magic-link' ];
    }

    /**
     * Re-register Magic Link script with current GSAP deps (deregister first so deps update).
     */
    private function register_magic_link_script() {
        $deps = [ 'jquery' ];

        if ( $this->is_splittext_enabled() ) {
            \Modernaweb\BlackWidgets\Plugin_Options::register_gsap_scripts();

            if ( wp_script_is( 'GSAP', 'registered' ) ) {
                $deps[] = 'GSAP';
            }
            if ( wp_script_is( 'GSAP-SplitText', 'registered' ) ) {
                $deps[] = 'GSAP-SplitText';
            }
        }

        wp_deregister_script( 'black-widgets-magic-link' );
        wp_register_script(
            'black-widgets-magic-link',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/magic-link.js',
            $deps,
            BLACK_WIDGETS_VERSION,
            true
        );
    }

    /**
     * Current widget_type from settings.
     */
    private function get_current_type() {
        $settings = $this->get_early_settings();
        return isset( $settings['widget_type'] ) ? (string) $settings['widget_type'] : '';
    }

    /**
     * Raw settings safe when Elementor data is not initialized yet.
     *
     * @return array
     */
    private function get_early_settings(): array {
        return black_widgets_elementor_raw_settings( $this );
    }

    /**
     * GSAP core + SplitText CDN available.
     */
    private function is_splittext_enabled() {
        return \Modernaweb\BlackWidgets\Plugin_Options::has_gsap_core()
            && \Modernaweb\BlackWidgets\Plugin_Options::has_split_text();
    }

    /**
     * @param string $type Type key.
     */
    private function is_split_type( $type ) {
        return in_array(
            $type,
            [
                'stx_rise',
                'stx_fade',
                'stx_wave',
                'stx_flip',
                'stx_swap',
                'stx_blur',
                'stx_elastic',
            ],
            true
        );
    }

    /**
     * Labels for SplitText link types.
     *
     * @return array<string, string>
     */
    private function get_split_type_labels() {
        return [
            'stx_rise'    => esc_html__( 'Split · Char Rise', 'blackwidgets' ),
            'stx_fade'    => esc_html__( 'Split · Char Fade', 'blackwidgets' ),
            'stx_wave'    => esc_html__( 'Split · Wave', 'blackwidgets' ),
            'stx_flip'    => esc_html__( 'Split · Perspective Flip', 'blackwidgets' ),
            'stx_swap'    => esc_html__( 'Split · Vertical Swap', 'blackwidgets' ),
            'stx_blur'    => esc_html__( 'Split · Blur Reveal', 'blackwidgets' ),
            'stx_elastic' => esc_html__( 'Split · Elastic Pop', 'blackwidgets' ),
        ];
    }

    /**
     * Map type to the data-effect key used by JS.
     *
     * @param string $type Type key.
     * @return string
     */
    private function get_split_effect_key( $type ) {
        $map = [
            'stx_rise'    => 'rise',
            'stx_fade'    => 'fade',
            'stx_wave'    => 'wave',
            'stx_flip'    => 'flip',
            'stx_swap'    => 'swap',
            'stx_blur'    => 'blur',
            'stx_elastic' => 'elastic',
        ];
        return $map[ $type ] ?? '';
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-magic-link/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'blackwidgets' ),
					esc_html__( 'Demo', 'blackwidgets' )
				),
			]
		);

		// Select type of the title
		$type_options = [
			'minimal' 	=> esc_html__( 'Minimal', 'blackwidgets' ),
			'liner' 	=> esc_html__( 'Liner', 'blackwidgets' ),
			'modern' 	=> esc_html__( 'Modern', 'blackwidgets' ),
			'simple' 	=> esc_html__( 'Simple', 'blackwidgets' ),
			'heart' 	=> esc_html__( 'Heart Beat', 'blackwidgets' ),
			'pullltr' 	=> esc_html__( 'Pull Left To Right', 'blackwidgets' ),
			'pullrtl' 	=> esc_html__( 'Pull Right To Left', 'blackwidgets' ),
			'pullttb' 	=> esc_html__( 'Pull Top To Bottom', 'blackwidgets' ),
			'pullbtt' 	=> esc_html__( 'Pull Bottom To Top', 'blackwidgets' ),
			'arrow' 	=> esc_html__( 'Arrow', 'blackwidgets' ),
			'anchor' 	=> esc_html__( 'Anchor', 'blackwidgets' ),
			'wheel' 	=> esc_html__( 'Wheel', 'blackwidgets' ),
		];

		$split_labels = $this->get_split_type_labels();
		$current_type = $this->get_current_type();

		// SplitText hover types - only offer when GSAP + SplitText CDN are ready.
		if ( $this->is_splittext_enabled() ) {
			foreach ( $split_labels as $key => $label ) {
				$type_options[ $key ] = $label;
			}
		} elseif ( isset( $split_labels[ $current_type ] ) ) {
			// Keep a previously saved split type visible if CDN was turned off.
			$type_options[ $current_type ] = $split_labels[ $current_type ];
		}

		$this->add_control(
			'widget_type',
			[
				'label' => esc_html__( 'Select Type', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'minimal',
				'options' => $type_options,
				'description' => esc_html__( 'CSS types work without CDN. Split types need GSAP and SplitText in Settings.', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_hover_text',
			[
				'label' => esc_html__( 'Hover Text', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => esc_html__( 'Same as link text if empty', 'blackwidgets' ),
				'condition' => [
					'widget_type' => 'stx_swap',
				],
			]
		);

		$this->add_control(
			'widget_split_note',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-panel-alert elementor-panel-alert-info">'
					. esc_html__( 'Split types need GSAP CDN + SplitText CDN enabled in Black Widgets → Settings.', 'blackwidgets' )
					. '</div>',
				'condition' => [
					'widget_type' => array_keys( $split_labels ),
				],
			]
		);

		$this->add_control(
			'widget_text',
			[
				'label' => esc_html__( 'Button Text', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Let\'s started', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'blackwidgets' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$split_type_keys = array_keys( $split_labels );

		$this->add_control(
			'stx_icon_heading',
			[
				'label' => esc_html__( 'Split Icon', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'widget_type' => $split_type_keys,
				],
			]
		);

		$this->add_control(
			'stx_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' => esc_html__( 'No', 'blackwidgets' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'widget_type' => $split_type_keys,
				],
			]
		);

		$this->add_control(
			'stx_icon',
			[
				'label' => esc_html__( 'Icon', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'widget_type' => $split_type_keys,
					'stx_show_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'stx_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'before' => [
						'title' => esc_html__( 'Before', 'blackwidgets' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => esc_html__( 'After', 'blackwidgets' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'after',
				'toggle' => false,
				'condition' => [
					'widget_type' => $split_type_keys,
					'stx_show_icon' => 'yes',
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
				'label' => esc_html__( 'Box Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		// Alignment (Elementor text-align on wrapper; skins use inline/inline-flex)
		$this->add_responsive_control(
			'widget_box_alignment',
			[
				'label'     => esc_html__( 'Text Alignment', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link' => 'text-align: {{VALUE}};',
				],
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
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-magic-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_1_hover',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
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
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_hover_box_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
        // End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_back_link_typo',
			[
				'label' => esc_html__( 'Text Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'style_main_back_link_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_back_link_typography1',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_main_back_link_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		$this->add_control(
			'hr6',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_back_link_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_back_link_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr7',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_back_link_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_back_link_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_back_link_hover_typo',
			[
				'label' => esc_html__( 'Hover Text Typography', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Color
		$this->add_control(
			'style_main_back_link_hover_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover,{{WRAPPER}} .bw-magic-link .bw-magic-wheel:hover span' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'style_main_back_link_hover_typography1',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_hover_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		$this->add_control(
			'hr8',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'style_main_back_link_hover_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		$this->add_control(
			'hr9',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'style_main_back_link_hover_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'style_main_back_link_hover_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr10',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'style_main_back_link_hover_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		// Border Radius
		$this->add_control(
			'style_main_back_link_hover_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'style_main_back_link_hover_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-magic-link a:hover',
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Main Text Typography
		$this->start_controls_section(
			'style_section_other',
			[
				'label' => esc_html__( 'Other Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Normal Height
		$this->add_control(
			'top_and_bottom_line_normal_height',
			[
				'label' => esc_html__( 'Normal Height', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 14,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
					],
				],
			]
		);

		// Hover Height
		$this->add_control(
			'top_and_bottom_line_hover_height',
			[
				'label' => esc_html__( 'Hover Height', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:hover:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:hover:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
					],
				],
			]
		);

		// Normal Width
		$this->add_control(
			'top_and_bottom_line_normal_width',
			[
				'label' => esc_html__( 'Normal Width', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 14,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'widget_type' 	=> [
						'pullltr',
						'pullrtl',
					],
				],
			]
		);

		// Hover Width
		$this->add_control(
			'top_and_bottom_line_hover_width',
			[
				'label' => esc_html__( 'Hover Width', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 10,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:hover:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:hover:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'widget_type' 	=> [
						'pullltr',
						'pullrtl',
					],
				],
			]
		);

		// Liner Height
		$this->add_control(
			'liner_line_height',
			[
				'label' => esc_html__( 'Liner Height', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 7,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link a.bw-magic-liner:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' 	=> [
					'widget_type' 	=> [
						'liner',
					],
				],
			]
		);

        $this->add_control(
            'bg_section_label',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'label' => esc_html__( 'Line Color', 'blackwidgets' ),
                'separator' => 'before',
                'condition' 	=> [
                    'widget_type' 	=> [
                        'pullttb',
                        'pullbtt',
                        'pullltr',
                        'pullrtl',
                        'liner',
                        'modern',
                        'minimal',
                        'heart',
                    ],
                ],
            ]
        );

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'line_bg',
				'label' => esc_html__( 'Bg Color/Line Color', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-magic-link a.bw-magic-modern:before, {{WRAPPER}} .bw-magic-link a.bw-magic-minimal:after, {{WRAPPER}} .bw-magic-link a.bw-magic-liner:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before, {{WRAPPER}} .bw-magic-link .bw-magic-heart span',
				'condition' 	=> [
					'widget_type' 	=> [
						'pullttb',
						'pullbtt',
						'pullltr',
						'pullrtl',
						'liner',
						'modern',
						'minimal',
						'heart',
					],
				],
			]
		);

        // Color
        $this->add_control(
            'line_color_modern',
            [
                // Distinct from the line_bg group control above, which fills the :before layer.
                'label' => esc_html__( 'Underline Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-magic-link .bw-magic-modern' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .bw-magic-link .bw-magic-simple:hover' => 'text-decoration-color: {{VALUE}} !important;',
                ],
                'condition' 	=> [
                    'widget_type' 	=> [
                        'modern',
                        'simple',
                    ],
                ],
            ]
        );

		// Color
		$this->add_control(
			'svg_heart_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-heart span svg' => 'fill: {{VALUE}}',
				],
				'condition' 	=> [
					'widget_type' 	=> [
						'heart',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'svg_wheel_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g line, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g polyline, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g circle' => 'stroke: {{VALUE}} !important',
				],
				'condition' 	=> [
					'widget_type' 	=> [
						'wheel',
					],
				],
			]
		);

		$this->add_control(
			'magic_link_bg_opacity',
			[
				'label' => esc_html__( 'Opacity', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g line, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g polyline, {{WRAPPER}} .bw-magic-link .bw-magic-wheel svg g circle, {{WRAPPER}} .bw-magic-link a.bw-magic-modern:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullttb:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullbtt:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullltr:before, {{WRAPPER}} .bw-magic-link a.bw-magic-pullrtl:before, {{WRAPPER}} .bw-magic-link .bw-magic-heart span svg' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();
		// End

		// Split icon style (CDN Split types only)
		$this->start_controls_section(
			'style_section_stx_icon',
			[
				'label' => esc_html__( 'Split Icon', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'widget_type' => array_keys( $this->get_split_type_labels() ),
					'stx_show_icon' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'stx_icon_size',
			[
				'label' => esc_html__( 'Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 8,
						'max' => 80,
						'step' => 1,
					],
					'em' => [
						'min' => 0.4,
						'max' => 4,
						'step' => 0.05,
					],
				],
				'default' => [
					'unit' => 'em',
					'size' => 0.95,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-ml__icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bw-magic-link .bw-ml__icon svg' => 'width: 1em; height: 1em;',
				],
			]
		);

		$this->add_responsive_control(
			'stx_icon_gap',
			[
				'label' => esc_html__( 'Gap', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 48,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.05,
					],
				],
				'default' => [
					'unit' => 'em',
					'size' => 0.45,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-ml__link.bw-ml--has-icon' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'stx_icon_style_tabs' );

		$this->start_controls_tab(
			'stx_icon_style_normal',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'stx_icon_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-ml__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bw-magic-link .bw-ml__icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'stx_icon_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-ml__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'stx_icon_style_hover',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'stx_icon_hover_color',
			[
				'label' => esc_html__( 'Color', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-ml__link:hover .bw-ml__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bw-magic-link .bw-ml__link:hover .bw-ml__icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'stx_icon_hover_rotate',
			[
				'label' => esc_html__( 'Rotate', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'deg' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-magic-link .bw-ml__link:hover .bw-ml__icon' => 'transform: rotate({{SIZE}}deg);',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

    /**
     * Whether split icon should render.
     *
     * @param array $settings Widget settings.
     * @return bool
     */
    private function has_split_icon( $settings ) {
        if ( empty( $settings['stx_show_icon'] ) || 'yes' !== $settings['stx_show_icon'] ) {
            return false;
        }
        if ( empty( $settings['stx_icon'] ) || empty( $settings['stx_icon']['value'] ) ) {
            return false;
        }
        return true;
    }

    /**
     * Print split icon markup (outside SplitText target).
     *
     * @param array $settings Widget settings.
     */
    private function render_split_icon( $settings ) {
        if ( ! $this->has_split_icon( $settings ) ) {
            return;
        }
        echo '<span class="bw-ml__icon" aria-hidden="true">';
        \Elementor\Icons_Manager::render_icon( $settings['stx_icon'], [ 'aria-hidden' => 'true' ] );
        echo '</span>';
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
		$type 	        	= isset($settings['widget_type']) 				? $settings['widget_type']				: '';
        $text 	        	= isset($settings['widget_text']) 				? $settings['widget_text'] 				: '';
		$target   = ! empty($settings['website_link']['is_external']) ? ' target="_blank"' : '';
        $nofollow = ! empty($settings['website_link']['nofollow'])   ? ' rel="nofollow"'  : '';
		$url      = isset( $settings['website_link']['url'] ) ? $settings['website_link']['url'] : '';

        $type = esc_attr( $type );
        $text = esc_html( $text );

		// SplitText hover types (CDN-gated in panel; graceful fallback markup if CDN off).
		if ( $this->is_split_type( $type ) ) {
			$effect = $this->get_split_effect_key( $type );
			$hover_text = isset( $settings['widget_hover_text'] ) ? trim( (string) $settings['widget_hover_text'] ) : '';
			if ( '' === $hover_text ) {
				$hover_text = isset( $settings['widget_text'] ) ? (string) $settings['widget_text'] : '';
			}
			$hover_text = esc_html( $hover_text );

			$has_icon = $this->has_split_icon( $settings );
			$icon_pos = ( isset( $settings['stx_icon_position'] ) && 'before' === $settings['stx_icon_position'] ) ? 'before' : 'after';
			$link_class = 'bw-ml__link bw-magic-' . $type;
			if ( $has_icon ) {
				$link_class .= ' bw-ml--has-icon bw-ml--icon-' . $icon_pos;
			}

			echo '<div class="bw-magic-link bw-magic-stx" data-bw-ml data-effect="' . esc_attr( $effect ) . '">';
			echo '<a href="' . esc_url( $url ) . '"' . $target . $nofollow . ' class="' . esc_attr( $link_class ) . '">';

			if ( $has_icon && 'before' === $icon_pos ) {
				$this->render_split_icon( $settings );
			}

			if ( 'stx_swap' === $type ) {
				echo '<span class="bw-ml__swap" aria-hidden="true">';
				echo '<span class="bw-ml__swap-line bw-ml__swap-top"><span class="bw-ml__text">' . $text . '</span></span>';
				echo '<span class="bw-ml__swap-line bw-ml__swap-bottom"><span class="bw-ml__text">' . $hover_text . '</span></span>';
				echo '</span>';
				echo '<span class="bw-ml__sr">' . $text . '</span>';
			} else {
				echo '<span class="bw-ml__mask"><span class="bw-ml__text">' . $text . '</span></span>';
			}

			if ( $has_icon && 'after' === $icon_pos ) {
				$this->render_split_icon( $settings );
			}

			echo '</a></div>';
			return;
		}

		// Render - alignment via Elementor selectors on .bw-magic-link (no left/center/right classes)
		switch ($type) {
			case 'heart':
                echo '<div class="bw-magic-link"><a href="' . esc_url( $url ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . ' <span><svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="160,115.4 180.7,96 352,256 180.7,416 160,396.7 310.5,256 "/></svg></span></a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
			case 'liner':
				echo '<div class="bw-magic-link"><a href="' . esc_url( $url ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '</a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
			case 'arrow':
				echo '<div class="bw-magic-link"><a href="' . esc_url( $url ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '<span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16" fill="currentColor"><path d="M438.6 233.4L278.6 73.37c-9.373-9.373-24.56-9.373-33.94 0s-9.373 24.56 0 33.94L370.7 224H24C10.75 224 0 234.8 0 248S10.75 272 24 272h346.7l-126.1 116.7c-9.373 9.373-9.373 24.56 0 33.94C250.3 426.5 255.1 428.3 260 428.3s9.373-1.811 13.06-5.498l160-160C448 257.9 448 242.1 438.6 233.4z"/></svg></span></a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
			case 'wheel':
				echo '<div class="bw-magic-link"><a href="' . esc_url( $url ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '"><svg><g><line x2="227.62" y1="31.28" y2="31.28"></line><polyline points="222.62 25.78 228.12 31.28 222.62 36.78"></polyline><circle cx="224.67" cy="30.94" r="30.5" transform="rotate(180 224.67 30.94) scale(1, -1) translate(0, -61)"></circle></g></svg><span>' . $text . '</span></a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
			default:
				echo '<div class="bw-magic-link"><a href="' . esc_url( $url ) . '"' . $target . $nofollow . ' class="bw-magic-' . $type . '">' . $text . '</a></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;
		}

	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\MagicLink', 'Black_Widgets\BLACK_WIDGETS_Magic_Link');
