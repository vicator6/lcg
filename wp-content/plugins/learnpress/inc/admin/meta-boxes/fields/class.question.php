<?php

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'RWMB_Question_Field' ) ) {
	/**
	 * Class RWMB_Question_Field
	 */
	class RWMB_Question_Field extends RWMB_Field {

		/**
		 * Admin script
		 */
		static function admin_enqueue_scripts() {
			wp_enqueue_style( 'lpr-question', LPR_PLUGIN_URL . 'inc/admin/meta-boxes/css/question.css', array(), '3.2' );
		}

		/**
		 * Add more actions
		 */
		static function add_actions() {
			// Do same actions as file field
			parent::add_actions();
			add_action( 'wp_ajax_lpr_load_question_settings', array( __CLASS__, 'load_question_settings' ) );
		}

		/**
		 *
		 */
		static function load_question_settings() {
			$type        = isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : null;
			$question_id = isset( $_REQUEST['question_id'] ) ? $_REQUEST['question_id'] : null;

			$options = array(
				'ID' => $question_id
			);

			$question = LPR_Question_Factory::instance()->get_question( $type, $options );
			$options  = $question->get( 'options' );
			if ( isset( $options['type'] ) && $options['type'] == $type ) {

			} else {
				unset( $options['answer'] );
				$question->set( 'options', $options );
			}

			$post_options = !empty( $_REQUEST['options'] ) ? $_REQUEST['options'] : null;
			if ( $type == 'single_choice' ) {
				$selected = - 1;
				if ( $post_options && $post_options['answer'] ) foreach ( $post_options['answer'] as $k => $option ) {
					if ( !empty( $option['is_true'] ) ) $selected = $k;
					$post_options['answer'][$k]['is_true'] = 0;
				}
				if ( $selected > - 1 ) {
					$post_options['answer'][$selected]['is_true'] = 1;
				}
			}
			if ( $post_options ) $question->set( 'options', $post_options );

			$question->admin_interface();
			die();
		}

		static function save( $new, $old, $post_id, $field ) {
			$type     = $_POST['lpr_question']['type'];
			//$question = LPR_Question_Factory::instance()->get_question( $post_id );
			$question = LPR_Question_Factory::instance()->get_question( $post_id, array( 'type' => $type ) );//LPR_Question_Type( $post_id );
			if ( $question ) $question->save_post_action();
		}

		static function html( $meta, $field ) {
			global $post;
			$question_factory = LPR_Question_Factory::instance();
			$post_id          = $post->ID;
			$question_types   = LPR_Question_Factory::get_types();
			$question_meta    = (array) get_post_meta( $post_id, '_lpr_question', true );
			$question_type    = $question_factory->get_question_type( $question_meta );

			ob_start();
			?>
			<script type="text/javascript">var learn_press_question_id = <?php echo intval($post_id);?>;</script>
			<div id="lpr-question-options-wrap">

				<div class="lpr-question-settings">
					<?php if ( $question = $question_factory->get_question( $post ) ) { ?>
						<?php $question->admin_interface(); ?>
					<?php } ?>

				</div>


				<?php /*if ( in_array( $question['type'], array( 'true_or_false', 'multiple_choice', 'single_choice' ) ) ): ?>
					<strong>Tips:</strong>
					<p><i>In <strong>Answer text</strong>, press Enter/Tab key to move to next</i></p>
					<p><i>In
							<strong>Answer text</strong>, when the text is empty press Delete/Back Space or click out side to remove</i>
					</p>
					<p><i>In <strong>Answer text</strong>, press ESC to restore the text at the last time edited</i></p>
				<?php endif;*/ ?>
			</div>
			<?php
			return ob_get_clean();
		}
	}
}