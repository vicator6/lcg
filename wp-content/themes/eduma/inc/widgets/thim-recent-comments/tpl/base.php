<?php
$css_animation = $instance['css_animation'];
$css_animation = thim_getCSSAnimation( $css_animation );
// query
$recent_args = array(
	'number' => $instance['number_comments']
);

$recent_comments = get_comments( $recent_args );

// // style
$css = $title_css = $meta_css = '';
// css header
$css .= ( $instance['heading_group']['textcolor'] ) ? 'color:' . $instance['heading_group']['textcolor'] . ';' : '';
if ( $instance['heading_group']['font_heading'] == 'custom' ) {
	$css .= ( $instance['heading_group']['custom_font_heading']['custom_font_size'] ) ? 'font-size:' . $instance['heading_group']['custom_font_heading']['custom_font_size'] . 'px;line-height:' . $instance['heading_group']['custom_font_heading']['custom_font_size'] . 'px;' : '';
	$css .= ( $instance['heading_group']['custom_font_heading']['custom_font_weight'] ) ? 'font-weight:' . $instance['heading_group']['custom_font_heading']['custom_font_weight'] . 'px' : '';
}
$css = ( $css ) ? 'style="' . $css . '"' : '';
//end css header
$title_css .= ( $instance['t_config']['title_color'] ) ? 'style="color:' . $instance['t_config']['title_color'] . '"' : '';
$meta_css .= ( $instance['t_config']['meta_color'] ) ? 'style="color:' . $instance['t_config']['meta_color'] . '"' : '';
//end style
if ( $instance['heading_group']['title'] ) {
	echo '<' . $instance['heading_group']['size'] . ' ' . $css . ' class="widget-title">' . $instance['heading_group']['title'] . '</' . $instance['heading_group']['size'] . '>';
}

if ( $recent_comments ) {
	echo '<div class="recent-comments' . $css_animation . '">';
	echo '<ul>';
	foreach ( $recent_comments as $recent_comment ) {
		if ( $recent_comment->user_id ) {
			$link = apply_filters( 'learn_press_instructor_profile_link', '#', $recent_comment->user_id, get_the_ID() );
		} else {
			$link = $recent_comment->comment_author_url;
		}
		echo '<li>';
		echo '<div class="comment-author"><span class="avatar">' . get_avatar( $recent_comment->user_id, 32 ) . '</span>';
		echo '<a href="' . $link . '">' . $recent_comment->comment_author . '</a></div>';
		$comment = $recent_comment->comment_content;
		$comment = ( strlen( $comment ) > 69 ) ? substr( $comment, 0, 60 ) . '...' : $comment;
		echo '<div class="comment-content">' . $comment . '</div>';
		echo '<div class="comment-post"><a href="' . get_the_permalink( $recent_comment->comment_post_ID ) . '">' . get_the_title( $recent_comment->comment_post_ID ) . '</div>';
		echo '</li>';
		echo '<hr>';
	}
	echo '</ul>';
	echo '</div>';
}

