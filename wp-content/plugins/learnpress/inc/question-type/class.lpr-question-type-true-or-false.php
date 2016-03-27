<?php

/**
 * Created by PhpStorm.
 * User: Tu
 * Date: 27/03/2015
 * Time: 11:42 SA
 * Modified 03 Apr 2015
 */
class LPR_Question_Type_True_Or_False extends LPR_Question_Type {
	function __construct( $type = null, $options = null ) {
		parent::__construct( $type, $options );


	}

	function submit_answer( $quiz_id, $answer ) {
		$questions = learn_press_get_question_answers( null, $quiz_id );
		if ( !is_array( $questions ) ) $questions = array();
		$questions[$quiz_id][$this->get( 'ID' )] = is_array( $answer ) ? reset( $answer ) : $answer;
		learn_press_save_question_answer( null, $quiz_id, $this->get( 'ID' ), is_array( $answer ) ? reset( $answer ) : $answer );
	}

	function admin_interface( $args = array() ) {
		$uid     = uniqid( 'lpr_question_answer' );
		$post_id = $this->get( 'ID' );
		$this->admin_interface_head( $args );
		?>
		<table class="lpr-question-option lpr-question-answer lpr-sortable">
			<thead>
			<th width="20"></th>
			<th width="100"><?php _e( 'Is Correct?', 'learn_press' ); ?></th>
			<th><?php _e( 'Answer Text', 'learn_press' ); ?></th>
			</thead>
			<tbody>
			<?php for( $i = 0; $i < 2; $i ++ ){?>
				<?php $value = $this->_get_option_value();?>

				<tr>
				<td class="lpr-sortable-handle">
					<i class="dashicons dashicons-sort"></i>
				</td>
				<td class="lpr-is-true-answer">
					<input type="radio" name="lpr_question[<?php echo $post_id; ?>][checked]" value="<?php echo $value;?>" <?php checked( $this->get( 'options.answer.' . $i . '.is_true', 0 ) ? 1 : 0 ); ?> />
				</td>
				<td>
					<input type="text" class="lpr-answer-text" name="lpr_question[<?php echo $post_id; ?>][answer][text][]" value="<?php echo esc_attr( $this->get( 'options.answer.' . $i . '.text', '' ) ); ?>" />
					<input type="hidden" name="lpr_question[<?php echo $post_id; ?>][answer][value][]" value="<?php echo $value; ?>" />
				</td>
				<td align="center" class="lpr-remove-answer"><i class="dashicons dashicons-trash"></td>
			</tr>
			<?php }?>
			</tbody>
		</table>

		<!--
		<label><?php _e( 'Question Explanation', 'learn_press' ) ?></label>
		<?php if ( $explaination = $this->get( 'options.explaination' ) ) {
			echo '<textarea rows="4" name="lpr_question[' . $post_id . '][explaination]">' . $explaination . '</textarea>';
		} else {
			echo '<textarea rows="4" name="lpr_question[' . $post_id . '][explaination]"></textarea>';
		} ?>
		-->
		<?php
		$this->admin_interface_foot( $args );
	}

	function render( $args = array() ) {
		$unique_name      = uniqid( 'lp_question_answer_' . $this->get( 'ID' ) . '_' );
		$answer           = null;
		///$load_user_answer = false;
		is_array( $args ) && extract( $args );
		if ( is_array( $answer ) ){
			$answer = reset( $answer );
		}

		//$is_enable = learn_press_user_is_used_hint( learn_press_get_current_user_id(), $this->get('ID') );
		?>
		<div class="lp-question-wrap lp-true-false-question question-<?php echo $this->get( 'ID' ); ?>">
			<?php do_action( 'learn_press_before_question_title', $this->get( 'ID' ) ); ?>
			<h4 class="question-title"><?php echo get_the_title( $this->get( 'ID' ) ); ?></h4>
			<?php do_action( 'learn_press_after_question_title', $this->get( 'ID' ) ); ?>
			<div class="question-content">
				<?php ob_start();?>
				<ul>
					<li>
						<label>
							<input type="radio" name="<?php echo $unique_name; ?>" <?php checked( strlen( $answer ) && !$answer ? 1 : 0 ); ?> value="0">
							<?php echo $this->get( 'options.answer.0.text' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="<?php echo $unique_name; ?>" <?php checked( $answer == 1 ? 1 : 0 ); ?> value="1">
							<?php echo $this->get( 'options.answer.1.text' ); ?>
						</label>
					</li>
				</ul>
				<?php echo apply_filters( 'learn_press_question_content', ob_get_clean(), $answer, $this );?>
			</div>
			<?php do_action( 'learn_press_after_question_content', $this->get( 'ID' ) ); ?>
		</div>
		<?php
	}

	function save_post_action() {
		$screen = get_current_screen();

		if ( $post_id = $this->id ) {
			$post_data    = isset( $_POST['lpr_question'] ) ? $_POST['lpr_question'] : array();

			$post_answers = array();
			if ( isset( $post_data[$post_id] ) && $post_data = $post_data[$post_id] ) {
				try {
					$ppp = wp_update_post(
						array(
							'ID'         => $post_id,
							'post_title' => $screen->id == 'lpr_question' ? $_POST['post_title'] : $post_data['text'],
							'post_type'  => 'lpr_question'
						)
					);
				} catch ( Exception $ex ) {
				}

				$index = 0;
				$checked = $post_data['checked'];
				if( !empty( $post_data['answer'] ) && !empty( $post_data['answer']['text'] ) ) {

					foreach ( $post_data['answer']['text'] as $k => $txt ) {
						$option_value            = $post_data['answer']['value'][$k];
						$post_answers[$index ++] = array(
							'text'    => $txt,
							'is_true' => $checked == $option_value ? 1 : 0,
							'value'   => $option_value
						);
					}
				}
			}
			$post_data['answer']       = $post_answers;
			$post_data['type']         = $this->get_type();
			//$post_data['explaination'] = $post_explain;
			update_post_meta( $post_id, '_lpr_question', $post_data );
			//print_r($_POST);
		}
		//echo __FILE__ . ' #' . __LINE__;die();
		return $post_id;
		// die();
	}

	function check( $args = false ) {
		$answer = false;
		is_array( $args ) && extract( $args );
		$return = array(
			'correct' => false,
			'mark'    => 0
		);

		if ( is_numeric( $answer ) ) {
			if ( $this->get( 'options.answer.' . $answer . '.is_true' ) ) {
				$return['correct'] = true;
				$return['mark']    = intval( get_post_meta( $this->get( 'ID' ), '_lpr_question_mark', true ) );
			}
		}
		return $return;
	}
	public static function admin_js_template(){

	}
}