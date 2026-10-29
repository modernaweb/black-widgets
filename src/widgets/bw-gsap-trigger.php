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
use Elementor\Group_Control_Css_Filter;
use enshrined\svgSanitize\Sanitizer;

/**
 * GSAP ScrollTrigger widget.
 *
 * Scroll-triggered GSAP animation for HTML or image content.
 *
 * @since 1.0.0
 */
class GSAPTrigger extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-gsap-trigger', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/gsap-trigger.css', [], BLACK_WIDGETS_VERSION );
        // Localization data is not available at this stage, so this widget outputs its JS inline.
    }

    /**
     * Get widget name.
     *
     * Retrieve the GSAP trigger widget name.
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
     * Retrieve the GSAP trigger widget title.
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
     * Retrieve the GSAP trigger widget icon.
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
     * Retrieve the list of categories the GSAP trigger widget belongs to.
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
        return [ 'black-widgets-gsap-trigger' ];
    }

    public function get_script_depends() {
        if ( ! $this->is_gsap_enabled() ) {
            return [];
        }

        return [ 'GSAP', 'GSAP-ScrollTrigger' ];
    }

    /**
     * Whether GSAP is enabled in plugin options with both CDN URLs set.
     *
     * @return bool
     */
    public function is_gsap_enabled() {
        return \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_ready();
    }

    /**
     * Scope selectors when widget switch is on, or admin force-flag is enabled.
     * Default remains legacy (global) so existing pages do not change.
     *
     * @param array $settings Widget settings.
     * @return bool
     */
    protected function should_scope_selectors( $settings ) {
        $admin_force = \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_trigger_scoped();
        $widget_on   = is_array( $settings ) && isset( $settings['scope_selectors'] ) && $settings['scope_selectors'] === 'yes';

        return $admin_force || $widget_on;
    }

    /**
     * Build a CSS selector for GSAP from/to targets.
     *
     * @param string $data_id Widget element id (without #).
     * @param string $class   Class name without leading dot.
     * @param bool   $scoped  Whether to prefix with #data_id.
     * @return string
     */
    protected function build_class_selector( $data_id, $class, $scoped ) {
        $class = ltrim( trim( (string) $class ), '.' );
        if ( $class === '' ) {
            return $scoped ? ( '#' . $data_id ) : '';
        }
        if ( $scoped ) {
            return '#' . $data_id . ' .' . $class;
        }
        return '.' . $class;
    }

    /**
     * First quoted ScrollTrigger trigger selector in a free-form JS fragment.
     *
     * @param string $fragment Object body such as trigger: ".foo", start: "...".
     * @return string Selector or empty.
     */
    protected function extract_scrolltrigger_selector( $fragment ) {
        $fragment = (string) $fragment;
        if ( $fragment === '' ) {
            return '';
        }
        if ( preg_match( '/(?:^|[\s,{])trigger\s*:\s*([\'"])([^\'"\n]+)\1/', $fragment, $m ) ) {
            return trim( $m[2] );
        }
        return '';
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

    /**
     * Register the GSAP trigger widget controls.
     *
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
            'scope_selectors',
            [
                'label' => esc_html__( 'Limit animation to this widget', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'blackwidgets' ),
                'label_off' => esc_html__( 'No', 'blackwidgets' ),
                'return_value' => 'yes',
                'default' => '',
                'description' => esc_html__( 'Off: use global class selectors (same as before). On: limit targets to this widget.', 'blackwidgets' ),
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
                'name' => 'thumbnail',
                'include' => [],
                'default' => 'full',
                'condition'  => [
                    'widget_type' => [
                        'image',
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_alt',
            [
                'label' => esc_html__( 'Alt Text', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Describe the image', 'blackwidgets' ),
                'description' => esc_html__( 'Leave empty to use the attachment alt text.', 'blackwidgets' ),
                'label_block' => true,
                'condition'  => [
                    'widget_type' => [
                        'image',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

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
                'description' => esc_html__( 'Class on the image wrapper. Keep it unique on the page unless "Limit animation to this widget" is on.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'image_js_scroll_heading',
            [
                'label' => esc_html__( 'Scroll position', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_start_point',
            [
                'label' => esc_html__( 'Start Point', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'center bottom', 'blackwidgets' ),
                'placeholder' => esc_html__( 'top bottom', 'blackwidgets' ),
                'description' => esc_html__( 'When the animation starts. Two words: element position, then viewport position. Example: center bottom.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'image_end_point',
            [
                'label' => esc_html__( 'End Point', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'center top', 'blackwidgets' ),
                'placeholder' => esc_html__( 'top bottom', 'blackwidgets' ),
                'description' => esc_html__( 'When the animation finishes. Example: center top.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'image_toggle_actions',
            [
                'label' => esc_html__( 'Toggle Actions', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'none none none none', 'blackwidgets' ),
                'placeholder' => esc_html__( 'none none none none', 'blackwidgets' ),
                'description' => esc_html__( 'Four words for on enter, leave, enter-back, leave-back. Common values: play, reverse, restart, reset, none.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'image_scrub',
            [
                'label' => esc_html__( 'Scrub', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( 'true', 'blackwidgets' ),
                'placeholder' => esc_html__( 'false', 'blackwidgets' ),
                'description' => esc_html__( 'true ties the animation to the scrollbar. A number (e.g. 1) does the same with a short delay. false plays once.', 'blackwidgets' ),
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
            'image_js_from_heading',
            [
                'label' => esc_html__( 'From (start look)', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'element_opacity_from',
            [
                'label' => esc_html__( 'Opacity', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '1', 'blackwidgets' ),
                'placeholder' => esc_html__( '1', 'blackwidgets' ),
                'description' => esc_html__( 'Starting visibility. 0 is hidden, 1 is fully visible.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'element_duration_from',
            [
                'label' => esc_html__( 'Duration', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '0.9', 'blackwidgets' ),
                'placeholder' => esc_html__( '0.4', 'blackwidgets' ),
                'description' => esc_html__( 'How long the start state lasts, in seconds.', 'blackwidgets' ),
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
                'description' => esc_html__( '3D perspective distance (transformPerspective). Higher values look flatter.', 'blackwidgets' ),
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
            'image_js_to_heading',
            [
                'label' => esc_html__( 'To (end look)', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'element_opacity_to',
            [
                'label' => esc_html__( 'Opacity', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '1', 'blackwidgets' ),
                'placeholder' => esc_html__( '1', 'blackwidgets' ),
                'description' => esc_html__( 'Ending visibility. 0 is hidden, 1 is fully visible.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'element_duration_to',
            [
                'label' => esc_html__( 'Duration', 'blackwidgets' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__( '0.5', 'blackwidgets' ),
                'placeholder' => esc_html__( '0.4', 'blackwidgets' ),
                'description' => esc_html__( 'How long it takes to reach the end look, in seconds.', 'blackwidgets' ),
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
                'description' => esc_html__( '3D perspective distance (transformPerspective). Higher values look flatter.', 'blackwidgets' ),
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

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__( 'Box Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'widget_box_background',
                'label' => esc_html__( 'Background', 'blackwidgets' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'widget_box_border',
                'label' => esc_html__( 'Border', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box',
            ]
        );

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

        $typo_selector = '{{WRAPPER}} .bw-gsap-code-box, {{WRAPPER}} .bw-gsap-code-box.bw-trigger-typo-wrapper, {{WRAPPER}} .bw-gsap-code-box.bw-trigger-typo-shared .bw-trigger-item, {{WRAPPER}} .bw-gsap-code-box.bw-trigger-typo-headings :is(h1,h2,h3,h4,h5,h6), {{WRAPPER}} .bw-gsap-code-box.bw-trigger-typo-all :is(h1,h2,h3,h4,h5,h6,p,span,a,li,blockquote)';
        $image_style_selector = '{{WRAPPER}} .bw-img-trigger-x, {{WRAPPER}} .bw-gsap-code-box.bw-trigger-style-html-imgs img';

        // Image style section
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => esc_html__( 'Image Style', 'blackwidgets' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'widget_type',
                            'operator' => '===',
                            'value' => 'image',
                        ],
                        [
                            'name' => 'style_html_images',
                            'operator' => '===',
                            'value' => 'yes',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'image_align',
            [
                'label' => esc_html__( 'Alignment', 'blackwidgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-gsap-img, {{WRAPPER}} .bw-gsap-code-box.bw-trigger-style-html-imgs' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__( 'Width', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    $image_style_selector => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_max_width',
            [
                'label' => esc_html__( 'Max Width', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    $image_style_selector => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__( 'Height', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    $image_style_selector => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_fit',
            [
                'label' => esc_html__( 'Object Fit', 'blackwidgets' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'blackwidgets' ),
                    'contain' => esc_html__( 'Contain', 'blackwidgets' ),
                    'cover' => esc_html__( 'Cover', 'blackwidgets' ),
                    'fill' => esc_html__( 'Fill', 'blackwidgets' ),
                    'none' => esc_html__( 'None', 'blackwidgets' ),
                    'scale-down' => esc_html__( 'Scale Down', 'blackwidgets' ),
                ],
                'selectors' => [
                    $image_style_selector => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_position',
            [
                'label' => esc_html__( 'Object Position', 'blackwidgets' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'blackwidgets' ),
                    'center center' => esc_html__( 'Center Center', 'blackwidgets' ),
                    'center left' => esc_html__( 'Center Left', 'blackwidgets' ),
                    'center right' => esc_html__( 'Center Right', 'blackwidgets' ),
                    'top center' => esc_html__( 'Top Center', 'blackwidgets' ),
                    'top left' => esc_html__( 'Top Left', 'blackwidgets' ),
                    'top right' => esc_html__( 'Top Right', 'blackwidgets' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'blackwidgets' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'blackwidgets' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'blackwidgets' ),
                ],
                'selectors' => [
                    $image_style_selector => 'object-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label' => esc_html__( 'Opacity', 'blackwidgets' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    $image_style_selector => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'image_css_filter',
                'selector' => $image_style_selector,
            ]
        );

        $this->add_control(
            'image_overflow',
            [
                'label' => esc_html__( 'Overflow', 'blackwidgets' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'blackwidgets' ),
                    'hidden' => esc_html__( 'Hidden', 'blackwidgets' ),
                    'visible' => esc_html__( 'Visible', 'blackwidgets' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bw-gsap-img' => 'overflow: {{VALUE}};',
                ],
                'condition' => [
                    'widget_type' => 'image',
                ],
            ]
        );

        $this->end_controls_section();

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

        $this->add_control(
            'style_apply_to',
            [
                'label' => esc_html__( 'Apply styles to', 'blackwidgets' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'shared',
                'options' => [
                    'shared' => esc_html__( 'Shared Class', 'blackwidgets' ),
                    'headings' => esc_html__( 'Headings', 'blackwidgets' ),
                    'all' => esc_html__( 'All text', 'blackwidgets' ),
                    'wrapper' => esc_html__( 'Wrapper', 'blackwidgets' ),
                ],
                'description' => esc_html__( 'Choose which HTML inside this widget receives typography. The box itself is still styled for backward compatibility.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'override_inline_styles',
            [
                'label' => esc_html__( 'Override inline styles', 'blackwidgets' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'blackwidgets' ),
                'label_off' => esc_html__( 'No', 'blackwidgets' ),
                'return_value' => 'yes',
                'default' => '',
                'description' => esc_html__( 'Remove font and color inline styles from the selected tags so these typography controls can apply.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'keep_demo_text_styles',
            [
                'label' => esc_html__( 'Use built-in demo text styles', 'blackwidgets' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'blackwidgets' ),
                'label_off' => esc_html__( 'No', 'blackwidgets' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => esc_html__( 'Keeps the default .bw-eg color, line-height, and start transform. Turn off to let your typography fully control the text.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'style_html_images',
            [
                'label' => esc_html__( 'Style images inside HTML', 'blackwidgets' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'blackwidgets' ),
                'label_off' => esc_html__( 'No', 'blackwidgets' ),
                'return_value' => 'yes',
                'default' => '',
                'description' => esc_html__( 'Apply Image Style controls to <img> tags inside your HTML content.', 'blackwidgets' ),
            ]
        );

        $this->add_control(
            'hr_typo_manage',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'style_text_align',
            [
                'label' => esc_html__( 'Alignment', 'blackwidgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justify', 'blackwidgets' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    $typo_selector => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'style_text_decoration',
            [
                'label' => esc_html__( 'Text Decoration', 'blackwidgets' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Default', 'blackwidgets' ),
                    'none' => esc_html__( 'None', 'blackwidgets' ),
                    'underline' => esc_html__( 'Underline', 'blackwidgets' ),
                    'overline' => esc_html__( 'Overline', 'blackwidgets' ),
                    'line-through' => esc_html__( 'Line Through', 'blackwidgets' ),
                ],
                'selectors' => [
                    $typo_selector => 'text-decoration: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'style_alert_color',
            [
                'label' => esc_html__( 'Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    $typo_selector => 'color: {{VALUE}}',
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
                'selector' => $typo_selector,
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_alert_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => $typo_selector,
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
                    '{{WRAPPER}} .bw-gsap-code-box i, {{WRAPPER}} .bw-gsap-code-box svg' => 'font-size: {{SIZE}}{{UNIT}} !important; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'style_icon_alert_color',
            [
                'label' => esc_html__( 'Icon Color', 'blackwidgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bw-gsap-code-box i, {{WRAPPER}} .bw-gsap-code-box svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'style_Icon_alert_text_shadow',
                'label' => esc_html__( 'Text Shadow', 'blackwidgets' ),
                'selector' => '{{WRAPPER}} .bw-gsap-code-box i, {{WRAPPER}} .bw-gsap-code-box svg',
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render the GSAP trigger widget output on the frontend.
     *
     * Outputs the widget HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings   					= $this->get_settings_for_display();

        // Variables
        $type 	        				= isset($settings['widget_type']) 									? $settings['widget_type'] 									: '';
        $message        				= isset($settings['widget_text']) 									? $settings['widget_text'] 									: '';
        $gsap_ready                     = \Modernaweb\BlackWidgets\Plugin_Options::is_gsap_ready();
        // Content - HTML
        $html_code						= isset($settings['widget_html_content'])							? $settings['widget_html_content']							: '';
        $shared_class					= isset($settings['widget_html_class'])								? $settings['widget_html_class']							: '';
        $html_trigger					= isset($settings['widget_html_js_gsap_timeline_scroll_trigger'])	? $this->sanitize_gsap_js_fragment( $settings['widget_html_js_gsap_timeline_scroll_trigger'] )	: '';
        $html_js_from					= isset($settings['widget_html_js_tl_from'])						? $this->sanitize_gsap_js_fragment( $settings['widget_html_js_tl_from'] )						: '';
        $html_js_to						= isset($settings['widget_html_js_tl_to'])							? $this->sanitize_gsap_js_fragment( $settings['widget_html_js_tl_to'] )							: '';
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
        $scoped         = $this->should_scope_selectors( $settings );
        $html_target_sel = $this->build_class_selector( $data_id, $shared_class, $scoped );
        $img_trigger_sel = $this->build_class_selector( $data_id, $image_trigger_class, $scoped );
        $img_target_sel  = $this->build_class_selector( $data_id, $img_class, $scoped );


        switch ($type) {
            case 'html':
                echo '<div class="' . esc_attr( implode( ' ', $this->get_html_box_classes( $settings ) ) ) . '" id="' . esc_attr( $data_id ) . '">';
                if ( stripos( $html_code, '<svg' ) !== false ) {
                    $sanitizer = new Sanitizer();
                    $sanitizer->removeRemoteReferences( true );

                    $output = '';
                    $pos    = 0;
                    $len    = strlen( $html_code );
                    while ( $pos < $len ) {
                        $svg_start = stripos( $html_code, '<svg', $pos );
                        if ( $svg_start === false ) {
                            $output .= wp_kses_post( substr( $html_code, $pos ) );
                            break;
                        }

                        if ( $svg_start > $pos ) {
                            $output .= wp_kses_post( substr( $html_code, $pos, $svg_start - $pos ) );
                        }

                        // Find the matching </svg> by counting nested <svg> opens and closes.
                        $depth   = 0;
                        $cursor  = $svg_start;
                        $svg_end = false;

                        // A self-closing outer <svg .../> is a complete block with no </svg>.
                        $outer_end = strpos( $html_code, '>', $svg_start );
                        if ( $outer_end !== false && $html_code[ $outer_end - 1 ] === '/' ) {
                            $svg_end = $outer_end + 1;
                            $cursor  = $len;
                        }
                        while ( $cursor < $len ) {
                            $next_open  = stripos( $html_code, '<svg', $cursor );
                            $next_close = stripos( $html_code, '</svg', $cursor );
                            if ( $next_close === false ) {
                                break;
                            }
                            if ( $next_open !== false && $next_open < $next_close ) {
                                $tag_end = strpos( $html_code, '>', $next_open );
                                if ( $tag_end === false ) {
                                    break;
                                }
                                // A self-closing nested <svg .../> has no </svg>, so it does not change the depth.
                                if ( $html_code[ $tag_end - 1 ] !== '/' ) {
                                    $depth++;
                                }
                                $cursor = $tag_end + 1;
                            } else {
                                $depth--;
                                $gt     = strpos( $html_code, '>', $next_close );
                                if ( $gt === false ) {
                                    break;
                                }
                                $cursor = $gt + 1;
                                if ( $depth === 0 ) {
                                    $svg_end = $cursor;
                                    break;
                                }
                            }
                        }

                        // Unclosed <svg>: drop it and everything after it.
                        if ( $svg_end === false ) {
                            break;
                        }

                        $clean = $sanitizer->sanitize( substr( $html_code, $svg_start, $svg_end - $svg_start ) );
                        if ( $clean !== false ) {
                            $clean   = preg_replace( '/^\s*<\?xml[^>]*\?>\s*/i', '', $clean );
                            $output .= $clean;
                        }

                        $pos = $svg_end;
                    }
                } else {
                    $output = wp_kses_post( $html_code );
                }
                echo $this->prepare_html_content( $output, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized above, then class-annotated.
                echo '</div>';

                if ( $gsap_ready && $html_target_sel !== '' ) {
                    $html_st_trigger = $this->extract_scrolltrigger_selector( $html_trigger );
                    $st_missing_guard = '';
                    if ( $html_st_trigger !== '' && $html_st_trigger !== $html_target_sel ) {
                        $st_missing_guard = 'if (!document.querySelector("' . esc_js( $html_st_trigger ) . '")) return;';
                    }
                    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<script>
                             jQuery(window).ready(function($) {
                                 if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;
                                 var targetSel = "' . esc_js( $html_target_sel ) . '";
                                 if (!targetSel || !document.querySelector(targetSel)) return;
                                 ' . $st_missing_guard . '
                                 gsap.registerPlugin(ScrollTrigger);
                                 const tl = gsap.timeline({
                                     scrollTrigger: {
                                         ' . $html_trigger . '
                                     }
                                 });
                                 tl.from(targetSel, {
                                     ' . $html_js_from . '
                                 });
                                 tl.to(targetSel, {
                                     ' . $html_js_to . '
                                 });
                             });
                         </script>';
                    // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                break;

            case 'image':
                echo '<div class="bw-gsap-img" id="' . esc_attr( $data_id ) . '"><div class="' . esc_attr( $image_trigger_class ) . '">';
                echo $this->render_trigger_image_html( $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside helper.
                echo '</div></div>';
                if ( $gsap_ready && $img_trigger_sel !== '' && $img_target_sel !== '' ) {
                    $img_target_guard = '';
                    if ( $img_target_sel !== $img_trigger_sel ) {
                        $img_target_guard = 'if (!document.querySelector("' . esc_js( $img_target_sel ) . '")) return;';
                    }
                    // Build executable JS. Values are escaped individually, not the whole script.
                    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<script>
						jQuery(window).ready(function($) {
							if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") return;
							var triggerSel = "' . esc_js( $img_trigger_sel ) . '";
							if (!triggerSel || !document.querySelector(triggerSel)) return;
							' . $img_target_guard . '
							gsap.registerPlugin(ScrollTrigger);
							const tl = gsap.timeline({
								scrollTrigger: {
								trigger:		triggerSel,
								start:			"' . esc_js( $image_start_point ) . '",
								end:			"' . esc_js( $image_end_point ) . '",
								toggleActions:	"' . esc_js( $image_toggle_actions ) . '",
								scrub: 			"' . esc_js( $image_scrub ) . '",
								markers: 		false,
							}});
							tl.from("' . esc_js( $img_target_sel ) . '", {
								opacity:				' . esc_js( $element_opacity_from ) . ',
								duration:				' . esc_js( $element_duration_from ) . ',
								rotateX:				' . esc_js( $element_rotationx_from ) . ',
								rotateY:				' . esc_js( $element_rotationy_from ) . ',
								scaleX:					' . esc_js( $element_scalex_from ) . ',
								scaleY:					' . esc_js( $element_scaley_from ) . ',
								scaleZ:					' . esc_js( $element_scalez_from ) . ',
								skewX:					' . esc_js( $element_skewx_from ) . ',
								skewY:					' . esc_js( $element_skewy_from ) . ',
								x:						' . esc_js( $element_movex_from ) . ',
								y:						' . esc_js( $element_movey_from ) . ',
								z:						' . esc_js( $element_movez_from ) . ',
								autoAlpha:				' . esc_js( $element_autoalpha_from ) . ',
								transformPerspective:	' . esc_js( $element_perspective_from ) . ',
								transformStyle:			"' . esc_js( $element_transformstyle_from ) . '",
							});
							tl.to("' . esc_js( $img_target_sel ) . '", {
								opacity:				' . esc_js( $element_opacity_to ) . ',
								duration:				' . esc_js( $element_duration_to ) . ',
								rotateX:				' . esc_js( $element_rotationx_to ) . ',
								rotateY:				' . esc_js( $element_rotationy_to ) . ',
								scaleX:					' . esc_js( $element_scalex_to ) . ',
								scaleY:					' . esc_js( $element_scaley_to ) . ',
								scaleZ:					' . esc_js( $element_scalez_to ) . ',
								skewX:					' . esc_js( $element_skewx_to ) . ',
								skewY:					' . esc_js( $element_skewy_to ) . ',
								x:						' . esc_js( $element_movex_to ) . ',
								y:						' . esc_js( $element_movey_to ) . ',
								z:						' . esc_js( $element_movez_to ) . ',
								autoAlpha:				' . esc_js( $element_autoalpha_to ) . ',
								transformPerspective:	' . esc_js( $element_perspective_to ) . ',
								transformStyle:			"' . esc_js( $element_transformstyle_to ) . '",
							});
						});
					</script>';
                    // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                break;

            default:
                // Supported types are html and image only - render nothing for unknown values.
                break;
        }


    }

    /**
     * Wrapper classes for HTML mode: typography target, demo styles, optional inner-image styling.
     *
     * @param array $settings Widget settings.
     * @return string[]
     */
    protected function get_html_box_classes( $settings ) {
        $classes = [ 'bw-gsap-code-box' ];

        $apply = isset( $settings['style_apply_to'] ) ? (string) $settings['style_apply_to'] : 'shared';
        $allowed = [ 'shared', 'headings', 'all', 'wrapper' ];
        if ( ! in_array( $apply, $allowed, true ) ) {
            $apply = 'shared';
        }
        $classes[] = 'bw-trigger-typo-' . $apply;

        if ( ! isset( $settings['keep_demo_text_styles'] ) || $settings['keep_demo_text_styles'] === 'yes' ) {
            $classes[] = 'bw-trigger-demo-styles';
        }

        if ( isset( $settings['style_html_images'] ) && $settings['style_html_images'] === 'yes' ) {
            $classes[] = 'bw-trigger-style-html-imgs';
        }

        return $classes;
    }

    /**
     * Add helper classes (and optionally strip inline typography) without removing user classes.
     *
     * @param string $html     Sanitized HTML fragment.
     * @param array  $settings Widget settings.
     * @return string
     */
    protected function prepare_html_content( $html, $settings ) {
        $html = (string) $html;
        if ( $html === '' || ! class_exists( '\WP_HTML_Tag_Processor' ) ) {
            return $html;
        }

        $shared = isset( $settings['widget_html_class'] ) ? ltrim( trim( (string) $settings['widget_html_class'] ), '.' ) : '';
        $apply  = isset( $settings['style_apply_to'] ) ? (string) $settings['style_apply_to'] : 'shared';
        $override = isset( $settings['override_inline_styles'] ) && $settings['override_inline_styles'] === 'yes';

        $heading_tags = [ 'H1', 'H2', 'H3', 'H4', 'H5', 'H6' ];
        $all_tags     = [ 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P', 'SPAN', 'A', 'LI', 'BLOCKQUOTE' ];

        $processor = new \WP_HTML_Tag_Processor( $html );
        while ( $processor->next_tag() ) {
            $class_attr = (string) $processor->get_attribute( 'class' );
            $classes    = preg_split( '/\s+/', $class_attr, -1, PREG_SPLIT_NO_EMPTY );
            $has_shared = ( $shared !== '' && is_array( $classes ) && in_array( $shared, $classes, true ) );

            if ( $has_shared ) {
                $processor->add_class( 'bw-trigger-item' );
            }

            if ( ! $override ) {
                continue;
            }

            $tag = strtoupper( (string) $processor->get_tag() );
            $should_strip = false;
            if ( $apply === 'shared' && $has_shared ) {
                $should_strip = true;
            } elseif ( $apply === 'headings' && in_array( $tag, $heading_tags, true ) ) {
                $should_strip = true;
            } elseif ( $apply === 'all' && in_array( $tag, $all_tags, true ) ) {
                $should_strip = true;
            }

            if ( ! $should_strip ) {
                continue;
            }

            $style   = (string) $processor->get_attribute( 'style' );
            $cleaned = $this->strip_inline_typography_styles( $style );
            if ( $cleaned === '' ) {
                $processor->remove_attribute( 'style' );
            } else {
                $processor->set_attribute( 'style', $cleaned );
            }
        }

        return $processor->get_updated_html();
    }

    /**
     * Drop typography-related declarations from an inline style attribute.
     *
     * @param string $style Raw style attribute.
     * @return string
     */
    protected function strip_inline_typography_styles( $style ) {
        $style = trim( (string) $style );
        if ( $style === '' ) {
            return '';
        }

        $remove = [
            'font-size',
            'font-family',
            'font-weight',
            'font-style',
            'line-height',
            'letter-spacing',
            'word-spacing',
            'text-transform',
            'color',
        ];

        $parts = array_map( 'trim', explode( ';', $style ) );
        $kept  = [];
        foreach ( $parts as $part ) {
            if ( $part === '' ) {
                continue;
            }
            $name = strtolower( trim( (string) strstr( $part, ':', true ) ) );
            if ( in_array( $name, $remove, true ) ) {
                continue;
            }
            $kept[] = $part;
        }

        return implode( '; ', $kept );
    }

    /**
     * Image markup with srcset/alt when an attachment exists, URL fallback otherwise.
     *
     * @param array $settings Widget settings.
     * @return string
     */
    protected function render_trigger_image_html( $settings ) {
        $image    = isset( $settings['image'] ) && is_array( $settings['image'] ) ? $settings['image'] : [];
        $image_id = ! empty( $image['id'] ) ? absint( $image['id'] ) : 0;
        $alt      = '';

        if ( ! empty( $settings['image_alt'] ) ) {
            $alt = (string) $settings['image_alt'];
        } elseif ( $image_id ) {
            $alt = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
        }

        $image_html = '';
        if ( $image_id ) {
            $image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );
        }

        if ( $image_html ) {
            if ( preg_match( '/<img\b[^>]*\bclass="/i', $image_html ) ) {
                $image_html = preg_replace( '/(<img\b[^>]*\bclass=")/i', '$1bw-img-trigger-x ', $image_html, 1 );
            } else {
                $image_html = preg_replace( '/<img\b/i', '<img class="bw-img-trigger-x"', $image_html, 1 );
            }

            if ( $alt !== '' ) {
                $safe_alt = esc_attr( $alt );
                if ( preg_match( '/<img\b[^>]*\balt=/i', $image_html ) ) {
                    $image_html = preg_replace_callback(
                        '/(<img\b[^>]*\balt=")[^"]*(")/i',
                        static function ( $matches ) use ( $safe_alt ) {
                            return $matches[1] . $safe_alt . $matches[2];
                        },
                        $image_html,
                        1
                    );
                } else {
                    $image_html = preg_replace( '/<img\b/i', '<img alt="' . $safe_alt . '"', $image_html, 1 );
                }
            }

            return is_string( $image_html ) ? $image_html : '';
        }

        $url = '';
        if ( ! empty( $image['url'] ) ) {
            $url = $image['url'];
        } elseif ( $image_id ) {
            $src = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings );
            $url = $src ? $src : '';
        }

        if ( $url === '' ) {
            return '';
        }

        return '<img src="' . esc_url( $url ) . '" class="bw-img-trigger-x" alt="' . esc_attr( $alt ) . '">'; // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
    }

    /**
     * Harden free-form GSAP object-literal fragments against script-tag breakout.
     * These fields are intentionally executable JS for trusted editors (legacy BC);
     * we only strip sequences that can escape the enclosing <script> element.
     *
     * @param mixed $code Raw editor input.
     * @return string
     */
    private function sanitize_gsap_js_fragment( $code ) {
        $code = (string) $code;
        $code = str_replace( "\0", '', $code );
        // Remove any HTML script tags / closers that would break out of our wrapper.
        $code = preg_replace( '/<\/?script\b[^>]*>/i', '', $code );
        $code = str_ireplace( [ '</script', '<script' ], '', $code );
        return $code;
    }

}


class_alias('Modernaweb\BlackWidgets\Widgets\GSAPTrigger', 'Black_Widgets\BLACK_WIDGETS_GSAP_Trigger');