<?php

/**
 * Created by PhpStorm.
 * User: Tu
 * Date: 27/03/2015
 * Time: 11:42 SA
 * Modified 03 Apr 2015
 */
class LPR_Question_Type_Multi_Choice extends LPR_Question_Type {
	function __construct( $type = null, $options = null ) {
		parent::__construct( $type, $options );
	}

	function submit_answer( $quiz_id, $answer ) {
		$questions = learn_press_get_question_answers( null, $quiz_id );
		if ( !is_array( $questions ) ) $questions = array();
		$questions[$quiz_id][$this->get( 'ID' )] = is_array( $answer ) ? reset( $answer ) : $answer;
		learn_press_save_question_answer( null, $quiz_id, $this->get( 'ID' ), is_array( $answer ) ? reset( $answer ) : $answer );
	}

	function admin_script() {
		parent::admin_script();
		?>

		<?php
	}

	/**
	 * @param array $args
	 */
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
			<th class="lpr-remove-answer" width="40"></th>
			</thead>
			<tbody>
			<?php if ( $answers = $this->get( 'options.answer' ) ): foreach ( $answers as $i => $ans ): ?>
				<?php $value = $this->_get_option_value( $this->get( 'options.answer.' . $i . '.value' ) );?>
				<tr>
					<td class="lpr-sortable-handle">
						<i class="dashicons dashicons-sort"></i>
					</td>
					<td class="lpr-is-true-answer">
						<input type="checkbox" name="lpr_question[<?php echo $post_id; ?>][checked_options][<?php echo $value;?>]" value="1" <?php checked( $this->get( 'options.answer.' . $i . '.is_true', 0 ) ? 1 : 0 ); ?> />
					</td>
					<td>
						<input type="text" class="lpr-answer-text" name="lpr_question[<?php echo $post_id; ?>][answer][text][]" value="<?php echo esc_attr( $this->get( 'options.answer.' . $i . '.text', '' ) ); ?>" />
						<input type="hidden" name="lpr_question[<?php echo $post_id; ?>][answer][value][]" value="<?php echo $value; ?>" />
					</td>
					<td align="center" class="lpr-remove-answer"><i class="dashicons dashicons-trash"></td>
				</tr>
			<?php endforeach; endif; ?>
			<tr class="lpr-disabled">
			<?php $value = $this->_get_option_value();?>
				<td class="lpr-sortable-handle">
					<i class="dashicons dashicons-sort"></i>
				</td>
				<td class="lpr-is-true-answer">
					<input type="checkbox" name="lpr_question[<?php echo $post_id; ?>][checked_options][<?php echo $value;?>]" value="1" />
				</td>
				<td>
					<input type="text" class="lpr-answer-text" name="lpr_question[<?php echo $post_id; ?>][answer][text][]" value="" />
					<input type="hidden" name="lpr_question[<?php echo $post_id; ?>][answer][value][]" value="<?php echo $value; ?>" />
				</td>
				<td align="center" class="lpr-remove-answer"><i class="dashicons dashicons-trash"></td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" name="lpr_question[<?php echo $post_id; ?>][type]" value="<?php echo $this->get_type(); ?>">

		<p>
			<button type="button" class="button lpr-button-add-answer add-question-option-button" data-id="<?php echo $post_id;?>"><?php _e( 'Add answer', 'learn_press' ); ?></button>
		</p>

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
		$this->_admin_enqueue_script( false );
	}

	private function _admin_enqueue_script( $enqueue = true ) {
		ob_start();
		$key = 'question_' . $this->get( 'ID' );
		?>
		<script type="text/javascript">
			(function ($) {
				var $form = $('#post');

				$form.unbind('learn_press_question_before_update.<?php echo $key;?>').bind('learn_press_question_before_update.<?php echo $key;?>', function () {
					var $question = $('.lpr-question-multi-choice[data-id="<?php echo $this->get('ID');?>"]');

					if ($question.length) {
						var $input = $('.lpr-is-true-answer input[type="checkbox"]:checked', $question);

						if (0 == $input.length) {
							var message = $('.lpr-question-title input', $question).val();
							message += ": " + '<?php _e( 'No answer added to question or you must select at least one the answer is correct!', 'learn_press' );?>';
							window.learn_press_before_update_quiz_message.push(message);

							return false;
						}
					}
				});
			})(jQuery);
		</script>
		<?php
		$script = ob_get_clean();
		if ( $enqueue ) {
			$script = preg_replace( '!</?script.*>!', '', $script );
			learn_press_enqueue_script( $script );
		} else {
			echo $script;
		}
	}

	function save_post_action() {
		$screen = get_current_screen();
		if ( $post_id = $this->ID ) {
			$post_data    = isset( $_POST['lpr_question'] ) ? $_POST['lpr_question'] : array();
			$post_answers = array();

			if ( isset( $post_data[$post_id] ) && $post_data = $post_data[$post_id] ) {
				$checked = $post_data['checked_options'];
				$post_args = array(
						'ID'         => $post_id,
						'post_title' => $screen->id == 'lpr_question' ? $_POST['post_title'] : $post_data['text'],
						'post_type'  => 'lpr_question'
				);

				wp_update_post( $post_args );
				if( !empty( $post_data['answer'] ) && !empty( $post_data['answer']['text'] ) ) {

					foreach ( $post_data['answer']['text'] as $k => $txt ) {
						if ( !$txt ) continue;
						$option_value   = $post_data['answer']['value'][$k];
						$post_answers[] = array(
							'text'    => $txt,
							'is_true' => isset( $checked[$option_value] ) ? 1 : 0,
							'value'   => $option_value
						);
					}
				}
				//learn_press_debug($checked, false);
				//learn_press_debug($post_answers);
				unset( $post_data['checked_options'] );
			}
			$post_data['answer']       = $post_answers;
			$post_data['type']         = $this->get_type();
			update_post_meta( $post_id, '_lpr_question', $post_data );
		}

		return intval( $post_id );
	}

	function render( $args = null ) {
		$unique_name = uniqid( 'lp_question_answer_' . $this->get( 'ID' ) . '_' );
		$answer      = false;
		$load_user_answer = false;
		is_array( $args ) && extract( $args );
		if ( !is_null( $answer ) ) {
			settype($answer, 'array');
		}else if( $load_user_answer ) {
		}else{
			$answer = array();
		}
		?>
		<div class="lp-question-wrap lp-multi-choice-question question-<?php echo $this->get( 'ID' ); ?>">
			<?php do_action( 'learn_press_before_question_title', $this->get( 'ID' ) ); ?>
			<h4 class="question-title"><?php echo get_the_title( $this->get( 'ID' ) ); ?></h4>
			<?php do_action( 'learn_press_after_question_title', $this->get( 'ID' ) ); ?>
			<div class="question-content">
				<?php ob_start();?>
				<ul>
					<?php if ( $answers = $this->get( 'options.answer' ) ) foreach ( $answers as $k => $ans ): ?>
						<li>
							<?php settype($k, 'string');?>
							<label>
								<input type="checkbox" name="<?php echo $unique_name; ?>[]" <?php checked( in_array( $k, $answer, true ) ? 1 : 0 ); ?> value="<?php echo $k; ?>">
								<?php echo $this->get( "options.answer.{$k}.text" ); ?>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php echo apply_filters( 'learn_press_question_content', ob_get_clean(), $answer, $this );?>
			</div>
			<?php do_action( 'learn_press_after_question_content', $this->get( 'ID' ) ); ?>
		</div>
		<?php
	}

	function check( $args = false ) {
		$answer = false;
		is_array( $args ) && extract( $args );
		$return = array(
				'correct' => true,
				'mark'    => intval( get_post_meta( $this->get( 'ID' ), '_lpr_question_mark', true ) )
		);
		settype( $answer, 'array' );
		if ( $answers = $this->get( 'options.answer' ) ) {
			foreach ( $answers as $k => $ans ) {
				$is_true = $this->get( 'options.answer.' . $k . '.is_true' ) ? true : false;
				// if the option is TRUE but user did not select it => WRONG
				// or, if the option is FALSE but user selected it => WRONG
				if ( ( $is_true && !in_array( $k, $answer ) ) || ( !$is_true && in_array( $k, $answer ) ) ) {
					$return['correct'] = false;
					$return['mark']    = 0;
					break;
				}
			}

		}

		return $return;
	}

	public static function admin_js_template(){
		?>
		<script type="text/html" id="tmpl-multi-choice-question-answer">
			<tr class="lpr-disabled">
				<td class="lpr-sortable-handle">
					<i class="dashicons dashicons-sort"></i>
				</td>
				<td class="lpr-is-true-answer">
					<input type="checkbox" name="lpr_question[{{data.question_id}}][checked_options][{{data.option_value}}]" value="1" />
				</td>
				<td>
					<input type="text" class="lpr-answer-text" name="lpr_question[{{data.question_id}}][answer][text][]" value="" />
					<input type="hidden" name="lpr_question[{{data.question_id}}][answer][value][]" value="{{data.option_value}}" />
				</td>
				<td align="center" class="lpr-remove-answer"><i class="dashicons dashicons-trash"></td>
			</tr>
		</script>
		<?php
	}
}