<?php
add_action( 'thim_logo', 'thim_logo', 1 );
// logo
if ( !function_exists( 'thim_logo' ) ) :
	function thim_logo() {
		$theme_options_data = thim_options_data();
		if ( isset( $theme_options_data['thim_logo'] ) && $theme_options_data['thim_logo'] <> '' ) {
			$thim_logo     = $theme_options_data['thim_logo'];
			$thim_logo_src = $thim_logo; // For the default value
			if ( is_numeric( $thim_logo ) ) {
				$logo_attachment = wp_get_attachment_image_src( $thim_logo, 'full' );
				if( $logo_attachment ) {
					$thim_logo_src = $logo_attachment[0];
				}else{
					$thim_logo_src = get_template_directory_uri().'/images/logo.png';
				}
			}
			$thim_logo_size = @getimagesize( $thim_logo_src );
			$logo_size      = $thim_logo_size[3];
			$site_title     = esc_attr( get_bloginfo( 'name', 'display' ) );
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home" class="no-sticky-logo"><img src="' . $thim_logo_src . '" alt="' . $site_title . '" ' . $logo_size . ' /></a>';
		} else {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home" class="no-sticky-logo">' . esc_attr( get_bloginfo( 'name' ) ) . '</a>';
		}
	}
endif;
add_action( 'thim_sticky_logo', 'thim_sticky_logo', 1 );

// get sticky logo
if ( !function_exists( 'thim_sticky_logo' ) ) :
	function thim_sticky_logo() {
		$theme_options_data = thim_options_data();
		if ( isset( $theme_options_data['thim_sticky_logo'] ) && $theme_options_data['thim_sticky_logo'] <> '' ) {
			$thim_logo_stick_logo     = $theme_options_data['thim_sticky_logo'];

			$thim_logo_stick_logo_src = $thim_logo_stick_logo; // For the default value
			if ( is_numeric( $thim_logo_stick_logo ) ) {
				$logo_attachment          = wp_get_attachment_image_src( $thim_logo_stick_logo, 'full' );
				if( $logo_attachment ) {
					$thim_logo_stick_logo_src = $logo_attachment[0];
				}else{
					$thim_logo_stick_logo_src = get_template_directory_uri().'/images/logo-sticky.png';
				}

			}
			$thim_logo_size = @getimagesize( $thim_logo_stick_logo_src );
			$logo_size      = $thim_logo_size[3];
			$site_title     = esc_attr( get_bloginfo( 'name', 'display' ) );
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '" rel="home" class="sticky-logo">
					<img src="' . $thim_logo_stick_logo_src . '" alt="' . $site_title . '" ' . $logo_size . ' /></a>';
		}
	}
endif; // thim_sticky_logo
