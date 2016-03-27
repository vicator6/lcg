<?php

do_action( 'learn_press_before_all_wishlist' );
echo '<h3>' . __( 'All Wishlist', 'learnpress_wishlist' ) . '</h3>';
do_action( 'learn_press_before_wishlist_course' );
$my_query = learn_press_get_wishlist_courses( $user->ID );
if ( $my_query->have_posts() ) :
	while ( $my_query->have_posts() ) : $my_query->the_post();
		learn_press_get_template( 'addons/course-wishlist/wishlist-content.php' );
	endwhile;
else :
	do_action( 'learn_press_before_no_wishlist_course' );
	echo '<p>' . __( 'No courses in your wishlist!', 'learnpress_wishlist' ) . '</p>';
	do_action( 'learn_press_after_no_wishlist_course' );
endif;
do_action( 'learn_press_after_wishlist_course' );
wp_reset_postdata();

do_action( 'learn_press_after_all_wishlist' );
