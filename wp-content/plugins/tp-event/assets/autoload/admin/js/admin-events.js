(function($){
	"use strict";
	var TP_Event_Admin = {};

	TP_Event_Admin.init = function(){

		// widgets
		var forms = $('#widgets-right .widget-content');
		for( var i = 0; i <= forms.length; i++ )
		{
			var form = $(forms[i]);

			form.find('.tp_event_admin_widget:first').addClass('active');

			form.find( '.tp_event_widget_tab li a:first' ).addClass('button-primary');
			$(document).on('click', '.tp_event_widget_tab li a', function(e){
				e.preventDefault();
				var tab_content = $(this).attr('data-tab'),
					widget_content = $(this).parents('.widget-content'),
					parent = $(this).parents('.tp_event_widget_tab');
				parent.find( 'li a' ).removeClass('button-primary');
				$(this).addClass('button-primary');

				widget_content.find('.tp_event_admin_widget').removeClass('active');
				widget_content.find('.tp_event_admin_widget[data-status="'+tab_content+'"]').addClass('active');
				return false;
			});
		}
	};

	$(document).ready(function(){
		TP_Event_Admin.init();
	});
})(jQuery);