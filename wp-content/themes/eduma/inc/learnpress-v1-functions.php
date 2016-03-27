<?php
/**
 * Custom functions for LearnPress 1.x
 *
 * @package thim
 */

if ( ! function_exists( 'thim_remove_learnpress_hooks' ) ) {
	function thim_remove_learnpress_hooks() {

		if( thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			$addon_review = LP_Addon_Course_Review::instance();
			remove_action( 'learn_press_after_the_title', array( $addon_review, 'print_rate' ), 10 );
			remove_action( 'learn_press_content_learning_summary', array( $addon_review, 'print_review' ), 80 );
			remove_action( 'learn_press_content_learning_summary', array( $addon_review, 'add_review_button' ), 5 );
		}


		remove_action( 'learn_press_before_main_content', 'learn_press_breadcrumb', 10 );
		remove_action( 'learn_press_after_the_title', 'learn_press_course_thumbnail', 10 );
		remove_action( 'learn_press_courses_loop_item_title', 'learn_press_courses_loop_item_title', 10 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_thumbnail', 10 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_price', 15 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_students', 20 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_instructor', 25 );
		remove_action( 'learn_press_after_courses_loop_item', 'learn_press_courses_loop_item_introduce', 30 );


		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_thumbnail', 5 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_title', 10 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_meta_start_wrapper', 15 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_price', 25 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_students', 30 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_meta_end_wrapper', 35 );
		//remove_action( 'learn_press_content_landing_summary', 'learn_press_single_course_content_lesson', 40 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_enroll_button', 45 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_single_course_description', 55 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_progress', 60 );
		remove_action( 'learn_press_content_landing_summary', 'learn_press_course_curriculum', 65 );

		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_thumbnail', 5 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_meta_start_wrapper', 10 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_status', 15 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_instructor', 20 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_students', 25 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_meta_end_wrapper', 30 );

		remove_action( 'learn_press_content_learning_summary', 'learn_press_single_course_description', 35 );
		//remove_action( 'learn_press_content_learning_summary', 'learn_press_single_course_content_lesson', 40 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_progress', 45 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_finish_button', 50 );
		remove_action( 'learn_press_content_learning_summary', 'learn_press_course_curriculum', 55 );

		remove_all_actions( 'learn_press_after_single_course_summary', 100 );

		remove_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_sidebar', 45 );
		remove_action( 'learn_press_single_quiz_sidebar', 'learn_press_single_quiz_timer', 5 );
		remove_action( 'learn_press_single_quiz_sidebar', 'learn_press_single_quiz_buttons', 10 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_title', 5 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_description', 10 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_left_start_wrap', 15 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_question', 20 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_result', 20 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_questions_nav', 25 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_questions', 30 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_history', 35 );
		//add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_left_end_wrap', 40 );
		add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_timer', 12 );
		add_action( 'learn_press_single_quiz_summary', 'learn_press_single_quiz_buttons', 50 );


		//remove_action( 'learn_press_user_profile_summary', 'learn_press_output_user_profile_info', 5 );
		//remove_action( 'learn_press_user_profile_summary', 'learn_press_output_user_profile_tabs', 10 );
		//remove_action( 'learn_press_user_profile_summary', 'learn_press_output_user_profile_order', 15 );

		// Remove register page from BuddyPress
		remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );

	}
}

add_action( 'after_setup_theme', 'thim_remove_learnpress_hooks', 15 );

if ( ! function_exists( 'thim_course_wishlist_button' ) ) {
	function thim_course_wishlist_button( $course_id = null ) {
		if ( ! thim_plugin_active( 'learnpress-wishlist/learnpress-wishlist.php' ) ) {
			return;
		}
		LP_Addon_Wishlist::instance()->wishlist_button( $course_id );

	}
}

/**
 * Display table detailed rating
 *
 * @param $course_id
 * @param $total
 */
function thim_detailed_rating( $course_id, $total ) {
	global $wpdb;
	$query = $wpdb->get_results( $wpdb->prepare(
		"
		SELECT cm2.meta_value AS rating, COUNT(*) AS quantity FROM $wpdb->posts AS p
		INNER JOIN $wpdb->comments AS c ON p.ID = c.comment_post_ID
		INNER JOIN $wpdb->users AS u ON u.ID = c.user_id
		INNER JOIN $wpdb->commentmeta AS cm1 ON cm1.comment_id = c.comment_ID AND cm1.meta_key=%s
		INNER JOIN $wpdb->commentmeta AS cm2 ON cm2.comment_id = c.comment_ID AND cm2.meta_key=%s
		WHERE p.ID=%d AND c.comment_type=%s
		GROUP BY cm2.meta_value",
		'_lpr_review_title',
		'_lpr_rating',
		$course_id,
		'review'
	), OBJECT_K
	);
	?>
	<div class="detailed-rating">
		<?php for ( $i = 5; $i >= 1; $i -- ) : ?>
			<div class="stars">
				<div class="key"><?php printf( esc_html__( '%s stars', 'eduma' ), $i ); ?></div>
				<div class="bar">
					<div class="full_bar">
						<div style="<?php echo ( $total && ! empty( $query[ $i ]->quantity ) ) ? esc_attr( 'width: ' . ( $query[ $i ]->quantity / $total * 100 ) . '%' ) : 'width: 0%'; ?>"></div>
					</div>
				</div>
				<div class="value"><?php echo empty( $query[ $i ]->quantity ) ? '0' : esc_html( $query[ $i ]->quantity ); ?></div>
			</div>
		<?php endfor; ?>
	</div>
	<?php
}


/**
 * Display review button
 *
 * @param $course_id
 */
if ( ! function_exists( 'thim_review_button' ) ) {
	function thim_review_button( $course_id ) {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		if ( ! get_current_user_id() ) {
			return;
		}
		if ( LP()->user->has( 'enrolled-course', $course_id ) || get_post_meta( $course_id, '_lp_required_enroll', true ) == 'no' ) {
			if ( ! learn_press_get_user_rate( $course_id ) ) {
				?>
				<div class="add-review">
					<h3 class="title"><?php esc_html_e( 'Leave A Review', 'eduma' ); ?></h3>

					<p class="description"><?php esc_html_e( 'Please provide as much detail as you can to justify your rating and to help others.', 'eduma' ); ?></p>
					<?php do_action( 'learn_press_before_review_fields' ); ?>
					<form method="post">
						<div>
							<label for="review-title"><?php esc_html_e( 'Title', 'eduma' ); ?>
								<span class="required">*</span></label>
							<input required type="text" id="review-title" name="review-course-title" />
						</div>
						<div>

							<label><?php esc_html_e( 'Rating', 'eduma' ); ?>
								<span class="required">*</span></label>

							<div class="review-stars-rated">
								<ul class="review-stars">
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
									<li><span class="fa fa-star-o"></span></li>
								</ul>
								<ul class="review-stars filled" style="width: 100%">
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
									<li><span class="fa fa-star"></span></li>
								</ul>
							</div>
						</div>
						<div>
							<label for="review-content"><?php esc_html_e( 'Comment', 'eduma' ); ?>
								<span class="required">*</span></label>
							<textarea required id="review-content" name="review-course-content"></textarea>
						</div>
						<input type="hidden" id="review-course-value" name="review-course-value" value="5" />
						<input type="hidden" id="comment_post_ID" name="comment_post_ID" value="<?php echo get_the_ID(); ?>" />
						<button type="submit"><?php esc_html_e( 'Submit Review', 'eduma' ); ?></button>
					</form>
					<?php do_action( 'learn_press_after_review_fields' ); ?>
				</div>
				<?php
			}
		}
	}
}


/**
 * Process review
 */
if ( ! function_exists( 'thim_process_review' ) ) {
	function thim_process_review() {

		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		$user_id     = get_current_user_id();
		$course_id   = isset ( $_POST['comment_post_ID'] ) ? $_POST['comment_post_ID'] : 0;
		$user_review = learn_press_get_user_rate( $course_id, $user_id );
		if ( ! $user_review && $course_id ) {
			$review_title   = isset ( $_POST['review-course-title'] ) ? $_POST['review-course-title'] : 0;
			$review_content = isset ( $_POST['review-course-content'] ) ? $_POST['review-course-content'] : 0;
			$review_rate    = isset ( $_POST['review-course-value'] ) ? $_POST['review-course-value'] : 0;
			learn_press_add_course_review( array(
				'title'     => $review_title,
				'content'   => $review_content,
				'rate'      => $review_rate,
				'user_id'   => $user_id,
				'course_id' => $course_id
			) );
		}
	}
}

add_action( 'learn_press_before_main_content', 'thim_process_review' );

/**
 * Display course ratings
 */
if ( ! function_exists( 'thim_course_ratings' ) ) {
	function thim_course_ratings() {

		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		$course_id   = get_the_ID();
		$course_rate = learn_press_get_course_rate( $course_id );
		$ratings     = learn_press_get_course_rate_total( $course_id );
		?>
		<div class="course-review">
			<label><?php esc_html_e( 'Review', 'eduma' ); ?></label>

			<div class="value">
				<?php thim_print_rating( $course_rate ); ?>
				<span><?php $ratings ? printf( _n( '(%1$s review)', '(%1$s reviews)', $ratings, 'eduma' ), number_format_i18n( $ratings ) ) : esc_html_e( '(0 review)', 'eduma' ); ?></span>
			</div>
		</div>
		<?php
	}
}

/**
 * Display ratings count
 */

if ( ! function_exists( 'thim_course_ratings_count' ) ) {
	function thim_course_ratings_count( $course_id = null ) {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}
		if ( ! $course_id ) {
			$course_id = get_the_ID();
		}
		$ratings = learn_press_get_course_rate_total( $course_id ) ? learn_press_get_course_rate_total( $course_id ) : 0;
		echo '<div class="course-comments-count">';
		echo '<div class="value"><i class="fa fa-comment"></i>';
		echo esc_html( $ratings );
		echo '</div>';
		echo '</div>';
	}
}

if ( ! function_exists( 'thim_print_rating' ) ) {
	function thim_print_rating( $rate ) {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		?>
		<div class="review-stars-rated">
			<ul class="review-stars">
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
				<li><span class="fa fa-star-o"></span></li>
			</ul>
			<ul class="review-stars filled" style="<?php echo esc_attr( 'width: ' . ( $rate * 20 ) . '%' ) ?>">
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
				<li><span class="fa fa-star"></span></li>
			</ul>
		</div>
		<?php
	}
}

/**
 * Display course review
 */
if ( ! function_exists( 'thim_course_review' ) ) {
	function thim_course_review() {
		if ( ! thim_plugin_active( 'learnpress-course-review/learnpress-course-review.php' ) ) {
			return;
		}

		$course_id     = get_the_ID();
		$course_review = learn_press_get_course_review( $course_id, isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1, 5, true );
		$course_rate   = learn_press_get_course_rate( $course_id );
		$total         = learn_press_get_course_rate_total( $course_id );
		$reviews       = $course_review['reviews'];

		?>
		<div class="course-rating">
			<h3><?php esc_html_e( 'Reviews', 'eduma' ); ?></h3>

			<div class="average-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
				<p class="rating-title"><?php esc_html_e( 'Average Rating', 'eduma' ); ?></p>

				<div class="rating-box">
					<div class="average-value" itemprop="ratingValue"><?php echo ( $course_rate ) ? esc_html( round( $course_rate, 1 ) ) : 0; ?></div>
					<div class="review-star">
						<?php thim_print_rating( $course_rate ); ?>
					</div>
					<div class="review-amount" itemprop="ratingCount">
						<?php $total ? printf( _n( '%1$s rating', '%1$s ratings', $total, 'eduma' ), number_format_i18n( $total ) ) : esc_html_e( '0 rating', 'eduma' ); ?>
					</div>
				</div>
			</div>
			<div class="detailed-rating">
				<p class="rating-title"><?php esc_html_e( 'Detailed Rating', 'eduma' ); ?></p>

				<div class="rating-box">
					<?php thim_detailed_rating( $course_id, $total ); ?>
				</div>
			</div>
		</div>

		<div class="course-review">
			<div id="course-reviews" class="content-review">
				<ul class="course-reviews-list">
					<?php foreach ( $reviews as $review ) : ?>
						<li>
							<div class="review-container" itemprop="review" itemscope itemtype="http://schema.org/Review">
								<div class="review-author">
									<?php echo get_avatar( $review->ID, 70 ); ?>
								</div>
								<div class="review-text">
									<h4 class="author-name" itemprop="author"><?php echo esc_html( $review->display_name ); ?></h4>

									<div class="review-star">
										<?php thim_print_rating( $review->rate ); ?>
									</div>
									<p class="review-title"><?php echo esc_html( $review->title ); ?></p>

									<div class="description" itemprop="reviewBody">
										<p><?php echo esc_html( $review->content ); ?></p>
									</div>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php if ( empty( $course_review['finish'] ) && $total ) : ?>
			<div class="review-load-more">
				<span id="course-review-load-more" data-paged="<?php echo esc_attr( $course_review['paged'] ); ?>"><i class="fa fa-angle-double-down"></i></span>
			</div>
		<?php endif; ?>
		<?php thim_review_button( $course_id ); ?>
		<?php
	}
}


/**
 * Display the link to course forum
 */
if ( ! function_exists( 'thim_course_forum_link' ) ) {
	function thim_course_forum_link() {

		if ( thim_plugin_active( 'bbpress/bbpress.php' ) && thim_plugin_active( 'learnpress-bbpress/learnpress-bbpress.php' ) ) {
			do_action( 'learn_press_before_course_forum' );
			if ( learn_press_is_connect_forum( get_the_ID() ) ) {
				printf(
					'<div class="forum-link">
					<label>%s</label>
					<div class="value">
						<a href="%s">%s</a>
					</div>
				</div>',
					esc_html__( 'Connect', 'eduma' ),
					learn_press_get_forum_link( get_the_ID() ),
					apply_filters( 'learn_press_forum_link_text', esc_html__( 'Forum', 'eduma' ) )
				);
			}
			do_action( 'learn_press_after_course_forum' );
		}
	}
}


/**
 * Display course info
 */
if ( ! function_exists( 'thim_course_info' ) ) {
	function thim_course_info() {
		global $course;
		$course_id = get_the_ID();
		?>
		<div class="thim-course-info">
			<h3 class="title"><?php esc_html_e( 'Course Features', 'eduma' ); ?></h3>
			<ul>
				<li>
					<i class="fa fa-files-o"></i>
					<span class="label"><?php esc_html_e( 'Lectures', 'eduma' ); ?></span>
					<span class="value"><?php echo count( $course->get_lessons() ); ?></span>
				</li>
				<li>
					<i class="fa fa-puzzle-piece"></i>
					<span class="label"><?php esc_html_e( 'Quizzes', 'eduma' ); ?></span>
					<span class="value"><?php echo count( $course->get_quizzes() ); ?></span>
				</li>
				<li>
					<i class="fa fa-clock-o"></i>
					<span class="label"><?php esc_html_e( 'Duration', 'eduma' ); ?></span>
					<span class="value"><?php echo esc_html( get_post_meta( $course_id, 'thim_course_duration', true ) ); ?></span>
				</li>
				<li>
					<i class="fa fa-level-up"></i>
					<span class="label"><?php esc_html_e( 'Skill level', 'eduma' ); ?></span>
					<span class="value"><?php echo esc_html( get_post_meta( $course_id, 'thim_course_skill_level', true ) ); ?></span>
				</li>
				<li>
					<i class="fa fa-language"></i>
					<span class="label"><?php esc_html_e( 'Language', 'eduma' ); ?></span>
					<span class="value"><?php echo esc_html( get_post_meta( $course_id, 'thim_course_language', true ) ); ?></span>
				</li>
				<li>
					<i class="fa fa-users"></i>
					<span class="label"><?php esc_html_e( 'Students', 'eduma' ); ?></span>
					<span class="value"><?php echo esc_html( $course->count_users_enrolled() ); ?></span>
				</li>
				<?php //thim_course_certificate( $course_id ); ?>
				<li>
					<i class="fa fa-check-square-o"></i>
					<span class="label"><?php esc_html_e( 'Assessments', 'eduma' ); ?></span>
					<span class="value"><?php echo ( get_post_meta( $course_id, '_lp_course_result', true ) == 'yes' ) ? esc_html__( 'Yes', 'eduma' ) : esc_html__( 'Self', 'eduma' ); ?></span>
				</li>
			</ul>
			<?php thim_course_wishlist_button(); ?>
		</div>
		<?php
	}

}


/**
 * Add some meta data for a course
 *
 * @param $meta_box
 */
if ( ! function_exists( 'thim_add_course_meta' ) ) {
	function thim_add_course_meta( $meta_box ) {
		$fields             = $meta_box['fields'];
		$fields[]           = array(
			'name' => esc_html__( 'Duration', 'eduma' ),
			'id'   => 'thim_course_duration',
			'type' => 'text',
			'desc' => esc_html__( 'Course duration', 'eduma' ),
			'std'  => esc_html__( '50 hours', 'eduma' )
		);
		$fields[]           = array(
			'name' => esc_html__( 'Skill Level', 'eduma' ),
			'id'   => 'thim_course_skill_level',
			'type' => 'text',
			'desc' => esc_html__( 'A possible level with this course', 'eduma' ),
			'std'  => esc_html__( 'All level', 'eduma' )
		);
		$fields[]           = array(
			'name' => esc_html__( 'Language', 'eduma' ),
			'id'   => 'thim_course_language',
			'type' => 'text',
			'desc' => esc_html__( 'Language\'s used for studying', 'eduma' ),
			'std'  => esc_html__( 'English', 'eduma' )
		);
		$meta_box['fields'] = $fields;

		return $meta_box;
	}

}

add_filter( 'learn_press_course_settings_meta_box_args', 'thim_add_course_meta' );


/**
 * Add format icon before curriculum items
 *
 * @param $lesson_or_quiz
 * @param $enrolled
 */
if ( ! function_exists( 'thim_add_format_icon' ) ) {
	function thim_add_format_icon( $item ) {
		$format = get_post_format( $item->item_id );
		if ( get_post_type( $item->item_id ) == 'lp_quiz' ) {
			echo '<span class="course-format-icon"><i class="fa fa-puzzle-piece"></i></span>';
		} elseif ( $format == 'video' ) {
			echo '<span class="course-format-icon"><i class="fa fa-play-circle"></i></span>';
		} else {
			echo '<span class="course-format-icon"><i class="fa fa-file-o"></i></span>';
		}
	}
}

add_action( 'learn_press_before_section_item_title', 'thim_add_format_icon', 10, 1 );


/**
 * Display related courses
 */
if ( ! function_exists( 'thim_related_courses' ) ) {
	function thim_related_courses() {
		$related_courses = thim_get_related_courses( null, array( 'posts_per_page' => 3 ) );
		if ( $related_courses ) {
			?>
			<div class="thim-ralated-course">
				<h3 class="related-title"><?php esc_html_e( 'You May Like', 'eduma' ); ?></h3>

				<div class="thim-course-grid">
					<?php foreach ( $related_courses as $course_item ) : ?>
						<?php
						$course      = LP_Course::get_course( $course_item->ID );
						$is_required = get_post_meta( $course->id, '_lp_required_enroll', true );
						?>
						<article class="course-grid-3 lpr_course">
							<div class="course-item">
								<div class="course-thumbnail">
									<a href="<?php echo get_the_permalink( $course_item->ID ); ?>">
										<?php
										echo thim_get_feature_image( get_post_thumbnail_id( $course_item->ID ), 'full', 450, 450, $course->post_title );
										?>
									</a>
									<?php thim_course_wishlist_button( $course_item->ID ); ?>
									<?php echo '<a class="course-readmore" href="' . esc_url( get_the_permalink( $course_item->ID ) ) . '">' . esc_html__( 'Read More', 'eduma' ) . '</a>'; ?>
								</div>
								<div class="thim-course-content">
									<div class="course-author">
										<?php echo get_avatar( $course->post_author, 44 ); ?>
										<div class="author-contain">
											<div class="value">
												<a href="<?php echo esc_url( apply_filters( 'learn_press_instructor_profile_link', '#', $user_id = null, $course_item->ID ) ); ?>">
													<?php echo get_the_author_meta( 'display_name', $course_item->post_author ); ?>
												</a>
											</div>
										</div>
									</div>
									<h2 class="course-title">
										<a rel="bookmark" href="<?php echo get_the_permalink( $course_item->ID ); ?>"><?php echo esc_html( $course_item->post_title ); ?></a>
									</h2> <!-- .entry-header -->
									<div class="course-meta">
										<?php
										$count_student = $course->count_users_enrolled();
										?>
										<div class="course-students">
											<label><?php esc_html_e( 'Students', 'eduma' ); ?></label>
											<?php do_action( 'learn_press_begin_course_students' ); ?>

											<div class="value"><i class="fa fa-group"></i>
												<?php echo esc_html( $count_student ); ?>
											</div>
											<?php do_action( 'learn_press_end_course_students' ); ?>

										</div>
										<?php thim_course_ratings_count( $course_item->ID ); ?>
										<div class="course-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
											<?php if ( $course->is_free() || 'no' == $is_required ) : ?>
												<div class="value free-course" itemprop="price" content="<?php esc_attr_e( 'Free', 'eduma' ); ?>">
													<?php esc_html_e( 'Free', 'eduma' ); ?>
												</div>
											<?php else: $price = learn_press_format_price( $course->get_price(), true ); ?>
												<div class="value " itemprop="price" content="<?php echo esc_attr( $price ); ?>">
													<?php echo esc_html( $price ); ?>
												</div>
											<?php endif; ?>
											<meta itemprop="priceCurrency" content="<?php echo learn_press_get_currency_symbol(); ?>" />
										</div>
									</div>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}
	}
}

function thim_get_related_courses( $limit ) {
	if ( ! $limit ) {
		$limit = 3;
	}
	$course_id = get_the_ID();
	$tag_ids   = array();
	$tags      = get_the_terms( $course_id, 'course_tag' );
	if ( $tags ) {
		foreach ( $tags as $individual_tag ) {
			$tag_ids[] = $individual_tag->slug;
		}
	}

	$args = array(
		'posts_per_page'      => $limit,
		'paged'               => 1,
		'ignore_sticky_posts' => 1,
		'post__not_in'        => array( $course_id ),
		'post_type'           => 'lp_course'
	);

	if ( $tag_ids ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'course_tag',
				'field'    => 'slug',
				'terms'    => $tag_ids
			)
		);
	}
	$related = array();
	if ( $posts = new WP_Query( $args ) ) {
		global $post;
		while ( $posts->have_posts() ) {
			$posts->the_post();
			$related[] = $post;
		}
	}
	wp_reset_query();

	return $related;
}


/**
 * Get number of lessons of a quiz
 *
 * @param $quiz_id
 *
 * @return string
 */
if ( ! function_exists( 'thim_lesson_duration' ) ) {
	function thim_quiz_questions( $quiz_id ) {
		$questions = learn_press_get_quiz_questions( $quiz_id );
		if ( $questions ) {
			return count( $questions );
		}

		return 0;
	}
}

/**
 * Get lesson duration in hours
 *
 * @param $lesson_id
 *
 * @return string
 */
if ( ! function_exists( 'thim_lesson_duration' ) ) {
	function thim_lesson_duration( $lesson_id ) {

		$duration = get_post_meta( $lesson_id, '_lp_duration', true );
		$hour     = floor( $duration / 60 );
		if ( $hour == 0 ) {
			$hour = '';
		} else {
			$hour = $hour . esc_html__( 'h', 'eduma' );
		}
		$minute = $duration % 60;
		$minute = $minute . esc_html__( 'm', 'eduma' );

		return $hour . $minute;
	}
}

/**
 * Create ajax handle for courses searching
 */
if ( ! function_exists( 'thim_courses_searching_callback' ) ) {
	function thim_courses_searching_callback() {
		ob_start();
		$keyword = $_REQUEST['keyword'];
		if ( $keyword ) {
			$keyword   = strtoupper( $keyword );
			$arr_query = array(
				'post_type'           => 'lp_course',
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
				's'                   => $keyword
			);
			$search    = new WP_Query( $arr_query );

			$newdata = array();
			foreach ( $search->posts as $post ) {
				$newdata[] = array(
					'id'    => $post->ID,
					'title' => $post->post_title,
					'guid'  => get_permalink( $post->ID ),
				);
			}

			ob_end_clean();
			if ( count( $search->posts ) ) {
				echo json_encode( $newdata );
			} else {
				$newdata[] = array(
					'id'    => '',
					'title' => '<i>' . esc_html__( 'No course found', 'eduma' ) . '</i>',
					'guid'  => '#',
				);
				echo json_encode( $newdata );
			}
			wp_reset_postdata();
		}
		die();
	}
}

add_action( 'wp_ajax_nopriv_courses_searching', 'thim_courses_searching_callback' );
add_action( 'wp_ajax_courses_searching', 'thim_courses_searching_callback' );

if ( ! function_exists( 'thim_become_a_teacher_filter' ) ) {
	function thim_become_a_teacher_filter( $error, $return ) {
		if ( ! $error ) {
			switch ( $return['code'] ) {
				case 1:
					return true;
					break;
				case 2:
					return true;
					break;
				case 3:
					return true;
					break;
				default:
					return true;
			}
		}

		return true;
	}
}

//add_filter( 'learn_press_become_a_teacher_display_form', 'thim_become_a_teacher_filter', 10, 2 );


/**
 * Filter profile title
 *
 * @param $tab_title
 * @param $key
 *
 * @return string
 */
function thim_tab_profile_filter_title( $tab_title, $key ) {
	switch ( $key ) {
		case 'courses':
			$tab_title = '<i class="fa fa-book"></i><span class="text">' . esc_html__( 'Courses', 'eduma' ) . '</span>';
			break;
		case 'quizzes':
			$tab_title = '<i class="fa fa-check-square-o"></i><span class="text">' . esc_html__( 'Quiz Results', 'eduma' ) . '</span>';
			break;
		case 'orders':
			$tab_title = '<i class="fa fa-shopping-cart"></i><span class="text">' . esc_html__( 'Orders', 'eduma' ) . '</span>';
			break;
		case 'wishlist':
			$tab_title = '<i class="fa fa-heart-o"></i><span class="text">' . esc_html__( 'Wishlist', 'eduma' ) . '</span>';
			break;
	}

	return $tab_title;
}

add_filter( 'learn_press_profile_courses_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_quizzes_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_orders_tab_title', 'thim_tab_profile_filter_title', 100, 2 );
add_filter( 'learn_press_profile_wishlist_tab_title', 'thim_tab_profile_filter_title', 100, 2 );


/**
 * Enqueue custom script for quiz
 */
if ( ! function_exists( 'thim_enqueue_quiz_scripts' ) ) {
	function thim_enqueue_quiz_scripts() {
		wp_print_scripts( array(
			'learn-press-js',
			'learn-press-timer',
			'lpr-alert-js',
			'single-quiz',
			'framework-bootstrap',
			'thim-custom-script',
			'thim-main'
		) );

		wp_print_styles( array(
			'learn-press',
			'thim-css-style',
			'thim-rtl',
			'thim-awesome',
			'dashicons',
			'thim-style'
		) );

		thim_enqueue_quiz_fonts();
	}
}


add_action( 'thim_quiz_scripts', 'thim_enqueue_quiz_scripts' );

/**
 * Enqueue google font for quiz
 */
if ( ! function_exists( 'thim_enqueue_quiz_fonts' ) ) {
	function thim_enqueue_quiz_fonts() {
		global $wp_styles;
		if ( isset( $wp_styles->queue ) ) {
			foreach ( $wp_styles->queue as $queued_style ) {
				if ( strpos( $queued_style, 'tf-google-webfont' ) !== false ) {
					wp_print_styles( $queued_style );
				}
			}
		}
	}
}

/*
 * thim script for learnpress v1.0
 */
function thim_script_learnpress_v1() {
	wp_dequeue_style( 'course-review' );
	wp_dequeue_script( 'course-review' );
}

add_action( 'wp_enqueue_scripts', 'thim_script_learnpress_v1', 1001 );