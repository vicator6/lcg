<?php
/**
 * The template for display the content of single course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $course;

$is_enrolled = LP()->user->has( 'enrolled-course', $course->id ) ;
$is_required = get_post_meta( get_the_ID(), '_lp_required_enroll', true );

do_action( 'learn_press_before_single_course' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/CreativeWork">

	<?php
	the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' );
	?>

	<div class="course-meta">
		<?php learn_press_course_instructor(); ?>
		<?php learn_press_course_categories(); ?>
		<?php if ( $is_enrolled || $is_required == 'no' ) thim_course_forum_link(); ?>
		<?php thim_course_ratings(); ?>
	</div>

	<div class="course-payment">
		<?php
		if ( !$is_enrolled ) {
			learn_press_course_price();
			//learn_press_course_payment_form();
			learn_press_course_enroll_button();
		} else {
			echo '<button class="course-enrolled"><i class="fa fa-check"></i>' . esc_html__( 'Enrolled', 'eduma' ) . '</button>';
		}
		?>
	</div>

	<?php do_action( 'learn_press_before_single_course_summary' ); ?>

	<?php learn_press_get_template( 'single-course/thumbnail.php' ); ?>

	<div class="course-summary">

		<?php if ( $is_enrolled || $is_required == 'no' ) { ?>

			<?php learn_press_get_template( 'single-course/content-learning.php' ); ?>

		<?php } else { ?>

			<?php learn_press_get_template( 'single-course/content-landing.php' ); ?>

		<?php } ?>
	</div>

	<?php do_action( 'learn_press_after_single_course_summary' ); ?>
	
</article><!-- #post-## -->

<?php do_action( 'learn_press_after_single_course' ); ?>
