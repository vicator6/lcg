<?php
$styling->addSubSection( array(
	'name'     => esc_html__( 'Color', 'eduma' ),
	'id'       => 'styling_color',
	'position' => 13,
	'livepreview' => '$("body").css("color", value);'
) );


$styling->createOption( array(
	'name'        => esc_html__( 'Body Background Color', 'eduma' ),
	'id'          => 'body_bg_color',
	'type'        => 'color-opacity',
	'default'     => '#fff',
	'livepreview' => '$("body #main-content").css("background-color", value);'
) );

$styling->createOption( array(
	'name'    => esc_html__( 'Primary Color', 'eduma' ),
	'id'      => 'body_primary_color',
	'type'    => 'color-opacity',
	'default' => '#ffb606',
) );

$styling->createOption( array(
	'name'    => esc_html__( 'Button Hover Color', 'eduma' ),
	'id'      => 'button_hover_color',
	'type'    => 'color-opacity',
	'default' => '#e6a303',
) );
