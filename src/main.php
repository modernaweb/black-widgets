<?php
namespace Modernaweb\Blackwidgets;

if (!defined('ABSPATH')) {
    exit;
}

use Modernaweb\Blackwidgets\widgets\{
    InteractiveLinks,
    Title,
    Button,
    ImagePro,
    FlipIx,
    TitleAnimate,
    MagicLink,
    Dropcap,
    Fade,
    Alert,
    Icon,
    ListItems,
    SocialLinks,
    IconBox,
    CallToAction,
    BlockQuote,
    Typography,
    Box,
    FlatNav,
    Sentence,
    RevealedText,
    TextMarquee,
    ImageMarquee,
    ImageCarousel,
    TextAnimate,
    GSAPTrigger,
    GSAPHorizontalScrolling,
    GSAPTab};

final class Main {

    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION = '7.0';

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        add_filter( 'upload_mimes', [ $this, 'add_file_types_to_uploads' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_styles' ] );
        add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
	    add_action('elementor/frontend/after_register_scripts', [ $this, 'register_widget_scripts' ] );
	    add_action('elementor/frontend/after_register_styles', [ $this, 'register_widget_styles']);

        if ( is_admin() ) {
            require_once( __DIR__ . '/admin.php' );
            new Admin();
        }
    }

    public function load_textdomain() {
		load_plugin_textdomain(
			'black-widgets',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
    }

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
            wp_enqueue_style( 'black-widgets-admin', BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/css/black-widgets-admin.css', array(), BLACK_WIDGETS_VERSION, 'all' );
        });

        add_action('elementor/editor/before_enqueue_scripts', function() {
            wp_enqueue_script('black-widgets-public', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/bw-public.js', array(), BLACK_WIDGETS_VERSION, 'true' );

        });

        add_action( 'elementor/preview/enqueue_scripts', function() {
            $options = get_option('plugin_options') ? get_option('plugin_options') : '';
            $bw_gsap_cdn1  = isset($options['bw_gsap_cdn1']) ? $options['bw_gsap_cdn1'] : '';
            $bw_gsap_cdn2  = isset($options['bw_gsap_cdn2']) ? $options['bw_gsap_cdn2'] : '';
            $bw_gsap_cdn3  = isset($options['bw_gsap_cdn3']) ? $options['bw_gsap_cdn3'] : '';

            $deps = [ 'black-widgets-tilt', 'black-widgets-simple-parallax', 'black-widgets-anime' ];

            if( isset($bw_gsap_cdn1) && !empty($bw_gsap_cdn1) ) {
                wp_register_script( 'GSAP', $bw_gsap_cdn1, array(), BLACK_WIDGETS_VERSION, 'true' );
                array_push( $deps, 'GSAP' );
            }

            if( isset($bw_gsap_cdn2) && !empty($bw_gsap_cdn2) ) {
                wp_register_script( 'GSAP-ScrollTrigger', $bw_gsap_cdn2, array(), BLACK_WIDGETS_VERSION, 'true' );
                array_push( $deps, 'GSAP-ScrollTrigger' );
            }

            if( isset($bw_gsap_cdn3) && !empty($bw_gsap_cdn3) ) {
                wp_register_script( 'TimelineMax', $bw_gsap_cdn3, array(), BLACK_WIDGETS_VERSION, 'true' );
                array_push( $deps, 'TimelineMax' );
            }

            wp_enqueue_script( 'black-widgets-preview', BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/js/black-widgets-preview.js', $deps, BLACK_WIDGETS_VERSION );
        } );
    }

	public function add_file_types_to_uploads($file_types){
		$new_filetypes = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types = array_merge($file_types, $new_filetypes );
		return $file_types;
	}

	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'black-widgets' ),
			'<strong>' . esc_html__( 'Black widgets is Elementor extension', 'black-widgets' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor Plugin', 'black-widgets' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'black-widgets' ),
			'<strong>' . esc_html__( 'This is Elementor extension', 'black-widgets' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'black-widgets' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'black-widgets' ),
			'<strong>' . esc_html__( 'Elementor Test Extension', 'black-widgets' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'black-widgets' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function init_widgets() {
        $options = get_option('plugin_options') ? get_option('plugin_options') : '';
        $gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';

		// Include Widget files
        require_once( __DIR__ . '/widgets/title.php' );
        require_once( __DIR__ . '/widgets/button.php' );
        require_once( __DIR__ . '/widgets/image-pro.php' );
        require_once( __DIR__ . '/widgets/flipIx.php' );
        require_once( __DIR__ . '/widgets/title-animate.php' );
        require_once( __DIR__ . '/widgets/magic-link.php' );
        require_once( __DIR__ . '/widgets/dropcap.php' );
        require_once( __DIR__ . '/widgets/fade.php' );
        require_once( __DIR__ . '/widgets/alert.php' );
        require_once( __DIR__ . '/widgets/icon.php' );
        require_once( __DIR__ . '/widgets/list-items.php' );
        require_once( __DIR__ . '/widgets/social-links.php' );
        require_once( __DIR__ . '/widgets/iconbox.php' );
        require_once( __DIR__ . '/widgets/call-to-action.php' );
		require_once( __DIR__ . '/widgets/block-quote.php' );
		require_once( __DIR__ . '/widgets/typography.php' );
		require_once( __DIR__ . '/widgets/box.php' );
		require_once( __DIR__ . '/widgets/flat-nav.php' );
		require_once( __DIR__ . '/widgets/sentence.php' );
		require_once( __DIR__ . '/widgets/revealed-text.php' );
		require_once( __DIR__ . '/widgets/text-marquee.php' );
		require_once( __DIR__ . '/widgets/image-marquee.php' );
		require_once( __DIR__ . '/widgets/text-animate.php' );
		require_once( __DIR__ . '/widgets/image-carousel.php' );

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register( new Title() );
        \Elementor\Plugin::instance()->widgets_manager->register( new Button() );
        \Elementor\Plugin::instance()->widgets_manager->register( new ImagePro() );
        \Elementor\Plugin::instance()->widgets_manager->register( new FlipIx() );
        \Elementor\Plugin::instance()->widgets_manager->register( new TitleAnimate() );
        \Elementor\Plugin::instance()->widgets_manager->register( new MagicLink() );
        \Elementor\Plugin::instance()->widgets_manager->register( new Dropcap() );
        \Elementor\Plugin::instance()->widgets_manager->register( new Fade() );
        \Elementor\Plugin::instance()->widgets_manager->register( new Alert() );
        \Elementor\Plugin::instance()->widgets_manager->register( new Icon() );
        \Elementor\Plugin::instance()->widgets_manager->register( new ListItems() );
        \Elementor\Plugin::instance()->widgets_manager->register( new SocialLinks() );
        \Elementor\Plugin::instance()->widgets_manager->register( new IconBox() );
        \Elementor\Plugin::instance()->widgets_manager->register( new CallToAction() );
		\Elementor\Plugin::instance()->widgets_manager->register( new BlockQuote() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Typography() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Box() );
		\Elementor\Plugin::instance()->widgets_manager->register( new FlatNav() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Sentence() );
		\Elementor\Plugin::instance()->widgets_manager->register( new RevealedText() );
		\Elementor\Plugin::instance()->widgets_manager->register( new TextMarquee() );
		\Elementor\Plugin::instance()->widgets_manager->register( new ImageMarquee() );
		\Elementor\Plugin::instance()->widgets_manager->register( new ImageCarousel() );
		\Elementor\Plugin::instance()->widgets_manager->register( new TextAnimate() );

        if( isset($gsap_options) && !empty($gsap_options) ) {
            require_once( __DIR__ . '/widgets/gsap-trigger.php' );
            require_once( __DIR__ . '/widgets/gsap-horizontal-scrolling.php' );
            require_once( __DIR__ . '/widgets/gsap-tab.php' );
            require_once( __DIR__ . '/widgets/interactive-links.php' );

            \Elementor\Plugin::instance()->widgets_manager->register( new GSAPTrigger() );
			\Elementor\Plugin::instance()->widgets_manager->register( new GSAPHorizontalScrolling() );
			\Elementor\Plugin::instance()->widgets_manager->register( new GSAPTab() );
            \Elementor\Plugin::instance()->widgets_manager->register( new InteractiveLinks() );
        }
	}

    public function enqueue_public_scripts() {
		wp_enqueue_script('bw-public', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/bw-public.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION, 'true' );

        // Load options
        $options = get_option('plugin_options') ? get_option('plugin_options') : '';
        $gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';
        $bw_gsap_cdn1  = isset($options['bw_gsap_cdn1']) ? $options['bw_gsap_cdn1'] : '';
        $bw_gsap_cdn2  = isset($options['bw_gsap_cdn2']) ? $options['bw_gsap_cdn2'] : '';
        $bw_gsap_cdn3  = isset($options['bw_gsap_cdn3']) ? $options['bw_gsap_cdn3'] : '';
        $bw_gsap_cdn4  = isset($options['bw_gsap_cdn4']) ? $options['bw_gsap_cdn4'] : '';

        if( isset($gsap_options) && !empty($gsap_options) ) {
            if( isset($bw_gsap_cdn1) && !empty($bw_gsap_cdn1) ) {
                wp_register_script( 'GSAP', $bw_gsap_cdn1, array(), BLACK_WIDGETS_VERSION, 'true' );
            }

            if( isset($bw_gsap_cdn2) && !empty($bw_gsap_cdn2) ) {
                wp_register_script( 'GSAP-ScrollTrigger', $bw_gsap_cdn2, array(), BLACK_WIDGETS_VERSION, 'true' );
            }

            if( isset($bw_gsap_cdn3) && !empty($bw_gsap_cdn3) ) {
                wp_register_script( 'TimelineMax', $bw_gsap_cdn3, array(), BLACK_WIDGETS_VERSION, 'true' );
            }

            if( isset($bw_gsap_cdn4) && !empty($bw_gsap_cdn4) ) {
                wp_register_script( 'TweenLite', $bw_gsap_cdn4, array(), BLACK_WIDGETS_VERSION, 'true' );
            }
        }

        wp_register_script( 'black-widgets-simple-parallax', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/simple-parallax.js', [ 'jquery' ], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-tilt', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/tilt.js', [ 'jquery' ], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-anime', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/anime.js', [ 'jquery' ], BLACK_WIDGETS_VERSION );
        wp_register_script( 'swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/swiper-bundle.min.js', [], BLACK_WIDGETS_VERSION );
        wp_register_style( 'swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/libraries/swiper-bundle.min.css', [], BLACK_WIDGETS_VERSION );
    }

    public function enqueue_public_styles() {
		wp_enqueue_style( 'black-widgets-public', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/black-widgets-public.css', array(), BLACK_WIDGETS_VERSION );
    }

	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'black_widgets',
			[
				'title' => esc_html__( 'Black widgets', 'black-widgets' ),
				'icon' => 'fa fa-plug',
			]
		);

	}

	public function register_widget_scripts() {

		if ( ! wp_script_is( 'GSAP', 'registered' ) ) {
			$options = get_option('plugin_options') ? get_option('plugin_options') : '';
			$bw_gsap_cdn1  = $options['bw_gsap_cdn1'] ?? '';
			if (empty($bw_gsap_cdn1 ) ) {
				return;
			}
			wp_register_script( 'GSAP', $bw_gsap_cdn1, [], BLACK_WIDGETS_VERSION, 'true' );
		}

		wp_register_script(
			'black-widgets-gsap-tab',
			BLACK_WIDGETS_PLUGIN_URL . 'assets/js/gsap-tab.js',
			['jquery', 'GSAP'],
			BLACK_WIDGETS_VERSION,
			true
		);
	}

	public function register_widget_styles() {
		wp_register_style(
			'black-widgets-gsap-tab',
			BLACK_WIDGETS_PLUGIN_URL . 'assets/css/gsap-tab.css',
			[],
			BLACK_WIDGETS_VERSION
		);
	}
}
