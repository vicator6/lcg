<?php
/**
 * The Template for displaying all archive products.
 *
 * Override this template by copying it to yourtheme/tp-event/templates/archive-event.php
 *
 * @author        ThimPress
 * @package       tp-event/template
 * @version       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//get_header();
?>

<?php
/**
 * tp_event_before_main_content hook
 *
 * @hooked tp_event_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked tp_event_breadcrumb - 20
 */
do_action( 'tp_event_before_main_content' );
?>

<?php
/**
 * tp_event_archive_description hook
 *
 * @hooked tp_event_taxonomy_archive_description - 10
 * @hooked tp_event_room_archive_description - 10
 */
do_action( 'tp_event_archive_description' );
?>

<?php if ( have_posts() ) : ?>

	<?php
	/**
	 * tp_event_before_event_loop hook
	 *
	 * @hooked tp_event_result_count - 20
	 * @hooked tp_event_catalog_ordering - 30
	 */
	do_action( 'tp_event_before_event_loop' );

	$happening = $expired = $upcoming = '';
	?>

	<?php while ( have_posts() ) : the_post(); ?>
		<?php
		$class     = 'item-event';
		$time_from = tp_event_start( 'g:i A' );
		$time_end  = tp_event_end( 'g:i A' );

		$location   = tp_event_location();
		$date_show  = tp_event_get_time( 'd' );
		$month_show = tp_event_get_time( 'F' );
		ob_start();
		?>

		<div <?php post_class( $class ); ?>>
			<div class="time-from">
				<div class="date">
					<?php echo esc_html( $date_show ); ?>
				</div>
				<div class="month">
					<?php echo esc_html( $month_show ); ?>
				</div>
			</div>
			<?php
			echo '<div class="image">';
			echo thim_get_feature_image( get_post_thumbnail_id(), 'full', 450, 233 );
			echo '</div>';
			?>
			<div class="event-wrapper">
				<h5 class="title">
					<a href="<?php echo esc_url( get_permalink( get_the_ID() ) ); ?>"> <?php echo get_the_title(); ?></a>
				</h5>

				<div class="meta">
					<div class="time">
						<i class="fa fa-clock-o"></i>
						<?php echo esc_html( $time_from ) . ' - ' . esc_html( $time_end ); ?>
					</div>
					<div class="location">
						<i class="fa fa-map-marker"></i>
						<?php echo ent2ncr( $location ); ?>
					</div>
				</div>
				<div class="description">
					<?php echo thim_excerpt( 25 ); ?>
				</div>
			</div>

		</div>

		<?php
		switch ( get_post_status() ) {
			case 'tp-event-happenning':
				$happening .= ob_get_contents();
				ob_end_clean();
				break;
			case 'tp-event-expired':
				$expired .= ob_get_contents();
				ob_end_clean();
				break;
			case 'tp-event-upcoming':
				$upcoming .= ob_get_contents();
				ob_end_clean();
				break;
			default :
				$upcoming .= ob_get_contents();
				ob_end_clean();
				break;
		}

		?>
	<?php endwhile; // end of the loop.
	wp_reset_postdata();
	?>

	<div class="list-tab-event">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-happening" data-toggle="tab"><?php esc_html_e( 'Happening', 'eduma' ); ?></a></li>
			<li><a href="#tab-upcoming" data-toggle="tab"><?php esc_html_e( 'Upcoming', 'eduma' ); ?></a></li>
			<li><a href="#tab-expired" data-toggle="tab"><?php esc_html_e( 'Expired', 'eduma' ); ?></a></li>
		</ul>
		<div class="tab-content thim-list-event">
			<div role="tabpanel" class="tab-pane fade in active" id="tab-happening">
				<?php
				if ( $happening != '' ) {
					echo ent2ncr( $happening );
				}
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="tab-upcoming">
				<?php
				if ( $upcoming != '' ) {
					echo ent2ncr( $upcoming );
				}
				?>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="tab-expired">
				<?php
				if ( $expired != '' ) {
					echo ent2ncr( $expired );
				}
				?>
			</div>
		</div>
	</div>

	<?php
	/**
	 * tp_event_after_event_loop hook
	 *
	 * @hooked tp_event_pagination - 10
	 */
	do_action( 'tp_event_after_event_loop' );
	?>

<?php endif; ?>

<?php
/**
 * tp_event_after_main_content hook
 *
 * @hooked tp_event_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'tp_event_after_main_content' );
?>

<?php
/**
 * tp_event_sidebar hook
 *
 * @hooked tp_event_get_sidebar - 10
 */
do_action( 'tp_event_sidebar' );
?>

<?php //get_footer(); ?>