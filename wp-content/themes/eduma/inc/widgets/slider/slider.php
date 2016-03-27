<?php

class Thim_Slider_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'slider',
			esc_html__( 'Thim: Slider', 'eduma' ),
			array(
				'description'   => esc_html__( 'Thim Slider', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon' => 'dashicons dashicons-welcome-learn-more'
			),
			array(),
			array(
				'thim_slider_frames'  => array(
					'type'      => 'repeater',
					'label'     => esc_html__( 'Slider Frames', 'eduma' ),
					'item_name' => esc_html__( 'Frame', 'eduma' ),
					'fields'    => array(
						'thim_slider_background_image' => array(
							'type'    => 'media',
							'library' => 'image',
							'label'   => esc_html__( 'Background Image', 'eduma' ),
						),
						'color_overlay'                => array(
							'type'  => 'color',
							'label' => esc_html__( 'Color Overlay images', 'eduma' ),
						),
						'content'                      => array(
							'type'   => 'section',
							'label'  => esc_html__( 'Content Slider', 'eduma'),
							'hide'   => true,
							'fields' => array(
								'thim_slider_icon'        => array(
									'type'    => 'media',
									'library' => 'image',
									'label'   => esc_html__( 'Icon', 'eduma' ),
								),
								'thim_slider_title'       => array(
									'type'                  => 'text',
									'label'                 => esc_html__( 'Heading Slider', 'eduma' ),
									'allow_html_formatting' => true,
								),
								'size'                    => array(
									'type'        => 'number',
									'label'       => esc_html__( 'Custom Font Size Title', 'eduma' ),
									'description' => 'input custom font size: ex: 30',
									'class'       => 'color-mini',
								),
								'thim_color_title'        => array(
									'type'  => 'color',
									'label' => esc_html__( 'Heading Color Title', 'eduma' ),
									'class' => 'color-mini',
								),
								'line-bottom'             => array(
									'type'    => 'checkbox',
									'label'   => esc_html__( 'line bottom', 'eduma' ),
									'default' => false,
									'class'   => 'color-mini',
								),
								'thim_slider_description' => array(
									'type'                  => 'textarea',
									'label'                 => esc_html__( 'Description', 'eduma' ),
									'allow_html_formatting' => true,
									'class'                 => 'clear-both',
								),
								'thim_color_des'          => array(
									'type'  => 'color',
									'label' => esc_html__( 'Description Color', 'eduma' )
								),
								'thim_slider_align'       => array(
									'type'    => 'select',
									'label'   => esc_html__( 'Content Align:', 'eduma' ),
									'options' => array(
										'left'   => esc_html__( 'Left', 'eduma' ),
										'right'  => esc_html__( 'Right', 'eduma' ),
										'center' => esc_html__( 'Center', 'eduma' )
									),
								),
							),
						),
					),
				),
				'thim_slider_speed'   => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Animation Speed', 'eduma' ),
					'description' => esc_html__( 'Animation speed in milliseconds.', 'eduma' ),
					'default'     => 800,
				),
				'thim_slider_timeout' => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Timeout', 'eduma' ),
					'description' => esc_html__( 'How long each slide is displayed for in milliseconds.', 'eduma' ),
					'default'     => 8000,
				),
				'slider_full_screen'  => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Full Screen', 'eduma' ),
					'default' => false ),
				'show_icon_scroll'    => array(
					'type'          => 'radio',
					'label'         => esc_html__( 'Icon Scroll', 'eduma' ),
					'options'       => array(
						'show' => esc_html__( 'Show', 'eduma' ),
						'hide' => esc_html__( 'Hide', 'eduma' )
					),
					'default'       => 'hide',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'show_icon_scroll_type' )
					)
				),
				'text_before_btn'     => array(
					'type'          => 'text',
					'label'         => esc_html__( 'Text Before Arrow', 'eduma' ),
					'state_handler' => array(
						'show_icon_scroll_type[show]' => array( 'show' ),
						'show_icon_scroll_type[hide]' => array( 'hide' ),
					),
					'default'       => esc_html__( 'Getting started' , 'eduma')
				),
				'button_id'           => array(
					'type'          => 'text',
					'label'         => esc_html__( 'ID', 'eduma' ),
					'state_handler' => array(
						'show_icon_scroll_type[show]' => array( 'show' ),
						'show_icon_scroll_type[hide]' => array( 'hide' ),
					),
					'description'   => esc_html__( 'id section scoll', 'eduma' ),
				),
			),
			THIM_DIR . 'inc/widgets/slider/'
		);
	}

	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return false;
	}

	/**
	 * Enqueue the slider scripts
	 */
	function enqueue_frontend_scripts() {
		wp_enqueue_script( 'thim-jquery-cycle', THIM_URI . 'inc/widgets/slider/js/jquery.cycle.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'thim-cycle.swipe', THIM_URI . 'inc/widgets/slider/js/jquery.cycle.swipe.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'thim-slider', THIM_URI . 'inc/widgets/slider/js/slider.js', array( 'jquery' ), '', true );
	}
}

function thim_slider_register_widget() {
	register_widget( 'Thim_Slider_Widget' );
}

add_action( 'widgets_init', 'thim_slider_register_widget' );