<?php

/**
 * Redirect user to a new URL.
 * 
 * @param 	string 	$url 	The new URL.
 * @since 	1.0
 **/
function redirect( $url ){
	header( "Location: $url" );
	exit;
}

/**
 * Check whether variables declared in array.
 * 
 * @param 	array 	$keys 		The items in the globals array
 * @param 	array 	$array 		The globals
 * @param 	mixed 	$default 	The default value if the items are not in the 
 * 								globals.
 * @since 	0.0.1
 **/
function _elms( $keys, $array, $default = '' ){
	$return = array();

	foreach ( (array)$keys as $key )
		$return[$key] = array_key_exists($key, (array)$array) ? $array[$key] : $default;

	return $return;
}
/**
 * Alias of _elms()
 * 
 * @uses 	_elms()
 * @return 	array
 * @since 	0.1.36
 **/
function parse_args( $array_default, $array, $default = NULL ){
	return _elms( $array_default, $array, $default );
}

/**
 * Checks whether a variable exists in an array but instead of returning the whole,
 * this function returns the value of the variable.
 * 
 * @param 	string 	$key 		The variable key.
 * @param 	array 	$array 		The array.
 * @param 	mixed 	$default 	The default value if not exists.
 * 
 * @return 	mixed
 * @since 	0.1.36
 **/
function parse_arg( $key, $array, $default = '' ){
	$args = parse_args( $key, (array)$array, $default );
	return $args[$key];
}

/**
 * Get the array_values() of parse_args() to be used when we're using list()
 * 
 * @param 	array|string 	$keys 	The keys, must be in order.
 * @param 	array 			$array 	The array.
 * @return 	array
 * @since 	0.1.36
 **/
function get_list( $keys, $array ){
	return array_values( parse_args((array)$keys, $array) );
}

/**
 * Hash password. Note that this is not a super-secure password protection.
 * 
 * @param 	string 	$password 	The password to hash.
 * @return 	string
 * @since 	1.0
 **/
function hash_pass( $password ){
	return hash('md5', $password);
}

/**
 * Check password. Note that this is not a super-secure password protection.
 * 
 * @param 	string 	$pass_to_check 	The password to check.
 * @param 	string 	$password 		The password that has been hashed.
 * @return 	boolean
 * @since 	1.0
 **/
function val_pass( $pass_to_check, $password ){
	return hash('md5', $pass_to_check) == $password;
}

/**
 * Check to see whether the data is serialized or otherwise.
 * 
 * This is a WordPress function. You can find this function in
 * ./wp-includes/functions.php on line 340.
 * 
 * @param 	string 	$data 		The data to check.
 * @param 	bool 	$strict 	Whether to be strict about the end of the $data.
 * @since 	0.1.31
 **/
function is_serialized( $data, $strict = true ) {
	// if it isn't a string, it isn't serialized.
	if ( ! is_string( $data ) ) {
		return false;
	}
	$data = trim( $data );
 	if ( 'N;' == $data ) {
		return true;
	}
	if ( strlen( $data ) < 4 ) {
		return false;
	}
	if ( ':' !== $data[1] ) {
		return false;
	}
	if ( $strict ) {
		$lastc = substr( $data, -1 );
		if ( ';' !== $lastc && '}' !== $lastc ) {
			return false;
		}
	} else {
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		// Either ; or } must exist.
		if ( false === $semicolon && false === $brace )
			return false;
		// But neither must be in the first X characters.
		if ( false !== $semicolon && $semicolon < 3 )
			return false;
		if ( false !== $brace && $brace < 4 )
			return false;
	}
	$token = $data[0];
	switch ( $token ) {
		case 's' :
			if ( $strict ) {
				if ( '"' !== substr( $data, -2, 1 ) ) {
					return false;
				}
			} elseif ( false === strpos( $data, '"' ) ) {
				return false;
			}
			// or else fall through
		case 'a' :
		case 'O' :
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b' :
		case 'i' :
		case 'd' :
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	}
	return false;
}

/**
 * Die nicely.
 * 
 * @since 	1.0
 **/
function go_die( $title = "", $description = "" ){
	if ( "" == $title && $description == "" ){
		$title = "Error!";
		$description = "<p>You do not have sufficient permission to do this.</p>";
	}

	$args = array_slice( func_get_args(), 2 );
	$description = vsprintf( $description, $args );
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>

	<title><?php echo($title) ?></title>

	<link rel="icon" href="../apple-icon-180x180.png" type="image/x-icon">
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/common.css">
	<link rel="stylesheet" href="../css/admin.css">
	<link rel="stylesheet" href="../css/twemoji-awesome.css">

	<style>
	html,body {background: #f9f9f9}
	</style>
</head>

<body>
	<div class="error-in-page"><div class="pt-white-card border-rounded content">
		<h1><?php echo($title); ?></h1>
		<?php
			vprintf($description, $args);
		?>
	</div></div>
</body>
</html>
<?php
	die();
}


/**
 * Check if user is logged in.
 * 
 * @since 	1.0
 **/
function is_loggedin(){
	return isset( $_COOKIE['dl_user'] );
}

/**
 * Get current user data.
 * 
 * @return 	mixed 	Return false if user is not logged in.
 * @since 	1.0
 **/
function get_user( $key = '' ){
	$data = parse_arg( 'dl_data', $_COOKIE );
	if ( !is_loggedin() || empty($data) )
		return false;

	$data = base64_decode( $data );
	if ( is_serialized($data) )
		$data = unserialize( $data );

	if ( empty($key) )
		return $data;

	return parse_arg( strtolower($key), $data );
}

/**
 * Check if the current user is administrator.
 * 
 * @return 	boolean
 * @since 	1.0
 **/
function is_admin(){
	return strtolower(get_user('group')) === 'admin';
}

/**
 * Check if the current user is eligible to perform an action.
 * 
 * @return 	boolean
 * @since 	1.0
 **/
function is_eligible(){
	return is_admin() && is_loggedin();
}

/**
 * Prints codes in HTML <pre> block.
 * 
 * @param 	mixed 	$input 		The thing to print.
 * @param 	bool 	$return		Set to true to return the output instead of echoing it.
 * @return 	string
 * @since 	0.1.38
 **/
function print_p( $input, $return = false ){
	if ( '' === $input ) return '';

	$input = sprintf( '<pre>%s</pre>', print_r($input, true) );

	if ( $return )
		return $input;

	echo $input;
}


/**
 * Get settings.
 * 
 * @return 	array
 * @since 	1.0
 **/
function get_settings( $key = '' ){
	$settings = require( ABSPATH . INC . '/settings.php' );
	
	if ( empty($key) )
		return $settings;

	return parse_arg($key, $settings);
}

/**
 * Get the places list.
 * 
 * @return 	array
 * @since 	1.0
 **/
function get_places(){
	$places = (array)get_settings( 'places' );

	$new_place = array();
	foreach ( $places as $key => $placedata ){
		$label = parse_arg( 'label', $placedata );
		if ( empty($label) )
			$label = $key;

		$new_place[$key] = $label;
	}

	return $new_place;
}

/**
 * Get place label.
 * 
 * @return 	string
 * @since 	1.0
 **/
function get_place_label( $key ){
	$places = get_places();
	$label 	= parse_arg( $key, $places );

	return empty($label) ? 'Undefined' : $label;
}

/**
 * Get the booking status badge.
 * 
 * @return 	string
 * @since 	1.0
 **/
function get_status_badge( $book_status ){
	$book_status = strtolower($book_status);
	$statuses = array('approved' => 'primary', 'completed' => 'success', 'pending' => 'warning', 'unknown' => 'secondary');

	$colour = parse_arg( $book_status, $statuses );
	if ( !array_key_exists($book_status, $statuses) || empty($book_status) ){
		$book_status = 'unknown';
		$colour = 'secondary';
	}

	return sprintf( '<span class="badge badge-%1$s">%2$s</span>', $colour, strtoupper($book_status) );
}

/**
 * Convert booking date to timestamp.
 * 
 * @return 	integer
 * @since 	1.0
 **/
function date2time( $date ){
	if ( empty($date) ) return 0;

	// Format: d/m/y
	$date = explode('/', $date);
	list($day, $month, $year) = (array)$date;

	if ( strlen($year) < 4 ){
		$datetime 	= DateTime::createFromFormat('y', $year);
		$year 		= $datetime->format('Y');
	}

	return mktime( 00, 00, 00, (int)$month, (int)$day, $year );
}

/**
 * Get booking action link.
 * 
 * @return 	string
 * @since 	1.0
 **/
function get_action_link( $status, $id ){
	$links = array(
		'pending' => 'approve_booking',
		'approved' => 'complete_booking'
	);

	if ( !$status || !$id || !array_key_exists($status, $links) ) return '';

	$action = parse_arg(strtolower($status), $links);

	return "../process.php?action={$action}&id={$id}";
}

/**
 * Get the booking action button.
 * 
 * @return 	string
 * @since 	1.0
 **/
function get_action_button( $status, $id ){
	$buttons = array(
		'pending' => '<a class="btn btn-primary btn-sm" href="%s"><i class="fa fa-check"></i> Approve</a>',
		'approved' => '<a class="btn btn-success btn-sm" href="%s"><i class="fa fa-check"></i> Complete</a>'
	);

	if ( !$status || !$id || !array_key_exists($status, $buttons) ) return '';

	$link 	= get_action_link( $status, $id );
	$button = parse_arg(strtolower($status), $buttons);

	return sprintf( $button, $link );
}