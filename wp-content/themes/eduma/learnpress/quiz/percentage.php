<?php
if ( learn_press_get_current_user()->is( 'guest' ) ) {
	return;
}
$course_id = learn_press_get_course_by_quiz( get_the_ID() );
$passed    = learn_press_user_has_passed_course( $course_id );
$result    = learn_press_get_quiz_result();
?>

<?php if ( $passed ): ?>
	<p class="message message-success"><?php printf( esc_html__( 'You have passed this course with %.2f%% of total', 'eduma' ), $result['mark_percent'] * 100 ); ?></p>
<?php else: ?>
	<?php $passing_condition = learn_press_get_course_passing_condition( $course_id ); ?>
	<p class="message message-error"><?php printf( esc_html__( 'Sorry, you have not passed this course. This course required you pass %.2f%% but your result is only %.2f%%', 'eduma' ), $passing_condition, $result['mark_percent'] * 100 ); ?></p>
<?php endif; ?>