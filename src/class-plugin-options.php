<?php
namespace Modernaweb\BlackWidgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Central accessor for plugin_options / GSAP CDN readiness.
 *
 * Replaces scattered get_option( 'plugin_options' ) reads across Main and widgets.
 */
final class Plugin_Options {

	/**
	 * Documented defaults for Settings API (flat array kept for BC with existing installs).
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults() {
		return [
			'gsap_options'          => 0,
			'bw_gsap_cdn1'          => '',
			'bw_gsap_cdn2'          => '',
			'bw_gsap_cdn3'          => '',
			'bw_gsap_cdn4'          => '',
			'bw_enable_scroll_text' => 1,
			'bw_gsap_trigger_scope' => 0,
		];
	}

	/**
	 * Full options array (always an array).
	 *
	 * @return array<string, mixed>
	 */
	public static function all() {
		$options = get_option( 'plugin_options', [] );
		return is_array( $options ) ? $options : [];
	}

	/**
	 * @param string $key     Option key.
	 * @param mixed  $default Default when missing.
	 * @return mixed
	 */
	public static function get( $key, $default = '' ) {
		$options = self::all();
		return array_key_exists( $key, $options ) ? $options[ $key ] : $default;
	}

	/**
	 * Master JS CDN checkbox (gsap_options).
	 */
	public static function is_gsap_toggle_on() {
		return ! empty( self::get( 'gsap_options' ) );
	}

	/**
	 * Allowed hosts for GSAP CDN script URLs (filterable).
	 *
	 * @return string[]
	 */
	public static function allowed_cdn_hosts() {
		$hosts = apply_filters(
			'black_widgets_allowed_gsap_cdn_hosts',
			[
				'cdn.jsdelivr.net',
				'cdnjs.cloudflare.com',
				'unpkg.com',
				'gsap.com',
				'cdn.gsap.com',
				's3-us-west-2.amazonaws.com',
			]
		);
		return is_array( $hosts ) ? $hosts : [];
	}

	/**
	 * Whether a host matches an allowlist entry, exactly or as a subdomain.
	 *
	 * @param mixed    $host          Host from wp_parse_url() (null when unparseable).
	 * @param string[] $allowed_hosts Allowlist entries.
	 * @return bool
	 */
	private static function host_matches_allowlist( $host, array $allowed_hosts ) {
		$host = strtolower( (string) $host );
		if ( '' === $host ) {
			return false;
		}

		foreach ( $allowed_hosts as $allowed ) {
			$allowed = strtolower( (string) $allowed );
			if ( '' === $allowed ) {
				continue;
			}
			if ( $host === $allowed || substr( $host, -( strlen( $allowed ) + 1 ) ) === '.' . $allowed ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Sanitize a GSAP CDN URL: HTTPS + allowlisted host.
	 *
	 * @param string $url Raw URL.
	 * @return string
	 */
	public static function sanitize_cdn_url( $url ) {
		$url = trim( (string) $url );
		if ( '' === $url ) {
			return '';
		}

		$allowed_hosts = self::allowed_cdn_hosts();

		/*
		 * Versions up to 1.3.9 saved this field without sanitizing it, so http:// URLs
		 * can exist. Upgrade them to https:// for allowlisted hosts instead of dropping
		 * them, which would silently disable the animations. Other hosts fall through
		 * to the https-only check below.
		 */
		if ( 0 === stripos( $url, 'http://' ) && self::host_matches_allowlist( wp_parse_url( $url, PHP_URL_HOST ), $allowed_hosts ) ) {
			$url = 'https://' . substr( $url, strlen( 'http://' ) );
		}

		$url = esc_url_raw( $url, [ 'https' ] );
		if ( '' === $url ) {
			return '';
		}

		$host = wp_parse_url( $url, PHP_URL_HOST );
		if ( ! is_string( $host ) || '' === $host ) {
			return '';
		}

		// Empty allowlist (via filter) = https-only mode.
		if ( empty( $allowed_hosts ) ) {
			return $url;
		}

		return self::host_matches_allowlist( $host, $allowed_hosts ) ? $url : '';
	}

	/**
	 * Option keys holding GSAP CDN URLs, in field order.
	 *
	 * @return string[]
	 */
	public static function cdn_keys() {
		return [ 'bw_gsap_cdn1', 'bw_gsap_cdn2', 'bw_gsap_cdn3', 'bw_gsap_cdn4' ];
	}

	/**
	 * CDN keys the plugin actually loads as a widget dependency.
	 *
	 * Field 4 (TweenMax) is deprecated and never enqueued, so a value the allowlist
	 * refuses there breaks nothing and must not be reported as lost animations.
	 *
	 * @return string[]
	 */
	public static function loaded_cdn_keys() {
		return [ 'bw_gsap_cdn1', 'bw_gsap_cdn2', 'bw_gsap_cdn3' ];
	}

	/**
	 * Companion key archiving a raw URL the current allowlist rejects.
	 *
	 * @param string $key CDN option key.
	 * @return string
	 */
	public static function legacy_cdn_key( $key ) {
		return $key . '_legacy';
	}

	/**
	 * Archive key for the value CDN field 3 held before 1.4.0, when it was the
	 * "TimelineMax CDN" field instead of SplitText.
	 *
	 * @return string
	 */
	public static function legacy_timelinemax_key() {
		return 'bw_gsap_cdn3_legacy_timelinemax';
	}

	/**
	 * Archived pre-1.4.0 TimelineMax URL, kept for reference only (never enqueued).
	 *
	 * @return string
	 */
	public static function legacy_timelinemax_url() {
		return trim( (string) self::get( self::legacy_timelinemax_key(), '' ) );
	}

	/**
	 * Sanitized CDN URL or empty string.
	 *
	 * @param int $n 1-4.
	 */
	public static function cdn( $n ) {
		$key = 'bw_gsap_cdn' . (int) $n;
		$url = self::get( $key, '' );
		return $url ? self::sanitize_cdn_url( (string) $url ) : '';
	}

	/**
	 * Stored CDN URLs (live or archived) that sanitize_cdn_url() refuses, so the
	 * Settings screen can explain the empty fields instead of losing the data.
	 *
	 * Limited to the keys that are actually loaded - the deprecated field 4 is skipped
	 * because "restore animations by pasting an HTTPS URL" would be false advice there.
	 *
	 * @return array<string, string> CDN option key => raw URL.
	 */
	public static function rejected_cdn_urls() {
		$options  = self::all();
		$rejected = [];

		foreach ( self::loaded_cdn_keys() as $key ) {
			foreach ( [ $key, self::legacy_cdn_key( $key ) ] as $candidate ) {
				$url = isset( $options[ $candidate ] ) ? trim( (string) $options[ $candidate ] ) : '';
				if ( '' === $url || '' !== self::sanitize_cdn_url( $url ) ) {
					continue;
				}

				// A live rejected value wins over its archived copy.
				$rejected[ $key ] = $url;
				break;
			}
		}

		return $rejected;
	}

	/**
	 * GSAP toggle on + core CDN present.
	 */
	public static function has_gsap_core() {
		return self::is_gsap_toggle_on() && self::cdn( 1 );
	}

	/**
	 * Standard GSAP readiness used by most widgets (core + ScrollTrigger).
	 * Same semantics as historical is_gsap_enabled().
	 */
	public static function is_gsap_ready() {
		return self::is_gsap_toggle_on() && self::cdn( 1 ) && self::cdn( 2 );
	}

	/**
	 * GSAP + ScrollTrigger + SplitText (Scroll Heat, Perspective Flip, etc.).
	 */
	public static function is_gsap_split_ready() {
		return self::is_gsap_ready() && self::cdn( 3 );
	}

	/**
	 * SplitText CDN present (assumes core already checked by caller when needed).
	 */
	public static function has_split_text() {
		return (bool) self::cdn( 3 );
	}

	/**
	 * Black Scroll Text feature flag (default ON when never saved).
	 */
	public static function is_scroll_text_enabled() {
		$options = self::all();
		if ( ! array_key_exists( 'bw_enable_scroll_text', $options ) ) {
			return true;
		}
		return ! empty( $options['bw_enable_scroll_text'] );
	}

	/**
	 * Scope GSAP Trigger selectors flag (default OFF).
	 */
	public static function is_gsap_trigger_scoped() {
		return ! empty( self::get( 'bw_gsap_trigger_scope' ) );
	}

	/**
	 * Register the bundled Swiper 11 assets without leaking window.Swiper
	 * when another copy is already on the page.
	 */
	public static function register_swiper_assets() {
		if ( ! wp_script_is( 'black-widgets-swiper', 'registered' ) ) {
			wp_register_script(
				'black-widgets-swiper',
				BLACK_WIDGETS_PLUGIN_URL . 'assets/js/libraries/swiper-bundle.min.js',
				[],
				BLACK_WIDGETS_VERSION,
				true
			);
		}
		if ( ! wp_style_is( 'black-widgets-swiper', 'registered' ) ) {
			wp_register_style(
				'black-widgets-swiper',
				BLACK_WIDGETS_PLUGIN_URL . 'assets/css/libraries/swiper-bundle.min.css',
				[],
				BLACK_WIDGETS_VERSION
			);
		}

		static $guarded = false;
		if ( $guarded || ! wp_script_is( 'black-widgets-swiper', 'registered' ) ) {
			return;
		}
		$guarded = true;

		wp_add_inline_script( 'black-widgets-swiper', 'window.__bwPrevSwiper=window.Swiper;', 'before' );
		wp_add_inline_script(
			'black-widgets-swiper',
			'window.BlackWidgetsSwiper=window.Swiper;if(window.__bwPrevSwiper){window.Swiper=window.__bwPrevSwiper;}delete window.__bwPrevSwiper;',
			'after'
		);
	}

	/**
	 * Snapshot/restore a window global around a classic script tag.
	 *
	 * @param string $handle WP script handle.
	 * @param string $prop   window property name (gsap, ScrollTrigger, SplitText).
	 */
	private static function preserve_window_global( $handle, $prop ) {
		static $attached = [];

		$prop = preg_replace( '/[^A-Za-z0-9_]/', '', (string) $prop );
		if ( '' === $prop || ! wp_script_is( $handle, 'registered' ) ) {
			return;
		}

		$key = $handle . '|' . $prop;
		if ( isset( $attached[ $key ] ) ) {
			return;
		}
		$attached[ $key ] = true;

		wp_add_inline_script( $handle, 'window.__bwHad' . $prop . '=window.' . $prop . ';', 'before' );
		wp_add_inline_script(
			$handle,
			'if(window.__bwHad' . $prop . '){window.' . $prop . '=window.__bwHad' . $prop . ';}delete window.__bwHad' . $prop . ';',
			'after'
		);
	}

	/**
	 * Register GSAP script handles from CDN settings when the master toggle is on.
	 * Safe to call multiple times (skips already-registered handles).
	 */
	public static function register_gsap_scripts() {
		if ( ! self::is_gsap_toggle_on() ) {
			return;
		}

		$cdn1 = self::cdn( 1 );
		$cdn2 = self::cdn( 2 );
		$cdn3 = self::cdn( 3 );
		$cdn4 = self::cdn( 4 );

		if ( $cdn1 ) {
			if ( ! wp_script_is( 'GSAP', 'registered' ) ) {
				wp_register_script( 'GSAP', $cdn1, [], BLACK_WIDGETS_VERSION, true );
			}
			self::preserve_window_global( 'GSAP', 'gsap' );
		}

		if ( $cdn2 ) {
			if ( ! wp_script_is( 'GSAP-ScrollTrigger', 'registered' ) ) {
				wp_register_script( 'GSAP-ScrollTrigger', $cdn2, [ 'GSAP' ], BLACK_WIDGETS_VERSION, true );
			}
			self::preserve_window_global( 'GSAP-ScrollTrigger', 'ScrollTrigger' );
		}

		if ( $cdn3 ) {
			if ( ! wp_script_is( 'GSAP-SplitText', 'registered' ) ) {
				wp_register_script( 'GSAP-SplitText', $cdn3, [ 'GSAP' ], BLACK_WIDGETS_VERSION, true );
			}
			self::preserve_window_global( 'GSAP-SplitText', 'SplitText' );
		}

		// Deprecated soft-compat - not used as a widget dependency.
		if ( $cdn4 && ! wp_script_is( 'TweenLite', 'registered' ) ) {
			wp_register_script( 'TweenLite', $cdn4, [], BLACK_WIDGETS_VERSION, true );
		}
	}
}
