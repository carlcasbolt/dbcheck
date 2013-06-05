<?php

/**
 * Query 
 * 
 * Retrieve the information about the Database and print out an XML Document for another script
 * to process.
 *
 * Standard XML Document output (some requests will not contain all of this information)
 *
 * <?xml version="1.0"?>
 * <document>
 *   <databases>
 *     <database>
 *       <name>foo</name>
 *       <fingerprint>8fa14cdd754f91cc6554c9e71929cce7</fingerprint>
 *     </database>
 *   </databases>
 *   <tables>
 *     <table>
 *       <name>foo</name>
 *       <fingerprint>37693cfc748049e45d87b8c7d8b9aacd</fingerprint>
 *     </table>
 *   </tables>
 *   <columns>
 *     <column>
 *       <name>id</name>
 *       <type>int(6)</type>
 *       <attributes>unsigned</attributes>
 *       <null>yes</null>
 *       <default>0</default>
 *       <extra>auto_increment</extra>
 *       <primary_key>1</primary_key>
 *       <unique_key>0</unique_key>
 *     </column>
 *     <column>
 *       <name>url</name>
 *       <type>varchar(255)</type>
 *       <attributes></attributes>
 *       <null>no</null>
 *       <default></default>
 *       <extra></extra>
 *       <primary_key>0</primary_key>
 *       <unique_key>0</unique_key>
 *     </column>
 *   </columns>
 * </document>
 *
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class Query extends Cachable
{
	const DATABASE_STATUS = 'dbStatus';
	const DATABASE_LIST   = 'dbList';
	const TABLE_LIST      = 'tblList';
	const TABLE_STRUCTURE = 'tblStructure';

	private $_action;
	private $_starting_with;
	private $_database;
	private $_table;
	
	private $_db_tools;

	public function __construct($action, $starting_with=null, $database=null, $table=null)
	{
		$this->_action        = $action;
		$this->_starting_with = $starting_with;
		$this->_database      = $database;
		$this->_table         = $table;
	}

	public function setDbTools($db_tools)
	{
		$this->_db_tools = $db_tools;
	}

	public function getDbTools()
	{
		if (!$this->_db_tools) {
			$this->setDbTools(DbTools::getInstance());
		}
		return $this->_db_tools;
	}

	/**
	 * @access public
	 * @return void
	 */
	public function execute()
	{
		if ($this->_action == self::DATABASE_LIST) {
			$cache_key = __CLASS__ . "::" . $this->_action . "::" . $this->_starting_with;
		} else {
			$cache_key = __CLASS__ . "::" . $this->_action . "::" . $this->_database . "::" . $this->_table;
		}

		$xml = $this->getCache()->getValue($cache_key);
		if (!strlen($xml)) {
			$xml = $this->getXml();
			$this->getCache()->setValue($cache_key, $xml);
		}
		header ("content-type: text/xml");
		print $xml;
	}

	/** 
	 * @return string
	 */
	public function getXml()
	{

		switch($this->_action) {
			case self::DATABASE_STATUS:   return $this->getDatabaseStatus();
			case self::DATABASE_LIST:     return $this->getDatabaseList();
			case self::TABLE_LIST:        return $this->getTableList();
			case self::TABLE_STRUCTURE:   return $this->getTableStructure();
		}
		throw new Exception('Invalid action detected');
	}

	public function getDatabaseStatus()
	{
		$xml = new XmlDocBuilder();

		$xml->appendDatabaseStatus($this->getDbTools()->getDatabaseStatus());

		return $xml->saveXML();
	}

	/**
	 * @return string
	 */
	public function getDatabaseList()
	{
		$xml = new XmlDocBuilder();

		$xml->appendDatabases($this->getDbTools()->getDatabaseList($this->_starting_with));

		return $xml->saveXML();
	}

	/**
	 * @return string
	 */
	public function getTableList()
	{
		$xml = new XmlDocBuilder();

		$xml->appendDatabases(array(array('name' => $this->_database)));
		$xml->appendTables($this->getDbTools()->getTableList($this->_database));

		return $xml->saveXML();
	}

	/**
	 * @return string
	 */
	public function getTableStructure()
	{
		$xml = new XmlDocBuilder();

		$xml->appendDatabases(array(array('name' => $this->_database)));
		$xml->appendTables(array(array('name' => $this->_table)));
		$xml->appendColumns($this->getDbTools()->getTableColumns($this->_database, $this->_table));
		$xml->appendKeys($this->getDbTools()->getTableKeys($this->_database, $this->_table));
		$xml->appendCharacterSet($this->getDbTools()->getTableCharacterSet($this->_database, $this->_table));
		$xml->appendCreateTable($this->getDbTools()->getCreateTableSql($this->_database, $this->_table));

		$xml->appendEngine($this->getDbTools()->getTableEngine($this->_database, $this->_table));

		return $xml->saveXML();
	}
}



