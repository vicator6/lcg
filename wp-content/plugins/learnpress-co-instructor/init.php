<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'pre_get_posts', 'learn_press_pre_get_co_items' );
function learn_press_pre_get_co_items( $query ) {
	$current_user = wp_get_current_user();
	global $post_type;
	global $pagenow;
	if ( in_array( 'lpr_teacher', $current_user->roles ) && is_admin() && $pagenow == 'edit.php' ) {
		if ( in_array( $post_type, array( 'lpr_course', 'lpr_lesson', 'lpr_quiz' ) ) ) {
			$courses = learn_press_get_available_courses();
			if ( $post_type == 'lpr_course' ) {
				if ( count( $courses ) == 0 ) {
					$query->set( 'post_type', 'lpr_empty' );
				} else {
					$query->set( 'post_type', $post_type );
					$query->set( 'post__in', $courses );
				}
				add_filter( 'views_edit-lpr_course', 'learn_press_restrict_co_items', 20 );
				return;
			}
			if ( $post_type == 'lpr_lesson' ) {
				$lessons = learn_press_get_available_lessons( $courses );
				if ( count( $lessons ) == 0 ) {
					$query->set( 'post_type', 'lpr_empty' );
				} else {
					$query->set( 'post_type', $post_type );
					$query->set( 'post__in', $lessons );
				}
				add_filter( 'views_edit-lpr_lesson', 'learn_press_restrict_co_items', 20 );
				return;
			}
			if ( $post_type == 'lpr_quiz' ) {
				$quizzes = learn_press_get_available_quizzes( $courses );
				if ( count( $quizzes ) == 0 ) {
					$query->set( 'post_type', 'lpr_empty' );
				} else {
					$query->set( 'post_type', $post_type );
					$query->set( 'post__in', $quizzes );
				}
				add_filter( 'views_edit-lpr_quiz', 'learn_press_restrict_co_items', 20 );
				return;
			}
		}
	}
}

/**
 * @param $views
 *
 * @return mixed
 */
function learn_press_restrict_co_items( $views ) {

	$post_type = get_query_var( 'post_type' );
	$author    = get_current_user_id();

	$new_views = array(
		'all'        => __( 'All', 'learnpress_co_instructor' ),
		'mine'       => __( 'Mine', 'learnpress_co_instructor' ),
		'publish'    => __( 'Published', 'learnpress_co_instructor' ),
		'private'    => __( 'Private', 'learnpress_co_instructor' ),
		'pending'    => __( 'Pending Review', 'learnpress_co_instructor' ),
		'future'     => __( 'Scheduled', 'learnpress_co_instructor' ),
		'draft'      => __( 'Draft', 'learnpress_co_instructor' ),
		'trash'      => __( 'Trash', 'learnpress_co_instructor' ),
		'co_teacher' => __( 'Co-instructor', 'learnpress_co_instructor' )
	);

	$url = 'edit.php';

	foreach ( $new_views as $view => $name ) {

		$query = array(
			'post_type' => $post_type
		);

		if ( $view == 'all' ) {
			$query['all_posts'] = 1;
			$class              = ( get_query_var( 'all_posts' ) == 1 || ( get_query_var( 'post_status' ) == '' && get_query_var( 'author' ) == '' ) ) ? ' class="current"' : '';

		} elseif ( $view == 'mine' ) {
			$query['author'] = $author;
			$class           = ( get_query_var( 'author' ) == $author ) ? ' class="current"' : '';
		} elseif ( $view == 'co_teacher' ) {
			$query['author'] = - $author;
			$class           = ( get_query_var( 'author' ) == - $author ) ? ' class="current"' : '';

		} else {
			$query['post_status'] = $view;
			$class                = ( get_query_var( 'post_status' ) == $view ) ? ' class="current"' : '';
		}

		$result = new WP_Query( $query );

		if ( $result->found_posts > 0 ) {

			$views[$view] = sprintf(
				'<a href="%s"' . $class . '>' . __( $name, 'learnpress_co_instructor' ) . ' <span class="count">(%d)</span></a>',
				esc_url( add_query_arg( $query, $url ) ),
				$result->found_posts
			);

		} else {

			unset( $views[$view] );

		}

	}

	return $views;
}


/**
 * @return array
 */
function learn_press_get_available_courses() {
	if ( !current_user_can( 'lpr_teacher' ) ) {
		return array();
	}
	global $wpdb;
	$courses = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT DISTINCT 	p.ID
						FROM				$wpdb->posts AS p
						INNER JOIN 			$wpdb->postmeta AS pm ON p.ID = pm.post_id
						WHERE  				( p.post_author = %d AND p.post_type = %s )
						OR 					( pm.meta_key = %s and pm.meta_value= %d and p.post_type = %s)",
			get_current_user_id(),
			'lpr_course',
			'_lpr_co_teacher',
			get_current_user_id(),
			'lpr_course'
		)
	);
	return $courses;
}

add_filter( 'learn_press_valid_courses', 'learn_press_get_available_courses' );

/**
 * @param $courses
 *
 * @return array
 */
function learn_press_get_available_lessons( $courses ) {
	global $wpdb;
	$lessons = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT         ID
			FROM            $wpdb->posts
			WHERE           post_type = %s
			AND				post_author = %d",
			'lpr_lesson',
			get_current_user_id()
		)

	);

	if ( $courses ) foreach ( $courses as $course ) {
		$temp    = learn_press_get_lessons( $course );
		$lessons = array_unique( array_merge( $lessons, $temp ) );
	}

	return $lessons;
}

add_filter(
	'learn_press_valid_lessons',
	function () {
		$courses = learn_press_get_available_courses();
		return learn_press_get_available_lessons( $courses );
	}
);

/**
 * @param $courses
 *
 * @return array
 */
function learn_press_get_available_quizzes( $courses ) {
	global $wpdb;
	$quizzes = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT         ID
			FROM            $wpdb->posts
			WHERE           post_type = %s
			AND				post_author = %d",
			'lpr_quiz',
			get_current_user_id()
		)
	);
	if ( $courses ) foreach ( $courses as $course ) {
		$temp    = learn_press_get_quizzes( $course );
		$quizzes = array_unique( array_merge( $quizzes, $temp ) );
	}
	return $quizzes;
}

add_filter(
	'learn_press_valid_quizzes',
	function () {
		$courses = learn_press_get_available_courses();
		return learn_press_get_available_quizzes( $courses );
	}
);

function learn_press_add_co_instructor_meta_box( $meta_box ) {
	$class       = '';
	$post_author = '';
	if ( isset( $_GET['post'] ) && isset( get_post( $_GET['post'] )->post_author ) ) {
		$post_author = get_post( $_GET['post'] )->post_author;
		if ( $post_author != get_current_user_id() && !current_user_can( 'manage_options' ) ) {
			$class = 'hidden';
		}
	}
	$include       = array();
	$users_by_role = get_users( array( 'role' => 'administrator' ) );
	if ( $users_by_role ) {
		foreach ( $users_by_role as $user ) {
			if ( $user->ID != $post_author ) {
				$include[] = $user->ID;
			}
		}
	}
	$users_by_role = get_users( array( 'role' => 'lpr_teacher' ) );
	if ( $users_by_role ) {
		foreach ( $users_by_role as $user ) {
			if ( $user->ID != $post_author ) {
				$include[] = $user->ID;
			}
		}
	}

	$meta_box['fields'][] = array(
		'name'        => __( 'Co-Instructors', 'learnpress_co_instructor' ),
		'id'          => "_lpr_co_teacher",
		'desc'        => __( 'Colleagues\'ll work with you', 'learnpress_co_instructor' ),
		'class'       => $class,
		'type'        => 'teacher',
		'multiple'    => true,
		'field_type'  => 'select_advanced',
		'placeholder' => __( 'Instructor username', 'learnpress_co_instructor' ),
		'query_args'  => array(
			'include' => $include
		)
	);
	return $meta_box;
}

add_filter( 'learn_press_course_settings_meta_box_args', 'learn_press_add_co_instructor_meta_box' );

/**
 * Set edit other course items capability for instructor
 */
function learn_press_set_cap_co_instructor() {
	$teacher = get_role( 'lpr_teacher' );
	$teacher->add_cap( 'edit_others_lpr_lessons' );
	$teacher->add_cap( 'edit_others_lpr_courses' );
}

/**
 * Remove edit other course items capability from instructor
 */
function learn_press_remove_cap_co_instructor() {
	$teacher = get_role( 'lpr_teacher' );
	$teacher->remove_cap( 'edit_others_lpr_lessons' );
	$teacher->remove_cap( 'edit_others_lpr_courses' );
}

if ( function_exists( 'learn_press_pre_get_co_items' ) ) {
	add_action( 'init', 'learn_press_set_cap_co_instructor', 50 );
} else {
	add_action( 'init', 'learn_press_remove_cap_co_instructor' );
}

