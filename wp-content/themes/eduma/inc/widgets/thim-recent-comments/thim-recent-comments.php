<?php

class Thim_Recent_Comments_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'thim-recent-comments',
			esc_html__( 'Thim: Recent Comments', 'eduma' ),
			array(
				'description'   => esc_html__( '', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'dashicons dashicons-welcome-learn-more'
			),
			array(),
			array(
				'heading_group'   => array(
					'type'   => 'section',
					'label'  => esc_html__( 'Heading', 'eduma' ),
					'hide'   => true,
					'fields' => array(
						'title'               => array(
							'type'                  => 'text',
							'label'                 => esc_html__( 'Heading Text', 'eduma' ),
							'default'               => esc_html__( "Recent Comments", 'eduma' ),
							'allow_html_formatting' => true
						),
						'textcolor'           => array(
							'type'  => 'color',
							'label' => esc_html__( 'Text Heading color', 'eduma' ),
							"class" => "color-mini",
						),
						'size'                => array(
							"type"    => "select",
							"label"   => esc_html__( "Size Heading", 'eduma' ),
							"default" => "h4",
							"options" => array(
								"h2" => esc_html__( "h2", 'eduma' ),
								"h3" => esc_html__( "h3", 'eduma' ),
								"h4" => esc_html__( "h4", 'eduma' ),
								"h5" => esc_html__( "h5", 'eduma' ),
								"h6" => esc_html__( "h6", 'eduma' )
							),
							"class"   => "color-mini",
						),
						'font_heading'        => array(
							"type"          => "select",
							"label"         => esc_html__( "Font Heading", 'eduma' ),
							"default"       => "default",
							"options"       => array(
								"default" => esc_html__( "Default", 'eduma' ),
								"custom"  => esc_html__( "Custom", 'eduma' )
							),
							"description"   => esc_html__( "Select Font heading.", 'eduma' ),
							'state_emitter' => array(
								'callback' => 'select',
								'args'     => array( 'font_heading_type' )
							),
							"class"         => "color-mini",
						),
						'custom_font_heading' => array(
							'type'          => 'section',
							'label'         => esc_html__( 'Custom Font Heading', 'eduma' ),
							'hide'          => true,
							"class"         => "clear-both",
							'state_handler' => array(
								'font_heading_type[custom]'  => array( 'show' ),
								'font_heading_type[default]' => array( 'hide' ),
							),
							'fields'        => array(
								'custom_font_size'   => array(
									"type"        => "number",
									"label"       => esc_html__( "Font Size", 'eduma' ),
									"suffix"      => "px",
									"default"     => "18",
									"description" => esc_html__( "custom font size", 'eduma' ),
									"class"       => "color-mini",
								),
								'custom_font_weight' => array(
									"type"        => "select",
									"label"       => esc_html__( "Custom Font Weight", 'eduma' ),
									"options"     => array(
										"normal" => esc_html__( "Normal", 'eduma' ),
										"bold"   => esc_html__( "Bold", 'eduma' ),
										"100"    => esc_html__( "100", 'eduma' ),
										"200"    => esc_html__( "200", 'eduma' ),
										"300"    => esc_html__( "300", 'eduma' ),
										"400"    => esc_html__( "400", 'eduma' ),
										"500"    => esc_html__( "500", 'eduma' ),
										"600"    => esc_html__( "600", 'eduma' ),
										"700"    => esc_html__( "700", 'eduma' ),
										"800"    => esc_html__( "800", 'eduma' ),
										"900"    => esc_html__( "900", 'eduma' )
									),
									"description" => esc_html__( "Select Custom Font Weight", 'eduma' ),
									"class"       => "color-mini",
								),
							),
						),
					),
				),

				't_config'        => array(
					'type'   => 'section',
					'label'  => esc_html__( 'Config Color', 'eduma' ),
					'hide'   => true,
					'fields' => array(
						// text color
						'title_color' => array(
							'type'  => 'color',
							'label' => esc_html__( 'Title Color', 'eduma' ),
							"class" => "color-mini"

						),
						'meta_color'  => array(
							'type'  => 'color',
							'label' => esc_html__( 'Meta color', 'eduma' ),
							"class" => "color-mini"
						),
					),
				),
				'number_comments' => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number Comments', 'eduma' ),
					'default' => esc_html__( "4", 'eduma' )
				),
				'css_animation'   => array(
					"type"    => "select",
					"label"   => esc_html__( "CSS Animation", 'eduma' ),
					"options" => array(
						""            => esc_html__( "No", 'eduma' ),
						"top-to-bottom" => esc_html__( "Top to bottom", 'eduma' ),
						"bottom-to-top" => esc_html__( "Bottom to top", 'eduma' ),
						"left-to-right" => esc_html__( "Left to right", 'eduma' ),
						"right-to-left" => esc_html__( "Right to left", 'eduma' ),
						"appear"        => esc_html__( "Appear from center", 'eduma' )
					),
					'default' => ''
				)

			),
			THIM_DIR . 'inc/widgets/recent-comments/'
		);
	}


	function get_template_name( $instance ) {
		return 'base';
	}

	function get_style_name( $instance ) {
		return 'basic';
	}
}

function thim_recent_comments_register_widget() {
	register_widget( 'Thim_Recent_Comments_Widget' );
}

add_action( 'widgets_init', 'thim_recent_comments_register_widget' );