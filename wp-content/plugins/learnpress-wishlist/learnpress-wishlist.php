<?php
/*
Plugin Name: LearnPress Courses Wishlist
Plugin URI: http://thimpress.com/learnpress
Description: Wishlist feature
Author: thimpress
Version: 0.9.3
Author URI: http://thimpress.com
Tags: learnpress
*/
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'LPR_WISHLIST_PATH', dirname( __FILE__ ) );
/**
 * Register wishlist addon
 */
function learn_press_register_wishlist() {
    require_once( LPR_WISHLIST_PATH . '/init.php' );
}
add_action( 'learn_press_register_add_ons', 'learn_press_register_wishlist' );


add_action('plugins_loaded','learnpress_wishlist_translations');
function learnpress_wishlist_translations(){          
    $textdomain = 'learnpress_wishlist';
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

