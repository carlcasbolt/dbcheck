<?php

/**
 * CompareEngine 
 * 
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class CompareEngine
{
	private $_selected_servers;
	private $_selected_database;
	private $_selected_table;
	
	private $_database_data;
	private $_table_sctructure_comparison_data = array();

	/**
	 * Initialise this class and create an instance of DatabaseData to retrieve data about the given servers, database and table.
	 * 
	 * @param   array    $selected_servers   - The servers being compared
	 * @param   string   $selected_database  - The database being compared
	 * @param   string   $selected_table     - The table being compared
	 * 
	 * @return  CompareEngine
	 */
	public function __construct($selected_servers, $selected_datebase=null, $selected_table=null)
	{
		$this->_selected_servers   = $selected_servers;
		$this->_selected_database  = $selected_datebase;
		$this->_selected_table     = $selected_table;
		
		$this->setDatabaseData(new DatabaseData($selected_servers, $selected_datebase, $selected_table));
	} // end of __construct()

	public function setDatabaseData($database_data)
	{
		$this->_database_data = $database_data;
	}

	public function getDatabaseData()
	{
		return $this->_database_data;
	}

	/**
	 * @return array(
	 *      'status::[name]' => array(
	 *           'name'  => '[var_name]',
	 *           'data'  => array(
	 *                'serverX' => array(
	 *                     'value' => '[value]',
	 *                     'state' => 'missing|present',
	 *                     'text'  => 'MISSING|present',
	 *                     ),
	 *                'serverY' => array(
	 *                     'value' => '[value]',
	 *                     'state' => 'missing|present',
	 *                     'text'  => 'MISSING|present',
	 *                     ),
	 *                ),
	 *           'state' => 'different|identical',
	 *           ),
	 *      );
	 */
	public function compareStatusLists()
	{
		$data_lists = $this->getDatabaseData()->getStatusLists();
		$available_items = $this->_mergeArrayLists($data_lists);

		asort($available_items);

		$return_data = array();
		// loop over all of the available items and check their state
		foreach($available_items as $hash => $info) {
			$name  = $info['var_name'];

			$different = true;

			$this_fingerprint = false;
			$last_fingerprint = false;

			$data = array();
			foreach($this->_selected_servers as $server_name) {
				if (!isset($data_lists[$server_name][$hash])) {
					$data[$server_name] = array(
							'state' => 'missing',
							'text'  => 'MISSING',
							);
				} else {
					$data[$server_name] = array(
							'state' => 'present',
							'text'  => $data_lists[$server_name][$hash]['value'],
							);
					$this_fingerprint = md5($data_lists[$server_name][$hash]['value']);

					if ($this_fingerprint && $last_fingerprint) {
						$different = ($this_fingerprint != $last_fingerprint);
					}
					$last_fingerprint = $this_fingerprint;
				}
			}
			$state = $different ? 'different' : 'identical';

			$return_data["status::{$name}"] = array(
					'name'  => $name,
					'data'  => $data,
					'state' => $state,
					);
		}
		return $return_data;
	}

	/**
	 * Retrieve the comparison data for a list of databases starting with a given string.
	 * - This could be all of the available databases if the correct wildcard is given.
	 * 
	 * WILDCARD: % (standard MySQL wildcard) 
	 * 
	 * @param   string  $starting_with   - the first letter(s) of a database.
	 * 
	 * @return array(
	 *      'database::[name]' => array(
	 *           'name'  => '[name]',
	 *           'data'  => array(
	 *                'serverX' => array(
	 *                     'state' => 'missing|present',
	 *                     'text'  => 'MISSING|present',
	 *                     ),
	 *                'serverY' => array(
	 *                     'state' => 'missing|present',
	 *                     'text'  => 'MISSING|present',
	 *                     ),
	 *                ),
	 *           'state' => 'different|identical',
	 *           ),
	 *      );
	 */
	public function compareDatabases($starting_with = "")
	{
		if (!$starting_with) return false;

		$data_lists = $this->getDatabaseData()->getDatabaseLists($starting_with);
		$available_items = $this->_mergeArrayLists($data_lists);

		asort($available_items);

		$return_data = array();
		// loop over all of the available items and check their state
		foreach($available_items as $hash => $info) {
			$name = $info['name'];

			$different = true;

			$this_fingerprint = false;
			$last_fingerprint = false;

			$data = array();
			foreach($this->_selected_servers as $server_name) {
				if (!isset($data_lists[$server_name][$hash])) {
					$data[$server_name] = array(
							'state' => 'missing',
							'text'  => 'MISSING',
							);
				} else {
					$data[$server_name] = array(
							'state' => 'present',
							'text'  => 'present',
							);
					$this_fingerprint = $data_lists[$server_name][$hash]['fingerprint'];

					if ($this_fingerprint && $last_fingerprint) {
						$different = ($this_fingerprint != $last_fingerprint);
					}
					$last_fingerprint = $this_fingerprint;
				}
			}
			$state = $different ? 'different' : 'identical';

			$return_data["database::{$name}"] = array(
					'name'  => $name,
					'data'  => $data,
					'state' => $state,
					);
		}
		return $return_data;
	}

	/**
	 * Retrieve an array of information for a list of database tables.
	 * 
	 * @return array(
	 *      'table::[name]' => array(
	 *           'name'  => '[name]',
	 *           'data'  => array(
	 *                'serverX' => array(
	 *                     'state' => 'missing|present',
	 *                     'text'  => 'MISSING|present',
	 *                     ),
	 *                'serverY' => array(
	 *                     'state' => 'missing|present',
	 *                     'text'  => 'MISSING|present',
	 *                     ),
	 *                ),
	 *           'state' => 'different|identical',
	 *           ),
	 *      );
	 */
	public function compareTables()
	{
		$data_lists = $this->getDatabaseData()->getTableLists($this->_selected_database);
		$available_items = $this->_mergeArrayLists($data_lists);

		asort($available_items);

		$return_data = array();
		// loop over all of the available items and check their state
		foreach($available_items as $hash => $info) {
			$name = $info['name'];

			$different = true;

			$this_fingerprint = false;
			$last_fingerprint = false;

			$data = array();
			foreach($this->_selected_servers as $server_name) {
				if (!isset($data_lists[$server_name][$hash])) {
					$data[$server_name] = array(
							'state' => 'missing',
							'text'  => 'MISSING',
							);
				} else {
					$data[$server_name] = array(
							'state'   => 'present',
							'engine'  => $data_lists[$server_name][$hash]['engine'],
							'charset' => $data_lists[$server_name][$hash]['charset'],
							);
					$this_fingerprint = $data_lists[$server_name][$hash]['fingerprint'];

					if ($this_fingerprint && $last_fingerprint) {
						$different = ($this_fingerprint != $last_fingerprint);
					}
					$last_fingerprint = $this_fingerprint;
				}
			}
			$state = $different ? 'different' : 'identical';

			$return_data["table::{$name}"] = array(
					'name'  => $name,
					'data'  => $data,
					'state' => $state,
					);
		}
		return $return_data;
	}

	/**
	 * @return array(
	 *     'columns' => array(),
	 *     'keys'    => array(),
	 *     'other'   => array(
	 *          'charsets' => array(),
	 *          'engines'  => array(),
	 *          'sql'      => array(),
	 *          ),
	 *     );
	 */
	public function compareTableStructure()
	{
		$column_lists      = $this->getDatabaseData()->getTableColumns();
		$key_lists         = $this->getDatabaseData()->getTableKeys();
		$create_table_sqls = $this->getDatabaseData()->getTableCreateSqls();
		$table_charsets    = $this->getDatabaseData()->getTableCharsets();
		$table_engines     = $this->getDatabaseData()->getTableEngines();
		
		$available_columns = $this->_mergeArrayLists($column_lists);
		$available_keys    = $this->_mergeArrayLists($key_lists);

		return array(
				'columns' => $this->_generateTableColumnsComparisonData($available_columns, $column_lists),
				'keys'    => $this->_generateTableKeysComparisonData($available_keys, $key_lists),
				'other'   => array(
					'charsets' => $this->_generateTableStructurePartComparisonData('CHARSET', $table_charsets),
					'engines'  => $this->_generateTableStructurePartComparisonData('ENGINE', $table_engines),
					'sql'      => $this->_generateTableStructurePartComparisonData('CREATE TABLE', $create_table_sqls),
					),
				);
	}

	/**
	 * Analyse the given comparison data for a database level. A new instance of the CompareEngine is created
	 * for each table name present within the given comparison data.
	 * 
	 * returns either a string for the complete ALTER TABLE statement or boolean false if no ALTER TABLE 
	 * statements are required.
	 * 
	 * @param   array  $comparison_data 
	 * @return  mixed
	 */
	public function generateAlterDatabaseStructure($comparison_data)
	{
		if (!$comparison_data) {
			throw new Exception('No Comparison data provided!');
		}

		$alter_sql = array();
		foreach($comparison_data as $table_data) {
			$comparison_engine = new CompareEngine($this->_selected_servers, $this->_selected_database, $table_data['name']);

			$comparison_data = $comparison_engine->compareTableStructure();
			$alter_table     = $comparison_engine->generateAlterTableStructure($comparison_data);

			if (!$alter_table) continue;

			$alter_sql[] = $alter_table;
		}
		if (count($alter_sql)) {
			return implode(";\n\n", $alter_sql).";";
		}
		return false;
	}

	/**
	 * Analyse the given comparison data for a table level generate the ALTER TABLE statement to convert a table
	 * from one version to the other (slave -> master)
	 * 
	 * @param   array   $comparison_data 
	 * @return  string
	 */
	public function generateAlterTableStructure($comparison_data)
	{
		if (!$comparison_data) {
			throw new Exception('No Comparison data provided!');
		}
		$master = $this->_selected_servers['master'];
		$slave  = $this->_selected_servers['slave'];

		$master_sql = $comparison_data['other']['sql']['data'][$master];
		$slave_sql  = $comparison_data['other']['sql']['data'][$slave];

		if ($master_sql['state'] == 'missing') {
			return "DROP TABLE `{$this->_selected_database}`.`{$this->_selected_table}`";
		}
		if ($slave_sql['state'] == 'missing') {
			$pattern = "/CREATE TABLE `{$this->_selected_table}`/";
			$replace = "CREATE TABLE `{$this->_selected_database}`.`{$this->_selected_table}`";

			return preg_replace($pattern, $replace, $master_sql['text']);
		}

		$different_columns = array();
		$different_keys    = array();

		// work out the correct change for each column which is not flagged as identical (drop/add/alter)
		foreach($comparison_data['columns'] as $column) {
			if ($column['state'] == 'identical') {
				continue;
			}
			$column_name = $column['name'];

			if ($column['data'][$master][0]['state'] == 'missing') {
				$different_columns[$column_name] = 'drop';
			} elseif ($column['data'][$slave][0]['state'] == 'missing') {
				$different_columns[$column_name] = 'add';
			} else {
				$different_columns[$column_name] = 'alter';
			}
		}
		
		// work out the correct change for each key which is not flagged as identical (drop/add/alter)
		foreach($comparison_data['keys'] as $key) {
			if ($key['state'] == 'identical') {
				continue;
			}
	
			$key_name = $key['key_name'];
			foreach($key['columns'] as $column_name) {
				if ($key['data'][$master][$column_name]['state'] == 'missing') {
					$different_keys[$key_name][] = 'drop';
				} elseif ($key['data'][$slave][$column_name]['state'] == 'missing') {
					$different_keys[$key_name][] = 'add';
				} else {
					$different_keys[$key_name][] = 'alter';
				}
			}
		}
		
		// if there are no changes to be made then return false here
		if (empty($different_columns) && empty($different_keys)) {
			return false;
		}

		$sql_lines = explode("\n", $master_sql['text']);
		$alter_sql = array();

		// work out the correct ALTER TABLE sql line for each column which we've identified is not identical 
		foreach($different_columns as $column_name => $action) {
			$pattern    = "/|{$column_name}|/";
			$create_sql = false;

			foreach($sql_lines as $line) {
				$line = str_replace("`", "|", $line);
				if (ereg($pattern, $line)) {
					$create_sql = preg_replace('/,$/', '', $line);
					break;
				}
			}
			if ($action == 'add') {
				$alter_sql['column:'.$column_name] = "ADD " . trim(str_replace("|", "`", $create_sql));;
			} elseif ($action == 'drop') {
				$alter_sql['column:'.$column_name] = "DROP COLUMN `{$column_name}`";
			} else {
				$alter_sql['column:'.$column_name] = "CHANGE `{$column_name}` ". trim(str_replace("|", "`", $create_sql));
			}
		}

		// work out the correct ALTER TABLE sql line for each key which we've identified is not identical 
		foreach($different_keys as $key_name => $actions) {
			$pattern    = "/|{$key_name}|/";
			$create_sql = false;

			foreach($sql_lines as $line) {
				$line = str_replace("`", "|", $line);

				if (!strstr($line, 'KEY')) {
					continue;
				}
				if (ereg($pattern, $line)) {
					$create_sql = preg_replace('/,$/', '', $line);
					break;
				}
			}
			
			for ($i = 1; $i < count($actions); $i++) {
				if ($actions[0] == $actions[$i]) {
					$action = $actions[0];
				} else {
					$action = 'alter';
					break;
				}
			}

			if ($action == 'add') {
				$alter_sql['key:'.$key_name] = "ADD ". trim(str_replace("|", "`", $create_sql));
			} elseif ($action == 'drop') {
				if ($key_name == 'PRIMARY') {
					$alter_sql['key:'.$key_name] = "DROP PRIMARY KEY";
				} else {
					$alter_sql['key:'.$key_name] = "DROP KEY `{$key_name}`";
				}
			} else {
				$alter_sql['key:'.$key_name.'1'] = "DROP KEY `{$key_name}`";
				$alter_sql['key:'.$key_name.'2'] = "ADD ". trim(str_replace("|", "`", $create_sql));
			}
		}
		return "ALTER TABLE `{$this->_selected_database}`.`{$this->_selected_table}`\n  " . implode(",\n  ", $alter_sql);
	}

	/**
	 * compare table columns
	 * 
	 * @param array $available_columns 
	 * @param array $column_lists 
	 * 
	 * @return array(
	 *      'column::[name]' = array(
	 *           'name'  => '[name]',
	 *           'data'  => array(
	 *                'serverX' => array(
	 *                     'state'   => 'missing|present',
	 *                     'text'    => 'MISSING|present',
	 *                     'colspan' => [integer],
	 *                     ),
	 *                'serverY' => array(
	 *                     'state'   => 'missing|present',
	 *                     'text'    => 'MISSING|present',
	 *                     'colspan' => [integer],
	 *           ),
	 *           'state' => 'different|identical',
	 *      ),
	 */
	private function _generateTableColumnsComparisonData($available_columns = array(), $column_lists = array())
	{
		$return_data = array();
		foreach($available_columns as $column_hash => $column_info) {
			$different = true;

			$this_fingerprint = false;
			$last_fingerprint = false;

			$name = $column_info['name'];
			$data = array();
			foreach($this->_selected_servers as $server_name) {
				if (!isset($column_lists[$server_name][$column_hash])) {
					$data[$server_name][] = array(
							'state'   => 'missing',
							'text'    => 'MISSING',
							'colspan' => 3,
							);
				} else {
					$type    = $column_lists[$server_name][$column_hash]['type'];
					$null    = $column_lists[$server_name][$column_hash]['null'] ? 'YES' : 'NO';
					$default = $column_lists[$server_name][$column_hash]['default'];

					$data[$server_name][] = array(
							'state' => 'present',
							'text'  => $type,
							);
					$data[$server_name][] = array(
							'state' => 'present',
							'text'  => $null,
							);
					$data[$server_name][] = array(
							'state' => 'present',
							'text'  => $default,
							);
					$this_fingerprint = md5($type.$null.$default);

					if ($this_fingerprint && $last_fingerprint) {
						$different = ($this_fingerprint != $last_fingerprint);
					}
					$last_fingerprint = $this_fingerprint;
				}
			}
			$state = $different ? 'different' : 'identical';

			$return_data['column::'.$name] = array(
					'name'  => $name,
					'data'  => $data,
					'state' => $state,
					);
		}
		return $return_data;
	}

	/**
	 * compare table keys
	 * @param array $available_keys 
	 * @param array $key_lists 
	 * 
	 * @return array(
	 *      'key::[key_name]' = array(
	 *           'columns'  => array(
	 *                '[column_name]' => '[column_name]',
	 *                ),
	 *           'data'     => array(   
	 *                'serverX' => array(
	 *                     'state'   => 'missing|present',
	 *                     'text'    => 'MISSING|[column_name]',
	 *                     ),
	 *                'serverY' => array(
	 *                     'state'   => 'missing|present',
	 *                     'text'    => 'MISSING|[column_name]',
	 *                     ),
	 *           'type'     => array(   
	 *                'serverX' => array(
	 *                     'state'   => 'missing|present',
	 *                     'text'    => 'MISSING|[key_type]',
	 *                     'colspan' => [integer],
	 *                     ),
	 *                'serverY' => array(
	 *                     'state'   => 'missing|present',
	 *                     'text'    => 'MISSING|',
	 *                     'colspan' => [integer],
	 *                     ),
	 *           'key_name' => [key_name],
	 *           'rowspan'  => [integer],
	 *           'state'    => 'different|identical',
	 *      ),
	 */
	private function _generateTableKeysComparisonData($available_keys = array(), $key_lists = array())
	{
		$return_data = array();
		foreach($available_keys as $key_hash => $key_info) {
			$different = true;

			$this_fingerprint = false;
			$last_fingerprint = false;

			$key_name = $key_info['key_name'];
			$key_id   = 'key::'.$key_name;

			$columns = array();
			$data    = array();
			$type    = array();

			if (isset($return_data[$key_id])) {
				$columns = $return_data[$key_id]['columns'];
				$data    = $return_data[$key_id]['data'];
				$type    = $return_data[$key_id]['type'];
			}

			$column_name = $key_info['column_name'];

			foreach($this->_selected_servers as $server_name) {
				$columns[$column_name] = $column_name;

				if (!isset($key_lists[$server_name][$key_hash])) {
					$type[$server_name] = array(
							'state'   => 'missing',
							'text'    => 'MISSING',
							);
					$data[$server_name][$column_name] = array(
							'state'   => 'missing',
							'text'    => 'MISSING',
							);
				} else {
					$index_type = $key_lists[$server_name][$key_hash]['index_type'];
					$non_unique = $key_lists[$server_name][$key_hash]['non_unique'];

					$key_type = self::_getNiceKeyType($index_type, $key_name, $non_unique);

					$type[$server_name] = array(
							'state' => 'present',
							'text'  => $key_type,
							);
					$data[$server_name][$column_name] = array(
							'state' => 'present',
							'text'  => $column_name,
							);

					$this_fingerprint = md5($key_type.$column_name);

					if ($this_fingerprint && $last_fingerprint) {
						$different = ($this_fingerprint != $last_fingerprint);
					}
					$last_fingerprint = $this_fingerprint;
				}
			}
			if (@$return_data['key::'.$key_name]['state'] == 'different') {
				$different = true;
			}
			$rowspan = count($columns);
			$state   = $different ? 'different' : 'identical';
			
			$return_data['key::'.$key_name] = array(
					'columns'  => $columns,
					'data'     => $data,
					'type'     => $type,
					'key_name' => $key_name,
					'rowspan'  => $rowspan,
					'state'    => $state,
					);
		}
		return $return_data;
	}

	/**
	 * compare part of the table structure
	 * 
	 * @param   string  $name  
	 * @param   array   $part 
	 * 
	 * @return array(
	 *      'name'  => '[name]',
	 *      'data'  => array(
	 *           'serverX' => array(
	 *                'state' => 'missing|present',
	 *                'text'  => 'MISSING|[part]',
	 *                )
	 *           'serverY' => array(
	 *                'state' => 'missing|present',
	 *                'text'  => 'MISSING|[part]',
	 *                )
	 *           ),
	 *      'state' => 'different|identical',
	 *      )
	 */
	private function _generateTableStructurePartComparisonData($name, $part)
	{
		$different = true;

		$this_fingerprint = false;
		$last_fingerprint = false;

		$data = array();
		foreach($this->_selected_servers as $server_name) {

			if(empty($part[$server_name])) {
				$data[$server_name] = array(
						'state' => 'missing',
						'text'  => 'MISSING',
						);
			} else {
				$data[$server_name] = array(
						'state' => 'present',
						'text'  => $part[$server_name],
						);

				$this_fingerprint = md5($part[$server_name]);

				if ($this_fingerprint && $last_fingerprint) {
					$different = ($this_fingerprint != $last_fingerprint);
				}
				$last_fingerprint = $this_fingerprint;
			}
		}
		$state = $different ? 'different' : 'identical';

		return array(
				'name'  => $name,
				'data'  => $data,
				'state' => $state,
				);
	}

	/**
	 * Lookup method for Key Types
	 * 
	 * @param  string   $index_type 
	 * @param  string   $key_name 
	 * @param  integer  $non_unique 
	 * 
	 * @return string 'FULLTEXT|PRIMARY|UNIQUE|INDEX'
	 */
	private static function _getNiceKeyType($index_type, $key_name, $non_unique)
	{
		if ($index_type == 'FULLTEXT') {
			return 'FULLTEXT';
		} elseif ($key_name == 'PRIMARY') {
			return 'PRIMARY';
		} elseif ($non_unique == '0') {
			return 'UNIQUE';
		} else {
			return 'INDEX';
		}
	}

	/**
	 * 
	 * @param array $array_lists 
	 * 
	 * @return array
	 */
	private function _mergeArrayLists($array_lists = array())
	{
		if (!is_array($array_lists)) {
			return array();
		}
		$available_array_items = array();
		foreach($array_lists as $array_list) {
			if (is_array($array_list)) {
				$available_array_items = array_merge($available_array_items, $array_list);
			}
		}
		return $available_array_items;
	}
}


