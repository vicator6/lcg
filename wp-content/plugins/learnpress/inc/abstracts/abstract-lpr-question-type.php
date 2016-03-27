<?php

/**
 * Base class for type of question
 *
 * @author  TuNN
 * @created 27 Mar 2015
 * @since   Beta
 */
class LPR_Question_Type {
	/**
	 * @var array
	 */
	protected $instances = array();

	/**
	 * @var null
	 */
	protected $options = null;

	/**
	 * @var int
	 */
	public $id	= 0;

	/**
	 * @var null
	 */
	public $post = null;

	/**
	 * @param null $the_question
	 * @param null $options
	 */
	function __construct( $the_question = null, $options = null ) {
		if ( is_numeric( $the_question ) ) {
			$this->id   = absint( $the_question );
			$this->post = get_post( $this->id );
		} elseif ( $the_question instanceof LP_Question ) {
			$this->id   = absint( $the_question->id );
			$this->post = $the_question->post;
		} elseif ( isset( $the_question->ID ) ) {
			$this->id   = absint( $the_question->ID );
			$this->post = $the_question;
		}

		$this->_options = $options;
		if ( is_admin() ) {
			add_action( 'admin_print_scripts', array( $this, 'admin_script' ) );
			add_action( 'admin_enqueue_styles', array( $this, 'admin_style' ) );

		} else {
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_script' ) );
			add_action( 'wp_enqueue_styles', array( $this, 'wp_style' ) );

		}

		$this->options = (array) $options;

		$this->_parse();
		//print_r($this);
	}

	function __get( $key ){
		$return = null;
		if( strtolower( $key ) == 'id' ){
			$key = 'id';
		}
		if( property_exists( $this, $key ) ){
			$return = $this->{$key};
		}else {
			switch ( $key ) {
				case 'answers':
					$question_meta = (array)get_post_meta( $this->id, '_lpr_question', true );
					$return = !empty( $question_meta['answer'] ) ? $question_meta['answer'] : '';
					break;
			}
		}
		return $return;
	}

	function submit_answer( $quiz_id, $answer ) {
		print_r( $_POST );
		die();
	}

	/**
	 * Parse the content of the post if the ID is passed to $options
	 * or try to find $post if it set
	 */
	private function _parse() {
		$this->options = array_merge( $this->options, (array) get_post_meta( $this->ID, '_lpr_question', true ) );
	}

	function admin_script() {
		global $wp_query, $post, $post_type;
		if ( !in_array( $post_type, array( 'lpr_question', 'lpr_quiz', 'lpr_lesson' ) ) ) return;
		if ( empty( $post->ID ) || $wp_query->is_archive ) return;

	}

	function admin_style() {

	}

	function wp_script() {

	}

	function wp_style() {

	}

	/**
	 * Prints the header of a question in admin mode
	 * should call this function before in the top of admin_interface in extends class
	 *
	 * @param array $args
	 *
	 * @reutrn void
	 */
	function admin_interface_head( $args = array() ) {
		$post_id = $this->get( 'ID' );
		settype( $args, 'array' );
		$is_collapse = array_key_exists( 'toggle', $args ) && !$args['toggle'];

		$questions = learn_press_get_question_types();
		?>
		<div class="lpr-question lpr-question-<?php echo preg_replace( '!_!', '-', $this->get_type() ); ?>" data-type="<?php echo preg_replace( '!_!', '-', $this->get_type() ); ?>" data-id="<?php echo $this->get( 'ID' ); ?>" id="learn-press-question-<?php echo $this->id;?>">
		<div class="lpr-question-head">
			<p>
				<a href="<?php echo get_edit_post_link( $post_id ); ?>"><?php _e( 'Edit', 'learn_press' ); ?></a>
				<a href="" data-action="remove"><?php _e( 'Remove', 'learn_press' ); ?></a>
				<a href="" data-action="expand" class="<?php echo !$is_collapse ? "hide-if-js" : ""; ?>"><?php _e( 'Expand', 'learn_press' ); ?></a>
				<a href="" data-action="collapse" class="<?php echo $is_collapse ? "hide-if-js" : ""; ?>"><?php _e( 'Collapse', 'learn_press' ); ?></a>
			</p>
			<!--<select name="lpr_question[<?php echo $post_id; ?>][type]" data-type="<?php echo $this->get_type(); ?>">
				<?php if ( $questions ) foreach ( $questions as $type ): ?>
					<?php $question = LPR_Question_Factory::instance()->get_question( $type ); ?>
					<?php if ( $question ) { ?>
						<option value="<?php echo $type; ?>" <?php selected( $this->get_type() == $type ? 1 : 0, 1 ); ?>>
							<?php echo $question->get_name(); ?>
						</option>
					<?php } ?>
				<?php endforeach; ?>
			</select>-->
			<span class="lpr-question-title"><input class="inactive" type="text" name="lpr_question[<?php echo $this->get( 'ID' ); ?>][text]" value="<?php echo esc_attr( $this->post->post_title ); ?>" /></span>
		</div>
		<div class="lpr-question-content<?php echo $is_collapse ? " hide-if-js" : ""; ?>">
		<?php //do_action( 'learn_press_admin_before_question_answer', $this );
		?>
		<p class="lpr-question-option-label"><?php _e( 'Answer', 'learn_press' ); ?></p>
		<?php
	}

	/**
	 * Prints the header of a question in admin mode
	 * should call this function before in the bottom of admin_interface in extends class
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	function admin_interface_foot( $args = array() ) {
		settype( $args, 'array' );
		$is_collapse = array_key_exists( 'toggle', $args ) && !$args['toggle'];
		//print_r($args);
		$question_types   = LPR_Question_Factory::get_types();
		$question_meta    = (array) get_post_meta( $this->id, '_lpr_question', true );
		$question_type    = LPR_Question_Factory::instance()->get_question_type( $question_meta );
		?>
		<p class="lpr-change-question-type">
			<span><?php _e( 'Change type of this question to', 'learn_press' );?></span>
			<select class="lpr-question-types" name="lpr_question[type]" id="lpr_question-type" data-type="<?php echo $question_type; ?>">
				<option value=""><?php _e( '--Select type--', 'learn_press' ); ?></option>
				<?php if ( $question_types ): foreach ( $question_types as $type ): ?>
					<option value="<?php echo $type; ?>" <?php selected( $type == $question_type ); ?>><?php echo learn_press_question_slug_to_title( $type );?></option>
				<?php endforeach; endif; ?>
			</select>
		</p>
		<input class="lpr-question-toggle" type="hidden" name="lpr_question[<?php echo $this->get( 'ID' ); ?>][toggle]" value="<?php echo $is_collapse ? 0 : 1; ?>" />
		</div>
		</div>
		<?php
	}

	/**
	 * Prints the content of a question in admin mode
	 * This function should be overridden from extends class
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	function admin_interface( $args = array() ) {
		printf( __( 'Function %s should override from its child', 'learn_press' ), __FUNCTION__ );
	}

	/**
	 * Prints the question in frontend user
	 *
	 * @param unknown
	 *
	 * @return void
	 */
	function render() {
		printf( __( 'Function %s should override from its child', 'learn_press' ), __FUNCTION__ );
	}

	function get_type( $slug = false ) {
		$type = strtolower( preg_replace( '!LPR_Question_Type_!', '', get_class( $this ) ) );
		if ( $slug ) $type = preg_replace( '!_!', '-', $type );
		return $type;
	}

	function get_name() {
		return
			isset( $this->options['name'] ) ? $this->options['name'] : ucfirst( preg_replace_callback( '!_([a-z])!', create_function( '$matches', 'return " " . strtoupper($matches[1]);' ), $this->get_type() ) );
	}

	protected function _get_option_value( $value = null ) {
		if ( !$value ) {
			$value = $this->id . uniqid();
		}
		return $value;
	}

	/**
	 * Sets the value for a variable of this class
	 *
	 * @param   $key      string  The name of a variable of this class
	 * @param   $value    any     The value to set
	 *
	 * @return  void
	 */
	function set( $key, $value ) {
		$this->$key = $value;
	}

	/**
	 * Gets the value of a variable of this class with multiple level of an object or array
	 * example: $obj->get('a.b') -> like this :
	 *          - $obj->a->b
	 *          - or $obj->a['b']
	 *
	 * @param   null $key     string  Single or multiple level such as a.b.c
	 * @param   null $default mixed   Return a default value if the key does not exists or is empty
	 * @param   null $func    string  The function to apply the result before return
	 *
	 * @return  mixed|null
	 */
	function get( $key = null, $default = null, $func = null ) {
		if( is_string( $key ) && strpos( $key, '.' ) === false ){
			return $this->{$key};
		}
		$val =  $this->_get( $this, $key, $default );
		return is_callable( $func ) ? call_user_func_array( $func, array( $val ) ) : $val;
	}


	protected function _get( $prop, $key, $default = null, $type = null ) {
		$return = $default;

		if ( $key === false || $key == null ) {
			return $return;
		}
		$deep = explode( '.', $key );

		if ( is_array( $prop ) ) {
			if ( isset( $prop[$deep[0]] ) ) {
				$return = $prop[$deep[0]];
				if ( count( $deep ) > 1 ) {
					unset( $deep[0] );
					$return = $this->_get( $return, implode( '.', $deep ), $default, $type );
				}
			}
		} elseif ( is_object( $prop ) ) {
			if ( isset( $prop->{$deep[0]} ) ) {
				$return = $prop->{$deep[0]};
				if ( count( $deep ) > 1 ) {
					unset( $deep[0] );
					$return = $this->_get( $return, implode( '.', $deep ), $default, $type );
				}
			}
		}


		if ( $type == 'object' ) settype( $return, 'object' );
		elseif ( $type == 'array' ) settype( $return, 'array' );

		// return;
		return $return;
	}

	/**
	 * Save question data on POST action
	 */
	function save_post_action() {
	}

	/**
	 * Store question data
	 */
	function store() {
		$post_id = $this->get( 'ID' );
		$is_new  = false;
		if ( $post_id ) {
			$post_id = wp_update_post(
				array(
					'ID'          => $post_id,
					'post_title'  => $this->get( 'post_title' ),
					'post_type'   => 'lpr_question',
					'post_status' => 'publish'

				)
			);
		} else {
			$post_id = wp_insert_post(
				array(
					'post_title'  => $this->get( 'post_title' ),
					'post_type'   => 'lpr_question',
					'post_status' => 'publish'
				)
			);
			$is_new  = true;
		}
		if ( $post_id ) {
			$options         = $this->get( 'options' );
			$options['type'] = $this->get_type();

			$this->set( 'options', $options );

			update_post_meta( $post_id, '_lpr_question', $this->get( 'options' ) );

			// update default mark
			if ( $is_new ) update_post_meta( $post_id, '_lpr_question_mark', 1 );

			$this->ID = $post_id;
		}
		return $post_id;
	}

	/**
	 * Gets an instance of a question by type or ID
	 * If the first param is a string ( type of question such as true_or_false ) then return the instance of class LPR_Question_Type_True_Or_False
	 * If the first param is a number ( ID of the post ) then find a post in the database with the type store in meta_key to return class corresponding
	 *
	 * @param   null $id_or_type Type or ID of an question in database
	 * @param   null $options
	 *
	 * @return  bool
	 */
	static function instance( $id_or_type = null, $options = null ) {
		$type = $id_or_type;
		if ( is_numeric( $id_or_type ) ) {
			$question = get_post( $id_or_type );
			if ( $question ) {
				$meta = (array) get_post_meta( $id_or_type, '_lpr_question', true );
				if ( isset( $meta['type'] ) ) {
					$type    = $meta['type'];
					$options = array_merge( (array) $options, array( 'ID' => $id_or_type ) );
				}
				//print_r($meta);
			}
		} else {

		}
		$class_name     = 'LPR_Question_Type_' . ucfirst( preg_replace_callback( '!(_[a-z])!', create_function( '$matches', 'return strtolower($matches[1]);' ), $type ) );
		$class_instance = false;

		if ( !class_exists( $class_name ) ) {
			$paths = array(
				LPR_PLUGIN_PATH . '/inc/question-type'
			);
			$paths = apply_filters( 'lpr_question_type_path', $paths );
			if ( $paths ) foreach ( $paths as $path ) {
				if ( is_file( $path ) ) {
					$file = $path;
				} else {
					$file = $path . '/class.lpr-question-type-' . preg_replace( '!_!', '-', $type ) . '.php';
				}
				if ( file_exists( $file ) ) {
					require_once( $file );
				}
			}
		}
		if ( class_exists( $class_name ) ) {
			$class_instance = new $class_name( $id_or_type, $options );
		} else {
			$class_instance = new LPR_Question_Type_None();
		}
		return $class_instance;
	}

	function check( $args = null ) {
		$return = array(
			'correct' => false,
			'mark'    => 0
		);
		return $return;
	}
}

function learn_press_get_question_types() {
	_deprecated_function( __FUNCTION__, '0.9.20', 'LPR_Question_Factory::get_types()' );
	return LPR_Question_Factory::get_types();
}

/**
 *
 * @param       $type
 * @param array $options
 *
 * @return bool
 */
function learn_press_get_question( $type, $options = array() ) {
	return LPR_Question_Factory::instance()->get_question( $type, $options );
}

/**
 * Because metabox is rendered after the enqueue scripts/styles function
 * so we must load all type of questions and call admin_script to
 * enqueue scripts/styles in admin_enqueue_scripts action
 */
function learn_press_question_scripts() {
	if ( !in_array( get_post_type(), array( 'lpr_question', 'lpr_quiz' ) ) ) return;
	$question_types = learn_press_get_question_types();
	if ( $question_types ) foreach ( $question_types as $type ) {
		$ques = learn_press_get_question( $type );
		if ( $ques ) {
			is_admin() ? $ques->admin_script() : '';
		}
	}
}

add_action( 'admin_enqueue_scripts', 'learn_press_question_scripts' );
add_action( 'wp_enqueue_scripts', 'learn_press_question_scripts' );

function learn_press_submit_answer() {

	_deprecated_function( 'learn_press_submit_answer', '0.9.15', false );

	$quiz_id         = !empty( $_REQUEST['quiz_id'] ) ? intval( $_REQUEST['quiz_id'] ) : 0;
	$question_id     = !empty( $_REQUEST['question_id'] ) ? intval( $_REQUEST['question_id'] ) : 0;
	$next_id         = !empty( $_REQUEST['next_id'] ) ? intval( $_REQUEST['next_id'] ) : learn_press_get_next_question( $quiz_id, $question_id );
	$question_answer = isset( $_REQUEST['question_answer'] ) ? $_REQUEST['question_answer'] : null;
	$finish          = isset( $_REQUEST['finish'] ) ? $_REQUEST['finish'] : null;

	$user_id = get_current_user_id();
	$json    = array();

	$ques = learn_press_get_question( $question_id );
	if ( $ques ) {
		$ques->submit_answer( $quiz_id, $question_answer );
	}
	ob_start();
	if ( $next_id ) {
		do_action( 'learn_press_submit_answer', $question_answer, $question_id, $quiz_id, $user_id, false );
		learn_press_get_template( 'quiz/form-question.php', array( 'question_id' => $next_id, 'course_id' => learn_press_get_course_by_quiz( $quiz_id ) ) );
	} else {
		$question_ids             = learn_press_get_user_quiz_questions( $quiz_id, $user_id );
		$quiz_completed           = get_user_meta( $user_id, '_lpr_quiz_completed', true );
		$quiz_completed[$quiz_id] = current_time( 'timestamp' );
		update_user_meta( $user_id, '_lpr_quiz_completed', $quiz_completed );
		$course_id = learn_press_get_course_by_quiz( $quiz_id );
		if ( !learn_press_user_has_finished_course( $course_id ) ) {
			if ( learn_press_user_has_completed_all_parts( $course_id, $user_id ) ) {
				learn_press_finish_course( $course_id, $user_id );
			}
		}
		learn_press_get_template( 'quiz/result.php' );
		$json['quiz_completed'] = true;
		do_action( 'learn_press_submit_answer', $question_answer, $question_id, $quiz_id, $user_id, true );
	}
	$output = ob_get_clean();
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$json['html']     = $output;
		$json['redirect'] = apply_filters( 'learn_press_submit_answer_redirect_url', get_the_permalink( $quiz_id ), $question_answer, $question_id, $quiz_id, $user_id );
		learn_press_send_json( $json );
	}
}

add_action( 'wp_ajax_learn_press_submit_answer', 'learn_press_submit_answer' );
add_action( 'wp_ajax_nopriv_learn_press_submit_answer', 'learn_press_submit_answer' );

add_action( 'learn_press_frontend_action_submit_answer', 'learn_press_submit_answer' );

function learn_press_load_question() {
	$question_id = !empty( $_REQUEST['question_id'] ) ? intval( $_REQUEST['question_id'] ) : 0;
	$quiz_id     = !empty( $_REQUEST['quiz_id'] ) ? intval( $_REQUEST['quiz_id'] ) : 0;
	/*$ques = learn_press_get_question( $question_id );
	if( $ques ){
		$quiz_answers = learn_press_get_question_answers(null, $quiz_id );
		$ques->render( array(
			'answer' => isset( $quiz_answers[$question_id] ) ? $quiz_answers[$question_id] : null
		));
	}*/
	learn_press_get_template( 'quiz/form-question.php', array( 'question_id' => $question_id, 'course_id' => learn_press_get_course_by_quiz( $quiz_id ) ) );

	die();
}

add_action( 'wp_ajax_learn_press_load_question', 'learn_press_load_question' );
add_action( 'wp_ajax_nopriv_learn_press_load_question', 'learn_press_load_question' );

function learn_press_show_answer() {

	$user_id         = learn_press_get_current_user_id();
	$quiz_id         = !empty( $_REQUEST['quiz_id'] ) ? intval( $_REQUEST['quiz_id'] ) : 0;
	$question_id     = !empty( $_REQUEST['question_id'] ) ? intval( $_REQUEST['question_id'] ) : 0;
	$question_answer = isset( $_REQUEST['question_answer'] ) ? $_REQUEST['question_answer'] : null;
	$ques            = learn_press_get_question( $question_id );

	if ( $ques ) {
		$ques->submit_answer( $quiz_id, $question_answer );
	}

	$json = array(
		'html' => learn_press_get_question_hint( $quiz_id, $question_id, $user_id )
	);

	learn_press_send_json( $json );
}

add_action( 'wp_ajax_learn_press_show_answer', 'learn_press_show_answer' );
add_action( 'wp_ajax_nopriv_learn_press_show_answer', 'learn_press_show_answer' );

function learn_press_load_quiz_question_result_for_hint( $content, $answer, $question ){
	/*$result = array(
		'result'    => 'success',
		'content'   => $content,
		'permalink' => learn_press_get_user_question_url( $quiz_id, $question_id )
	);*/
	$user_id = learn_press_get_current_user_id();
	$quiz_id = null;
	if( learn_press_user_has_checked_answer( $question->get( 'ID' ), $user_id, $quiz_id ) ){
		$content = learn_press_get_question_hint( $quiz_id, $question->get( 'ID' ), $user_id );
	}

	return $content;
}
add_filter( 'learn_press_question_content', 'learn_press_load_quiz_question_result_for_hint', 10, 3 );

function learn_press_load_quiz_question_result( $result, $question_id, $quiz_id ) {
	$user_id = get_current_user_id();
	$quizzes = (array) get_user_meta( $user_id, '_lpr_quiz_question_hint', true );
	if ( isset( $quizzes[$quiz_id] ) && isset( $quizzes[$quiz_id][$question_id] ) ) {
		$result['hint'] = 1;
	}
	return $result;
}

add_filter( 'learn_press_load_quiz_question_result', 'learn_press_load_quiz_question_result', 10, 3 );

function learn_press_before_submit_user_answer( $question_answer, $user_id, $question_id, $quiz_id ) {
	if ( learn_press_user_is_used_hint( $user_id, $question_id, $quiz_id ) ) {
		$quizzes = (array) get_user_meta( $user_id, '_lpr_quiz_question_answer', true );
		if ( isset( $quizzes[$quiz_id] ) && isset( $quizzes[$quiz_id][$question_id] ) ) {
			$question_answer = $quizzes[$quiz_id][$question_id];
		}
	}
	return $question_answer;
}

add_filter( 'learn_press_submit_user_answer', 'learn_press_before_submit_user_answer', 10, 4 );

function learn_press_single_quiz_params( $params, $quiz_id, $user_id = null ) {
	$quizzes = (array) get_user_meta( $user_id, '_lpr_quiz_question_hint', true );
	if ( isset( $quizzes[$quiz_id] ) ) {
		$params['hint'] = array_keys( $quizzes[$quiz_id] );
	} else {
		$params['hint'] = array();
	}
	return $params;
}

add_filter( 'learn_press_single_quiz_params', 'learn_press_single_quiz_params', 10, 3 );
