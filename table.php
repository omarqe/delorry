<?php

require_once( 'includes/load.php' );

$tables = array(
	array(
		'name' => 'user',
		'primary' => ['user_id', 'username'],
		'columns' => [
			'user_id' => 'int auto_increment',
			'username' => 'varchar(100) not null',
			'password' => 'varchar(100) not null',
			'email' => 'varchar(100) not null',
			'phone' => 'varchar(100) not null',
			'group' => 'varchar(50) not null'
		]
	),

	array(
		'name' => 'booking',
		'primary' => 'book_id',
		'columns' => [
			'book_id' => 'int auto_increment',
			'book_user_id' => 'int(10) not null',
			'book_date' => 'varchar(100) not null',
			'book_from' => 'varchar(100) not null',
			'book_to' => 'varchar(100) not null',
			'book_status' => 'varchar(50) not null',
			'book_vehicle' => 'varchar(100) not null',
			'book_price' => 'float not null'
		]
	)
);

// Create the tables
foreach ( $tables as $index => $table ){
	if ( is_array($table) ){
		$name 	= $table['name'];
		$prime	= $table['primary'];
		$columns = $table['columns'];

		if ( isset($columns) && is_array($columns) ){
			foreach( $columns as $k => $v ){
				if ( @$db->create( $name, $columns, !empty($prime) ? $prime : '', 'InnoDB' ) ){
					echo "<span style=\"color:green\">Table '$name' is successfully created.</span><br/>";
					break;
				} else {
					echo "<span style=\"color:red\">Table '$name' cannot be created.</span><br/>";
					break;
				}
			}
		}
	}
}