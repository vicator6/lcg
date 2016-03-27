<?php $theme_options_data = thim_options_data(); ?>
<?php 
	$class_footer = is_active_sidebar( 'footer_bottom' ) ? 'site-footer has-footer-bottom' : 'site-footer';
?>
<footer id="colophon" class="<?php echo esc_attr($class_footer); ?>">
	<?php if ( is_active_sidebar( 'footer' ) ) : ?>
		<div class="footer">
			<div class="container">
				<div class="row">
					<?php dynamic_sidebar( 'footer' ); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<!--==============================powered=====================================-->
	<?php if ( ( isset( $theme_options_data['thim_copyright_text'] ) && !empty( $theme_options_data['thim_copyright_text'] ) ) || is_active_sidebar( 'copyright' ) ) { ?>
		<div class="copyright-area">
			<div class="container">
				<div class="copyright-content">
					<div class="row">
						<?php
						if ( isset( $theme_options_data['thim_copyright_text'] ) ) {
							echo '<div class="col-sm-6"><p class="text-copyright">' . $theme_options_data['thim_copyright_text'] . '</p></div>';
						}
						if ( is_active_sidebar( 'copyright' ) ) : ?>
							<div class="col-sm-6 text-right">
								<?php dynamic_sidebar( 'copyright' ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	
	
</footer><!-- #colophon -->
</div><!--end main-content-->

<!-- Footer Bottom -->
<?php if(is_active_sidebar( 'footer_bottom' ) ) {?>
	<div class="footer-bottom">
		<div class="container">
			<?php dynamic_sidebar( 'footer_bottom' ); ?>
		</div>
	</div>
<?php } ?>

</div><!-- end wrapper-container and content-pusher-->

<?php
if ( isset( $theme_options_data['thim_show_to_top'] ) && $theme_options_data['thim_show_to_top'] == 1 ) { ?>
	<a href="#" id="back-to-top">
		<i class="fa fa-chevron-up"></i>
	</a>
<?php
}
?>



</div>

<?php wp_footer(); ?>
</body >
</html >