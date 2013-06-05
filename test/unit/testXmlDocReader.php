<?php

class testXmlDocReader extends UnitTestCase
{
	public function setUp()
	{
		$this->_xml_doc_reader = new XmlDocReader();
	}

	public function tearDown()
	{
		unset($this->_xml_doc_reader);
	}

	public function testInit()
	{
		$this->assertTrue($this->_xml_doc_reader instanceof XmlDocReader);
	}

	public function testLoadFromString()
	{
		$this->assertTrue($this->_xml_doc_reader->loadFromString("<?xml version=\"1.0\"?>\n<document/>"));
		$this->assertFalse($this->_xml_doc_reader->loadFromString(""));
	}

	public function testLoadFromFile()
	{
		$this->assertTrue($this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/databases.xml"));
		$this->assertTrue($this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/tables.xml"));
		$this->assertFalse($this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/text.txt"));
		$this->assertFalse($this->_xml_doc_reader->loadFromFile("/tmp/invalid/file/name"));
		$this->assertFalse($this->_xml_doc_reader->loadFromFile(null));
	}

	public function testLoadFromUrl()
	{
		global $_SERVER;

		// if we're not testing via a web page then we won't have valid URL's
		if (!isset($_SERVER['HTTP_HOST']) || !isset($_SERVER['REQUEST_URI'])) {
			return;
		}

		$url_to_fixtures = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'fixtures';

		$this->assertTrue($this->_xml_doc_reader->loadFromUrl($url_to_fixtures . "/databases.xml"));
		$this->assertTrue($this->_xml_doc_reader->loadFromUrl($url_to_fixtures . "/tables.xml"));
		$this->assertFalse($this->_xml_doc_reader->loadFromUrl($url_to_fixtures . "/text.txt"));
		$this->assertFalse($this->_xml_doc_reader->loadFromUrl("http://www.example.com/invalid/file/name"));
		$this->assertFalse($this->_xml_doc_reader->loadFromUrl(null));
	}

	public function testGetStatusLists()
	{
		$this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/statuses.xml");

		$expected_data = array(
				'1f5b6942842c7d59b39c070f8599cae3a9013f4f' => array(
					'var_name' => 'Aborted_clients',
					'value'    => 0,
					),
				'a8a6aef71353c0bf7b99005796f40d2aa3ebf5f7' => array(
					'var_name' => 'Aborted_connects',
					'value'    => 0,
					),
				'd77c776a4251b2c4aec42aec1769053748972daf' => array(
					'var_name' => 'Binlog_cache_disk_use',
					'value'    => 0,
					),
				'5f77becbfc0768f2c8ee37ae350d8a0e0c2234bf' => array(
					'var_name' => 'Binlog_cache_use',
					'value'    => 0,
					),
				'ea240c016f694b64a68f86e8e8db87f9d3001411' => array(
					'var_name' => 'Bytes_received',
					'value'    => 81,
					),
				);

		$status_lists = $this->_xml_doc_reader->getStatusLists();

		$this->assertEqual($status_lists, $expected_data);
	}

	public function testGetDatabaseLists()
	{
		$this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/databases.xml");

		$expected_data = array(
				'9912b1a59a4dea063db3689ea3bf0b35ba414d87' => array(
					'name'        => 'example_database_1',
					'fingerprint' => 'b278a3b49648beaeed6da6dce2d9a5ef45e5143d',
					),
				'081fd5630cc4e8b7cf3757ac53097ab9b31b27d1' => array(
					'name'        => 'example_database_2',
					'fingerprint' => '87d5aebf66962a28eefafa22b7f85f1be85d274c',
					),
				);

		$database_lists = $this->_xml_doc_reader->getDatabaseLists();

		$this->assertEqual($database_lists, $expected_data);
	}

	public function testGetTableLists()
	{
		$this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/tables.xml");

		$expected_data = array(
				'c89120f621cfd9c7568bdf6954ce4aed37b67fbc' => array(
					'name'        => 'example_table_1',
					'fingerprint' => '5898d78703effff0ff2bc71a23c1a4bb15f375e6',
					'engine'      => 'MyISAM',
					'charset'     => 'latin1',
					),
				'f40e191cf319580c8f17aca87e59a04255309a7e' => array(
					'name'        => 'example_table_2',
					'fingerprint' => 'f881e38baac6aa9aa5746f72efb1f7fee015f0c1',
					'engine'      => 'MyISAM',
					'charset'     => 'latin1',
					),
				'1be19955a1a56114c5d8966a1231c305ec3e2728' => array(
					'name'        => 'example_table_3',
					'fingerprint' => 'a0c71427ad6b8e25ae8df99b364f593dd0359ded',
					'engine'      => 'MyISAM',
					'charset'     => 'latin1',
					),
				);

		$table_lists = $this->_xml_doc_reader->getTableLists();

		$this->assertEqual($table_lists, $expected_data);
	}

	public function testGetTableColumns()
	{
		$this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/table_structure.xml");

		$expected_data = array(
				'87ea5dfc8b8e384d848979496e706390b497e547' => array(
					'name'       => 'id',
					'type'       => 'int(9) unsigned',
					'collation'  => '',
					'null'       => 'NO',
					'key'        => 'PRI',
					'default'    => '',
					'extra'      => 'auto_increment',
					'privileges' => 'select,insert,update,references',
					'comment'    => '',
					),
				'8843d7f92416211de9ebb963ff4ce28125932878' => array(
					'name'       => 'foobar',
					'type'       => 'varchar(255)',
					'collation'  => 'utf8_unicode_ci',
					'null'       => 'NO',
					'key'        => '',
					'default'    => '',
					'extra'      => '',
					'privileges' => 'select,insert,update,references',
					'comment'     => '',
					),
				);

		$table_columns = $this->_xml_doc_reader->getTableColumns();

		$this->assertEqual($table_columns, $expected_data);
	}

	public function testGetTableKeys()
	{
		$this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/table_structure.xml");

		$expected_data = array(
				'9b7a33359d1266139c4d32f4093ead8b18cc434b' => array(
					'non_unique'   => 0,
					'key_name'     => 'PRIMARY',
					'seq_in_index' => 1,
					'column_name'  => 'id',
					'collation'    => 'A',
					'cardinality'  => 21,
					'sub_part'     => '',
					'packed'       => '',
					'null'         => '',
					'index_type'   => 'BTREE',
					'comment'      => '',
					),
				);

		$table_keys = $this->_xml_doc_reader->getTableKeys();

		$this->assertEqual($table_keys, $expected_data);
	}

	public function testGetTableCreateSqls()
	{
		$this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/table_structure.xml");

		$expected_sql  = "CREATE TABLE `example_table` (\n";
		$expected_sql .= "  `id` int(9) unsigned NOT NULL auto_increment,\n";
		$expected_sql .= "  `foobar` varchar(255) collate utf8_unicode_ci NOT NULL default ''\n";
		$expected_sql .= "  PRIMARY KEY  (`id`)\n";
		$expected_sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

		$create_sql = $this->_xml_doc_reader->getTableCreateSqls();

		$this->assertEqual($create_sql, $expected_sql);
	}

	public function testGetTableCharsets()
	{
		$this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/table_structure.xml");

		$this->assertEqual($this->_xml_doc_reader->getTableCharsets(), 'utf8');
	}

	public function testGetTableEngines()
	{
		$this->_xml_doc_reader->loadFromFile(FIXTURES_DIR . "/table_structure.xml");

		$this->assertEqual($this->_xml_doc_reader->getTableEngines(), 'MyISAM');
	}
}
