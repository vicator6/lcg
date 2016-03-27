<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$course_id = get_the_ID();
$course_review = learn_press_get_course_review( $course_id );
if( $course_review['total'] ) {
    $course_rate = learn_press_get_course_rate( $course_id );
    $reviews = $course_review['reviews'];
    ?>
    <div id="course-reviews">
        <h3 class="course-review-head"><?php _e( 'Reviews', 'learnpress_course_review' );?></h3>
        <p class="course-average-rate"><?php printf( __( 'Average rate: <span>%.1f</span>', 'learnpress_course_review' ), $course_rate );?></p>
        <ul class="course-reviews-list">
            <?php foreach( $reviews as $review ) {?>
                <?php
                learn_press_course_review_template( 'loop-review.php', array( 'review' => $review ) );
                ?>
            <?php } ?>
            <?php if( empty( $course_review['finish'] ) ){?>
            <li class="loading"><?php _e( 'Loading...', 'learnpress_course_review' );?></li>
            <?php }
            //else?>
            <!-- <li><?php _e( 'No review to load', 'learnpress_course_review' );?></li> -->            
        </ul>
        <?php if( empty( $course_review['finish'] ) ){?>
        <button class="button" id="course-review-load-more" data-paged="<?php echo $course_review['paged'];?>"><?php _e( 'Load More', 'learnpress_course_review' );?></button>
        <?php }?>
    </div>
    <?php
}