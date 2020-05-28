<?php
namespace Elementor;
namespace BW_Modernaweb\Includes\Widgets;
use Elementor\Plugin;

use Elementor\Widget_Base;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Color;
use Elementor\Group_Control_Text_Shadow;

/**
 * Elementor title Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class BW_Button extends \Elementor\Widget_Base {

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
		return 'b_button';
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
		return __( 'Black Button', 'bw' );
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
		return 'eicon-button';
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
		return [ 'bw' ];
	}

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		// Start
		// Content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Select type of the title
		$this->add_control(
			'widget_type',
			[
				'label' => __( 'Select Type', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'minimal',
				'options' => [
					'minimal' 	=> __( 'Minimal', 'bw' ),
					'modern' 	=> __( 'Modern', 'bw' ),
					'noise' 	=> __( 'Noise', 'bw' ),
					'fancy' 	=> __( 'Fancy', 'bw' ),
					'abstract' 	=> __( 'Abstract', 'bw' ),
					'gradient' 	=> __( 'Gradient', 'bw' ),
					'simple' 	=> __( 'Simple', 'bw' ),
					'custom' 	=> __( 'Custom', 'bw' ),
				],
				'description' => __( 'We create some skin before, you can use these or no! make a new custom type.', 'bw' ),
			]
		);
		
		$this->add_control(
			'widget_modern_type',
			[
				'label' => __( 'Modern Skin', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'm-1',
				'options' => [
					'm-1' 	=> __( 'Type 1', 'bw' ),
					'm-2' 	=> __( 'Type 2', 'bw' ),
					'm-3' 	=> __( 'Type 3', 'bw' ),
					'm-4' 	=> __( 'Type 4', 'bw' ),
					'm-5' 	=> __( 'Type 5', 'bw' ),
				],
				'condition'  => [
					'widget_type' => [
						'modern',
					],
				],
			]
		);
		
		$this->add_control(
			'widget_noise_type',
			[
				'label' => __( 'Noise Skin', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'n-1',
				'options' => [
					'n-1' 	=> __( 'Type 1', 'bw' ),
					'n-2' 	=> __( 'Type 2', 'bw' ),
					'n-3' 	=> __( 'Type 3', 'bw' ),
				],
				'condition'  => [
					'widget_type' => [
						'noise',
					],
				],
			]
		);

		$this->add_control(
			'widget_fancy_type',
			[
				'label' => __( 'Fancy Skin', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'f-1',
				'options' => [
					'f-1' 	=> __( 'Type 1', 'bw' ),
					'f-2' 	=> __( 'Type 2', 'bw' ),
					'f-3' 	=> __( 'Type 3', 'bw' ),
					'f-4' 	=> __( 'Type 4', 'bw' ),
					'f-5' 	=> __( 'Type 5', 'bw' ),
				],
				'condition'  => [
					'widget_type' => [
						'fancy',
					],
				],
			]
		);

		$this->add_control(
			'widget_abstract_type',
			[
				'label' => __( 'Abstract Skin', 'bw' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'a-1',
				'options' => [
					'a-1' 	=> __( 'Type 1', 'bw' ),
					'a-2' 	=> __( 'Type 2', 'bw' ),
				],
				'condition'  => [
					'widget_type' => [
						'abstract',
					],
				],
			]
		);

		$this->add_control(
			'widget_text',
			[
				'label' => __( 'Button Text', 'bw' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Let\'s started', 'bw' ),
				'placeholder' => __( 'Type your title here', 'bw' ),
			]
		);
        
		$this->add_control(
			'website_link',
			[
				'label' => __( 'Link', 'bw' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'bw' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->end_controls_section();
		// End

		// Start
		// Content section
		$this->start_controls_section(
			'custom_section',
			[
				'label' => __( 'Custom Content', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition'  => [
					'widget_type' => [
						'custom',
					],
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
				'label' => __( 'Box Style', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_1_tab');
		$this->start_controls_tab(
			'tab_1_normal',
			[
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .bw-button-box .bw-btn',
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
				'label' => __( 'Margin', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-button-box .bw-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_box_padding',
			[
				'label' => __( 'Padding', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-button-box .bw-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-button-box .bw-btn',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-button-box .bw-btn',
			]
		);

		$this->add_control(
			'widget_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-button-box .bw-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_1_hover',
			[
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Background
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_hover_box_background',
				'label' => __( 'Background', 'bw' ),
				'types' => [ 'classic', 'gradient', ],
				'selector' => '{{WRAPPER}} .bw-button-box .bw-btn:hover',
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
				'label' => __( 'Margin', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-button-box .bw-btn:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Padding
		$this->add_responsive_control(
			'widget_hover_box_padding',
			[
				'label' => __( 'Padding', 'bw' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-button-box .bw-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => __( 'Border', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-button-box .bw-btn:hover',
			]
		);

		// Box shadow
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_hover_box_box_shadow',
				'label' => __( 'Box Shadow', 'bw' ),
				'selector' => '{{WRAPPER}} .bw-button-box .bw-btn:hover',
			]
		);

		$this->add_control(
			'widget_hover_box_border_radius', //param_name
			[
				'label' 		=> __( 'Border Radius', 'bw' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-button-box .bw-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => __( 'Button Typography', 'bw' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Style Subtitle Tabs
		$this->start_controls_tabs('black_widget_2_tab');
		$this->start_controls_tab(
			'tab_2_normal',
			[
				'label' => __( 'Normal', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_btn_solid_color',
			[
				'label' => __( 'Button Text Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-button-box .bw-btn' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography1',
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-button-box .bw-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_2_hover',
			[
				'label' => __( 'Hover', 'bw' ),
			]
		);

		// Color
		$this->add_control(
			'widget_btn_hover_solid_color',
			[
				'label' => __( 'Button Text Color', 'bw' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bw-button-box .bw-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// Typography
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_hover_typography1',
				'label' => __( 'Typography', 'bw' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bw-button-box .bw-btn:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); // End Tabs		

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
        $type 	        	= isset($settings['widget_type']) 				? $settings['widget_type'] 				: '';
        // $position        = isset($settings['widget_alignment']) 			? $settings['widget_alignment'] 		: '';
        $modern_type		= isset($settings['widget_modern_type']) 		? $settings['widget_modern_type'] 		: '';
        $noise_type			= isset($settings['widget_noise_type']) 		? $settings['widget_noise_type'] 		: '';
        $fancy_type			= isset($settings['widget_fancy_type']) 		? $settings['widget_fancy_type'] 		: '';
        $abstract_type		= isset($settings['widget_abstract_type']) 		? $settings['widget_abstract_type'] 	: '';
        $text 	        	= isset($settings['widget_text']) 				? $settings['widget_text'] 				: '';
		$target         	= $settings['website_link']['is_external'] 		? 'target="_blank"' 					: '';
		$nofollow       	= $settings['website_link']['nofollow'] 		? ' rel="nofollow"'						: '';

		// Render
        echo '<div class="bw-button-box ' . $type . ' ' . $modern_type . ' ' . $fancy_type . ' ' . $noise_type . ' ' . $abstract_type . '">';
			switch ($type) {
				case 'modern':
					switch ($modern_type) {
						case 'm-4':
							echo '<div class="btn-wrapper"><a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn bw-btn-' . $modern_type . '">' . $text . '</a></div>';
							echo '<!-- symbols -->
							<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
								<symbol id="donut" viewBox="0 0 14 14"><path fill="#000" fill-rule="nonzero" d="M7 12c2.76 0 5-2.24 5-5S9.76 2 7 2 2 4.24 2 7s2.24 5 5 5zm0 2c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></symbol>
								<symbol id="circle" viewBox="0 0 10 10"><circle cx="5" cy="5" r="5" fill="#000" fill-rule="evenodd"/></symbol>
								<symbol id="tri_hollow" viewBox="0 0 12 11"><path fill="#000" fill-rule="nonzero" d="M3.4 8.96h5.2L6 4.2 3.4 8.95zM6 0l6 11H0L6 0z"/></symbol>
								<symbol id="triangle" viewBox="0 0 10 9"><path fill="#000" fill-rule="evenodd" d="M5 0l5 9H0"/></symbol>
								<symbol id="square" viewBox="0 0 8 8"><path fill="#000" fill-rule="evenodd" d="M0 0h8v8H0z"/></symbol>
								<symbol id="squ_hollow" viewBox="0 0 8 8"><path fill="#000" fill-rule="nonzero" d="M1.5 1.5v5h5v-5h-5zM0 0h8v8H0V0z"/></symbol>
							</svg>';
						break;
						case 'm-5':
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn">
									<span>' . $text . '</span>
								</a>';
						break;
						default:
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn">' . $text . '</a>';
						break;
					}
				break;
				case 'noise':
					switch ($noise_type) {
						case 'n-1':
							echo '
							<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn bw-btn-' . $noise_type . '">
								<div>' . $text . '</div>
								<div>
									<div>' . $text . '</div>
									<div>' . $text . '</div>
									<div>' . $text . '</div>
								</div>
							</a>';
						break;
						case 'n-2':
							echo '
							<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn bw-btn-' . $noise_type . '">
								<div></div>
								<div>' . $text . '</div>
							</a>';
						break;
						case 'n-3':
							echo '
							<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn bw-btn-' . $noise_type . '" data-text="' . $text . '">
								<div>' . $text . '</div>
							</a>';
						break;
						default:
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn">' . $text . '</a>';
						break;
					}
				break;
				case 'abstract':
					switch ($abstract_type) {
						case 'a-1':
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn">' . $text . '<span><?xml version="1.0" ?><!DOCTYPE svg  PUBLIC \'-//W3C//DTD SVG 1.1//EN\'  \'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\'><svg height="512px" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="160,115.4 180.7,96 352,256 180.7,416 160,396.7 310.5,256 "/></svg></span></a>';
						break;
						case 'a-2':
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn"><div id="btx-a1">' . $text . '</div></a>';
							echo '
							<script>
								jQuery(document).ready(function () {
									\'use strict\';
									const FPS = 7;
									const DURATION = 200;
									const CHARACTERS = \'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\';
									const TEXT = \'' . $text . '\';
									const DELAY = ~~(300 / FPS);
									const FRAME_COUNT = ~~(DURATION / 200) * FPS
									const $Element = document.getElementById(\'btx-a1\');
									let frameIndex = 0;
									let timeoutId = undefined;
									function resetText() {
										if (timeoutId !== undefined) clearTimeout(timeoutId);
										frameIndex = 0;
										$Element.innerText = TEXT;
									}
									function setRandomText() {
										const text = Array.from({length: TEXT.length}).map(() => CHARACTERS[~~(Math.random() * CHARACTERS.length)]);
										console.log(\' CHARACTERS.length \');
										$Element.innerText = text.join(\'\');
									}
									function animate() {
										if (frameIndex >= FRAME_COUNT) {
											resetText();
										} else {
											frameIndex += 1;
											setRandomText();
											timeoutId = setTimeout(animate, DELAY);
										}
									}
									$Element.addEventListener(\'mouseenter\', animate);
									$Element.addEventListener(\'mouseout\', resetText);
									resetText();
								});
							</script>';
						break;
						default:
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn">' . $text . '</a>';
						break;
					}
				break;
				case'fancy':
					switch ($fancy_type) {
						case 'f-2':
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn"><span></span><span></span><span></span><span></span>' . $text . '</a>';
						break;
						case 'f-3':
						case 'f-4':
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . '><div class="bw-btn"><div>' . $text . '</div><div>' . $text . '</div></div></a>';
						break;
						case 'f-5':
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn"><svg><rect x="0" y="0" fill="none" width="100%" height="100%"/></svg>' . $text . '</a>';
						break;
						default:
							echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn">' . $text . '</a>';
						break;
					}
				break;
				default:
					echo '<a href="' . $settings['website_link']['url'] . '"' . $target . $nofollow . ' class="bw-btn">' . $text . '</a>';
				break;
			}
		echo '</div>';

	}

}