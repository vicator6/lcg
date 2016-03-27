<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class LPR_Assets
 */
class LPR_Assets {

	// styles
	private static $styles = array();

	// scripts
	private static $scripts = array();

	// localize
	private static $wp_localize_scripts = array();

	private static $localized = false;

	/**
	 * Init Asset
	 */
	static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
		add_action( 'wp_print_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'localize_printed_scripts' ), 5 );
	}

	/**
	 * register script
	 *
	 * @param string  $handle
	 * @param string  $src
	 * @param array   $deps
	 * @param string  $version
	 * @param boolean $in_footer
	 */
	static function add_script( $handle, $src, $deps = array( 'jquery' ), $version = LEARNPRESS_VERSION, $in_footer = true ) {
		self::$scripts[] = $handle;
		wp_register_script( $handle, $src, $deps, $version, $in_footer );
	}

	/**
	 * register style
	 *
	 * @param string $handle
	 * @param string $src
	 * @param array  $deps
	 * @param string $version
	 * @param string $media
	 */
	static function add_style( $handle, $src, $deps = array(), $version = LEARNPRESS_VERSION, $media = 'all' ) {
		self::$styles[] = $handle;
		wp_register_style( $handle, $src, $deps, $version, $media );
	}

	/**
	 * enqueue script
	 *
	 * @param string  $handle
	 * @param string  $src
	 * @param array   $deps
	 * @param string  $version
	 * @param boolean $in_footer
	 */
	static function enqueue_script( $handle, $src = '', $deps = array( 'jquery' ), $version = LEARNPRESS_VERSION, $in_footer = true ) {
		if ( !in_array( $handle, self::$scripts ) && $src ) {
			self::add_script( $handle, $src, $deps, $version, $in_footer );
		}
		wp_enqueue_script( $handle );
	}

	/**
	 * enqueue style
	 *
	 * @param string $handle
	 * @param string $src
	 * @param array  $deps
	 * @param string $version
	 * @param string $media
	 */
	static function enqueue_style( $handle, $src = '', $deps = array(), $version = LEARNPRESS_VERSION, $media = 'all' ) {
		if ( !in_array( $handle, self::$styles ) && $src ) {
			self::add_style( $handle, $src, $deps, $version, $media );
		}
		wp_enqueue_style( $handle );
	}

	/**
	 * add translate text
	 *
	 * @param array $localize
	 */
	static function add_localize( $key, $localize = null, $handle = 'learn-press-js' ) {
		if ( is_array( $key ) ) {
			foreach ( $key as $k => $v ) {
				self::add_localize( $k, $v, $handle );
			}
		} elseif ( is_string( $key ) && strlen( $key ) ) {
			if ( !$handle ) $handle = 'learn-press-js';
			if ( empty( self::$wp_localize_scripts[$handle] ) ) self::$wp_localize_scripts[$handle] = array();
			self::$wp_localize_scripts[$handle][$key] = $localize;
		}
	}

	/**
	 * Localize script
	 *
	 * @param  mixed $handle
	 */
	private static function localize_script( $handle ) {
		$data = !empty( self::$wp_localize_scripts[$handle] ) ? self::$wp_localize_scripts[$handle] : false;
		if ( wp_script_is( $handle ) && $data ) {
			$name = str_replace( '-', '_', $handle ) . '_localize';
			unset( self::$wp_localize_scripts[$handle] );
			wp_localize_script( $handle, $name, apply_filters( $name, $data ) );
		}
	}

	/**
	 * Load Script
	 */
	static function load_scripts() {

		self::add_style( 'lpr-learnpress-css', LPR_CSS_URL . 'learnpress.css' );
		self::add_style( 'lpr-time-circle-css', LPR_CSS_URL . 'timer.css' );

		self::add_script( 'lpr-alert-js', LPR_JS_URL . 'jquery.alert.js' );
        self::add_script( 'lpr-global', LPR_JS_URL . 'global.js' );
		self::add_script( 'lpr-time-circle-js', LPR_JS_URL . 'jquery.timer.js' );
        self::add_script( 'block-ui', LPR_JS_URL . 'jquery.block-ui.js' );
        self::add_script( 'learn-press-js', LPR_JS_URL . 'learnpress.js', array( 'jquery', 'lpr-alert-js', 'lpr-global', 'lpr-time-circle-js' ) );

        learn_press_enqueue_script( "<script>var ajaxurl='" . admin_url( 'admin-ajax.php' ) . "';</script>", true );

		global $post;

		if ( ! $post || ! in_array( $post->post_type, array( 'lpr_course', 'lpr_quiz', 'lpr_lesson' ) ) ) {
			return;
		}

		self::enqueue_style( 'lpr-learnpress-css' );
		self::enqueue_style( 'lpr-time-circle-css' );
		self::enqueue_style( 'dashicons' );

		self::enqueue_script( 'learn-press-js' );
		self::enqueue_script( 'lpr-alert-js' );
		self::enqueue_script( 'lpr-time-circle-js' );
        self::enqueue_script( 'block-ui' );
	}

	static function localize_printed_scripts() {
		if ( self::$scripts ) foreach ( self::$scripts as $handle ) {
			self::localize_script( $handle );
		}
	}
}
//
LPR_Assets::init();