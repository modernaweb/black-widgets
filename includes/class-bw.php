<?php

namespace Elementor;
namespace BLACK_WIDGETS_Modernaweb\Includes\Widgets;
namespace Black_Widgets;

use enshrined\svgSanitize\Sanitizer;

final class BLACK_WIDGETS_Modernaweb_Plugin {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var BLACK_WIDGETS_Modernaweb_Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return BLACK_WIDGETS_Modernaweb_Plugin An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		// add_action( 'admin_menu', [ $this, 'black_widgets_reg_menu' ] );
		add_filter('upload_mimes', [ $this, 'add_file_types_to_uploads' ]);
        add_filter('wp_handle_upload_prefilter', [ $this, 'sanitize_svg' ] );

	}

	/**
	 * Sanitizes SVG files to prevent XSS attacks.
	 *
	 * @param array $file The uploaded file data.
	 * @return array The sanitized file data.
	 */
    public function sanitize_svg( $file ) {
        if ( $file['type'] == 'image/svg+xml' ) {
            $sanitizer = new Sanitizer();
            $content = file_get_contents( $file['tmp_name'] );
            $content = $sanitizer->sanitize( $content );
            file_put_contents( $file['tmp_name'], $content );
        }
    
        return $file;
    }

	/**
	 * Add SVG availablity
	 *
	 * Fired by `add_file_types_to_uploads` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function add_file_types_to_uploads($file_types){
		$new_filetypes = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types = array_merge($file_types, $new_filetypes );
		return $file_types;
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'elementor-test-extension' );

	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Add Plugin actions
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );

		// Add Category
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );

        add_action( 'elementor/editor/after_enqueue_styles', function() {

			// Load options
			if ( get_option('plugin_options') ): $options = get_option('plugin_options'); else: $options = ''; endif;
			$bw_dark_style  = isset($options['bw_dark_style']) ? $options['bw_dark_style'] : '';
			// var_dump( $options['bw_dark_style'] );
			if ( $bw_dark_style == 'on' ) {
				wp_register_style( 'style', plugins_url( '/admin/css/black-widgets-elementor.css', __FILE__ ), array(), '1', 'all' );
			} else {
				wp_register_style( 'style', plugins_url( '/admin/css/black-widgets-admin.css', __FILE__ ), array(), '1', 'all' );
			}

			wp_enqueue_style( 'style' );

		});

		add_action('elementor/editor/before_enqueue_scripts', function() {
			wp_enqueue_script('ele-bw-jquery-plugins', plugin_dir_url( __FILE__ ) . 'front/js/ele-bw-jquery-plugins.js', array(), '1.0.0', 'true' );
			wp_enqueue_script('bw-public', plugin_dir_url( __FILE__ ) . 'front/js/bw-public.js', array(), '1.0.0', 'true' );

		});

		// add_action( 'elementor/element/before_section_start', [ $this, 'bw_style_before_section_start' ] , 10, 3 );
		// add_action( 'elementor/frontend/section/before_render', [ $this, 'bw_css_pattern' ] );
		// add_action( 'elementor/frontend/section/after_render', [ $this, 'bw_css_js' ] );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Black Widgets is Elementor extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor Plugin', 'elementor-test-extension' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'This is Elementor extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-extension' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-extension' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'elementor-test-extension' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-test-extension' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets() {

        $options = get_option('plugin_options') ? get_option('plugin_options') : '';
        $gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';

		// Include Widget files
        require_once( __DIR__ . '/widgets/bw-title.php' );
        require_once( __DIR__ . '/widgets/bw-button.php' );
        require_once( __DIR__ . '/widgets/bw-image-pro.php' );
        require_once( __DIR__ . '/widgets/bw-flipix.php' );
        require_once( __DIR__ . '/widgets/bw-title-animate.php' );
        require_once( __DIR__ . '/widgets/bw-magic-link.php' );
        require_once( __DIR__ . '/widgets/bw-dropcap.php' );
        require_once( __DIR__ . '/widgets/bw-fade.php' );
        require_once( __DIR__ . '/widgets/bw-alert.php' );
        require_once( __DIR__ . '/widgets/bw-icon.php' );
        require_once( __DIR__ . '/widgets/bw-list.php' );
        require_once( __DIR__ . '/widgets/bw-social-links.php' );
        require_once( __DIR__ . '/widgets/bw-icon-box.php' );
        require_once( __DIR__ . '/widgets/bw-call-to-action.php' );
		require_once( __DIR__ . '/widgets/bw-blockquote.php' );
		require_once( __DIR__ . '/widgets/bw-typography.php' );
		// require_once( __DIR__ . '/widgets/bw-loop.php' );
		require_once( __DIR__ . '/widgets/bw-box.php' );
		require_once( __DIR__ . '/widgets/bw-nav.php' );
		// require_once( __DIR__ . '/widgets/bw-modale.php' ); 
		// require_once( __DIR__ . '/widgets/bw-menu-x.php' );  // Navigation Menu with responsive size and more + logo and other header styles!
		require_once( __DIR__ . '/widgets/bw-sentence.php' );

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Title() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Button() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Image_Pro() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Flip_Ix() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Title_Animate() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Magic_Link() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Dropcap() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Fade() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Alert() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Icon() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_List() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Social_Links() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Icon_Box() );
        \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Call_To_Action() );
		\Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Block_Quote() );
		\Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Typography() );
		// \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Loop() );
		\Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Box() );
		// \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Modale() );
		\Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Nav() );
		\Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Sentence() );

        if( isset($gsap_options) && !empty($gsap_options) ) {
            // require_once( __DIR__ . '/widgets/bw-sequence.php' );
            require_once( __DIR__ . '/widgets/bw-gsap-trigger.php' );
            require_once( __DIR__ . '/widgets/bw-gsap-horizontal-scrolling.php' );
            // \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_Sequence() );
            \Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_GSAP_Trigger() );
			\Elementor\Plugin::instance()->widgets_manager->register( new BLACK_WIDGETS_GSAP_HORIZONTAL_SCROLLING() );
        }


	}

	function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'black_widgets',
			[
				'title' => esc_html__( 'Black Widgets', 'blackwidgets' ),
				'icon' => 'fa fa-plug',
			]
		);
	
	}
	
	/* public function bw_style_before_section_start ( $element, $section_id, $args ) {
		// @var \Elementor\Element_Base $element | https://code.elementor.com/php-hooks/
		if ( 'section' === $element->get_name() && 'section_background' === $section_id ) {
	 
			$element->start_controls_section(
				'bw_custom_section_settings',
				[
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					'label' => esc_html__( 'BW Custom Section Settings', 'blackwidgets' ),
				]
			);

			$element->add_control(
				'bw_custom_section_enable_css_pattern',
				[
					'label' 		=> esc_html__( 'CSS Pattern For Background', 'blackwidgets' ),
					'type' 			=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'Yes', 'blackwidgets' ),
					'label_off' 	=> esc_html__( 'No !', 'blackwidgets' ),
					'return_value' 	=> 'css_enable',
					'default' 		=> 'off',
				]
			);

			$element->add_control(
				'bw_custom_section_css_pattern',
				[
					'label' => esc_html__( 'CSS Pattern', 'blackwidgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'bw-pattern-1',
					'options' => [
						'bw-pattern-1' 	=> esc_html__( 'Type 1', 'blackwidgets' ),
						'bw-pattern-2' 	=> esc_html__( 'Type 2', 'blackwidgets' ),
						'bw-pattern-3' 	=> esc_html__( 'Type 3', 'blackwidgets' ),
						'bw-pattern-4' 	=> esc_html__( 'Type 4', 'blackwidgets' ),
						'bw-pattern-5' 	=> esc_html__( 'Type 5', 'blackwidgets' ),
						'bw-pattern-6' 	=> esc_html__( 'Type 6', 'blackwidgets' ),
						'bw-pattern-7' 	=> esc_html__( 'Type 7', 'blackwidgets' ),
					],
					'condition'  => [
						'bw_custom_section_enable_css_pattern' => [
							'css_enable',
						],
					],
				]
			);

			$element->end_controls_section();
		}
	 } */

	/*  public function bw_css_pattern ( $element ) {
		
		$settings = $element->get_settings();
		if (isset($settings['bw_custom_section_enable_css_pattern']) && $settings['bw_custom_section_enable_css_pattern'] == 'css_enable' ){
			$element->add_render_attribute( '_wrapper', 'id', $settings['bw_custom_section_css_pattern'] );
		}

	 } */

	 /* function bw_css_js() {

		?>
			<script>
				jQuery(document).ready(function($) {
					jQuery("head link[rel='stylesheet']").last().before("<style>#bw-pattern-1{background: #ccc;}</style>");
				});
			</script>
		<?php

	 } */

}

BLACK_WIDGETS_Modernaweb_Plugin::instance();
