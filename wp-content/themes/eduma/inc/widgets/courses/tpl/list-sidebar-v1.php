<?php
global $post;
$limit     = $instance['limit'];
$sort      = $instance['order'];
$condition = array(
	'post_type'           => 'lp_course',
	'posts_per_page'      => $limit,
	'ignore_sticky_posts' => true,
);

if ( $sort == 'category' && $instance['cat_id'] && $instance['cat_id'] != 'all' ) {
	if ( get_term( $instance['cat_id'], 'course_category' ) ) {
		$condition['tax_query'] = array(
			array(
				'taxonomy' => 'course_category',
				'field'    => 'term_id',
				'terms'    => $instance['cat_id']
			),
		);
	}
}

if ( $sort == 'popular' ) {
//	global $wpdb;
//	$the_query = $wpdb->get_results( $wpdb->prepare(
//		"
//		SELECT      p.ID, pm.meta_value AS student, pm2.meta_value AS setting
//		FROM        $wpdb->posts AS p
//		LEFT JOIN  $wpdb->postmeta AS pm ON p.ID = pm.post_id AND pm.meta_key = %s
//		LEFT JOIN  $wpdb->postmeta AS pm2 ON p.ID = pm2.post_id AND pm2.meta_key = %s
//		WHERE 		p.post_type = %s AND p.post_status = %s",
//		'_lpr_course_user',
//		'_lpr_course_student',
//		'lpr_course',
//		'publish'
//	) );
//	$temp      = array();
//	foreach ( $the_query as $course ) {
//		if ( unserialize( $course->student ) ) {
//			$course->student = count( unserialize( $course->student ) );
//		} else {
//			$course->student = 0;
//		}
//		$temp[ $course->ID ] = $course->setting + $course->student;
//	}
//	arsort( $temp );
//	$condition['post__in'] = array_keys( $temp );
//	$condition['orderby']  = 'post__in';
}

$the_query = new WP_Query( $condition );

if ( $the_query->have_posts() ) :
	if ( $instance['title'] ) {
		echo ent2ncr( $args['before_title'] . $instance['title'] . $args['after_title'] );
	}
	?>
	<div class="thim-course-list-sidebar">
		<?php
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$course      = LP_Course::get_course( $post->ID );
			$is_required = get_post_meta( $course->id, '_lp_required_enroll', true );

			?>
			<div class="lpr_course">
				<?php
				if ( has_post_thumbnail() ) {
					$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
					echo '<div class="course-thumbnail">';
					echo '<img src="' . esc_url( $src[0] ) . '" alt="' . get_the_title() . '"/>';
					echo '</div>';
				}
				?>
				<div class="thim-course-content">
					<h3 class="course-title">
						<a href="<?php echo esc_url( get_the_permalink() ); ?>"> <?php echo get_the_title(); ?></a>
					</h3>

					<div class="course-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
						<?php if ( $course->is_free() || 'no' == $is_required ) : ?>
							<div class="value free-course" itemprop="price" content="<?php esc_attr_e( 'Free', 'eduma' ); ?>">
								<?php esc_html_e( 'Free', 'eduma' ); ?>
							</div>
						<?php else: $price = learn_press_format_price( $course->get_price(), true ); ?>
							<div class="value " itemprop="price" content="<?php echo esc_attr( $price ); ?>">
								<?php echo esc_html( $price ); ?>
							</div>
						<?php endif; ?>
						<meta itemprop="priceCurrency" content="<?php echo learn_press_get_currency_symbol(); ?>" />

					</div>
				</div>
			</div>
			<?php
		endwhile;
		?>
	</div>
	<?php
endif;
wp_reset_postdata();

?>