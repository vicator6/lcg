<?php
$styling->addSubSection( array(
	'name'     => esc_html__( 'Support', 'eduma' ),
	'id'       => 'styling_rtl',
	'position' => 15,
) );

$styling->createOption( array(
	'name'    => esc_html__( 'RTL Support', 'eduma' ),
	'id'      => 'rtl_support',
	'type'    => 'checkbox',
	'desc'    => esc_html__( 'Enable/Disable', 'eduma' ),
	'default' => false,
) );

$styling->createOption( array(
	'name'    => esc_html__( 'Pre-loader', 'eduma' ),
	'id'      => 'preload',
	'type'    => 'checkbox',
	'desc'    => esc_html__( 'Enable/Disable', 'eduma' ),
	'default' => true,
) );
