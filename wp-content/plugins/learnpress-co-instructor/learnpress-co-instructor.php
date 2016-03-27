<?php
/*
Plugin Name: LearnPress Co-Instructor
Plugin URI: http://thimpress.com/learnpress
Description: Building courses with other instructors
Author: Ken
Version: 1.0.0
Author URI: http://thimpress.com
Tags: learnpress, lms, add-on, co-instructor
*/

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
define( 'LPR_CO_INSTRUCTOR_PATH', dirname( __FILE__ ) );

/**
 * Register co-instructor addon
 */
function learn_press_register_co_instructors() {
    require_once( LPR_CO_INSTRUCTOR_PATH . '/init.php' );
}

add_action( 'learn_press_register_add_ons', 'learn_press_register_co_instructors' );

add_action('plugins_loaded','learnpress_co_instructor_translations');
function learnpress_co_instructor_translations()
{
    $textdomain = 'learnpress_co_instructor';
    $locale = apply_filters("plugin_locale", get_locale(), $textdomain);
    $lang_dir = dirname(__FILE__) . '/lang/';
    $mofile = sprintf('%s.mo', $locale);
    $mofile_local = $lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;
    if (file_exists($mofile_global)) {
        load_textdomain($textdomain, $mofile_global);
    } else {
        load_textdomain($textdomain, $mofile_local);
    }
}
