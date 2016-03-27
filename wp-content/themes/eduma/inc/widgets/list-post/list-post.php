<?php

class Thim_List_Post_Widget extends Thim_Widget {
	function __construct() {
		$list_image_size = $this->get_image_sizes();
		$image_size = array();
		$image_size['none'] = esc_html__("No Image", 'eduma');
		if(is_array($list_image_size) && !empty($list_image_size)){
			foreach( $list_image_size as $key=>$value){
				if($value['width'] && $value['height']){
					$image_size[$key] = $value['width'].'x'.$value['height']; 
				}else{
					$image_size[$key] = $key;
				}
			}
		}
		parent::__construct(
			'list-post',
			esc_html__( 'Thim: List Posts', 'eduma' ),
			array(
				'description'   => esc_html__( 'Display list posts', 'eduma' ),
				'help'          => '',
				'panels_groups' => array( 'thim_widget_group' ),

			),
			array(),
			array(
				'title'        => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Title', 'eduma' ),
					'default' => esc_html__( "From Blog", 'eduma' )
				),
				'cat_id' => array(
					'type' 		=> 'select',
					'label'		=> esc_html__('Select Category', 'eduma'),
					'default'	=> 'none',
					'options'	=> $this->thim_get_categories()
				),
				'image_size' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Select Image Size', 'eduma' ),
					'default' => 'none',
					'options' => $image_size,
				),
				'show_description' =>array(
					'type'		=> 'radio',
					'label'		=> esc_html__('Show Description', 'eduma'),
					'default'	=> 'yes',
					'options'	=> array(
						'no' => esc_html__("No", 'eduma'),
						'yes' => esc_html__("Yes", 'eduma'),
					)
				),
				'number_posts' => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number Post', 'eduma' ),
					'default' => '4'
				),
				'orderby'      => array(
					"type"    => "select",
					"label"   => esc_html__( "Order by", 'eduma' ),
					"options" => array(
						"popular" => esc_html__( "Popular", 'eduma' ),
						"recent"  => esc_html__( "Recent", 'eduma' ),
						"title"   => esc_html__( "Title", 'eduma' ),
						"random"  => esc_html__( "Random", 'eduma' ),
					),
				),
				'order'        => array(
					"type"    => "select",
					"label"   => esc_html__( "Order by", 'eduma' ),
					"options" => array(
						"asc"  => esc_html__( "ASC", 'eduma' ),
						"desc" => esc_html__( "DESC", 'eduma' )
					),
				),
				'link'        => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Link All Post', 'eduma' ),
 				),
				'text_link'        => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Text Link', 'eduma' ),
 				),
 				'style'      => array(
					"type"    => "select",
					"label"   => esc_html__( "Style", 'eduma' ),
					"options" => array(
						""			=> esc_html__( "No Style", 'eduma' ),
						"homepage" 	=> esc_html__( "Home Page", 'eduma' ),
						"sidebar"  	=> esc_html__( "Sidebar", 'eduma' ),
					),
				),
			),
			THIM_DIR . 'inc/widgets/list-post/'
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
	
	// list image size
    function get_image_sizes( $size = '' ) {

        global $_wp_additional_image_sizes;

        $sizes = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach( $get_intermediate_image_sizes as $_size ) {

                if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {

                        $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
                        $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                        $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );

                } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

                        $sizes[ $_size ] = array(
                                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
                        );

                }

        }

        // Get only 1 size if found
        if ( $size ) {

                if( isset( $sizes[ $size ] ) ) {
                        return $sizes[ $size ];
                } else {
                        return false;
                }

        }

        return $sizes;
    }

	// Get list category
    function thim_get_categories(){
    	$args = array(
		  'orderby' 	=> 'id',
		  'parent' 		=> 0
		 );
		$items = array();
		$items['all'] = 'All';
		$categories = get_categories( $args );
		if (isset($categories)) {
			foreach ($categories as $key => $cat) {
				$items[$cat -> cat_ID] = $cat -> cat_name;
				$childrens = get_term_children($cat->term_id, $cat->taxonomy);
				if ($childrens){
					foreach ($childrens as $key => $children) {
						$child = get_term_by( 'id', $children, $cat->taxonomy);
						$items[$child->term_id] = '--'.$child->name;

					}
				}
			}
		}
		return $items;
    }
}

function thim_list_post_register_widget() {
	register_widget( 'Thim_List_Post_Widget' );
}

add_action( 'widgets_init', 'thim_list_post_register_widget' );