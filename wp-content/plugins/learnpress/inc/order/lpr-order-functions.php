<?php
/**
 * @file
 *
 * Check to see if a user can view the order
 *
 * @param      $order_id
 * @param null $user_id
 *
 * @return bool
 */
function learn_press_user_can_view_order( $order_id, $user_id = null ) {
	if ( !intval( $order_id ) ) return false;
	if ( !$user_id && !( $user_id = get_current_user_id() ) ) return false;
	if ( !get_post( $order_id ) ) return false;

	$orders = get_user_meta( $user_id, '_lpr_order_id' );

	if ( !in_array( $order_id, $orders ) ) return false;

	return true;
}

/**
 * Function get order information
 *
 * @param int $order_id
 *
 * @return LPR_Order object instance
 */
function learn_press_get_order( $order_id ) {
	if ( !$order_id ) {
		return false;
	}

	return new LPR_Order( $order_id );
}

/**
 * get confirm order URL
 *
 * @param int $order_id
 *
 * @return string
 */
function learn_press_get_order_confirm_url( $order_id = 0 ) {
	$url = '';

	if ( ( $confirm_page_id = learn_press_get_page_id( 'taken_course_confirm' ) ) && get_post( $confirm_page_id ) ) {
		$url = get_permalink( $confirm_page_id );
		if ( $order_id ) {
			$url = join( preg_match( '!\?!', $url ) ? '&' : '?', array( $url, "order_id={$order_id}" ) );
		}
	} else {
		$order = new LPR_Order( $order_id );
		if ( ( $items = $order->get_items() ) && !empty( $items->products ) ) {
			$course = reset( $items->products );
			$url    = get_permalink( $course['id'] );
		} else {
			$url = get_site_url();
		}
	}
	return $url;
}


function learn_press_do_transaction( $method, $transaction = false ) {
	LPR_Gateways::instance()->get_available_payment_gateways();

	do_action( 'learn_press_do_transaction_' . $method, $transaction );
}

function learn_press_uniqid() {
	$hash = str_replace( '.', '', microtime( true ) . uniqid() );
	return apply_filters( 'learn_press_generate_unique_hash', $hash );
}

function learn_press_set_transient_transaction( $method, $temp_id, $user_id, $transaction ) {
	set_transient( $method . '-' . $temp_id, array( 'user_id' => $user_id, 'transaction_object' => $transaction ), 60 * 60 * 24 );
}

function learn_press_get_transient_transaction( $method, $temp_id ) {
	return get_transient( $method . '-' . $temp_id );
}

function learn_press_delete_transient_transaction( $method, $temp_id ) {
	return delete_transient( $method . '-' . $temp_id );
}

function learn_press_create_order( $args = false ) {
	$default_args = array(
		'status'        => '',
		'customer_id'   => null,
		'customer_note' => null,
		'order_id'      => 0,
		'created_via'   => '',
		'parent'        => 0
	);

	$args       = wp_parse_args( $args, $default_args );
	$order_data = array();

	if ( $args['order_id'] > 0 ) {
		$updating         = true;
		$order_data['ID'] = $args['order_id'];
	} else {
		$updating                    = false;
		$order_data['post_type']     = 'lpr_order';
		$order_data['post_status']   = 'pending';
		$order_data['ping_status']   = 'closed';
		$order_data['post_author']   = 1; // always is administrator
		$order_data['post_password'] = uniqid( 'order_' );
		$order_data['post_title']    = sprintf( __( 'Order &ndash; %s', 'learn_press' ), strftime( '%b %d, %Y @ %I:%M %p' ) );
		$order_data['post_parent']   = absint( $args['parent'] );
	}

	if ( $updating ) {
		$order_id = wp_update_post( $order_data );
	} else {
		$order_id = wp_insert_post( apply_filters( 'learn_press_temp_order_data', $order_data ), true );
	}
	return new LPR_Order( $order_id );
}

/**
 * Deprecated function
 *
 * @param array $args
 *
 * @return int
 */
function learn_press_add_transaction( $args = null ) {
	//_deprecated_function( 'learn_press_add_transaction', '1.0', 'learn_press_add_order' );
	return learn_press_add_order( $args );
}

/**
 * @param null $args
 *
 * @return mixed
 */
function learn_press_add_order( $args = null ) {
	//$method, $method_id, $status = 'Pending', $customer_id = false, $transaction_object = false, $args = array()
	$default_args = array(
		'method'             => '',
		'method_id'          => '',
		'status'             => '',
		'user_id'            => null,
		'order_id'           => 0,
		'parent'             => 0,
		'transaction_object' => false
	);

	$args       = wp_parse_args( $args, $default_args );
	$order_data = array();

	if ( $args['order_id'] > 0 && get_post( $args['order_id'] ) ) {
		$updating         = true;
		$order_data['ID'] = $args['order_id'];
	} else {
		$updating                  = false;
		$order_data['post_type']   = 'lpr_order';
		$order_data['post_status'] = !empty( $args['status'] ) ? 'publish' : 'lpr-draft';
		$order_data['ping_status'] = 'closed';
		$order_data['post_author'] = ( $order_owner_id = learn_press_cart_order_instructor() ) ? $order_owner_id : 1; // always is administrator
		$order_data['post_parent'] = absint( $args['parent'] );
	}
	$order_title = array();
	if ( $args['method'] ) $order_title[] = $args['method'];
	if ( $args['method_id'] ) $order_title[] = $args['method_id'];
	$order_title[]            = date_i18n( 'Y-m-d-H:i:s' );
	$order_data['post_title'] = join( '-', $order_title );

	if ( empty( $args['user_id'] ) ) {
		$user            = learn_press_get_current_user();
		$args['user_id'] = $user->ID;
	}

	if ( !$args['transaction_object'] ) $args['transaction_object'] = learn_press_generate_transaction_object();

	if ( !$updating ) {
		if ( $transaction_id = wp_insert_post( $order_data ) ) {
			update_post_meta( $transaction_id, '_learn_press_transaction_method', $args['method'] );

			//update_post_meta( $transaction_id, '_learn_press_transaction_status',    $status );
			update_post_meta( $transaction_id, '_learn_press_customer_id', $args['user_id'] );
			update_post_meta( $transaction_id, '_learn_press_customer_ip', learn_press_get_ip() );
			update_post_meta( $transaction_id, '_learn_press_order_items', $args['transaction_object'] );

			add_user_meta( $args['user_id'], '_lpr_order_id', $transaction_id );


		}
	} else {
		$transaction_id = wp_update_post( $order_data );
	}

	if ( $transaction_id ) {
		if ( !empty( $args['status'] ) ) {
			learn_press_update_order_status( $transaction_id, $args['status'] );
		}
		update_post_meta( $transaction_id, '_learn_press_transaction_method_id', $args['method_id'] );

		if ( $args['transaction_object'] ) {
			///update_post_meta($transaction_id, '_learn_press_order_items', $args['transaction_object']);
		}


		if ( !empty( $args['status'] ) ) {
			if ( $updating ) {
				return apply_filters( 'learn_press_update_transaction_success', $transaction_id, $args );
			} else
				return apply_filters( 'learn_press_add_transaction_success', $transaction_id, $args );
		}
		return $transaction_id;
	}
	return false;

	//do_action( 'learn_press_add_transaction_fail', $args );// $method, $method_id, $status, $customer_id, $transaction_object, $args );
}

function learn_press_payment_method_from_slug( $order_id ) {
	$slug = get_post_meta( $order_id, '_learn_press_transaction_method', true );
	return apply_filters( 'learn_press_payment_method_from_slug_' . $slug, $slug );
}

function learn_press_generate_transaction_object() {
	$cart = learn_press_get_cart();


	if ( $products = $cart->get_products() ) {
		foreach ( $products as $key => $product ) {
			$products[$key]['product_base_price'] = floatval( learn_press_get_course_price( $product['id'] ) );
			$products[$key]['product_subtotal']   = floatval( learn_press_get_course_price( $product['id'] ) * $product['quantity'] );
			$products[$key]['product_name']       = get_the_title( $product['id'] );
			$products                             = apply_filters( 'learn_press_generate_transaction_object_products', $products, $key, $product );
		}
	}


	$transaction_object                         = new stdClass();
	$transaction_object->cart_id                = $cart->get_cart_id();
	$transaction_object->total                  = round( $cart->get_total(), 2 );
	$transaction_object->sub_total              = $cart->get_sub_total();
	$transaction_object->currency               = learn_press_get_currency();
	$transaction_object->description            = learn_press_get_cart_description();
	$transaction_object->products               = $products;
	$transaction_object->coupons                = '';
	$transaction_object->coupons_total_discount = '';

	$transaction_object = apply_filters( 'learn_press_generate_transaction_object', $transaction_object );

	return $transaction_object;
}

/**
 * Get the author ID of course in the cart
 *
 * Currently, it only get the first item in cart
 *
 * @return int
 */
function learn_press_cart_order_instructor() {
	$cart = learn_press_get_cart();
	if ( $products = $cart->get_products() ) {
		foreach ( $products as $key => $product ) {
			$post = get_post( $product['id'] );
			if ( $post && !empty( $post->ID ) ) {
				return $post->post_author;
			}
		}
	}
	return 0;
}

function learn_press_handle_purchase_request() {
	LPR_Gateways::instance()->get_available_payment_gateways();
	$method_var = 'learn-press-transaction-method';

	$requested_transaction_method = empty( $_REQUEST[$method_var] ) ? false : $_REQUEST[$method_var];
	learn_press_do_transaction( $requested_transaction_method );
}


function learn_press_get_orders( $args = array() ) {
	$defaults = array(
		'post_type' => 'lpr_order',
	);

	$args = wp_parse_args( $args, $defaults );

	$args['meta_query'] = empty( $args['meta_query'] ) ? array() : $args['meta_query'];

	if ( !empty( $args['transaction_method'] ) ) {
		$meta_query           = array(
			'key'   => '_learn_press_transaction_method',
			'value' => $args['transaction_method'],
		);
		$args['meta_query'][] = $meta_query;
	}

	$args = apply_filters( 'learn_press_get_orders_get_posts_args', $args );

	if ( $orders = get_posts( $args ) ) {

	}

	return apply_filters( 'learn_press_get_orders', $orders, $args );
}

function learn_press_on_order_status_changed( $status, $order_id ) {
	$course_id = learn_press_get_course_by_order( $order_id );
	$user_id   = get_post_meta( $order_id, '_learn_press_customer_id', true );

	$user_courses = get_user_meta( $user_id, '_lpr_user_course', true );
	$course_users = get_post_meta( $course_id, '_lpr_course_user', true );
	if ( strtolower( $status ) == 'completed' ) {
		learn_press_increment_user_enrolled( $course_id );
		if ( is_array( $user_courses ) ) {
			$user_courses[] = $course_id;
		} else {
			$user_courses = array( $course_id );
		}

		if ( is_array( $course_users ) ) {
			$course_users[] = $user_id;
		} else {
			$course_users = array( $user_id );
		}
	} else {
		learn_press_decrement_user_enrolled( $course_id );
		if ( is_array( $user_courses ) && ( false !== ( $pos = array_search( $course_id, $user_courses ) ) ) ) {
			unset( $user_courses[$pos] );
		}

		if ( is_array( $course_users ) && ( false !== ( $pos = array_search( $user_id, $course_users ) ) ) ) {
			unset( $course_users[$pos] );
		}
	}
	update_user_meta( $user_id, '_lpr_user_course', $user_courses );
	update_post_meta( $course_id, '_lpr_course_user', $course_users );
}

add_action( 'learn_press_update_order_status', 'learn_press_on_order_status_changed', 50, 2 );
/*
function learn_press_send_user_email($status, $order_id){

    if( 'completed' == strtolower( $status ) ) {

        $order = new LPR_Order($order_id);
        $to = $order->get_user('email');
        $action = 'enrolled_course';
        learn_press_send_mail( $to, $action, null );
    }
}
add_action( 'learn_press_update_order_status', 'learn_press_send_user_email', 50, 2 );*/

function learn_press_get_course_price_text( $price, $course_id ) {
	if ( !$price && 'lpr_course' == get_post_type( $course_id ) ) {
		$price = __( 'Free', 'learn_press' );
	}
	return $price;
}

add_filter( 'learn_press_get_course_price', 'learn_press_get_course_price_text', 5, 2 );


function learn_press_get_order_items( $order_id ) {
	return get_post_meta( $order_id, '_learn_press_order_items', true );
}

function learn_press_format_price( $price, $with_currency = false ) {
	if ( !is_numeric( $price ) )
		$price = 0;
	$settings = learn_press_settings( 'general' );
	$before   = $after = '';
	if ( $with_currency ) {
		if ( gettype( $with_currency ) != 'string' ) {
			$currency = learn_press_get_currency_symbol();
		} else {
			$currency = $with_currency;
		}

		switch ( $settings->get( 'currency_pos' ) ) {
			default:
				$before = $currency;
				break;
			case 'left_with_space':
				$before = $currency . ' ';
				break;
			case 'right':
				$after = $currency;
				break;
			case 'right_with_space':
				$after = ' ' . $currency;
		}
	}

	$price =
		$before
		. number_format(
			$price,
			$settings->get( 'number_of_decimals', 2 ),
			$settings->get( 'decimals_separator', '.' ),
			$settings->get( 'thousands_separator', ',' )
		) . $after;

	return $price;
}

function learn_press_transaction_order_number( $order_number ) {
	return '#' . sprintf( "%'.010d", $order_number );
}

function learn_press_transaction_order_date( $date, $format = null ) {
	$format = empty( $format ) ? get_option( 'date_format' ) : $format;
	return date( $format, strtotime( $date ) );
}