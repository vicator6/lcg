<?php
/**
 *
 */
//print_r(learn_press_get_quiz_questions());
if ( learn_press_user_has_started_quiz() ) {
	$question_list = learn_press_get_user_quiz_questions( get_the_ID() );
} else {
	$question_list = learn_press_get_quiz_questions();
}
?>
<?php //$question_list = learn_press_get_user_quiz_questions( get_the_ID() );//apply_filters( 'learn_press_user_sidebar_questions', learn_press_get_quiz_questions(), get_the_ID(), learn_press_get_current_user_id() ); ?>
<?php do_action( 'learn_press_quiz_questions_before_questions' ); ?>
<?php if ( $question_list ) { ?>
	<div class="quiz-questions">
		<?php $title = apply_filters( 'learn_press_list_of_questions_text', esc_attr__( 'List of questions', 'learn_press' ) ); ?>
		<?php if ( $title ): ?>
			<h3><?php echo $title; ?></h3>
		<?php endif; ?>
		<?php do_action( 'learn_press_quiz_questions_before_questions_loop' ); ?>
		<ul>
			<?php
			$index                  = 0;
			$question_loop_template = learn_press_locate_template( 'quiz/loop.php' );
			$current_question       = learn_press_get_current_question();
			$quiz_completed         = learn_press_user_has_completed_quiz();
			if ( !empty ( $question_list ) ) {
				foreach ( $question_list as $question_id => $question_options ) {
					$index ++;
					$question_title = get_the_title( $question_id );
					$question       = get_post( $question_id );
					if ( $current_question && ( $current_question == $question_id ) && !$quiz_completed ) {
						$current = true;
					} else {
						$current = false;
					}
					// include to ensure that sub-template can be load all variables from here
					require( $question_loop_template );
				}
			}
			?>
		</ul>
		<?php do_action( 'learn_press_quiz_questions_after_questions_loop' ); ?>
	</div>
<?php } ?>
<?php do_action( 'learn_press_quiz_questions_after_questions' ); ?>
