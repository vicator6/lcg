<?php
/**
 * User Courses enrolled
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
$heading = apply_filters( 'learn_press_profile_tab_courses_learning_heading', false );
?>

<?php if ( $heading ): ?>

	<h4 class="profile-courses-heading"><?php echo $heading; ?></h4>

<?php endif; ?>

<?php if ( $courses ) : ?>

	<div class="thim-carousel-wrapper thim-course-carousel thim-course-grid" data-visible="3" data-pagination="0" data-navigation="1">

		<?php foreach( $courses as $post ){ setup_postdata( $post );?>

			<?php learn_press_get_template( 'profile/tabs/courses/loop.php', array( 'subtab' => 'learning' ) ); ?>

		<?php } ?>
	</div>

<?php else: ?>

	<?php learn_press_display_message( __( 'You haven\'t got any learning courses!', 'learnpress' ) ); ?>

<?php endif ?>

<?php wp_reset_postdata(); // do not forget to call this function here! ?>
