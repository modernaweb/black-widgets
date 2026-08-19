<?php
namespace Modernaweb\BlackWidgets;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'addPluginAdminMenu' ), 9);
		add_action( 'admin_init', array( $this, 'sampleoptions_init_fn' ));
		add_action( 'admin_init', array( $this, 'maybe_upgrade_plugin_db_version' ) );
		add_action( 'admin_init', array( $this, 'maybe_archive_rejected_cdn_urls' ) );
		add_action( 'admin_init', array( $this, 'handle_dismiss_cdn_notice' ) );
		add_action( 'admin_init', array( $this, 'handle_dismiss_cdn_allowlist_notice' ) );
		add_action( 'admin_init', array( $this, 'handle_dismiss_cdn3_timelinemax_notice' ) );
		add_action( 'admin_notices', array( $this, 'render_cdn_migration_notice' ) );
		add_action( 'admin_notices', array( $this, 'render_cdn_allowlist_notice' ) );
		add_action( 'admin_notices', array( $this, 'render_cdn3_timelinemax_notice' ) );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_menu_icon_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Menu icon CSS must load on every admin screen (sidebar is global).
	 *
	 * @return void
	 */
	public function enqueue_menu_icon_styles() {
		wp_enqueue_style(
			'black-widgets-admin-menu',
			BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/css/black-widgets-admin-menu.css',
			array(),
			BLACK_WIDGETS_VERSION,
			'all'
		);
	}


	/**
	 * Whether the current admin screen belongs to Black Widgets.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 * @return bool
	 */
	protected function is_bw_admin_page( $hook_suffix ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

		if ( in_array( $page, [ 'black-widgets', 'black-widgets-settings' ], true ) ) {
			return true;
		}

		return in_array(
			$hook_suffix,
			[
				'toplevel_page_black-widgets',
				'black-widgets_page_black-widgets-settings',
			],
			true
		);
	}


	public function enqueue_styles( $hook_suffix = '' ) {
		if ( ! $this->is_bw_admin_page( $hook_suffix ) ) {
			return;
		}

		wp_enqueue_style( 'black-widgets-admin', BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/css/black-widgets-admin.css', array(), BLACK_WIDGETS_VERSION, 'all' );
	}


	public function enqueue_scripts( $hook_suffix = '' ) {
		if ( ! $this->is_bw_admin_page( $hook_suffix ) ) {
			return;
		}

		wp_enqueue_script( 'black-widgets-admin', BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/js/black-widgets-admin.js', array( 'jquery' ), BLACK_WIDGETS_VERSION, false );
	}


	public function addPluginAdminMenu() {
		$page_title = __( 'Black Widgets', 'black-widgets' );
		$menu_title = __( 'Black Widgets', 'black-widgets' );
		$capability = 'manage_options';
		$menu_slug  = 'black-widgets';
		$function   = 'black_widgets_options';
		$icon_url   = BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/img/bw.svg';
		$position   = 58;
		add_menu_page(
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			$function,
			$icon_url,
			$position
		);

		add_submenu_page(
			$menu_slug,
			__( 'Dashboard', 'black-widgets' ),
			__( 'Dashboard', 'black-widgets' ),
			$capability,
			$menu_slug
		);

		add_submenu_page(
			$menu_slug,
			__( 'Settings', 'black-widgets' ),
			__( 'Settings', 'black-widgets' ),
			$capability,
			'black-widgets-settings',
			array($this, 'black_widgets_settings')
		);

    }


    public function validate_options( $input ) {
        // Rebuild allow-listed options. Drop legacy keys (e.g. bw_dark_style).
        // Disabled GSAP child fields may be omitted from POST; keep prior CDN values.
        $prev  = Plugin_Options::all();
        $input = is_array( $input ) ? $input : [];
        $clean = [];

        $clean['gsap_options'] = ! empty( $input['gsap_options'] ) ? 1 : 0;

        // Feature flags: if GSAP is off, fields are disabled and may be omitted - keep prior.
        foreach ( [ 'bw_enable_scroll_text', 'bw_gsap_trigger_scope' ] as $flag ) {
            if ( array_key_exists( $flag, $input ) ) {
                $clean[ $flag ] = ! empty( $input[ $flag ] ) ? 1 : 0;
            } elseif ( $clean['gsap_options'] ) {
                // Toggle on but key missing (shouldn't happen) → treat as unchecked.
                $clean[ $flag ] = 0;
            } else {
                $clean[ $flag ] = ! empty( $prev[ $flag ] ) ? 1 : 0;
            }
        }

        // Default Scroll Text ON for fresh installs / first save with GSAP on and no prior key.
        if ( $clean['gsap_options'] && ! array_key_exists( 'bw_enable_scroll_text', $prev ) && ! array_key_exists( 'bw_enable_scroll_text', $input ) ) {
            $clean['bw_enable_scroll_text'] = 1;
        }

        foreach ( Plugin_Options::cdn_keys() as $field ) {
            $legacy_key = Plugin_Options::legacy_cdn_key( $field );
            $prev_url   = isset( $prev[ $field ] ) ? trim( (string) $prev[ $field ] ) : '';
            $archive    = isset( $prev[ $legacy_key ] ) ? trim( (string) $prev[ $legacy_key ] ) : '';

            if ( ! array_key_exists( $field, $input ) ) {
                // Field omitted from POST: keep the stored value.
                $clean[ $field ] = $prev_url;
            } else {
                $submitted     = trim( (string) $input[ $field ] );
                $sanitized     = $this->sanitize_gsap_cdn_url( $submitted );
                $prev_rejected = ( '' !== $prev_url && '' === $this->sanitize_gsap_cdn_url( $prev_url ) );

                if ( '' !== $sanitized ) {
                    $clean[ $field ] = $sanitized;
                    $archive         = '';
                } elseif ( $prev_rejected && ( '' === $submitted || $submitted === $prev_url ) ) {
                    // Rejected URLs render as an empty field, so an empty (or unchanged) submission
                    // cannot mean "clear it". Keep the raw value and archive it for the notice.
                    $clean[ $field ] = $prev_url;
                    $archive         = $prev_url;
                } else {
                    // URL the user just typed: sanitize_gsap_cdn_url() still decides what is stored,
                    // but archive the text so the notice can say why the field came back empty.
                    $clean[ $field ] = '';
                    if ( '' !== $submitted ) {
                        $archive = $submitted;
                    }
                }
            }

            if ( '' !== $archive ) {
                $clean[ $legacy_key ] = $archive;
            }
        }

        // Reference-only archive of the pre-1.4.0 TimelineMax URL - has no field, so the
        // allow-list rebuild above would drop it on the first save after the upgrade.
        $timelinemax_key     = Plugin_Options::legacy_timelinemax_key();
        $timelinemax_archive = isset( $prev[ $timelinemax_key ] ) ? trim( (string) $prev[ $timelinemax_key ] ) : '';
        if ( '' !== $timelinemax_archive ) {
            $clean[ $timelinemax_key ] = $timelinemax_archive;
        }

        return $clean;
    }

    /**
     * Allow only HTTPS CDN URLs on known hosts (filterable for custom CDNs).
     *
     * @param string $url Raw URL.
     * @return string
     */
    private function sanitize_gsap_cdn_url( $url ) {
        return Plugin_Options::sanitize_cdn_url( $url );
    }


	function sampleoptions_init_fn(){
		register_setting(
			'plugin_options',
			'plugin_options',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'validate_options' ),
				'default'           => Plugin_Options::defaults(),
				'show_in_rest'      => false,
				'capability'        => 'manage_options',
			)
		);

		add_settings_section(
			'black_widgets_settings_setting',
			__( 'General Settings', 'black-widgets' ),
			array( $this, 'section_text_fn' ),
			'black_widgets_settings_general_settings'
		);


        add_settings_field(
            'bw_gsap_options',
            __( 'JS → CDN', 'black-widgets' ),
            array( $this, 'bw_setting_chb2_gsap_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_gsap_options',
                'class' => 'gsap-option'
            ]
        );

        // Visible on first paint when master toggle is already on (no FOUC / no-JS fallback).
        $gsap_child_class = Plugin_Options::is_gsap_toggle_on() ? ' bw-gsap-child-visible' : '';

        add_settings_field(
            'bw_option_gsap_cdn1',
            __( 'GSAP CDN', 'black-widgets' ),
            array( $this, 'bw_setting_gsap_cdn1_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_option_gsap_cdn1',
                'class'     => 'gsap-cdn' . $gsap_child_class,
            ]
        );

        add_settings_field(
            'bw_option_gsap_cdn2',
            __( 'ScrollTrigger CDN', 'black-widgets' ),
            array( $this, 'bw_setting_gsap_cdn2_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_option_gsap_cdn2',
                'class'     => 'gsap-cdn' . $gsap_child_class,
            ]
        );

        add_settings_field(
            'bw_option_gsap_cdn3',
            __( 'SplitText CDN', 'black-widgets' ),
            array( $this, 'bw_setting_gsap_cdn3_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_option_gsap_cdn3',
                'class'     => 'gsap-cdn' . $gsap_child_class,
            ]
        );

        add_settings_field(
            'bw_option_gsap_cdn4',
            __( 'TweenMax CDN (Deprecated)', 'black-widgets' ),
            array( $this, 'bw_setting_gsap_cdn4_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_option_gsap_cdn4',
                'class'     => 'gsap-cdn gsap-cdn-deprecated' . $gsap_child_class,
            ]
        );

        add_settings_field(
            'bw_enable_scroll_text',
            __( 'Enable Black Scroll Text', 'black-widgets' ),
            array( $this, 'bw_setting_enable_scroll_text_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_enable_scroll_text',
                'class'     => 'gsap-dependent' . $gsap_child_class,
            ]
        );

        add_settings_field(
            'bw_gsap_trigger_scope',
            __( 'Scope GSAP Trigger selectors', 'black-widgets' ),
            array( $this, 'bw_setting_gsap_trigger_scope_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_gsap_trigger_scope',
                'class'     => 'gsap-dependent' . $gsap_child_class,
            ]
        );

	}


	function  section_text_fn() {
		echo '<p>' . esc_html__( 'Turn on JS → CDN to unlock the fields below. Add GSAP and ScrollTrigger URLs for scroll animations. Add SplitText only if you need character or word effects. TweenMax is optional and unused.', 'black-widgets' ) . '</p>';
	}


    /**
     * Disabled attribute for GSAP child fields when master toggle is off.
     */
    protected function gsap_child_disabled_attr() {
        return Plugin_Options::is_gsap_toggle_on() ? '' : ' disabled="disabled"';
    }

    function bw_setting_chb2_gsap_fn() {
        $checked       = Plugin_Options::is_gsap_toggle_on() ? ' checked="checked" ' : '';
        $checked_class = Plugin_Options::is_gsap_toggle_on() ? 'class="bw-checked"' : '';
        echo '<input ' . $checked . ' id="bw_gsap_options" name="plugin_options[gsap_options]" type="checkbox" ' . $checked_class . ' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<p>' . esc_html__( 'Shows GSAP widgets in Elementor and unlocks movement options. Add at least the GSAP CDN URL below. Some widgets also need ScrollTrigger or SplitText.', 'black-widgets' ) . '</p>';
    }


    function bw_setting_gsap_cdn1_fn() {
        $cdn1    = Plugin_Options::cdn( 1 );
        $example = 'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/gsap.min.js';
        $disabled = $this->gsap_child_disabled_attr();
        echo "<input id='bw_option_gsap_cdn1' name='plugin_options[bw_gsap_cdn1]' type='text' class='regular-text' value='" . esc_url( $cdn1 ) . "' placeholder='" . esc_attr( $example ) . "'{$disabled} />";
        echo '<p class="description">' . esc_html__( 'URL for the GSAP core file. Example:', 'black-widgets' ) . '</p>';
        echo '<p class="description"><code>' . esc_html( $example ) . '</code></p>';
    }


    function bw_setting_gsap_cdn2_fn() {
        $cdn2     = Plugin_Options::cdn( 2 );
        $example  = 'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/ScrollTrigger.min.js';
        $disabled = $this->gsap_child_disabled_attr();
        echo "<input id='bw_option_gsap_cdn2' name='plugin_options[bw_gsap_cdn2]' type='text' class='regular-text' value='" . esc_url( $cdn2 ) . "' placeholder='" . esc_attr( $example ) . "'{$disabled} />";
        echo '<p class="description">' . esc_html__( 'URL for ScrollTrigger. Example:', 'black-widgets' ) . '</p>';
        echo '<p class="description"><code>' . esc_html( $example ) . '</code></p>';
    }


    function bw_setting_gsap_cdn3_fn() {
        $cdn3     = Plugin_Options::cdn( 3 );
        $example  = 'https://cdn.jsdelivr.net/npm/gsap@3.15/dist/SplitText.min.js';
        $disabled = $this->gsap_child_disabled_attr();
        echo "<input id='bw_option_gsap_cdn3' name='plugin_options[bw_gsap_cdn3]' type='text' class='regular-text' value='" . esc_url( $cdn3 ) . "' placeholder='" . esc_attr( $example ) . "'{$disabled} />";
        echo '<p class="description">' . esc_html__( 'Optional. Needed for Perspective Flip on buttons and for text effects that split characters or words. Example:', 'black-widgets' ) . '</p>';
        echo '<p class="description"><code>' . esc_html( $example ) . '</code></p>';
    }


    function bw_setting_gsap_cdn4_fn() {
        $cdn4     = Plugin_Options::cdn( 4 );
        $disabled = $this->gsap_child_disabled_attr();
        echo "<input id='bw_option_gsap_cdn4' name='plugin_options[bw_gsap_cdn4]' type='text' class='regular-text' value='" . esc_url( $cdn4 ) . "'{$disabled} />";
        echo '<p class="description">' . esc_html__( 'Deprecated. Leave empty. Black Widgets does not load this file.', 'black-widgets' ) . '</p>';
    }

    function bw_setting_enable_scroll_text_fn() {
        $enabled  = Plugin_Options::is_scroll_text_enabled();
        $checked  = $enabled ? ' checked="checked" ' : '';
        $disabled = $this->gsap_child_disabled_attr();
        echo "<input {$checked} id='bw_enable_scroll_text' name='plugin_options[bw_enable_scroll_text]' type='checkbox'{$disabled} />";
        echo '<p class="description">' . esc_html__( 'Show the Black Scroll Text widget in Elementor. Typography On Scroll still works if this is off.', 'black-widgets' ) . '</p>';
    }

    function bw_setting_gsap_trigger_scope_fn() {
        $checked  = Plugin_Options::is_gsap_trigger_scoped() ? ' checked="checked" ' : '';
        $disabled = $this->gsap_child_disabled_attr();
        echo "<input {$checked} id='bw_gsap_trigger_scope' name='plugin_options[bw_gsap_trigger_scope]' type='checkbox'{$disabled} />";
        echo '<p class="description">' . esc_html__( 'Limit Trigger selectors to the current widget. Off uses the same global selectors as before. You can also set this per widget.', 'black-widgets' ) . '</p>';
    }


	function black_widgets_settings() {
		require_once( BLACK_WIDGETS_PLUGIN_PATH . 'includes/admin/black-widgets-settings.php');
	}

	/**
	 * Compare stored DB version with the plugin version and flag the one-time CDN notice.
	 */
	public function maybe_upgrade_plugin_db_version() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Soft-remove legacy Dark Mode flag (UI removed in 1.4.0) without waiting for Settings save.
		$this->maybe_strip_legacy_dark_mode_option();

		$stored  = get_option( 'bw_plugin_db_version', '0' );
		$current = BLACK_WIDGETS_VERSION;

		if ( version_compare( (string) $stored, (string) $current, '>=' ) ) {
			return;
		}

		// Upgrading from before 1.4.0: queue the CDN migration notice once.
		if ( version_compare( (string) $stored, '1.4.0', '<' ) ) {
			if ( ! get_option( 'bw_cdn_notice_dismissed' ) ) {
				update_option( 'bw_show_cdn_migration_notice', '1', false );
			}

			$this->migrate_legacy_timelinemax_cdn();
		}

		$this->clear_elementor_css_cache();

		update_option( 'bw_plugin_db_version', $current, false );
	}

	/**
	 * Drop Elementor's pre-generated CSS files after a plugin upgrade.
	 *
	 * Several stored controls changed their `selector` in 1.4.0 (List, Icon Box hover,
	 * Flip Box), so pages keep their old rules until each post CSS file is rebuilt.
	 * Runs on admin_init, which is after Elementor builds files_manager on init.
	 *
	 * @return void
	 */
	protected function clear_elementor_css_cache() {
		if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		if ( ! isset( \Elementor\Plugin::$instance->files_manager ) ) {
			return;
		}

		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	/**
	 * CDN field 3 was "TimelineMax CDN" before 1.4.0 and is now registered as
	 * GSAP-SplitText. Reusing the stored value would turn on SplitText-only options
	 * while shipping a GSAP 2 file, so archive the URL and hand the field back empty.
	 *
	 * @return void
	 */
	protected function migrate_legacy_timelinemax_cdn() {
		$options = Plugin_Options::all();
		$url     = isset( $options['bw_gsap_cdn3'] ) ? trim( (string) $options['bw_gsap_cdn3'] ) : '';

		if ( '' === $url ) {
			return;
		}

		$legacy_key = Plugin_Options::legacy_timelinemax_key();
		if ( ! isset( $options[ $legacy_key ] ) || '' === trim( (string) $options[ $legacy_key ] ) ) {
			$options[ $legacy_key ] = $url;
		}

		$options['bw_gsap_cdn3'] = '';

		// The allowlist archive would otherwise keep offering the same URL back as CDN 3.
		unset( $options[ Plugin_Options::legacy_cdn_key( 'bw_gsap_cdn3' ) ] );

		// No $autoload argument: plugin_options must stay autoloaded (read on every front-end load).
		update_option( 'plugin_options', $options );

		if ( ! get_option( 'bw_cdn3_timelinemax_notice_dismissed' ) ) {
			update_option( 'bw_show_cdn3_timelinemax_notice', '1', false );
		}
	}

	/**
	 * Permanently dismiss the CDN 3 (TimelineMax → SplitText) notice.
	 */
	public function handle_dismiss_cdn3_timelinemax_notice() {
		if ( ! isset( $_GET['bw_dismiss_cdn3_timelinemax_notice'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'bw_dismiss_cdn3_timelinemax_notice' );

		update_option( 'bw_cdn3_timelinemax_notice_dismissed', '1', false );
		delete_option( 'bw_show_cdn3_timelinemax_notice' );

		wp_safe_redirect( remove_query_arg( array( 'bw_dismiss_cdn3_timelinemax_notice', '_wpnonce' ) ) );
		exit;
	}

	/**
	 * One-time notice explaining why CDN field 3 is empty after the upgrade.
	 */
	public function render_cdn3_timelinemax_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! get_option( 'bw_show_cdn3_timelinemax_notice' ) || get_option( 'bw_cdn3_timelinemax_notice_dismissed' ) ) {
			return;
		}

		$archived     = Plugin_Options::legacy_timelinemax_url();
		$settings_url = admin_url( 'admin.php?page=black-widgets-settings' );
		$dismiss_url  = wp_nonce_url(
			add_query_arg( 'bw_dismiss_cdn3_timelinemax_notice', '1' ),
			'bw_dismiss_cdn3_timelinemax_notice'
		);
		?>
		<div class="notice notice-warning bw-cdn3-timelinemax-notice">
			<p>
				<strong><?php esc_html_e( 'Black Widgets: SplitText CDN (was TimelineMax)', 'black-widgets' ); ?></strong>
			</p>
			<p>
				<?php esc_html_e( 'In 1.4.0 the third CDN field is SplitText, not TimelineMax. Your old TimelineMax URL was saved for reference and removed from this field so the wrong file is not loaded.', 'black-widgets' ); ?>
			</p>
			<?php if ( '' !== $archived ) : ?>
				<p>
					<?php esc_html_e( 'Saved copy (plugin_options key):', 'black-widgets' ); ?>
				</p>
				<ul>
					<li>
						<strong><code><?php echo esc_html( Plugin_Options::legacy_timelinemax_key() ); ?></code>:</strong>
						<code><?php echo esc_html( $archived ); ?></code>
					</li>
				</ul>
			<?php endif; ?>
			<p>
				<?php esc_html_e( 'If you use Perspective Flip or split-text effects, paste a SplitText CDN URL. If not, leave the field empty.', 'black-widgets' ); ?>
			</p>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( $settings_url ); ?>">
					<?php esc_html_e( 'View Settings', 'black-widgets' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $dismiss_url ); ?>">
					<?php esc_html_e( 'Dismiss', 'black-widgets' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Drop bw_dark_style from plugin_options if present.
	 *
	 * @return void
	 */
	protected function maybe_strip_legacy_dark_mode_option() {
		$options = Plugin_Options::all();
		if ( ! array_key_exists( 'bw_dark_style', $options ) ) {
			return;
		}

		unset( $options['bw_dark_style'] );
		// No $autoload argument: plugin_options is read on every front-end load and must stay autoloaded.
		update_option( 'plugin_options', $options );
	}

	/**
	 * Permanently dismiss the CDN migration notice (nonce link, not a transient).
	 */
	public function handle_dismiss_cdn_notice() {
		if ( ! isset( $_GET['bw_dismiss_cdn_notice'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'bw_dismiss_cdn_notice' );

		update_option( 'bw_cdn_notice_dismissed', '1', false );
		delete_option( 'bw_show_cdn_migration_notice' );

		wp_safe_redirect( remove_query_arg( array( 'bw_dismiss_cdn_notice', '_wpnonce' ) ) );
		exit;
	}

	/**
	 * One-time admin notice after updating toward GSAP 3.15 CDN setup.
	 */
	public function render_cdn_migration_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! get_option( 'bw_show_cdn_migration_notice' ) || get_option( 'bw_cdn_notice_dismissed' ) ) {
			return;
		}

		$settings_url = admin_url( 'admin.php?page=black-widgets-settings' );
		$dismiss_url  = wp_nonce_url(
			add_query_arg( 'bw_dismiss_cdn_notice', '1' ),
			'bw_dismiss_cdn_notice'
		);
		?>
		<div class="notice notice-info bw-cdn-migration-notice">
			<p>
				<strong><?php esc_html_e( 'Black Widgets 1.4.0', 'black-widgets' ); ?></strong>
			</p>
			<p>
				<?php esc_html_e( 'GSAP animations work with GSAP 3.15 (core, ScrollTrigger, and SplitText when needed). TweenMax is no longer used.', 'black-widgets' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'If animations stop after updating:', 'black-widgets' ); ?>
			</p>
			<ol>
				<li><?php esc_html_e( 'Open Settings and set GSAP and ScrollTrigger to the 3.15 examples shown there. Saved URLs are not changed for you.', 'black-widgets' ); ?></li>
				<li><?php esc_html_e( 'Clear your site cache and Elementor CSS/JS cache.', 'black-widgets' ); ?></li>
			</ol>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( $settings_url ); ?>">
					<?php esc_html_e( 'View Settings', 'black-widgets' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $dismiss_url ); ?>">
					<?php esc_html_e( 'Dismiss', 'black-widgets' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Copy CDN URLs the allowlist rejects into their *_legacy keys, so a URL saved
	 * before 1.4.0 stays recoverable even if the empty Settings field is saved over it.
	 *
	 * @return void
	 */
	public function maybe_archive_rejected_cdn_urls() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$options = Plugin_Options::all();
		$changed = false;

		foreach ( Plugin_Options::cdn_keys() as $key ) {
			$url = isset( $options[ $key ] ) ? trim( (string) $options[ $key ] ) : '';
			if ( '' === $url || '' !== Plugin_Options::sanitize_cdn_url( $url ) ) {
				continue;
			}

			$legacy_key = Plugin_Options::legacy_cdn_key( $key );
			if ( isset( $options[ $legacy_key ] ) && trim( (string) $options[ $legacy_key ] ) === $url ) {
				continue;
			}

			$options[ $legacy_key ] = $url;
			$changed                = true;
		}

		if ( $changed ) {
			// No $autoload argument: plugin_options must stay autoloaded (read on every front-end load).
			update_option( 'plugin_options', $options );
		}
	}

	/**
	 * Field labels for the CDN option keys, matching the Settings screen.
	 *
	 * @return array<string, string>
	 */
	protected function cdn_field_labels() {
		return array(
			'bw_gsap_cdn1' => __( 'GSAP CDN', 'black-widgets' ),
			'bw_gsap_cdn2' => __( 'ScrollTrigger CDN', 'black-widgets' ),
			'bw_gsap_cdn3' => __( 'SplitText CDN', 'black-widgets' ),
			'bw_gsap_cdn4' => __( 'TweenMax CDN (Deprecated)', 'black-widgets' ),
		);
	}

	/**
	 * Identity of the current rejected set, so dismissing hides only these URLs and the
	 * notice returns when a different URL is rejected later.
	 *
	 * @param array<string, string> $rejected CDN option key => raw URL.
	 * @return string
	 */
	protected function cdn_allowlist_notice_fingerprint( array $rejected ) {
		ksort( $rejected );
		return md5( (string) wp_json_encode( $rejected ) );
	}

	/**
	 * Dismiss the allowlist notice for the URLs currently rejected.
	 */
	public function handle_dismiss_cdn_allowlist_notice() {
		if ( ! isset( $_GET['bw_dismiss_cdn_allowlist_notice'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		check_admin_referer( 'bw_dismiss_cdn_allowlist_notice' );

		update_option(
			'bw_gsap_cdn_allowlist_notice_dismissed',
			$this->cdn_allowlist_notice_fingerprint( Plugin_Options::rejected_cdn_urls() ),
			false
		);

		wp_safe_redirect( remove_query_arg( array( 'bw_dismiss_cdn_allowlist_notice', '_wpnonce' ) ) );
		exit;
	}

	/**
	 * Explain saved CDN URLs that are kept in the database but not loaded because they
	 * fail the HTTPS / allowlisted host rule.
	 */
	public function render_cdn_allowlist_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$rejected = Plugin_Options::rejected_cdn_urls();
		if ( empty( $rejected ) ) {
			return;
		}

		$dismissed = (string) get_option( 'bw_gsap_cdn_allowlist_notice_dismissed', '' );
		if ( '' !== $dismissed && $dismissed === $this->cdn_allowlist_notice_fingerprint( $rejected ) ) {
			return;
		}

		$labels       = $this->cdn_field_labels();
		$settings_url = admin_url( 'admin.php?page=black-widgets-settings' );
		$dismiss_url  = wp_nonce_url(
			add_query_arg( 'bw_dismiss_cdn_allowlist_notice', '1' ),
			'bw_dismiss_cdn_allowlist_notice'
		);
		?>
		<div class="notice notice-warning bw-cdn-allowlist-notice">
			<p>
				<strong><?php esc_html_e( 'Black Widgets: some CDN URLs were not loaded', 'black-widgets' ); ?></strong>
			</p>
			<p>
				<?php esc_html_e( 'These URLs are still saved, but they were skipped (must be HTTPS on an allowed host). The Settings fields look empty for that reason.', 'black-widgets' ); ?>
			</p>
			<ul>
				<?php foreach ( $rejected as $field => $url ) : ?>
					<li>
						<strong><?php echo esc_html( isset( $labels[ $field ] ) ? $labels[ $field ] : $field ); ?>:</strong>
						<code><?php echo esc_html( $url ); ?></code>
					</li>
				<?php endforeach; ?>
			</ul>
			<p>
				<?php esc_html_e( 'Paste an HTTPS URL from an allowed CDN (see the examples under each field), or ask a developer to allow your own host.', 'black-widgets' ); ?>
			</p>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( $settings_url ); ?>">
					<?php esc_html_e( 'View Settings', 'black-widgets' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $dismiss_url ); ?>">
					<?php esc_html_e( 'Dismiss', 'black-widgets' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

}
