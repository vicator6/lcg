<?php

/**
 * Animation
 *
 * @param $css_animation
 *
 * @return string
 */
function thim_getCSSAnimation( $css_animation ) {
	$output = '';
	if ( $css_animation != '' ) {
		wp_enqueue_script( 'thim-waypoints' );
		$output = ' wpb_animate_when_almost_visible wpb_' . $css_animation;
	}

	return $output;
}

/**
 * Custom excerpt
 *
 * @param $limit
 *
 * @return array|mixed|string|void
 */
function thim_excerpt( $limit ) {
	$excerpt = explode( ' ', get_the_excerpt(), $limit );
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( " ", $excerpt ) . '...';
	} else {
		$excerpt = implode( " ", $excerpt );
	}
	$excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );

	return '<p>' . $excerpt . '</p>';
}

/**
 * Display breadcrumbs
 */
if ( ! function_exists( 'thim_breadcrumbs' ) ) {
	function thim_breadcrumbs() {

		// Do not display on the homepage
		if ( is_front_page() || is_404() ) {
			return;
		}

		// Get the query & post information
		global $post;
		$categories = get_the_category();

		// Build the breadcrums
		echo '<ul itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList" id="breadcrumbs" class="breadcrumbs">';


		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_html( get_home_url() ) . '" title="' . esc_attr__( 'Home', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Home', 'eduma' ) . '</span></a></li>';

		if ( is_home() ) {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html__( 'Blog', 'eduma' ) . '</span></li>';
		}

		if ( is_single() ) {
			if ( get_post_type() == 'tp_event' ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_post_type_archive_link( 'tp_event' ) ) . '" title="' . esc_attr__( 'Events', 'eduma' ) . '"><span itemprop="name">' . esc_html__( 'Events', 'eduma' ) . '</span></a></li>';
			}
			// Single post (Only display the first category)
			if ( isset( $categories[0] ) ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" title="' . esc_attr( $categories[0]->cat_name ) . '"><span itemprop="name">' . esc_html( $categories[0]->cat_name ) . '</span></a></li>';
			}
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '">' . esc_html( get_the_title() ) . '</span></li>';

		} else if ( is_category() ) {

			// Category page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">' . esc_html( $categories[0]->cat_name ) . '</span></li>';

		} else if ( is_page() ) {

			// Standard page
			if ( $post->post_parent ) {

				// If child page, get parents
				$anc = get_post_ancestors( $post->ID );

				// Get parents in the right order
				$anc = array_reverse( $anc );

				// Parent page loop
				foreach ( $anc as $ancestor ) {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( $ancestor ) ) . '" title="' . esc_attr( get_the_title( $ancestor ) ) . '"><span itemprop="name">' . esc_html( get_the_title( $ancestor ) ) . '</span></a></li>';
				}
			}

			// Current page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_title() ) . '"> ' . esc_html( get_the_title() ) . '</span></li>';


		} else if ( is_tag() ) {

			// Display the tag name
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( single_term_title( '', false ) ) . '">' . esc_html( single_term_title( '', false ) ) . '</span></li>';

		} elseif ( is_day() ) {

			// Day archive

			// Year link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '" title="' . esc_attr( get_the_time( 'Y' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></a></li>';

			// Month link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '" title="' . esc_attr( get_the_time( 'M' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></a></li>';

			// Day display
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'jS' ) ) . '"> ' . esc_html( get_the_time( 'jS' ) ) . ' ' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></li>';

		} else if ( is_month() ) {

			// Month Archive

			// Year link
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '" title="' . esc_attr( get_the_time( 'Y' ) ) . '"><span itemprop="name">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></a></li>';

			// Month display
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'M' ) ) . '">' . esc_html( get_the_time( 'M' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></li>';

		} else if ( is_year() ) {

			// Display year archive
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( get_the_time( 'Y' ) ) . '">' . esc_html( get_the_time( 'Y' ) ) . ' ' . esc_html__( 'Archives', 'eduma' ) . '</span></li>';

		} else if ( is_author() ) {

			// Auhor archive

			// Get the author information
			global $author;
			$userdata = get_userdata( $author );

			// Display author name
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr( $userdata->display_name ) . '">' . esc_attr__( 'Author', 'eduma' ) . ' ' . esc_html( $userdata->display_name ) . '</span></li>';

		} else if ( get_query_var( 'paged' ) ) {

			// Paginated archives
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Page', 'eduma' ) . ' ' . get_query_var( 'paged' ) . '">' . esc_html__( 'Page', 'eduma' ) . ' ' . esc_html( get_query_var( 'paged' ) ) . '</span></li>';

		} else if ( is_search() ) {

			// Search results page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Search results for:', 'eduma' ) . ' ' . esc_attr( get_search_query() ) . '">' . esc_html__( 'Search results for:', 'eduma' ) . ' ' . esc_html( get_search_query() ) . '</span></li>';

		} elseif ( is_404() ) {
			// 404 page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( '404 Page', 'eduma' ) . '">' . esc_html__( '404 Page', 'eduma' ) . '</span></li>';
		} elseif ( is_archive() ) {
			if ( get_post_type() == "tp_event" ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . esc_attr__( 'Events', 'eduma' ) . '">' . esc_html__( 'Events', 'eduma' ) . '</span></li>';
			}
		}

		echo '</ul>';
	}
}

/**
 * Get related posts
 *
 * @param     $post_id
 * @param int $number_posts
 *
 * @return WP_Query
 */
function thim_get_related_posts( $post_id, $number_posts = - 1 ) {
	$query = new WP_Query();
	$args  = '';
	if ( $number_posts == 0 ) {
		return $query;
	}
	$args  = wp_parse_args( $args, array(
		'posts_per_page'      => $number_posts,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => 0,
		'meta_key'            => '_thumbnail_id',
		'category__in'        => wp_get_post_categories( $post_id )
	) );
	$query = new WP_Query( $args );

	return $query;
}

// bbPress
function thim_use_bbpress() {
	if ( function_exists( 'is_bbpress' ) ) {
		return is_bbpress();
	} else {
		return false;
	}
}

/************ List Comment ***************/
if ( ! function_exists( 'thim_comment' ) ) {
	function thim_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		//extract( $args, EXTR_SKIP );
		if ( 'div' == $args['style'] ) {
			$tag       = 'div';
			$add_below = 'comment';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo ent2ncr( $tag . ' ' ) ?><?php comment_class( 'description_comment' ) ?> id="comment-<?php comment_ID() ?>">
		<div class="wrapper-comment">
			<?php
			if ( $args['avatar_size'] != 0 ) {
				echo '<div class="avatar">';
				echo get_avatar( $comment, $args['avatar_size'] );
				echo '</div>';
			}
			?>
			<div class="comment-right">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'eduma' ) ?></em>
				<?php endif; ?>

				<div class="comment-extra-info">
					<div
						class="author"><span class="author-name"><?php echo get_comment_author_link(); ?></span></div>
					<div class="date" itemprop="commentTime">
						<?php printf( get_comment_date(), get_comment_time() ) ?></div>
					<?php edit_comment_link( esc_html__( 'Edit', 'eduma' ), '', '' ); ?>

					<?php comment_reply_link( array_merge( $args, array(
						'add_below' => $add_below,
						'depth'     => $depth,
						'max_depth' => $args['max_depth']
					) ) ) ?>
				</div>

				<div class="content-comment">
					<?php comment_text() ?>
				</div>
			</div>
		</div>
		<?php
	}
}

// dislay setting layout
require THIM_DIR . 'inc/wrapper-before-after.php';

/**
 * @param $mtb_setting
 *
 * @return mixed
 */
function thim_mtb_setting_after_created( $mtb_setting ) {
	$mtb_setting->removeOption( array( 11 ) );
	$option_name_space = $mtb_setting->owner->optionNamespace;

	$settings   = array(
		'name'      => esc_html__( 'Color Sub Title', 'eduma' ),
		'id'        => 'mtb_color_sub_title',
		'type'      => 'color-opacity',
		'desc'      => ' ',
		'row_class' => 'child_of_' . $option_name_space . '_mtb_using_custom_heading thim_sub_option',
	);
	$settings_1 = array(
		'name' => esc_html__( 'No Padding Content', 'eduma' ),
		'id'   => 'mtb_no_padding',
		'type' => 'checkbox',
		'desc' => ' ',
	);

	$mtb_setting->insertOptionBefore( $settings, 11 );
	$mtb_setting->insertOptionBefore( $settings_1, 16 );

	return $mtb_setting;
}

add_filter( 'thim_mtb_setting_after_created', 'thim_mtb_setting_after_created', 10, 2 );


/**
 * @param $tabs
 *
 * @return array
 */
function thim_widget_group( $tabs ) {
	$tabs[] = array(
		'title'  => esc_html__( 'Thim Widget', 'eduma' ),
		'filter' => array(
			'groups' => array( 'thim_widget_group' )
		)
	);

	return $tabs;
}

add_filter( 'siteorigin_panels_widget_dialog_tabs', 'thim_widget_group', 19 );

/**
 * @param $fields
 *
 * @return mixed
 */
function thim_row_style_fields( $fields ) {
	$fields['parallax'] = array(
		'name'        => esc_html__( 'Parallax', 'eduma' ),
		'type'        => 'checkbox',
		'group'       => 'design',
		'description' => esc_html__( 'If enabled, the background image will have a parallax effect.', 'eduma' ),
		'priority'    => 8,
	);

	return $fields;
}

//add_filter( 'siteorigin_panels_row_style_fields', 'thim_row_style_fields' );

/**
 * @param $attributes
 * @param $args
 *
 * @return mixed
 */
function thim_row_style_attributes( $attributes, $args ) {
	if ( ! empty( $args['parallax'] ) ) {
		array_push( $attributes['class'], 'article__parallax' );
	}

	if ( ! empty( $args['row_stretch'] ) && $args['row_stretch'] == 'full-stretched' ) {
		array_push( $attributes['class'], 'thim-fix-stretched' );
	}

	return $attributes;
}

add_filter( 'siteorigin_panels_row_style_attributes', 'thim_row_style_attributes', 10, 2 );

/**
 * @return string
 */
function thim_excerpt_length() {
	$theme_options_data = thim_options_data();
	if ( isset( $theme_options_data['thim_archive_excerpt_length'] ) ) {
		$length = $theme_options_data['thim_archive_excerpt_length'];
	} else {
		$length = '50';
	}

	return $length;
}

add_filter( 'excerpt_length', 'thim_excerpt_length', 999 );

/**
 * @param $text
 *
 * @return mixed|string|void
 */
function thim_wp_new_excerpt( $text ) {
	if ( $text == '' ) {
		$text           = get_the_content( '' );
		$text           = strip_shortcodes( $text );
		$text           = apply_filters( 'the_content', $text );
		$text           = str_replace( ']]>', ']]>', $text );
		$text           = strip_tags( $text );
		$text           = nl2br( $text );
		$excerpt_length = apply_filters( 'excerpt_length', 55 );
		$words          = explode( ' ', $text, $excerpt_length + 1 );
		if ( count( $words ) > $excerpt_length ) {
			array_pop( $words );
			array_push( $words, '' );
			$text = implode( ' ', $words );
		}
	}

	return $text;
}

remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
add_filter( 'get_the_excerpt', 'thim_wp_new_excerpt' );

/**
 * Social sharing
 */
if ( ! function_exists( 'thim_social_share' ) ) {
	function thim_social_share() {
		$theme_options_data = thim_options_data();

		$facebook  = isset( $theme_options_data['thim_sharing_facebook'] ) && $theme_options_data['thim_sharing_facebook'] ? $theme_options_data['thim_sharing_facebook'] : null;
		$twitter   = isset( $theme_options_data['thim_sharing_twitter'] ) && $theme_options_data['thim_sharing_twitter'] ? $theme_options_data['thim_sharing_twitter'] : null;
		$pinterest = isset( $theme_options_data['thim_sharing_pinterest'] ) && $theme_options_data['thim_sharing_pinterest'] ? $theme_options_data['thim_sharing_pinterest'] : null;
		$google    = isset( $theme_options_data['thim_sharing_google'] ) && $theme_options_data['thim_sharing_google'] ? $theme_options_data['thim_sharing_google'] : null;

		if ( $facebook || $twitter || $pinterest || $google ) {
			echo '<ul class="thim-social-share">';
			do_action( 'thim_before_social_list' );
			if ( $facebook ) {

				echo '<li class="facebook">
						<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, \'script\', \'facebook-jssdk\'));</script>
						<div class="fb-share-button" data-href="' . esc_url( get_the_permalink() ) . '" data-layout="button_count"></div>
					</li>';
			}
			if ( $google ) {
				echo '<li class="google-plus">
						<script src="' . esc_url( "https://apis.google.com/js/platform.js" ) . '" async defer></script>
						<div class="g-plusone" data-width="200"></div>
					</li>';
			}
			if ( $twitter ) {
				echo '<li class="twitter">
						<a href="' . esc_url( get_permalink() ) . '" class="twitter-share-button">Tweet</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>
					</li>';
			}

			if ( $pinterest ) {
				echo '<li class="pinterest">
						<a data-pin-do="buttonBookmark"  href="' . esc_url( "//www.pinterest.com/pin/create/button/" ) . '"><img src="' . esc_url( "//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" ) . '" alt="' . esc_html__( "Pinterest", "eduma" ) . '"/></a>
						<script async defer src="//assets.pinterest.com/js/pinit.js"></script>
					</li>';
			}
			do_action( 'thim_after_social_list' );

			echo '</ul>';
		}

	}
}
add_action( 'thim_social_share', 'thim_social_share' );


/**
 * Display favicon
 */
function thim_favicon() {
	if ( function_exists( 'wp_site_icon' ) ) {
		if ( function_exists( 'has_site_icon' ) ) {
			if ( ! has_site_icon() ) {
				// Icon default
				$thim_favicon_src = get_template_directory_uri() . "/images/favicon.png";
				echo '<link rel="shortcut icon" href="' . esc_url( $thim_favicon_src ) . '" type="image/x-icon" />';

				return;
			}

			return;
		}
	}

	/**
	 * Support WordPress < 4.3
	 */
	$theme_options_data = thim_options_data();
	$thim_favicon_src   = '';
	if ( isset( $theme_options_data['thim_favicon'] ) ) {
		$thim_favicon       = $theme_options_data['thim_favicon'];
		$favicon_attachment = wp_get_attachment_image_src( $thim_favicon, 'full' );
		$thim_favicon_src   = $favicon_attachment[0];
	}
	if ( ! $thim_favicon_src ) {
		$thim_favicon_src = get_template_directory_uri() . "/images/favicon.png";
	}
	echo '<link rel="shortcut icon" href="' . esc_url( $thim_favicon_src ) . '" type="image/x-icon" />';
}

add_action( 'wp_head', 'thim_favicon' );

/**
 * Redirect to custom login page
 */
function thim_login_failed() {
	wp_redirect( add_query_arg( 'result', 'failed', thim_get_login_page_url() ) );
	exit;
}

add_action( 'wp_login_failed', 'thim_login_failed', 1000 );

/**
 * Redirect to custom login page
 *
 * @param $user
 * @param $username
 * @param $password
 */
function thim_verify_username_password( $user, $username, $password ) {

	global $wpdb;
	$page = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT p.ID FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
			WHERE 	pm.meta_key = %s
			AND 	pm.meta_value = %s
			AND		p.post_type = %s
			AND		p.post_status = %s",
			'thim_login_page',
			'1',
			'page',
			'publish'
		)
	);
	if ( empty( $page[0] ) || ! thim_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) ) {
		return $user;
	} else {
		$url = null;
		if ( $username == '' && $password == '' ) {
			$url = ( add_query_arg( 'result', 'empty', thim_get_login_page_url() ) );
		} elseif ( $username == '' || $password == '' ) {
			$url = ( add_query_arg( 'result', 'failed', thim_get_login_page_url() ) );
		}
		if ( $url ) {
			if ( ! empty( $_REQUEST['redirect_to'] ) ) {
				$url = add_query_arg( 'redirect_to', urlencode( $_REQUEST['redirect_to'] ), $url );
			}
			wp_redirect( $url );
		}
	}
}

//add_filter( 'authenticate', 'thim_verify_username_password', 1, 3 );

/**
 * Filter register link
 *
 * @param $register_url
 *
 * @return string|void
 */
function thim_register_url( $url ) {
	$url = add_query_arg( 'action', 'register', thim_get_login_page_url() );

	return $url;
}

add_filter( 'register_url', 'thim_register_url', 1000 );

/**
 * Register failed
 *
 * @param $sanitized_user_login
 * @param $user_email
 * @param $errors
 */
function thim_register_failed( $sanitized_user_login, $user_email, $errors ) {

	$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

	if ( $errors->get_error_code() ) {

		//setup your custom URL for redirection
		$url = add_query_arg( 'action', 'register', thim_get_login_page_url() );

		foreach ( $errors->errors as $e => $m ) {
			$url = add_query_arg( $e, '1', $url );
		}
		wp_redirect( $url );
		exit;
	}
}

add_action( 'register_post', 'thim_register_failed', 99, 3 );

/**
 * Redirect to custom register page in case multi sites
 *
 * @param $url
 *
 * @return mixed
 */
function thim_multisite_register_redirect( $url ) {

	if ( is_multisite() ) {
		$url = add_query_arg( 'action', 'register', thim_get_login_page_url() );
	}

	$user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';
	$user_email = isset( $_POST['user_email'] ) ? $_POST['user_email'] : '';
	$errors     = register_new_user( $user_login, $user_email );
	if ( ! is_wp_error( $errors ) ) {
		$redirect_to = ! empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : 'wp-login.php?checkemail=registered';
		wp_safe_redirect( $redirect_to );
		exit();
	}

	return $url;
}

add_filter( 'wp_signup_location', 'thim_multisite_register_redirect' );


function thim_multisite_signup_redirect() {
	if ( is_multisite() ) {
		wp_redirect( wp_registration_url() );
		die();
	}
}

add_action( 'signup_header', 'thim_multisite_signup_redirect' );


/**
 * Filter lost password link
 *
 * @param $url
 *
 * @return string
 */
function thim_lost_password_url( $url ) {
	$url = add_query_arg( 'action', 'lostpassword', thim_get_login_page_url() );

	return $url;
}

add_filter( 'lostpassword_url', 'thim_lost_password_url', 999 );


/**
 * Add lost password link into login form
 *
 * @param $content
 * @param $args
 *
 * @return string
 */
function thim_add_lost_password_link( $content, $args ) {
	$content = '<a class="lost-pass-link" href="' . wp_lostpassword_url() . '" title="' . esc_attr__( 'Lost Password', 'eduma' ) . '">' . esc_html__( 'Lost your password?', 'eduma' ) . '</a>';

	return $content;
}

add_filter( 'login_form_middle', 'thim_add_lost_password_link', 999 );

/**
 * Register failed
 */
function thim_reset_password_failed() {

	//setup your custom URL for redirection
	$url = add_query_arg( 'action', 'lostpassword', thim_get_login_page_url() );

	if ( empty( $_POST['user_login'] ) ) {
		$url = add_query_arg( 'empty', '1', $url );
		wp_redirect( $url );
		exit;
	} elseif ( strpos( $_POST['user_login'], '@' ) ) {
		$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
		if ( empty( $user_data ) ) {
			$url = add_query_arg( 'user_not_exist', '1', $url );
			wp_redirect( $url );
			exit;
		}
	} elseif ( ! username_exists( $_POST['user_login'] ) ) {
		$url = add_query_arg( 'user_not_exist', '1', $url );
		wp_redirect( $url );
		exit;
	}


}

add_action( 'lostpassword_post', 'thim_reset_password_failed', 999 );

/**
 * Get login page url
 *
 * @return false|string
 */
function thim_get_login_page_url() {
	global $wpdb;
	$page = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT p.ID FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
			WHERE 	pm.meta_key = %s
			AND 	pm.meta_value = %s
			AND		p.post_type = %s
			AND		p.post_status = %s",
			'thim_login_page',
			'1',
			'page',
			'publish'
		)
	);
	if ( empty( $page[0] ) || ! thim_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) ) {
		return wp_login_url();
	} else {
		return get_permalink( $page[0] );
	}
}

/**
 * Display feature image
 *
 * @param $attachment_id
 * @param $size_type
 * @param $width
 * @param $height
 * @param $alt
 * @param $title
 *
 * @return string
 */
function thim_get_feature_image( $attachment_id, $size_type = null, $width = null, $height = null, $alt = null, $title = null ) {

	if ( ! $size_type ) {
		$size_type = 'full';
	}
	$src   = wp_get_attachment_image_src( $attachment_id, $size_type );
	$style = '';
	if ( ! $src ) {
		// Get demo image
		global $wpdb;
		$attachment_id = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT p.ID FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
				WHERE 	pm.meta_key = %s
				AND 	pm.meta_value LIKE %s",
				'_wp_attached_file',
				'%demo_image.jpg'
			)
		);

		if ( empty( $attachment_id[0] ) ) {
			return;
		}

		$attachment_id = $attachment_id[0];
		$src           = wp_get_attachment_image_src( $attachment_id, 'full' );

	}

	if ( $width && $height ) {

		if ( $src[1] >= $width || $src[2] >= $height ) {

			$crop = ( $src[1] >= $width && $src[2] >= $height ) ? true : false;

			$src[0] = aq_resize( $src[0], $width, $height, $crop );

		}
		$style = ' width="' . $width . '" height="' . $height . '"';
	}

	if ( ! $alt ) {
		$alt = get_the_title( $attachment_id );
	}

	if ( ! $title ) {
		$title = get_the_title( $attachment_id );
	}

	return '<img src="' . esc_url( $src[0] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" ' . $style . '>';

}

function thim_get_feature_image_lazyload( $attachment_id, $size_type = null, $width = null, $height = null, $alt = null, $title = null ) {
	if ( ! $size_type ) {
		$size_type = 'full';
	}
	$src   = wp_get_attachment_image_src( $attachment_id, $size_type );
	$style = '';
	if ( ! $src ) {
		// Get demo image
		global $wpdb;
		$attachment_id = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT p.ID FROM $wpdb->posts AS p INNER JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id
				WHERE 	pm.meta_key = %s
				AND 	pm.meta_value LIKE %s",
				'_wp_attached_file',
				'%demo_image.jpg'
			)
		);

		if ( empty( $attachment_id[0] ) ) {
			return;
		}

		$attachment_id = $attachment_id[0];
		$src           = wp_get_attachment_image_src( $attachment_id, 'full' );

	}

	if ( $width && $height ) {

		if ( $src[1] >= $width || $src[2] >= $height ) {

			$crop = ( $src[1] >= $width && $src[2] >= $height ) ? true : false;

			$src[0] = aq_resize( $src[0], $width, $height, $crop );

		}
		$style = ' width="' . $width . '" height="' . $height . '"';
	}

	if ( ! $alt ) {
		$alt = get_the_title( $attachment_id );
	}

	if ( ! $title ) {
		$title = get_the_title( $attachment_id );
	}

	return '<img class="lazyOwl" data-src="' . esc_url( $src[0] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" ' . $style . '>';
}


/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function thim_event_add_meta_boxes() {

	if ( ! post_type_exists( 'tp_event' ) || ! post_type_exists( 'our_team' ) ) {
		return;
	}
	add_meta_box(
		'thim_organizers',
		esc_html__( 'Organizers', 'eduma' ),
		'thim_event_meta_boxes_callback',
		'tp_event'
	);
}

add_action( 'add_meta_boxes', 'thim_event_add_meta_boxes' );

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function thim_event_meta_boxes_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'thim_event_save_meta_boxes', 'thim_event_meta_boxes_nonce' );

	// Get all team
	$team = new WP_Query( array(
		'post_type'           => 'our_team',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'posts_per_page'      => - 1
	) );

	if ( empty( $team->post_count ) ) {
		echo '<p>' . esc_html__( 'No members exists. You can create a member data from', 'eduma' ) . ' <a target="_blank" href="' . admin_url( 'post-new.php?post_type=our_team' ) . '">Our Team</a></p>';

		return;
	}

	echo '<label for="thim_event_members">';
	esc_html_e( 'Get Members', 'eduma' );
	echo '</label> ';
	echo '<select id="thim_event_members" name="thim_event_members[]" multiple>';
	if ( isset( $team->posts ) ) {
		$team = $team->posts;
		foreach ( $team as $member ) {
			echo '<option value="' . esc_attr( $member->ID ) . '">' . get_the_title( $member->ID ) . '</option>';
		}
	}
	echo '</select>';
	echo '<span>';
	esc_html_e( 'Hold down the Ctrl (Windows) / Command (Mac) button to select multiple options.', 'eduma' );
	echo '</span><br>';
	wp_reset_postdata();

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$members = get_post_meta( $post->ID, 'thim_event_members', true );
	echo '<p>' . esc_html__( 'Current Members: ', 'eduma' );
	if ( ! $members ) {
		echo esc_html__( 'None', 'eduma' ) . '</p>';
	} else {
		$total = count( $members );
		foreach ( $members as $key => $id ) {
			echo '<strong><a target="_blank" href="' . get_edit_post_link( $id ) . '">' . get_the_title( $id ) . '</a></strong>';
			if ( $key != count( $total ) ) {
				echo ', ';
			}
		}
	}
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function thim_event_save_meta_boxes( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */

	// Check if our nonce is set.
	if ( ! isset( $_POST['thim_event_meta_boxes_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['thim_event_meta_boxes_nonce'], 'thim_event_save_meta_boxes' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'tp_event' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

	}

	/* OK, it's safe for us to save the data now. */

	// Make sure that it is set.
	if ( ! isset( $_POST['thim_event_members'] ) ) {
		return;
	}

	// Update the meta field in the database.
	update_post_meta( $post_id, 'thim_event_members', $_POST['thim_event_members'] );
}

add_action( 'save_post', 'thim_event_save_meta_boxes' );


/**
 * Change default comment fields
 *
 * @param $field
 *
 * @return string
 */
function thim_new_comment_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? 'aria-required=true' : '' );

	$fields = array(
		'author' => '<p class="comment-form-author">' . '<input placeholder="' . esc_attr__( 'Name', 'eduma' ) . ( $req ? ' *' : '' ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" ' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email">' . '<input placeholder="' . esc_attr__( 'Email', 'eduma' ) . ( $req ? ' *' : '' ) . '" id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" ' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url">' . '<input placeholder="' . esc_attr__( 'Website', 'eduma' ) . '" id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	return $fields;
}

add_filter( 'comment_form_default_fields', 'thim_new_comment_fields', 1 );


/**
 * Remove Emoji scripts
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Optimize script files
 */
function thim_optimize_scripts() {
	global $wp_scripts;
	if ( ! is_a( $wp_scripts, 'WP_Scripts' ) ) {
		return;
	}
	foreach ( $wp_scripts->registered as $handle => $script ) {
		$wp_scripts->registered[ $handle ]->ver = null;
	}
}

add_action( 'wp_print_scripts', 'thim_optimize_scripts', 999 );
add_action( 'wp_print_footer_scripts', 'thim_optimize_scripts', 999 );

/**
 * Optimize style files
 */
function thim_optimize_styles() {
	global $wp_styles;
	if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
		return;
	}
	foreach ( $wp_styles->registered as $handle => $style ) {
		$wp_styles->registered[ $handle ]->ver = null;
	}
}

add_action( 'admin_print_styles', 'thim_optimize_styles', 999 );
add_action( 'wp_print_styles', 'thim_optimize_styles', 999 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 *
 * @return array
 */
function thim_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}

add_filter( 'wp_page_menu_args', 'thim_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function thim_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}

add_filter( 'body_class', 'thim_body_classes' );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function thim_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'thim_setup_author' );

function thim_add_event_admin_styles() {
	?>
	<style type="text/css">
		#thim_event_members {
			min-height: 200px;
		}
	</style>
	<?php
}

add_action( 'admin_print_styles', 'thim_add_event_admin_styles' );

/**
 * Check a plugin activate
 *
 * @param $plugin
 *
 * @return bool
 */
function thim_plugin_active( $plugin ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( $plugin ) ) {
		return true;
	}

	return false;
}

/**
 * Get data customizer
 *
 * @return array|void
 */
function thim_options_data() {
	global $theme_options_data;
	$theme_options_data = get_theme_mods();

	return $theme_options_data;
}

/**
 * Custom WooCommerce breadcrumbs
 *
 * @return array
 */
function thim_woocommerce_breadcrumbs() {
	return array(
		'delimiter'   => '',
		'wrap_before' => '<ul class="breadcrumbs" id="breadcrumbs" itemtype="http://schema.org/BreadcrumbList" itemscope="" itemprop="breadcrumb">',
		'wrap_after'  => '</ul>',
		'before'      => '<li itemtype="http://schema.org/ListItem" itemscope="" itemprop="itemListElement">',
		'after'       => '</li>',
		'home'        => esc_html__( 'Home', 'eduma' ),
	);
}

add_filter( 'woocommerce_breadcrumb_defaults', 'thim_woocommerce_breadcrumbs' );

/**
 * Display post thumbnail by default
 *
 * @param $size
 */
function thim_default_get_post_thumbnail( $size ) {

	if ( thim_plugin_active( 'thim-framework/tp-framework.php' ) ) {
		return;
	}

	if ( get_the_post_thumbnail( get_the_ID(), $size ) ) {
		?>
		<div class='post-formats-wrapper'>
			<a class="post-image" href="<?php echo esc_url( get_permalink() ); ?>">
				<?php echo get_the_post_thumbnail( get_the_ID(), $size ); ?>
			</a>
		</div>
		<?php
	}
}

add_action( 'thim_entry_top', 'thim_default_get_post_thumbnail', 20 );


/**
 * Set unlimit events in archive
 *
 * @param $query
 */
function thim_event_post_filter( $query ) {
	global $wp_query;

	if ( is_post_type_archive( 'tp_event' ) && 'tp_event' == $query->get( 'post_type' ) ) {
		$query->set( 'posts_per_page', - 1 );

		return;
	}
}

add_action( 'pre_get_posts', 'thim_event_post_filter' );


function thim_start_widget_element_content( $content, $panels_data, $post_id ) {
	global $siteorigin_panels_inline_css;

	if ( ! empty( $siteorigin_panels_inline_css[ $post_id ] ) ) {
		$content = '<style scoped>' . ( $siteorigin_panels_inline_css[ $post_id ] ) . '</style>' . $content;
	}

	return $content;
}

remove_action( 'wp_footer', 'siteorigin_panels_print_inline_css' );
add_filter( 'siteorigin_panels_before_content', 'thim_start_widget_element_content', 10, 3 );

//Override ajax-loader contact form
function thim_wpcf7_ajax_loader() {
	return THIM_URI . '/images/ajax-loader.gif';
}

add_filter( 'wpcf7_ajax_loader', 'thim_wpcf7_ajax_loader' );

function thim_ssl_secure_url( $sources ) {
	$scheme = parse_url( site_url(), PHP_URL_SCHEME );
	if ( 'https' == $scheme ) {
		if ( stripos( $sources, 'http://' ) === 0 ) {
			$sources = 'https' . substr( $sources, 4 );
		}

		return $sources;
	}

	return $sources;
}

function thim_ssl_secure_image_srcset( $sources ) {
	$scheme = parse_url( site_url(), PHP_URL_SCHEME );
	if ( 'https' == $scheme ) {
		foreach ( $sources as &$source ) {
			if ( stripos( $source['url'], 'http://' ) === 0 ) {
				$source['url'] = 'https' . substr( $source['url'], 4 );
			}
		}

		return $sources;
	}

	return $sources;
}

//add_filter('script_loader_src', 'thim_ssl_secure_url');
//add_filter('style_loader_src', 'thim_ssl_secure_url');
add_filter( 'wp_calculate_image_srcset', 'thim_ssl_secure_image_srcset' );
add_filter( 'wp_get_attachment_url', 'thim_ssl_secure_url', 1000 );
add_filter( 'image_widget_image_url', 'thim_ssl_secure_url' );


/**
 * Testing with CF7 scripts
 */
if ( ! function_exists( 'thim_disable_cf7_cache' ) ) {
	function thim_disable_cf7_cache() {
		global $wp_scripts;
		if ( ! empty( $wp_scripts->registered['contact-form-7'] ) ) {
			if ( ! empty( $wp_scripts->registered['contact-form-7']->extra['data'] ) ) {
				$localize                                                = $wp_scripts->registered['contact-form-7']->extra['data'];
				$localize                                                = str_replace( '"cached":"1"', '"cached":0', $localize );
				$wp_scripts->registered['contact-form-7']->extra['data'] = $localize;
			}
		}
	}
}

add_action( 'wpcf7_enqueue_scripts', 'thim_disable_cf7_cache' );


//Function thim_related_our_team
function thim_related_our_team( $post_id, $number_posts = - 1 ) {
	$query = new WP_Query();
	$args  = '';
	if ( $number_posts == 0 ) {
		return $query;
	}
	$args  = wp_parse_args( $args, array(
		'posts_per_page'      => $number_posts,
		'post_type'           => 'our_team',
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true
	) );
	$query = new WP_Query( $args );

	return $query;
}


/**
 * Process events order
 */

add_filter( 'posts_fields', 'thim_event_posts_fields' );
add_filter( 'posts_join_paged', 'thim_event_posts_join_paged' );
add_filter( 'posts_where_paged', 'thim_event_posts_where_paged' );
add_filter( 'posts_orderby', 'thim_event_posts_orderby' );

function thim_is_events_archive() {
	global $pagenow, $post_type;
	if ( ! is_post_type_archive( 'tp_event' ) || ! is_main_query() ) {
		return false;
	}

	return true;
}

function thim_event_posts_fields( $fields ) {
	if ( ! thim_is_events_archive() ) {
		return $fields;
	}

	$fields = " DISTINCT " . $fields;
	$fields .= ', concat( str_to_date( pm1.meta_value, \'%m/%d/%Y\' ), \' \', str_to_date(pm2.meta_value, \'%h:%i %p\' ) ) as start_date_time ';

	return $fields;
}

function thim_event_posts_join_paged( $join ) {
	if ( ! thim_is_events_archive() ) {
		return $join;
	}

	global $wpdb;
	$join .= " LEFT JOIN {$wpdb->postmeta} pm1 ON pm1.post_id = {$wpdb->posts}.ID AND pm1.meta_key = 'tp_event_date_start'";
	$join .= " LEFT JOIN {$wpdb->postmeta} pm2 ON pm2.post_id = {$wpdb->posts}.ID AND pm2.meta_key = 'tp_event_time_start'";


	return $join;
}

function thim_event_posts_where_paged( $where ) {
	if ( ! thim_is_events_archive() ) {
		return $where;
	}

	return $where;
}

function thim_event_posts_orderby( $order_by_statement ) {

	if ( ! thim_is_events_archive() ) {
		return $order_by_statement;
	}
	$order_by_statement = "start_date_time ASC"; // ASC
	return $order_by_statement;
}

function thim_replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {

	$reset_link = add_query_arg(
		array(
			'action' => 'rp',
			'key'    => $key,
			'login'  => rawurlencode( $user_login )
		), thim_get_login_page_url()
	);

	// Create new message
	$message = __( 'Someone has requested a password reset for the following account:', 'eduma' ) . "\r\n\r\n";
	$message .= network_home_url( '/' ) . "\r\n\r\n";
	$message .= sprintf( __( 'Username: %s', 'eduma' ), $user_login ) . "\r\n\r\n";
	$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'eduma' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:', 'eduma' ) . "\r\n\r\n";
	$message .= '<' . $reset_link . ">\r\n";

	return $message;
}

add_filter( 'retrieve_password_message', 'thim_replace_retrieve_password_message', 10, 4 );


function thim_do_password_reset() {

	$login_page = thim_get_login_page_url();
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

		if ( ! isset( $_REQUEST['key'] ) || ! isset( $_REQUEST['login'] ) ) {
			return;
		}

		$key   = $_REQUEST['key'];
		$login = $_REQUEST['login'];

		$user = check_password_reset_key( $key, $login );

		if ( ! $user || is_wp_error( $user ) ) {
			if ( $user && $user->get_error_code() === 'expired_key' ) {
				wp_redirect( add_query_arg(
					array(
						'action'      => 'rp',
						'expired_key' => '1',
					), $login_page
				) );
			} else {
				wp_redirect( add_query_arg(
					array(
						'action'      => 'rp',
						'invalid_key' => '1',
					), $login_page
				) );
			}
			exit;
		}

		if ( isset( $_POST['password'] ) ) {

			if ( empty( $_POST['password'] ) ) {
				// Password is empty
				wp_redirect( add_query_arg(
					array(
						'action'           => 'rp',
						'key'              => $_REQUEST['key'],
						'login'            => $_REQUEST['login'],
						'invalid_password' => '1',
					), $login_page
				) );
				exit;
			}

			// Parameter checks OK, reset password
			reset_password( $user, $_POST['password'] );
			wp_redirect( add_query_arg(
				array(
					'result' => 'changed',
				), $login_page
			) );
		} else {
			_e( 'Invalid request.', 'eduma' );
		}

		exit;
	}
}

add_action( 'login_form_rp', 'thim_do_password_reset' );
add_action( 'login_form_resetpass', 'thim_do_password_reset' );

//Include iconmoon
function thim_icomoon( $args, $arr ) {
	$icons  = array(
		'home',
		'home2',
		'home3',
		'home4',
		'home5',
		'home6',
		'bathtub',
		'toothbrush',
		'bed',
		'couch',
		'chair',
		'city',
		'apartment',
		'pencil',
		'pencil2',
		'pen',
		'pencil3',
		'eraser',
		'pencil4',
		'pencil5',
		'feather',
		'feather2',
		'feather3',
		'pen2',
		'pen-add',
		'pen-remove',
		'vector',
		'pen3',
		'blog',
		'brush',
		'brush2',
		'spray',
		'paint-roller',
		'stamp',
		'tape',
		'desk-tape',
		'texture',
		'eye-dropper',
		'palette',
		'color-sampler',
		'bucket',
		'gradient',
		'gradient2',
		'magic-wand',
		'magnet',
		'pencil-ruler',
		'pencil-ruler2',
		'compass'
	,
		'aim',
		'gun',
		'bottle',
		'drop',
		'drop-crossed',
		'drop2',
		'snow',
		'snow2',
		'fire',
		'lighter',
		'knife',
		'dagger',
		'tissue',
		'toilet-paper',
		'poop',
		'umbrella',
		'umbrella2',
		'rain',
		'tornado',
		'wind',
		'fan',
		'contrast',
		'sun-small',
		'sun',
		'sun2',
		'moon',
		'cloud',
		'cloud-upload',
		'cloud-download',
		'cloud-rain',
		'cloud-hailstones',
		'cloud-snow',
		'cloud-windy',
		'sun-wind',
		'cloud-fog',
		'cloud-sun',
		'cloud-lightning',
		'cloud-sync',
		'cloud-lock',
		'cloud-gear',
		'cloud-alert',
		'cloud-check',
		'cloud-cross',
		'cloud-crossed',
		'cloud-database',
		'database',
		'database-add',
		'database-remove',
		'database-lock',
		'database-refresh',
		'database-check',
		'database-history',
		'database-upload',
		'database-download',
		'server',
		'shield',
		'shield-check',
		'shield-alert',
		'shield-cross',
		'lock',
		'rotation-lock',
		'unlock',
		'key',
		'key-hole',
		'toggle-off',
		'toggle-on',
		'cog',
		'cog2',
		'wrench',
		'screwdriver',
		'hammer-wrench',
		'hammer',
		'saw',
		'axe',
		'axe2',
		'shovel',
		'pickaxe',
		'factory',
		'factory2',
		'recycle',
		'trash',
		'trash2',
		'trash3',
		'broom',
		'game',
		'gamepad',
		'joystick',
		'dice',
		'spades',
		'diamonds',
		'clubs',
		'hearts',
		'heart',
		'star',
		'star-half',
		'star-empty',
		'flag',
		'flag2',
		'flag3',
		'mailbox-full',
		'mailbox-empty',
		'at-sign',
		'envelope',
		'envelope-open',
		'paperclip',
		'paper-plane',
		'reply',
		'reply-all',
		'inbox',
		'inbox2',
		'outbox',
		'box',
		'archive',
		'archive2',
		'drawers',
		'drawers2',
		'drawers3',
		'eye',
		'eye-crossed',
		'eye-plus',
		'eye-minus',
		'binoculars',
		'binoculars2',
		'hdd',
		'hdd-down',
		'hdd-up',
		'floppy-disk',
		'disc',
		'tape2',
		'printer',
		'shredder',
		'file-empty',
		'file-add',
		'file-check',
		'file-lock',
		'files',
		'copy',
		'compare',
		'folder',
		'folder-search',
		'folder-plus',
		'folder-minus',
		'folder-download',
		'folder-upload',
		'folder-star',
		'folder-heart',
		'folder-user',
		'folder-shared',
		'folder-music',
		'folder-picture',
		'folder-film',
		'scissors',
		'paste',
		'clipboard-empty',
		'clipboard-pencil',
		'clipboard-text',
		'clipboard-check',
		'clipboard-down',
		'clipboard-left',
		'clipboard-alert',
		'clipboard-user',
		'register',
		'enter',
		'exit',
		'papers',
		'news',
		'reading',
		'typewriter',
		'document',
		'document2',
		'graduation-hat',
		'license',
		'license2',
		'medal-empty',
		'medal-first',
		'medal-second',
		'medal-third',
		'podium',
		'trophy',
		'trophy2',
		'music-note',
		'music-note2',
		'music-note3',
		'playlist',
		'playlist-add',
		'guitar',
		'trumpet',
		'album',
		'shuffle',
		'repeat-one',
		'repeat',
		'headphones',
		'headset',
		'loudspeaker',
		'equalizer',
		'theater',
		'3d-glasses',
		'ticket',
		'presentation',
		'play',
		'film-play',
		'clapboard-play',
		'media',
		'film',
		'film2',
		'surveillance',
		'surveillance2',
		'camera',
		'camera-crossed',
		'camera-play',
		'time-lapse',
		'record',
		'camera2',
		'camera-flip',
		'panorama',
		'time-lapse2',
		'shutter',
		'shutter2',
		'face-detection',
		'flare',
		'convex',
		'concave',
		'picture',
		'picture2',
		'picture3',
		'pictures',
		'book',
		'audio-book',
		'book2',
		'bookmark',
		'bookmark2',
		'label',
		'library',
		'library2',
		'contacts',
		'profile',
		'portrait',
		'portrait2',
		'user',
		'user-plus',
		'user-minus',
		'user-lock',
		'users',
		'users2',
		'users-plus',
		'users-minus',
		'group-work',
		'woman',
		'man',
		'baby',
		'baby2',
		'baby3',
		'baby-bottle',
		'walk',
		'hand-waving',
		'jump',
		'run',
		'woman2',
		'man2',
		'man-woman',
		'height',
		'weight',
		'scale',
		'button',
		'bow-tie',
		'tie',
		'socks',
		'shoe',
		'shoes',
		'hat',
		'pants',
		'shorts',
		'flip-flops',
		'shirt',
		'hanger',
		'laundry',
		'store',
		'haircut',
		'store-24',
		'barcode',
		'barcode2',
		'barcode3',
		'cashier',
		'bag',
		'bag2',
		'cart',
		'cart-empty',
		'cart-full',
		'cart-plus',
		'cart-plus2',
		'cart-add',
		'cart-remove',
		'cart-exchange',
		'tag',
		'tags',
		'receipt',
		'wallet',
		'credit-card',
		'cash-dollar',
		'cash-euro',
		'cash-pound',
		'cash-yen',
		'bag-dollar',
		'bag-euro',
		'bag-pound',
		'bag-yen',
		'coin-dollar',
		'coin-euro',
		'coin-pound',
		'coin-yen',
		'calculator',
		'calculator2',
		'abacus',
		'vault',
		'telephone',
		'phone-lock',
		'phone-wave',
		'phone-pause',
		'phone-outgoing',
		'phone-incoming',
		'phone-in-out',
		'phone-error',
		'phone-sip',
		'phone-plus',
		'phone-minus',
		'voicemail',
		'dial',
		'telephone2',
		'pushpin',
		'pushpin2',
		'map-marker',
		'map-marker-user',
		'map-marker-down',
		'map-marker-check',
		'map-marker-crossed',
		'radar',
		'compass2',
		'map',
		'map2',
		'location',
		'road-sign',
		'calendar-empty',
		'calendar-check',
		'calendar-cross',
		'calendar-31',
		'calendar-full',
		'calendar-insert',
		'calendar-text',
		'calendar-user',
		'mouse',
		'mouse-left',
		'mouse-right',
		'mouse-both',
		'keyboard',
		'keyboard-up',
		'keyboard-down',
		'delete',
		'spell-check',
		'escape',
		'enter2',
		'screen',
		'aspect-ratio',
		'signal',
		'signal-lock',
		'signal-80',
		'signal-60',
		'signal-40',
		'signal-20',
		'signal-0',
		'signal-blocked',
		'sim',
		'flash-memory',
		'usb-drive',
		'phone',
		'smartphone',
		'smartphone-notification',
		'smartphone-vibration',
		'smartphone-embed',
		'smartphone-waves',
		'tablet',
		'tablet2',
		'laptop',
		'laptop-phone',
		'desktop',
		'launch',
		'new-tab',
		'window',
		'cable',
		'cable2',
		'tv',
		'radio',
		'remote-control',
		'power-switch',
		'power',
		'power-crossed',
		'flash-auto',
		'lamp',
		'flashlight',
		'lampshade',
		'cord',
		'outlet',
		'battery-power',
		'battery-empty',
		'battery-alert',
		'battery-error',
		'battery-low1',
		'battery-low2',
		'battery-low3',
		'battery-mid1',
		'battery-mid2',
		'battery-mid3',
		'battery-full',
		'battery-charging',
		'battery-charging2',
		'battery-charging3',
		'battery-charging4',
		'battery-charging5',
		'battery-charging6',
		'battery-charging7',
		'chip',
		'chip-x64',
		'chip-x86',
		'bubble',
		'bubbles',
		'bubble-dots',
		'bubble-alert',
		'bubble-question',
		'bubble-text',
		'bubble-pencil',
		'bubble-picture',
		'bubble-video',
		'bubble-user',
		'bubble-quote',
		'bubble-heart',
		'bubble-emoticon',
		'bubble-attachment',
		'phone-bubble',
		'quote-open',
		'quote-close',
		'dna',
		'heart-pulse',
		'pulse',
		'syringe',
		'pills',
		'first-aid',
		'lifebuoy',
		'bandage',
		'bandages',
		'thermometer',
		'microscope',
		'brain',
		'beaker',
		'skull',
		'bone',
		'construction',
		'construction-cone',
		'pie-chart',
		'pie-chart2',
		'graph',
		'chart-growth',
		'chart-bars',
		'chart-settings',
		'cake',
		'gift',
		'balloon',
		'rank',
		'rank2',
		'rank3',
		'crown',
		'lotus',
		'diamond',
		'diamond2',
		'diamond3',
		'diamond4',
		'linearicons',
		'teacup',
		'teapot',
		'glass',
		'bottle2',
		'glass-cocktail',
		'glass2',
		'dinner',
		'dinner2',
		'chef',
		'scale2',
		'egg',
		'egg2',
		'eggs',
		'platter',
		'steak',
		'hamburger',
		'hotdog',
		'pizza',
		'sausage',
		'chicken',
		'fish',
		'carrot',
		'cheese',
		'bread',
		'ice-cream',
		'ice-cream2',
		'candy',
		'lollipop',
		'coffee-bean',
		'coffee-cup',
		'cherry',
		'grapes',
		'citrus',
		'apple',
		'leaf',
		'landscape',
		'pine-tree',
		'tree',
		'cactus',
		'paw',
		'footprint',
		'speed-slow',
		'speed-medium',
		'speed-fast',
		'rocket',
		'hammer2',
		'balance',
		'briefcase',
		'luggage-weight',
		'dolly',
		'plane',
		'plane-crossed',
		'helicopter',
		'traffic-lights',
		'siren',
		'road',
		'engine',
		'oil-pressure',
		'coolant-temperature',
		'car-battery',
		'gas',
		'gallon',
		'transmission',
		'car',
		'car-wash',
		'car-wash2',
		'bus',
		'bus2',
		'car2',
		'parking',
		'car-lock',
		'taxi',
		'car-siren',
		'car-wash3',
		'car-wash4',
		'ambulance',
		'truck',
		'trailer',
		'scale-truck',
		'train',
		'ship',
		'ship2',
		'anchor',
		'boat',
		'bicycle',
		'bicycle2',
		'dumbbell',
		'bench-press',
		'swim',
		'football',
		'baseball-bat',
		'baseball',
		'tennis',
		'tennis2',
		'ping-pong',
		'hockey',
		'8ball',
		'bowling',
		'bowling-pins',
		'golf',
		'golf2',
		'archery',
		'slingshot',
		'soccer',
		'basketball',
		'cube',
		'3d-rotate',
		'puzzle',
		'glasses',
		'glasses2',
		'accessibility',
		'wheelchair',
		'wall',
		'fence',
		'wall2',
		'icons',
		'resize-handle',
		'icons2',
		'select',
		'select2',
		'site-map',
		'earth',
		'earth-lock',
		'network',
		'network-lock',
		'planet',
		'happy',
		'smile',
		'grin',
		'tongue',
		'sad',
		'wink',
		'dream',
		'shocked',
		'shocked2',
		'tongue2',
		'neutral',
		'happy-grin',
		'cool',
		'mad',
		'grin-evil',
		'evil',
		'wow',
		'annoyed',
		'wondering',
		'confused',
		'zipped',
		'grumpy',
		'mustache',
		'tombstone-hipster',
		'tombstone',
		'ghost',
		'ghost-hipster',
		'halloween',
		'christmas',
		'easter-egg',
		'mustache2',
		'mustache-glasses',
		'pipe',
		'alarm',
		'alarm-add',
		'alarm-snooze',
		'alarm-ringing',
		'bullhorn',
		'hearing',
		'volume-high',
		'volume-medium',
		'volume-low',
		'volume',
		'mute',
		'lan',
		'lan2',
		'wifi',
		'wifi-lock',
		'wifi-blocked',
		'wifi-mid',
		'wifi-low',
		'wifi-low2',
		'wifi-alert',
		'wifi-alert-mid',
		'wifi-alert-low',
		'wifi-alert-low2',
		'stream',
		'stream-check',
		'stream-error',
		'stream-alert',
		'communication',
		'communication-crossed',
		'broadcast',
		'antenna',
		'satellite',
		'satellite2',
		'mic',
		'mic-mute',
		'mic2',
		'spotlights',
		'hourglass',
		'loading',
		'loading2',
		'loading3',
		'refresh',
		'refresh2',
		'undo',
		'redo',
		'jump2',
		'undo2',
		'redo2',
		'sync',
		'repeat-one2',
		'sync-crossed',
		'sync2',
		'repeat-one3',
		'sync-crossed2',
		'return',
		'return2',
		'refund',
		'history',
		'history2',
		'self-timer',
		'clock',
		'clock2',
		'clock3',
		'watch',
		'alarm2',
		'alarm-add2',
		'alarm-remove',
		'alarm-check',
		'alarm-error',
		'timer',
		'timer-crossed',
		'timer2',
		'timer-crossed2',
		'download',
		'upload',
		'download2',
		'upload2',
		'enter-up',
		'enter-down',
		'enter-left',
		'enter-right',
		'exit-up',
		'exit-down',
		'exit-left',
		'exit-right',
		'enter-up2',
		'enter-down2',
		'enter-vertical',
		'enter-left2',
		'enter-right2',
		'enter-horizontal',
		'exit-up2',
		'exit-down2',
		'exit-left2',
		'exit-right2',
		'cli',
		'bug',
		'code',
		'file-code',
		'file-image',
		'file-zip',
		'file-audio',
		'file-video',
		'file-preview',
		'file-charts',
		'file-stats',
		'file-spreadsheet',
		'link',
		'unlink',
		'link2',
		'unlink2',
		'thumbs-up',
		'thumbs-down',
		'thumbs-up2',
		'thumbs-down2',
		'thumbs-up3',
		'thumbs-down3',
		'share',
		'share2',
		'share3',
		'magnifier',
		'file-search',
		'find-replace',
		'zoom-in',
		'zoom-out',
		'loupe',
		'loupe-zoom-in',
		'loupe-zoom-out',
		'cross',
		'menu',
		'list',
		'list2',
		'list3',
		'menu2',
		'list4',
		'menu3',
		'exclamation',
		'question',
		'check',
		'cross2',
		'plus',
		'minus',
		'percent',
		'chevron-up',
		'chevron-down',
		'chevron-left',
		'chevron-right',
		'chevrons-expand-vertical',
		'chevrons-expand-horizontal',
		'chevrons-contract-vertical',
		'chevrons-contract-horizontal',
		'arrow-up',
		'arrow-down',
		'arrow-left',
		'arrow-right',
		'arrow-up-right',
		'arrows-merge',
		'arrows-split',
		'arrow-divert',
		'arrow-return',
		'expand',
		'contract',
		'expand2',
		'contract2',
		'move',
		'tab',
		'arrow-wave',
		'expand3',
		'expand4',
		'contract3',
		'notification',
		'warning',
		'notification-circle',
		'question-circle',
		'menu-circle',
		'checkmark-circle',
		'cross-circle',
		'plus-circle',
		'circle-minus',
		'percent-circle',
		'arrow-up-circle',
		'arrow-down-circle',
		'arrow-left-circle',
		'arrow-right-circle',
		'chevron-up-circle',
		'chevron-down-circle',
		'chevron-left-circle',
		'chevron-right-circle',
		'backward-circle',
		'first-circle',
		'previous-circle',
		'stop-circle',
		'play-circle',
		'pause-circle',
		'next-circle',
		'last-circle',
		'forward-circle',
		'eject-circle',
		'crop',
		'frame-expand',
		'frame-contract',
		'focus',
		'transform',
		'grid',
		'grid-crossed',
		'layers',
		'layers-crossed',
		'toggle',
		'rulers',
		'ruler',
		'funnel',
		'flip-horizontal',
		'flip-vertical',
		'flip-horizontal2',
		'flip-vertical2',
		'angle',
		'angle2',
		'subtract',
		'combine',
		'intersect',
		'exclude',
		'align-center-vertical',
		'align-right',
		'align-bottom',
		'align-left',
		'align-center-horizontal',
		'align-top',
		'square',
		'plus-square',
		'minus-square',
		'percent-square',
		'arrow-up-square',
		'arrow-down-square',
		'arrow-left-square',
		'arrow-right-square',
		'chevron-up-square',
		'chevron-down-square',
		'chevron-left-square',
		'chevron-right-square',
		'check-square',
		'cross-square',
		'menu-square',
		'prohibited',
		'circle',
		'radio-button',
		'ligature',
		'text-format',
		'text-format-remove',
		'text-size',
		'bold',
		'italic',
		'underline',
		'strikethrough',
		'highlight',
		'text-align-left',
		'text-align-center',
		'text-align-right',
		'text-align-justify',
		'line-spacing',
		'indent-increase',
		'indent-decrease',
		'text-wrap',
		'pilcrow',
		'direction-ltr',
		'direction-rtl',
		'page-break',
		'page-break2',
		'sort-alpha-asc',
		'sort-alpha-desc',
		'sort-numeric-asc',
		'sort-numeric-desc',
		'sort-amount-asc',
		'sort-amount-desc',
		'sort-time-asc',
		'sort-time-desc',
		'sigma',
		'pencil-line',
		'hand',
		'pointer-up',
		'pointer-right',
		'pointer-down',
		'pointer-left',
		'finger-tap',
		'fingers-tap',
		'reminder',
		'fingers-crossed',
		'fingers-victory',
		'gesture-zoom',
		'gesture-pinch',
		'fingers-scroll-horizontal',
		'fingers-scroll-vertical',
		'fingers-scroll-left',
		'fingers-scroll-right',
		'hand2',
		'pointer-up2',
		'pointer-right2',
		'pointer-down2',
		'pointer-left2',
		'finger-tap2',
		'fingers-tap2',
		'reminder2',
		'gesture-zoom2',
		'gesture-pinch2',
		'fingers-scroll-horizontal2',
		'fingers-scroll-vertical2',
		'fingers-scroll-left2',
		'fingers-scroll-right2',
		'fingers-scroll-vertical3',
		'border-style',
		'border-all',
		'border-outer',
		'border-inner',
		'border-top',
		'border-horizontal',
		'border-bottom',
		'border-left',
		'border-vertical',
		'border-right',
		'border-none',
		'ellipsis'
	);
	$output = '<div class="wrapper_icon"><input type="hidden" name="' . $args['name'] . '" class="wpb_vc_param_value" value="' . esc_attr( $args['value'] ) . '" id="trace"/>
					<div class="icon-preview"><i class=" icon-' . esc_attr( $args['value'] ) . '"></i></div>';
	$output .= '<input class="search" type="text" placeholder="Search" />';
	$output .= '<div id="icon-dropdown-icomoon">';
	$output .= '<ul class="icon-list">';
	$n = 1;
	foreach ( $icons as $icon ) {
		$selected = ( $icon == esc_attr( $args['value'] ) ) ? 'class="selected"' : '';
		$output .= '<li ' . $selected . ' data-icon="' . $icon . '"><i class="icon-' . $icon . '"></i><label class="icon">' . $icon . '</label></li>';
		$n ++;
	}
	$output .= '</ul>';
	$output .= '</div></div>';
	$output .= '<script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery(".search").keyup(function(){
                            // Retrieve the input field text and reset the count to zero
                            var filter = jQuery(this).val(), count = 0;
                            // Loop through the icon list
                            jQuery(".icon-list li").each(function(){
                                    // If the list item does not contain the text phrase fade it out
                                    if (jQuery(this).text().search(new RegExp(filter, "i")) < 0) {
                                            jQuery(this).fadeOut();
                                    } else {
                                            jQuery(this).show();
                                            count++;
                                    }
                            });
                        });
                    });

                    jQuery("#icon-dropdown-icomoon li").click(function() {
                        jQuery(this).attr("class","selected").siblings().removeAttr("class");
                        var icon = jQuery(this).attr("data-icon");
                        jQuery(this).closest(".wrapper_icon").find(".wpb_vc_param_value").val(icon);
                        jQuery(this).closest(".wrapper_icon").find(".icon-preview").html("<i class=\'icon-"+icon+"\'></i>");
				});
				</script>';

	$arr[] = array(
		'type' => 'icomoon',
		'form' => $output
	);

	return $arr;
}

//add_filter( 'tp_widget_custom_field_type', 'thim_icomoon', 10, 2 );


function thim_related_portfolio( $post_id ) {

	?>
	<div class="related-portfolio col-md-12">
		<div class="module_title"><h4 class="widget-title"><?php esc_html_e( 'Related Items', 'eduma' ); ?></h4>
		</div>

		<?php //Get Related posts by category	-->
		$args      = array(
			'posts_per_page' => 3,
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'post__not_in'   => array( $post_id )
		);
		$port_post = get_posts( $args );
		?>

		<ul class="row">
			<?php
			foreach ( $port_post as $post ) : setup_postdata( $post ); ?>
				<?php
				$bk_ef = get_post_meta( $post->ID, 'thim_portfolio_bg_color_ef', true );
				if ( $bk_ef == '' ) {
					$bk_ef = get_post_meta( $post->ID, 'thim_portfolio_bg_color_ef', true );
					$bg    = '';
				} else {
					$bk_ef = get_post_meta( $post->ID, 'thim_portfolio_bg_color_ef', true );
					$bg    = 'style="background-color:' . $bk_ef . ';"';
				}
				?>
				<li class="col-sm-4">
					<?php

					$imImage = get_permalink( $post->ID );

					$image_url = thim_get_feature_image( get_post_thumbnail_id( $post->ID ), 'full', '480', '320' );
					echo '<div data-color="' . $bk_ef . '" ' . $bg . '>';
					echo '<div class="portfolio-image" ' . $bg . '>' . $image_url . '
					<div class="portfolio_hover"><div class="thumb-bg"><div class="mask-content">';
					echo '<h3><a href="' . esc_url( get_permalink( $post->ID ) ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" >' . get_the_title( $post->ID ) . '</a></h3>';
					echo '<span class="p_line"></span>';
					$terms    = get_the_terms( $post->ID, 'portfolio_category' );
					$cat_name = "";
					if ( $terms && ! is_wp_error( $terms ) ) :
						foreach ( $terms as $term ) {
							if ( $cat_name ) {
								$cat_name .= ', ';
							}
							$cat_name .= '<a href="' . esc_url( get_term_link( $term ) ) . '">' . $term->name . "</a>";
						}
						echo '<div class="cat_portfolio">' . $cat_name . '</div>';
					endif;
					echo '<a href="' . esc_url( $imImage ) . '" title="' . esc_attr( get_the_title( $post->ID ) ) . '" class="btn_zoom ">' . esc_html__( 'Zoom', 'eduma' ) . '</a>';
					echo '</div></div></div></div></div>';
					?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php wp_reset_postdata(); ?>
	</div><!--#portfolio_related-->
	<?php
}


//Function ajax widget gallery-posts
add_action( 'wp_ajax_thim_gallery_popup', 'thim_gallery_popup' );
add_action( 'wp_ajax_nopriv_thim_gallery_popup', 'thim_gallery_popup' );
/** widget gallery posts ajax output **/
function thim_gallery_popup() {
	global $post;
	$post_id = $_POST["post_id"];
	$post    = get_post( $post_id );
	$images  = thim_meta( 'thim_gallery', "type=image&single=false&size=full" );
	// Get category permalink
	ob_start();
	if ( ! empty( $images ) ) {
		foreach ( $images as $k => $value ) {
			$url_image = $value['url'];
			if ( $url_image ) {
				echo '<a href="' . $url_image . '">';
				echo '<img src="' . $url_image . '" alt="Test" />';
				echo '</a>';
			}
		}
	}
	?>

	<?php
	$output = ob_get_contents();
	ob_end_clean();
	echo ent2ncr( $output );
	die();
}


/**
 * LearnPress section
 */

if ( thim_plugin_active( 'learnpress/learnpress.php' ) ) {
	//filter learnpress hooks
	if ( thim_is_new_learnpress() ) {

		function thim_new_learnpress_template_path() {
			return 'learnpress-v1/';
		}

		add_filter( 'learn_press_template_path', 'thim_new_learnpress_template_path', 999 );
		require_once THIM_DIR . 'inc/learnpress-v1-functions.php';
	} else {
		require_once THIM_DIR . 'inc/learnpress-functions.php';
	}

}

/**
 * Check new version of LearnPress
 *
 * @return mixed
 */
function thim_is_new_learnpress() {
	return version_compare( get_option( 'learnpress_version' ), '1.0', '>=' );
}


//Action call reload page when change font on preview box
function thim_chameleon_add_script_reload() {
	?>
	location.reload();
	<?php
}

add_action( 'tp_chameleon_script_after_change_body_font', 'thim_chameleon_add_script_reload' );
add_action( 'tp_chameleon_script_after_change_heading_font', 'thim_chameleon_add_script_reload' );