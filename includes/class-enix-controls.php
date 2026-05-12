<?php
/**
 * Enix Animation Controls
 *
 * @package Enix_Animation
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

use Elementor\Controls_Manager;

class Enix_Animation_Controls {

	private $element;

	public function __construct( $element ) {
		$this->element = $element;
	}

	public function enix_register() {

		$this->element->start_controls_section(
			'enix_animation_section',
			array(
				'label' => esc_html__( 'Enix Animation', 'enix-animation' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		// 1. Animation Type (20+ styles)
		$this->element->add_control(
			'enix_animation_type',
			array(
				'label'       => esc_html__( 'Animation Type', 'enix-animation' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'render_type' => 'none',
				'options'     => array(
					'none'         => esc_html__( '— None —', 'enix-animation' ),
					'fade-in'      => esc_html__( 'Fade In', 'enix-animation' ),
					'fade-up'      => esc_html__( 'Fade Up', 'enix-animation' ),
					'fade-down'    => esc_html__( 'Fade Down', 'enix-animation' ),
					'fade-left'    => esc_html__( 'Fade Left', 'enix-animation' ),
					'fade-right'   => esc_html__( 'Fade Right', 'enix-animation' ),
					'slide-up'     => esc_html__( 'Slide Up', 'enix-animation' ),
					'slide-down'   => esc_html__( 'Slide Down', 'enix-animation' ),
					'slide-left'   => esc_html__( 'Slide Left', 'enix-animation' ),
					'slide-right'  => esc_html__( 'Slide Right', 'enix-animation' ),
					'zoom-in'      => esc_html__( 'Zoom In', 'enix-animation' ),
					'zoom-out'     => esc_html__( 'Zoom Out', 'enix-animation' ),
					'zoom-in-up'   => esc_html__( 'Zoom In Up', 'enix-animation' ),
					'zoom-in-down' => esc_html__( 'Zoom In Down', 'enix-animation' ),
					'flip-x'       => esc_html__( 'Flip X', 'enix-animation' ),
					'flip-y'       => esc_html__( 'Flip Y', 'enix-animation' ),
					'flip-up'      => esc_html__( 'Flip Up', 'enix-animation' ),
					'flip-down'    => esc_html__( 'Flip Down', 'enix-animation' ),
					'rotate-in'    => esc_html__( 'Rotate In', 'enix-animation' ),
					'bounce-in'    => esc_html__( 'Bounce In', 'enix-animation' ),
					'bounce-up'    => esc_html__( 'Bounce Up', 'enix-animation' ),
					'blur-in'      => esc_html__( 'Blur In', 'enix-animation' ),
					'skew-in'      => esc_html__( 'Skew In', 'enix-animation' ),
				),
			)
		);

		// 2. Duration
		$this->element->add_control(
			'enix_animation_duration',
			array(
				'label'       => esc_html__( 'Duration', 'enix-animation' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'normal',
				'render_type' => 'none',
				'options'     => array(
					'fast'   => esc_html__( 'Fast (300ms)', 'enix-animation' ),
					'normal' => esc_html__( 'Normal (600ms)', 'enix-animation' ),
					'slow'   => esc_html__( 'Slow (1000ms)', 'enix-animation' ),
				),
				'condition'   => array( 'enix_animation_type!' => 'none' ),
			)
		);

		// 3. Delay
		$this->element->add_control(
			'enix_animation_delay',
			array(
				'label'       => esc_html__( 'Delay (ms)', 'enix-animation' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 3000,
				'step'        => 100,
				'default'     => 0,
				'render_type' => 'none',
				'condition'   => array( 'enix_animation_type!' => 'none' ),
			)
		);

		// 4. Easing
		$this->element->add_control(
			'enix_animation_easing',
			array(
				'label'       => esc_html__( 'Easing', 'enix-animation' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'ease-out',
				'render_type' => 'none',
				'options'     => array(
					'linear'                            => 'Linear',
					'ease'                              => 'Ease',
					'ease-in'                           => 'Ease In',
					'ease-out'                          => 'Ease Out',
					'ease-in-out'                       => 'Ease In Out',
					'cubic-bezier(0.25,0.46,0.45,0.94)' => 'Smooth',
					'cubic-bezier(0.68,-0.55,0.27,1.55)'=> 'Back',
				),
				'condition'   => array( 'enix_animation_type!' => 'none' ),
			)
		);

		// 5. Trigger Offset
		$this->element->add_control(
			'enix_animation_offset',
			array(
				'label'       => esc_html__( 'Trigger Offset (px)', 'enix-animation' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 600, 'step' => 5 ) ),
				'default'     => array( 'unit' => 'px', 'size' => 80 ),
				'render_type' => 'none',
				'condition'   => array( 'enix_animation_type!' => 'none' ),
			)
		);

		// 6. Animate Once
		$this->element->add_control(
			'enix_animation_once',
			array(
				'label'        => esc_html__( 'Animate Once', 'enix-animation' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'enix-animation' ),
				'label_off'    => esc_html__( 'No', 'enix-animation' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'If OFF, animation re-plays every time the element re-enters the viewport (bidirectional).', 'enix-animation' ),
				'render_type'  => 'none',
				'condition'    => array( 'enix_animation_type!' => 'none' ),
			)
		);

		$this->element->end_controls_section();
	}
}
