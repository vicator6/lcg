<?php

/**
 * register metabox
 */
abstract class TP_Event_Meta_Box
{

	/**
	 * id of the meta box
	 * @var null
	 */
	protected $_id = null;

	/**
	 * meta key prefix
	 * @var string
	 */
	public $_prefix = 'tp_event_';

	/**
	 * title of metabox
	 * @var null
	 */
	protected $_title = null;

	/**
	 * array name
	 * @var array
	 */
	protected $_name = array();

	/**
	 * layout file render metabox options
	 * @var null
	 */
	protected $_layout = null;

	/**
	 * screen post, page, tp_event
	 * @var array
	 */
	public $_screen = array( 'tp_event' );

	public function __construct()
	{
		if( ! $this->_id )
			return;

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'update' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'delete' ) );
	}

	/**
	 * add meta box
	 */
	public function add_meta_box()
	{
        foreach ( $this->_screen as $post_type ) {
			add_meta_box(
				$this->_id,
				$this->_title,
				array( $this, 'render' ),
				$post_type,
				'advanced',
				'low'
			);
        }
	}

	/**
	 * build meta box layout
	 */
	public function render()
	{
		if( $this->_layout && file_exists($this->_layout) )
		{
			$this->_layout = apply_filters( 'tp_event_meta_box_layout', $this->_layout, $this->_id );
			do_action( 'tp_event_before_metabox', $this->_id );
			require_once $this->_layout;
			do_action( 'tp_event_after_metabox', $this->_id );
		}
	}

	/**
	 * get_field_name option
	 * @param  string $name
	 * @return string
	 */
	public function get_field_name( $name = '' )
	{
		return $this->_prefix . $name;
	}

	public function get_field_value( $name = '' )
	{
		global $post;
		return get_post_meta( $post->ID, $this->_prefix . $name, true );
	}

	public function update( $post_id, $post, $update )
	{
		if( ! isset( $_POST ) || empty( $_POST ) )
			return;

		if( ! in_array( $post->post_type, $this->_screen ) )
			return;

		foreach ($_POST as $key => $val) {
			if( ! strpos( $key, $this->_prefix ) === 0 )
				continue;

			if( is_string( $val ) )
			{
				$val = trim( $val );
			} else if( is_array( $val ) )
			{
				$val = array_map( 'trim', $val );
			}
			update_post_meta( $post_id, $key, $val );
		}
	}

	/**
	 * delete meta post within post
	 * @return
	 */
	public function delete( $post_id )
	{
		delete_post_meta( $post_id, $this->_id );
	}

}
