<?php

class SampleTestData
{
	public static function getDatabaseStatuses()
	{
		return array(
				array(
					'var_name' => 'Aborted_clients',
					'value'    => '0',
					),
				array(
					'var_name' => 'Aborted_connects',
					'value'    => '0',
					),
				);
	}

	public static function getDatabases()
	{
		return array(
				array(
					'name'        => 'foobar1',
					'fingerprint' => 'eccbc87e4b5ce2fe28308fd9f2a7baf3',
					),
				array(
					'name'        => 'foobar2',
					'fingerprint' => 'ce2f2a7eccbc87e4b5baf3fe28308fd9',
					),
				);
	}

	public static function getTables()
	{
		return array(
				array(
					'name'        => 'foobar1',
					'fingerprint' => 'eccbc87e4b5ce2fe28308fd9f2a7baf3',
					'engine'      => 'MyISAM',
					'charset'     => 'utf8',
					),
				array(
					'name'        => 'foobar2',
					'fingerprint' => 'ce2f2a7eccbc87e4b5baf3fe28308fd9',
					'engine'      => 'MyISAM',
					'charset'     => 'utf8',
					),
				);
	}

	public static function getColumns()
	{
		return array(
				array(
					'name'       => 'id',
					'type'       => 'int(9) unsigned',
					'collation'  => '',
					'null'       => 'NO',
					'key'        => '',
					'default'    => '0',
					'extra'      => '',
					'privileges' => 'select,insert,update,references',
					'comment'    => '',
					),
				array(
					'name'       => 'name',
					'type'       => 'varchar(255)',
					'collation'  => 'utf8_unicode_ci',
					'null'       => '',
					'key'        => '',
					'default'    => '',
					'extra'      => '',
					'privileges' => 'select,insert,update,references',
					'comment'    => '',
					),
				);
	}

	public static function getKeys()
	{
		return array(
				array(
					'table'        => 'foobar1',
					'non_unique'   => '0',
					'key_name'     => 'PRIMARY',
					'seq_in_index' => '1',
					'column_name'  => 'myColumn',
					'collation'    => 'A',
					'cardinality'  => '14',
					'sub_part'     => '',
					'packed'       => '',
					'null'         => '',
					'index_type'   => 'BTREE',
					'comment'      => '',
					),
				array(
					'table'        => 'foobar1',
					'non_unique'   => '0',
					'key_name'     => 'myKey',
					'seq_in_index' => '1',
					'column_name'  => 'myColumn',
					'collation'    => 'A',
					'cardinality'  => '14',
					'sub_part'     => '',
					'packed'       => '',
					'null'         => '',
					'index_type'   => 'BTREE',
					'comment'      => '',
					),
				);
	}

	public static function getCharset()
	{
		return 'utf8';
	}

	public static function getEngine()
	{
		return 'MyISAM';
	}

	public static function getCreateTable()
	{
		$sql  = "CREATE TABLE `foobar1` (\n";
		$sql .= "  `id` int(9) unsigned NOT NULL default '0',\n";
		$sql .= "  `name` varchar(255) collate utf8_unicode_ci NOT NULL default '',\n";
		$sql .= "  `type` varchar(20) collate utf8_unicode_ci NOT NULL default '',\n";
		$sql .= "  PRIMARY KEY  (`id`),\n";
		$sql .= "  UNIQUE KEY `id` (`id`),\n";
		$sql .= "  KEY `id2` (`id`)\n";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

		return $sql;
	}

	public function getDatabaseStatusesXml()
	{
		return self::_generateXml('statuses', 'status', self::getDatabaseStatuses());
	}	

	public function getDatabasesXml()
	{
		return self::_generateXml('databases', 'database', self::getDatabases());
	}

	public function getTablesXml()
	{
		return self::_generateXml('tables', 'table', self::getTables());
	}

	public function getColumnsXml()
	{
		return self::_generateXml('columns', 'column', self::getColumns());
	}

	public function getKeysXml()
	{
		return self::_generateXml('keys', 'key', self::getKeys());
	}

	public static function getCharsetXml()
	{
		return self::_generateBasicXml('charset', self::getCharset());
	}

	public static function getEngineXml()
	{
		return self::_generateBasicXml('engine', self::getEngine());
	}

	public static function getCreateTableXml()
	{
		return self::_generateBasicXml('create_table_sql', self::getCreateTable());
	}

	private static function _generateBasicXml($name, $value)
	{
		return "<?xml version=\"1.0\"?>\n<document><{$name}>{$value}</{$name}></document>";
	}
	
	private static function _generateXml($parent, $child, array $data)
	{
		$xml  = "<?xml version=\"1.0\"?>\n";
		$xml .= "<document>";
		$xml .= "<{$parent}>";

		foreach($data as $part) {
			$xml .= "<{$child}>";
			foreach($part as $key => $value) {
				$xml .= "<{$key}>{$value}</{$key}>";
			}
			$xml .= "</{$child}>";
		}

		$xml .= "</{$parent}>";
		$xml .= "</document>";

		return $xml;
	}

	public static function getServerList()
	{
		return array(
				'ServerX' => 'http://www.example.com/dbcheck/',
				'ServerY' => 'http://www.example.com/dbcheck/',
				);
	}

	public static function getOnlineServers()
	{
		$online_servers = array();
		foreach(self::getServerList() as $name => $url) {
			$online_servers[] = array(
					'name'   => $name,
					'url'    => $url,
					'online' => true,
					);
		}
		return $online_servers;
	}

	public static function getSelectedServers()
	{
		$online_servers = self::getOnlineServers();
		return array(
				'master' => $online_servers[0]['name'],
				'slave'  => $online_servers[1]['name'],
				);
	}

	public static function getSelectedDatabase()
	{
		$databases = self::getDatabases();
		return $databases[0]['name'];
	}

	public static function getSelectedTable()
	{
		$tables = self::getTables();
		return $tables[0]['name'];
	}
}