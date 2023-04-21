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

		wp_enqueue_script('bw-jquery-plugins', plugin_dir_url( __FILE__ ) . 'js/bw-jquery-plugins.js', array('jquery'), '1.0.0', 'true' );
		wp_enqueue_script('bw-public', plugin_dir_url( __FILE__ ) . 'js/bw-public.js', array('jquery'), '1.0.0', 'true' );

        // Load options
        $options = get_option('plugin_options') ? get_option('plugin_options') : '';
        $gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';
        $bw_gsap_cdn1  = isset($options['bw_gsap_cdn1']) ? $options['bw_gsap_cdn1'] : '';
        $bw_gsap_cdn2  = isset($options['bw_gsap_cdn2']) ? $options['bw_gsap_cdn2'] : '';
        $bw_gsap_cdn3  = isset($options['bw_gsap_cdn3']) ? $options['bw_gsap_cdn3'] : '';
        $bw_gsap_cdn4  = isset($options['bw_gsap_cdn4']) ? $options['bw_gsap_cdn4'] : '';

        if( isset($gsap_options) && !empty($gsap_options) ) {
            if( isset($bw_gsap_cdn1) && !empty($bw_gsap_cdn1) ) {
                wp_register_script( 'GSAP', $bw_gsap_cdn1, array(), '1.2.5', 'true' );
                wp_enqueue_script('GSAP');
            }

            if( isset($bw_gsap_cdn2) && !empty($bw_gsap_cdn2) ) {
                wp_register_script( 'GSAP-ScrollTrigger', $bw_gsap_cdn2, array(), '1.2.5', 'true' );
                wp_enqueue_script('GSAP-ScrollTrigger');
            }

            if( isset($bw_gsap_cdn3) && !empty($bw_gsap_cdn3) ) {
                wp_register_script( 'TimelineMax', $bw_gsap_cdn3, array(), '1.2.5', 'true' );
                wp_enqueue_script('TimelineMax');
            }

            if( isset($bw_gsap_cdn4) && !empty($bw_gsap_cdn4) ) {
                wp_register_script( 'TweenLite', $bw_gsap_cdn4, array(), '1.2.5', 'true' );
                wp_enqueue_script('TweenLite');
            }

			wp_enqueue_script('bw-gsap-css-script', plugin_dir_url( __FILE__ ) . 'js/bw-gsap-css-script.js', array(), '1.2.5', 'true' );
            // wp_enqueue_script('bw-cdn-uses', plugin_dir_url( __FILE__ ) . 'js/bw-cdn-uses.js', array(), '1.2.5', 'true' );

        }

	}

}
