<?php
/*
Plugin Name: Thim Event
Plugin URI: http://thimpress.com/thim-event
Description: Thim event - countdown
Author: ThimPress
Version: 1.3
Author URI: http://thimpress.com
*/

if ( !defined( 'ABSPATH' ) ) exit();

if ( defined( 'TP_EVENT_PATH' ) ) return;

define( 'TP_EVENT_PATH', plugin_dir_path( __FILE__ ) );
define( 'TP_EVENT_URI', plugins_url( '', __FILE__ ) );
define( 'TP_EVENT_INC', TP_EVENT_PATH . 'inc' );
define( 'TP_EVENT_INC_URI', TP_EVENT_URI . '/inc' );
define( 'TP_EVENT_ASSETS_URI', TP_EVENT_URI . '/assets' );
define( 'TP_EVENT_LIB_URI', TP_EVENT_INC_URI . '/libraries' );
define( 'TP_EVENT_VER', 1.3 );

/**
 * Event class
 */
class TP_Event {

	/**
	 * file include
	 * @var array
	 */
	protected $_files = array();

	/**
	 * assets enqueue
	 * @var array
	 */
	protected $_assets = array(
		'admin' => array( 'css' => array(), 'js' => array() ),
		'site'  => array( 'css' => array(), 'js' => array() )
	);

	function __construct() {
		$this->includes();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueues' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueues' ) );
		// active plugin
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );
		// $this->text_domain();
		add_action( 'plugins_loaded', array( $this, 'text_domain' ) );
	}

	/**
	 * install plugin options, define v.v.
	 * @return null
	 */
	public function install() {
		$this->_include( 'install.php' );
	}

	/**
	 * uninstall plugin
	 * @return null
	 */
	public function uninstall() {
		$this->_include( 'uninstall.php' );
	}

	/**
	 * autoload function
	 * @return null
	 */
	public function autoload() {

		$path  = TP_EVENT_PATH . 'assets/autoload';
		$local = array( 'admin', 'site' );

		// assets file
		foreach ( $local as $key => $lo ) {
			$csss = $path . '/' . $lo . '/css';
			if ( file_exists( $csss ) ) {
				foreach ( (array) glob( $csss . '/*.css' ) as $key => $f ) {
					if ( ( is_admin() && $lo === 'admin' ) || ( !is_admin() && $lo === 'site' ) ) {
						$name        = basename( $f );
						$mod_enqueue = 'tp-event-' . $lo . '-css-' . $name;
						wp_enqueue_style( $mod_enqueue, TP_EVENT_ASSETS_URI . '/autoload/' . $lo . '/css/' . $name, array(), TP_EVENT_VER );
					}
				}
			}

			$jss = $path . '/' . $lo . '/js';
			if ( file_exists( $jss ) ) {
				foreach ( (array) glob( $jss . '/*.js' ) as $key => $f ) {
					if ( ( is_admin() && $lo === 'admin' ) || ( !is_admin() && $lo === 'site' ) ) {
						$name        = basename( $f );
						$mod_enqueue = 'tp-event-' . $lo . '-js-' . $name;
						wp_enqueue_script( $mod_enqueue, TP_EVENT_ASSETS_URI . '/autoload/' . $lo . '/js/' . $name, array(), TP_EVENT_VER, true );
					}
				}
			}
		}
	}

	/**
	 * include file
	 *
	 * @param  array or string
	 *
	 * @return null
	 */
	public function includes() {

		$this->_include( 'inc/functions.php' );

		$paths = array( 'abstract', 'shortcode', 'widget', 'metabox' );

		foreach ( $paths as $key => $path ) {
			$real_path = TP_EVENT_INC . '/' . $path;
			foreach ( (array) glob( $real_path . '/class-tp-event-' . $path . '-*.php' ) as $key => $file ) {
				$this->_include( $file );
			}
		}

		$this->_include( 'inc/admin/class-tp-custom-post-type.php' );
		$this->_include( 'inc/class-tp-template.php' );
	}

	public function _include( $file ) {
		if ( is_array( $file ) ) {
			foreach ( $file as $key => $f ) {
				if ( file_exists( TP_EVENT_PATH . $f ) )
					require_once TP_EVENT_PATH . $f;
			}
		} else {
			if ( file_exists( TP_EVENT_PATH . $file ) )
				require_once TP_EVENT_PATH . $file;
			elseif ( file_exists( $file ) )
				require_once $file;
		}
	}

	/**
	 * enqueue script, style
	 * @return null
	 */
	public function enqueues() {
		wp_enqueue_script( 'jquery' );
		if ( is_admin() ) {
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'wp-util' );

			global $post;
			if ( $post && $post->post_type === 'tp_event' ) {
				wp_dequeue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'thim-event-bootstrap-datepicker-js', TP_EVENT_LIB_URI . '/datetimepicker/js/bootstrap-datepicker.js', array(), TP_EVENT_VER, true );
				wp_enqueue_script( 'thim-event-bootstrap-timepicker-js', TP_EVENT_LIB_URI . '/datetimepicker/js/jquery.timepicker.js', array(), TP_EVENT_VER, true );

				wp_enqueue_style( 'thim-event-datepicker', TP_EVENT_LIB_URI . '/datetimepicker/css/bootstrap-datetimepicker.css', array(), TP_EVENT_VER );
				wp_enqueue_style( 'thim-event-timepicker', TP_EVENT_LIB_URI . '/datetimepicker/css/jquery.timepicker.css', array(), TP_EVENT_VER );
			}

		} else {
			// countdown
			wp_register_script( 'thim-event-countdown-plugin-js', TP_EVENT_LIB_URI . '/countdown/js/jquery.plugin.min.js', array(), TP_EVENT_VER, true );
			wp_register_script( 'thim-event-countdown-js', TP_EVENT_LIB_URI . '/countdown/js/jquery.countdown.min.js', array(), TP_EVENT_VER, true );
			wp_localize_script( 'thim-event-countdown-js', 'TP_Event', tp_event_l18n() );

			wp_enqueue_script(  'thim-event-countdown-plugin-js' );
			wp_enqueue_script(  'thim-event-countdown-js' );
			wp_enqueue_style( 'thim-event-countdown-css', TP_EVENT_LIB_URI . '/countdown/css/jquery.countdown.css', array(), TP_EVENT_VER );

			// owl-carousel
			wp_enqueue_script( 'thim-event-owl-carousel-js', TP_EVENT_LIB_URI . '/owl-carousel/js/owl.carousel.min.js', array(), TP_EVENT_VER, true );
			wp_enqueue_style( 'thim-event-owl-carousel-css', TP_EVENT_LIB_URI . '/owl-carousel/css/owl.carousel.css', array(), TP_EVENT_VER );

		}
		$this->autoload();

	}

	/**
	 * load text domain
	 * @return null
	 */
	function text_domain() {
		// Get mo file
		$text_domain = 'tp-event';
		$locale      = apply_filters( 'plugin_locale', get_locale(), $text_domain );
		$mo_file     = $text_domain . '-' . $locale . '.mo';
		// Check mo file global
		$mo_global = WP_LANG_DIR . '/plugins/' . $mo_file;
		// Load translate file
		if ( file_exists( $mo_global ) ) {
			load_textdomain( $text_domain, $mo_global );
		} else {
			load_textdomain( $text_domain, TP_EVENT_PATH . '/languages/' . $mo_file );
		}
	}
}
new TP_Event();