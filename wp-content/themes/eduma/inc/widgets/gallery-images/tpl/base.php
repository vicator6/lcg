<?php
$link_before = $after_link = $image = $css_animation = $number = $style_width = '';
if ( $instance['image'] ) {
	if ( $instance['link_target'] ) {
		$t = 'target="_blank"';
	} else {
		$t = '';
	}
	if ( $instance['number'] ) {
		$number = 'data-visible="' . $instance['number'] . '"';
	}

	$img_id = explode( ",", $instance['image'] );
	if ( $instance['image_link'] ) {
		$img_url = explode( ",", $instance['image_link'] );
	}

	$css_animation = $instance['css_animation'];
	$css_animation = thim_getCSSAnimation( $css_animation );
	echo '<div class="thim-carousel-wrapper gallery-img ' . esc_attr( $css_animation ) . '" ' . $number . '>';
	$i = 0;
	foreach ( $img_id as $id ) {
		$src = wp_get_attachment_image_src( $id, $instance['image_size'] );
		if ( $src ) {
			$img_size = '';
			$src_size = @getimagesize( $src['0'] );
			$image    = '<img src ="' . esc_url( $src['0'] ) . '" ' . $src_size[3] . ' alt=""/>';
		}
		if ( $instance['image_link'] ) {
			$link_before = '<a ' . $t . ' href="' . esc_url( $img_url[$i] ) . '">';
			$after_link  = "<span class='mark'></span></a>";
		}
		echo '<div class="item"' . $style_width . '>' . $link_before . $image . $after_link . "</div>";
		$i ++;
	}
	echo "</div>";
}