<?php

class TP_Event_Meta_Box_Event extends TP_Event_Meta_Box
{
	/**
	 * id of the meta box
	 * @var null
	 */
	public $_id = null;

	/**
	 * title of meta box
	 * @var null
	 */
	public $_title = null;

	/**
	 * array meta key
	 * @var array
	 */
	public $_name = array();

	public function __construct()
	{
		$this->_id = 'tp_event_setting_section';
		$this->_title = __( 'Event Settings', 'tp-event' );
		$this->_layout = TP_EVENT_INC . '/metabox/views/event-countdown.php';
		add_action( 'tp_event_schedule_status', array( $this, 'schedule_status' ), 10, 2 );
		$this->init();

		add_action( 'updated_postmeta', array( $this, 'update_post_meta' ), 10, 4 );
		add_action( 'added_post_meta', array( $this, 'update_post_meta' ), 10, 4 );
		parent::__construct();
	}

	public function update_post_meta( $meta_id, $post_id, $meta_key, $meta_value )
	{
		if( ! isset( $_POST ) || empty( $_POST ) )
			return;

		if( get_post_type( $post_id ) !== 'tp_event' )
			return;

		if( ! in_array( $meta_key, array( 'tp_event_date_start', 'tp_event_date_end', 'tp_event_time_start', 'tp_event_time_end' ) ) )
			return;

		if( isset( $_POST['tp_event_date_start'] ) || isset( $_POST['tp_event_date_end'] ) || isset( $_POST['tp_event_time_start'] ) || isset( $_POST['tp_event_time_end'] ) )
			return;

		$date_start = get_post_meta( $post_id, 'tp_event_date_start', true );
		$time_start = get_post_meta( $post_id, 'tp_event_time_start', true );

		$date_end = get_post_meta( $post_id, 'tp_event_date_end', true );
		$time_end = get_post_meta( $post_id, 'tp_event_time_end', true );

		$time = current_time( 'timestamp', 1 );
		$start = false;
		$end = false;

		if( $date_start && $date_end )
		{
			if( ! $start = strtotime( $date_start ) )
			{
				$start = $time;
			}

			if( $time_start )
			{
				$start = strtotime( trim( $date_start . ' ' . $time_start ) );
			}

			if( ! $end = strtotime( $date_end ) )
			{
				$end = $start + 1;
			}

			if( $time_end )
			{
				$end = strtotime( trim( $date_start . ' ' . $time_end ) );
			}

		}

		$schedules = get_option( 'tp_event_schedules' );
		if( ! $schedules )
			$schedules = array();

		if( $start && $end )
		{
			if( $start > $time )
			{
				$status = 'tp-event-upcoming';
			}
			else if( $start <= $time && $time < $end )
			{
				$status = 'tp-event-happenning';
			}
			else if( $time >= $end )
			{
				$status = 'tp-event-expired';
			}
		}

		if( $start && $end )
		{
			$schedules[ $post_id ] = array(
					'start'	=> $start,
					'end'	=> $end,
				);
			update_option( 'tp_event_schedules', $schedules );

			wp_update_post( array( 'ID' => $post_id, 'post_status' => $status ) );
		}

	}

	public function update( $post_id, $post, $update )
	{
		if( ! isset( $_POST ) || empty( $_POST ) )
			return;

		if( $post->post_type !== 'tp_event' )
			return;

		remove_action( 'save_post', array( $this, 'update' ), 10, 3 );
		parent::update( $post_id, $post, $update );

		$post = tp_event_add_property_countdown($post);

		$event_start = strtotime( $post->event_start );
		$event_end = strtotime( $post->event_end );

		$time = current_time( 'timestamp', 1 );

		$schedules = get_option( 'tp_event_schedules' );
		if( ! $schedules )
			$schedules = array();

		$status = 'draft';
		if( $event_start && $event_end )
		{
			if( $event_start > $time )
			{
				$status = 'tp-event-upcoming';
			}
			else if( $event_start <= $time && $time < $event_end )
			{
				$status = 'tp-event-happenning';
			}
			else if( $time >= $event_end )
			{
				$status = 'tp-event-expired';
			}
		}

		$schedules[ $post_id ] = array(
				'start'	=> $event_start,
				'end'	=> $event_end,
			);
		update_option( 'tp_event_schedules', $schedules );

		wp_update_post( array( 'ID' => $post_id, 'post_status' => $status ) );

		add_action( 'save_post', array( $this, 'update' ), 10, 3 );
	}

	function init()
	{
		$schedules = get_option( 'tp_event_schedules' );
		if( ! $schedules ) return;

		wp_clear_scheduled_hook( 'tp_event_schedule_status' );
		foreach ( $schedules as $post_id => $schedule ) {

			if( ! in_array( get_post_status( $post_id ), array( 'tp-event-upcoming', 'tp-event-happenning', 'tp-event-expired' ) ) )
				continue;

			if( isset($schedule['start'], $schedule['end']) && $schedule['start'] && $schedule['end'] )
			{
				if( ! wp_next_scheduled( 'tp_event_schedule_status', array( $post_id, $schedule ) ) )
				{
					if( $schedule['start'] <= current_time( 'timestamp', 1 ) || current_time( 'timestamp', 1 ) >= $schedule['end'] )
					{
						wp_schedule_single_event( current_time( 'timestamp', 1 ), 'tp_event_schedule_status', array( $post_id, $schedule ) );
					}
					else
					{
						wp_schedule_single_event( $schedule['start'], 'tp_event_schedule_status', array( $post_id, $schedule ) );
						wp_schedule_single_event( $schedule['end'], 'tp_event_schedule_status', array( $post_id, $schedule ) );
					}
				}
			}
		}
	}

	public function schedule_status( $post_id, $schedule )
	{
		$event_start = $schedule['start'];
		$event_end = $schedule['end'];
		$time = current_time( 'timestamp', 1 );

		$status = 'draft';
		if( $event_start > $time )
		{
			$status = 'tp-event-upcoming';
		}
		else if( $event_start <= $time && $time < $event_end )
		{
			$status = 'tp-event-happenning';
		}
		else if( $time >= $event_end )
		{
			$status = 'tp-event-expired';
		}

		wp_update_post( array( 'ID' => $post_id, 'post_status' => $status ) );
	}

}

new TP_Event_Meta_Box_Event();