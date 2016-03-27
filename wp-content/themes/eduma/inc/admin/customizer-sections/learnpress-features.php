<?php
$courses->addSubSection( array(
	'name'     => esc_html__( 'Features', 'eduma' ),
	'id'       => 'learnpress_features',
	'position' => 5,
) );

$courses->createOption( array(
	'name'    => esc_html__( 'One Course ID', 'eduma' ),
	'id'      => 'learnpress_one_course_id',
	'type'    => 'text',
	'desc'    => esc_html__( 'The one course will be a latest course if you leave this field empty.', 'eduma' ),
	'default' => '',
) );