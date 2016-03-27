<?php

class Thim_Gallery_Images_Widget extends Thim_Widget {

	function __construct() {

		parent::__construct(
			'gallery-images',
			esc_html__( 'Thim: Gallery Images', 'eduma' ),
			array(
				'description' => esc_html__( 'Add gallery image', 'eduma' ),
				'help'        => '',
				'panels_groups' => array('thim_widget_group'),
				'panels_icon' => 'dashicons dashicons-welcome-learn-more'
			),
			array(),
			array(
				'image'         => array(
					'type'        => 'multimedia',
					'label'       => esc_html__( 'Image', 'eduma' ),
					'description' => esc_html__( 'Select image from media library.', 'eduma' )
				),

				'image_size'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image size', 'eduma' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full"', 'eduma' )
				),
				'image_link'    => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Image Link', 'eduma' ),
					'description' => esc_html__( 'Enter URL if you want this image to have a link. These links are separated by comma (Ex: #,#,#,#)', 'eduma' )
				),
				'number'        => array(
					'type'    => 'number',
					'default' => '4',
					'label'   => esc_html__( 'Visible Item', 'eduma' ),
				),
				'link_target'   => array(
					"type"    => "select",
					"label"   => esc_html__( "Link Target", 'eduma' ),
					"options" => array(
						"_self"  => esc_html__( "Same window", 'eduma' ),
						"_blank" => esc_html__( "New window", 'eduma' ),
					),
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
				),
			),
			THIM_DIR . 'inc/widgets/gallery-images/'
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


function thim_gallery_images_widget() {
	register_widget( 'Thim_Gallery_Images_Widget' );
}

add_action( 'widgets_init', 'thim_gallery_images_widget' );