<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of customizer-options
 *
 * @author Tuannv
 */
require_once "generate-less-to-css.php";

class Thim_Customize_Options {

	function __construct() {
		add_action( 'tf_create_options', array( $this, 'create_customizer_options' ) );
		add_action( 'customize_save_after', array( $this, 'generate_to_css' ) );

		/* Unregister Default Customizer Section */
		add_action( 'customize_register', array( $this, 'unregister' ) );
	}

	function unregister( $wp_customize ) {
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_section( 'background_image' );
		$wp_customize->remove_section( 'nav' );
		$wp_customize->remove_section( 'static_front_page' );
	}

	function create_customizer_options() {
		$titan = TitanFramework::getInstance( 'thim' );
		/* Register Customizer Sections */
		//include heading
		include THIM_DIR . "inc/admin/customizer-sections/logo.php";

		include THIM_DIR . "inc/admin/customizer-sections/header.php";
		include THIM_DIR . "inc/admin/customizer-sections/header-mainmenu.php";
		include THIM_DIR . "inc/admin/customizer-sections/header-mobile.php";;
		include THIM_DIR . "inc/admin/customizer-sections/header-submenu.php";
		include THIM_DIR . "inc/admin/customizer-sections/header-stickymenu.php";
		include THIM_DIR . "inc/admin/customizer-sections/header-toolbar.php";

		//include styling
		include THIM_DIR . "inc/admin/customizer-sections/styling.php";
		include THIM_DIR . "inc/admin/customizer-sections/styling-color.php";
		include THIM_DIR . "inc/admin/customizer-sections/styling-layout.php";
		include THIM_DIR . "inc/admin/customizer-sections/styling-pattern.php";
		include THIM_DIR . "inc/admin/customizer-sections/styling-rtl.php";

		//include display setting
		include THIM_DIR . "inc/admin/customizer-sections/display.php";
		include THIM_DIR . "inc/admin/customizer-sections/display-archive.php";
		include THIM_DIR . "inc/admin/customizer-sections/display-frontpage.php";
		include THIM_DIR . "inc/admin/customizer-sections/display-postpage.php";
		include THIM_DIR . "inc/admin/customizer-sections/display-sharing.php";


		//include typography
		include THIM_DIR . "inc/admin/customizer-sections/typography.php";

		//include footer
		include THIM_DIR . "inc/admin/customizer-sections/footer.php";
		include THIM_DIR . "inc/admin/customizer-sections/footer-copyright.php";
		include THIM_DIR . "inc/admin/customizer-sections/footer-options.php";

		//include woocommerce
		if ( class_exists( 'WooCommerce' ) ) {
			include THIM_DIR . "inc/admin/customizer-sections/woocommerce.php";
			include THIM_DIR . "inc/admin/customizer-sections/woocommerce-archive.php";
			include THIM_DIR . "inc/admin/customizer-sections/woocommerce-setting.php";
			include THIM_DIR . "inc/admin/customizer-sections/woocommerce-single.php";
		}
		// include customizer courses
		if ( class_exists( 'LearnPress' ) ) {
			include THIM_DIR . "inc/admin/customizer-sections/learnpress.php";
			include THIM_DIR . "inc/admin/customizer-sections/learnpress-archive.php";
			include THIM_DIR . "inc/admin/customizer-sections/learnpress-single.php";
			include THIM_DIR . "inc/admin/customizer-sections/learnpress-features.php";
		}

		if ( class_exists( 'THIM_portfolio' ) ) {
			include THIM_DIR . "inc/admin/customizer-sections/portfolio.php";
			include THIM_DIR . "inc/admin/customizer-sections/portfolio-archive.php";
//			include THIM_DIR . "inc/admin/customizer-sections/portfolio-single.php";
//			include THIM_DIR . "inc/admin/customizer-sections/portfolio-features.php";
		}

		// include Support
		include THIM_DIR . "inc/admin/customizer-sections/utilities.php";

		//include Custom Css
		include THIM_DIR . "inc/admin/customizer-sections/custom-css.php";
		//include Import/Export
		include THIM_DIR . "inc/admin/customizer-sections/import-export.php";
		//include Share this in post
		include THIM_DIR . "inc/admin/metabox-sections/share-this.php";
		include THIM_DIR . "inc/admin/metabox-sections/portfolio-background.php";
	}

	function generate_to_css() {
		$options = get_theme_mods();
		thim_options_variation( $options );
		thim_generate( THIM_DIR . 'style', '.css', $options );
	}
}

new Thim_Customize_Options();