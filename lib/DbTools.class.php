<?php

/**
 * DbTools 
 * 
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class DbTools
{
	static $instance;

	private $_connection;

	public function __construct($db_host, $db_user, $db_password)
	{
		$this->_connection = mysql_connect($db_host, $db_user, $db_password);

		if (!$this->_connection) {
			throw new Exception('DB Error: Could not connect to the Database Server');
		}
	}

	public function __destruct()
	{
		if ($connection = $this->getConnection()) {
			mysql_close($connection);
		}
	}

	public function getInstance()
	{
		global $_CONFIG;
	
		if(!self::$instance) {
			self::$instance = new DbTools($_CONFIG['db_host'], $_CONFIG['db_user'], $_CONFIG['db_pass']);
		}
		return self::$instance;
	}

	public function getConnection()
	{
		return $this->_connection;
	}

	public function getDatabaseStatus()
	{
		$result = mysql_query("SHOW STATUS", $this->getConnection());

		if (!$result) {
			return array();
		}
		$db_status = array();
		while ($row = mysql_fetch_row($result)) {
			$db_status[] = array(
					'var_name' => $row[0],
					'value'    => $row[1],
					);
		}
		sort($db_status);

		return $db_status;
	}

	public function getDatabaseList($starting_with)
	{
		$mysql_list_dbs = mysql_list_dbs($this->getConnection());

		$db_list = array();
		while ($row = mysql_fetch_object($mysql_list_dbs)) {
			$db_name = $row->Database;

			if ($db_name == 'information_schema' || $db_name == 'mysql') {
				continue;
			}
			if ($this->canListDatabase($starting_with, $db_name)) {
				$db_list[] = array (
					'name'        => $db_name,
					'fingerprint' => $this->getDatabaseFingerPrint($db_name),
				);
			}
		}
		sort($db_list);

		return $db_list;
	}

	public function getTableList($database)
	{
		$sql = sprintf("SHOW TABLES FROM `%s`", mysql_real_escape_string($database));

		$result = mysql_query($sql, $this->getConnection());

		if (!$result) {
			return array();
		}
		$table_list = array();
		while ($row = mysql_fetch_row($result)) {
			$table = $row[0];
			$table_list[] = array(
				'name'        => $table,
				'fingerprint' => $this->getTableFingerPrint($database, $table),
				'engine'      => $this->getTableEngine($database, $table),
				'charset'     => $this->getTableCharacterSet($database, $table),
			);
		}
		sort($table_list);

		return $table_list;
	}

	public function getTableColumns($database, $table)
	{
		$sql = sprintf("SHOW FULL FIELDS FROM `%s`.`%s`", mysql_real_escape_string($database), mysql_real_escape_string($table));

		$result = mysql_query($sql, $this->getConnection());

		if (!$result) {
			array();
		}
		$columns = array();
		while($row = mysql_fetch_row($result)) {
			$columns[$row[0]] = array(
				'name'       => $row[0],
				'type'       => $row[1],
				'collation'  => $row[2],
				'null'       => $row[3],
				'key'        => $row[4],
				'default'    => $row[5],
				'extra'      => $row[6],
				'privileges' => $row[7],
				'comment'    => $row[8],
			);
		}
		return $columns;
	}

	public function getTableKeys($database, $table)
	{
		$sql = sprintf("SHOW KEYS FROM `%s`.`%s`", mysql_real_escape_string($database), mysql_real_escape_string($table));

		$result = mysql_query($sql, $this->getConnection());

		if (!$result) {
			array();
		}
		$keys = array();
		while($row = mysql_fetch_row($result)) {
			$keys[] = array(
				'table'        => $row[0],
				'non_unique'   => $row[1],
				'key_name'     => $row[2],
				'seq_in_index' => $row[3],
				'column_name'  => $row[4],
				'collation'    => $row[5],
				'cardinality'  => $row[6],
				'sub_part'     => $row[7],
				'packed'       => $row[8],
				'null'         => $row[9],
				'index_type'   => $row[10],
				'comment'      => $row[11],
			);
		}
		return $keys;
	}

	public function getTableCharacterSet($database, $table)
	{
		$create_table_sql = $this->getCreateTableSql($database, $table);

		$string  = substr($create_table_sql, stripos($create_table_sql, 'CHARSET'));
		$pattern = '/CHARSET=([a-zA-Z0-9_]+)/';

		preg_match($pattern, $string, $matches);

		return $matches[1];
	}

	public function getTableEngine($database, $table)
	{
		$create_table_sql = $this->getCreateTableSql($database, $table);

		$string  = substr($create_table_sql, stripos($create_table_sql, 'ENGINE'));
		$pattern = '/ENGINE=([a-zA-Z0-9_]+)/';

		preg_match($pattern, $string, $matches);

		return $matches[1];
	}

	public function getCreateTableSql($database, $table)
	{
		$sql = sprintf("SHOW CREATE TABLE `%s`.`%s`", mysql_real_escape_string($database), mysql_real_escape_string($table));

		$result = mysql_query($sql, $this->getConnection());

		if (!$result) {
			return null;
		}
		$row = mysql_fetch_row($result);

		$create_table_sql = $row[1];

		// remove any AUTO_INCREMENT=### from create table SQL
		$create_table_sql = preg_replace('/ AUTO_INCREMENT=(\d+)/', '', $create_table_sql);

		// enforce the same case for some SQL keywords
		$create_table_sql = preg_replace("/ default '/", " DEFAULT '", $create_table_sql);

		return $create_table_sql;
	}

	public function getDatabaseFingerPrint($database)
	{
		$return = "";
		foreach(self::getTableList($database) as $table) {
			$fingerprint = self::getTableFingerPrint($database, $table['name']);

			if ($fingerprint) $return .= $fingerprint;
		}
		return sha1($return);
	}

	public function getTableFingerPrint($database, $table)
	{
		$create_table_sql = self::getCreateTableSql($database, $table);

		// remove the ENGINE=foo
		$create_table_sql = preg_replace('/ ENGINE=(\w+)/', '', $create_table_sql);

		// PACK_KEYS
		$create_table_sql = preg_replace('/ PACK_KEYS=(\d+)/', '', $create_table_sql);

		return $create_table_sql ? sha1($create_table_sql) : null;
	}

	public function canListDatabase($starting_with, $db_name)
	{
		if (is_null($starting_with) || stristr($db_name, 'test')) {
			return false;
		}
		return ($starting_with == '%') || (strtolower(substr($db_name, 0, 1)) == strtolower($starting_with));
	}
}
