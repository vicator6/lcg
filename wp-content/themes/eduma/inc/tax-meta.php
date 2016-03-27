<?php

if ( is_admin() ) {
	/*
	   * prefix of meta keys, optional
	   */
	$prefix = 'thim_';

	/*
	   * configure your meta box
	   */
	$config = array(
		'id'             => 'category_meta_box',
		// meta box id, unique per meta box
		'title'          => esc_html__('Category Meta Box', 'eduma'),
		// meta box title
		'pages'          => array( 'category', 'product_cat' ),
		// taxonomy name, accept categories, post_tag and custom taxonomies
		'context'        => 'normal',
		// where the meta box appear: normal (default), advanced, side; optional
		'fields'         => array(),
		// list of meta fields (can be added by field arrays)
		'local_images'   => false,
		// Use local or hosted images (meta box images for add/remove)
		'use_with_theme' => false
		//change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	);

	$config_product_cat = array(
		'id'             => 'product_cat_meta_box',
		// meta box id, unique per meta box
		'title'          => esc_html__('Category Meta Box', 'eduma'),
		// meta box title
		'pages'          => array( 'product_cat' ),
		// taxonomy name, accept categories, post_tag and custom taxonomies
		'context'        => 'normal',
		// where the meta box appear: normal (default), advanced, side; optional
		'fields'         => array(),
		// list of meta fields (can be added by field arrays)
		'local_images'   => false,
		// Use local or hosted images (meta box images for add/remove)
		'use_with_theme' => false
		//change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	);

	$config_courses_cat = array(
		'id'             => 'product_cat_meta_box',
		// meta box id, unique per meta box
		'title'          => esc_html__('Course Meta Box', 'eduma'),
		// meta box title
		'pages'          => array( 'course_category' ),
		// taxonomy name, accept categories, post_tag and custom taxonomies
		'context'        => 'normal',
		// where the meta box appear: normal (default), advanced, side; optional
		'fields'         => array(),
		// list of meta fields (can be added by field arrays)
		'local_images'   => false,
		// Use local or hosted images (meta box images for add/remove)
		'use_with_theme' => false
		//change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	);
	/*
	   * Initiate your meta box
	   */
	$my_meta            = new Tax_Meta_Class( $config );
	$product_cat_meta   = new Tax_Meta_Class( $config_product_cat );
	$config_courses_cat = new Tax_Meta_Class( $config_courses_cat );

	/*
   * Add fields to your meta box
   */
	/* blog */

	$my_meta->addSelect( $prefix . 'layout', array(
		''              => esc_html__('Using in Theme Option', 'eduma'),
		'full-content'  => esc_html__('No Sidebar', 'eduma'),
		'sidebar-left'  => esc_html__('Left Sidebar', 'eduma'),
		'sidebar-right' => esc_html__('Right Sidebar', 'eduma')
	), array( 'name' => esc_html__( 'Custom Layout ', 'eduma' ), 'std' => array( '' ) ) );

	$my_meta->addSelect( $prefix . 'custom_heading', array(
		''       => esc_html__('Using in Theme Option', 'eduma'),
		'custom' => esc_html__('Custom',  'eduma'),
	), array( 'name' => esc_html__( 'Custom Heading ', 'eduma' ), 'std' => array( '' ) ) );

	$my_meta->addImage( $prefix . 'archive_top_image', array( 'name' => esc_html__( 'Background Image Heading', 'eduma' ) ) );
	$my_meta->addColor( $prefix . 'archive_cate_heading_bg_color', array( 'name' => esc_html__( 'Background Color Heading', 'eduma' ) ) );
	$my_meta->addColor( $prefix . 'archive_cate_heading_text_color', array( 'name' => esc_html__( 'Text Color Heading', 'eduma' ) ) );
	$my_meta->addColor( $prefix . 'archive_cate_sub_heading_text_color', array( 'name' => esc_html__( 'Color Description Category', 'eduma' ) ) );
	$my_meta->addCheckbox( $prefix . 'archive_cate_hide_title', array( 'name' => esc_html__( 'Hide Title', 'eduma' ) ) );
	$my_meta->addCheckbox( $prefix . 'archive_cate_hide_breadcrumbs', array( 'name' => esc_html__( 'Hide Breadcrumbs', 'eduma' ) ) );
	$my_meta->Finish();

	// option woocommerce
	$product_cat_meta->addSelect( $prefix . 'layout', array(
		''              => esc_html__('Using in Theme Option', 'eduma'),
		'full-content'  => esc_html__('No Sidebar', 'eduma'),
		'sidebar-left'  => esc_html__('Left Sidebar', 'eduma'),
		'sidebar-right' => esc_html__('Right Sidebar', 'eduma')
	), array( 'name' => esc_html__( 'Custom Layout ', 'eduma' ), 'std' => array( '' ) ) );

	$product_cat_meta->addSelect( $prefix . 'custom_heading', array(
		''       => esc_html__('Using in Theme Option','eduma'),
		'custom' => esc_html__('Custom', 'eduma')
	), array( 'name' => esc_html__( 'Custom Heading ', 'eduma' ), 'std' => array( '' ) ) );

	$product_cat_meta->addImage( $prefix . 'woo_top_image', array( 'name' => esc_html__( 'Background Image Heading', 'eduma' ) ) );
	$product_cat_meta->addColor( $prefix . 'woo_cate_heading_bg_color', array( 'name' => esc_html__( 'Background Color Heading', 'eduma' ) ) );
	$product_cat_meta->addColor( $prefix . 'woo_cate_heading_text_color', array( 'name' => esc_html__( 'Text Color Heading', 'eduma' ) ) );
	$product_cat_meta->addColor( $prefix . 'woo_cate_sub_heading_text_color', array( 'name' => esc_html__( 'Color Description Category', 'eduma' ) ) );
	$product_cat_meta->addCheckbox( $prefix . 'woo_cate_hide_title', array( 'name' => esc_html__( 'Hide Title', 'eduma' ) ) );
	$product_cat_meta->addCheckbox( $prefix . 'woo_cate_hide_breadcrumbs', array( 'name' => esc_html__( 'Hide Breadcrumbs', 'eduma' ) ) );
	$product_cat_meta->Finish();

	// option courses
	$config_courses_cat->addSelect( $prefix . 'layout', array(
		''              => esc_html__('Using in Theme Option', 'eduma' ),
		'full-content'  => esc_html__('No Sidebar', 'eduma' ),
		'sidebar-left'  => esc_html__('Left Sidebar', 'eduma' ),
		'sidebar-right' => esc_html__('Right Sidebar', 'eduma' ),
	), array( 'name' => esc_html__( 'Custom Layout ', 'eduma' ), 'std' => array( '' ) ) );

	$config_courses_cat->addSelect( $prefix . 'custom_heading', array(
		''       => esc_html__('Using in Theme Option', 'eduma' ),
		'custom' => esc_html__('Custom', 'eduma' ),
	), array( 'name' => esc_html__( 'Custom Heading ', 'eduma' ), 'std' => array( '' ) ) );

	$config_courses_cat->addImage( $prefix . 'learnpress_top_image', array( 'name' => esc_html__( 'Background Image Heading', 'eduma' ) ) );
	$config_courses_cat->addColor( $prefix . 'learnpress_cate_heading_bg_color', array( 'name' => esc_html__( 'Background Color Heading', 'eduma' ) ) );
	$config_courses_cat->addColor( $prefix . 'learnpress_cate_heading_text_color', array( 'name' => esc_html__( 'Text Color Heading', 'eduma' ) ) );
	$config_courses_cat->addColor( $prefix . 'learnpress_cate_sub_heading_text_color', array( 'name' => esc_html__( 'Color Description Category', 'eduma' ) ) );
	$config_courses_cat->addCheckbox( $prefix . 'learnpress_cate_hide_title', array( 'name' => esc_html__( 'Hide Title', 'eduma' ) ) );
	$config_courses_cat->addCheckbox( $prefix . 'learnpress_cate_hide_breadcrumbs', array( 'name' => esc_html__( 'Hide Breadcrumbs', 'eduma' ) ) );
	$config_courses_cat->Finish();
}
