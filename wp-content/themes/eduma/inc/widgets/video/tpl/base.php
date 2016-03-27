<?php
$videos = '';

$width = $height = '100%';
if( isset($instance['video_width']) && '' != $instance['video_width'] ) {
	$width = $instance['video_width'];
}
if( isset($instance['video_height']) && '' != $instance['video_height'] ) {
	$height = $instance['video_height'];
}

?>
<div class="thim-video-box">
	<div class="video-container">
<!--		<div class="video-button thim-color"><i class="fa fa-play "></i></div>-->
		<?php
		echo '<div class="video"><iframe id="thim-video" width="'.$width.'" height="'.$height.'" src="https://player.vimeo.com/video/' . esc_attr( $instance['external_video'] ) . '?portrait=0&title=0&byline=0&badge=0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="border: 0px;"></iframe></div>';
		?>
	</div>
</div>