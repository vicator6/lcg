<?php
/*
Plugin Name: LearnPress Course Review
Plugin URI: http://thimpress.com/learnpress
Description: Adding review for course
Author: thimpress
Version: 0.9.1
Author URI: http://thimpress.com
Tags: learnpress
*/
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'LPR_COURSE_REVIEW_PATH', dirname( __FILE__ ) );

/**
 * Register course-review course addon
 */
function learn_press_register_course_review() {
    require_once( LPR_COURSE_REVIEW_PATH . '/init.php' );
}
add_action( 'learn_press_register_add_ons', 'learn_press_register_course_review' );

add_action('plugins_loaded','learnpress_course_review_translations');
function learnpress_course_review_translations(){          
    $textdomain = 'learnpress_course_review';
    $locale = apply_filters("plugin_locale", get_locale(), $textdomain);                   
    $lang_dir = dirname( __FILE__ ) . '/lang/';
    $mofile        = sprintf( '%s.mo', $locale );
    $mofile_local  = $lang_dir . $mofile;    
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;
    if ( file_exists( $mofile_global ) ) {      
        load_textdomain( $textdomain, $mofile_global );
    } else {        
        load_textdomain( $textdomain, $mofile_local );
    }  
}
