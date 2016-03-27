jQuery(function ($) {
	custom_admin_select();
	function custom_admin_select() {
		$('#customize-control-thim_logo_mobile').hide();
		$('#customize-control-thim_sticky_logo_mobile').hide();
		$('#customize-control-thim_config_logo_mobile select').on('change', function () {
			if ($(this).val() == "custom_logo") {
				$('#customize-control-thim_logo_mobile').show();
				$('#customize-control-thim_sticky_logo_mobile').show();
			} else {
				$('#customize-control-thim_logo_mobile').hide();
				$('#customize-control-thim_sticky_logo_mobile').hide();
			}
		}).trigger('change');
	}
});