<?php
namespace Modernaweb\BlackWidgets;

if (!defined('ABSPATH')) {
    exit;
}

use Modernaweb\BlackWidgets\Widgets\{
    GSAPInteractiveLinks,
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
    ScrollText,
    Box,
    FlatNav,
    Sentence,
    ScrollHeat,
    TextMarquee,
    ImageMarquee,
    ImageCarousel,
    TextAnimate,
    GSAPTrigger,
    GSAPHorizontalScrolling,
    GSAPTab};
use enshrined\svgSanitize\Sanitizer;

final class Main {

    const MINIMUM_ELEMENTOR_VERSION = '3.5.0';
    const MINIMUM_PHP_VERSION = '7.4';

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        add_filter( 'upload_mimes', [ $this, 'add_file_types_to_uploads' ] );
        add_filter( 'wp_handle_upload_prefilter', [ $this, 'sanitize_uploaded_svg' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_scripts' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_public_styles' ] );
        add_action('elementor/frontend/after_register_scripts', [ $this, 'register_widget_scripts' ] );
        add_action('elementor/frontend/after_register_styles', [ $this, 'register_widget_styles']);

        // Registered outside is_admin() on purpose: WP-CLI and cron auto-updates
        // never load the Admin class, and that is exactly when nobody is around to
        // trigger the Elementor CSS rebuild from wp-admin.
        add_action( 'upgrader_process_complete', 'black_widgets_clear_css_cache_after_update', 10, 2 );

        if ( is_admin() ) {
            require_once( __DIR__ . '/admin.php' );
            new Admin();
        }
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

        add_action( 'elementor/editor/after_enqueue_scripts', function() {
            wp_enqueue_script(
                'black-widgets-editor-panel',
                BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/js/black-widgets-editor-panel.js',
                [ 'jquery' ],
                BLACK_WIDGETS_VERSION,
                true
            );
        } );

        add_action('elementor/editor/before_enqueue_scripts', function() {
            // Ensure handles exist for the editor chrome; do not force-load on every page.
            // Widget get_script_depends() loads bw-public/anime only when needed (preview/front).
            if ( ! wp_script_is( 'black-widgets-anime', 'registered' ) ) {
                wp_register_script( 'black-widgets-anime', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/anime.js', [ 'jquery' ], BLACK_WIDGETS_VERSION, true );
            }
            if ( ! wp_script_is( 'bw-public', 'registered' ) ) {
                wp_register_script( 'bw-public', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/bw-public.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION, true );
            }
        });

        add_action( 'elementor/preview/enqueue_scripts', function() {
            // Only load GSAP into preview when the master toggle is on (same as front).
            Plugin_Options::register_gsap_scripts();

            if ( ! wp_script_is( 'black-widgets-anime', 'registered' ) ) {
                wp_register_script( 'black-widgets-anime', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/anime.js', [ 'jquery' ], BLACK_WIDGETS_VERSION, true );
            }
            if ( ! wp_script_is( 'bw-public', 'registered' ) ) {
                wp_register_script( 'bw-public', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/bw-public.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION, true );
            }
            if ( ! wp_script_is( 'black-widgets-swiper', 'registered' ) ) {
                wp_register_script( 'black-widgets-swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/swiper-bundle.min.js', [], BLACK_WIDGETS_VERSION, true );
                wp_register_style( 'black-widgets-swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/libraries/swiper-bundle.min.css', [], BLACK_WIDGETS_VERSION );
            }

            // Widget get_script_depends() only covers widgets that were already on the
            // canvas when the preview document was rendered. Dragging a fresh Scroll
            // Text / Image Carousel in afterwards would otherwise leave the page
            // without its init script until the editor is reloaded.
            $this->enqueue_preview_widget_assets();

            $deps = [ 'black-widgets-tilt', 'black-widgets-simple-parallax', 'black-widgets-anime' ];
            if ( wp_script_is( 'GSAP', 'registered' ) ) {
                $deps[] = 'GSAP';
            }
            if ( wp_script_is( 'GSAP-ScrollTrigger', 'registered' ) ) {
                $deps[] = 'GSAP-ScrollTrigger';
            }
            if ( wp_script_is( 'GSAP-SplitText', 'registered' ) ) {
                $deps[] = 'GSAP-SplitText';
            }
            if ( wp_script_is( 'black-widgets-typography', 'registered' ) ) {
                $deps[] = 'black-widgets-typography';
            }
            if ( wp_script_is( 'black-widgets-swiper', 'registered' ) ) {
                $deps[] = 'black-widgets-swiper';
            }

            wp_enqueue_script( 'black-widgets-preview', BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/js/black-widgets-preview.js', $deps, BLACK_WIDGETS_VERSION, true );
        } );
    }

    /**
     * Register + enqueue the init assets of widgets whose animations must keep
     * working when the widget is added to an already-open editor preview.
     *
     * Handles are registered defensively here because widget constructors (the
     * usual registration point) may not have run yet inside the preview.
     */
    private function enqueue_preview_widget_assets() {
        if ( ! wp_style_is( 'black-widgets-typography', 'registered' ) ) {
            wp_register_style( 'black-widgets-typography', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/typography.css', [], BLACK_WIDGETS_VERSION );
        }

        if ( Plugin_Options::is_gsap_ready() && ! wp_script_is( 'black-widgets-typography', 'registered' ) ) {
            $typo_deps = [ 'jquery', 'GSAP', 'GSAP-ScrollTrigger' ];
            if ( Plugin_Options::is_gsap_split_ready() && wp_script_is( 'GSAP-SplitText', 'registered' ) ) {
                $typo_deps[] = 'GSAP-SplitText';
            }
            wp_register_script( 'black-widgets-typography', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/typography.js', $typo_deps, BLACK_WIDGETS_VERSION, true );
        }

        // Always enqueue SplitText in the preview when the CDN option is on.
        // Mask Rise / Clip Wipe need it even if typography.js was registered earlier.
        if ( Plugin_Options::is_gsap_split_ready() && wp_script_is( 'GSAP-SplitText', 'registered' ) ) {
            wp_enqueue_script( 'GSAP-SplitText' );
        }

        if ( ! wp_style_is( 'black-widgets-image-carousel', 'registered' ) ) {
            wp_register_style( 'black-widgets-image-carousel', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/image-carousel.css', [ 'black-widgets-swiper' ], BLACK_WIDGETS_VERSION );
        }

        if ( ! wp_script_is( 'black-widgets-image-carousel', 'registered' ) ) {
            wp_register_script( 'black-widgets-image-carousel', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/image-carousel.js', [ 'jquery', 'black-widgets-swiper' ], BLACK_WIDGETS_VERSION, true );
        }

        foreach ( [ 'black-widgets-typography', 'black-widgets-image-carousel' ] as $handle ) {
            if ( wp_style_is( $handle, 'registered' ) ) {
                wp_enqueue_style( $handle );
            }
            if ( wp_script_is( $handle, 'registered' ) ) {
                wp_enqueue_script( $handle );
            }
        }

        // Reliable signal for typography.js: preview iframe has no usable scroll,
        // and elementorFrontend.isEditMode() is not always true on first paint.
        if ( wp_script_is( 'black-widgets-typography', 'registered' ) ) {
            wp_localize_script(
                'black-widgets-typography',
                'bwTypographyEnv',
                [
                    'isEditor' => true,
                ]
            );
        }
    }

    public function add_file_types_to_uploads($file_types){
        $new_filetypes = array();
        $new_filetypes['svg'] = 'image/svg+xml';
        $file_types = array_merge($file_types, $new_filetypes );
        return $file_types;
    }

    public function sanitize_uploaded_svg( $file ) {
        if ( $file['type'] !== 'image/svg+xml' && pathinfo( $file['name'], PATHINFO_EXTENSION ) !== 'svg' ) {
            return $file;
        }

        if ( ! class_exists( 'enshrined\svgSanitize\Sanitizer' ) ) {
            $file['error'] = 'SVG Sanitizer library is not loaded.';
            return $file;
        }

        $dirty_svg = file_get_contents( $file['tmp_name'] );
        $sanitizer = new Sanitizer();
        $clean_svg = $sanitizer->sanitize( $dirty_svg );

        if ( $clean_svg === false || empty( $clean_svg ) ) {
            $file['error'] = 'Invalid or potentially unsafe SVG file.';
            return $file;
        }

        file_put_contents( $file['tmp_name'], $clean_svg );
        return $file;
    }

    public function admin_notice_missing_main_plugin() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'blackwidgets' ),
            '<strong>' . esc_html__( 'Black widgets is Elementor extension', 'blackwidgets' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor Plugin', 'blackwidgets' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public function admin_notice_minimum_elementor_version() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'blackwidgets' ),
            '<strong>' . esc_html__( 'Black Widgets', 'blackwidgets' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'blackwidgets' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

    public function admin_notice_minimum_php_version() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'blackwidgets' ),
            '<strong>' . esc_html__( 'Black Widgets', 'blackwidgets' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'blackwidgets' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public function init_widgets() {
        // Include Widget files
        require_once( __DIR__ . '/widgets/bw-title.php' );
        require_once( __DIR__ . '/widgets/bw-button.php' );
        require_once( __DIR__ . '/widgets/bw-image-pro.php' );
        require_once( __DIR__ . '/widgets/bw-flipbox.php' );
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
        require_once( __DIR__ . '/widgets/bw-box.php' );
        require_once( __DIR__ . '/widgets/bw-nav.php' );
        require_once( __DIR__ . '/widgets/bw-sentence.php' );
        require_once( __DIR__ . '/widgets/bw-text-marquee.php' );
        require_once( __DIR__ . '/widgets/bw-image-marquee.php' );
        require_once( __DIR__ . '/widgets/bw-text-animate.php' );
        require_once( __DIR__ . '/widgets/bw-image-carousel.php' );

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
        \Elementor\Plugin::instance()->widgets_manager->register( new TextMarquee() );
        \Elementor\Plugin::instance()->widgets_manager->register( new ImageMarquee() );
        \Elementor\Plugin::instance()->widgets_manager->register( new ImageCarousel() );
        \Elementor\Plugin::instance()->widgets_manager->register( new TextAnimate() );

        // GSAP-powered widgets - only when the JS CDN master toggle is on.
        if ( Plugin_Options::is_gsap_toggle_on() ) {
            require_once( __DIR__ . '/widgets/bw-gsap-trigger.php' );
            require_once( __DIR__ . '/widgets/bw-gsap-horizontal-scrolling.php' );
            require_once( __DIR__ . '/widgets/bw-gsap-tab.php' );
            require_once( __DIR__ . '/widgets/bw-gsap-interactive-links.php' );
            require_once( __DIR__ . '/widgets/bw-scroll-heat.php' );

            \Elementor\Plugin::instance()->widgets_manager->register( new GSAPTrigger() );
            \Elementor\Plugin::instance()->widgets_manager->register( new GSAPHorizontalScrolling() );
            \Elementor\Plugin::instance()->widgets_manager->register( new GSAPTab() );
            \Elementor\Plugin::instance()->widgets_manager->register( new GSAPInteractiveLinks() );
            \Elementor\Plugin::instance()->widgets_manager->register( new ScrollHeat() );

            if ( Plugin_Options::is_scroll_text_enabled() ) {
                require_once( __DIR__ . '/widgets/bw-scroll-text.php' );
                \Elementor\Plugin::instance()->widgets_manager->register( new ScrollText() );
            }
        }
    }

    /**
     * @deprecated 1.4.0 Use Plugin_Options::is_scroll_text_enabled().
     * @param array|string|null $options Unused; kept for BC of any external callers.
     * @return bool
     */
    public function is_scroll_text_enabled( $options = null ) {
        return Plugin_Options::is_scroll_text_enabled();
    }

    public function enqueue_public_scripts() {
        // Register only - enqueue via widget get_script_depends() so pages without
        // Fade / Title Animate / Text Animate do not pay for anime.js + bw-public.js.
        wp_register_script( 'black-widgets-anime', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/anime.js', [ 'jquery' ], BLACK_WIDGETS_VERSION, true );
        wp_register_script( 'bw-public', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/bw-public.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION, true );

        Plugin_Options::register_gsap_scripts();

        wp_register_script( 'black-widgets-simple-parallax', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/simple-parallax.js', [ 'jquery' ], BLACK_WIDGETS_VERSION, true );
        wp_register_script( 'black-widgets-tilt', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/tilt.js', [ 'jquery' ], BLACK_WIDGETS_VERSION, true );
        wp_register_script( 'black-widgets-swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/swiper-bundle.min.js', [], BLACK_WIDGETS_VERSION, true );
        wp_register_style( 'black-widgets-swiper', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/libraries/swiper-bundle.min.css', [], BLACK_WIDGETS_VERSION );
    }

    public function enqueue_public_styles() {
        wp_enqueue_style( 'black-widgets-public', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/black-widgets-public.css', array(), BLACK_WIDGETS_VERSION );

        // Additive RTL overlays only - never replaces LTR stylesheets.
        if ( is_rtl() ) {
            wp_enqueue_style(
                'black-widgets-rtl',
                BLACK_WIDGETS_PLUGIN_URL . 'assets/css/bw-rtl.css',
                array( 'black-widgets-public' ),
                BLACK_WIDGETS_VERSION
            );
        }
    }

    public function add_elementor_widget_categories( $elements_manager ) {
        $elements_manager->add_category(
            'black_widgets',
            [
                'title' => esc_html__( 'Black Widgets', 'blackwidgets' ),
                'icon' => 'fa fa-plug',
            ]
        );

    }

    public function register_widget_scripts() {
        // Editor/tab scripts: register GSAP handles only when master toggle is on.
        Plugin_Options::register_gsap_scripts();

        // Shared scroll helpers used by Fade / Title Animate script deps.
        if ( ! wp_script_is( 'black-widgets-anime', 'registered' ) ) {
            wp_register_script( 'black-widgets-anime', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/anime.js', [ 'jquery' ], BLACK_WIDGETS_VERSION, true );
        }
        if ( ! wp_script_is( 'bw-public', 'registered' ) ) {
            wp_register_script( 'bw-public', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/bw-public.js', [ 'jquery', 'black-widgets-anime' ], BLACK_WIDGETS_VERSION, true );
        }

        $tab_deps = [ 'jquery' ];
        if ( wp_script_is( 'GSAP', 'registered' ) ) {
            $tab_deps[] = 'GSAP';
        }

        wp_register_script(
            'black-widgets-gsap-tab',
            BLACK_WIDGETS_PLUGIN_URL . 'assets/js/gsap-tab.js',
            $tab_deps,
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
