<?php
class LP_User extends LP_Abstract_User{
	function __get( $key ){
		//print_r($this->user->data->user_email);
		if( !empty( $this->user ) ){
			$data = $this->user->data->{$key};
			if( $data ) return $data;
		}

	}
	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	static function get_user( $id ){
		if( empty( self::$_users[ $id ] ) ){
			self::$_users[ $id ] = new self( $id );
		}
		return self::$_users[ $id ];
	}
}