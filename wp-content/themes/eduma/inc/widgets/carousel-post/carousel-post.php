<?php

class Thim_Carousel_Post_Widget extends Thim_Widget {

	function __construct() {
		parent::__construct(
			'carousel-post',
			esc_html__( 'Thim: Carousel Posts', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display Post with Carousel', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),
			),
			array(),
			array(
				'title'           => array(
					'type'                  => 'text',
					'label'                 => esc_html__( 'Heading Text', 'eduma' ),
					'default'               => esc_html__( 'Post Carousel', 'eduma' ),
					'allow_html_formatting' => true
				),
				'cat_id'          => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Select Category', 'eduma' ),
					'default' => 'all',
					'options' => $this->thim_get_categories()
				),
				'visible_post'    => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Posts visible', 'eduma' ),
					'default' => '3'
				),
				'number_posts'    => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Number posts', 'eduma' ),
					'default' => '6'
				),
				'show_nav'        => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Navigation', 'eduma' ),
					'default' => 'yes',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'show_pagination' => array(
					'type'    => 'radio',
					'label'   => esc_html__( 'Show Pagination', 'eduma' ),
					'default' => 'no',
					'options' => array(
						'no'  => esc_html__( 'No', 'eduma' ),
						'yes' => esc_html__( 'Yes', 'eduma' ),
					)
				),
				'orderby'         => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Order by', 'eduma' ),
					'options' => array(
						'popular' => esc_html__( 'Popular', 'eduma' ),
						'recent'  => esc_html__( 'Recent', 'eduma' ),
						'title'   => esc_html__( 'Title', 'eduma' ),
						'random'  => esc_html__( 'Random', 'eduma' ),
					),
				),
				'order'           => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Order by', 'eduma' ),
					'options' => array(
						'asc'  => esc_html__( 'ASC', 'eduma' ),
						'desc' => esc_html__( 'DESC', 'eduma' )
					),
				),

			),
			THIM_DIR . 'inc/widgets/carousel-post/'
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

	function enqueue_frontend_scripts() {
		wp_enqueue_script( 'thim-owl-carousel' );
	}

	// Get list category
	function thim_get_categories() {
		$args         = array(
			'orderby' => 'id',
			'parent'  => 0
		);
		$items        = array();
		$items['all'] = 'All';
		$categories   = get_categories( $args );
		if ( isset( $categories ) ) {
			foreach ( $categories as $key => $cat ) {
				$items[ $cat->cat_ID ] = $cat->cat_name;
				$childrens             = get_term_children( $cat->term_id, $cat->taxonomy );
				if ( $childrens ) {
					foreach ( $childrens as $key => $children ) {
						$child                    = get_term_by( 'id', $children, $cat->taxonomy );
						$items[ $child->term_id ] = '--' . $child->name;

					}
				}
			}
		}

		return $items;
	}
}

function thim_carousel_post_widget() {
	register_widget( 'Thim_Carousel_Post_Widget' );
}

add_action( 'widgets_init', 'thim_carousel_post_widget' );