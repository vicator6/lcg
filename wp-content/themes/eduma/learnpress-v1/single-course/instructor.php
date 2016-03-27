<?php
/**
 * Template for displaying the instructor of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $course;

?>

<div class="course-author" itemscope itemtype="http://schema.org/Person">
	<?php echo get_avatar( get_the_author_meta( 'ID' ), 44 ); ?>
	<div class="author-contain">
		<label itemprop="jobTitle"><?php esc_html_e( 'Teacher', 'eduma' ); ?></label>

		<div class="value" itemprop="name">
			<a itemprop="url" href="<?php echo esc_url( apply_filters( 'learn_press_instructor_profile_link', '#', $user_id = null, get_the_ID() ) ); ?>">
				<?php echo get_the_author(); ?>
			</a>
		</div>
	</div>
</div>