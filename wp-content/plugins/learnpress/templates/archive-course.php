<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<header class="page-header">
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->
		<?php if ( have_posts() ) : ?>

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();
				learn_press_get_template( 'archive-course-content.php' );
			endwhile;

			// Previous/next page navigation.
			learn_press_course_paging_nav();
			?>

		<?php else: ?>

			<article id="post-0" <?php post_class(); ?>>

				<div class="entry-content">
					<?php _e( 'No courses found!', 'learn_press' );?>
				</div>

			</article><!-- #post-## -->

		<?php endif; ?>

	</main>
	<!-- .site-main -->
</section><!-- .content-area -->
<?php get_footer(); ?>
