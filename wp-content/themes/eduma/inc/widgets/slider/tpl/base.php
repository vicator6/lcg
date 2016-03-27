<?php
$settings = array(
	'pagination'       => true,
	'speed'            => $instance['thim_slider_speed'],
	'timeout'          => $instance['thim_slider_timeout'],
	'full_screen'      => $instance['slider_full_screen'],
	'show_icon_scroll' => $instance['show_icon_scroll']
);

if ( empty( $instance['thim_slider_frames'] ) ) {
	return;
}
?>
<div class="ob-slider-base <?php if ( wp_is_mobile() ) {
	echo 'sow-slider-is-mobile';
} ?>">

	<ul class="ob-slider-images" data-settings="<?php echo esc_attr( json_encode( $settings ) ) ?>">
		<?php
		foreach ( $instance['thim_slider_frames'] as $frame ) {
			if ( empty( $frame['thim_slider_background_image'] ) ) {
				$background_image = false;
			} else {
				$background_image     = wp_get_attachment_image_src( $frame['thim_slider_background_image'], 'full' );
				$background_image_url = 'background-image: url(' . esc_url( $background_image['0'] ) . ');';
			}
			?>
			<li class="ob-slider-image" <?php if ( $instance['slider_full_screen'] == '1' ) {
				echo 'style="' . $background_image_url . '"';
			} ?>>

				<span class="overlay_images"<?php if ( $frame['color_overlay'] ) {
					echo ' style="background-color:' . $frame['color_overlay'] . '"';
				} ?>></span>
				<?php
				if ( $instance['slider_full_screen'] == '0' ) {
					echo wp_get_attachment_image( $frame['thim_slider_background_image'], 'full' );
				}
				$style_des = $style_heading = $style_opt = $style_border = '';
				if ( !empty( $frame['content']['thim_slider_title'] ) ) {
					?>
					<div class="ob-slider-image-container <?php echo 'slider-' . esc_attr( $frame['content']['thim_slider_align'] ) ?>">
						<div class="wrapper-container">
							<div class="container">
								<?php
								if ( $frame['content']['thim_color_des'] <> '' ) {
									$style_des = 'style="color:' . $frame['content']['thim_color_des'] . '"';
								}
								$style_opt .= ( $frame['content']['thim_color_title'] != '' ) ? 'color: ' . $frame['content']['thim_color_title'] . ';' : '';
								$style_opt .= ( $frame['content']['size'] != '0' ) ? 'font-size: ' . $frame['content']['size'] . 'px;line-height:' . $frame['content']['size'] . 'px;' : '';
								$style_opt .= ( $frame['content']['custom_font_weight'] != '' ) ? 'font-weight: ' . $frame['content']['custom_font_weight'] : '';

								if ( $style_opt <> '' ) {
									$style_heading = 'style="' . $style_opt . '"';
								}
								if ( !empty( $frame['content']['thim_slider_icon'] ) ) {
									$icon_image = wp_get_attachment_image_src( $frame['content']['thim_slider_icon'], 'full' );
									echo '<img src="' . esc_url( $icon_image['0'] ) . '" alt="' . $frame['content']['thim_slider_title'] . '" class="icon-slider-top">';
								}
								if ( !empty( $frame['content']['thim_slider_title'] ) ) {
									?>
									<h2 class="slider-title" <?php echo ent2ncr( $style_heading ); ?>><?php echo ent2ncr( $frame['content']['thim_slider_title'] ); ?>
										<?php if ( !empty( $frame['content']['line-bottom'] ) ) {
											echo '<span class="line-bottom"></span>';
										} ?>
									</h2>
								<?php
								}
								if ( !empty( $frame['content']['thim_slider_description'] ) ) {
									echo '<div class="slider-desc"' . $style_des . '>';
									echo ent2ncr($frame['content']['thim_slider_description']);
									echo '</div>';
								}
								?>
							</div>
						</div>
					</div>
					<?php //echo ent2ncr( $button_after ); ?>
				<?php
				}
				?>
			</li>
		<?php
		}
		?>
	</ul>
	<?php
	if ( $instance['show_icon_scroll'] == 'show' ) { ?>
		<div class="local-scroll">
			<a class="scroll-down" target="_self" title="button" href="#<?php echo ent2ncr( $instance['button_id'] ) ?>">
				<?php echo '<span>'.$instance['text_before_btn'].'</span>'; ?>
				<i class="fa fa-angle-double-down"></i>
			</a>
		</div>
	<?php }
	?>

	<?php if ( count( $instance['thim_slider_frames'] ) > 1 ) {
		?>
		<div class="ob-slide-nav ob-slide-nav-next">
			<a href="#" data-goto="next" data-action="next">
				<i class="fa fa fa-angle-right fa-2x"></i>
			</a>
		</div>

		<div class="ob-slide-nav ob-slide-nav-prev">
			<a href="#" data-goto="previous" data-action="prev">
				<i class="fa fa fa-angle-left fa-2x"></i>
			</a>
		</div>
	<?php } ?>
</div>

