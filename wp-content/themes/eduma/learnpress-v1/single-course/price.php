<?php
/**
 * Template for displaying the price of a course
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $course;
$is_required = get_post_meta( $course->id , '_lp_required_enroll', true );

if ( LP()->user->has( 'enrolled-course', $course->id ) && is_singular('lp_course') ) {
	return;
}
?>

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