<?php

Mock::generate('DatabaseData');

class testCompareEngine extends UnitTestCase
{
	private $_compare_engine;
	private $_database_data;

	public function __construct()
	{
		$this->_selected_servers  = SampleTestData::getSelectedServers();
		$this->_selected_datebase = SampleTestData::getSelectedDatabase();
		$this->_selected_table    = SampleTestData::getSelectedTable();

		$this->_database_data = new MockDatabaseData($this->_selected_servers, $this->_selected_database, $this->_selected_table);
	/*
		$this->_database_data->setReturnValue('getStatusLists',     '');
		$this->_database_data->setReturnValue('getDatabaseLists',   '');
		$this->_database_data->setReturnValue('getTableLists',      '');
		$this->_database_data->setReturnValue('getTableColumns',    '');
		$this->_database_data->setReturnValue('getTableKeys',       '');
		$this->_database_data->setReturnValue('getTableCreateSqls', '');
		$this->_database_data->setReturnValue('getTableCharsets',   '');
		$this->_database_data->setReturnValue('getTableEngines',    '');
*/
		parent::__construct();
	}

	public function setUp()
	{
		$this->_compare_engine = new CompareEngine();
//		$this->_compare_engine->setDatabaseData($this->_database_data);
	}

	public function tearDown()
	{
		unset($this->_compare_engine);
	}

	public function testInit()
	{
		$this->assertTrue($this->_compare_engine instanceof CompareEngine);
	}
}
