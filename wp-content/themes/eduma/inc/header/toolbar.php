<?php if ( is_active_sidebar( 'toolbar' ) ) : ?>
	<div id="toolbar" class="toolbar">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<?php dynamic_sidebar( 'toolbar' ); ?>
				</div>
			</div>
		</div>
	</div><!--End/div#toolbar-->
<?php
endif;