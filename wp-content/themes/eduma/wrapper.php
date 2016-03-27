<?php

$template_file = thim_template_path();

if ( is_page_template( 'page-templates/homepage.php' ) || is_singular( 'lpr_quiz' ) || is_singular( 'lp_quiz' ) || is_page_template( 'page-templates/landing-no-footer.php' ) ) {
	load_template( $template_file );

	return;
}

get_header();
?>
	<section class="content-area">
		<?php
		get_template_part( 'inc/templates/heading', 'top' );
		do_action( 'thim_wrapper_loop_start' );
		load_template( $template_file );

		do_action( 'thim_wrapper_loop_end' );
		?>
	</section>
<?php
get_footer();