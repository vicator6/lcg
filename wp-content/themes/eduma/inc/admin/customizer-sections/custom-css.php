<?php

$custom_css = $titan->createThemeCustomizerSection( array(
	'name'     => esc_html__( 'Custom CSS', 'eduma' ),
	'position' => 100,
) );

/*
 * Archive Display Settings
 */
$custom_css->createOption( array(
	'name'    => esc_html__( 'Custom CSS', 'eduma' ),
	'id'      => 'custom_css',
	'type'    => 'textarea',
	'desc'    => esc_html__( 'Put your additional CSS rules here.', 'eduma' ),
	'is_code' => true,
) );