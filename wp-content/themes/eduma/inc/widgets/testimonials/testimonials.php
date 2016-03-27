<?php
if ( class_exists( 'THIM_Testimonials' ) ) {
	class Thim_Testimonials_Widget extends Thim_Widget {
		function __construct() {
			parent::__construct(
				'testimonials',
				esc_html__( 'Thim: Testimonials', 'eduma' ),
				array(
					'description'   => esc_html__( '', 'eduma' ),
					'help'          => '',
					'panels_groups' => array( 'thim_widget_group' ),
					'panels_icon'   => 'dashicons dashicons-welcome-learn-more'
				),
				array(),
				array(
					'title'               => array(
						'type'                  => 'text',
						'label'                 => esc_html__( 'Heading Text', 'eduma' ),
						'default'               => esc_html__( 'Testimonials', 'eduma' ),
						'allow_html_formatting' => true
					),
					'limit'        => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Limit Posts', 'eduma' ),
						'default' => '7'
					),
					'item_visible'        => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Item visible', 'eduma' ),
						'desc'	  => esc_html__('Enter odd number', 'eduma'),	
						'default' => '5'
					),
					'autoplay'             => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Auto play', 'eduma' ),
						'default' => false,
					),
					'mousewheel'             => array(
						'type'    => 'checkbox',
						'label'   => esc_html__( 'Mousewheel Scroll', 'eduma' ),
						'default' => false,
					),
					
				),
				THIM_DIR . 'inc/widgets/testimonials/'
			);
		}

		/**
		 * Initialize the CTA widget
		 */


		function get_template_name( $instance ) {
			return 'base';
		}

		function get_style_name( $instance ) {
			return false;
		}

	}

	function thim_testimonials_register_widget() {
		register_widget( 'Thim_Testimonials_Widget' );
	}

	add_action( 'widgets_init', 'thim_testimonials_register_widget' );
}