<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package thim
 */
if (!is_active_sidebar('sidebar')) {
    return;
}
?>

<div id="sidebar" class="widget-area col-sm-3 sticky-sidebar" role="complementary">
	<?php if ( ! dynamic_sidebar( 'sidebar' ) ) :
		dynamic_sidebar( 'sidebar' );
	endif; // end sidebar widget area ?>
</div><!-- #secondary -->
