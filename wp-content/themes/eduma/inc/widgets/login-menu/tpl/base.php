<div class="thim-link-login">
	<?php if ( is_user_logged_in() ): ?>
		<a class="profile" href="<?php echo esc_url( apply_filters( 'learn_press_instructor_profile_link', '#', get_current_user_id(), '' ) ); ?>"><?php esc_html_e( 'Profile', 'eduma' ); ?></a>
		<a class="logout" href="<?php echo esc_url( wp_logout_url( thim_get_login_page_url() ) ); ?>"><?php echo esc_html( $instance['text_logout'] ); ?></a>
	<?php else : ?>
		<a class="register" href="<?php echo esc_url( wp_registration_url() ); ?>"><?php echo esc_html( $instance['text_register'] ); ?></a>
		<a class="login" href="<?php echo esc_url( thim_get_login_page_url() ); ?>"><?php echo esc_html( $instance['text_login'] ); ?></a>
	<?php endif; ?>
</div>

