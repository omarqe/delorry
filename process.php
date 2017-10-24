<?php
/**
 * Processor file.
 * 
 * @since 	1.0
 **/

require_once( 'includes/load.php' );

$action = strtolower( parse_arg('action', $_REQUEST) );

if ( !empty($action) ){
	switch ( $action ){
		/**
		 * Register an account.
		 * 
		 * @since 	1.0
		 **/
		case "register":
			$keys = 
			list( $username, $password, $confirm_pass, $phone, $email ) = get_list(
				['username', 'password', 'confirm_pass', 'phone', 'email'],
				$_POST
			);


			// $_POST parameters (soon will be converted into URL query params)
			$params = parse_args(['username', 'phone', 'email'], $_POST);
			
			$page = 'register.php';
			if ( empty($username) || empty($password) || empty($phone) || empty($email) ){
				$params['msg'] = 'fields_empty';
			} elseif ( $password != $confirm_pass ){
				$params['msg'] = 'password_mismatch';
			} else {
				// Check if user exists.
				$db->query( "SELECT COUNT(username) AS total FROM user where username = '%s'", strtolower($username) );
				$row 	= $db->fetch();
				$exist 	= ((int)$row['total'] > 0);

				if ( $exist ){
					unset( $params['username'] );
					$params['msg'] = 'user_exists';
				} else {
					$data = array(
						'username' => strtolower($username),
						'password' => hash_pass( $password ),
						'phone' => $phone,
						'email' => $email,
						'group' => 'user'
					);

					if ( !$db->insert('user', $data) ){
						$params['msg'] = 'error';
					}
					else {
						foreach ( $params as $key => $value )
							if ( $key != 'username' ) unset($params[$key]);

						$page = 'login.php';
						$params['msg'] = 'registered';
					}
				}
			}

			// Build redirect plan
			$params 	= http_build_query( array_filter($params) );
			$redirect 	= $page . '?' . $params;

			// Redirect user to a new page
			redirect( $redirect );
			exit;

		case "login":
			list( $username, $password ) = get_list(['username', 'password'], $_POST);

			$redirect = './login.php';
			if ( empty($username) || empty($password) ){
				$redirect = '?msg=empty';
			} else {
				$keys = ['user_id', 'username', 'password', 'email', 'phone', 'group'];
				$db->select( 'user', array('username' => strtolower($username)), $keys);
				$row = $db->fetch();

				list( $user_id, $username, $user_pass, $email, $phone, $group ) = get_list($keys, $row);

				if ( !val_pass($password, $user_pass) || empty($username) ){
					$redirect = '?msg=error';
				} else {
					setcookie( 'dl_user', $user_id, time()+86400 ); // One day
					setcookie( 'dl_data', base64_encode( serialize(compact('username','email', 'phone', 'group')) ) );

					$redirect = (strtolower($group) == 'admin') ? './admin/' : './';
				}
			}

			// Redirect user to a new page.
			redirect( $redirect );
			exit;

		case "logout":
			setcookie( 'dl_user', '', -3600 );
			setcookie( 'dl_data', '', -3600 );

			redirect( './' );
			exit;

		case "delete_booking":
		case "approve_booking":
		case "complete_booking":
			$message_key = 'success';
			$booking_id  = parse_arg( 'id', $_GET );
			$where 		 = array( 'book_id' => $booking_id );

			if ( $action != 'delete_booking' ){
				$new_status = array(
					'approve_booking' => 'approved',
					'complete_booking' => 'completed'
				);

				if ( !$db->update('booking', array('book_status' => $new_status[$action]), $where) )
					$message = 'error';
			} else {
				if ( !$db->delete('booking', $where, 1) )
					$message = 'error';
			}

			redirect( './admin/?msg=' . $message_key );
			exit;

		case "book":
			// Must login first
			if ( !is_loggedin() )
				redirect( './login.php' );

			$msg_code = 'success';

			list( $date, $from, $to, $vehicle ) = get_list([
				'date', 'from', 'to', 'vehicle'
			], $_POST);

			if ( $from == $to ){
				// We don't tolerate same place
				$msg_code = 'same_place';
			} else {
				// Get the distance and the price per kilometer
				$price_per_km = get_settings( 'price' );
				$places = get_settings( 'places' );
				$placedata = parse_arg( $from, $places );
				$distances  = parse_arg( 'distance', $placedata );
				$distance_to_destination = parse_arg( $to, $distances );

				// Calculate the final price (price per kilometer * distance to destination)
				$final_price = $distance_to_destination * $price_per_km;

				$username = get_user();
				$data = array(
					'book_user_id' => parse_arg('dl_user', $_COOKIE),
					'book_vehicle' => $vehicle,
					'book_date' => $date,
					'book_from' => $from,
					'book_to' => $to,
					'book_status' => 'pending',
					'book_price' => $final_price
				);

				if ( !$db->insert('booking', $data) )
					$msg_code = 'error';
			}

			redirect( './?msg=' . $msg_code );
			exit;
	}

	redirect( './' );
}