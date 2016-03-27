<?php

learn_press_prevent_access_directly();

$course_status = learn_press_get_user_course_status();
// only show enroll button if user had not enrolled
if ( ! ( $course_status && ( 'completed' != strtolower( $course_status ) ) ) ){
    return;
}
?>
<div class="course-status">
<?php
    _e( 'You have taken this course but it\'s status is not publish for you', 'learn_press' );
?>
</div>