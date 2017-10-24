<?php
/**
 * Database Helper Class
 * 
 * THIS NOTICE MUST STAY INTACT FOR LEGAL USE.
 * ================================================================================
 * By using this class, you agreee to use it for personal purpose only.
 * Redistribution of this helper class will result in LEGAL ACTION or any action
 * necessary.
 * 
 * @author 		Omar Mokhtar Al-Asad
 * @link 		https://omarqe.com
 * @license 	http://www.gnu.org/licenses/
 **/

/**
 * Column selector. Supply an array of column and join them with commas.
 * 
 * @param	array 			$columns	The columns to select.
 * @param	array 			$implode	Implode the columns with commas.
 * @return	string|array 	
 * @since	0.1.42
 **/
function select_columns( $columns, $implode = true ){
	if ( is_array($columns) && $implode && !empty($columns) )
		return implode( ',', $columns );
	elseif ( empty($columns) )
		return '*';

	return $columns;
}

/**
 * Real escape string.
 * 
 * @return	mixed
 * @since	0.1.45
 **/
function real_escape( $string ){
	global $ptdb;
	return $ptdb->escape( $string );
}

/**
 * The database class.
 * 
 * Uses BW database class to adapt with the Paystur system.
 * 
 * @package 	BW
 * @subpackage	Database
 * @since 		0.1
 **/
class PS_Database {
	/**
	 * The database host
	 * 
	 * @var 	String
	 * @since 	0.1
	 **/
	public $dbhost = '';

	/**
	 * The database user
	 * 
	 * @var 	String
	 * @since 	0.1
	 **/
	public $dbuser = '';

	/**
	 * The database name
	 * 
	 * @var 	String
	 * @since 	0.1
	 **/
	public $dbname = '';

	/**
	 * The database password
	 * 
	 * @var 	String
	 * @since 	0.1
	 **/
	public $dbpass = '';

	/**
	 * The table prefix
	 * 
	 * @var 	String
	 * @since 	0.1
	 **/
	public $prefix = '';

	public $insert_id;

	public $num_rows;

	public $result;

	public $query_string;

	public $query_count = 0;

	public $queries = array();

	public $total_exec_time = 0;

	public $exec_time = 0;

	/**
	 * The list of tables.
	 * 
	 * @var		array
	 * @access	public
	 * @since	0.1.44
	 **/
	public $tables = array();

	/**
	 * A list of character set
	 * 
	 * @var 	Array
	 * @since 	0.1
	 **/
	public $charset = array( 'big5'
		, 'dec8'
		, 'cp850'
		, 'hp8'
		, 'koi8r'
		, 'latin1'
		, 'latin2'
		, 'swe7'
		, 'ascii'
		, 'ujis'
		, 'sjis'
		, 'hebrew'
		, 'tis620'
		, 'euckr'
		, 'koi8u'
		, 'gb2312'
		, 'greek'
		, 'cp1250'
		, 'gbk'
		, 'latin5'
		, 'armscii8'
		, 'utf8'
		, 'ucs2'
		, 'cp866'
		, 'keybcs2'
		, 'macce'
		, 'macroman'
		, 'cp852'
		, 'latin7'
		, 'cp1251'
	);

	public $debug = true;

	public $mysqli;

	public $stat;

	public $query;
	
	/**
	 * Constructor method
	 * 
	 * Connects to the database once the class is called.
	 * 
	 * @param 	string 	$dbhost 	The database host
	 * @param 	string 	$dbuser 	The database user
	 * @param 	string 	$dbpass 	The database password
	 * @param 	string 	$dbname 	The database name
	 * @param 	string 	$prefix 	The table prefix (in case if we are going to
	 * 								run multiple app in a single database).
	 * @since 	0.1
	 **/
	function __construct( $dbhost, $dbuser, $dbpass, $dbname, $prefix = '', $tables = '' ){
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbname = $dbname;
		$this->dbpass = $dbpass;
		
		$this->prefix = !empty($prefix) && is_string($prefix) ? $prefix : "";

		$connect = @$this->connect();

		if ( !$connect ){
			printf(
				'<h1>Error establishing database connection.</h1> <b>Error %s:</b> %s',
				$this->mysqli->connect_errno,
				$this->mysqli->connect_error
			);
			exit;
		}

		$this->init_tables($tables);
		$this->tables = $tables;
		$this->stat = $this->mysqli->stat();
		$this->debug = IS_DEBUG;
	}

	function init_tables( $tables ){
		if ( !isset($tables) || !is_array($tables) )
			return false;

		foreach( $tables as $table )
			$this->$table = $this->prefix . $table;

		return true;
	}

	/**
	 * Connects to the database
	 * 
	 * @since 	0.1
	 **/
	public function connect(){
		$this->mysqli = new mysqli( $this->dbhost, $this->dbuser, $this->dbpass, $this->dbname );

		if ( $this->mysqli->connect_errno )
			return false;

		return true;
	}

	/**
	 * Executes an SQL query and escape the arguments passed to it.
	 * 
	 * @param 	string 	$query 		The query string.
	 * @param 	mixed 	*$args 		The valuto be escaped.
	 * @since 	0.1
	 **/
	function query( $query, $args = '' ){
		if ( empty($query) )
			return false;

		if ( !empty($args) && !is_array($args) )
			$args = array_slice(func_get_args(), 1);

		$args = $this->escape( $args );
		$query = $this->query_string = trim( vsprintf($query, $args) );
		$prepare = $this->mysqli->prepare( $query );

		$bt = debug_backtrace();
		$error_txt = '<h5>Reason [%1$s]:</h5> <small>%2$s</small><h5>MySQL Query:</h5><small>%3$s</small><h5>Further Info:</h5><small>Called in %4$s by <code>%5$s()</code> on line %6$d.</small>';

		if ( !$prepare && $this->debug ){
			go_die( 'Database Error', $error_txt, $this->mysqli->errno, $this->mysqli->error, $query, $bt[1]['file'], $bt[1]['function'], $bt[1]['line'] );
			die();
		}

		$time = microtime();
		if( $this->query = $this->mysqli->query( $query ) ){
			$this->exec_time = microtime()-$time;
			$this->queries[] = array(
				'query' => $query,
				'time'	=> round($this->exec_time, 7),
				// 'trace' => 'Called by ' . $bt[1]['function'] . '() on line ' . $bt[1]['line'] . ' in ' . $bt[1]['file']
			);
			$this->query_count = count(array_keys($this->queries));

			if ( !empty($this->queries) && is_array($this->queries) ){
				foreach ( $this->queries as $i => $query_info )
					$this->total_exec_time += parse_arg('time', $query_info);
			}

			$this->total_exec_time = round($this->total_exec_time, 7);
			return true;
		}
		
		is_debug()
		? set_error($this->mysqli->error . '.', 'database_error')
		: set_error("We cannot perform this action at the moment.", "database_error");

		return false;
	}

	/**
	 * Runs a SELECT SQL query.
	 * 
	 * @param 	string 	$table 		The table name
	 * @param 	array 	$where 		Optional. The WHERE clause.
	 * @param 	array 	$columns 	The columns to select
	 * @param 	string 	$clause 	The extra clause to be added at the end of
	 * 								the SQL query.
	 * @since 	0.1
	 **/
	function select( $table, $where = '', $columns = '', $clause = '' ){
		if ( empty($table) || !empty($where) && !is_array($where) )
			return false;

		$the_where = '';
		$v = '';

		if ( !empty($where) ){
			$cols = array_keys($where);

			foreach ( $where as $column => $value ){
				$concat = 'AND';

				$column = str_replace( ' ', '', trim($column) );

				$col_pfx = substr($column, 0, 3);
				if ( $col_pfx === 'or:' || $col_pfx === 'OR:' ){
					$concat = 'OR';
					$column = substr($column, 3);
				}

				$fmt = $this->_get_format($value);

				$whr[]  = "`$column` = '$fmt'";

				$v[] = $value;
			}

			$the_where = " WHERE " . implode( " $concat ", $whr ) . ' ';
		}

		if ( is_array($columns) && !empty($columns) )
			$col = '`' . implode( '`, `', (array)$columns ) . '`';
		elseif ( is_string($columns) && !empty($columns) )
			$col = "$columns";
		else
			$col = "*";

		$q  = "SELECT $col FROM `".$this->get_table_name($table)."`";
		$q .= $the_where;
		$q .= $clause;

		$q = trim($q);

		return $this->query( $q, $v );
	}

	/**
	 * Add a new record into a table.
	 * 
	 * @param 	string 	$table 		The table name.
	 * @param 	array 	$data 		The data to be inserted in a record.
	 * @param 	array 	$format 	The format of the value of the data.
	 * @see 	$this->_insert()
	 * @since 	0.1.0
	 **/
	function insert( $table, $data, $format = '', $ignore = false ){
		$r = $this->_insert( $table, $data, $format, 'INSERT', $ignore );
		$this->insert_id = $this->mysqli->insert_id;

		return $r;
	}

	/**
	 * Insert on duplicate. This method allows multiple rows insertion, but if we find that the row is duplicated, the
	 * query perform update on the specific row instead of inserting a new one. ON DUPLICATE KEY query works best with
	 * the composite key feature in the table.
	 * 
	 * @param	string	$table		The table name.
	 * @param	array 	$columns	The column names.
	 * @param	array 	$values		The values. The arrangement of values is complement to the arrangement of column.
	 * 
	 * @return	boolean
	 * @since	0.1.44
	 **/
	public function insert_duplicate( $table, $columns, $values ){
		if ( empty($table) || empty($columns) || !is_array($columns) || empty($values) || !is_array($values) )
			return false;

		$column = implode( ',', $columns );
		$table = $this->get_table_name( $table );
		$value = '';

		// Determine if we're inserting multiple rows.
		if ( array_depth($values) > 1 ){
			$the_values = array();
			foreach ( $values as $val ){
				if ( is_string($val) )
					$val = explode( ',', $val );

				// Data must be escaped first before serialization.
				$val = $this->escape( $val );

				foreach ( (array)$val as $k => $each_value )
					$val[$k] = maybe_serialize($each_value);

				$val = array_trim_value( $val );
				if ( count($val) != count($columns) && set_error("The values do not match the columns.", "value_column_err") ){
					return false;
				}

				$val = $this->escape( $val );
				$val = __( "('%s')", implode("', '", $val) );
				$the_values[] = $val;
			}

			$value = implode( ', ', $the_values );
		} else {
			$value = array_trim_value( $values );
			$value = $this->escape( $value );

			foreach ( (array)$value as $k => $each_value )
				$value[$k] = maybe_serialize($each_value);

			$value = implode( "', '", $value );
			$value = "('$value')";
		}


		// On duplicate columns
		$duplicate_values = array();
		foreach ( $columns as $col )
			$duplicate_values[] = "$col = VALUES($col)";

		// We're not performing insert if we found that the value query is empty (just in case).
		if ( empty($value) || empty($columns) )
			return false;

		// Write the query
		$duplicate_values = implode( ', ', $duplicate_values );
		$query = "INSERT INTO $table ($column) VALUES $value ON DUPLICATE KEY UPDATE $duplicate_values";

		return $this->query( $query );
	}

	public function replace( $table, $data, $format = '' ){
		return $this->_insert( $table, $data, $format, 'REPLACE' );
	}

	/**
	 * Insert into dataase.
	 * 
	 * @param	string	$table	The table name.
	 * @param	array 	$data	The data to insert.
	 * @param	array 	$format	The format.
	 * @param	string	$type	The command, choose whether to insert or replace.
	 * @param	boolean	$ignore	Ignore when there's a duplicate entry.
	 * 
	 * @return	boolean
	 * @since	0.1.44
	 **/
	public function _insert( $table, $data, $format = '', $type = 'INSERT', $ignore = false ){
		if ( !is_array($data) || !in_array(strtoupper($type), array('INSERT', 'REPLACE')) )
			return false;

		$cols = array_keys($data);
		$val = '';

		foreach( $data as $column => $value ){
			$fmt[] = $this->_get_format($value);
			if ( !empty($format) && count($format) == count($data) )
				$fmt = $format;

			$val[] = $value;
		}

		$ignore = $ignore ? " IGNORE " : "";

		$q = $type . "$ignore INTO `".$this->get_table_name($table)."` ( `". implode('`, `', $cols ) ."` ) VALUES ( '" . implode("', '", $fmt) . "' )";

		return $this->query( $q, $val );
	}

	/**
	 * Update a record in the database.
	 * 
	 * @param 	string 	$table 	The table name.
	 * @param 	array 	$data 	The data to update, key-value pair array.
	 * @param 	array 	$where 	The WHERE clause, indicating which row to update.
	 * 
	 * @return 	boolean
	 * @since 	0.0.1
	 **/
	function update( $table, $data, $where, $format = '' ){
		if( empty($table) || empty($data) || !is_array($data) || empty($where) || !is_array($where) )
			return false;

		foreach ( $data as $column => $value ){
			$fmt = $this->_get_format($value);

			if ( !empty($format) && is_array($format) )
				$fmt = array_shift($format);

			$set[] = "`$column` = '$fmt'";
		}

		$w = '';
		if ( !empty($where) && is_array($where) ){
			foreach ( $where as $k => $v ){
				$concat = 'AND';

				$k = str_replace( ' ', '', trim($k) );
				$col_pfx = substr($k, 0, 3);
				if ( $col_pfx === 'or:' || $col_pfx === 'OR:' ){
					$concat = 'OR';
					$k = substr($k, 3);
				}

				$w[]  = "`$k` = '".$this->_get_format($v)."'";
			}

			$w = implode(" $concat ", $w);
		}

		$q = "UPDATE `".$this->get_table_name($table)."` SET " . implode( ', ', $set ) . " WHERE " . $w;

		$val = array_merge( $data, array_values($where) );

		// echo vsprintf($q, $val);

		// echo $q;
		return $this->query($q, $val);
	}

	// DELETE FROM `table` WHERE column = 'value' LIMIT 1
	function delete( $table, $where, $limit = '' ){
		if ( empty($table) || empty($where) || !is_array($where) )
			return false;

		if ( empty($limit) )
			$limit = 1;

		foreach( $where as $column => $value ){
			$concat = 'AND';

			if ( preg_match("/^\[OR\]/", $column) ){
				$concat = 'OR';
				$column = substr($column, 4);
			}

			$w[]  = "$column = '".$this->_get_format($value)."'";

			$val[] = $value;
		}
		
		$limit = !empty($limit) && is_integer($limit) ? " LIMIT " . $limit : '';

		return $this->query( "DELETE FROM `".$this->get_table_name($table)."` WHERE " . implode( " $concat ", $w ) . $limit, $val );
	}

	function drop( $table ){
		if ( empty($table) )
			return false;

		return $this->query( "DROP TABLE IF EXISTS {$this->prefix}$table" );
	}

	function create( $table_name, $columns, $primary_key = '', $engine = 'MyISAM', $charset = 'utf8', $has_prefix = true ){
		$this->drop( $this->prefix . $table_name );
		
		if ( !is_array($columns) )
			return false;
		
		if ( is_array($columns) ){
			foreach ( $columns as $col => $attr ){
				$col_attr[]	= "`$col` $attr";
			}
		}
		
		if ( !$has_prefix )
			$this->prefix = '';
		
		$query	 = "CREATE TABLE `{$this->prefix}$table_name` (\n";
		$query	.= join( ",\n", $col_attr );
		
		$the_column	= array_keys($columns);
		
		if ( !empty($primary_key) && (is_string($primary_key) && in_array($primary_key, $the_column)) || is_array($primary_key) ){
			if ( is_array($primary_key) ){
				foreach ( $primary_key as $i => $key ){
					if ( !in_array($key, $the_column) )
						unset($primary_key[$i]);
				}

				if ( !empty($primary_key) )
					$primary_key = implode( '`,`', $primary_key );
			}

			if ( !empty($primary_key) )
				$query	.= ",\nPRIMARY KEY(`$primary_key`)";
		}
		
		if ( !$this->is_valid_charset($charset) )
			$charset = 'utf8';

		$engine = empty($engine) ? 'MyISAM' : $engine;
		
		$query	.= "\n)\nENGINE=$engine DEFAULT CHARSET=$charset;";
		
		return $this->query( $query );
	}

	function is_valid_charset( $string ){
		$charset	= array_values($this->charset);
		
		if ( in_array($string, $charset) )
			return true;
	}

	function _get_format( $value = '' ){
		if ( empty($value) )
			$fmt = '%s';
		else
			$fmt = is_integer($value) ? '%d' : '%s';

		return $fmt;
	}

	function num_rows(){
		return $this->query->num_rows;
	}

	/**
	 * Determine whether the query return a result or otherwise.
	 * 
	 * @return 	boolean
	 * @since 	0.1.34
	 **/
	public function have_result(){
		if ( $this->num_rows() < 1 )
			return false;

		return true;
	}

	function fetch( $as_object = false, $q = '' ){
		if ( !empty($q) && is_object($q) )
			return $this->result = $as_object ? $this->$q->fetch_object() : $this->$q->fetch_assoc();

		return $this->result = $as_object ? $this->query->fetch_object() : $this->query->fetch_assoc();
	}

	function escape( $data ){
		if ( empty($data) )
			return false;

		if ( is_array($data) ){
			foreach( $data as $key => $value ){
				if ( is_array($value) )
					$data[$key] = $this->escape($value);
				else
					$data[$key] = $this->real_escape($value);
			}
		} else {
			$data = $this->real_escape($data);
		}

		return $data;
	}

	function real_escape( $str ){
		if ( !is_serialized($str) ){
			$str = str_replace("%", "%%", $str);
			// $str = strip_tags($str);
			$str = htmlspecialchars($str, ENT_NOQUOTES);
		}

		$str =  $this->mysqli->real_escape_string($str);

		return $str;
	}

	function get_table_name( $table ){
		$table = substr($table, 0, strlen($this->prefix)) != $this->prefix
			? $this->prefix . $table
			: $table;

		return $table;
	}
}