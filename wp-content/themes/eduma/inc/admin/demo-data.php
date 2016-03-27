<?php

defined( 'DS' ) OR define( 'DS', DIRECTORY_SEPARATOR );

$demo_datas_dir = THIM_DIR . 'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'data';

$demo_datas = array(
	'demo-01' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-01',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-01/screenshot.jpg',
		'title'         => esc_html__( 'Demo 01', 'eduma' ),
		'demo_url'      => 'https://educationwp.thimpress.com',
	),
	'demo-02' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-02',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-02/screenshot.jpg',
		'title'         => esc_html__( 'Demo 02', 'eduma' ),
		'demo_url'      => 'http://educationwp.thimpress.com/demo-2/',
	),
	'demo-03' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-03',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-03/screenshot.jpg',
		'title'         => esc_html__( 'Demo 03', 'eduma' ),
		'demo_url'      => 'http://educationwp.thimpress.com/demo-3/',
	),
	'demo-04' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-04',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-04/screenshot.jpg',
		'title'         => esc_html__( 'Demo Boxed', 'eduma' ),
		'demo_url'      => 'http://educationwp.thimpress.com/demo-boxed/',
	),
	'demo-05' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-05',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-05/screenshot.jpg',
		'title'         => esc_html__( 'Demo RTL', 'eduma' ),
		'demo_url'      => 'http://educationwp.thimpress.com/demo-rtl/',
	),
	'demo-06' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-06',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-06/screenshot.jpg',
		'title'         => esc_html__( 'Demo One Course', 'eduma' ),
		'demo_url'      => 'https://educationwp.thimpress.com/demo-one-course/',
	),
	'demo-07' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-07',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-07/screenshot.jpg',
		'title'         => esc_html__( 'Demo One Instructor', 'eduma' ),
		'demo_url'      => 'https://educationwp.thimpress.com/demo-one-instructor/',
	),
	'demo-08' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-08',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-08/screenshot.jpg',
		'title'         => esc_html__( 'Demo University 1', 'eduma' ),
		'demo_url'      => 'https://educationwp.thimpress.com/demo-university/',
	),
	'demo-09' => array(
		'data_dir'      => $demo_datas_dir . DS . 'demo-09',
		'thumbnail_url' => THIM_URI . 'inc/admin/data/demo-09/screenshot.jpg',
		'title'         => esc_html__( 'Demo University 2', 'eduma' ),
		'demo_url'      => 'https://educationwp.thimpress.com/demo-university-2/',
	),
);