<?php
define( 'ABSPATH', dirname(dirname(__FILE__)) );
define( 'INC', '/includes/' );

// Load configuration file
require_once( ABSPATH . INC . 'config.php' );

// Load libraries
require_once( ABSPATH . INC . 'class-database.php' );
require_once( ABSPATH . INC . 'functions.php' );


// Create database object
$db = new PS_Database( DB_HOST, DB_USER, DB_PASS, DB_NAME );