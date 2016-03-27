<?php
if( learn_press_course_enroll_required( $course_id ) ){
	if( !learn_press_is_enrolled_course( $course_id ) ){
		return;
	}
}

if( $next_id ){
	$next_title   = get_the_title( $next_id );
	$next_title   = apply_filters( 'learn_press_course_content_next_button_text', $next_title, $next_id, $course_id );
}
if( $prev_id ){
	$prev_title   = get_the_title( $prev_id );
	$prev_title   = apply_filters( 'learn_press_course_content_prev_button_text', $prev_title, $prev_id, $course_id );
}
?>
<p class="course-content-lesson-nav-text">
	<?php if( $prev_id ) { ?>
		<span class="prev-lesson-text"><?php _e( 'Previous', 'learn_press' );?></span>
	<?php } ?>
	<?php if( $next_id ) { ?>
		<span class="next-lesson-text"><?php _e( 'Next', 'learn_press' );?></span>
	<?php } ?>
</p>
<p class="course-content-lesson-nav">
	<?php if( $prev_id ) { ?>

		<?php printf( '<a href="%s" class="prev-lesson" data-id="%d">%s</a>', learn_press_get_course_lesson_permalink( $prev_id, $course_id ), $prev_id, $prev_title ); ?>

	<?php } ?>

	<?php if( $next_id ) { ?>

		<?php printf( '<a href="%s" class="next-lesson" data-id="%d">%s</a>', learn_press_get_course_lesson_permalink( $next_id, $course_id ), $next_id, $next_title ); ?>

	<?php } ?>
</p>