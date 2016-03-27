<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'LPR_Question_Post_Type' ) ) {
	// class LPR_Question_Post_Type
	class LPR_Question_Post_Type {
		private static $loaded = false;

		function __construct() {
			if ( self::$loaded ) return;

			add_action( 'init', array( $this, 'register_post_type' ) );
			add_action( 'admin_head', array( $this, 'enqueue_script' ) );
			add_action( 'admin_init', array( $this, 'add_meta_boxes' ), 5 );
			add_filter( 'manage_lpr_question_posts_columns', array( $this, 'columns_head' ) );
			add_action( 'save_post', array( $this, 'save_post' ), 999999999999 );

			self::$loaded = true;
		}

		function save_post( $p ) {
			//echo __FILE__ . ' #' . __LINE__;

		}

		/**
		 * Register question post type
		 */
		function register_post_type() {
			register_post_type( LPR_QUESTION_CPT,
				array(
					'labels'             => array(
						'name'               => __( 'Question Bank', 'learn_press' ),
						'menu_name'          => __( 'Question Bank', 'learn_press' ),
						'singular_name'      => __( 'Question', 'learn_press' ),
						'all_items'          => __( 'Questions', 'learn_press' ),
						'view_item'          => __( 'View Question', 'learn_press' ),
						'add_new_item'       => __( 'Add New Question', 'learn_press' ),
						'add_new'            => __( 'Add New', 'learn_press' ),
						'edit_item'          => __( 'Edit Question', 'learn_press' ),
						'update_item'        => __( 'Update Question', 'learn_press' ),
						'search_items'       => __( 'Search Questions', 'learn_press' ),
						'not_found'          => __( 'No question found', 'learn_press' ),
						'not_found_in_trash' => __( 'No question found in trash', 'learn_press' ),
					),
					'public'             => false,
					'publicly_queryable' => false,
					'show_ui'            => true,
					'has_archive'        => false,
					'capability_type'    => LPR_LESSON_CPT,
					'map_meta_cap'       => true,
					'show_in_menu'       => 'learn_press',
					'show_in_admin_bar'  => true,
					'show_in_nav_menus'  => true,
					'supports'           => array( 'title', 'editor', 'revisions', 'author' ),
					'hierarchical'       => true,
					'rewrite'            => array( 'slug' => _x( 'questions', 'Permalink Slug', 'learn_press' ), 'hierarchical' => true, 'with_front' => false )
				)
			);


			register_taxonomy( 'question-tag', array( LPR_QUESTION_CPT ),
				array(
					'labels'            => array(
						'name'          => __( 'Question Tag', 'learn_press' ),
						'menu_name'     => __( 'Tag', 'learn_press' ),
						'singular_name' => __( 'Tag', 'learn_press' ),
						'add_new_item'  => __( 'Add New Tag', 'learn_press' ),
						'all_items'     => __( 'All Tags', 'learn_press' )
					),
					'public'            => true,
					'hierarchical'      => false,
					'show_ui'           => true,
					'show_admin_column' => 'true',
					'show_in_nav_menus' => true,
					'rewrite'           => array(
						'slug'         => _x( 'question-tag', 'Permalink Slug', 'learn_press' ),
						'hierarchical' => false,
						'with_front'   => false
					),
				)
			);
			add_post_type_support( 'question', 'comments' );


		}

		function add_meta_boxes() {
			$this->meta_box_answers();
			$this->meta_box_settings();
		}

		function meta_box_answers() {
			new RW_Meta_Box(
				array(
					'id'     => 'question_answers',
					'title'  => __( 'Question answers', 'learn_press' ),
					'pages'  => array( LPR_QUESTION_CPT ),
					'fields' => array(
						array(
							'name' => '',
							'id'   => "_lpr_question_type",
							'type' => 'question'
						)
					)
				)
			);
		}

		function meta_box_settings() {
			$prefix = '_lpr_';

			new RW_Meta_Box(
				apply_filters(
					'learn_press_question_meta_box_args',
					array(
						'id'     => 'question_settings',
						'title'  => __( 'Settings', 'learn_press' ),
						'pages'  => array( LPR_QUESTION_CPT ),
						'fields' => array(
							array(
								'name' => __( 'Explanation', 'learn_press' ),
								'id'   => "{$prefix}explanation",
								'type' => 'textarea',
								'desc' => __( 'Explanation for this question', 'learn_press' ),
							),
							array(
								'name'  => __( 'Mark For This Question', 'learn_press' ),
								'id'    => "{$prefix}question_mark",
								'type'  => 'number',
								'desc'  => __( 'Mark for choosing the right answer', 'learn_press' ),
								'min'   => 1,
								'std'   => 1
							)
						)
					)
				)
			);
		}

		/**
		 * Enqueue scripts
		 */
		function enqueue_script() {
			if ( !in_array( get_post_type(), array( 'lpr_question' ) ) ) return;
			ob_start();
			?>
			<script>
				var form = $('#post');
				form.submit(function (evt) {
					var $title = $('#title'),
						is_error = false;
					if (0 == $title.val().length) {
						alert('<?php _e( 'Please enter the title of the question', 'learn_press' );?>');
						$title.focus();
						is_error = true;
					} else if ($('.lpr-question-types').length && ( 0 == $('.lpr-question-types').val().length )) {
						alert('<?php _e( 'Please a type of question', 'learn_press' );?>');
						$('.lpr-question-types').focus();
						is_error = true;
					}
					if (is_error) {
						evt.preventDefault();
						return false;
					}
				});
			</script>
			<?php
			$script = ob_get_clean();
			$script = preg_replace( '!</?script>!', '', $script );
			learn_press_enqueue_script( $script );
			ob_start();
			?>
			<script type="text/html" id="tmpl-form-quick-add-question">
				<div id="lpr-form-quick-add-question" class="lpr-quick-add-form">
					<input type="text">
					<select class="lpr-question-types lpr-select2" name="lpr_question[type]" id="lpr-quiz-question-type">
						<?php if ( $questions = LPR_Question_Factory::get_types() ): ?>
							<?php foreach ( $questions as $type ): $question = LPR_Question_Factory::instance()->get_question( $type );
								if ( !$question ) continue; ?>
								<option value="<?php echo $type; ?>"><?php echo $question->get_name(); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<button class="button" data-action="add" type="button"><?php _e( 'Add [Enter]', 'learn_press' ); ?></button>
					<button data-action="cancel" class="button" type="button"><?php _e( 'Cancel [ESC]', 'learn_press' ); ?></button>
					<span class="lpr-ajaxload">...</span>
				</div>
			</script>
			<?php
			$js_template = ob_get_clean();
			learn_press_enqueue_script( $js_template, true );
		}

		/**
		 * Add columns to admin manage question page
		 *
		 * @param  array $columns
		 *
		 * @return array
		 */
		function columns_head( $columns ) {
			$user = wp_get_current_user();
			if ( in_array( 'lpr_teacher', $user->roles ) ) {
				unset( $columns['author'] );
			}
			return $columns;
		}

	} // end LPR_Question_Post_Type
}

new LPR_Question_Post_Type();