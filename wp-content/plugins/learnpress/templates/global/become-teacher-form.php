<?php
/**
 * Template for displaying the form let user fill out their information to become a teacher
 */

$method  = $atts['method'];
$request = $method == 'post' ? $_POST : $_REQUEST;
$is_logged_in = is_user_logged_in();
$disabled = !$is_logged_in ? 'disabled="disabled"' : '';
?>
<div class="become-teacher-form">
	<?php if ( !$is_logged_in ){ ?>
	<p class="lp-message-login-requires"><?php printf( __( 'You have <a href="%s">login</a> to fill out this form', 'learn_press' ), wp_login_url( add_query_arg( '', '' ) ) ); ?></p>
	<?php } ?>

	<form name="become_teacher_form" method="<?php echo $method; ?>" enctype="multipart/form-data" action="<?php echo $atts['action']; ?>">
		<?php if ( $fields ): ?>
			<ul>
				<?php foreach ( $fields as $name => $option ): ?>
					<?php
					$option        = wp_parse_args(
						$option,
						array(
							'title'       => '',
							'type'        => '',
							'def'         => '',
							'placeholder' => ''
						)
					);
					$value         = !empty( $request[$name] ) ? $request[$name] : ( !empty( $option['def'] ) ? $option['def'] : '' );
					$requested     = strtolower( $_SERVER['REQUEST_METHOD'] ) == $method;
					$error_message = null;
					if ( $requested ) {
						$error_message = apply_filters( 'learn_press_become_teacher_form_validate_' . $name, $value );
					}

					?>
					<li>
						<label><?php echo $option['title']; ?></label>
						<?php
						switch ( $option['type'] ) {
							case 'text':
							case 'email':
								printf( '<input type="%s" name="%s" placeholder="%s" value="%s" %s />', $option['type'], $name, $option['placeholder'], esc_attr( $value ), $disabled );
								break;
						}
						if ( $error_message ) {
							printf( '<p class="error">%s</p>', $error );
						}
						?>
					</li>
				<?php endforeach; ?>
			</ul>
			<input type="hidden" name="action" value="become_a_teacher" />
			<button type="submit" <?php echo $disabled;?>><?php echo $atts['submit_button_text']; ?></button>
		<?php endif; ?>
	</form>
</div>