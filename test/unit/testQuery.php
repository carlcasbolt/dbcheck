<?php

Mock::generate('DbTools');

class testQuery extends UnitTestCase
{
	private $_db_tools;

	public function __construct()
	{
		$this->_db_tools = new MockDbTools();
		$this->_db_tools->setReturnValue('getDatabaseStatus',      SampleTestData::getDatabaseStatuses());
		$this->_db_tools->setReturnValue('getDatabaseList',        SampleTestData::getDatabases());
		$this->_db_tools->setReturnValue('getTableList',           SampleTestData::getTables());
		$this->_db_tools->setReturnValue('getTableColumns',        SampleTestData::getColumns());
		$this->_db_tools->setReturnValue('getTableKeys',           SampleTestData::getKeys());
		$this->_db_tools->setReturnValue('getTableCharacterSet',   'utf8');
		$this->_db_tools->setReturnValue('getTableEngine',         'MyISAM');
		$this->_db_tools->setReturnValue('getCreateTableSql',      SampleTestData::getCreateTable());
		$this->_db_tools->setReturnValue('getDatabaseFingerPrint', sha1('foobar'));
		$this->_db_tools->setReturnValue('getTableFingerPrint',    sha1('foobar'));
		$this->_db_tools->setReturnValue('canListDatabase',        true);

		parent::__construct();
	}

	public function setUp()
	{
		$this->_query = new Query(null, null, null, null);
		$this->_query->setDbTools($this->_db_tools);
	}
	
	public function tearDown()
	{
		unset($this->_query);
	}

	public function testInit()
	{
		$this->assertTrue($this->_query instanceof Query);
	}

	public function testGetXmlExceptions()
	{
		$this->_testGetXmlException(null, true);
		$this->_testGetXmlException('FooBar', true);
		$this->_testGetXmlException(Query::DATABASE_STATUS, false);
		$this->_testGetXmlException(Query::DATABASE_LIST, false);
		$this->_testGetXmlException(Query::TABLE_LIST, false);
		$this->_testGetXmlException(Query::TABLE_STRUCTURE, false);
	}

	private function _testGetXmlException($action, $expect_exception)
	{
		$query = new Query($action, null, null, null);
		$query->setDbTools($this->_db_tools);
		
		$exception_thrown = false;
		try {
			$query->getXml();
		} catch (Exception $exception) {
			$exception_thrown = true;
			$this->assertEqual($exception->getMessage(), 'Invalid action detected');
		}
		if ($expect_exception) {
			$this->assertTrue($exception_thrown);
		} else {
			$this->assertFalse($exception_thrown);
		}
	}

	public function testGetDatabaseStatus()
	{
		$this->assertEqual($this->_query->getDatabaseStatus(), SampleTestData::getDatabaseStatusesXml());
	}

	public function testGetDatabaseList()
	{
		$this->assertEqual($this->_query->getDatabaseList(), SampleTestData::getDatabasesXml());
	}

	public function testGetTableList()
	{
		$expected_xml = file_get_contents(FIXTURES_DIR . '/query_getTableList.xml');
		$this->assertEqual($this->_query->getTableList(), $expected_xml);
	}

	public function testGetTableStructure()
	{
		$expected_xml = file_get_contents(FIXTURES_DIR . '/query_getTableStructure.xml');
		$this->assertEqual($this->_query->getTableStructure(), $expected_xml);
	}

}
