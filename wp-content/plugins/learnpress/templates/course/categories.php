<?php
/**
 * Template for displaying the categories of a course
 */

learn_press_prevent_access_directly();
do_action( 'learn_press_before_course_categories' );

$term_list = get_the_term_list( get_the_ID(), 'course_category', '', ', ', '' );
if( $term_list ) {
	printf(
		'<span class="cat-links">%s</span>',
		$term_list
	);
}
do_action( 'learn_press_after_course_categories' );
