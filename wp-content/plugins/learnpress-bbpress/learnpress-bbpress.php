<?php
/*
Plugin Name: LearnPress bbPress Integration
Plugin URI: http://thimpress.com/learnpress
Description: Using the forum for courses provided by bbPress
Author: thimpress
Version: 0.9.3
Author URI: http://thimpress.com
Tags: learnpress, lms
*/

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
define( 'LPR_BBP_PATH', dirname( __FILE__ ) );
/**
 * Register bbPress addon
 */
function learn_press_register_bbpress() {
    require_once( LPR_BBP_PATH . '/init.php' );
}

add_action( 'learn_press_register_add_ons', 'learn_press_register_bbpress' );

add_action('plugins_loaded','learnpress_bbpress_translations');
function learnpress_bbpress_translations(){			
	$textdomain = 'learnpress_bbpress';
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