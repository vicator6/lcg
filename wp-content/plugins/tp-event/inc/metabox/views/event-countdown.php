<?php global $post; ?>
<table>
	<tr>
		<th>
			<label><?php _e( 'Start Event', 'tp-event' ) ?></label>
		</th>
		<td>
			<p id="tp_event_datetime_start">
			    <input type="text" class="date start" name="<?php echo esc_attr( $this->get_field_name( 'date_start' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'date_start' ) ); ?>"/>
			    <input type="text" class="time start" name="<?php echo esc_attr( $this->get_field_name( 'time_start' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'time_start' ) ); ?>"/>
			</p>
		</td>
	</tr>
	<tr>
		<th>
			<label><?php _e( 'End Event', 'tp-event' ) ?></label>
		</th>
		<td>
			<p id="tp_event_datetime_end">
			    <input type="text" class="date end" name="<?php echo esc_attr( $this->get_field_name( 'date_end' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'date_end' ) ); ?>"/>
			    <input type="text" class="time end" name="<?php echo esc_attr( $this->get_field_name( 'time_end' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'time_end' ) ); ?>"/>
			</p>
		</td>
	</tr>
	<tr>
		<th>
			<label><?php _e( 'Location', 'tp-event' ) ?></label>
		</th>
		<td>
			<p id="tp_event_datetime_end">
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'location' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'location' ) ); ?>" />
			</p>
		</td>
	</tr>
	<tr>
		<th>
			<label><?php _e( 'Shortcode', 'tp-event' ) ?></label>
		</th>
		<td>
			<p>
			    <input type="text" class="shortcode" name="<?php echo esc_attr( $this->get_field_name( 'shortcode' ) ); ?>" value="<?php echo esc_attr( '[tp_event_countdown events="'.$post->ID.'"]' ); ?>" readonly/>
			</p>
		</td>
	</tr>
</table>
<script>
    (function($){
    	$.noConflict();
    	$(document).ready(function(){
    		// initialize input widgets first
    		var time_start = $('#tp_event_datetime_start .time'),
    			date_start = $('#tp_event_datetime_start .date'),
    			time_end = $('#tp_event_datetime_end .time'),
    			date_end = $('#tp_event_datetime_end .date');
    			// min_date =

		    time_start.timepicker({
		        showDuration: true,
		        timeFormat: 'g:i A'
		    });

		    date_start.datepicker({
		        format: 'mm/dd/yyyy',
		        autoclose: true
		    });

    		// initialize input widgets first
		    time_end.timepicker({
		        showDuration: true,
		        timeFormat: 'g:i A'
		    });

		    date_end.datepicker({
		        format: 'mm/dd/yyyy',
		        autoclose: true
		    });
    	});
    })(jQuery);
</script>