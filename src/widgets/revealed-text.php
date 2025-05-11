<?php
namespace Modernaweb\BlackWidgets\Widgets;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;

class RevealedText extends \Elementor\Widget_Base {

    public function __construct( $data = [], $args = null ) {
        parent::__construct( $data, $args );
        wp_register_style( 'black-widgets-revealed-text', BLACK_WIDGETS_PLUGIN_URL . 'assets/css/revealed-text.css', [], BLACK_WIDGETS_VERSION );
        wp_register_script( 'black-widgets-revealed-text', BLACK_WIDGETS_PLUGIN_URL . 'assets/js/revealed-text.js', ['jquery'], BLACK_WIDGETS_VERSION );
    }

	public function get_name() {
		return 'b_revealed_text';
	}

	public function get_title() {
		return __( 'Black Revealed Text', 'black-widgets' );
	}

	public function get_icon() {
		return 'eicon-wordart';
	}

	public function get_categories() {
		return [ 'black_widgets' ];
	}

    public function get_style_depends() {
        return [ 'black-widgets-revealed-text' ];
    }

    public function get_script_depends() {
        return [ 'black-widgets-revealed-text' ];
    }

    protected function is_dynamic_content(): bool {
        return false;
    }

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'widget_text',
			[
				'label' => esc_html__( 'Text', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 10,
				'default' => esc_html__( 'Black Widget Revealed Text', 'black-widgets' ),
				'placeholder' => esc_html__( 'Type your text here', 'black-widgets' ),
			]
		);

		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

        $this->add_control(
			'widget_sensivity_factor',
			[
				'label' => esc_html__( 'Sensivity Factor', 'black-widgets' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0.1,
				'max' => 10,
				'default' => 1,
                'description' => esc_html__( 'Larger value causes the reveal effect to happen sooner', 'black-widgets' ),
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'widget_alignment',
			[
				'label'     => esc_html__( 'Text Alignment', 'black-widgets' ),
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
				'selectors' => [
					'{{WRAPPER}} .bw-revealed-text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Box Style', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'widget_box_background',
				'label' => esc_html__( 'Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-revealed-text',
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'widget_box_margin',
			[
				'label' => esc_html__( 'Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-revealed-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'widget_box_padding',
			[
				'label' => esc_html__( 'Padding', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-revealed-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr4',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_box_border',
				'label' => esc_html__( 'Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-revealed-text',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-revealed-text',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'typography_section',
			[
				'label' => esc_html__( 'Typography', 'black-widgets' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'widget_typography_text_color',
			[
				'label' => esc_html__( 'Color', 'black-widgets' ),
				'type' => Controls_Manager::COLOR,
                'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .bw-revealed-text p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'widget_typography_typography',
				'label' => esc_html__( 'Typography', 'black-widgets' ),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
				'selector' => '{{WRAPPER}} .bw-revealed-text p',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'widget_typography_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-revealed-text p',
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
				'name' => 'widget_typography_text_background',
				'label' => esc_html__( 'Text Background', 'black-widgets' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .bw-revealed-text p',
			]
		);

		$this->add_control(
			'hr6',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'widget_typography_text_margin',
			[
				'label' => esc_html__( 'Text Margin', 'black-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bw-revealed-text p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bw-revealed-text p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr7',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'widget_typography_title_border',
				'label' => esc_html__( 'Title Border', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-revealed-text p',
			]
		);

		$this->add_control(
			'widget_typography_title_border_radius',
			[
				'label' 		=> esc_html__( 'Border Radius', 'black-widgets' ),
				'type' 			=> \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bw-revealed-text p' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'widget_typography_title_box_shadow',
				'label' => esc_html__( 'Title Box Shadow', 'black-widgets' ),
				'selector' => '{{WRAPPER}} .bw-revealed-text p',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

        $text = isset( $settings['widget_text'] ) ? $settings['widget_text'] : '';
        $factor = isset( $settings['widget_sensivity_factor'] ) ? $settings['widget_sensivity_factor'] : '1';
        ?>
        <div class="bw-revealed-text" data-factor="<?php echo esc_attr( $factor ); ?>"> 
            <p><?php echo esc_html( $text ); ?></p>
        </div>
        <?php
	}

}	
