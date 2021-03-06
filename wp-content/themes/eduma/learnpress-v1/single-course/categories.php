<?php
/**
 * Template for displaying the categories of a course
 */

do_action( 'learn_press_before_course_categories' );

$term_list = get_the_term_list( get_the_ID(), 'course_category', '', ', ', '' );
?>

	<div class="course-categories">
		<label><?php esc_html_e( 'Categories', 'eduma' ); ?></label>
		<div class="value">
			<?php
			if ( $term_list ) {
				printf(
					'<span class="cat-links">%s</span>',
					$term_list
				);
			}
			?>
		</div>
	</div>
<?php
do_action( 'learn_press_after_course_categories' );
