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

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}


	public function enqueue_styles() {
		wp_enqueue_style( 'blakc-widgets-admin', BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/css/black-widgets-admin.css', array(), BLACK_WIDGETS_VERSION, 'all' );
	}


	public function enqueue_scripts() {
		wp_enqueue_script( 'black-widgets-admin', BLACK_WIDGETS_PLUGIN_URL . 'assets/admin/js/black-widgets-admin.js', array( 'jquery' ), BLACK_WIDGETS_VERSION, false );
	}


	public function addPluginAdminMenu() {
		$page_title = 'Black Widgets';
		$menu_title = 'Black Widgets';
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


	function sampleoptions_init_fn(){
		register_setting(
			'plugin_options',
			'plugin_options',
			'bw_polugin_options_validate'
		);

		add_settings_section(
			'black_widgets_settings_setting', // ID used to identify this section and with which to register options
			'General Settings',  // Title to be displayed on the administration page
			array( $this, 'section_text_fn' ), // Callback used to render the description of the section
			'black_widgets_settings_general_settings' // Page on which to add this section of options
		);


        add_settings_field(
            'bw_gsap_options',
            'JS → CDN',
            array( $this, 'bw_setting_chb2_gsap_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_gsap_options',
                'class' => 'gsap-option'
            ]
        );

        add_settings_field(
            'bw_option_gsap_cdn1',
            'GSAP CDN',
            array( $this, 'bw_setting_gsap_cdn1_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_option_gsap_cdn1',
                'class' => 'gsap-cdn'
            ]
        );

        add_settings_field(
            'bw_option_gsap_cdn2',
            'ScrollTrigger CDN',
            array( $this, 'bw_setting_gsap_cdn2_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_option_gsap_cdn2',
                'class' => 'gsap-cdn'
            ]
        );

        add_settings_field(
            'bw_option_gsap_cdn3',
            'TimelineMax CDN',
            array( $this, 'bw_setting_gsap_cdn3_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_option_gsap_cdn3',
                'class' => 'gsap-cdn'
            ]
        );

        add_settings_field(
            'bw_option_gsap_cdn4',
            'TweenMax CDN',
            array( $this, 'bw_setting_gsap_cdn4_fn' ),
            'black_widgets_settings_general_settings',
            'black_widgets_settings_setting',
            [
                'label_for' => 'bw_option_gsap_cdn4',
                'class' => 'gsap-cdn'
            ]
        );

	}


	function  section_text_fn() {
		echo null; // <p>Below are some examples of different option controls.</p>
	}


    function bw_setting_chb2_gsap_fn() {
        $checked = '';
        $checked_class = '';
        // $options = get_option('plugin_options');
        if ( get_option('plugin_options') ): $options = get_option('plugin_options'); else: $options = ''; endif;
        if(isset($options['gsap_options']) && $options['gsap_options']) { $checked = ' checked="checked" '; $checked_class = 'class="bw-checked"'; }
        echo "<input ".$checked." id='bw_gsap_options' name='plugin_options[gsap_options]' type='checkbox' $checked_class />"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<p> After enabling it, you can find new widgets in the Elementor environment. Additionally, some widgets will have new features enabled for your enjoyment. </p>';
    }


    function bw_setting_gsap_cdn1_fn() {
        $options = get_option('plugin_options');
        if ( get_option('plugin_options') ): $options = get_option('plugin_options'); else: $options = ''; endif;
        $cdn1 = (isset($options['bw_gsap_cdn1']) && $options['bw_gsap_cdn1']) ? $options['bw_gsap_cdn1'] : '';
        echo "<input id='bw_option_gsap_cdn1' name='plugin_options[bw_gsap_cdn1]' type='text' value='" . esc_url( $cdn1 ) . "' />";
    }


    function bw_setting_gsap_cdn2_fn() {
        $options = get_option('plugin_options');
        if ( get_option('plugin_options') ): $options = get_option('plugin_options'); else: $options = ''; endif;
        $cdn2 = (isset($options['bw_gsap_cdn2']) && $options['bw_gsap_cdn2']) ? $options['bw_gsap_cdn2'] : '';
        echo "<input id='bw_option_gsap_cdn2' name='plugin_options[bw_gsap_cdn2]' type='text' value='" . esc_url( $cdn2 ) . "' />";
    }


    function bw_setting_gsap_cdn3_fn() {
        $options = get_option('plugin_options');
        if ( get_option('plugin_options') ): $options = get_option('plugin_options'); else: $options = ''; endif;
        $cdn3 = (isset($options['bw_gsap_cdn3']) && $options['bw_gsap_cdn3']) ? $options['bw_gsap_cdn3'] : '';
        echo "<input id='bw_option_gsap_cdn3' name='plugin_options[bw_gsap_cdn3]' type='text' value='" . esc_url( $cdn3 ) . "' />";
    }


    function bw_setting_gsap_cdn4_fn() {
        $options = get_option('plugin_options');
        if ( get_option('plugin_options') ): $options = get_option('plugin_options'); else: $options = ''; endif;
        $cdn4 = (isset($options['bw_gsap_cdn4']) && $options['bw_gsap_cdn4']) ? $options['bw_gsap_cdn4'] : '';
        echo "<input id='bw_option_gsap_cdn4' name='plugin_options[bw_gsap_cdn4]' type='text' value='" . esc_url( $cdn4 ) . "' />";
    }


	function black_widgets_settings() {
		require_once( BLACK_WIDGETS_PLUGIN_PATH . 'includes/admin/black-widgets-settings.php');
	}

}
