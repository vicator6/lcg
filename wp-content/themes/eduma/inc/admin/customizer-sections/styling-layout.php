<?php
$styling->addSubSection( array(
	'name'     => esc_html__( 'Layout', 'eduma' ),
	'id'       => 'styling_layout',
	'position' => 10,
) );
$styling->createOption( array(
	'name'    => esc_html__( 'Body Layout', 'eduma' ),
	'id'      => 'box_layout',
	'type'    => 'select',
	'options' => array(
		'boxed' => esc_html__( 'Boxed', 'eduma' ),
		'wide'  => esc_html__( 'Wide', 'eduma' ),
	),
	'default' => 'Wide',
) );
