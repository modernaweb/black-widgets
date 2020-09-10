<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://modernaweb.net
 * @since      1.0.0
 *
 * @package    Black_Widgets
 * @subpackage Black_Widgets/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Black_Widgets
 * @subpackage Black_Widgets/admin
 * @author     Modernaweb <info@modernaweb.net>
 */
class Black_Widgets_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $black_widgets    The ID of this plugin.
	 */
	private $black_widgets;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $black_widgets       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $black_widgets, $version ) {

		$this->black_widgets = $black_widgets;
		$this->version = $version;

		add_action('admin_menu', array( $this, 'addPluginAdminMenu' ), 9);
		add_action('admin_init', array( $this, 'sampleoptions_init_fn' ));

		/** Admin Content */
		function black_widgets_options() {
			require_once( __DIR__ . '/black-widgets-control-panel.php');
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Black_Widgets_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Black_Widgets_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Load options
		if ( get_option('plugin_options') ): $options = get_option('plugin_options'); else: $options = ''; endif;
		$bw_dark_style  = isset($options['bw_dark_style']) ? $options['bw_dark_style'] : '';
		// var_dump( $options['bw_dark_style'] );
		if ( $bw_dark_style == 'on' ) {
			wp_enqueue_style( $this->black_widgets, plugin_dir_url( __FILE__ ) . 'css/black-widgets-elementor.css', array(), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->black_widgets, plugin_dir_url( __FILE__ ) . 'css/black-widgets-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Black_Widgets_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Black_Widgets_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->black_widgets, plugin_dir_url( __FILE__ ) . 'js/black-widgets-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function addPluginAdminMenu() {

		$page_title = 'Black Widgets';
		$menu_title = 'Black Widgets';
		$capability = 'manage_options';
		$menu_slug  = 'black-widgets';
		$function   = 'black_widgets_options';
		$icon_url   = plugin_dir_url(__FILE__ ) . 'img/bw.svg';
		$position   = 9;
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
			__( 'Dashboard', 'blackwidgets' ),
			__( 'Dashboard', 'blackwidgets' ),
			$capability,
			$menu_slug
		);

		add_submenu_page(
			$menu_slug,
			__( 'Settings', 'blackwidgets' ),
			__( 'Settings', 'blackwidgets' ),
			$capability,
			'black-widgets-settings',
			array($this, 'black_widgets_settings')
		);

    }

	public function blackwidgets() {
		require_once 'partials/'.'Black Widgets'.'-admin-display.php';
    }

	// Register our settings. Add the settings section, and settings fields
	function sampleoptions_init_fn(){
		register_setting(
			'plugin_options',
			'plugin_options',
			'bw_polugin_options_validate'
		);
		add_settings_section(
			'black_widgets_settings_example_setting', // ID used to identify this section and with which to register options
			'General Settings',  // Title to be displayed on the administration page
			array( $this, 'section_text_fn' ), // Callback used to render the description of the section
			'black_widgets_settings_general_settings' // Page on which to add this section of options
		);

		add_settings_field( // For add a new setting field : head over to 17 June 2020 1:30PM >> Settings Part IV << 3c64f36
			'bw_dark_style_chb1',
			'Black Elementor Environment',
			array( $this, 'bw_setting_chb1_fn' ),
			'black_widgets_settings_general_settings',
			'black_widgets_settings_example_setting'
		);
	}

	// Section HTML, displayed before the first option
	function  section_text_fn() {
		echo null; // <p>Below are some examples of different option controls.</p>
	}

	// CHECKBOX - Name: plugin_options[bw_dark_style]
	function bw_setting_chb1_fn() {
		$checked = '';
		// $options = get_option('plugin_options');
		if ( get_option('plugin_options') ): $options = get_option('plugin_options'); else: $options = ''; endif;
		if(isset($options['bw_dark_style']) && $options['bw_dark_style']) { $checked = ' checked="checked" '; }
		echo "<input ".$checked." id='bw_dark_style_chb1' name='plugin_options[bw_dark_style]' type='checkbox' />";
	}

	// Display the admin options page
	function black_widgets_settings() {
	
		require_once( __DIR__ . '/black-widgets-settings.php');

	}



}