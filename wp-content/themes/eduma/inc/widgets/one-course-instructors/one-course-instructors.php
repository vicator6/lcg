<?php

class Thim_One_Course_Instructors_Widget extends Thim_Widget {
	function __construct() {
		parent::__construct(
			'one-course-instructors',
			esc_html__( 'Thim: 1 Course Instructors', 'eduma' ),
			array(
				'description'   => esc_html__( 'Show carousel slider instructors of one course feature.', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'dashicons dashicons-welcome-learn-more'
			),
			array(),
			array(
				'visible_item'   => array(
						'type'    => 'number',
						'label'   => esc_html__( 'Visible instructors', 'eduma' ),
						'default' => '3'
				),
				'show_pagination' =>array(
						'type'		=> 'radio',
						'label'		=> esc_html__('Show Pagination', 'eduma'),
						'default'	=> 'yes',
						'options'	=> array(
								'no' => esc_html__('No', 'eduma'),
								'yes' => esc_html__('Yes', 'eduma'),
						)
				),
			),
			
			THIM_DIR . 'inc/widgets/one-course-instructors/'
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

function thim_one_course_instructors_register_widget() {
	register_widget( 'Thim_One_Course_Instructors_Widget' );

}

add_action( 'widgets_init', 'thim_one_course_instructors_register_widget' );

