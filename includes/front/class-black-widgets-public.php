<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://modernaweb.net
 * @since      1.0.0
 *
 * @package    Black_Widgets
 * @subpackage Black_Widgets/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Black_Widgets
 * @subpackage Black_Widgets/public
 * @author     Modernaweb <info@modernaweb.net>
 */
class Black_Widgets_Public {

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
	 * @param      string    $black_widgets       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $black_widgets, $version ) {

		$this->black_widgets = $black_widgets;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_enqueue_style( $this->black_widgets, plugin_dir_url( __FILE__ ) . 'css/black-widgets-public.css', array(), $this->version);
		// wp_enqueue_style( 'owl-carousel', plugin_dir_url( __FILE__ ) . 'css/black-widgets-min.css', array(), $this->version);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script('bw-jquery-plugins', plugin_dir_url( __FILE__ ) . 'js/bw-jquery-plugins.js', array(), '1.0.0', 'true' );
		wp_enqueue_script('bw-public', plugin_dir_url( __FILE__ ) . 'js/bw-public.js', array(), '1.0.0', 'true' );


	}

}
