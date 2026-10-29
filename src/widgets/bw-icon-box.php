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
use Elementor\Group_Control_Image_Size;

/**
 * Icon box widget.
 *
 * Box with an icon, SVG or image, a title, a subtitle, a paragraph and a link.
 *
 * @since 1.0.0
 */
class IconBox extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        if ( ! wp_style_is( 'black-widgets-icon-box', 'registered' ) ) {
            wp_register_style( 'black-widgets-icon-box', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/icon-box.css', [], $this->icon_box_asset_ver( 'assets/css/icon-box.css' ) );
        }
        $this->register_icon_box_script();
    }

	/**
	 * Get widget name.
	 *
	 * Retrieve the icon box widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'b_icon_box';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve the icon box widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black Icon Box Pro', 'blackwidgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve the icon box widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-icon-box';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the icon box widget belongs to.
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
        return [ 'black-widgets-icon-box' ];
    }

    public function get_script_depends() {
        $this->register_icon_box_script();
        return [ 'black-widgets-icon-box' ];
    }

    /**
     * Raw settings safe when Elementor data is not initialized yet.
     *
     * @return array
     */
    protected function get_early_settings(): array {
        return black_widgets_elementor_raw_settings( $this );
    }

    /**
     * Whether GSAP core + ScrollTrigger are ready in Settings.
     *
     * @return bool
     */
    public function is_gsap_enabled() {
        return \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_ready();
    }

    /**
     * Cache-bust CSS/JS when the file changes (same plugin version).
     *
     * @param string $rel Path relative to the plugin root.
     * @return string
     */
    private function icon_box_asset_ver( $rel ) {
        $ver  = BLACK_WIDGETS_VERSION;
        $path = BLACK_WIDGETS_PLUGIN_PATH . ltrim( $rel, '/\\' );
        if ( is_readable( $path ) ) {
            $ver .= '.' . (int) filemtime( $path );
        }
        return $ver;
    }

    /**
     * Register the widget script once. GSAP is a dep whenever Settings CDN is ready
     * so the editor can enable Animate without a full reload.
     */
    private function register_icon_box_script() {
        if ( wp_script_is( 'black-widgets-icon-box', 'registered' ) ) {
            return;
        }

        $deps = [ 'jquery' ];
        if ( $this->is_gsap_enabled() ) {
            \Modernaweb\BlackWidgets\Plugin_Options::register_gsap_scripts();
            if ( wp_script_is( 'GSAP', 'registered' ) ) {
                $deps[] = 'GSAP';
            }
            if ( wp_script_is( 'GSAP-ScrollTrigger', 'registered' ) ) {
                $deps[] = 'GSAP-ScrollTrigger';
            }
        }

        wp_register_script(
            'black-widgets-icon-box',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/icon-box.js',
            $deps,
            $this->icon_box_asset_ver( 'assets/js/icon-box.js' ),
            true
        );
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

	/**
	 * Register the icon box widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'position_section',
			[
				'label' => esc_html__( 'Positions', 'blackwidgets' ),
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
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-icon-box-pro/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'blackwidgets' ),
					esc_html__( 'Demo', 'blackwidgets' )
				),
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_position',
			[
				'label' => esc_html__( 'Icon Box Position', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'position-5',
				'options' => [
					'position-1' 	=> esc_html__( 'Position 1', 'blackwidgets' ),
					'position-2' 	=> esc_html__( 'Position 2', 'blackwidgets' ),
					'position-3' 	=> esc_html__( 'Position 3', 'blackwidgets' ),
					'position-4' 	=> esc_html__( 'Position 4', 'blackwidgets' ),
					'position-5' 	=> esc_html__( 'Position 5', 'blackwidgets' ),
					'position-6' 	=> esc_html__( 'Position 6', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Select position of the Icon Box.', 'blackwidgets' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Enable Title Section
		$this->add_control(
			'widget_title_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Title?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'title_enable',
				'default' 		=> 'title_enable',
			]
		);

		// Title
		$this->add_control(
			'widget_title',
			[
				'label' => esc_html__( 'Title', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'This is Title', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'description' => esc_html__( 'Plain text only. HTML is escaped.', 'blackwidgets' ),
				'condition'  => [
					'widget_title_enable' => [
						'title_enable',
					],
				],
			]
		);

		// HTML Tag
		$this->add_control(
			'widget_html_tag_title',
			[
				'label' => esc_html__( 'HTML Tag', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => [
					'div' => esc_html__( 'div', 'blackwidgets' ),
					'h1' => esc_html__( 'H1', 'blackwidgets' ),
					'h2' => esc_html__( 'H2', 'blackwidgets' ),
					'h3' => esc_html__( 'H3', 'blackwidgets' ),
					'h4' => esc_html__( 'H4', 'blackwidgets' ),
					'h5' => esc_html__( 'H5', 'blackwidgets' ),
					'h6' => esc_html__( 'H6', 'blackwidgets' ),
					'p' => esc_html__( 'p', 'blackwidgets' ),
					'span' => esc_html__( 'span', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'blackwidgets' ),
				'condition'  => [
					'widget_title_enable' => [
						'title_enable',
					],
				],
				'separator' => 'after',
			]
		);

		// Enable Subtitle Section
		$this->add_control(
			'widget_subtitle_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Subtitle?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'subtitle_enable',
				'default' 		=> 'off',
			]
		);

		// Subtitle
		$this->add_control(
			'widget_subtitle',
			[
				'label' => esc_html__( 'Subtitle', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'This is Subtitle', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your subtitle here', 'blackwidgets' ),
				'description' => esc_html__( 'Plain text only. HTML is escaped.', 'blackwidgets' ),
				'condition'  => [
					'widget_subtitle_enable' => [
						'subtitle_enable',
					],
				],
			]
		);

		// HTML Tag
		$this->add_control(
			'widget_html_tag_subtitle',
			[
				'label' => esc_html__( 'HTML Tag', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => [
					'div' => esc_html__( 'div', 'blackwidgets' ),
					'h1' => esc_html__( 'H1', 'blackwidgets' ),
					'h2' => esc_html__( 'H2', 'blackwidgets' ),
					'h3' => esc_html__( 'H3', 'blackwidgets' ),
					'h4' => esc_html__( 'H4', 'blackwidgets' ),
					'h5' => esc_html__( 'H5', 'blackwidgets' ),
					'h6' => esc_html__( 'H6', 'blackwidgets' ),
					'p' => esc_html__( 'p', 'blackwidgets' ),
					'span' => esc_html__( 'span', 'blackwidgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'blackwidgets' ),
				'condition'  => [
					'widget_subtitle_enable' => [
						'subtitle_enable',
					],
				],
				'separator' => 'after',
			]
		);

		// Enable Paragraph Section
		$this->add_control(
			'widget_paragraph_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Paragraph?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'paragraph_enable',
				'default' 		=> 'off',
			]
		);

		$this->add_control(
			'widget_paragraph',
			[
				'label' => esc_html__( 'Paragraph', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 4,
				'default' => esc_html__( 'This is paragraph', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your paragraph here', 'blackwidgets' ),
				'condition'  => [
					'widget_paragraph_enable' => [
						'paragraph_enable',
					],
				],
				'separator' => 'after',
			]
		);

		// Enable Link Section
		$this->add_control(
			'widget_link_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Link?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'link_enable',
				'default' 		=> 'off',
			]
		);

		$this->add_control(
			'widget_link_text',
			[
				'label' => esc_html__( 'Link Text', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Read more', 'blackwidgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'blackwidgets' ),
				'condition'  => [
					'widget_link_enable' => [
						'link_enable',
					],
				],
			]
		);

		$this->add_control(
			'widget_link_url',
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
				'condition'  => [
					'widget_link_enable' => [
						'link_enable',
					],
				],
				'separator' => 'after',
			]
        );

		// Enable Icon/Image/SVG Section
		$this->add_control(
			'widget_icon_image_enable',
			[
				'label' 		=> esc_html__( 'Do You Need Icon or Image?', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'icon_image_enable',
				'default' 		=> 'icon_image_enable',
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_icon_image_type',
			[
				'label' => esc_html__( 'How exactly does it work?', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'enable_icon',
				'options' => [
					'enable_icon' 			=> esc_html__( 'With ICON', 'blackwidgets' ),
					'enable_image'			=> esc_html__( 'With IMAGE or SVG', 'blackwidgets' ),
					'enable_code'			=> esc_html__( 'With SVG Code', 'blackwidgets' ),
				],
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
				],
			]
		);

		// Subtitle
		$this->add_control(
			'widget_code',
			[
				'label' => esc_html__( 'CODE', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 6,
				'default' => '',
				'placeholder' => esc_html__( 'Paste SVG markup here', 'blackwidgets' ),
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_code',
					],
				],
			]
		);

		$this->add_control(
			'widget_icon',
			[
				'label' => esc_html__( 'Choose Icon', 'blackwidgets' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'eicon eicon-nerd',
					'library' => 'elementor',
				],
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_icon',
					],
				],
			]
		);

		$this->add_control(
			'widget_image',
			[
				'label' => esc_html__( 'Choose Image', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_image',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'exclude' => [ 'custom' ],
				'include' => [],
				'default' => 'full',
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_image',
					],
				],
			]
        );

		// Enable Paragraph Section
		$this->add_control(
			'widget_svg_animate',
			[
				'label' 		=> esc_html__( 'SVG Animate!', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
				'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
				'return_value' 	=> 'bw-iconbox-svg-animate',
				'default' 		=> 'off',
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_code',
						'enable_image',
					],
				],
			]
		);

		$this->end_controls_section();

		if ( $this->is_gsap_enabled() ) {
			$this->start_controls_section(
				'animate_section',
				[
					'label' => esc_html__( 'Animate', 'blackwidgets' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
					'condition'  => [
						'widget_icon_image_enable' => [
							'icon_image_enable',
						],
					],
				]
			);

			$this->add_control(
				'widget_animate_enable',
				[
					'label' 		=> esc_html__( 'Enable Animate', 'blackwidgets' ),
					'type' 			=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
					'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'description'	=> esc_html__( 'Needs GSAP and ScrollTrigger in Black Widgets Settings.', 'blackwidgets' ),
				]
			);

			$this->add_control(
				'widget_animate_type',
				[
					'label' => esc_html__( 'Animation Type', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'fade_up',
					'options' => [
						'fade'    => esc_html__( 'Fade', 'blackwidgets' ),
						'fade_up' => esc_html__( 'Fade Up', 'blackwidgets' ),
						'scale'   => esc_html__( 'Scale', 'blackwidgets' ),
						'rotate'  => esc_html__( 'Rotate', 'blackwidgets' ),
						'bounce'  => esc_html__( 'Bounce', 'blackwidgets' ),
					],
					'condition' => [
						'widget_animate_enable' => 'yes',
						'widget_icon_image_type' => 'enable_icon',
					],
				]
			);

			$this->add_control(
				'widget_animate_type_svg',
				[
					'label' => esc_html__( 'Animation Type', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'draw',
					'options' => [
						'draw'    => esc_html__( 'Draw (SVG)', 'blackwidgets' ),
						'fade'    => esc_html__( 'Fade', 'blackwidgets' ),
						'fade_up' => esc_html__( 'Fade Up', 'blackwidgets' ),
						'scale'   => esc_html__( 'Scale', 'blackwidgets' ),
						'rotate'  => esc_html__( 'Rotate', 'blackwidgets' ),
						'bounce'  => esc_html__( 'Bounce', 'blackwidgets' ),
					],
					'description' => esc_html__( 'Draw traces SVG paths. Raster images fall back to Fade Up.', 'blackwidgets' ),
					'condition' => [
						'widget_animate_enable' => 'yes',
						'widget_icon_image_type' => [ 'enable_code', 'enable_image' ],
					],
				]
			);

			$this->add_control(
				'widget_animate_trigger',
				[
					'label' => esc_html__( 'Trigger', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'scroll_once',
					'options' => [
						'on_load'      => esc_html__( 'On Load', 'blackwidgets' ),
						'scroll_once'  => esc_html__( 'On Scroll Once', 'blackwidgets' ),
						'scroll_every' => esc_html__( 'On Every Scroll', 'blackwidgets' ),
					],
					'condition' => [
						'widget_animate_enable' => 'yes',
					],
				]
			);

			$this->add_control(
				'widget_animate_hover',
				[
					'label' => esc_html__( 'Hover', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'none'   => esc_html__( 'None', 'blackwidgets' ),
						'scale'  => esc_html__( 'Scale', 'blackwidgets' ),
						'rotate' => esc_html__( 'Rotate', 'blackwidgets' ),
					],
					'condition' => [
						'widget_animate_enable' => 'yes',
						'widget_icon_image_type' => 'enable_icon',
					],
				]
			);

			$this->add_control(
				'widget_animate_hover_svg',
				[
					'label' => esc_html__( 'Hover', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'none'   => esc_html__( 'None', 'blackwidgets' ),
						'scale'  => esc_html__( 'Scale', 'blackwidgets' ),
						'rotate' => esc_html__( 'Rotate', 'blackwidgets' ),
						'draw'   => esc_html__( 'Draw', 'blackwidgets' ),
					],
					'condition' => [
						'widget_animate_enable' => 'yes',
						'widget_icon_image_type' => [ 'enable_code', 'enable_image' ],
					],
				]
			);

			$this->add_control(
				'widget_animate_loop',
				[
					'label' => esc_html__( 'Loop (in view)', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [
						'none'  => esc_html__( 'None', 'blackwidgets' ),
						'float' => esc_html__( 'Float', 'blackwidgets' ),
						'pulse' => esc_html__( 'Pulse', 'blackwidgets' ),
						'spin'  => esc_html__( 'Spin', 'blackwidgets' ),
					],
					'condition' => [
						'widget_animate_enable' => 'yes',
					],
				]
			);

			$this->add_control(
				'widget_animate_duration',
				[
					'label' => esc_html__( 'Duration (ms)', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 100,
					'max' => 6000,
					'step' => 50,
					'default' => 800,
					'condition' => [
						'widget_animate_enable' => 'yes',
					],
				]
			);

			$this->add_control(
				'widget_animate_delay',
				[
					'label' => esc_html__( 'Delay (ms)', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 4000,
					'step' => 50,
					'default' => 0,
					'condition' => [
						'widget_animate_enable' => 'yes',
					],
				]
			);

			$this->add_control(
				'widget_animate_ease',
				[
					'label' => esc_html__( 'Easing', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'power2.out',
					'options' => [
						'power2.out'  => esc_html__( 'Soft', 'blackwidgets' ),
						'power2.inOut'=> esc_html__( 'Smooth', 'blackwidgets' ),
						'power3.out'  => esc_html__( 'Snappy', 'blackwidgets' ),
						'back.out(1.4)' => esc_html__( 'Overshoot', 'blackwidgets' ),
						'none'        => esc_html__( 'Linear', 'blackwidgets' ),
					],
					'condition' => [
						'widget_animate_enable' => 'yes',
					],
				]
			);

			$this->end_controls_section();
		}

		// Box Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Box Style', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Alignment
		$this->add_responsive_control(
			'box_text_alignment',
			[
				'label'     => esc_html__( 'Box Alignment', 'blackwidgets' ),
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
				'toggle'    => true,
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox' => 'text-align: {{VALUE}};',
				],
				'condition'  => [
					'widget_position' => [
						'position-1',
						'position-2',
					],
				],
			]
		);

		// Alignment
		$this->add_responsive_control(
			'box_b_text_alignment',
			[
				'label'     => esc_html__( 'Box Alignment', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'blackwidgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'    => true,
				'default'   => 'left',
				// No selectors: Elementor must re-render so .bw-box-left|.bw-box-right
				// classes update (Pos 3/4 padding/border mirror + Pos 5/6 flex reverse).
				'condition'  => [
					'widget_position' => [
						'position-3',
						'position-4',
						'position-5',
						'position-6',
					],
				],
			]
		);

		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-iconbox',
			]
		);

		$this->add_control(
			'widget_normal_style_blur',
			[
				'label' => esc_html__( 'Blur', 'blackwidgets' ),
				'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
				],
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'widget_box_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
			]
		);

		$this->add_control(
			'widget_hover_style_blur',
			[
				'label' => esc_html__( 'Blur', 'blackwidgets' ),
				'description' => esc_html__( 'Background (only for color) with low opacity is required.', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}) !important;',
				],
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'widget_hover_box_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'widget_hover_box_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_hover_box_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'icon_shape_section',
			[
				'label' => esc_html__( 'Icon Shape', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
				],
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label' => esc_html__( 'Shape', 'blackwidgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto'    => esc_html__( 'Auto', 'blackwidgets' ),
					'square'  => esc_html__( 'Square', 'blackwidgets' ),
					'circle'  => esc_html__( 'Circle', 'blackwidgets' ),
					'rounded' => esc_html__( 'Rounded', 'blackwidgets' ),
				],
			]
		);

		$this->add_responsive_control(
			'icon_shape_size',
			[
				'label' => esc_html__( 'Box Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 24,
						'max' => 240,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 72,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox' => '--bw-icon-shape-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_shape!' => 'auto',
				],
			]
		);

		$this->end_controls_section();

		// Title Style section
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__( 'Title Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_title_enable' => [
						'title_enable',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_title_tab');
		$this->start_controls_tab(
			'title_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_icon_box_title_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_icon_box_title_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-it-is-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'title_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_title_typographys',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_title_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_title_text_alignment',
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
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_title__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_title__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_title__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		$this->add_control(
			'content_title__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_title__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-title',
			]
		);

		$this->end_controls_section();

		// Subtitle Style section
		$this->start_controls_section(
			'subtitle_style_section',
			[
				'label' => esc_html__( 'Subtitle Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_subtitle_enable' => [
						'subtitle_enable',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_subtitle_tab');
		$this->start_controls_tab(
			'subtitle_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_icon_box_subtitle_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'subtitle_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_icon_box_subtitle_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-it-is-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'subtitle_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_subtitle_typographys',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_subtitle_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_subtitle_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_subtitle_text_alignment',
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
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_subtitle__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_subtitle__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_subtitle__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		$this->add_control(
			'content_subtitle__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_subtitle__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-subtitle',
			]
		);

		$this->end_controls_section();

		// Paragraph Style section
		$this->start_controls_section(
			'paragraph_style_section',
			[
				'label' => esc_html__( 'Paragraph Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_paragraph_enable' => [
						'paragraph_enable',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_paragraph_tab');
		$this->start_controls_tab(
			'paragraph_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_icon_box_paragraph_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'paragraph_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_icon_box_paragraph_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-it-is-paragraph' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'paragraph_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_paragraph_typographys',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_paragraph_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_paragraph_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_paragraph_text_alignment',
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
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_paragraph__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_paragraph__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_paragraph__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		$this->add_control(
			'content_paragraph__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_paragraph__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-it-is-paragraph',
			]
		);

		$this->end_controls_section();

		// Link Style section
		$this->start_controls_section(
			'link_style_section',
			[
				'label' => esc_html__( 'Link Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_link_enable' => [
						'link_enable',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_link_tab');
		$this->start_controls_tab(
			'link_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_icon_box_link_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'link_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Box hover uses the Title/Subtitle colors; link hover is set separately.
		$this->add_control(
			'widget_icon_box_link_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .bw-iconbox:hover .bw-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'link_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_link_typographys',
				'label' => esc_html__( 'Typography', 'blackwidgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'content_link_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_link_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		// Alignment
		$this->add_responsive_control(
			'content_link_text_alignment',
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
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'text-align: {{VALUE}}; display: block; width: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'content_link__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_link__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_link__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		$this->add_control(
			'content_link__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_link__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-btn',
			]
		);

		$this->end_controls_section();

		// Icon Style section
		$this->start_controls_section(
			'icon_style_section',
			[
				'label' => esc_html__( 'Icon Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_icon',
						'enable_code',
						'enable_image',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_icon_tab');
		$this->start_controls_tab(
			'icon_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		$this->add_control(
			'widget_icon_box_icon_normal_color',
			[
				'label' => esc_html__( 'Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'widget_svg_fill_icon_box_icon_normal_color',
			[
				'label' => esc_html__( 'SVG(fill) Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg path, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg circle, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg rect, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg polygon' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img svg path, {{WRAPPER}} .bw-iconbox .bw-iconbox-img svg circle, {{WRAPPER}} .bw-iconbox .bw-iconbox-img svg rect, {{WRAPPER}} .bw-iconbox .bw-iconbox-img svg polygon' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'widget_svg_stroke_icon_box_icon_normal_color',
			[
				'label' => esc_html__( 'SVG(stroke) Normal Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg path, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg circle, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg rect, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg polygon, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg line, {{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg polyline' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img svg' => 'stroke: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img svg path, {{WRAPPER}} .bw-iconbox .bw-iconbox-img svg circle, {{WRAPPER}} .bw-iconbox .bw-iconbox-img svg rect, {{WRAPPER}} .bw-iconbox .bw-iconbox-img svg polygon, {{WRAPPER}} .bw-iconbox .bw-iconbox-img svg line, {{WRAPPER}} .bw-iconbox .bw-iconbox-img svg polyline' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_icon_normal_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon, {{WRAPPER}} .bw-iconbox .bw-iconbox-img',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'icon_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		// Color - hover the box (not only the icon node)
		$this->add_control(
			'widget_icon_box_icon_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'widget_svg_fill_icon_box_icon_hover_color',
			[
				'label' => esc_html__( 'SVG(fill) Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg path, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg circle, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg rect, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg polygon' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg path, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg circle, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg rect, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg polygon' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'widget_svg_stroke_icon_box_icon_hover_color',
			[
				'label' => esc_html__( 'SVG(stroke) Hover Color', 'blackwidgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg path, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg circle, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg rect, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg polygon, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg line, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon svg polyline' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg' => 'stroke: {{VALUE}}; color: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg path, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg circle, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg rect, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg polygon, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg line, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img svg polyline' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_icon_hover_background',
				'label' => esc_html__( 'Hover Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-icon, {{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'icon_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'content_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'widget_icon_image_type' => [
						'enable_icon',
					],
				],
			]
		);

		$this->add_responsive_control(
			'content_svg_icon_size',
			[
				'label' => esc_html__( 'SVG Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img svg' => 'width: {{SIZE}}{{UNIT}} !important; height: auto !important; max-width: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'the_icon_alignment',
			[
				'label'     => esc_html__( 'Icon Position', 'blackwidgets' ),
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
				'toggle'    => true,
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox-media-row' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'the_icon_v_alignment',
			[
				'label'     => esc_html__( 'Icon Vertical Align', 'blackwidgets' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'top'   => [
						'title' => esc_html__( 'Top', 'blackwidgets' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'blackwidgets' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom'  => [
						'title' => esc_html__( 'Bottom', 'blackwidgets' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'toggle'    => true,
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox.bw-position-3' => 'align-items: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox.bw-position-4' => 'align-items: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox.bw-position-5 .bw-wrapper' => 'align-items: {{VALUE}};',
					'{{WRAPPER}} .bw-iconbox.bw-position-6 .bw-wrapper' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'widget_position' => [
						'position-3',
						'position-4',
						'position-5',
						'position-6',
					],
				],
			]
		);

		$this->add_responsive_control(
			'content_icon__margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon, {{WRAPPER}} .bw-iconbox .bw-iconbox-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_icon__padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon, {{WRAPPER}} .bw-iconbox .bw-iconbox-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_icon__border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon, {{WRAPPER}} .bw-iconbox .bw-iconbox-img',
			]
		);

		$this->add_control(
			'content_icon__border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-icon, {{WRAPPER}} .bw-iconbox .bw-iconbox-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_icon__box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-icon, {{WRAPPER}} .bw-iconbox .bw-iconbox-img',
			]
		);

		$this->end_controls_section();

		// Image Style section
		$this->start_controls_section(
			'image_style_section',
			[
				'label' => esc_html__( 'Image Styles', 'blackwidgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_icon_image_enable' => [
						'icon_image_enable',
					],
					'widget_icon_image_type' => [
						'enable_image',
					],
				],
			]
		);

		$this->start_controls_tabs('inner_image_tab');
		$this->start_controls_tab(
			'image_tab_1',
			[
				'label' => esc_html__( 'Normal', 'blackwidgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_image_normal_background',
				'label' => esc_html__( 'Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img img',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'image_tab_2',
			[
				'label' => esc_html__( 'Hover', 'blackwidgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_image_hover_background',
				'label' => esc_html__( 'Hover Background', 'blackwidgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-iconbox:hover .bw-iconbox-img img',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'image_hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'content_image_size',
			[
				'label' => esc_html__( 'Image Size', 'blackwidgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img img' => 'width: {{SIZE}}{{UNIT}} !important; height: auto;',
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img.bw-iconbox-svg-file svg' => 'width: {{SIZE}}{{UNIT}} !important; height: auto; max-width: 100%;',
				],
			]
		);

		// // Alignment
		// $this->add_responsive_control(
		// 	'content_image_text_alignment',
		// 	[
		// 		'label'     => esc_html__( 'Image Alignment', 'blackwidgets' ),
		// 		'type'      => \Elementor\Controls_Manager::CHOOSE,
		// 		'options'   => [
		// 			'left'   => [
		// 				'title' => esc_html__( 'Left', 'blackwidgets' ),
		// 				'icon'  => 'fas fa-long-arrow-alt-left',
		// 			],
		// 			'center' => [
		// 				'title' => esc_html__( 'Center', 'blackwidgets' ),
		// 				'icon'  => 'fas fa-compress-arrows-alt',
		// 			],
		// 			'right'  => [
		// 				'title' => esc_html__( 'Right', 'blackwidgets' ),
		// 				'icon'  => 'fas fa-long-arrow-alt-right',
		// 			],
		// 		],
		// 		'toggle'    => true,
		// 	]
		// );

		$this->add_responsive_control(
			'content_image_margin',
			[
				'label' => esc_html__( 'Margin', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img img, {{WRAPPER}} .bw-iconbox .bw-iconbox-img.bw-iconbox-svg-file svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_image_padding',
			[
				'label' => esc_html__( 'Padding', 'blackwidgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img img, {{WRAPPER}} .bw-iconbox .bw-iconbox-img.bw-iconbox-svg-file svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'content_image_border',
				'label' => esc_html__( 'Border', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img img, {{WRAPPER}} .bw-iconbox .bw-iconbox-img.bw-iconbox-svg-file svg',
			]
		);

		$this->add_control(
			'content_image_border_radius', //param_name
			[
				'label' 		=> esc_html__( 'Border Radius', 'blackwidgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-iconbox .bw-iconbox-img img, {{WRAPPER}} .bw-iconbox .bw-iconbox-img.bw-iconbox-svg-file svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_image_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'blackwidgets' ),
				'selector' => '{{WRAPPER}} .bw-iconbox .bw-iconbox-img img, {{WRAPPER}} .bw-iconbox .bw-iconbox-img.bw-iconbox-svg-file svg',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render the icon box widget output on the frontend.
	 *
	 * Outputs the widget HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$enable_title     = isset( $settings['widget_title_enable'] ) && 'title_enable' === $settings['widget_title_enable'];
		$allowed_tags     = [ 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span' ];
		$title_tag        = isset( $settings['widget_html_tag_title'] ) ? $settings['widget_html_tag_title'] : 'h3';
		if ( ! in_array( $title_tag, $allowed_tags, true ) ) {
			$title_tag = 'h3';
		}
		$title            = isset( $settings['widget_title'] ) ? $settings['widget_title'] : '';
		$enable_subtitle  = isset( $settings['widget_subtitle_enable'] ) && 'subtitle_enable' === $settings['widget_subtitle_enable'];
		$subtitle_tag     = isset( $settings['widget_html_tag_subtitle'] ) ? $settings['widget_html_tag_subtitle'] : 'h4';
		if ( ! in_array( $subtitle_tag, $allowed_tags, true ) ) {
			$subtitle_tag = 'h4';
		}
		$subtitle         = isset( $settings['widget_subtitle'] ) ? $settings['widget_subtitle'] : '';
		$enable_paragraph = isset( $settings['widget_paragraph_enable'] ) && 'paragraph_enable' === $settings['widget_paragraph_enable'];
		$paragraph        = isset( $settings['widget_paragraph'] ) ? $settings['widget_paragraph'] : '';
		$enable_icon      = isset( $settings['widget_icon_image_enable'] ) && 'icon_image_enable' === $settings['widget_icon_image_enable'];
		$enable_link      = isset( $settings['widget_link_enable'] ) && 'link_enable' === $settings['widget_link_enable'];
		$link             = isset( $settings['widget_link_url']['url'] ) ? $settings['widget_link_url']['url'] : '';
		$link_text        = isset( $settings['widget_link_text'] ) ? $settings['widget_link_text'] : '';
		$link_target      = ! empty( $settings['widget_link_url']['is_external'] ) ? ' target="_blank"' : '';
		$link_nofollow    = ! empty( $settings['widget_link_url']['nofollow'] ) ? ' rel="nofollow"' : '';
		$position         = isset( $settings['widget_position'] ) ? $settings['widget_position'] : 'position-5';

		$title_align     = $this->choose_string( $settings, 'content_title_text_alignment' );
		$subtitle_align  = $this->choose_string( $settings, 'content_subtitle_text_alignment' );
		$paragraph_align = $this->choose_string( $settings, 'content_paragraph_text_alignment' );
		$link_align      = $this->choose_string( $settings, 'content_link_text_alignment' );
		$is_stacked      = in_array( $position, [ 'position-3', 'position-4', 'position-5', 'position-6' ], true );
		$icon_h_class    = $this->icon_horizontal_classes( $settings );
		$box_side        = $this->box_side_classes( $settings, $position );
		$shape           = $this->choose_string( $settings, 'icon_shape' );
		if ( ! in_array( $shape, [ 'auto', 'square', 'circle', 'rounded' ], true ) ) {
			$shape = 'auto';
		}
		$svg_animate = ( isset( $settings['widget_svg_animate'] ) && 'bw-iconbox-svg-animate' === $settings['widget_svg_animate'] ) ? 'bw-iconbox-svg-animate' : '';
		$data_id     = 'bw_ibox_' . $this->get_id();

		$root_classes = [
			'bw-iconbox',
			'bw-' . $position,
			'bw-icon-shape-' . $shape,
			$box_side,
			$svg_animate,
		];
		if ( $is_stacked ) {
			$root_classes[] = $this->icon_vertical_classes( $settings );
			$root_classes[] = $this->icon_column_h_classes( $settings );
		}

		$anim_attrs = $this->animate_data_attributes( $settings );

		echo '<div id="' . esc_attr( $data_id ) . '" class="' . esc_attr( trim( preg_replace( '/\s+/', ' ', implode( ' ', $root_classes ) ) ) ) . '"' . $anim_attrs . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attrs escaped in helper

		switch ( $position ) {
			case 'position-2':
				$this->render_heading( $enable_title, $title_tag, $title, $title_align, 'bw-it-is-title' );
				$this->render_heading( $enable_subtitle, $subtitle_tag, $subtitle, $subtitle_align, 'bw-it-is-subtitle' );
				$this->render_media( $settings, $enable_icon, $icon_h_class );
				$this->render_paragraph( $enable_paragraph, $paragraph, $paragraph_align );
				$this->render_link( $enable_link, $link, $link_text, $link_target, $link_nofollow, $link_align );
				break;

			case 'position-3':
			case 'position-4':
				if ( $enable_icon ) {
					echo '<div class="bw-image-wrap">';
					$this->render_media( $settings, true, $icon_h_class );
					echo '</div>';
				}
				echo '<div class="bw-content-wrap">';
				$this->render_heading( $enable_title, $title_tag, $title, $title_align, 'bw-it-is-title' );
				$this->render_heading( $enable_subtitle, $subtitle_tag, $subtitle, $subtitle_align, 'bw-it-is-subtitle' );
				$this->render_paragraph( $enable_paragraph, $paragraph, $paragraph_align );
				$this->render_link( $enable_link, $link, $link_text, $link_target, $link_nofollow, $link_align );
				echo '</div>';
				break;

			case 'position-5':
			case 'position-6':
				echo '<div class="bw-wrapper">';
				$this->render_media( $settings, $enable_icon, $icon_h_class );
				echo '<div class="bw-typography-wrapper">';
				echo '<div class="bw-icon-box-title">';
				$this->render_heading( $enable_title, $title_tag, $title, $title_align, 'bw-it-is-title' );
				$this->render_heading( $enable_subtitle, $subtitle_tag, $subtitle, $subtitle_align, 'bw-it-is-subtitle' );
				echo '</div>';
				$this->render_paragraph( $enable_paragraph, $paragraph, $paragraph_align );
				$this->render_link( $enable_link, $link, $link_text, $link_target, $link_nofollow, $link_align );
				echo '</div>';
				echo '</div>';
				break;

			default:
				$this->render_media( $settings, $enable_icon, $icon_h_class );
				$this->render_heading( $enable_title, $title_tag, $title, $title_align, 'bw-it-is-title' );
				$this->render_heading( $enable_subtitle, $subtitle_tag, $subtitle, $subtitle_align, 'bw-it-is-subtitle' );
				$this->render_paragraph( $enable_paragraph, $paragraph, $paragraph_align );
				$this->render_link( $enable_link, $link, $link_text, $link_target, $link_nofollow, $link_align );
				break;
		}

		echo '</div>';
	}

	/**
	 * @param array  $settings Settings.
	 * @param string $key      Control ID.
	 * @return string
	 */
	private function choose_string( $settings, $key ) {
		return ( isset( $settings[ $key ] ) && is_string( $settings[ $key ] ) ) ? $settings[ $key ] : '';
	}

	/**
	 * Pos 1/2 icon wrap classes including tablet/mobile.
	 *
	 * @param array $settings Settings.
	 * @return string
	 */
	private function icon_horizontal_classes( $settings ) {
		$classes = [];
		$desktop = $this->choose_string( $settings, 'the_icon_alignment' );
		if ( $desktop === '' ) {
			$desktop = $this->choose_string( $settings, 'the_icon_h_alignment' );
		}
		$tablet  = $this->choose_string( $settings, 'the_icon_alignment_tablet' );
		if ( $tablet === '' ) {
			$tablet = $this->choose_string( $settings, 'the_icon_h_alignment_tablet' );
		}
		$mobile  = $this->choose_string( $settings, 'the_icon_alignment_mobile' );
		if ( $mobile === '' ) {
			$mobile = $this->choose_string( $settings, 'the_icon_h_alignment_mobile' );
		}
		if ( $desktop ) {
			$classes[] = $desktop;
		}
		if ( $tablet ) {
			$classes[] = 'bw-align-tablet-' . $tablet;
		}
		if ( $mobile ) {
			$classes[] = 'bw-align-mobile-' . $mobile;
		}
		return trim( implode( ' ', $classes ) );
	}

	/**
	 * Map leftover left/center/right icon align to vertical values.
	 *
	 * @param string $value Choose value.
	 * @return string
	 */
	private function map_legacy_valign( $value ) {
		$map = [
			'left'   => 'top',
			'center' => 'middle',
			'right'  => 'bottom',
			'top'    => 'top',
			'middle' => 'middle',
			'bottom' => 'bottom',
		];
		return isset( $map[ $value ] ) ? $map[ $value ] : '';
	}

	/**
	 * Pos 3-6 vertical align classes.
	 *
	 * @param array $settings Settings.
	 * @return string
	 */
	private function icon_vertical_classes( $settings ) {
		$desktop = $this->choose_string( $settings, 'the_icon_v_alignment' );
		if ( ! $desktop ) {
			$desktop = $this->map_legacy_valign( $this->choose_string( $settings, 'the_icon_alignment' ) );
		}
		$tablet = $this->choose_string( $settings, 'the_icon_v_alignment_tablet' );
		if ( ! $tablet ) {
			$tablet = $this->map_legacy_valign( $this->choose_string( $settings, 'the_icon_alignment_tablet' ) );
		}
		$mobile = $this->choose_string( $settings, 'the_icon_v_alignment_mobile' );
		if ( ! $mobile ) {
			$mobile = $this->map_legacy_valign( $this->choose_string( $settings, 'the_icon_alignment_mobile' ) );
		}

		$classes = [];
		$legacy  = [
			'top'    => 'left',
			'middle' => 'center',
			'bottom' => 'right',
		];
		if ( $desktop ) {
			$classes[] = 'bw-icon-valign-' . $desktop;
			if ( isset( $legacy[ $desktop ] ) ) {
				$classes[] = 'bw-icon-align-' . $legacy[ $desktop ];
			}
		}
		if ( $tablet ) {
			$classes[] = 'bw-icon-valign-tablet-' . $tablet;
		}
		if ( $mobile ) {
			$classes[] = 'bw-icon-valign-mobile-' . $mobile;
		}
		return trim( implode( ' ', $classes ) );
	}

	/**
	 * Pos 3-6 horizontal align of the icon column.
	 *
	 * @param array $settings Settings.
	 * @return string
	 */
	private function icon_column_h_classes( $settings ) {
		$classes = [];
		$desktop = $this->choose_string( $settings, 'the_icon_h_alignment' );
		$tablet  = $this->choose_string( $settings, 'the_icon_h_alignment_tablet' );
		$mobile  = $this->choose_string( $settings, 'the_icon_h_alignment_mobile' );
		if ( $desktop ) {
			$classes[] = 'bw-icon-halign-' . $desktop;
		}
		if ( $tablet ) {
			$classes[] = 'bw-icon-halign-tablet-' . $tablet;
		}
		if ( $mobile ) {
			$classes[] = 'bw-icon-halign-mobile-' . $mobile;
		}
		return trim( implode( ' ', $classes ) );
	}

	/**
	 * Box left/right (and tablet/mobile) classes.
	 *
	 * @param array  $settings Settings.
	 * @param string $position Position key.
	 * @return string
	 */
	private function box_side_classes( $settings, $position ) {
		$is_stack = in_array( $position, [ 'position-3', 'position-4', 'position-5', 'position-6' ], true );
		$key      = $is_stack ? 'box_b_text_alignment' : 'box_text_alignment';
		$desktop  = $this->choose_string( $settings, $key );
		if ( $desktop === '' ) {
			$desktop = 'left';
		}
		$classes = [ 'bw-box-' . $desktop ];
		if ( $is_stack ) {
			$tablet = $this->choose_string( $settings, $key . '_tablet' );
			$mobile = $this->choose_string( $settings, $key . '_mobile' );
			if ( $tablet ) {
				$classes[] = 'bw-box-tablet-' . $tablet;
			}
			if ( $mobile ) {
				$classes[] = 'bw-box-mobile-' . $mobile;
			}
		}
		return implode( ' ', $classes );
	}

	/**
	 * GSAP data attributes when Settings CDN is ready and Animate is on.
	 *
	 * @param array $settings Settings.
	 * @return string
	 */
	private function animate_data_attributes( $settings ) {
		if ( ! $this->is_gsap_enabled() ) {
			return '';
		}
		if ( empty( $settings['widget_animate_enable'] ) || $settings['widget_animate_enable'] !== 'yes' ) {
			return '';
		}

		$media    = $this->media_kind( $settings );
		$type     = $this->resolve_animate_type( $settings, $media );
		$trigger  = isset( $settings['widget_animate_trigger'] ) ? $settings['widget_animate_trigger'] : 'scroll_once';
		$hover    = $this->resolve_animate_hover( $settings, $media );
		$loop     = isset( $settings['widget_animate_loop'] ) ? $settings['widget_animate_loop'] : 'none';
		$duration = isset( $settings['widget_animate_duration'] ) ? (int) $settings['widget_animate_duration'] : 800;
		$delay    = isset( $settings['widget_animate_delay'] ) ? (int) $settings['widget_animate_delay'] : 0;
		$ease     = isset( $settings['widget_animate_ease'] ) ? $settings['widget_animate_ease'] : 'power2.out';

		$allowed_trigger = [ 'on_load', 'scroll_once', 'scroll_every' ];
		$allowed_loop    = [ 'none', 'float', 'pulse', 'spin' ];
		if ( ! in_array( $trigger, $allowed_trigger, true ) ) {
			$trigger = 'scroll_once';
		}
		if ( ! in_array( $loop, $allowed_loop, true ) ) {
			$loop = 'none';
		}

		return sprintf(
			' data-bw-media="%s" data-bw-anim="%s" data-bw-anim-trigger="%s" data-bw-anim-hover="%s" data-bw-anim-loop="%s" data-bw-anim-duration="%d" data-bw-anim-delay="%d" data-bw-anim-ease="%s"',
			esc_attr( $media ),
			esc_attr( $type ),
			esc_attr( $trigger ),
			esc_attr( $hover ),
			esc_attr( $loop ),
			max( 100, $duration ),
			max( 0, $delay ),
			esc_attr( $ease )
		);
	}

	/**
	 * Returns the media type (icon, svg or image) that decides which Animate options apply.
	 *
	 * @param array $settings Settings.
	 * @return string
	 */
	private function media_kind( $settings ) {
		$type = isset( $settings['widget_icon_image_type'] ) ? $settings['widget_icon_image_type'] : 'enable_icon';
		if ( $type === 'enable_code' ) {
			return 'svg';
		}
		if ( $type === 'enable_image' ) {
			$attachment_id = isset( $settings['widget_image']['id'] ) ? (int) $settings['widget_image']['id'] : 0;
			if ( $attachment_id && black_widgets_get_inline_svg_from_attachment( $attachment_id ) !== '' ) {
				return 'svg';
			}
			return 'image';
		}
		return 'icon';
	}

	/**
	 * @param array  $settings Settings.
	 * @param string $media    Media kind.
	 * @return string
	 */
	private function resolve_animate_type( $settings, $media ) {
		$allowed = [ 'fade', 'fade_up', 'scale', 'rotate', 'bounce' ];
		if ( $media === 'svg' ) {
			$allowed[] = 'draw';
		}

		$type = '';
		if ( $media === 'svg' && ! empty( $settings['widget_animate_type_svg'] ) ) {
			$type = $settings['widget_animate_type_svg'];
		}
		if ( $type === '' && ! empty( $settings['widget_animate_type'] ) ) {
			$type = $settings['widget_animate_type'];
		}
		if ( ! in_array( $type, $allowed, true ) ) {
			return $media === 'svg' ? 'draw' : 'fade_up';
		}
		return $type;
	}

	/**
	 * @param array  $settings Settings.
	 * @param string $media    Media kind.
	 * @return string
	 */
	private function resolve_animate_hover( $settings, $media ) {
		$allowed = [ 'none', 'scale', 'rotate' ];
		if ( $media === 'svg' ) {
			$allowed[] = 'draw';
		}

		$hover = '';
		if ( $media === 'svg' && ! empty( $settings['widget_animate_hover_svg'] ) ) {
			$hover = $settings['widget_animate_hover_svg'];
		}
		if ( $hover === '' && ! empty( $settings['widget_animate_hover'] ) ) {
			$hover = $settings['widget_animate_hover'];
		}
		if ( ! in_array( $hover, $allowed, true ) ) {
			return 'none';
		}
		return $hover;
	}

	/**
	 * @param bool   $enable Whether to print.
	 * @param string $tag    Allowed tag.
	 * @param string $text   Text.
	 * @param string $align  Align class.
	 * @param string $class  Base class.
	 */
	private function render_heading( $enable, $tag, $text, $align, $class ) {
		if ( ! $enable ) {
			return;
		}
		echo '<' . $tag . ' class="' . esc_attr( trim( $class . ' ' . $align ) ) . '">' . esc_html( $text ) . '</' . $tag . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- tag allowlisted
	}

	/**
	 * @param bool   $enable Whether to print.
	 * @param string $text   Text.
	 * @param string $align  Align class.
	 */
	private function render_paragraph( $enable, $text, $align ) {
		if ( ! $enable ) {
			return;
		}
		echo '<p class="bw-it-is-paragraph ' . esc_attr( $align ) . '">' . esc_html( $text ) . '</p>';
	}

	/**
	 * @param bool   $enable   Whether to print.
	 * @param string $url      URL.
	 * @param string $text     Label.
	 * @param string $target   Escaped attribute snippet.
	 * @param string $nofollow Escaped attribute snippet.
	 * @param string $align    Align class.
	 */
	private function render_link( $enable, $url, $text, $target, $nofollow, $align ) {
		if ( ! $enable ) {
			return;
		}
		echo '<a href="' . esc_url( $url ) . '"' . $target . $nofollow . ' class="bw-btn ' . esc_attr( $align ) . '">' . esc_html( $text ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- target/nofollow built from booleans
	}

	/**
	 * Icon / image / SVG media block.
	 *
	 * @param array  $settings Settings.
	 * @param bool   $enable   Whether media is on.
	 * @param string $align    Extra wrap classes.
	 */
	private function render_media( $settings, $enable, $align ) {
		if ( ! $enable ) {
			return;
		}

		$type        = isset( $settings['widget_icon_image_type'] ) ? $settings['widget_icon_image_type'] : '';
		$align_class = $align ? ' ' . $align : '';

		echo '<div class="bw-iconbox-media-row' . esc_attr( $align_class ) . '">';

		if ( $type === 'enable_icon' ) {
			echo '<div class="bw-iconbox-icon">';
			\Elementor\Icons_Manager::render_icon( isset( $settings['widget_icon'] ) ? $settings['widget_icon'] : [], [ 'aria-hidden' => 'true' ] );
			echo '</div>';
			echo '</div>';
			return;
		}

		if ( $type === 'enable_code' ) {
			$svg = black_widgets_sanitize_svg_markup( isset( $settings['widget_code'] ) ? $settings['widget_code'] : '' );
			echo '<div class="bw-iconbox-img">' . $svg . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized SVG
			echo '</div>';
			return;
		}

		$attachment_id = isset( $settings['widget_image']['id'] ) ? (int) $settings['widget_image']['id'] : 0;
		$inline        = $attachment_id ? black_widgets_get_inline_svg_from_attachment( $attachment_id ) : '';
		if ( $inline !== '' ) {
			echo '<div class="bw-iconbox-img bw-iconbox-svg-file">' . $inline . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized SVG
			echo '</div>';
			return;
		}

		$image_url = '';
		if ( $attachment_id ) {
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment_id, 'thumbnail', $settings );
		}
		if ( ! $image_url && ! empty( $settings['widget_image']['url'] ) ) {
			$image_url = $settings['widget_image']['url'];
		}
		if ( ! $image_url ) {
			echo '</div>';
			return;
		}

		echo '<div class="bw-iconbox-img">';
		echo '<img src="' . esc_url( $image_url ) . '" class="bw-iconbox-image" alt="">'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
		echo '</div>';
		echo '</div>';
	}
}

class_alias('Modernaweb\BlackWidgets\Widgets\IconBox', 'Black_Widgets\BLACK_WIDGETS_Icon_Box');