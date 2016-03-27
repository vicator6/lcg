<?php
/**
 * thim functions and definitions
 *
 * @package thim
 */

define( 'THIM_DIR', trailingslashit( get_template_directory() ) );
define( 'THIM_URI', trailingslashit( get_template_directory_uri() ) );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

/**
 * Translation ready
 */

load_theme_textdomain( 'eduma', get_template_directory() . '/languages' );

if ( ! function_exists( 'thim_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function thim_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on thim, use a find and replace
		 * to change 'eduma' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'eduma', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'eduma' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );
		/* Add WooCommerce support */
		add_theme_support( 'woocommerce' );
		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio'
		) );
	}
endif; // thim_setup
add_action( 'after_setup_theme', 'thim_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if ( ! function_exists( 'thim_widgets_inits' ) ) {
	function thim_widgets_inits() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'eduma' ),
			'id'            => 'sidebar',
			'description'   => esc_html__( 'Right Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Toolbar', 'eduma' ),
			'id'            => 'toolbar',
			'description'   => esc_html__( 'Toolbar Header', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Menu Right', 'eduma' ),
			'id'            => 'menu_right',
			'description'   => esc_html__( 'Menu Right', 'eduma' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Menu Top', 'eduma' ),
			'id'            => 'menu_top',
			'description'   => esc_html__( 'Menu top only display with header version 2', 'eduma' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Footer', 'eduma' ),
			'id'            => 'footer',
			'description'   => esc_html__( 'Footer Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s footer_widget">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Bottom', 'eduma' ),
			'id'            => 'footer_bottom',
			'description'   => esc_html__( 'Footer Bottom Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Copyright', 'eduma' ),
			'id'            => 'copyright',
			'description'   => esc_html__( 'Copyright', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		if ( class_exists( 'LearnPress' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar Courses', 'eduma' ),
				'id'            => 'sidebar_courses',
				'description'   => esc_html__( 'Sidebar Courses', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		}
	}
}

add_action( 'widgets_init', 'thim_widgets_inits' );

/**
 * Enqueue styles.
 */
if ( ! function_exists( 'thim_styles' ) ) {
	function thim_styles() {
		$theme_options_data = thim_options_data();

		wp_enqueue_style( 'thim-css-style', THIM_URI . 'assets/css/custom-style.css', array() );

		if ( is_multisite() ) {
			$stylesheet_uri_multi = THIM_URI . 'style-' . get_current_blog_id() . '.css';
			wp_enqueue_style( 'thim-style', apply_filters( 'thim_stylesheet_multisite', $stylesheet_uri_multi, get_current_blog_id() ) );
		} else {
			wp_enqueue_style( 'thim-style', get_stylesheet_uri() );
		}

		if ( isset( $theme_options_data['thim_rtl_support'] ) && $theme_options_data['thim_rtl_support'] == '1' ) {
			wp_enqueue_style( 'thim-rtl', THIM_URI . 'rtl.css', array() );
		}
	}

	add_action( 'wp_enqueue_scripts', 'thim_styles' );
}
/**
 * Enqueue scripts.
 */
if ( ! function_exists( 'thim_scripts' ) ) {
	function thim_scripts() {

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// New script update from resca,sailing
		wp_enqueue_script( 'thim-main', THIM_URI . 'assets/js/main.min.js', array( 'jquery' ), '', true );

		if( thim_is_new_learnpress() ) {
			wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script-v1.js', array( 'jquery' ), '', true );
			//wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script-v1.min.js', array( 'jquery' ), '', true );
		}else{
			wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script.min.js', array( 'jquery' ), '', true );
			//wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script-v1.min.js', array( 'jquery' ), '', true );
		}

		// Remove some scripts LearnPress
		wp_dequeue_style( 'lpr-print-rate-css' );
		wp_dequeue_style( 'tipsy' );
		wp_dequeue_style( 'certificate' );
		wp_dequeue_style( 'fib' );
		wp_dequeue_style( 'sorting-choice' );
		wp_dequeue_style( 'course-wishlist-style' );
		wp_dequeue_script( 'tipsy' );
		wp_dequeue_script( 'lpr-print-rate-js' );
		wp_dequeue_script( 'course-wishlist-script' );
		wp_dequeue_script( 'learnpress-block-ui' );

		if ( ! is_single( 'lpr_course' ) && ! is_single( 'lpr_quiz' ) ) {
			wp_dequeue_script( 'sorting-choice' );
		}

		if ( is_front_page() ) {
			wp_dequeue_script( 'webfont' );
			wp_dequeue_script( 'fabric-js' );
			wp_dequeue_script( 'certificate' );

			wp_dequeue_script( 'thim-event-countdown-plugin-js' );
			wp_dequeue_script( 'thim-event-countdown-js' );


			if ( ! is_user_logged_in() ) {
				wp_dequeue_style( 'dashicons' );
			}
		}

		//Plugin tp-event
		wp_dequeue_style( 'tp-event-site-css-events.css' );
		wp_dequeue_script( 'thim-event-owl-carousel-js' );
		wp_dequeue_script( 'tp-event-site-js-events.js' );
		wp_dequeue_style( 'thim-event-countdown-css' );
		wp_dequeue_style( 'thim-event-owl-carousel-css' );

		wp_dequeue_style( 'mo_openid_admin_settings_style' );
		wp_dequeue_style( 'mo_openid_admin_settings_phone_style' );
		wp_dequeue_style( 'mo-wp-bootstrap-social' );
		wp_dequeue_style( 'mo-wp-bootstrap-main' );
		wp_dequeue_style( 'mo-wp-font-awesome' );

		wp_dequeue_style( 'contact-form-7' );
		wp_dequeue_style( 'mc4wp-form-basic' );

		//Woocommerce
		wp_dequeue_script( 'jquery-cookie' );

		//Miniorange-login
		wp_dequeue_script( 'js-cookie-script' );
		wp_dequeue_script( 'mo-social-login-script' );

		if ( ! thim_use_bbpress() ) {
			wp_dequeue_style( 'bbp-default' );
			wp_dequeue_script( 'bbpress-editor' );
		}

		// Localize the script with new data
		wp_localize_script( 'thim-custom-script', 'thim_placeholder', array(
			'login'    => esc_attr__( 'Username', 'eduma' ),
			'password' => esc_attr__( 'Password', 'eduma' ),
		) );

		if ( get_post_type() == 'portfolio' && ( is_category() || is_archive() || is_singular( 'portfolio' ) ) ) {
			wp_enqueue_script( 'thim-portfolio-appear', THIM_URI . 'assets/js/jquery.appear.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'thim-portfolio-widget', THIM_URI . 'assets/js/portfolio.js', array(
				'jquery',
				'thim-main'
			), '', true );
		}
	}

	add_action( 'wp_enqueue_scripts', 'thim_scripts', 1000 );
}

if ( class_exists( 'WooCommerce' ) ) {
	add_action( 'wp_enqueue_scripts', 'thim_manage_woocommerce_styles', 9999 );
}

if ( ! function_exists( 'thim_manage_woocommerce_styles' ) ) {
	function thim_manage_woocommerce_styles() {
		//remove generator meta tag
		remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

		//first check that woo exists to prevent fatal errors
		if ( function_exists( 'is_woocommerce' ) ) {
			//dequeue scripts and styles
			if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
				wp_dequeue_style( 'woocommerce_frontend_styles' );
				wp_dequeue_style( 'woocommerce_fancybox_styles' );
				wp_dequeue_style( 'woocommerce_chosen_styles' );
				wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
				wp_dequeue_style( 'woocommerce-layout' );
				wp_dequeue_style( 'woocommerce-general' );
				wp_dequeue_script( 'wc_price_slider' );
				wp_dequeue_script( 'wc-single-product' );
				wp_dequeue_script( 'wc-add-to-cart' );
				wp_dequeue_script( 'wc-cart-fragments' );
				wp_dequeue_script( 'wc-checkout' );
				wp_dequeue_script( 'wc-add-to-cart-variation' );
				wp_dequeue_script( 'wc-single-product' );
				wp_dequeue_script( 'wc-cart' );
				wp_dequeue_script( 'wc-chosen' );
				wp_dequeue_script( 'woocommerce' );
			}
		}

	}
}

function thim_custom_admin_scripts() {
	wp_enqueue_script( 'thim-admin-custom-script', THIM_URI . 'assets/js/admin-custom-script.js', array( 'jquery' ), '', true );
	wp_enqueue_style( 'thim-admin-icomoon-style', THIM_URI . 'assets/css/icomoon.css', array() );
}

add_action( 'admin_enqueue_scripts', 'thim_custom_admin_scripts' );

// Require library
require THIM_DIR . 'inc/libs/class-tgm-plugin-activation.php';
require THIM_DIR . 'inc/libs/theme-wrapper.php';
require THIM_DIR . 'inc/libs/tax-meta-class/Tax-meta-class.php';
require THIM_DIR . 'inc/libs/custom-export.php';
require THIM_DIR . 'inc/libs/aq_resizer.php';


// Custom functions.
require get_template_directory() . '/inc/custom-functions.php';

// Require plugins
if ( is_admin() && current_user_can( 'manage_options' ) ) {
	require THIM_DIR . 'inc/admin/plugins-require.php';
}

if ( thim_plugin_active( 'thim-framework/tp-framework.php' ) ) {

	require THIM_DIR . 'inc/admin/customize-options.php';

	require THIM_DIR . 'inc/widgets/widgets.php';

	require THIM_DIR . 'inc/tax-meta.php';
}

/**
 * Custom template tags for this theme.
 */
require THIM_DIR . 'inc/template-tags.php';


if ( class_exists( 'WooCommerce' ) ) {
	require THIM_DIR . 'woocommerce/woocommerce.php';
}

if ( class_exists( 'BuddyPress' ) ) {
	require THIM_DIR . 'buddypress/bp-custom.php';
}

//logo
require_once THIM_DIR . 'inc/header/logo.php';

//custom logo mobile
require_once THIM_DIR . 'inc/header/logo-mobile.php';

/**
 * Testing
 */
function xxx( $x ) {
	echo '<pre>';
	if ( is_array( $x ) || is_object( $x ) ) {
		print_r( $x );
	} else {
		echo $x;
	}
	echo '</pre>';
}
