<?php
/**
 * Template for displaying the enroll button
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $course;

if ( !$course->is_required_enroll() ){
	return;
}

$course_status = LP()->user->get_course_status($course->ID );
$user          = learn_press_get_current_user();
// only show enroll button if user had not enrolled
$purchase_button_text = apply_filters( 'learn_press_purchase_button_text', esc_html__( 'Take This Course', 'eduma' ) );
$enroll_button_text   = apply_filters( 'learn_press_enroll_button_loading_text', esc_html__( 'Enrolled', 'eduma' ) );
?>
<?php if ( $user->has( 'enrolled-course', $course->id ) ): ?>

	<?php learn_press_display_message( esc_html__( 'You have already enrolled this course', 'eduma' ) ); ?>

<?php else: ?>
	<?php if ( $user->has( 'purchased-course', $course->id ) ) : ?>

		<?php if ( $user->can( 'enroll-course', $course->id ) ) : ?>

			<form name="enroll-course" class="enroll-course" method="post" enctype="multipart/form-data">
				<?php do_action( 'learn_press_before_enroll_button' ); ?>

				<input type="hidden" name="lp-ajax" value="enroll-course" />
				<input type="hidden" name="enroll-course" value="<?php echo $course->id; ?>" />
				<input type="hidden" name="_wp_http_referer" value="<?php echo get_the_permalink(); ?>" />
				<button class="button enroll-button"><?php echo $enroll_button_text; ?></button>

				<?php do_action( 'learn_press_after_enroll_button' ); ?>
			</form>

		<?php else: ?>

			<?php learn_press_display_message( apply_filters( 'learn_press_user_purchased_course_message', esc_html__( 'You have already purchased this course. Please wait for approve', 'eduma' ), $course, $user ) ); ?>

		<?php endif; ?>

	<?php elseif( $user->can( 'purchase-course', $course->id ) ) : ?>

		<?php if ( LP()->cart->has_item( $course->id ) ) { ?>
			<?php learn_press_display_message( sprintf( esc_html__( 'This course is already added to your cart <a href="%s" class="button view-cart-button">%s</a>', 'eduma' ), learn_press_get_page_link( 'cart' ), esc_html__( 'View Cart', 'eduma' ) ) ); ?>
		<?php } else { ?>

			<form name="purchase-course" class="purchase-course" method="post" enctype="multipart/form-data">
				<?php do_action( 'learn_press_before_purchase_button' ); ?>
				<input type="hidden" name="_wp_http_referer" value="<?php echo get_the_permalink(); ?>" />
				<input type="hidden" name="add-course-to-cart" value="<?php echo $course->id; ?>" />
				<button class="button purchase-button thim-enroll-course-button"><?php echo $purchase_button_text; ?></button>
				<a class="button view-cart-button" href="<?php echo learn_press_get_page_link( 'cart' ); ?>"><?php esc_html_e( 'View cart', 'eduma' ); ?></a>
				<?php do_action( 'learn_press_after_purchase_button' ); ?>
			</form>

		<?php } ?>

	<?php else: ?>

		<?php learn_press_display_message( apply_filters( 'learn_press_user_can_not_purchase_course_message', esc_html__( 'Sorry, you can not purchase this course', 'eduma' ), $course, $user ) ); ?>

	<?php endif; ?>

<?php endif; ?>

