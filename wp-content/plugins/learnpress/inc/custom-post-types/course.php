<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !class_exists( 'LPR_Course_Post_Type' ) ) {

	// class LPR_Course_Post_Type
	final class LPR_Course_Post_Type {
		protected static $loaded;

		function __construct() {
			if ( self::$loaded ) return;

			add_action( 'init', array( $this, 'register_post_type' ) );

			add_action( 'admin_head', array( $this, 'enqueue_script' ) );
			add_action( 'admin_init', array( $this, 'add_meta_boxes' ), 0 );
			add_action( 'rwmb_course_curriculum_before_save_post', array( $this, 'before_save_curriculum' ) );
			add_filter( 'manage_lpr_course_posts_columns', array( $this, 'columns_head' ) );

			add_filter( "rwmb__lpr_course_price_html", array( $this, 'currency_symbol' ), 5, 3 );

			self::$loaded = true;
		}

		function currency_symbol( $input_html, $field, $sub_meta ) {
			return $input_html . '<span class="lpr-course-price-symbol">' . learn_press_get_currency_symbol() . '</span>';
		}

		/**
		 * Register course post type
		 */
		function register_post_type() {
			$labels = array(
				'name'               => _x( 'Courses', 'Post Type General Name', 'learn_press' ),
				'singular_name'      => _x( 'Course', 'Post Type Singular Name', 'learn_press' ),
				'menu_name'          => __( 'Courses', 'learn_press' ),
				'parent_item_colon'  => __( 'Parent Item:', 'learn_press' ),
				'all_items'          => __( 'Courses', 'learn_press' ),
				'view_item'          => __( 'View Course', 'learn_press' ),
				'add_new_item'       => __( 'Add New Course', 'learn_press' ),
				'add_new'            => __( 'Add New', 'learn_press' ),
				'edit_item'          => __( 'Edit Course', 'learn_press' ),
				'update_item'        => __( 'Update Course', 'learn_press' ),
				'search_items'       => __( 'Search Course', 'learn_press' ),
				'not_found'          => __( 'No course found', 'learn_press' ),
				'not_found_in_trash' => __( 'No course found in Trash', 'learn_press' ),
			);


			$args = array(
				'labels'             => $labels,
				'public'             => true,
                'query_var'          => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'has_archive'        => ( $page_id = learn_press_get_page_id( 'courses' ) ) && get_post( $page_id ) ? get_page_uri( $page_id ) : _x( 'courses', 'Permalink Slug', 'learn_press' ),
				'capability_type'    => LPR_COURSE_CPT,
				'map_meta_cap'       => true,
				'show_in_menu'       => 'learn_press',
				'show_in_admin_bar'  => true,
				'show_in_nav_menus'  => true,
				'taxonomies'         => array( 'course_category', 'course_tag' ),
				'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions', 'comments', 'author' ),
				'hierarchical'       => true,
				'rewrite'            => array( 'slug' => _x( 'courses', 'Permalink Slug', 'learn_press' ), 'hierarchical' => true, 'with_front' => false )
			);
			register_post_type( LPR_COURSE_CPT, $args );

			register_taxonomy( 'course_category', array( LPR_COURSE_CPT ),
				array(
					'label'             => __( 'Course Categories', 'learn_press' ),
					'labels'            => array(
						'name'          => __( 'Course Categories', 'learn_press' ),
						'menu_name'     => __( 'Category', 'learn_press' ),
						'singular_name' => __( 'Category', 'learn_press' ),
						'add_new_item'  => __( 'Add New Course Category', 'learn_press' ),
						'all_items'     => __( 'All Categories', 'learn_press' )
					),
					'query_var'         => true,
					'public'            => true,
					'hierarchical'      => true,
					'show_ui'           => true,
					'show_in_menu'      => 'learn_press',
					'show_admin_column' => true,
					'show_in_admin_bar' => true,
					'show_in_nav_menus' => true,
					'rewrite'           => array(
						'slug'         => _x( 'course_category', 'Permalink Slug', 'learn_press' ),
						'hierarchical' => true,
						'with_front'   => false
					),
				)
			);
			register_taxonomy( 'course_tag', array( LPR_COURSE_CPT ),
				array(
					'labels'                => array(
						'name'                       => __( 'Course Tags', 'learn_press' ),
						'singular_name'              => __( 'Tag', 'learn_press' ),
						'search_items'               => __( 'Search Course Tags', 'learn_press' ),
						'popular_items'              => __( 'Popular Course Tags', 'learn_press' ),
						'all_items'                  => __( 'All Course Tags', 'learn_press' ),
						'parent_item'                => null,
						'parent_item_colon'          => null,
						'edit_item'                  => __( 'Edit Course Tag', 'learn_press' ),
						'update_item'                => __( 'Update Course Tag', 'learn_press' ),
						'add_new_item'               => __( 'Add New Course Tag', 'learn_press' ),
						'new_item_name'              => __( 'New Course Tag Name', 'learn_press' ),
						'separate_items_with_commas' => __( 'Separate tags with commas', 'learn_press' ),
						'add_or_remove_items'        => __( 'Add or remove tags', 'learn_press' ),
						'choose_from_most_used'      => __( 'Choose from the most used tags', 'learn_press' ),
						'menu_name'                  => __( 'Tags', 'learn_press' ),
					),
					'public'                => true,
					'hierarchical'          => false,
					'show_ui'               => true,
					'show_in_menu'          => 'learn_press',
					'update_count_callback' => '_update_post_term_count',
					'query_var'             => true,
					'rewrite'               => array(
						'slug'         => _x( 'course_tag', 'Permalink Slug', 'learn_press' ),
						'hierarchical' => true,
						'with_front'   => false
					),
				)
			);
			if ( !is_admin() ) {
				LPR_Assets::enqueue_script( 'tipsy', LPR_PLUGIN_URL . '/assets/js/jquery.tipsy.js' );
				LPR_Assets::enqueue_style( 'tipsy', LPR_PLUGIN_URL . '/assets/css/tipsy.css' );
			}
			flush_rewrite_rules();
		}

		/**
		 * Add meta boxes to course post type page
		 */
		function add_meta_boxes() {

			new RW_Meta_Box( $this->curriculum_meta_box() );
			new RW_Meta_Box( $this->settings_meta_box() );
			new RW_Meta_Box( $this->assessment_meta_box() );
			new RW_Meta_Box( $this->payment_meta_box() );

		}

		function curriculum_meta_box() {
			$prefix = '_lpr_';

			$meta_box = array(
				'id'       => 'course_curriculum',
				'title'    => __( 'Course Curriculum', 'learn_press' ),
				'priority' => 'high',
				'pages'    => array( LPR_COURSE_CPT ),
				'fields'   => array(
					array(
						'name' => __( 'Course Curriculum', 'learn_press' ),
						'id'   => "{$prefix}course_lesson_quiz",
						'type' => 'course_lesson_quiz',
						'desc' => '',
					),
				)
			);

			return apply_filters( 'learn_press_course_curriculum_meta_box_args', $meta_box );
		}

		function settings_meta_box() {
			$prefix = '_lpr_';

			$meta_box = array(
				'id'       => 'course_settings',
				'title'    => __( 'Course Settings', 'learn_press' ),
				'pages'    => array( LPR_COURSE_CPT ),
				'priority' => 'high',
				'fields'   => array(
					array(
						'name' => __( 'Course Duration', 'learn_press' ),
						'id'   => "{$prefix}course_duration",
						'type' => 'number',
						'desc' => __( 'The length of the course (by weeks)', 'learn_press' ),
						'std'  => 10,
					),
					// array(
					// 	'name' => __( 'Course Time', 'learn_press' ),
					// 	'id'   => "{$prefix}course_time",
					// 	'type' => 'text',
					// 	'desc' => 'Course start and end time. Example:2-4pm',
					// ),
					array(
						'name' => __( 'Number of Students Enrolled', 'learn_press' ),
						'id'   => "{$prefix}course_student",
						'type' => 'number',
						'desc' => __( 'The number of students took this course', 'learn_press' ),
						'std'  => 0,
					),
					array(
						'name' => __( 'Maximum students can take the course', 'learn_press' ),
						'id'   => "{$prefix}max_course_number_student",
						'type' => 'number',
						'desc' => __( 'Maximum Number Student of the Course', 'learn_press' ),
						'std'  => 1000,
					),
					array(
						'name' => __( 'Re-take course', 'learn_press' ),
						'id'   => "{$prefix}retake_course",
						'type' => 'number',
						'desc' => __( 'How many times the user can re-take this course. Set to 0 to disable', 'learn_press' ),
						'std'  => '0',
					),

				)
			);

			return apply_filters( 'learn_press_course_settings_meta_box_args', $meta_box );
		}

		function assessment_meta_box() {
			$prefix = '_lpr_';

			$meta_box = array(
				'id'     	=> 'course_assessment',
				'title'  	=> __('Course Assessment Settings', 'learn_press'),
				'priority' 	=> 'high',
				'pages'  	=> array( LPR_COURSE_CPT ),
				'fields' 	=> array(
					array(
						'name'    => __( 'Course Final Quiz', 'learn_press' ),
						'id'      => "{$prefix}course_final",
						'type'    => 'radio',
						'desc'    => __('If Final Quiz option is checked, then the course will be assessed by result of the last quiz, else the course will be assessed by the progress of learning lessons', 'learn_press'),
						'options' => array(
							'no'  => __( 'No Final Quiz', 'learn_press' ),
                            'yes' => __( 'Using Final Quiz', 'learn_press' )
						),
						'std'     => 'no'
					),
					array(
						'name' => __( 'Passing Condition', 'learn_press' ),
						'id'   => "{$prefix}course_condition",
						'type' => 'number',
						'min'  => 1,
						'max'  => 100,
						'desc' => __('The percentage of quiz result to finish the course', 'learn_press'),
						'std'  => 50
					)
				)
			);
			return apply_filters( 'learn_press_course_assessment_metabox', $meta_box );
		}

		function payment_meta_box() {

			$prefix = '_lpr_';

			$meta_box = array(
				'id'     	=> 'course_payment',
				'title'  	=> __('Course Payment Settings', 'learn_press'),
				'priority' 	=> 'high',
				'pages'  	=> array( LPR_COURSE_CPT ),
				'fields' 	=> array(
                    array(
                        'name'    => __( 'Enrolled Require', 'learn_press' ),
                        'id'      => "{$prefix}course_enrolled_require",
                        'type'    => 'radio',
                        'desc'    => __('Require users logged in to study or public to all', 'learn_press'),
                        'options' => array(
                            'yes'     => __( 'Yes, enroll is required', 'learn_press' ),
                            'no' => __( 'No', 'learn_press' ),
                        ),
                        'std'     => 'yes',
                        'class' => 'hide-if-js'
                    ),
					array(
						'name'    => __( 'Course Payment', 'learn_press' ),
						'id'      => "{$prefix}course_payment",
						'type'    => 'radio',
						'desc'    => __('If Paid be checked, An administrator will review then set course price and commission', 'learn_press'),
						'options' => array(
							'free'     => __( 'Free', 'learn_press' ),
							'not_free' => __( 'Paid', 'learn_press' ),
						),
						'std'     => 'free',
                        'class' => 'lpr-course-payment-field'
					)
				)
			);

			if ( current_user_can( 'manage_options' ) ) {
				$message = __('If free, this field is empty or set 0. (Only admin can edit this field)', 'learn_press');
				$price = 0;

				if( isset($_GET['post']) ) {
					$course_id = $_GET['post'];
					$type = get_post_meta( $course_id, '_lpr_course_payment', true );
					if( $type != 'free' ) {
						$suggest_price = get_post_meta( $course_id, '_lpr_course_suggestion_price', true );
						if( isset( $suggest_price ) ) {
							$message = __('This course is enrolled require and the suggestion price is ', 'learn_press') . '<span>' . learn_press_get_currency_symbol() . $suggest_price . '</span>';
							$price = $suggest_price;
						}
					} else {
						$message = __('This course is free.','learn_press');
					};
				}
				array_push(
					$meta_box['fields'],
					array(
						'name' => __( 'Course Price', 'learn_press' ),
						'id'   => "{$prefix}course_price",
						'type' => 'number',
						'min'  => 0,
						'step' => 0.01,
						'desc' => $message,
						'std'  => $price,
                        'class' => 'lpr-course-price-field hide-if-js'
					)
				);
			} else {
				array_push(
					$meta_box['fields'],
					array(
						'name' 	=> __( 'Course Suggestion Price', 'learn_press'),
						'id'	=> "{$prefix}course_suggestion_price",
						'type'	=> 'number',
						'min'	=> 0,
						'step'	=> 0.01,
						'desc'	=> __('The course price you want to suggest for admin to set.', 'learn_press'),
                        'class' => 'lpr-course-price-field hide-if-js',
                        'std'   => 0
					)
					);
			}
			return apply_filters( 'learn_press_course_payment_meta_box_args', $meta_box );
		}

		function before_save_curriculum() {
			if ( $sections = $_POST['_lpr_course_lesson_quiz'] ) foreach ( $sections as $k => $section ) {
				if ( empty( $section['name'] ) ) {
					unset( $_POST['_lpr_course_lesson_quiz'][$k] );
				}
				$_POST['_lpr_course_lesson_quiz'] = array_values( $_POST['_lpr_course_lesson_quiz'] );
			}

		}

		function enqueue_script() {
			if ( 'lpr_course' != get_post_type() ) return;
			ob_start();
			global $post;
			?>
			<script>
				window.course_id = <?php echo $post->ID;?>, form = $('#post');
				form.submit(function (evt) {
					var $title = $('#title'),
						$curriculum = $('.lpr-curriculum-section:not(.lpr-empty)'),
						is_error = false;
					if (0 == $title.val().length) {
						alert('<?php _e( 'Please enter the title of the course', 'learn_press' );?>');
						$title.focus();
						is_error = true;
					} else if (0 == $curriculum.length) {
						alert('<?php _e( 'Please add at least one section for the course', 'learn_press' );?>');
						$('.lpr-curriculum-section .lpr-section-name').focus();
						is_error = true;
					} else {
						$curriculum.each(function () {
							var $section = $('.lpr-section-name', this);
							if (0 == $section.val().length) {
								alert('<?php _e( 'Please enter the title of the section', 'learn_press' );?>');
								$section.focus();
								is_error = true;
								return false;
							}
						});
					}
                    if( $( 'input[name="_lpr_course_payment"]:checked').val() == 'not_free' && $('input[name="_lpr_course_price"]').val() <= 0 ){
                        alert( '<?php _e( 'Please set a price for this course', 'learn_press' );?>' );
                        is_error = true;
                        $('input[name="_lpr_course_price"]').focus();
                    }
					if (true == is_error) {
						evt.preventDefault();
						return false;
					}
				});
                $('input[name="_lpr_course_final"]').bind('click change', function(){
                    if( $(this).val() == 'yes' ){
                        $(this).closest('.rwmb-field').next().show();
                    }else{
                        $(this).closest('.rwmb-field').next().hide();
                    }
                }).filter(":checked").trigger('change')
			</script>
			<?php
			$script = ob_get_clean();
			$script = preg_replace( '!</?script>!', '', $script );
			learn_press_enqueue_script( $script );
		}

		/**
		 * Add columns to admin manage course page
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
	} // end LPR_Course_Post_Type
	new LPR_Course_Post_Type();
}


