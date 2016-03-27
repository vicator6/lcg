<?php
/**
 * Template for displaying the countdown timer
 *
 * @author  ThimPress
 * @package LearnPress
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $quiz;

if ( !$quiz->has( 'questions' ) ) {
	return;
}
?>
<div class="quiz-clock">
	<div class="quiz-total">
		<i class="fa fa-bullhorn"></i>
		<div class="quiz-text"><?php echo '<span class="number">'.thim_quiz_questions( get_the_ID() ) . '</span> '; ?><?php echo thim_quiz_questions( get_the_ID() ) > 1 ? esc_html__( 'Questions', 'eduma' ) : esc_html__( 'Question', 'eduma' ); ?></div>
	</div>
	<div class="quiz-countdown quiz-timer">
		<i class="fa fa-clock-o"></i>
		<span class="quiz-text"><?php echo esc_html__( 'Time', 'eduma' ); ?></span>
		<span id="quiz-countdown-value">0:00:00</span>
		<span class="quiz-countdown-label quiz-text">
			<?php
			echo apply_filters(
				'learn_press_quiz_time_label',
				$quiz->duration > 59 ? esc_html__( '(h:m:s)', 'eduma' ) : esc_html__( '(m:s)', 'eduma' )
			);
			?>
		</span>
	</div>
</div>
