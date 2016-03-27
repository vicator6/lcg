<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Check to known bbpress is active
 *
 * @return bool
 */
if( ! function_exists( 'learn_press_bbpress_is_active' ) ) {
    function learn_press_bbpress_is_active()
    {
        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        return class_exists('bbPress') && is_plugin_active('bbpress/bbpress.php');
    }
}

if ( learn_press_bbpress_is_active() ) {
	/**
	 * Add custom bbPress forum metabox in course editing
	 *
	 * @param $meta_boxes
	 *
	 * @return mixed
	 */
	function learn_press_add_bbpress_forum_metabox( $meta_boxes ) {
		$meta_boxes['fields'][] = array(
			'name'    => __( 'Course Forum', 'learnpress_bbpress' ),
			'id'      => "_lpr_course_forum",
			'type'    => 'radio',
			'desc'    => __( 'A bbPress forum will be created and connected to this course automatically. If option no discussion be chosen, all data of forum will be deleted permanently!', 'learnpress_bbpress' ),
			'options' => array(
				'yes' => __( 'Connect to forum', 'learnpress_bbpress' ),
				'no'  => __( 'No discussion', 'learnpress_bbpress' ),
			)
		);
		return $meta_boxes;
	}

	add_filter( 'learn_press_course_settings_meta_box_args', 'learn_press_add_bbpress_forum_metabox' );

	/**
	 * Process forum from course settings
	 *
	 * @param $course_id
	 */
	function learn_press_process_course_forum( $course_id ) {
		$course = get_post( $course_id );
		if ( $course->post_status != 'publish' ) {
			return;
		}
		if ( learn_press_is_connect_forum( $course_id ) ) {
			if ( !learn_press_is_exist_forum( $course_id ) ) {
				$new_forum = array(
					'post_title'   => $course->post_title,
					'post_content' => __( 'Forum of course "' . $course->post_title . '"', 'learnpress_bbpress' ),
					'post_author'  => $course->post_author,
				);
				$forum_id  = bbp_insert_forum( $new_forum, array() );
				update_post_meta( $forum_id, '_lpr_forum_course_id', $course_id );
				update_post_meta( $course_id, '_lpr_course_forum_id', $forum_id );
			}
		} else {
			$forum_id = learn_press_get_forum_id( $course_id );
			if ( !$forum_id ) {
				return;
			}
			wp_delete_post( $forum_id );
			update_post_meta( $course_id, '_lpr_course_forum_id', '' );
		}

	}

	add_action( 'save_post', 'learn_press_process_course_forum', 100, 1 );

	/**
	 * Remove forum after delete course permanently
	 *
	 * @param $course_id
	 */
	function learn_press_remove_forum( $course_id ) {
		$forum_id = learn_press_get_forum_id( $course_id );
		if ( !$forum_id ) {
			return;
		}
		wp_delete_post( $forum_id );
	}

	add_action( 'before_delete_post', 'learn_press_remove_forum' );

	/**
	 * Add link to forum in course display
	 */
	function learn_press_forum_link() {
		do_action( 'learn_press_before_course_forum' );
		if ( learn_press_is_connect_forum( get_the_ID() ) ) {
			printf(
				'<div class="forum-link">
					<span><a href="%s">%s</a></span>
				</div>',
				learn_press_get_forum_link( get_the_ID() ),
				apply_filters( 'learn_press_forum_link_text', __( 'Forum', 'learnpress_bbpress' ) )
			);
		}
		do_action( 'learn_press_after_course_forum' );
	}

	add_action( 'learn_press_course_landing_content', 'learn_press_forum_link', 80 );
	add_action( 'learn_press_course_learning_content', 'learn_press_forum_link', 30 );

	/**
	 * Get forum link from respective course
	 *
	 * @param $course_id
	 *
	 * @return string
	 */
	function learn_press_get_forum_link( $course_id ) {
		$forum_id = learn_press_get_forum_id( $course_id );
		return esc_url( bbp_get_forum_permalink( $forum_id ) );
	}

	/**
	 * Process limit access forum
	 */
	function learn_press_limit_access_course_forum() {
		global $post;
		if ( !learn_press_is_access_forum( $post->ID, $post->post_type ) ) {
			ob_start();
		}
	}

	add_action( 'bbp_template_before_single_topic', 'learn_press_limit_access_course_forum' );
	add_action( 'bbp_template_before_single_forum', 'learn_press_limit_access_course_forum' );

	/**
	 * Restrict forum content
	 */
	function learn_press_restrict_forum_content() {
		global $post;
		$course_id = learn_press_get_forum_course_id( $post->ID );
		if ( !$course_id ) {
			return;
		}
		if ( !learn_press_is_access_forum( $post->ID, $post->post_type ) ) {
			ob_end_clean();
			wp_enqueue_style( 'lp-dashicons', get_site_url() . '/wp-includes/css/dashicons.css' );
			echo '<p>' . __( 'You have to enroll the respective course!', 'learnpress_bbpress' ) . '<p>';
			echo '<p>' . __( 'Go back to', 'learnpress_bbpress' ) . '<a href="' . get_permalink( $course_id ) . '"> ' . __( 'the course', 'learnpress_bbpress' ) . '!</a></p>';
		}
	}

	add_action( 'bbp_template_after_single_topic', 'learn_press_restrict_forum_content' );
	add_action( 'bbp_template_after_single_forum', 'learn_press_restrict_forum_content' );

	/**
	 * Process ability to access forum
	 *
	 * @param $id
	 * @param $type
	 *
	 * @return bool
	 */
	function learn_press_is_access_forum( $id, $type ) {
		$user_id = get_current_user_id();
		// Case: invalid user or post
		if ( !$user_id || !$id ) {
			return false;
		}
		$course_id = 0;
		if ( $type == 'forum' ) {
			$course_id = learn_press_get_forum_course_id( $id );
		} elseif ( $type == 'topic' ) {
			$forum_id = get_post_meta( $id, '_bbp_forum_id', true );
			if ( !empty( $forum_id ) ) {
				$course_id = learn_press_get_forum_course_id( $forum_id );
			}
		}
		// Case: a normal forum which has no connecting with any courses
		if ( !$course_id ) {
			return true;
		}

		$is_required = get_post_meta( $course_id, '_lpr_course_enrolled_require', true );
		// Case: no required course
		if ( $is_required == 'no' ) {
			return true;
		}

		// Case: user is the course author
		$object = get_post( $course_id );
		if ( $user_id == $object->post_author ) {
			return true;
		}
		$object = get_user_meta( $user_id, '_lpr_user_course', true );
		// Case: users haven't enrolled any one
		if ( !$object ) {
			return false;
		}
		// Case: users enrolled this course
		if ( in_array( $course_id, $object ) ) {
			return true;
		}
		// Case: user is Admin, Moderator or Key Master

		if ( current_user_can( 'manage_options' ) || current_user_can( 'bbp_moderator' ) || current_user_can( 'bbp_keymaster' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Process ability a course can connect with a forum
	 *
	 * @param $course_id
	 *
	 * @return bool
	 */
	function learn_press_is_connect_forum( $course_id ) {
		$check = get_post_meta( $course_id, '_lpr_course_forum', true );
		if ( !empty ( $check ) && $check == 'yes' ) {
			return true;
		}
		return false;
	}

	/**
	 * Process ability a course connected to a forum
	 *
	 * @param $course_id
	 *
	 * @return bool
	 */
	function learn_press_is_exist_forum( $course_id ) {
		$check = get_post_meta( $course_id, '_lpr_course_forum_id', true );
		if ( !empty ( $check ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Ger forum id from respective course
	 *
	 * @param $course_id
	 *
	 * @return int|mixed
	 */
	function learn_press_get_forum_id( $course_id ) {
		if ( learn_press_is_exist_forum( $course_id ) ) {
			return get_post_meta( $course_id, '_lpr_course_forum_id', true );
		}
		return 0;
	}

	/**
	 * Get course id from respective forum
	 *
	 * @param $forum_id
	 *
	 * @return int|mixed
	 */
	function learn_press_get_forum_course_id( $forum_id ) {
		$course_id = get_post_meta( $forum_id, '_lpr_forum_course_id', true );
		if ( !empty ( $course_id ) ) {
			return $course_id;
		}
		return 0;
	}	
}