<?php

class Thim_Counters_Box_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'counters-box',
			esc_html__( 'Thim: Counters Box', 'eduma' ),
			array(
				'description'   => esc_html__( 'Counters Box', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
			),
			array(),
			array(

				'counters_label' => array(
					"type"  => "text",
					"label" => esc_html__( "Counters label", 'eduma' ),
				),

				'counters_value' => array(
					"type"    => "number",
					"label"   => esc_html__( "Counters Value", 'eduma' ),
					"default" => "20",
				),

				'icon'         => array(
					"type"  => "icon",
					"label" => esc_html__( "Icon", 'eduma' ),
				),
				'border_color' => array(
					"type"  => "color",
					"label" => esc_html__( "Border Color Icon", 'eduma' ),
				),

				'counter_color' => array(
					"type"  => "color",
					"label" => esc_html__( "Counters Box Icon", 'eduma' ),
				),

				'style' => array(
					"type"    => "select",
					"label"   => esc_html__( "Counter Style", 'eduma' ),
					"options" => array(
						"home-page" => esc_html__( "Home Page", 'eduma' ),
						"about-us"  => esc_html__( "Page About Us", 'eduma' ),
					),
					'default' => 'home-page'
				),

				'css_animation' => array(
					"type"    => "select",
					"label"   => esc_html__( "CSS Animation", 'eduma' ),
					"options" => array(
						""              => esc_html__( "No", 'eduma' ),
						"top-to-bottom" => esc_html__( "Top to bottom", 'eduma' ),
						"bottom-to-top" => esc_html__( "Bottom to top", 'eduma' ),
						"left-to-right" => esc_html__( "Left to right", 'eduma' ),
						"right-to-left" => esc_html__( "Right to left", 'eduma' ),
						"appear"        => esc_html__( "Appear from center", 'eduma' )
					),
				)
			),
			THIM_DIR . 'inc/widgets/counters-box/'
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

function thim_counters_box_widget() {
	register_widget( 'Thim_Counters_Box_Widget' );
}

add_action( 'widgets_init', 'thim_counters_box_widget' );