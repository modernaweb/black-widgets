<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use enshrined\svgSanitize\Sanitizer;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Typography extends \Elementor\Widget_Base {

    protected $script_deps = [];

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-typography', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/typography.css', [], BLACK_WIDGETS_VERSION );
        // Since most of the js is created dynamically we use inline js
        
        $options = get_option('plugin_options') ? get_option('plugin_options') : '';
        $gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';
        $bw_gsap_cdn1  = isset($options['bw_gsap_cdn1']) ? $options['bw_gsap_cdn1'] : '';
        $bw_gsap_cdn2  = isset($options['bw_gsap_cdn2']) ? $options['bw_gsap_cdn2'] : '';
        $bw_gsap_cdn3  = isset($options['bw_gsap_cdn3']) ? $options['bw_gsap_cdn3'] : '';

        if( isset($gsap_options) && !empty($gsap_options) ) {

            if( !isset($bw_gsap_cdn1) || empty($bw_gsap_cdn1) ) {
                return;
            }

            if( !isset($bw_gsap_cdn2) || empty($bw_gsap_cdn2) ) {
                return;
            }

            if( !isset($bw_gsap_cdn3) || empty($bw_gsap_cdn3) ) {
                return;
            }

		    wp_register_script( 'black-widgets-typography', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/typography.js', [ 'GSAP', 'GSAP-ScrollTrigger', 'TimelineMax' ], BLACK_WIDGETS_VERSION );
            $this->script_deps = [ 'black-widgets-typography' ];
        }
    }

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'b_typography';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Black Typography', 'black-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-editor-bold';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the button widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'black_widgets' ];
	}

    public function get_style_depends() {
        return [ 'black-widgets-typography' ];
    }

    public function get_script_depends() {
        return $this->script_deps;
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$options = get_option('plugin_options') ? get_option('plugin_options') : '';
		$gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';

		// Start
		// Content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_panel_alert',
			[
				'type' => \Elementor\Controls_Manager::ALERT,
				'alert_type' => 'info',     /* info, success, warning, danger */
				'heading' => esc_html__( 'Feel free to edit. Check this widget\'s demo.', 'black-widgets' ),
				'content' => sprintf(
					'%s <a href="https://modernaweb.net/black-widgets/all-widgets/black-typography/" target="_blank">%s</a>',
					esc_html__( 'Check ', 'black-widgets' ),
					esc_html__( 'Demo', 'black-widgets' )
				),
			]
		);

		// Select type of the typography you want
		$this->add_control(
			'widget_type',
			[
				'label' => esc_html__( 'Select Type', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'bw-t-1',
				'options' => [
					'bw-t-1' 	=> esc_html__( 'Simple', 'black-widgets' ),
					'bw-t-2' 	=> esc_html__( 'With Shapes', 'black-widgets' ),
					'bw-t-3' 	=> esc_html__( 'Repetitive', 'black-widgets' ),
					'bw-t-4' 	=> esc_html__( 'On Scroll', 'black-widgets' ),
				],
			]
		);

		// Type title
		$this->add_control(
			'widget_title',
			[
				'label' => esc_html__( 'Title', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Black Widget Title', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your title here', 'black-widgets' ),
				'description' => esc_html__( 'You can use all other HTML tags into the title field e.g. code, mark, abbr, blockquote and ...', 'black-widgets' ),
			]
		);

		$this->add_control(
			'shape_widget',
			[
				'label' => esc_html__( 'SVG Shape', 'black-widgets' ),
				'type' => Controls_Manager::ICONS,
				'condition'  => [
					'widget_type' => [
						'bw-t-1',
					],
				],
				'description' => esc_html__( 'Upload your custom SVG shape, then add <em> and </em> around of the text! you\'ll see the shape under the text with a simple animate.', 'black-widgets' ),
			]
		);

		// Select tag
		$this->add_control(
			'widget_html_tag_title',
			[
				'label' => esc_html__( 'HTML Tag', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'div',
				'options' => [
					'div' 	=> esc_html__( 'div', 'black-widgets' ),
					'h1' 	=> esc_html__( 'H1', 'black-widgets' ),
					'h2' 	=> esc_html__( 'H2', 'black-widgets' ),
					'h3' 	=> esc_html__( 'H3', 'black-widgets' ),
					'h4' 	=> esc_html__( 'H4', 'black-widgets' ),
					'h5' 	=> esc_html__( 'H5', 'black-widgets' ),
					'h6' 	=> esc_html__( 'H6', 'black-widgets' ),
					'p' 	=> esc_html__( 'p', 'black-widgets' ),
					'span' 	=> esc_html__( 'span', 'black-widgets' ),
				],
				'description' => esc_html__( 'Choose an HTML tag, it can help you to SEO and beautifully of the UI design with follow the structure of your website.', 'black-widgets' ),
			]
		);

        // With Shape 
		// Select type of the typography you want
		$this->add_control(
			'widget_type_2',
			[
				'label' => esc_html__( 'Select Type', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'style-x-1',
				'options' => [
					'style-x-1' 	=> esc_html__( 'Style 1', 'black-widgets' ),
					'style-x-2' 	=> esc_html__( 'Style 2', 'black-widgets' ),
					'style-x-3' 	=> esc_html__( 'Style 3', 'black-widgets' ),
					'style-x-4' 	=> esc_html__( 'Style 4', 'black-widgets' ),
					'style-x-5' 	=> esc_html__( 'Style 5', 'black-widgets' ),
					'style-x-6' 	=> esc_html__( 'Style 6', 'black-widgets' ),
					'custom-style' 	=> esc_html__( 'Custom Style', 'black-widgets' ),
				],
				'condition'  => [
					'widget_type' => [
						'bw-t-2',
					],
				],
			]
		);

		$options = get_option('plugin_options') ? get_option('plugin_options') : '';
		$gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';
		if( isset($gsap_options) && !empty($gsap_options) ) {
			// Go on scroll 
			// Select type of the typography you want
			$this->add_control(
				'widget_type_4',
				[
					'label' => esc_html__( 'Select Animate On Scroll', 'black-widgets' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'bw-scroll-e-1',
					'options' => [
						'bw-scroll-e-1' 	=> esc_html__( 'Animate 1', 'black-widgets' ),
						'bw-scroll-e-2' 	=> esc_html__( 'Animate 2', 'black-widgets' ),
						'bw-scroll-e-3' 	=> esc_html__( 'Animate 3', 'black-widgets' ),
						'bw-scroll-e-4' 	=> esc_html__( 'Animate 4', 'black-widgets' ),
						'bw-scroll-e-5' 	=> esc_html__( 'Animate 5', 'black-widgets' ),
						'bw-scroll-e-6' 	=> esc_html__( 'Animate 6', 'black-widgets' ),
						'bw-scroll-e-7' 	=> esc_html__( 'Animate 7', 'black-widgets' ),
						'bw-scroll-e-8' 	=> esc_html__( 'Animate 8', 'black-widgets' ),
					],
					'condition'  => [
						'widget_type' => [
							'bw-t-4',
						],
					],
				]
			);
		}

		// Scrub Title
		$this->add_responsive_control(
			'title_scrub',
			[
				'label' 		=> esc_html__( 'Scrub on scrolling up and down', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Enable', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'Disable', 'black-widgets' ),
				'return_value' 	=> 'scrub_mode',
				'default' 		=> 'off',
				'condition'  => [
					'widget_type' => [
						'bw-t-4',
					],
				],
			]
		);

        // Style Subtitle Tabs
        $this->start_controls_tabs('tabx');
        $this->start_controls_tab(
            'tab_style_1',
            [
                'label' => esc_html__( 'Left or Top', 'black-widgets' ),
				'condition'  => [
					'widget_type_2' => [
						'custom-style',
					],
				],
            ]
        );

            $this->add_control(
                'tab_style_1_type',
                [
                    'label' => esc_html__( 'How exactly does it work?', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'enable_icon',
                    'options' => [
                        'enable_icon'       => esc_html__( 'With ICON', 'black-widgets' ),
                        'enable_image'      => esc_html__( 'With IMAGE or SVG', 'black-widgets' ),
                        'enable_code'       => esc_html__( 'With SVG Code', 'black-widgets' ),
                    ],
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'widget_1_alignment',
                [
                    'label'     => esc_html__( 'Text Alignment', 'black-widgets' ),
                    'type'      => \Elementor\Controls_Manager::CHOOSE,
                    'options'   => [
                        'left-right'   => [
                            'title' => esc_html__( 'Left', 'black-widgets' ),
                            'icon'  => 'eicon-arrow-left',
                        ],
                        'top-bottom' => [
                            'title' => esc_html__( 'Top', 'black-widgets' ),
                            'icon'  => 'eicon-arrow-up',
                        ],
                    ],
                    'default'   => 'left-right',
                    'toggle'    => true,
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                    ],
					// 'selectors' => [
					// 	'{{WRAPPER}} .bw-typograpgy .bw-left-top' => 'text-align: {{VALUE}};',
					// ],
                ]
            );

            $this->add_control(
                'tab_style_1_code',
                [
                    'label' => esc_html__( 'CODE', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'SVG CODE HERE', 'black-widgets' ),
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                        'tab_style_1_type' => [
                            'enable_code',
                        ],
                    ],
                ]
            );

            $this->add_control(
                'tab_style_1_icon',
                [
                    'label' => esc_html__( 'Choose Icon', 'black-widgets' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'eicon eicon-nerd',
                        'library' => 'elementor',
                    ],
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                        'tab_style_1_type' => [
                            'enable_icon',
                        ],
                    ],
                ]
            );

            $this->add_control(
                'tab_style_1_image',
                [
                    'label' => esc_html__( 'Choose Image', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                        'tab_style_1_type' => [
                            'enable_image',
                        ],
                    ],
                ]
            );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_style_2',
            [
                'label' => esc_html__( 'Right or Bottom', 'black-widgets' ),
				'condition'  => [
                    'widget_type_2' => [
                        'custom-style',
                    ],
				],
            ]
        );

            $this->add_control(
                'tab_style_2_type',
                [
                    'label' => esc_html__( 'How exactly does it work?', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'enable_icon',
                    'options' => [
                        'enable_icon'       => esc_html__( 'With ICON', 'black-widgets' ),
                        'enable_image'      => esc_html__( 'With IMAGE or SVG', 'black-widgets' ),
                        'enable_code'       => esc_html__( 'With SVG Code', 'black-widgets' ),
                    ],
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                    ],
                ]
            );

            $this->add_responsive_control(
                'widget_2_alignment',
                [
                    'label'     => esc_html__( 'Text Alignment', 'black-widgets' ),
                    'type'      => \Elementor\Controls_Manager::CHOOSE,
                    'options'   => [
                        'left-right'   => [
                            'title' => esc_html__( 'Right', 'black-widgets' ),
                            'icon'  => 'eicon-arrow-right',
                        ],
                        'top-bottom' => [
                            'title' => esc_html__( 'Bottom', 'black-widgets' ),
                            'icon'  => 'eicon-arrow-down',
                        ],
                    ],
                    'default'   => 'left-right',
                    'toggle'    => true,
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                    ],
					// 'selectors' => [
					// 	'{{WRAPPER}} .bw-typograpgy .bw-right-bottom' => 'text-align: {{VALUE}};',
					// ],
                ]
            );

            $this->add_control(
                'tab_style_2_code',
                [
                    'label' => esc_html__( 'CODE', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => esc_html__( 'SVG CODE HERE', 'black-widgets' ),
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                        'tab_style_2_type' => [
                            'enable_code',
                        ],
                    ],
                ]
            );

            $this->add_control(
                'tab_style_2_icon',
                [
                    'label' => esc_html__( 'Choose Icon', 'black-widgets' ),
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'eicon eicon-nerd',
                        'library' => 'elementor',
                    ],
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                        'tab_style_2_type' => [
                            'enable_icon',
                        ],
                    ],
                ]
            );

            $this->add_control(
                'tab_style_2_image',
                [
                    'label' => esc_html__( 'Choose Image', 'black-widgets' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                    'condition'  => [
                        'widget_type_2' => [
                            'custom-style',
                        ],
                        'tab_style_2_type' => [
                            'enable_image',
                        ],
                    ],
                ]
            );

        $this->end_controls_tab();
        $this->end_controls_tabs(); // End Tabs

        $this->add_control(
            'repetitive_repeat',
            [
                'label' => esc_html__( 'How many repeat you need?', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '7',
                'options' => [
                    '1'     => esc_html__( '1', 'black-widgets' ),
                    '2'     => esc_html__( '2', 'black-widgets' ),
                    '3'     => esc_html__( '3', 'black-widgets' ),
                    '4'     => esc_html__( '4', 'black-widgets' ),
                    '5'     => esc_html__( '5', 'black-widgets' ),
                    '6'     => esc_html__( '6', 'black-widgets' ),
                    '7'     => esc_html__( '7', 'black-widgets' ),
                    '8'     => esc_html__( '8', 'black-widgets' ),
                    '9'     => esc_html__( '9', 'black-widgets' ),
                    '10'    => esc_html__( '10', 'black-widgets' ),
                    '11'    => esc_html__( '11', 'black-widgets' ),
                    '12'    => esc_html__( '12', 'black-widgets' ),
                    '13'    => esc_html__( '13', 'black-widgets' ),
                    '14'    => esc_html__( '14', 'black-widgets' ),
                    '15'    => esc_html__( '15', 'black-widgets' ),
                    '16'    => esc_html__( '16', 'black-widgets' ),
                ],
				'condition'  => [
					'widget_type' => [
						'bw-t-3',
					],
				],
            ]
        );

        $this->add_control(
            'repetitive_repeat_other_style',
            [
                'label' => esc_html__( 'Set Other Style for which repeat?', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1'     => esc_html__( '1', 'black-widgets' ),
                    '2'     => esc_html__( '2', 'black-widgets' ),
                    '3'     => esc_html__( '3', 'black-widgets' ),
                    '4'     => esc_html__( '4', 'black-widgets' ),
                    '5'     => esc_html__( '5', 'black-widgets' ),
                    '6'     => esc_html__( '6', 'black-widgets' ),
                    '7'     => esc_html__( '7', 'black-widgets' ),
                    '8'     => esc_html__( '8', 'black-widgets' ),
                    '9'     => esc_html__( '9', 'black-widgets' ),
                    '10'    => esc_html__( '10', 'black-widgets' ),
                    '11'    => esc_html__( '11', 'black-widgets' ),
                    '12'    => esc_html__( '12', 'black-widgets' ),
                    '13'    => esc_html__( '13', 'black-widgets' ),
                    '14'    => esc_html__( '14', 'black-widgets' ),
                    '15'    => esc_html__( '15', 'black-widgets' ),
                    '16'    => esc_html__( '16', 'black-widgets' ),
                ],
				'condition'  => [
					'widget_type' => [
						'bw-t-3',
					],
				],
            ]
        );

		$this->add_responsive_control(
			'widget_alignment',
			[
				'label'     => esc_html__( 'Text Alignment', 'black-widgets' ), //all items!
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'black-widgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'black-widgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'black-widgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
				// 'condition'  => [
                //     'widget_type_2' => [
                //         'custom-style',
                //     ],
				// ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Vertical Title
		$this->add_responsive_control(
			'vertical_title_option',
			[
				'label' 		=> esc_html__( 'Vertical Title', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Enable', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'Disable', 'black-widgets' ),
				'return_value' 	=> 'vertical_mode',
				'default' 		=> 'off',
			]
		);

        $this->add_control(
            'vertical_title_display',
            [
                'label' => esc_html__( 'Reset Wrapper:', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'block',
                'options' => [
                    'block'     => esc_html__( 'Block', 'black-widgets' ),
                    'unset'     => esc_html__( 'unset', 'black-widgets' ),
                ],
				'condition'  => [
					'vertical_title_option' => [
						'vertical_mode',
					],
				],
            ]
        );

        $this->add_control(
            'vertical_rotation',
            [
                'label' => esc_html__( 'Reset Wrapper:', 'black-widgets' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'bw-rotation-1',
                'options' => [
                    'bw-rotation-1'     => esc_html__( '-180deg', 'black-widgets' ),
                    'bw-rotation-2'     => esc_html__( '-90deg', 'black-widgets' ),
                    'bw-rotation-3'     => esc_html__( '-45deg', 'black-widgets' ),
                    'bw-rotation-4'     => esc_html__( '0', 'black-widgets' ),
                    'bw-rotation-5'     => esc_html__( '45deg', 'black-widgets' ),
                    'bw-rotation-6'     => esc_html__( '90deg', 'black-widgets' ),
                    'bw-rotation-7'     => esc_html__( '180deg', 'black-widgets' ),
                ],
				'condition'  => [
					'vertical_title_option' => [
						'vertical_mode',
					],
				],
            ]
        );

		$options = get_option('plugin_options') ? get_option('plugin_options') : '';
		$gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';
		if( isset($gsap_options) && !empty($gsap_options) ) {

			// Enable Image Movement Animate
			$this->add_control(
				'text_movement2',
				[
					'label' 		=> esc_html__( 'Text Movement Animate → From', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'Enable', 'black-widgets' ),
					'label_off' 	=> esc_html__( 'Disable', 'black-widgets' ),
					'return_value' 	=> 'on',
					'default' 		=> 'off',
				]
			);

			// Start From
			$this->add_control(
				'trigger_hook2',
				[
					'label' 		=> esc_html__( 'Start Point', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'placeholder'   => '0.1',
					'default'       => '0.1',
					'condition' 	=> [ 
						'text_movement2' 	=> [
							'on',
						],
					],
				]
			);

			// Length
			$this->add_control(
				'duration2',
				[
					'label' 		=> esc_html__( 'Duration', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'placeholder'   => '0.4',
					'default'       => '0.4',
					'condition' 	=> [ 
						'text_movement2' 	=> [
							'on',
						],
					],
				]
			);

			// Vertical Movement
			$this->add_control(
				'vertical_movement2',
				[
					'label' 		=> esc_html__( 'Vertical Movement', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'condition' 	=> [ 
						'text_movement2' 	=> [
							'on',
						],
					],
				]
			);

			// Horizontal Movement
			$this->add_control(
				'horizontal_movement2',
				[
					'label' 		=> esc_html__( 'Horizontal Movement', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'condition' 	=> [ 
						'text_movement2' 	=> [
							'on',
						],
					],
				]
			);

			// Opacity at End
			$this->add_control(
				'opacity2',
				[
					'label' 		=> esc_html__( 'Opacity at End', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'condition' 	=> [ 
						'text_movement2' 	=> [
							'on',
						],
					],
				]
			);

			// Rotation at End
			$this->add_control(
				'rotation2',
				[
					'label' 		=> esc_html__( 'Rotation at End', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'condition' 	=> [ 
						'text_movement2' 	=> [
							'on',
						],
					],
				]
			);


			// Enable Image Movement Animate
			$this->add_control(
				'text_movement',
				[
					'label' 		=> esc_html__( 'Text Movement Animate → To', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::SWITCHER,
					'label_on' 		=> esc_html__( 'Enable', 'black-widgets' ),
					'label_off' 	=> esc_html__( 'Disable', 'black-widgets' ),
					'return_value' 	=> 'on',
					'default' 		=> 'off',
				]
			);

			// Start From
			$this->add_control(
				'trigger_hook',
				[
					'label' 		=> esc_html__( 'Start Point', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'placeholder'   => '0.1',
					'default'       => '0.1',
					'condition' 	=> [ 
						'text_movement' 	=> [
							'on',
						],
					],
				]
			);

			// Length
			$this->add_control(
				'duration',
				[
					'label' 		=> esc_html__( 'Duration', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'placeholder'   => '0.4',
					'default'       => '0.4',
					'condition' 	=> [ 
						'text_movement' 	=> [
							'on',
						],
					],
				]
			);

			// Vertical Movement
			$this->add_control(
				'vertical_movement',
				[
					'label' 		=> esc_html__( 'Vertical Movement', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'condition' 	=> [ 
						'text_movement' 	=> [
							'on',
						],
					],
				]
			);

			// Horizontal Movement
			$this->add_control(
				'horizontal_movement',
				[
					'label' 		=> esc_html__( 'Horizontal Movement', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'condition' 	=> [ 
						'text_movement' 	=> [
							'on',
						],
					],
				]
			);

			// Opacity at End
			$this->add_control(
				'opacity',
				[
					'label' 		=> esc_html__( 'Opacity at End', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'condition' 	=> [ 
						'text_movement' 	=> [
							'on',
						],
					],
				]
			);

			// Rotation at End
			$this->add_control(
				'rotation',
				[
					'label' 		=> esc_html__( 'Rotation at End', 'black-widgets' ),
					'type' 			=> \Elementor\Controls_Manager::TEXT,
					'condition' 	=> [ 
						'text_movement' 	=> [
							'on',
						],
					],
				]
			);
		}

		$this->end_controls_section();
		// End

		// Start
		// Content section
		$this->start_controls_section(
			'overlay_section',
			[
				'label' => esc_html__( 'Overlay', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Enable Title Section
		$this->add_responsive_control(
			'overlay_section_enable',
			[
				'label' 		=> esc_html__( 'Do you need overlay?', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'No !', 'black-widgets' ),
				'return_value' 	=> 'overlay_enable',
				// 'default' 		=> 'false',
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_overlay_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-typograpgy .bw-overlay',
				'fields_options' => [
					'size' => ['default' => 'cover'],
					'position' => ['default' => 'center center'],
					'repeat' => ['default' => 'no-repeat'],
				],
				'condition'  => [
					'overlay_section_enable' => [
						'overlay_enable',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'widget_overlay_background_css_filter',
				'selector' => 
					'{{WRAPPER}} .bw-typograpgy .bw-overlay'
			]
		);

		$this->add_responsive_control(
			'widget_icon_opacity',
			[
				'label' => esc_html__( 'Opacity', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy .bw-overlay' => 'opacity: {{SIZE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'widget_icon_backdrop_filter',
			[
				'label' => esc_html__( 'Backdrop Filter Blur', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy .bw-overlay' => 'backdrop-filter: blur( {{SIZE}}{{UNIT}} ) !important;',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_overlay_typography_title_border',
				'label' => esc_html__( 'Title Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy .bw-overlay',
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'widget_overlay_typography_title_border_radius', 
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy .bw-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Box Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => esc_html__( 'Normal', 'black-widgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-typograpgy',
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_box_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy',
			]
		);

		$this->add_responsive_control(
			'widget_box_border_radius', 
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_1_hover',
			[
				'label' => esc_html__( 'Hover', 'black-widgets' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-typograpgy:hover',
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_hover_box_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_hover_box_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_hover_box_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy:hover',
			]
		);

		$this->add_responsive_control(
			'widget_hover_box_border_radius',
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
        // End

		// Start
		// Style section
		$this->start_controls_section(
			'typography1_section',
			[
				'label' => esc_html__( 'Title Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'widget_title_solid_color',
			[
				'label' => esc_html__( 'Title Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],				
				'selectors' => [
					'{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive' => 'color: {{VALUE}}; -webkit-text-fill-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow1',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive',
			]
		);

		$this->add_control(
			'hr5',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_typography_title_background',
				'label' => esc_html__( 'Title Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive',
			]
		);

		$this->add_control(
			'hr6',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'widget_typography_title_margin',
			[
				'label' => esc_html__( 'Title Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'widget_typography_title_padding',
			[
				'label' => esc_html__( 'Title Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr7',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_typography_title_border',
				'label' => esc_html__( 'Title Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive',
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'widget_typography_title_border_radius', 
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_typography_title_box_shadow',
				'label' => esc_html__( 'Title Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive',
			]
		);

		$this->add_control(
			'z_index_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'z_index',
			[
				'label' => esc_html__( 'Z-Index', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -99999,
				'max' => 999999,
				'step' => 9,
				'description' => esc_html__( 'If you using the 2D / 3D style, so z-index will be disabled.', 'black-widgets' ),
			]
		);

		$this->add_control(
			'stroke1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Enable Title Section
		$this->add_control(
			'widget_stroke_title_enable',
			[
				'label' 		=> esc_html__( 'Text Stroke', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'No !', 'black-widgets' ),
				'return_value' 	=> 'stroke_enable',
				// 'default' 		=> 'false',
			]
		);

		$this->add_control(
			'widget_stroke_stroke_color',
			[
				'label' => esc_html__( 'Text Stroke Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],				
				'selectors' => [
					'{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive' => '-webkit-text-stroke-color: {{VALUE}}',
				],
				'condition'  => [
					'widget_stroke_title_enable' => [
						'stroke_enable',
					],
				],
			]
		);

		$this->add_responsive_control(
			'widget_stroke_stroke_width',
			[
				'label' => esc_html__( 'Text Stroke Size', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'widget_stroke_title_enable' => [
						'stroke_enable',
					],
				],
			]
		);

		$this->add_control(
			'gradient_color1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'gradient_color_title_enable',
			[
				'label' 		=> esc_html__( 'Text Gradient/Image', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'No !', 'black-widgets' ),
				'return_value' 	=> 'gradient_enable',
				// 'default' 		=> 'false',
			]
		);


		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'unique_widget_typography_title_gradient',
				'label' => esc_html__( 'Title Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-bw-t-2-text, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-main-title, {{WRAPPER}} .bw-typograpgy-animate, {{WRAPPER}} .bw-typograpgy-repetitive',
				'condition'  => [
					'gradient_color_title_enable' => [
						'gradient_enable',
					],
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'unique_typography1_section',
			[
				'label' => esc_html__( 'Unique Title Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'bw-t-3',
					],
				],
			]
		);

		// Color
		$this->add_control(
			'unique_widget_title_solid_color',
			[
				'label' => esc_html__( 'Title Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],				
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique' => 'color: {{VALUE}}; -webkit-text-fill-color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'unique_content_typography1',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique',
			]
		);

		// Text shadow
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'unique_text_shadow1',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique',
			]
		);

		$this->add_control(
			'hr8',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'unique_widget_typography_title_background',
				'label' => esc_html__( 'Title Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique',
			]
		);

		$this->add_control(
			'hr9',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Margin
		$this->add_responsive_control(
			'unique_widget_typography_title_margin',
			[
				'label' => esc_html__( 'Title Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'unique_widget_typography_title_padding',
			[
				'label' => esc_html__( 'Title Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr10',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'unique_widget_typography_title_border',
				'label' => esc_html__( 'Title Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique',
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'unique_widget_typography_title_border_radius', 
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'unique_widget_typography_title_box_shadow',
				'label' => esc_html__( 'Title Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique',
			]
		);

		$this->add_control(
			'unique_z_index_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'unique_z_index',
			[
				'label' => esc_html__( 'Z-Index', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => -99999,
				'max' => 999999,
				'step' => 9,
				'description' => esc_html__( 'If you using the 2D / 3D style, so z-index will be disabled.', 'black-widgets' ),
			]
		);

		$this->add_control(
			'unique_stroke1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		// Enable Title Section
		$this->add_control(
			'unique_widget_stroke_title_enable',
			[
				'label' 		=> esc_html__( 'Text Stroke', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'No !', 'black-widgets' ),
				'return_value' 	=> 'stroke_enable',
				// 'default' 		=> 'false',
			]
		);

		// $this->add_control(
		// 	'unique_widget_stroke_fill_color',
		// 	[
		// 		'label' => esc_html__( 'Text Stroke Color', 'black-widgets' ),
		// 		'type' => Controls_Manager::COLOR,
		// 		'scheme' => [
		// 			'type' => Color::get_type(),
		// 			'value' => Color::COLOR_1,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique' => '-webkit-text-fill-color: {{VALUE}}',
		// 		],
		// 		'condition'  => [
		// 			'unique_widget_stroke_title_enable' => [
		// 				'stroke_enable',
		// 			],
		// 		],
		// 	]
		// );

		$this->add_control(
			'unique_widget_stroke_stroke_color',
			[
				'label' => esc_html__( 'Text Stroke Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],				
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique' => '-webkit-text-stroke-color: {{VALUE}}',
				],
				'condition'  => [
					'unique_widget_stroke_title_enable' => [
						'stroke_enable',
					],
				],
			]
		);

		$this->add_responsive_control(
			'unique_widget_stroke_stroke_width',
			[
				'label' => esc_html__( 'Text Stroke Size', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'unique_widget_stroke_title_enable' => [
						'stroke_enable',
					],
				],
			]
		);


		$this->add_control(
			'gradient_color2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'gradient_color_unique_widget_enable',
			[
				'label' 		=> esc_html__( 'Text Gradient', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Yes', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'No !', 'black-widgets' ),
				'return_value' 	=> 'unique_gradient_enable',
				// 'default' 		=> 'false',
			]
		);


		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'unique_widget_typography_unique_widget_gradient',
				'label' => esc_html__( 'Title Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-typograpgy-repetitive.bw-unique',
				'condition'  => [
					'gradient_color_unique_widget_enable' => [
						'unique_gradient_enable',
					],
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'transform_section',
			[
				'label' => esc_html__( '2D & 3D Normal Transform Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Enable Image Movement Animate
		$this->add_control(
			'transform_normal_option',
			[
				'label' 		=> esc_html__( 'Transform Style', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::SWITCHER,
				'label_on' 		=> esc_html__( 'Enable', 'black-widgets' ),
				'label_off' 	=> esc_html__( 'Disable', 'black-widgets' ),
				'return_value' 	=> 'normal_transform',
				'default' 		=> 'off',
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('transform_tabs', [
			'condition' 	=> [
				'transform_normal_option' 	=> [
					'normal_transform',
				],
			],
		]);
		$this->start_controls_tab(
			'transform_tab_move',
			[
				'label' => esc_html__( 'Move', 'black-widgets' ),
			]
		);

		// Start normal tab content

		$this->add_responsive_control(
			'move_normal_x',
			[
				'label' => esc_html__( 'Move on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'movement on the diagram X - do not leave empty!', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'move_normal_y',
			[
				'label' => esc_html__( 'Move on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'movement on the diagram Y - do not leave empty!', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'move_normal_z',
			[
				'label' => esc_html__( 'Move on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 2,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'movement on the diagram Z - do not leave empty!', 'black-widgets' ),
			]
		);

		// end normal tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_scale',
			[
				'label' => esc_html__( 'Scale', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'scale_normal_x',
			[
				'label' => esc_html__( 'Scale on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'set 1.1 to scale on left and right - do not set 0', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'scale_normal_y',
			[
				'label' => esc_html__( 'Scale on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'set 1.1 to scale on top and bottom - do not set 0', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'scale_normal_z',
			[
				'label' => esc_html__( 'Scale on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 1,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'perspective',
			[
				'label' => esc_html__( 'Self Perspective ◊', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ps' ],
				'range' => [
					'px' => [
						'min' => -3000,
						'max' => 3000,
						'step' => 50,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		$this->add_responsive_control(
			'perspective_child',
			[
				'label' => esc_html__( 'Children Perspective ◊', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'ps' ],
				'range' => [
					'px' => [
						'min' => -3000,
						'max' => 3000,
						'step' => 50,
					],
				],
				'default'  => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'If you want to use scale Z or 3D Scale, you should set perspective size', 'black-widgets' ),
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_rotate',
			[
				'label' => esc_html__( 'Rotate', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'rotate_normal_x',
			[
				'label' => esc_html__( 'Rotate on → X', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'rotate_normal_y',
			[
				'label' => esc_html__( 'Rotate on ↑ Y', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'rotate_normal_z',
			[
				'label' => esc_html__( 'Rotate on ↙ Z', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->start_controls_tab(
			'transform_tab_skew',
			[
				'label' => esc_html__( 'Skew', 'black-widgets' ),
			]
		);

		// Start hover tab content

		$this->add_responsive_control(
			'skew_normal_x',
			[
				'label' => esc_html__( 'Skew on → X ▱', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		$this->add_responsive_control(
			'skew_normal_y',
			[
				'label' => esc_html__( 'Skew on ↑ Y ▱', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 5,
					],
				],
				'default'  => [
					'unit' => 'deg',
					'size' => 0,
				]
			]
		);

		// end hover tab content

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'line_section',
			[
				'label' => esc_html__( 'Line Styles', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'bw-t-2',
					],
					'widget_type_2!' => [
						'custom-style',
					],
				],
			]
		);

		$this->add_responsive_control(
			'widget_line_width',
			[
				'label' => esc_html__( 'Line Width', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy.bw-t-2 .line-1, {{WRAPPER}} .bw-typograpgy.bw-t-2 .line-2' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'widget_line_height',
			[
				'label' => esc_html__( 'Line Height', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy.bw-t-2 .line-1, {{WRAPPER}} .bw-typograpgy.bw-t-2 .line-2' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_line_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-typograpgy.bw-t-2 .line-1, {{WRAPPER}} .bw-typograpgy.bw-t-2 .line-2',
			]
		);

		$this->add_responsive_control(
			'widget_line_box_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'allowed_dimensions' => ['top', 'bottom'],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy.bw-t-2 .line-1, {{WRAPPER}} .bw-typograpgy.bw-t-2 .line-2' => 'margin: {{TOP}}{{UNIT}} auto {{BOTTOM}}{{UNIT}} auto;',
				],
			]
		);

		// Border
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_line_box_border',
				'label' => esc_html__( 'Title Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-typograpgy.bw-t-2 .line-1, {{WRAPPER}} .bw-typograpgy.bw-t-2 .line-2',
			]
		);

		// Border Radius
		$this->add_responsive_control(
			'widget_line_box_border_radius', 
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy.bw-t-2 .line-1, {{WRAPPER}} .bw-typograpgy.bw-t-2 .line-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Style section
		$this->start_controls_section(
			'icon_section',
			[
				'label' => esc_html__( 'Icon Styles', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'  => [
					'widget_type' => [
						'bw-t-2',
					],
					'widget_type_2' => [
						'custom-style',
					],
				],
			]
		);

		$this->add_responsive_control(
			'widget_icon_width',
			[
				'label' => esc_html__( 'Icon Size', 'black-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy .custom-style i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'widget_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],				
				'selectors' => [
					'{{WRAPPER}} .bw-typograpgy .custom-style i' => 'color: {{VALUE}}; -webkit-text-fill-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
		// End

	}

	/**
	 * Render title widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings   	= $this->get_settings_for_display();
		// Variables
        $type 	                = isset($settings['widget_type'])                        ? $settings['widget_type']						: '';
		$shape 	                = isset($settings['shape_widget'])						 ? $settings['shape_widget']					: '';
		// Added this for XSS — Vulnerable to Cross Site Scripting (XSS)
		$allowed_tags 			= ['div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span'];
		$title_tag 				= isset($settings['widget_html_tag_title'])				 ? $settings['widget_html_tag_title']			: '';
		// Validate the HTML tag
		if (!in_array($title_tag, $allowed_tags)) {
			$title_tag = 'div';
		}
        $title 			        = isset($settings['widget_title'])                       ? $settings['widget_title']					: '';
        $alignment 		        = '';
        $title_scrub			= isset($settings['title_scrub'])                        ? $settings['title_scrub']						: '';
        $vertical				= isset($settings['vertical_title_display'])			 ? $settings['vertical_title_display']			: '';
        $vertical_rotation		= isset($settings['vertical_rotation'])					 ? $settings['vertical_rotation']				: '';
        $type2 		            = isset($settings['widget_type_2'])                      ? $settings['widget_type_2']					: '';
        $type4 		            = isset($settings['widget_type_4'])                      ? $settings['widget_type_4']					: '';
        $custom_style_x         = ($type2 == 'custom-style')                             ? 'custom-style'								: '';
        $repeat                 = isset($settings['repetitive_repeat'])                  ? $settings['repetitive_repeat']				: '';
        $other_style            = isset($settings['repetitive_repeat_other_style'])      ? $settings['repetitive_repeat_other_style']	: '';
		// Overlay 
		$overlay				= isset($settings['overlay_section_enable'])			? $settings['overlay_section_enable']			: '';
		// Gradient
		$set_gradient			= isset($settings['gradient_color_title_enable'])		? $settings['gradient_color_title_enable']		: '';
		$gradient				= ($set_gradient == 'gradient_enable')					? 'bw-gradient'									: '';
        // Custom Style for shape
        $style_1_type           = isset($settings['tab_style_1_type'])                  ? $settings['tab_style_1_type']					: '';
        $image_id_1		        = isset($settings['tab_style_1_image']['url']) 		    ?  $settings['tab_style_1_image']['url']        : ''; // Image 1
		$iconset_1				= isset($settings['tab_style_1_icon'])                  ? $settings['tab_style_1_icon']                 : ''; // Icon 1
		$svgcode_1				= isset($settings['tab_style_1_code'])                  ? $settings['tab_style_1_code']                 : ''; // Code 1
        $alignment_1            = isset($settings['widget_1_alignment'])                ? $settings['widget_1_alignment']               : '';
        // $alignment_1            = '';
        $style_2_type           = isset($settings['tab_style_2_type'])                  ? $settings['tab_style_2_type']                 : '';
        $image_id_2		        = isset($settings['tab_style_2_image']['url']) 		    ?  $settings['tab_style_2_image']['url']        : ''; // Image 2
		$iconset_2				= isset($settings['tab_style_2_icon'])                  ? $settings['tab_style_2_icon']                 : ''; // Icon 2
		$svgcode_2				= isset($settings['tab_style_2_code'])                  ? $settings['tab_style_2_code']                 : ''; // Code 2
        $alignment_2            = isset($settings['widget_2_alignment'])                ? $settings['widget_2_alignment']               : '';
        // $alignment_2            = '';
		// Transform Option
		$normal_transform		= isset($settings['transform_normal_option'])			? $settings['transform_normal_option']			: '';
		//Transform Styles
		$move_normal_x			= isset($settings['move_normal_x'])						? $settings['move_normal_x']					: '0px';
		$move_normal_y			= isset($settings['move_normal_y'])						? $settings['move_normal_y']					: '0px';
		$move_normal_z			= isset($settings['move_normal_z'])						? $settings['move_normal_z']					: '0px';
		$scale_normal_x			= isset($settings['scale_normal_x'])					? $settings['scale_normal_x']					: '1';
		$scale_normal_y			= isset($settings['scale_normal_y'])					? $settings['scale_normal_y']					: '1';
		$scale_normal_z			= isset($settings['scale_normal_z'])					? $settings['scale_normal_z']					: '1';
		$rotate_normal_x		= isset($settings['rotate_normal_x'])					? $settings['rotate_normal_x']					: '0deg';
		$rotate_normal_y		= isset($settings['rotate_normal_y'])					? $settings['rotate_normal_y']					: '0deg';
		$rotate_normal_z		= isset($settings['rotate_normal_z'])					? $settings['rotate_normal_z']					: '0deg';
		$skew_normal_x			= isset($settings['skew_normal_x'])						? $settings['skew_normal_x']					: '0deg';
		$skew_normal_y			= isset($settings['skew_normal_y'])						? $settings['skew_normal_y']					: '0deg';
		$perspective			= isset($settings['perspective'])						? $settings['perspective']						: '0px';
		$perspective_child		= isset($settings['perspective_child'])					? $settings['perspective_child']				: '0px';
		//ID Settings
		$data_id				= 'bw_' . uniqid();
		$script_id				= '#' . $data_id;
		$options = get_option('plugin_options') ? get_option('plugin_options') : '';
		$gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';
		if( isset($gsap_options) && !empty($gsap_options) ) {
			$text_movement			= $settings['text_movement'];
			$text_movement2			= $settings['text_movement2'];
		}
		//Transform Normal Styles 
		// Normal Move 
		$translatex 			= isset( $move_normal_x["size"] ) 						? $move_normal_x["size"] . $move_normal_x["unit"] : '';
		$translatey 			= isset( $move_normal_y["size"] ) 						? $move_normal_y["size"] . $move_normal_y["unit"] : '';
		$translatez 			= isset( $move_normal_z["size"] ) 						? $move_normal_z["size"] . $move_normal_z["unit"] : '';
		$translate3d 			= 'translate3d(' . $translatex . ', ' . $translatey . ', ' . $translatez . ')';
		// Normal Scale 
		$scalex 				= isset( $scale_normal_x["size"] ) 						? $scale_normal_x["size"] : '';
		$scaley 				= isset( $scale_normal_y["size"] ) 						? $scale_normal_y["size"] : '';
		$scalez 				= isset( $scale_normal_z["size"] ) 						? $scale_normal_z["size"] : '';
		$scale3dx 				= isset( $scale_normal_z["size"] ) 						? '-webkit-transform-style: preserve-3d; transform-style: preserve-3d;' : '';
		$scale3d 				= 'scale3d(' . $scalex . ', ' . $scaley . ', ' . $scalez . ')';
		// Normal Rotate
		$rotatex 				= isset( $rotate_normal_x["size"] ) 					? 'rotateX(' . $rotate_normal_x["size"] . $rotate_normal_x["unit"] . ')' : '';
		$rotatey 				= isset( $rotate_normal_y["size"] ) 					? 'rotateY(' . $rotate_normal_y["size"] . $rotate_normal_y["unit"] . ')' : '';
		$rotatez 				= isset( $rotate_normal_z["size"] ) 					? 'rotateZ(' . $rotate_normal_z["size"] . $rotate_normal_z["unit"] . ')' : '';
		// Normal Skew
		$skewx 					= isset( $skew_normal_x["size"] ) 						? $skew_normal_x["size"] . $skew_normal_x["unit"] : '';
		$skewy 					= isset( $skew_normal_y["size"] ) 						? $skew_normal_y["size"] . $skew_normal_y["unit"] : '';
		$skew 					= 'skew(' . $skewx . ', ' . $skewy . ')';
		// Normal Self perspective
		$perspective 			= isset( $perspective["size"] ) 						? 'perspective:' . $perspective["size"] . $perspective["unit"] . '; -webkit-perspective:' . $perspective["size"] . $perspective["unit"] . ';': '';
		// Normal Child perspective
		$perspective_child 		= isset( $perspective_child["size"] ) 					? 'perspective(' . $perspective_child["size"] . $perspective_child["unit"] . ')': '';
        // Typography Z Index
		$z_index            	= isset($settings['z_index'])                			? '#'.$data_id.' .bw-typograpgy-with-title, #'.$data_id.' .bw-typography-this-title {z-index:'.$settings['z_index'].'; position: relative;}'          			 	: '';
		$unique_z_index			= isset($settings['unique_z_index'])					? '#'.$data_id.' .bw-unique {z-index:'.$settings['unique_z_index'].'; position: relative;}'          			 	: '';
		// Create a Custom CSS for Normal and Hover Styles
		$normal_transform_style = ($normal_transform == 'normal_transform')				? "#$data_id { $perspective transform: $perspective_child $skew $rotatex $rotatey $rotatez $scale3d $translate3d; -webkit-transform: $perspective_child $skew $rotatex $rotatey $rotatez $scale3d $translate3d; $scale3dx }" : '';
		$options = get_option('plugin_options') ? get_option('plugin_options') : '';
        $gsap_options  = isset($options['gsap_options']) ? $options['gsap_options'] : '';
		$second_bw_id			= 'second_bw_' . uniqid();
		$second_bwscript_id		= '#' . $second_bw_id;

        $sanitizer = new Sanitizer();
        $svgcode_1 = $sanitizer->sanitize( $svgcode_1 );
        $svgcode_2 = $sanitizer->sanitize( $svgcode_2 );

		echo '<style>'. esc_html( $normal_transform_style ) . ' ' . $z_index. ' ' . $unique_z_index.'</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 

		// Render
        echo '<div class="bw-typograpgy '. esc_attr( $type ) .' '.$alignment.' '.$custom_style_x.' '.$gradient.'">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
			echo '<div class="bw-typograpgy-wrap" id="'. $data_id .'">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
			switch ($type) {
				case 'bw-t-1': // Type 1
					echo '<'. $title_tag .' class="bw-typograpgy-main-title bw-'. esc_attr( $vertical ) .' '. esc_attr( $vertical_rotation ) .' bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
					break;
				case 'bw-t-2':// Type 2
					echo '<div class="bw-typograpgy-with-title bw-'. esc_attr( $vertical ) . ' ' . esc_attr( $type2 ) . ' ' . esc_attr( $vertical_rotation ) .'">';
					switch ($type2) {
						case 'style-x-1':
							echo '<span class="line-1"></span>';
							echo '<'. $title_tag .' class="bw-bw-t-2-text bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							break;
						case 'style-x-2':
							echo '<'. $title_tag .' class="bw-bw-t-2-text bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							echo '<span class="line-2"></span>';
							break;
						case 'style-x-3':
							echo '<span class="line-1"></span>';
							echo '<'. $title_tag .' class="bw-bw-t-2-text bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							echo '<span class="line-2"></span>';
							break;
						case 'style-x-4':
							echo '<span class="line-1"></span>';
							echo '<'. $title_tag .' class="bw-bw-t-2-text bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							break;
						case 'style-x-5':
							echo '<'. $title_tag .' class="bw-bw-t-2-text bw-typography-this-title">'.esc_html($title).'</'.$title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							echo '<span class="line-2"></span>';
							break;
						case 'style-x-6':
							echo '<span class="line-1"></span>';
							echo '<'. $title_tag .' class="bw-bw-t-2-text bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							echo '<span class="line-2"></span>';
							break;
						case 'custom-style':
							echo '<span class="bw-left-top '. esc_attr( $alignment_1 ) .'">';
								if ( $style_1_type == 'enable_icon' ): echo '<div class="bw-iconbox-icon">'; \Elementor\Icons_Manager::render_icon( $iconset_1, [ 'aria-hidden' => 'true' ] ); echo '</div>';
								elseif ( $style_1_type == 'enable_code' ): echo '<div class="bw-iconbox-img xcv--mw">' . wp_kses_post( $svgcode_1 ) . '</div>';
								else: echo '<div class="bw-iconbox-img"><img src="'. esc_url( $image_id_1 ).'" class="bw-iconbox-image"></div>';
								endif;
							echo '</span>';
							echo '<'. $title_tag .' class="bw-bw-t-2-text '.$alignment.' bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							echo '<span class="bw-right-bottom '.esc_attr( $alignment_2 ) .'">';
								if ( $style_2_type == 'enable_icon' ): echo '<div class="bw-iconbox-icon">'; \Elementor\Icons_Manager::render_icon( $iconset_2, [ 'aria-hidden' => 'true' ] ); echo '</div>';
								elseif ( $style_2_type == 'enable_code' ): echo '<div class="bw-iconbox-img xcv--mw">' . wp_kses_post( $svgcode_2 ) . '</div>';
								else: echo '<div class="bw-iconbox-img"><img src="'. esc_url( $image_id_2 ).'" class="bw-iconbox-image"></div>';
								endif;
							echo '</span>';
							break;
						default:
							echo '<span class="line-1"></span>';
							echo '<'. $title_tag .' class="bw-bw-t-2-text bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							break;
					}
					echo '</div>';
					break;
				case 'bw-t-3': // Type 3
						if ( !empty($vertical) ) echo '<div class="bw-wrapper-'. esc_attr( $vertical ) .' '. esc_attr( $vertical_rotation ) .'">';
						$count = 1;
						while( $count <= $repeat ) {
							if ($count == $other_style) {
								echo '<'. $title_tag .' class="bw-typograpgy-repetitive bw-unique bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							} else {
								echo '<'. $title_tag .' class="bw-typograpgy-repetitive bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							}
							$count++;
						}
						if ( !empty($vertical) ) echo '</div>';
					break;
				case 'bw-t-4': // Type 4
					echo '<h2 class="bw-typograpgy-animate words chars splitting '. esc_attr( $vertical_rotation ) .'" '. esc_attr( $title_scrub ) .' bw-data-splitting bw-data-splitting bw-data-'. esc_attr( $type4 ) .' id="scrub'.$data_id.'" data-scrub="true">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						echo '<span class="word" data-word="'. esc_attr( $title ) .'" style="--word-index:0;">';
							echo esc_html( $title );
						echo '</span>';
					echo '</h2>';

					break;
				default: // simple
                    echo '<'. $title_tag .' class="bw-typograpgy-main-title bw-'. esc_attr( $vertical ) . ' ' . esc_attr( $vertical_rotation ) .' bw-typography-this-title">'.esc_html($title).'</'. $title_tag .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                    break;
			}
			echo '</div>';
			if ( $overlay == 'overlay_enable' ) echo '<div class="bw-overlay"></div>';
        echo '</div>';

		if ( isset($gsap_options) && !empty($gsap_options) ) {
			if ($text_movement2 == 'on') {
                $duration2 = ! empty( $settings['duration2'] ) ? esc_js( $settings['duration2'] ) : '';
                $horizontal_movement2 = ! empty( $settings['horizontal_movement2'] ) ? 'x: "' . esc_js( $settings['horizontal_movement2'] ) . '",' : '';
                $vertical_movement2 = ! empty( $settings['vertical_movement2'] ) ? 'y: "' . esc_js( $settings['vertical_movement2'] ) . '",' : '';
                $opacity2 = ! empty( $settings['opacity2'] ) ? 'opacity: "' . esc_js( $settings['opacity2'] ) . '",' : '';
                $rotation2 = ! empty( $settings['rotation2'] ) ? 'rotation: "' . esc_js( $settings['rotation2'] ) . '",' : '';
				$tlfrom = 'tl.from("#'. $data_id .'", { ' . $opacity2 . $rotation2 . $horizontal_movement2 . $vertical_movement2 . ' duration: '.$duration2.' })';
			} else {
				$tlfrom = '';
			}
			if ($text_movement == 'on') {
                $duration = ! empty( $settings['duration'] ) ? esc_js( $settings['duration'] ) : '';
                $horizontal_movement = ! empty( $settings['horizontal_movement'] ) ? 'x: "' . esc_js( $settings['horizontal_movement'] ) . '",' : '';
                $vertical_movement = ! empty( $settings['vertical_movement'] ) ? 'y: "' . esc_js( $settings['vertical_movement'] ) . '",' : '';
                $opacity = ! empty( $settings['opacity'] ) ? 'opacity: "' . esc_js( $settings['opacity'] ) . '",' : '';
                $rotation = ! empty( $settings['rotation'] ) ? 'rotation: "' . esc_js( $settings['rotation'] ) . '",' : '';
                $tlto = 'tl.to("#'. $data_id .'", { ' . $opacity . $rotation . $horizontal_movement . $vertical_movement . ' duration: '.$duration.' })';
            } else {
				$tlto = '';
			}

            echo '<script>
					jQuery(window).ready(function($) {
						gsap.registerPlugin(ScrollTrigger);
						ScrollTrigger.config({ limitCallbacks: true });
						const tl = gsap.timeline({
							scrollTrigger: {
							trigger: "#' . $data_id . '",
							start: "center bottom",
							end: "center top",
							scrub: true,
							// markers: true
							}
						});
						' . $tlfrom . '
						' . $tlto . '
					});
			</script>';

				if( $shape ) {
					echo '<div class="bw-code-em" id="' . $second_bw_id . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						\Elementor\Icons_Manager::render_icon( $settings['shape_widget'], [ 'aria-hidden' => 'true' ] );
					echo '</div>';

					echo '<script>
                        jQuery(window).ready(function($) {
							jQuery("'. $second_bwscript_id .'").appendTo( jQuery("'. $script_id .' em") );

                            const scroll = jQuery(window).scrollTop();
							const objectSelect = jQuery("'. $script_id .'");
                            const bottom = jQuery(window).height();
                            const objectPosition = objectSelect.offset().top - bottom;
                            if (scroll > objectPosition) {
								jQuery("'. $script_id .' .bw-code-em").addClass("run");
                            } else {
                                jQuery("'. $script_id .' .bw-code-em").removeClass("run");
                            }
                        });

                        jQuery(window).scroll(function($) {    
                            const scroll = jQuery(window).scrollTop();
							const objectSelect = jQuery("'. $script_id .'");
                            const bottom = jQuery(window).height();
                            const objectPosition = objectSelect.offset().top - bottom;
                            if (scroll > objectPosition) {
                                jQuery("'. $script_id .' .bw-code-em").addClass("run");
                            } else {
                                jQuery("'. $script_id .' .bw-code-em").removeClass("run");
                            }
                        });
					</script>';

				}

		}

	}

}

class_alias('Modernaweb\BlackWidgets\Widgets\Typography', 'Black_Widgets\BLACK_WIDGETS_Typography');
