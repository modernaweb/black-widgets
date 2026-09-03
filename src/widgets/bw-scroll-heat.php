<?php
namespace Modernaweb\BlackWidgets\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

/**
 * Black Scroll Heat - Type 1: scrubbed word heat; Type 2: animated text; Type 3: line reveal.
 */
class ScrollHeat extends \Elementor\Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style(
			'black-widgets-scroll-heat',
			BLACK_WIDGETS_PLUGIN_URL . 'assets/css/scroll-heat.css',
			[],
			BLACK_WIDGETS_VERSION
		);
		wp_register_style(
			'black-widgets-scroll-heat-animated',
			BLACK_WIDGETS_PLUGIN_URL . 'assets/css/scroll-heat-animated.css',
			[],
			BLACK_WIDGETS_VERSION
		);
		wp_register_style(
			'black-widgets-scroll-heat-line-reveal',
			BLACK_WIDGETS_PLUGIN_URL . 'assets/css/scroll-heat-line-reveal.css',
			[],
			BLACK_WIDGETS_VERSION
		);

		$this->register_scroll_heat_scripts();
	}

	public function get_name() {
		return 'b_scroll_heat';
	}

	public function get_title() {
		return esc_html__( 'Black Scroll Heat', 'blackwidgets' );
	}

	public function get_icon() {
		return 'eicon-animation-text';
	}

	public function get_categories() {
		return [ 'black_widgets' ];
	}

	public function get_keywords() {
		return [ 'scroll', 'heat', 'animated', 'text', 'gsap', 'reveal', 'split', 'line', 'black' ];
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Current Select Type.
	 */
	private function get_widget_type() {
		$settings = $this->get_early_settings();
		$type     = isset( $settings['widget_type'] ) ? (string) $settings['widget_type'] : 'scroll_heat';
		return in_array( $type, [ 'scroll_heat', 'animated_text', 'line_reveal' ], true ) ? $type : 'scroll_heat';
	}

	/**
	 * Raw settings safe when Elementor data is not initialized yet.
	 *
	 * @return array
	 */
	private function get_early_settings(): array {
		return black_widgets_elementor_raw_settings( $this );
	}

	private function is_gsap_bundle_ready() {
		return \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_split_ready();
	}

	public function get_style_depends() {
		// Avoid reading instance settings during early Elementor enqueue.
		return [ 'black-widgets-scroll-heat', 'black-widgets-scroll-heat-animated', 'black-widgets-scroll-heat-line-reveal' ];
	}

	public function get_script_depends() {
		$this->register_scroll_heat_scripts();

		return [ 'black-widgets-scroll-heat', 'black-widgets-scroll-heat-animated' ];
	}

	/**
	 * Re-register Scroll Heat scripts with current GSAP deps (deregister first so deps update).
	 */
	private function register_scroll_heat_scripts() {
		$deps = [ 'jquery' ];

		if ( $this->is_gsap_bundle_ready() ) {
			\Modernaweb\BlackWidgets\Plugin_Options::register_gsap_scripts();

			if ( wp_script_is( 'GSAP', 'registered' ) ) {
				$deps[] = 'GSAP';
			}
			if ( wp_script_is( 'GSAP-ScrollTrigger', 'registered' ) ) {
				$deps[] = 'GSAP-ScrollTrigger';
			}
			if ( wp_script_is( 'GSAP-SplitText', 'registered' ) ) {
				$deps[] = 'GSAP-SplitText';
			}
		}

		wp_deregister_script( 'black-widgets-scroll-heat' );
		wp_register_script(
			'black-widgets-scroll-heat',
			BLACK_WIDGETS_PLUGIN_URL . 'assets/js/scroll-heat.js',
			$deps,
			BLACK_WIDGETS_VERSION,
			true
		);

		wp_deregister_script( 'black-widgets-scroll-heat-animated' );
		wp_register_script(
			'black-widgets-scroll-heat-animated',
			BLACK_WIDGETS_PLUGIN_URL . 'assets/js/scroll-heat-animated.js',
			$deps,
			BLACK_WIDGETS_VERSION,
			true
		);
	}

	/**
	 * @return array<int, string>
	 */
	private function get_allowed_tags() {
		return [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span' ];
	}

	/**
	 * @param string $tag Raw tag.
	 * @param string $fallback Fallback tag.
	 */
	private function get_allowed_tag( $tag, $fallback = 'p' ) {
		$tag = strtolower( (string) $tag );
		return in_array( $tag, $this->get_allowed_tags(), true ) ? $tag : $fallback;
	}

	/**
	 * @param string $text Raw text.
	 */
	private function escape_typography_text( $text ) {
		return wp_kses(
			(string) $text,
			[
				'br'     => [],
				'strong' => [],
				'b'      => [],
				'hr'     => [],
			]
		);
	}

	/**
	 * @param array  $settings Settings.
	 * @param string $key Control key.
	 * @param float  $default Default.
	 */
	private function get_slider_size( $settings, $key, $default ) {
		if ( ! isset( $settings[ $key ] ) || ! is_array( $settings[ $key ] ) ) {
			return (float) $default;
		}
		if ( ! isset( $settings[ $key ]['size'] ) || ! is_numeric( $settings[ $key ]['size'] ) ) {
			return (float) $default;
		}
		return (float) $settings[ $key ]['size'];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_type',
			[
				'label' => esc_html__( 'Type', 'blackwidgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'widget_type',
			[
				'label'   => esc_html__( 'Select Type', 'blackwidgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'scroll_heat',
				'options' => [
					'scroll_heat'   => esc_html__( 'Scroll Heat', 'blackwidgets' ),
					'animated_text' => esc_html__( 'Animated Text', 'blackwidgets' ),
					'line_reveal'   => esc_html__( 'Line Reveal', 'blackwidgets' ),
				],
			]
		);

		$this->add_control(
			'gsap_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => '<div class="elementor-panel-alert elementor-panel-alert-info">'
					. esc_html__( 'Needs GSAP, ScrollTrigger, and SplitText URLs in Black Widgets → Settings.', 'blackwidgets' )
					. '</div>',
			]
		);

		$this->end_controls_section();

		$this->register_shared_content_controls();
		$this->register_line_reveal_content_controls();
		$this->register_heat_animation_controls();
		$this->register_animated_animation_controls();
		$this->register_line_reveal_animation_controls();
		$this->register_heat_style_controls();
		$this->register_animated_style_controls();
		$this->register_line_reveal_style_controls();
	}

	private function register_shared_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'blackwidgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'text',
			[
				'label'       => esc_html__( 'Text', 'blackwidgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => esc_html__( 'Type your text here. Words change color as you scroll.', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Enter text…', 'blackwidgets' ),
				'description' => esc_html__( 'Allowed HTML: <br>, <strong>, <b>, <hr>. No attributes.', 'blackwidgets' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
				'condition'   => [
					'widget_type!' => 'line_reveal',
				],
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'blackwidgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'p',
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'p'    => 'P',
					'div'  => 'Div',
					'span' => 'Span',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'blackwidgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justify', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .mws-ew-hw'          => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .mws-ew-hw__text'    => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .mws-ew-at'          => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .mws-ew-lr__content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_line_reveal_content_controls() {
		$this->start_controls_section(
			'section_lr_content',
			[
				'label'     => esc_html__( 'Line Reveal', 'blackwidgets' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'widget_type' => 'line_reveal' ],
			]
		);

		$this->add_control(
			'lr_text',
			[
				'label'       => esc_html__( 'Paragraph', 'blackwidgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Enter text…', 'blackwidgets' ),
				'description' => esc_html__( 'Allowed HTML: <br>, <strong>, <b>, <hr>. No attributes.', 'blackwidgets' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->add_control(
			'lr_show_index',
			[
				'label'        => esc_html__( 'Show Index', 'blackwidgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'blackwidgets' ),
				'label_off'    => esc_html__( 'No', 'blackwidgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'lr_index',
			[
				'label'     => esc_html__( 'Index', 'blackwidgets' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '01',
				'dynamic'   => [ 'active' => true ],
				'condition' => [ 'lr_show_index' => 'yes' ],
			]
		);

		$this->add_control(
			'lr_show_eyebrow',
			[
				'label'        => esc_html__( 'Show Eyebrow', 'blackwidgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'blackwidgets' ),
				'label_off'    => esc_html__( 'No', 'blackwidgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'lr_eyebrow',
			[
				'label'     => esc_html__( 'Eyebrow', 'blackwidgets' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( '( Lorem ipsum )', 'blackwidgets' ),
				'dynamic'   => [ 'active' => true ],
				'condition' => [ 'lr_show_eyebrow' => 'yes' ],
			]
		);

		$this->end_controls_section();
	}

	private function register_heat_animation_controls() {
		$this->start_controls_section(
			'section_heat_animation',
			[
				'label'     => esc_html__( 'Animation', 'blackwidgets' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'widget_type' => 'scroll_heat' ],
			]
		);

		$this->add_control(
			'heat_scroll_start',
			[
				'label'   => esc_html__( 'Scroll Start', 'blackwidgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top 78%',
				'options' => [
					'top 90%' => esc_html__( 'Early (Top 90%)', 'blackwidgets' ),
					'top 85%' => esc_html__( 'Top 85%', 'blackwidgets' ),
					'top 78%' => esc_html__( 'Default (Top 78%)', 'blackwidgets' ),
					'top 70%' => esc_html__( 'Top 70%', 'blackwidgets' ),
					'top 60%' => esc_html__( 'Top 60%', 'blackwidgets' ),
					'top 50%' => esc_html__( 'Center (Top 50%)', 'blackwidgets' ),
				],
			]
		);

		$this->add_control(
			'heat_scroll_end',
			[
				'label'   => esc_html__( 'Scroll End', 'blackwidgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom 32%',
				'options' => [
					'bottom 20%' => esc_html__( 'Early (Bottom 20%)', 'blackwidgets' ),
					'bottom 32%' => esc_html__( 'Default (Bottom 32%)', 'blackwidgets' ),
					'bottom 40%' => esc_html__( 'Bottom 40%', 'blackwidgets' ),
					'bottom 50%' => esc_html__( 'Center (Bottom 50%)', 'blackwidgets' ),
					'bottom 60%' => esc_html__( 'Late (Bottom 60%)', 'blackwidgets' ),
					'bottom top' => esc_html__( 'Until Fully Out', 'blackwidgets' ),
				],
			]
		);

		$this->add_responsive_control(
			'heat_scrub_amount',
			[
				'label'       => esc_html__( 'Scrub Smoothness', 'blackwidgets' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 2,
						'step' => 0.05,
					],
				],
				'default'     => [ 'size' => 0.35 ],
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-hw' => '--mws-ew-hw-scrub: {{SIZE}};',
				],
				'description' => esc_html__( 'Higher values feel softer. 0 follows the scroll exactly.', 'blackwidgets' ),
			]
		);

		$this->add_responsive_control(
			'heat_stagger',
			[
				'label'       => esc_html__( 'Word Stagger', 'blackwidgets' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => 0.01,
						'max'  => 0.2,
						'step' => 0.01,
					],
				],
				'default'     => [ 'size' => 0.06 ],
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-hw' => '--mws-ew-hw-stagger: {{SIZE}};',
				],
				'description' => esc_html__( 'Delay between each word.', 'blackwidgets' ),
			]
		);

		$this->end_controls_section();
	}

	private function register_animated_animation_controls() {
		$this->start_controls_section(
			'section_at_animation',
			[
				'label'     => esc_html__( 'Animation', 'blackwidgets' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'widget_type' => 'animated_text' ],
			]
		);

		$this->add_control(
			'anim_type',
			[
				'label'   => esc_html__( 'Animation Type', 'blackwidgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'from-bottom',
				'options' => [
					'from-bottom' => esc_html__( 'From Bottom', 'blackwidgets' ),
					'from-top'    => esc_html__( 'From Top', 'blackwidgets' ),
					'from-left'   => esc_html__( 'From Left', 'blackwidgets' ),
					'from-right'  => esc_html__( 'From Right', 'blackwidgets' ),
					'fade'        => esc_html__( 'Fade In', 'blackwidgets' ),
					'blur'        => esc_html__( 'Blur Focus', 'blackwidgets' ),
					'flip3d'      => esc_html__( '3D Flip', 'blackwidgets' ),
					'mask'        => esc_html__( 'Mask Reveal + Gradient', 'blackwidgets' ),
				],
			]
		);

		$this->add_control(
			'split_type',
			[
				'label'       => esc_html__( 'Split Type', 'blackwidgets' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => [
					'auto'  => esc_html__( 'Auto (Recommended)', 'blackwidgets' ),
					'chars' => esc_html__( 'Characters', 'blackwidgets' ),
					'words' => esc_html__( 'Words', 'blackwidgets' ),
					'lines' => esc_html__( 'Lines', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Auto picks the best split for the selected animation.', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'trigger_mode',
			[
				'label'       => esc_html__( 'Trigger', 'blackwidgets' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'scroll',
				'separator'   => 'before',
				'options'     => [
					'scroll' => esc_html__( 'On Scroll', 'blackwidgets' ),
					'inview' => esc_html__( 'When In View (Footer / Page End)', 'blackwidgets' ),
					'load'   => esc_html__( 'On Page Load', 'blackwidgets' ),
				],
			]
		);

		$this->add_control(
			'scroll_mode',
			[
				'label'     => esc_html__( 'Scroll Behavior', 'blackwidgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'once',
				'options'   => [
					'once'    => esc_html__( 'Play Once (No Repeat)', 'blackwidgets' ),
					'repeat'  => esc_html__( 'Replay Every Time In View', 'blackwidgets' ),
					'reverse' => esc_html__( 'Play & Reverse On Leave', 'blackwidgets' ),
					'scrub'   => esc_html__( 'Scrub With Scroll', 'blackwidgets' ),
				],
				'condition' => [
					'trigger_mode' => [ 'scroll', 'inview' ],
				],
			]
		);

		$this->add_control(
			'replay_on_back',
			[
				'label'        => esc_html__( 'Replay When Scrolling Back', 'blackwidgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'blackwidgets' ),
				'label_off'    => esc_html__( 'No', 'blackwidgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'trigger_mode' => [ 'scroll', 'inview' ],
					'scroll_mode'  => [ 'once', 'repeat' ],
				],
			]
		);

		$this->add_control(
			'at_scroll_start',
			[
				'label'     => esc_html__( 'Scroll Start', 'blackwidgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top 75%',
				'options'   => [
					'top bottom' => esc_html__( 'When Visible (Footer Safe)', 'blackwidgets' ),
					'top 90%'    => esc_html__( 'Early (Top 90%)', 'blackwidgets' ),
					'top 80%'    => esc_html__( 'Top 80%', 'blackwidgets' ),
					'top 75%'    => esc_html__( 'Default (Top 75%)', 'blackwidgets' ),
					'top 60%'    => esc_html__( 'Top 60%', 'blackwidgets' ),
					'top 50%'    => esc_html__( 'Center (Top 50%)', 'blackwidgets' ),
					'top 40%'    => esc_html__( 'Late (Top 40%)', 'blackwidgets' ),
				],
				'condition' => [
					'trigger_mode' => 'scroll',
				],
			]
		);

		$this->add_responsive_control(
			'at_scrub_amount',
			[
				'label'       => esc_html__( 'Scrub Smoothness', 'blackwidgets' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'default'     => [ 'size' => 1 ],
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-at' => '--mws-ew-at-scrub: {{SIZE}};',
				],
				'condition'   => [
					'trigger_mode' => [ 'scroll', 'inview' ],
					'scroll_mode'  => 'scrub',
				],
			]
		);

		$this->add_responsive_control(
			'at_duration',
			[
				'label'       => esc_html__( 'Duration (s)', 'blackwidgets' ),
				'type'        => Controls_Manager::SLIDER,
				'separator'   => 'before',
				'render_type' => 'template',
				'range'       => [
					'px' => [
						'min'  => 0.2,
						'max'  => 3,
						'step' => 0.05,
					],
				],
				'default'     => [ 'size' => 0.9 ],
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-at' => '--mws-ew-at-duration: {{SIZE}};',
				],
				'condition'   => [
					'scroll_mode!' => 'scrub',
				],
			]
		);

		$this->add_responsive_control(
			'at_stagger',
			[
				'label'       => esc_html__( 'Stagger', 'blackwidgets' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 0.2,
						'step' => 0.002,
					],
				],
				'default'     => [ 'size' => 0.028 ],
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-at' => '--mws-ew-at-stagger: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'at_delay',
			[
				'label'       => esc_html__( 'Delay (s)', 'blackwidgets' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 2,
						'step' => 0.05,
					],
				],
				'default'     => [ 'size' => 0 ],
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-at' => '--mws-ew-at-delay: {{SIZE}};',
				],
				'condition'   => [
					'scroll_mode!' => 'scrub',
				],
			]
		);

		$this->add_control(
			'ease',
			[
				'label'     => esc_html__( 'Easing', 'blackwidgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'auto',
				'options'   => [
					'auto'          => esc_html__( 'Auto (Per Animation)', 'blackwidgets' ),
					'none'          => esc_html__( 'Linear', 'blackwidgets' ),
					'power1.out'    => esc_html__( 'Soft', 'blackwidgets' ),
					'power2.out'    => esc_html__( 'Smooth', 'blackwidgets' ),
					'power3.out'    => esc_html__( 'Snappy', 'blackwidgets' ),
					'power4.out'    => esc_html__( 'Sharp', 'blackwidgets' ),
					'expo.out'      => esc_html__( 'Dramatic', 'blackwidgets' ),
					'sine.out'      => esc_html__( 'Gentle', 'blackwidgets' ),
					'back.out(1.7)' => esc_html__( 'Overshoot', 'blackwidgets' ),
					'circ.out'      => esc_html__( 'Circular', 'blackwidgets' ),
				],
				'condition' => [
					'scroll_mode!' => 'scrub',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_line_reveal_animation_controls() {
		$this->start_controls_section(
			'section_lr_animation',
			[
				'label'     => esc_html__( 'Animation', 'blackwidgets' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'widget_type' => 'line_reveal' ],
			]
		);

		$this->add_control(
			'lr_scroll_start',
			[
				'label'   => esc_html__( 'Scroll Start', 'blackwidgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top 75%',
				'options' => [
					'top bottom' => esc_html__( 'When Visible (Footer Safe)', 'blackwidgets' ),
					'top 90%'    => esc_html__( 'Early (Top 90%)', 'blackwidgets' ),
					'top 80%'    => esc_html__( 'Top 80%', 'blackwidgets' ),
					'top 75%'    => esc_html__( 'Default (Top 75%)', 'blackwidgets' ),
					'top 60%'    => esc_html__( 'Top 60%', 'blackwidgets' ),
					'top 50%'    => esc_html__( 'Center (Top 50%)', 'blackwidgets' ),
					'top 40%'    => esc_html__( 'Late (Top 40%)', 'blackwidgets' ),
				],
			]
		);

		$this->add_responsive_control(
			'lr_duration',
			[
				'label'       => esc_html__( 'Duration (s)', 'blackwidgets' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'range'       => [
					'px' => [
						'min'  => 0.2,
						'max'  => 2.5,
						'step' => 0.05,
					],
				],
				'default'     => [ 'size' => 1 ],
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-lr' => '--mws-ew-lr-duration: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'lr_stagger',
			[
				'label'       => esc_html__( 'Stagger', 'blackwidgets' ),
				'type'        => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 0.3,
						'step' => 0.01,
					],
				],
				'default'     => [ 'size' => 0.09 ],
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-lr' => '--mws-ew-lr-stagger: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'lr_replay',
			[
				'label'        => esc_html__( 'Replay on Every Scroll', 'blackwidgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'blackwidgets' ),
				'label_off'    => esc_html__( 'No', 'blackwidgets' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->end_controls_section();
	}

	private function register_heat_style_controls() {
		$this->start_controls_section(
			'section_heat_style',
			[
				'label'     => esc_html__( 'Text', 'blackwidgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'widget_type' => 'scroll_heat' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heat_typography',
				'selector' => '{{WRAPPER}} .mws-ew-hw__text',
			]
		);

		$this->add_control(
			'dim_color',
			[
				'label'       => esc_html__( 'Dim Color', 'blackwidgets' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#D7D8D4',
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-hw' => '--mws-ew-hw-dim: {{VALUE}};',
				],
				'description' => esc_html__( 'Word color before scroll highlights them.', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'lit_color',
			[
				'label'       => esc_html__( 'Lit Color', 'blackwidgets' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#16171B',
				'selectors'   => [
					'{{WRAPPER}} .mws-ew-hw' => '--mws-ew-hw-lit: {{VALUE}};',
				],
				'description' => esc_html__( 'Word color after scroll highlights them.', 'blackwidgets' ),
			]
		);

		$this->add_responsive_control(
			'heat_max_width',
			[
				'label'      => esc_html__( 'Max Width', 'blackwidgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1400,
					],
					'%'  => [
						'min' => 20,
						'max' => 100,
					],
					'vw' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 760,
				],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-hw__text' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'heat_padding',
			[
				'label'      => esc_html__( 'Padding', 'blackwidgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '40',
					'right'    => '0',
					'bottom'   => '40',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-hw' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'heat_text_shadow',
				'selector' => '{{WRAPPER}} .mws-ew-hw__text',
			]
		);

		$this->end_controls_section();
	}

	private function register_animated_style_controls() {
		$this->start_controls_section(
			'section_at_style',
			[
				'label'     => esc_html__( 'Text', 'blackwidgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'widget_type' => 'animated_text' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'at_typography',
				'selector' => '{{WRAPPER}} .mws-ew-at__title',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Color', 'blackwidgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1B1B18',
				'selectors' => [
					'{{WRAPPER}} .mws-ew-at' => '--mws-ew-at-color: {{VALUE}};',
				],
				'condition' => [
					'enable_gradient!' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_gradient',
			[
				'label'        => esc_html__( 'Gradient Text', 'blackwidgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'blackwidgets' ),
				'label_off'    => esc_html__( 'No', 'blackwidgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'gradient_from',
			[
				'label'     => esc_html__( 'Gradient From', 'blackwidgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1B1B18',
				'selectors' => [
					'{{WRAPPER}} .mws-ew-at' => '--mws-ew-at-grad-from: {{VALUE}};',
				],
				'condition' => [ 'enable_gradient' => 'yes' ],
			]
		);

		$this->add_control(
			'gradient_mid',
			[
				'label'     => esc_html__( 'Gradient Accent', 'blackwidgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4A5CFF',
				'selectors' => [
					'{{WRAPPER}} .mws-ew-at' => '--mws-ew-at-grad-mid: {{VALUE}};',
				],
				'condition' => [ 'enable_gradient' => 'yes' ],
			]
		);

		$this->add_control(
			'gradient_to',
			[
				'label'     => esc_html__( 'Gradient To', 'blackwidgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1B1B18',
				'selectors' => [
					'{{WRAPPER}} .mws-ew-at' => '--mws-ew-at-grad-to: {{VALUE}};',
				],
				'condition' => [ 'enable_gradient' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'at_max_width',
			[
				'label'      => esc_html__( 'Max Width', 'blackwidgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1400,
					],
					'%'  => [
						'min' => 20,
						'max' => 100,
					],
					'vw' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-at' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'at_padding',
			[
				'label'      => esc_html__( 'Padding', 'blackwidgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-at' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'      => 'at_text_shadow',
				'selector'  => '{{WRAPPER}} .mws-ew-at__title',
				'condition' => [
					'enable_gradient!' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	private function register_line_reveal_style_controls() {
		$this->start_controls_section(
			'section_lr_style',
			[
				'label'     => esc_html__( 'Line Reveal', 'blackwidgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'widget_type' => 'line_reveal' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lr_typography',
				'selector' => '{{WRAPPER}} .mws-ew-lr__title',
			]
		);

		$this->add_control(
			'lr_color',
			[
				'label'     => esc_html__( 'Color', 'blackwidgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1B1B18',
				'selectors' => [
					'{{WRAPPER}} .mws-ew-lr__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'lr_index_typography',
				'selector'  => '{{WRAPPER}} .mws-ew-lr__index',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lr_index_color',
			[
				'label'     => esc_html__( 'Index Color', 'blackwidgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#9A9A9A',
				'selectors' => [
					'{{WRAPPER}} .mws-ew-lr__index' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'lr_eyebrow_typography',
				'selector'  => '{{WRAPPER}} .mws-ew-lr__eyebrow',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lr_eyebrow_color',
			[
				'label'     => esc_html__( 'Eyebrow Color', 'blackwidgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#9A9A9A',
				'selectors' => [
					'{{WRAPPER}} .mws-ew-lr__eyebrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'lr_index_width',
			[
				'label'      => esc_html__( 'Index Width', 'blackwidgets' ),
				'type'       => Controls_Manager::SLIDER,
				'separator'  => 'before',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 20,
						'max' => 400,
					],
					'%'  => [
						'min' => 5,
						'max' => 50,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 120,
				],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-lr' => '--mws-ew-lr-index-w: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'lr_gap',
			[
				'label'      => esc_html__( 'Gap', 'blackwidgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 120,
					],
					'em' => [
						'min' => 0,
						'max' => 8,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 24,
				],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-lr' => '--mws-ew-lr-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'lr_eyebrow_width',
			[
				'label'      => esc_html__( 'Eyebrow Width', 'blackwidgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range'      => [
					'px' => [
						'min' => 40,
						'max' => 600,
					],
					'%'  => [
						'min' => 10,
						'max' => 80,
					],
					'em' => [
						'min' => 2,
						'max' => 40,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 280,
				],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-lr' => '--mws-ew-lr-eyebrow-w: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'lr_eyebrow_gap',
			[
				'label'      => esc_html__( 'Eyebrow Gap', 'blackwidgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
					'em' => [
						'min' => 0,
						'max' => 4,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-lr' => '--mws-ew-lr-eyebrow-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'lr_max_width',
			[
				'label'      => esc_html__( 'Max Width', 'blackwidgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vw' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1400,
					],
					'%'  => [
						'min' => 20,
						'max' => 100,
					],
					'vw' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-lr__title' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'lr_padding',
			[
				'label'      => esc_html__( 'Padding', 'blackwidgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mws-ew-lr' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$type     = isset( $settings['widget_type'] ) ? (string) $settings['widget_type'] : $this->get_widget_type();
		if ( ! in_array( $type, [ 'scroll_heat', 'animated_text', 'line_reveal' ], true ) ) {
			$type = $this->get_widget_type();
		}

		if ( 'line_reveal' === $type ) {
			$text = isset( $settings['text'] ) ? trim( (string) $settings['text'] ) : '';
			$this->render_line_reveal( $settings, $text );
			return;
		}

		$text = isset( $settings['text'] ) ? trim( (string) $settings['text'] ) : '';
		if ( '' === $text ) {
			return;
		}

		if ( 'animated_text' === $type ) {
			$this->render_animated_text( $settings, $text );
			return;
		}

		$this->render_scroll_heat( $settings, $text );
	}

	/**
	 * @param array  $settings Settings.
	 * @param string $text Text.
	 */
	private function render_scroll_heat( $settings, $text ) {
		$tag          = $this->get_allowed_tag( $settings['html_tag'] ?? 'p', 'p' );
		$scroll_start = ! empty( $settings['heat_scroll_start'] ) ? (string) $settings['heat_scroll_start'] : 'top 78%';
		$scroll_end   = ! empty( $settings['heat_scroll_end'] ) ? (string) $settings['heat_scroll_end'] : 'bottom 32%';

		$allowed_starts = [ 'top 90%', 'top 85%', 'top 78%', 'top 70%', 'top 60%', 'top 50%' ];
		if ( ! in_array( $scroll_start, $allowed_starts, true ) ) {
			$scroll_start = 'top 78%';
		}

		$allowed_ends = [ 'bottom 20%', 'bottom 32%', 'bottom 40%', 'bottom 50%', 'bottom 60%', 'bottom top' ];
		if ( ! in_array( $scroll_end, $allowed_ends, true ) ) {
			$scroll_end = 'bottom 32%';
		}

		$this->add_render_attribute(
			'heat_root',
			[
				'class'               => 'mws-ew-hw',
				'data-mws-ew-hw'      => '1',
				'data-bw-scroll-heat' => '1',
				'data-start'          => $scroll_start,
				'data-end'            => $scroll_end,
				'data-scrub'          => (string) $this->get_slider_size( $settings, 'heat_scrub_amount', 0.35 ),
				'data-stagger'        => (string) $this->get_slider_size( $settings, 'heat_stagger', 0.06 ),
			]
		);

		$this->add_render_attribute(
			'heat_text',
			[
				'class' => 'mws-ew-hw__text',
			]
		);
		?>
		<div <?php $this->print_render_attribute_string( 'heat_root' ); ?>>
			<<?php echo esc_html( $tag ); ?> <?php $this->print_render_attribute_string( 'heat_text' ); ?>>
				<?php echo $this->escape_typography_text( $text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</<?php echo esc_html( $tag ); ?>>
		</div>
		<?php
	}

	/**
	 * @param array  $settings Settings.
	 * @param string $text Text.
	 */
	private function render_animated_text( $settings, $text ) {
		$tag            = $this->get_allowed_tag( $settings['html_tag'] ?? 'h2', 'h2' );
		$anim_type      = ! empty( $settings['anim_type'] ) ? (string) $settings['anim_type'] : 'from-bottom';
		$split_type     = ! empty( $settings['split_type'] ) ? (string) $settings['split_type'] : 'auto';
		$trigger_mode   = ! empty( $settings['trigger_mode'] ) ? (string) $settings['trigger_mode'] : 'scroll';
		$scroll_mode    = ! empty( $settings['scroll_mode'] ) ? (string) $settings['scroll_mode'] : 'once';
		$replay_on_back = ( ! empty( $settings['replay_on_back'] ) && 'yes' === $settings['replay_on_back'] ) ? 'yes' : '';
		$scroll_start   = ! empty( $settings['at_scroll_start'] ) ? (string) $settings['at_scroll_start'] : 'top 75%';
		$enable_grad    = ( ! empty( $settings['enable_gradient'] ) && 'yes' === $settings['enable_gradient'] );

		$allowed_anims = [ 'from-bottom', 'from-top', 'from-left', 'from-right', 'fade', 'blur', 'flip3d', 'mask' ];
		if ( ! in_array( $anim_type, $allowed_anims, true ) ) {
			$anim_type = 'from-bottom';
		}

		$allowed_splits = [ 'auto', 'chars', 'words', 'lines' ];
		if ( ! in_array( $split_type, $allowed_splits, true ) ) {
			$split_type = 'auto';
		}

		$allowed_triggers = [ 'scroll', 'inview', 'load' ];
		if ( ! in_array( $trigger_mode, $allowed_triggers, true ) ) {
			$trigger_mode = 'scroll';
		}

		$allowed_scroll = [ 'once', 'repeat', 'reverse', 'scrub' ];
		if ( ! in_array( $scroll_mode, $allowed_scroll, true ) ) {
			$scroll_mode = 'once';
		}

		$allowed_starts = [ 'top bottom', 'top 90%', 'top 80%', 'top 75%', 'top 60%', 'top 50%', 'top 40%' ];
		if ( ! in_array( $scroll_start, $allowed_starts, true ) ) {
			$scroll_start = 'top 75%';
		}

		if ( 'inview' === $trigger_mode ) {
			$scroll_start = 'top bottom';
		}

		if ( 'mask' === $anim_type ) {
			$enable_grad = true;
		}

		$ease = ! empty( $settings['ease'] ) ? (string) $settings['ease'] : 'auto';

		$classes = [ 'mws-ew-at' ];
		if ( $enable_grad ) {
			$classes[] = 'mws-ew-at--gradient';
		}
		if ( 'flip3d' === $anim_type ) {
			$classes[] = 'mws-ew-at--flip3d';
		}

		$title_classes = [ 'mws-ew-at__title' ];
		if ( 'span' === $tag ) {
			$title_classes[] = 'mws-ew-at__title--inline';
		}

		$this->add_render_attribute(
			'at_root',
			[
				'class'                 => $classes,
				'data-mws-ew-at'        => '1',
				'data-bw-animated-text' => '1',
				'data-anim'             => $anim_type,
				'data-split'            => $split_type,
				'data-trigger'          => $trigger_mode,
				'data-scroll-mode'      => $scroll_mode,
				'data-replay-back'      => $replay_on_back,
				'data-start'            => $scroll_start,
				'data-ease'             => $ease,
				'data-duration'         => (string) $this->get_slider_size( $settings, 'at_duration', 0.9 ),
				'data-stagger'          => (string) $this->get_slider_size( $settings, 'at_stagger', 0.028 ),
				'data-delay'            => (string) $this->get_slider_size( $settings, 'at_delay', 0 ),
				'data-scrub'            => (string) $this->get_slider_size( $settings, 'at_scrub_amount', 1 ),
			]
		);

		$this->add_render_attribute(
			'at_title',
			[
				'class' => $title_classes,
			]
		);
		?>
		<div <?php $this->print_render_attribute_string( 'at_root' ); ?>>
			<<?php echo esc_html( $tag ); ?> <?php $this->print_render_attribute_string( 'at_title' ); ?>>
				<?php echo $this->escape_typography_text( $text ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</<?php echo esc_html( $tag ); ?>>
		</div>
		<?php
	}

	/**
	 * @param array  $settings Settings.
	 * @param string $text Shared text fallback.
	 */
	private function render_line_reveal( $settings, $text ) {
		$body = isset( $settings['lr_text'] ) ? trim( (string) $settings['lr_text'] ) : '';
		if ( '' === $body ) {
			$body = $text;
		}
		if ( '' === $body ) {
			$body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
		}

		$tag          = $this->get_allowed_tag( $settings['html_tag'] ?? 'p', 'p' );
		$scroll_start = ! empty( $settings['lr_scroll_start'] ) ? (string) $settings['lr_scroll_start'] : 'top 75%';
		$replay       = ( ! empty( $settings['lr_replay'] ) && 'yes' === $settings['lr_replay'] ) ? 'yes' : '';

		$allowed_starts = [ 'top bottom', 'top 90%', 'top 80%', 'top 75%', 'top 60%', 'top 50%', 'top 40%' ];
		if ( ! in_array( $scroll_start, $allowed_starts, true ) ) {
			$scroll_start = 'top 75%';
		}

		$show_index   = ( ! empty( $settings['lr_show_index'] ) && 'yes' === $settings['lr_show_index'] );
		$show_eyebrow = ( ! empty( $settings['lr_show_eyebrow'] ) && 'yes' === $settings['lr_show_eyebrow'] );
		$index        = isset( $settings['lr_index'] ) ? (string) $settings['lr_index'] : '';
		$eyebrow      = isset( $settings['lr_eyebrow'] ) ? (string) $settings['lr_eyebrow'] : '';

		$this->add_render_attribute(
			'lr_root',
			[
				'class'               => 'mws-ew-lr',
				'data-mws-ew-lr'      => '1',
				'data-bw-line-reveal' => '1',
				'data-start'          => $scroll_start,
				'data-duration'       => (string) $this->get_slider_size( $settings, 'lr_duration', 1 ),
				'data-stagger'        => (string) $this->get_slider_size( $settings, 'lr_stagger', 0.09 ),
				'data-replay'         => $replay,
			]
		);

		$this->add_render_attribute(
			'lr_title',
			[
				'class' => 'mws-ew-lr__title',
			]
		);
		?>
		<div <?php $this->print_render_attribute_string( 'lr_root' ); ?>>
			<div class="mws-ew-lr__inner">
				<?php if ( $show_index && '' !== trim( $index ) ) : ?>
					<div class="mws-ew-lr__index"><?php echo esc_html( $index ); ?></div>
				<?php endif; ?>
				<div class="mws-ew-lr__content">
					<?php if ( $show_eyebrow && '' !== trim( $eyebrow ) ) : ?>
						<div class="mws-ew-lr__eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
					<?php endif; ?>
					<<?php echo esc_html( $tag ); ?> <?php $this->print_render_attribute_string( 'lr_title' ); ?>>
						<?php echo $this->escape_typography_text( $body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</<?php echo esc_html( $tag ); ?>>
				</div>
			</div>
		</div>
		<?php
	}
}
