<?php
/*
Plugin Name: LearnPress Sorting Choice Question
Plugin URI: http://thimpress.com/learnpress
Description: Sorting Choice provide ability to sorting the options of a question to the right order
Author: ThimPress
Version: 0.9.7
Author URI: http://thimpress.com
Tags: learnpress
*/
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! defined( 'LPR_SORTING_CHOICE_PATH' ) ) {
    define( 'LPR_SORTING_CHOICE_FILE', __FILE__ );
    define( 'LPR_SORTING_CHOICE_PATH', dirname(__FILE__) );
}
/**
 * Register entry point for addon
 */
function learn_press_register_sorting_choice_add_on() {
    if( function_exists( 'learn_press_addon_notice' ) ){
        learn_press_addon_notice( 'LearnPress-WooCommerce Payment add-on is not ready for LearnPress 1.x. It will be available soon.', 'learnpress_woo_payment' );
        return;
    }
    require_once( LPR_SORTING_CHOICE_PATH . '/includes/class-sorting-choice.php' );
}
add_action( 'learn_press_register_add_ons', 'learn_press_register_sorting_choice_add_on' );

add_action('plugins_loaded','learnpress_sorting_choice_translations');
function learnpress_sorting_choice_translations(){          
    $textdomain = 'learnpress_sorting_choice';
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

