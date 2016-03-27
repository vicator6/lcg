<?php
if ( have_posts() ) :?>
	<div class="row blog-content archive_switch blog-list">
		<?php
		/* Start the Loop */
		while ( have_posts() ) : the_post();
			get_template_part( 'content' );
		endwhile;
		?>
	</div>
	<?php
	thim_paging_nav();
else :
	get_template_part( 'content', 'none' );
endif;