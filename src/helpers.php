<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adding custom icon to icon control in Elementor
 */
function Black_Widgets_elementor_icons( $tabs = array() ) {
	// Append new icons
    $new_icons = array(
        'editor-link',
        'editor-unlink',
        'editor-external-link',
        'editor-close',
        'editor-list-ol',
        'editor-list-ul',
        'editor-bold',
        'editor-italic',
        'editor-underline',
        'editor-paragraph',
        'editor-h1',
        'editor-h2',
        'editor-h3',
        'editor-h4',
        'editor-h5',
        'editor-h6',
        'editor-quote',
        'editor-code',
        'elementor',
        'elementor-square',
        'pojome',
        'plus',
        'menu-bar',
        'apps',
        'accordion',
        'alert',
        'animation-text',
        'animation',
        'banner',
        'blockquote',
        'button',
        'call-to-action',
        'captcha',
        'carousel',
        'checkbox',
        'columns',
        'countdown',
        'counter',
        'date',
        'divider-shape',
        'divider',
        'download-button',
        'dual-button',
        'email-field',
        'facebook-comments',
        'facebook-like-box',
        'form-horizontal',
        'form-vertical',
        'gallery-grid',
        'gallery-group',
        'gallery-justified',
        'gallery-masonry',
        'image-before-after',
        'image-box',
        'image-hotspot',
        'image-rollover',
        'info-box',
        'inner-section',
        'mailchimp',
        'menu-card',
        'navigation-horizontal',
        'nav-menu',
        'navigation-vertical',
        'number-field',
        'parallax',
        'php7',
        'post-list',
        'post-slider',
        'post',
        'posts-carousel',
        'posts-grid',
        'posts-group',
        'posts-justified',
        'posts-masonry',
        'posts-ticker',
        'price-list',
        'price-table',
        'radio',
        'rtl',
        'scroll',
        'search',
        'select',
        'share',
        'skill-bar',
        'slider-3d',
        'slider-album',
        'slider-device',
        'slider-full-screen',
        'slider-push',
        'slider-vertical',
        'slider-video',
        'slides',
        'social-icons',
        'spacer',
        'table',
        'tabs',
        'tel-field',
        'text-area',
        'text-field',
        'thumbnails-down',
        'thumbnails-half',
        'thumbnails-right',
        'time-line',
        'toggle',
        'url',
        't-letter',
        'text',
        'anchor',
        'bullet-list',
        'code',
        'favorite',
        'google-maps',
        'image',
        'photo-library',
        'woocommerce',
        'youtube',
        'flip-box',
        'settings',
        'headphones',
        'testimonial',
        'counter-circle',
        'person',
        'chevron-right',
        'chevron-left',
        'close',
        'file-download',
        'save',
        'zoom-in',
        'shortcode',
        'nerd',
        'device-desktop',
        'device-tablet',
        'device-mobile',
        'document-file',
        'folder-o',
        'hypster',
        'pro-icon',
        'mail',
        'lock-user',
        'testimonial-carousel',
        'media-carousel',
        'section',
        'column',
        'edit',
        'clone',
        'trash',
        'play',
        'angle-right',
        'angle-left',
        'animated-headline',
        'menu-toggle',
        'fb-embed',
        'fb-feed',
        'twitter-embed',
        'twitter-feed',
        'sync',
        'import-export',
        'check-circle',
        'library-save',
        'library-download',
        'insert',
        'preview-medium',
        'sort-down',
        'sort-up',
        'heading',
        'logo',
        'meta-data',
        'post-content',
        'post-excerpt',
        'post-navigation',
        'yoast',
        'nerd-chuckle',
        'nerd-wink',
        'comments',
        'download-circle-o',
        'library-upload',
        'save-o',
        'upload-circle-o',
        'ellipsis-h',
        'ellipsis-v',
        'arrow-left',
        'arrow-right',
        'arrow-up',
        'arrow-down',
        'play-o',
        'archive-posts',
        'archive-title',
        'featured-image',
        'post-info',
        'post-title',
        'site-logo',
        'site-search',
        'site-title',
        'cloud-check',
        'drag-n-drop',
        'welcome',
        'handle',
        'cart',
        'product-add-to-cart',
        'product-breadcrumbs',
        'product-categories',
        'product-description',
        'product-images',
        'product-info',
        'product-meta',
        'product-pages',
        'product-price',
        'product-rating',
        'product-related',
        'product-stock',
        'product-tabs',
        'product-title',
        'product-upsell',
        'products',
        'bag-light',
        'bag-medium',
        'bag-solid',
        'basket-light',
        'basket-medium',
        'basket-solid',
        'cart-light',
        'cart-medium',
        'cart-solid',
        'exchange',
        'device-laptop',
        'collapse',
        'expand',
        'navigator',
        'plug',
        'dashboard',
        'typography',
        'info-circle-o',
        'integration',
        'rating',
        'review',
        'tools',
        'loading',
        'sitemap',
        'click',
        'clock',
        'library-open',
        'warning',
        'flow',
        'cursor-move',
        'arrow-circle-left',
        'flash',
        'ban',
        'barcode',
        'calendar',
        'caret-left',
        'caret-right',
        'caret-up',
        'chain-broken',
        'check-circle-o',
        'check',
        'chevron-double-left',
        'chevron-double-right',
        'circle-o',
        'circle',
        'clock-o',
        'cog',
        'cogs',
        'commenting-o',
        'copy',
        'database',
        'dot-circle-o',
        'envelope',
        'external-link-square',
        'folder',
        'font',
        'history',
        'image-bold',
        'info-circle',
        'link',
        'long-arrow-left',
        'long-arrow-right',
        'paint-brush',
        'pencil',
        'zoom-in-bold',
        'sort-amount-desc',
        'sign-out',
        'spinner',
        'square',
        'star-o',
        'text-align-justify',
        'text-align-center',
        'tags',
        'text-align-left',
        'text-align-right',
        'close-circle',
        'trash-o',
        'user-circle-o',
        'video-camera',
        'wrench',
        'redo',
        'undo',
        'font-awesome',
        'filter',
        'adjust',
        'lightbox',
        'caret-down',
        'heart-o',
        'heart',
        'wordpress',
        'star',
        'help',
        'help-o',
        'zoom-out-bold',
        'sidebar',
        'plus-square-o',
        'minus-square-o',
        'plus-circle-o',
        'plus-circle',
        'minus-circle',
        'minus-circle-o',
        'code-bold',
        'plus-square',
        'minus-square',
        'cloud-upload',
        'search-bold',
        'map-pin',
        'meetup',
        'slideshow',
        't-letter-bold',
        'h-align-left',
        'h-align-right',
        'h-align-center',
        'h-align-stretch',
        'v-align-top',
        'v-align-bottom',
        'v-align-middle',
        'v-align-stretch',
        'icon-box',
        'preferences',
        'table-of-contents',
        'tv',
        'upload',
        'instagram-comments',
        'instagram-nested-gallery',
        'instagram-post',
        'instagram-video',
        'instagram-gallery',
        'instagram-likes',
        'facebook',
        'twitter',
        'pinterest',
        'frame-minimize',
        'share-arrow',
        'eyedropper',
        'archive',
        'colors-typography',
        'custom',
        'footer',
        'header',
        'layout-settings',
        'lightbox-expand',
        'error-404',
        'search-results',
        'single-post',
        'site-identity',
        'preview-thin',
        'theme-style',
        'theme-builder',
        'download-bold',
        'frame-expand',
        'global-settings',
        'user-preferences',
        'lock',
    );

	$tabs['black_widgets_elementor_icons'] = array(
		'name'          => 'black_widgets_elementor_icons',
		'label'         => esc_html__( 'Elementor Icons', 'blackwidgets' ),
		'labelIcon'     => 'demo-icon eicon-elementor-square',
		'prefix'        => 'eicon-',
		'displayPrefix' => 'eicon',
		'icons'         => $new_icons,
		'ver'           => '5.6.0',
	);

	return $tabs;
}
add_filter( 'elementor/icons_manager/additional_tabs', 'Black_Widgets_elementor_icons' );


/** Admin Content */
function black_widgets_options() {
    require_once( BLACK_WIDGETS_PLUGIN_PATH . 'includes/admin/black-widgets-control-panel.php');
}

/**
 * Sanitize raw SVG markup for safe front-end output.
 *
 * Uses enshrined/svg-sanitize. Do not pass the result through wp_kses_post() - 
 * WordPress post kses strips <svg> and empties the markup.
 *
 * @param string $raw Raw SVG markup.
 * @return string Clean SVG markup, or empty string on failure.
 */
function black_widgets_sanitize_svg_markup( $raw ) {
	if ( ! is_string( $raw ) || $raw === '' ) {
		return '';
	}

	if ( ! class_exists( '\enshrined\svgSanitize\Sanitizer' ) ) {
		return '';
	}

	$sanitizer = new \enshrined\svgSanitize\Sanitizer();
	$sanitizer->removeRemoteReferences( true );
	$clean     = $sanitizer->sanitize( $raw );

	if ( $clean === false || $clean === null || $clean === '' ) {
		return '';
	}

	return $clean;
}

/**
 * Sanitize an Elementor repeater item `_id` for safe use in CSS class names.
 *
 * Blocks attribute-breakout XSS when `_id` is tampered (e.g. intercepted Elementor save).
 * Keeps only A-Z, a-z, 0-9, underscore, and hyphen - matching normal Elementor repeater IDs.
 *
 * @param mixed $id Raw repeater `_id`.
 * @return string
 */
function black_widgets_sanitize_repeater_id( $id ) {
	if ( ! is_scalar( $id ) ) {
		return '';
	}

	return sanitize_html_class( (string) $id );
}

/**
 * Read raw Elementor widget settings without calling get_data()/get_settings_for_display().
 *
 * Elementor 3.28+ typed sanitize_settings(array $settings). Widget *type* prototypes
 * (empty constructor data) leave $data null - get_data() then fatals with null settings.
 * This helper reflects the private $data property safely for early hooks / register_controls.
 *
 * @param object $stack Elementor Controls_Stack / Widget instance.
 * @return array<string, mixed>
 */
function black_widgets_elementor_raw_settings( $stack ) {
	if ( ! is_object( $stack ) || ! class_exists( '\Elementor\Controls_Stack' ) ) {
		return [];
	}

	if ( ! $stack instanceof \Elementor\Controls_Stack ) {
		return [];
	}

	try {
		$ref = new \ReflectionClass( \Elementor\Controls_Stack::class );
		if ( ! $ref->hasProperty( 'data' ) ) {
			return [];
		}
		$prop = $ref->getProperty( 'data' );
		// No-op since PHP 8.1 (all properties are implicitly accessible) and
		// deprecated in 8.5, so only call it where it is still required.
		if ( PHP_VERSION_ID < 80100 ) {
			$prop->setAccessible( true );
		}
		$data = $prop->getValue( $stack );
	} catch ( \Throwable $e ) {
		return [];
	}

	if ( ! is_array( $data ) ) {
		return [];
	}

	$settings = $data['settings'] ?? [];
	return is_array( $settings ) ? $settings : [];
}

/**
 * Drop Elementor's pre-generated CSS files.
 *
 * Several stored controls changed their `selector` in 1.4.0 (List, Icon Box hover,
 * Flip Box), so existing pages keep rendering their old rules until each post CSS
 * file is rebuilt.
 *
 * Defined here rather than on the Admin class because WP-CLI updates and cron
 * auto-updates never reach `is_admin()`, so that class is not loaded there.
 *
 * @return bool Whether the cache was cleared.
 */
function black_widgets_clear_elementor_css_cache() {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}

	if ( ! isset( \Elementor\Plugin::$instance->files_manager ) ) {
		return false;
	}

	\Elementor\Plugin::$instance->files_manager->clear_cache();

	return true;
}

/**
 * Clear Elementor's CSS files right after Black Widgets itself is updated, so a
 * WP-CLI or background auto-update does not leave the site on stale post CSS
 * until an administrator happens to open wp-admin.
 *
 * The stored DB version is deliberately not touched here: during this hook the
 * pre-update code is still the one in memory, so BLACK_WIDGETS_VERSION is the old
 * value and writing it would skip the real migration in Admin.
 *
 * @param mixed $upgrader   Upgrader instance (unused).
 * @param array $hook_extra Update context.
 * @return void
 */
function black_widgets_clear_css_cache_after_update( $upgrader = null, $hook_extra = array() ) {
	if ( ! is_array( $hook_extra ) ) {
		return;
	}

	$type   = isset( $hook_extra['type'] ) ? $hook_extra['type'] : '';
	$action = isset( $hook_extra['action'] ) ? $hook_extra['action'] : '';

	if ( 'plugin' !== $type || 'update' !== $action ) {
		return;
	}

	// Bulk updates report 'plugins', single updates report 'plugin'.
	if ( isset( $hook_extra['plugins'] ) && is_array( $hook_extra['plugins'] ) ) {
		$plugins = $hook_extra['plugins'];
	} elseif ( isset( $hook_extra['plugin'] ) ) {
		$plugins = array( $hook_extra['plugin'] );
	} else {
		return;
	}

	if ( ! in_array( BLACK_WIDGETS_PLUGIN_BASENAME, $plugins, true ) ) {
		return;
	}

	black_widgets_clear_elementor_css_cache();
}
