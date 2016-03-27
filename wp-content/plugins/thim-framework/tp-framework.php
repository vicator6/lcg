<?php
/*
Plugin Name: Thim Framework
Plugin URI: thimpress.com
Description: Theme Framework by ThimPress
Author: ThimPress
Author URI: thimpress.com
Version: 1.9.3
Text Domain: thim-framework
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'TP_THEME_THIM_DIR', trailingslashit( get_template_directory() ) );
define( 'TP_THEME_THIM_URI', trailingslashit( get_template_directory_uri() ) );
define( 'TP_THEME_FRAMEWORK_DIR', plugin_dir_path( __FILE__ ) );
define( 'TP_THEME_FRAMEWORK_URI', plugin_dir_url( __FILE__ ) );
define( 'TP_FRAMEWORK_LIBS_DIR', TP_THEME_FRAMEWORK_DIR . 'libs/' );
define( 'TP_FRAMEWORK_LIBS_URI', TP_THEME_FRAMEWORK_URI . 'libs/' );
define( 'TP_FRAMEWORK_LESS_DIR', TP_THEME_FRAMEWORK_DIR . 'less/' );
define( 'TP_FRAMEWORK_LESS_URI', TP_THEME_FRAMEWORK_URI . 'less/' );
define( 'TP_FRAMEWORK_SCSS_DIR', TP_THEME_FRAMEWORK_DIR . 'scss/' );
define( 'TP_FRAMEWORK_VERSION', '1.9.3' );


/**
 * Init
 */
function tp_init() {
	// Get mo file
	$text_domain = 'thim-framework';
	$locale      = apply_filters( 'plugin_locale', get_locale(), $text_domain );
	$mo_file     = $text_domain . '-' . $locale . '.mo';
	// Check mo file global
	$mo_global = WP_LANG_DIR . '/plugins/' . $mo_file;
	// Load translate file
	if ( file_exists( $mo_global ) ) {
		load_textdomain( $text_domain, $mo_global );
	} else {
		load_textdomain( $text_domain, TP_THEME_FRAMEWORK_DIR . '/languages/' . $mo_file );
	}
}

add_action( 'plugins_loaded', 'tp_init' );


/**
 * Put data to a file with WP_Filesystem
 *
 * @param $file
 * @param $data
 *
 * @return bool
 */
function thim_file_put_contents( $file, $data ) {

	WP_Filesystem();
	global $wp_filesystem;

	return $wp_filesystem->put_contents( $file, $data, FS_CHMOD_FILE );
}

/**
 * Get data from a file with WP_Filesystem
 *
 * @param $file
 *
 * @return bool
 */
function thim_file_get_contents( $file ) {

	WP_Filesystem();
	global $wp_filesystem;

	return $wp_filesystem->get_contents( $file );
}

/**
 * Enqueue admin scripts
 */
function tp_enqueue_backend_scripts() {

	wp_enqueue_style( 'thim-admin-custom-framework', TP_THEME_FRAMEWORK_URI . 'css/custom-framework.css' );
	wp_enqueue_script( 'thim-admin-custom-framework', TP_THEME_FRAMEWORK_URI . 'js/custom-framework.js', array( 'jquery' ), '1.0', true );
	if ( is_admin() ) {
		wp_enqueue_script( 'thim-meta-boxes', TP_THEME_FRAMEWORK_URI . 'js/admin/meta-boxes.js', array( 'jquery' ), '', true );
	}
}

add_action( 'admin_enqueue_scripts', 'tp_enqueue_backend_scripts' );

/**
 * Enqueue frontend scripts
 */
function tp_enqueue_frontend_scripts() {

	wp_enqueue_script( 'framework-bootstrap', TP_THEME_FRAMEWORK_URI . 'js/bootstrap.min.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'thim-awesome', TP_THEME_FRAMEWORK_URI . 'css/font-awesome.min.css', array() );
}

add_action( 'wp_enqueue_scripts', 'tp_enqueue_frontend_scripts' );

// Require framework
require( TP_FRAMEWORK_LIBS_DIR . 'titan-framework/titan-framework.php' );
require( TP_FRAMEWORK_LIBS_DIR . 'class-tp-themeoption-metabox.php' );
require( TP_FRAMEWORK_LIBS_DIR . 'megamenu/class-megamenu.php' );
require( TP_FRAMEWORK_LIBS_DIR . 'class-tp-shortcodes.php' );
require( TP_FRAMEWORK_LIBS_DIR . 'class-tp-widgets.php' );
require( TP_FRAMEWORK_LIBS_DIR . 'post-format/post-formats.php' );

$customize = get_theme_mods();
if ( ! isset( $customize['thim_enable_import_demo'] ) || $customize['thim_enable_import_demo'] ) {
	// Require other processes
	require_once( TP_FRAMEWORK_LIBS_DIR . 'import/inc/class-import-demo.php' );
	require_once( TP_FRAMEWORK_LIBS_DIR . 'import/inc/import/functions.php' );
}