<?php

/**
 * register all post type
 */
class TP_Event_Post_Type
{

	public function __construct()
	{
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_post_status' ) );
		add_filter( 'manage_tp_event_posts_columns', array( $this, 'column_filter' ) );
		add_action( 'manage_tp_event_posts_custom_column' , array( $this, 'column_action' ), 10, 2 );
	}

	// register post type hook callback
	function register_post_type()
	{
		if( is_admin() && ! current_user_can( 'administrator' ) )
			return;
		// post type
		$labels = array(
			'name'               => _x( 'Events', 'post type general name', 'tp-event' ),
			'singular_name'      => _x( 'Event', 'post type singular name', 'tp-event' ),
			'menu_name'          => _x( 'Events', 'admin menu', 'tp-event' ),
			'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'tp-event' ),
			'add_new'            => _x( 'Add New', 'event', 'tp-event' ),
			'add_new_item'       => __( 'Add New Event', 'tp-event' ),
			'new_item'           => __( 'New Event', 'tp-event' ),
			'edit_item'          => __( 'Edit Event', 'tp-event' ),
			'view_item'          => __( 'View Event', 'tp-event' ),
			'all_items'          => __( 'All Events', 'tp-event' ),
			'search_items'       => __( 'Search Events', 'tp-event' ),
			'parent_item_colon'  => __( 'Parent Events:', 'tp-event' ),
			'not_found'          => __( 'No events found.', 'tp-event' ),
			'not_found_in_trash' => __( 'No events found in Trash.', 'tp-event' )
		);

		$args = array(
			'labels'             => $labels,
            'description'        => __( 'Event post type.', 'tp-event' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => _x( 'events', 'URL slug', 'tp-event' ) ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		$args = apply_filters( 'tp_event_register_event_post_type_args', $args );
		register_post_type( 'tp_event', $args );
	}

	public function register_post_status()
	{
		// post status // upcoming // expired // happenning
		$args = apply_filters( 'tp_event_register_upcoming_status_args', array(
			'label'                     => _x( 'Upcoming', 'tp-event' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Upcoming <span class="count">(%s)</span>', 'Upcoming <span class="count">(%s)</span>' ),
		));
		register_post_status( 'tp-event-upcoming', $args);

		$args = apply_filters( 'tp_event_register_happening_status_args', array(
			'label'                     => _x( 'Happenning', 'tp-event' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Happenning <span class="count">(%s)</span>', 'Happenning <span class="count">(%s)</span>' ),
		));
		register_post_status( 'tp-event-happenning', $args );

		$args = apply_filters( 'tp_event_register_expired_status_args', array(
			'label'                     => _x( 'Expired', 'tp-event' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>' ),
		));
		register_post_status( 'tp-event-expired', $args );
	}

	public function column_filter( $columns )
	{
		$columns['tp_event_status'] = __( 'Event Status', 'tp-event' );
		return $columns;
	}

	public function column_action( $column, $post_id )
	{
		switch ( $column ) {

        case 'tp_event_status' :
				$status = get_post_status_object( get_post_status( $post_id) );
				echo $status->label;
			break;

    }
	}

}

new TP_Event_Post_Type();