<article id="tp_event-<?php the_ID(); ?>" <?php post_class( 'tp_single_event' ); ?>>

	<?php
	/**
	 * tp_event_before_loop_room_summary hook
	 *
	 * @hooked tp_event_show_room_sale_flash - 10
	 * @hooked tp_event_show_room_images - 20
	 */
	do_action( 'tp_event_before_single_event' );
	?>

	<div class="summary entry-summary">

		<?php
		/**
		 * tp_event_single_event_title hook
		 */
		do_action( 'tp_event_single_event_title' );

		/**
		 * tp_event_single_event_thumbnail hook
		 */
		echo '<div class="tp-event-top">';
		do_action( 'tp_event_single_event_thumbnail' );

		/**
		 * tp_event_loop_event_countdown
		 */
		do_action( 'tp_event_loop_event_countdown' );
		echo '</div>';
		?>
		<div class="tp-event-content">
			<?php
			/**
			 * tp_event_single_event_content hook
			 */
			do_action( 'tp_event_single_event_content' );

			$time_from   = get_post_meta( get_the_ID(), 'tp_event_date_start', true ) ? strtotime( get_post_meta( get_the_ID(), 'tp_event_date_start', true ) ) : time();
			$time_finish = get_post_meta( get_the_ID(), 'tp_event_date_end', true ) ? strtotime( get_post_meta( get_the_ID(), 'tp_event_date_end', true ) ) : time();
			$time_start  = get_post_meta( get_the_ID(), 'tp_event_time_start', true ) ? get_post_meta( get_the_ID(), 'tp_event_time_start', true ) : '8:00 AM';
			$time_end    = get_post_meta( get_the_ID(), 'tp_event_time_end', true ) ? get_post_meta( get_the_ID(), 'tp_event_time_end', true ) : '5:00 PM';
			$location    = get_post_meta( get_the_ID(), 'tp_event_location', true ) ? get_post_meta( get_the_ID(), 'tp_event_location', true ) : 'Birmingham, UK';
			?>
			<div class="tp-event-info">
				<div class="tp-info-box">
					<p class="heading">
						<i class="thim-color fa fa-clock-o"></i><?php esc_html_e( 'Start Time', 'eduma' ); ?>
					</p>

					<p><?php echo esc_html( $time_start ); ?></p>

					<p><?php echo date_i18n( 'l, F j, Y', $time_from ); ?></p>
				</div>
				<div class="tp-info-box">
					<p class="heading">
						<i class="thim-color fa fa-flag"></i><?php esc_html_e( 'Finish Time', 'eduma' ); ?>
					</p>

					<p><?php echo esc_html( $time_end ); ?></p>

					<p><?php echo date_i18n( 'l, F j, Y', $time_finish ); ?></p>
				</div>
				<div class="tp-info-box">
					<p class="heading">
						<i class="thim-color fa fa-map-marker"></i><?php esc_html_e( 'Address', 'eduma' ); ?>
					</p>

					<p><?php echo esc_html( $location ); ?></p>
				</div>
			</div>
		</div>

		<?php $members = get_post_meta( get_the_ID(), 'thim_event_members', true ); ?>
		<?php if ( $members ) : ?>
			<div class="tp-event-organizers">
				<h3 class="title"><?php esc_html_e( 'Who come with us?', 'eduma' ); ?></h3>
				<div class="thim-carousel-container">
					<div class="thim-carousel-wrapper" data-visible="4" data-navigation="1">
						<?php foreach ( $members as $member ) : ?>
							<div class="item">
								<div class="thumbnail"><?php echo thim_get_feature_image( get_post_thumbnail_id( $member ), 'full', 110, 110 ); ?></div>
								<p class="name"><?php echo get_the_title( $member ); ?></p>
	
								<p class="regency"><?php echo get_post_meta( $member, 'regency', true ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		
		<div class="tp-event-single-share">
		<?php do_action( 'thim_social_share' ); ?>
		</div>

	</div><!-- .summary -->

	<?php
	/**
	 * hotel_booking_after_loop_room hook
	 *
	 * @hooked hotel_booking_output_room_data_tabs - 10
	 * @hooked hotel_booking_upsell_display - 15
	 * @hooked hotel_booking_output_related_products - 20
	 */
	do_action( 'tp_event_after_single_event' );
	?>

</article><!-- #product-<?php the_ID(); ?> -->