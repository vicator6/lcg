<?php

/**
 * Class Courses_Widget
 *
 * Widget Name: Courses
 *
 * Author: Ken
 */
class Thim_Courses_Widget extends Thim_Widget {
	function __construct() {

		parent::__construct(
			'courses',
			esc_html__( 'Thim: Courses', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display courses', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
				'panels_icon'   => 'dashicons dashicons-welcome-learn-more'
			),
			array(),
			array(
				'title' => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Heading Text', 'eduma' ),
					'default'               => esc_html__( 'Latest Courses', 'eduma' ),
					'allow_html_formatting' => true
				),

				'order'            => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Order By', 'eduma' ),
					'options'       => array(
						'popular'  => esc_html__( 'Popular', 'eduma' ),
						'latest'   => esc_html__( 'Latest', 'eduma' ),
						'category' => esc_html__( 'Category', 'eduma' )
					),
					'default'       => 'latest',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'order' )
					),
				),
				'cat_id'           => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Select Category', 'eduma' ),
					'default'       => 'all',
					'hide'          => true,
					'options'       => $this->thim_get_course_categories(),
					'state_handler' => array(
						'order[category]' => array( 'show' ),
						'order[popular]'  => array( 'hide' ),
						'order[latest]'   => array( 'hide' ),
					),
				),
				'layout'           => array(
					'type'          => 'select',
					'label'         => esc_html__( 'Widget Layout', 'eduma' ),
					'options'       => array(
						'slider'       => esc_html__( 'Slider', 'eduma' ),
						'grid'         => esc_html__( 'Grid', 'eduma' ),
						'list-sidebar' => esc_html__( 'List Sidebar', 'eduma' ),
						'megamenu'     => esc_html__( 'Mega Menu', 'eduma' ),
					),
					'default'       => 'slider',
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'layout_type' )
					),
				),
				'limit'            => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Limit number course', 'eduma' ),
					'default' => '8'
				),
				'view_all_courses' => array(
					'type'          => 'text',
					'label'         => esc_html__( 'Text View All Courses', 'eduma' ),
					'default'       => '',
					'hide'          => true,
					'state_handler' => array(
						'layout_type[slider]'       => array( 'hide' ),
						'layout_type[grid]'         => array( 'show' ),
						'layout_type[list-sidebar]' => array( 'hide' ),
					),
				),
				'slider-options'   => array(
					'type'          => 'section',
					'label'         => esc_html__( 'Slider Layout Options', 'eduma' ),
					'hide'          => true,
					"class"         => "clear-both",
					'state_handler' => array(
						'layout_type[slider]'       => array( 'show' ),
						'layout_type[grid]'         => array( 'hide' ),
						'layout_type[list-sidebar]' => array( 'hide' ),
					),
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'courses_slider_opt' )
					),
					'fields'        => array(
						'show_pagination' => array(
							'type'    => 'checkbox',
							'label'   => esc_html__( 'Show Pagination', 'eduma' ),
							'default' => false
						),
						'show_navigation' => array(
							'type'    => 'checkbox',
							'label'   => esc_html__( 'Show Navigation', 'eduma' ),
							'default' => true
						),
						'item_visible'    => array(
							'type'    => 'select',
							'label'   => esc_html__( 'Items Visible', 'eduma' ),
							'options' => array(
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
								'5' => '5',
								'6' => '6',
							),
							'default' => '4'
						),
					),

				),

				'grid-options' => array(
					'type'          => 'section',
					'label'         => esc_html__( 'Grid Layout Options', 'eduma' ),
					'hide'          => true,
					"class"         => "clear-both",
					'state_handler' => array(
						'layout_type[slider]'       => array( 'hide' ),
						'layout_type[grid]'         => array( 'show' ),
						'layout_type[list-sidebar]' => array( 'hide' ),
						'layout_type[megamenu]'     => array( 'hide' ),
					),
					'state_emitter' => array(
						'callback' => 'select',
						'args'     => array( 'courses_grid_opt' )
					),
					'fields'        => array(
						'columns' => array(
							'type'    => 'select',
							'label'   => esc_html__( 'Columns', 'eduma' ),
							'options' => array(
								'1' => '1',
								'2' => '2',
								'3' => '3',
								'4' => '4',
								'5' => '5',
								'6' => '6',
							),
							'default' => '4'
						),

					),

				),


			)
		);
	}

	function get_template_name( $instance ) {
		if ( $instance['layout'] && '' != $instance['layout'] ) {
			$layout = $instance['layout'];
		}
		if ( thim_is_new_learnpress() ) {
			$layout .= '-v1';
		}

		return $layout;
	}

	function get_style_name( $instance ) {
		return false;
	}

	// Get list category
	function thim_get_course_categories() {
		global $wpdb;
		$query = $wpdb->get_results( $wpdb->prepare(
			"
				  SELECT      t1.term_id, t2.name
				  FROM        $wpdb->term_taxonomy AS t1
				  INNER JOIN $wpdb->terms AS t2 ON t1.term_id = t2.term_id
				  WHERE t1.taxonomy = %s
				  AND t1.count > %d
				  ",
			'course_category', 0
		) );

		$cats        = array();
		$cats['all'] = 'All';
		if ( ! empty( $query ) ) {
			foreach ( $query as $key => $value ) {
				$cats[ $value->term_id ] = $value->name;
			}
		}

		return $cats;
	}

}

function thim_courses_register_widget() {
	register_widget( 'Thim_Courses_Widget' );
}

add_action( 'widgets_init', 'thim_courses_register_widget' );