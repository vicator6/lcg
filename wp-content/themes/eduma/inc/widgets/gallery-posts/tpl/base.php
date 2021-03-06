<?php

$query_args = array(
	'post_type' => 'post',
	'tax_query' => array(
		array(
			'taxonomy' => 'post_format',
			'field'    => 'slug',
			'terms'    => array( 'post-format-gallery' ),
		)
	)
);
$filter     = isset( $instance['filter'] ) ? $instance['filter'] : true;
$columns    = ! empty( $instance['columns'] ) ? $instance['columns'] : 3;
if ( ! empty( $instance['cat'] ) ) {
	if ( '' != get_cat_name( $instance['cat'] ) ) {
		$query_args['cat'] = $instance['cat'];
	}
}

switch ( $columns ) {
	case 2:
		$class_col = "col-sm-6";
		break;
	case 3:
		$class_col = "col-sm-4";
		break;
	case 4:
		$class_col = "col-sm-3";
		break;
	case 5:
		$class_col = "thim-col-5";
		break;
	case 6:
		$class_col = "col-sm-2";
		break;
	default:
		$class_col = "col-sm-4";
}

$posts_display = new WP_Query( $query_args );

if ( $posts_display->have_posts() ) :

	$categories = array();
	$html       = '';

	while ( $posts_display->have_posts() ) : $posts_display->the_post();

		$img   = thim_get_feature_image( get_post_thumbnail_id(), 'full', 450, 450, get_the_title() );
		$class = '';
		$cats  = get_the_category();
		if ( ! empty( $cats ) ) {
			foreach ( $cats as $key => $value ) {
				$class .= ' filter-' . $value->term_id;
				$categories[$value->term_id] = $value->name;
			}
		}
		$html .= '<div class="' . $class_col . $class . '">';
		$html .= '<a class="thim-gallery-popup" href="#" data-id="' . get_the_ID() . '">' . $img . '</a>';
		$html .= '</div>';

	endwhile;

	?>

	<?php if ( $filter ): ?>
	<div class="wrapper-filter-controls">
		<ul class="filter-controls">
			<li>
				<a class="filter active" data-filter="*" href="javascript:;"><?php esc_html_e( 'All', 'eduma' ); ?></a>
			</li>
			<?php

			if ( ! empty( $categories ) ) {
				foreach ( $categories as $key => $value ) {
					echo '<li><a class="filter" href="javascript:;" data-filter=".filter-' . $key . '">' . $value . '</a></li>';
				}
			}
			?>
		</ul>
	</div>
	<?php endif; ?>

	<div class="wrapper-gallery-filter row" itemscope itemtype="http://schema.org/ItemList">
		<?php
		echo ent2ncr( $html );
		?>
		<div class="thim-gallery-show"></div>
	</div>

	<?php
endif;
wp_reset_postdata();
