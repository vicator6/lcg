<?php

class Thim_List_Event_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'list-event',
			esc_html__( 'Thim: List Events ', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display list events', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
			),
			array(),
			array(
				'title'        => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Title', 'eduma' ),
					'allow_html_formatting' => true
				),
				'layout'       => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Layout', 'eduma' ),
					'options' => array(
						'base'     => esc_html__( 'Default', 'eduma' ),
						'layout-2' => esc_html__( 'Layout 2', 'eduma' ),
					),
					'default' => 'base'
				),
				'number_posts' => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number posts', 'eduma' ),
					'default' => '2'
				),
				'text_link'    => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Text Link All', 'eduma' ),
					'default'               => esc_html__( 'View All', 'eduma' ),
					'allow_html_formatting' => true
				),

			),
			THIM_DIR . 'inc/widgets/list-event/'
		);
	}

	/**
	 * Initialize the CTA widget
	 */

	function get_template_name( $instance ) {
		if ( isset( $instance['layout'] ) && $instance['layout'] == 'layout-2' ) {
			return $instance['layout'];
		} else {
			return 'base';
		}

	}

	function get_style_name( $instance ) {
		return false;
	}
}

function thim_list_event_widget() {
	register_widget( 'Thim_List_Event_Widget' );
}

add_action( 'widgets_init', 'thim_list_event_widget' );