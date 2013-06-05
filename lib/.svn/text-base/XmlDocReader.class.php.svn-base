<?php

/**
 * XmlDocReader 
 * 
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class XmlDocReader
{
	private $xml_data;

	public function loadFromUrl($url)
	{
		$stream = @fopen($url, 'r');
		if (!$stream) {
			return false;
		}
		$xml = stream_get_contents($stream);
		@fclose($stream);

		return $this->loadFromString($xml);
	}

	public function loadFromFile($filename)
	{
		// make sure that the file does exist and is readable
		if (!is_file($filename) || !is_readable($filename)) {
			return false;
		}
		$xml = file_get_contents($filename);
		return $this->loadFromString($xml);
	}

	public function loadFromString($xml)
	{
		$this->xml_data = @simplexml_load_string($xml);
		return ($this->xml_data instanceof SimpleXMLElement);
	}

	public function getStatusLists()
	{
		$statuses = array();
		if (!$this->xml_data) {
			return $statuses;
		}
		foreach($this->xml_data->statuses->status as $status) {
			$statuses[sha1($status->var_name)] = array(
				'var_name' => (string) $status->var_name,
				'value'    => (string) $status->value,
			);
		}
		return $statuses;
	}

	public function getDatabaseLists()
	{
		$databases = array();
		if (!$this->xml_data) {
			return $databases;
		}
		foreach($this->xml_data->databases->database as $database) {
			$databases[sha1($database->name)] = array(
				'name'        => (string) $database->name,
				'fingerprint' => (string) $database->fingerprint,
			);
		}
		return $databases;
	}

	public function getTableLists()
	{
		$tables = array();
		if (!$this->xml_data) {
			return $tables;
		}
		foreach($this->xml_data->tables->table as $table) {
			$tables[sha1($table->name)] = array(
				'name'        => (string) $table->name,
				'fingerprint' => (string) $table->fingerprint,
				'engine'      => (string) $table->engine,
				'charset'     => (string) $table->charset,
			);
		}
		return $tables;
	}

	public function getTableColumns()
	{
		$columns = array();
		if (!$this->xml_data) {
			return $columns;
		}
		foreach($this->xml_data->columns->column as $column) {
			$columns[sha1($column->name)] = array(
				'name'       => (string) $column->name,
				'type'       => (string) $column->type,
				'collation'  => (string) $column->collation,
				'null'       => (string) $column->null,
				'key'        => (string) $column->key,
				'default'    => (string) $column->default,
				'extra'      => (string) $column->extra,
				'privileges' => (string) $column->privileges,
				'comment'    => (string) $column->comment,
			);
		}
		return $columns;
	}

	public function getTableKeys()
	{
		$keys = array();
		if (!$this->xml_data) {
			return $keys;
		}
		foreach($this->xml_data->keys->key as $key) {
			$keys[sha1($key->key_name . $key->column_name)] = array(
				'non_unique'   => (string) $key->non_unique,
				'key_name'     => (string) $key->key_name,
				'seq_in_index' => (string) $key->seq_in_index,
				'column_name'  => (string) $key->column_name,
				'collation'    => (string) $key->collation,
				'cardinality'  => (string) $key->cardinality,
				'sub_part'     => (string) $key->sub_part,
				'packed'       => (string) $key->packed,
				'null'         => (string) $key->null,
				'index_type'   => (string) $key->index_type,
				'comment'      => (string) $key->comment,
			);
		}
		return $keys;
	}

	public function getTableCreateSqls()
	{
		if (!$this->xml_data) {
			return "";
		}
		return (string) $this->xml_data->create_table_sql;
	}

	public function getTableCharsets()
	{
		if (!$this->xml_data) {
			return "";
		}
		return (string) $this->xml_data->charset;
	}

	public function getTableEngines()
	{
		if (!$this->xml_data) {
			return "";
		}
		return (string) $this->xml_data->engine;
	}
}

