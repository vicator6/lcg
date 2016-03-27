<?php
/**
 * This file contains deprecated functions
 */
function learn_press_download_images() {
	global $wp_filesystem;
	WP_Filesystem();
	$import_content = $wp_filesystem->get_contents( LPR_PLUGIN_PATH . 'dummy-data/learnpress-how-to-use-learnpress.xml' );
	if( preg_match_all( '/http:\/\/home.foobla.com\/wp-content\/uploads\/(.*\.png)/iSU', $import_content, $matches)){
		$image_sources = array_values( array_unique( $matches[0] ) );
		$image_names = array_values( array_unique( $matches[1] ) );
		foreach( $image_sources as $k => $src ){
			$image_data = $wp_filesystem->get_contents( $src );
			$image_name = basename( $image_names[ $k ] );
			echo "Write: " . (LPR_PLUGIN_PATH . 'dummy-data/' . $image_name)."\n";
			$wp_filesystem->put_contents(  LPR_PLUGIN_PATH . 'dummy-data/' . $image_name, $image_data );
		}
	}
	echo "XXXXXXXXXXXXXXXXXXXXXXXXXXX";
}
add_action( 'admin_head', 'learn_press_download_images' );



