<?php

class testXmlDocBuilder extends UnitTestCase
{
	private $_xml_doc_builder;

	public function setUp()
	{
		$this->_xml_doc_builder = new XmlDocBuilder();
	}

	public function tearDown()
	{
		unset($this->_xml_doc_builder);
	}

	public function testInit()
	{
		$this->assertTrue($this->_xml_doc_builder instanceof XmlDocBuilder);
	}

	public function testSaveXML()
	{
		$this->assertEqual($this->_xml_doc_builder->saveXML(), "<?xml version=\"1.0\"?>\n<document/>");
	}

	public function testAppendChildren()
	{
		$data = array(array('cc' => 'dd'));
		$xml = "<?xml version=\"1.0\"?>\n<document><aa><bb><cc>dd</cc></bb></aa></document>";

		$this->_xml_doc_builder->appendChildren('aa', 'bb', $data);
		$this->assertEqual($this->_xml_doc_builder->saveXML(), $xml);
	}

	public function testAppendDatabaseStatus()
	{
		$this->_xml_doc_builder->appendDatabaseStatus(SampleTestData::getDatabaseStatuses());
		$this->assertEqual($this->_xml_doc_builder->saveXML(), SampleTestData::getDatabaseStatusesXml());
	}

	public function testAppendDatabases()
	{
		$this->_xml_doc_builder->appendDatabases(SampleTestData::getDatabases());
		$this->assertEqual($this->_xml_doc_builder->saveXML(), SampleTestData::getDatabasesXml());
	}

	public function testAppendTables()
	{
		$this->_xml_doc_builder->appendTables(SampleTestData::getTables());
		$this->assertEqual($this->_xml_doc_builder->saveXML(), SampleTestData::getTablesXml());
	}

	public function testAppendColumns()
	{
		$this->_xml_doc_builder->appendColumns(SampleTestData::getColumns());
		$this->assertEqual($this->_xml_doc_builder->saveXML(), SampleTestData::getColumnsXml());
	}

	public function testAppendKeys()
	{
		$this->_xml_doc_builder->appendKeys(SampleTestData::getKeys());
		$this->assertEqual($this->_xml_doc_builder->saveXML(), SampleTestData::getKeysXml());
	}

	public function testAppendBasicRootElement()
	{
		$expected_xml = "<?xml version=\"1.0\"?>\n<document><foobar>monkey</foobar></document>";

		$this->_xml_doc_builder->appendBasicRootElement('foobar', 'monkey');
		$this->assertEqual($this->_xml_doc_builder->saveXML(), $expected_xml);
	}

	public function testAppendCharacterSet()
	{
		$this->_xml_doc_builder->appendCharacterSet(SampleTestData::getCharset());
		$this->assertEqual($this->_xml_doc_builder->saveXML(), SampleTestData::getCharsetXml());
	}

	public function testAppendCreateTable()
	{
		$this->_xml_doc_builder->appendCreateTable(SampleTestData::getCreateTable());
		$this->assertEqual($this->_xml_doc_builder->saveXML(), SampleTestData::getCreateTableXml());
	}

	public function testAppendEngine()
	{
		$this->_xml_doc_builder->appendEngine(SampleTestData::getEngine());
		$this->assertEqual($this->_xml_doc_builder->saveXML(), SampleTestData::getEngineXml());
	}
}
