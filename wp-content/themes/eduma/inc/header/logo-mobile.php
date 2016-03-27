<?php
$theme_options_data = thim_options_data();
if ( wp_is_mobile() && ( isset( $theme_options_data['thim_config_logo_mobile'] ) && $theme_options_data['thim_config_logo_mobile'] == 'custom_logo' ) ) {
	// custom logo mobile
	if ( isset( $theme_options_data['thim_logo_mobile'] ) && $theme_options_data['thim_logo_mobile'] <> '' ) {
		add_action( 'thim_logo', 'thim_logo_mobile', 2 );
		if ( !function_exists( 'thim_logo_mobile' ) ) :
			function thim_logo_mobile() {
				$theme_options_data = thim_options_data();
				if ( isset( $theme_options_data['thim_logo_mobile'] ) && $theme_options_data['thim_logo_mobile'] <> '' ) {
					$thim_logo     = $theme_options_data['thim_logo_mobile'];
					$thim_logo_src = $thim_logo; // For the default value
					if ( is_numeric( $thim_logo ) ) {
						$logo_attachment = wp_get_attachment_image_src( $thim_logo, 'full' );
						$thim_logo_src   = $logo_attachment[0];
					}
					$thim_logo_size = @getimagesize( $thim_logo_src );
					$logo_size      = $thim_logo_size[3];
					$site_title     = esc_attr( get_bloginfo( 'name', 'display' ) );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home" class="no-sticky-logo-mobile"><img src="' . $thim_logo_src . '" alt="' . $site_title . '" ' . $logo_size . ' /></a>';
				}
			}
		endif;
	}
	if ( ( isset( $theme_options_data['thim_logo_mobile'] ) && $theme_options_data['thim_logo_mobile'] <> '' ) || ( isset( $theme_options_data['thim_sticky_logo_mobile'] ) && $theme_options_data['thim_sticky_logo_mobile'] <> '' ) ) {
		add_action( 'thim_sticky_logo', 'thim_sticky_logo_mobile', 2 );
		// get sticky logo
		if ( !function_exists( 'thim_sticky_logo_mobile' ) ) :
			function thim_sticky_logo_mobile() {
				$theme_options_data = thim_options_data();
				if ( isset( $theme_options_data['thim_sticky_logo_mobile'] ) && $theme_options_data['thim_sticky_logo_mobile'] <> '' ) {
					$thim_logo_stick_logo     = $theme_options_data['thim_sticky_logo_mobile'];
					$thim_logo_stick_logo_src = $thim_logo_stick_logo; // For the default value
					if ( is_numeric( $thim_logo_stick_logo ) ) {
						$logo_attachment          = wp_get_attachment_image_src( $thim_logo_stick_logo, 'full' );
						$thim_logo_stick_logo_src = $logo_attachment[0];
					}
					$thim_logo_size = @getimagesize( $thim_logo_stick_logo_src );
					$logo_size      = $thim_logo_size[3];
					$site_title     = esc_attr( get_bloginfo( 'name', 'display' ) );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home" class="sticky-logo-mobile">
					<img src="' . $thim_logo_stick_logo_src . '" alt="' . $site_title . '" ' . $logo_size . ' /></a>';
				} elseif ( isset( $theme_options_data['thim_logo_mobile'] ) && $theme_options_data['thim_logo_mobile'] <> '' ) {
					$thim_logo     = $theme_options_data['thim_logo_mobile'];
					$thim_logo_src = $thim_logo; // For the default value
					if ( is_numeric( $thim_logo ) ) {
						$logo_attachment = wp_get_attachment_image_src( $thim_logo, 'full' );
						$thim_logo_src   = $logo_attachment[0];
					}
					$thim_logo_size = @getimagesize( $thim_logo_src );
					$logo_size      = $thim_logo_size[3];
					$site_title     = esc_attr( get_bloginfo( 'name', 'display' ) );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home" class="sticky-logo-mobile">
				<img src="' . $thim_logo_src . '" alt="' . $site_title . '" ' . $logo_size . ' /></a>';
				}
			}
		endif; // thim_sticky_logo
	}
}
