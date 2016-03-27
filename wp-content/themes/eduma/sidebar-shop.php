<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package thim
 */
if (!is_active_sidebar('shop')) {
    return;
}
?>

<div id="sidebar" class="widget-area col-sm-3 sticky-sidebar" role="complementary">
	<?php if ( ! dynamic_sidebar( 'shop' ) ) :
		dynamic_sidebar( 'shop' );
	endif; // end sidebar widget area ?>
</div><!-- #secondary-2 -->
