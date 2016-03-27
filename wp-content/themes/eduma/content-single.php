<?php
/**
 * @package thim
 */

$theme_options_data = thim_options_data();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<div class="page-content-inner">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			<?php thim_entry_meta(); ?>
		</header>
		<?php
		/* Video, Audio, Image, Gallery, Default will get thumb */
		do_action( 'thim_entry_top', 'full' );
		?>
		<!-- .entry-header -->
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'eduma' ),
				'after'  => '</div>',
			) );
			?>
		</div>
		<div class="entry-tag-share">
			<div class="row">
				<div class="col-sm-6">
					<?php
					if ( get_the_tag_list() ) {
						echo get_the_tag_list( '<p class="post-tag"><span>' . esc_html__( "Tag:", 'eduma' ) . '</span>', ', ', '</p>' );
					}
					?>
				</div>
				<div class="col-sm-6">
					<?php do_action( 'thim_social_share' ); ?>
				</div>
			</div>
		</div>
		<?php do_action( 'thim_about_author' ); ?>

		<div class="entry-navigation-post">
			<?php $prev_post = get_previous_post();
			if ( !empty( $prev_post ) ):
				?>
				<div class="prev-post">
					<p class="heading"><?php echo esc_html__( 'Previous post', 'eduma' ); ?></p>
					<h5 class="title">
						<a href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo esc_html( $prev_post->post_title ); ?></a>
					</h5>

					<div class="date">
						<?php echo get_the_date( 'j F, Y', $prev_post->ID ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php $next_post = get_next_post();
			if ( !empty( $next_post ) ):
				?>
				<div class="next-post">
					<p class="heading"><?php echo esc_html__( 'Next post', 'eduma' ); ?></p>
					<h5 class="title">
						<a href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo esc_html( $next_post->post_title ); ?></a>
					</h5>

					<div class="date">
						<?php echo get_the_date( 'j F, Y', $next_post->ID ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php
		get_template_part( 'inc/related' );
		?>
	</div>
</article>