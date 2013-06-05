<?php

Mock::generate('CompareEngine');
Mock::generate('ServerInformation');

class testController extends UnitTestCase
{
	private $_action;
	private $_server_environments;
	private $_starting_with;
	private $_database;
	private $_table;
	private $_controller;

	public function __construct()
	{
		$status_lists    = SampleComparisonData::getCompareStatusLists();
		$databases       = SampleComparisonData::getCompareDatabases();
		$tables          = SampleComparisonData::getCompareTables();
		$table_structure = SampleComparisonData::getCompareTableStructure();
		$alter_database  = SampleComparisonData::getGenerateAlterDatabaseStructure();
		$alter_table     = SampleComparisonData::getGenerateAlterTableStructure();

		$this->_engine = new MockCompareEngine();
		$this->_engine->setReturnValue('compareStatusLists',             $status_lists);
		$this->_engine->setReturnValue('compareDatabases',               $databases);
		$this->_engine->setReturnValue('compareTables',                  $tables);
		$this->_engine->setReturnValue('compareTableStructure',          $table_structure);
		$this->_engine->setReturnValue('generateAlterDatabaseStructure', $alter_database);
		$this->_engine->setReturnValue('generateAlterTableStructure',    $alter_table);
		
		$online_servers = SampleTestData::getOnlineServers();
		
		$this->_server_information = new MockServerInformation();
		$this->_server_information->setReturnValue('getServerStatuses',    $online_servers);
		$this->_server_information->setReturnValue('getOnlineServersList', $online_servers);

		Config::getInstance()->setServerList(SampleTestData::getServerList());
	}

	public function setUp()
	{
		$this->_action              = '';
		$this->_server_environments = '0|1';
		$this->_starting_with       = 'A';
		$this->_database            = 'Arrow';
		$this->_table               = 'Head';

		$this->_initController($this->_action, $this->_server_environments, $this->_starting_with, $this->_database, $this->_table);
	}
	
	public function tearDown()
	{
		unset($this->_action);
		unset($this->_server_environments);
		unset($this->_starting_with);
		unset($this->_database);
		unset($this->_table);
	
		unset($this->_controller);
	}

	private function _initController($action, $server_environments, $starting_with, $database, $table)
	{
		$this->_controller = new Controller($action, $server_environments, $starting_with, $database, $table);
		$this->_controller->setCompareEngine($this->_engine);
		$this->_controller->setServerDetails($this->_server_information);
	}
	
	public function testInit()
	{
		$this->assertTrue($this->_controller instanceof Controller);
	}

	public function testExecute()
	{
		$this->_testWelcomePage();

		$this->_initController(Query::DATABASE_STATUS, $this->_server_environments, '', '', '');
		$this->_testDatabaseStatusPage();
/*
		$this->_initController(Query::DATABASE_LIST, $this->_server_environments, '', '', '');
		$this->_testDatabaseListPage();
		
		$this->_initController(Query::DATABASE_LIST, $this->_server_environments, $this->_starting_with, '', '');
		$this->_testDatabaseListPage();

		$this->_initController(Query::TABLE_LIST, $this->_server_environments, '', $this->_database, '');
		$this->_testTableListPage();

		$this->_initController(Query::TABLE_STRUCTURE, $this->_server_environments, '', $this->_database, $this->_table);
		$this->_testTableStructurePage();

		$this->_initController('FooBar', 'FooBar', 'FooBar', 'FooBar', 'FooBar');
		$this->_testWelcomePage();
*/
	}

	private function _testWelcomePage()
	{
		$patterns = array(
				'<title>DB Comparison Tool \(version ' . DBCHECK_VERSION . '\)</title>',
				'<h1 class="title">DB Comparison Tool \(version ' . DBCHECK_VERSION . '\)</h1>',
				'<h2>Compare Database Structures</h2>',
				'<h2>Compare Database Server Variables</h2>',
				'<h2>How to use this tool.</h2>',
				'<li>Select which two environments you wish to compare \(master\* and slave\).</li>',
				'<li>Select which databases you wish to compare \(pick the first letter or a database\)</li>',
				'<li>Select which database you wish to examine</li>',
				'<li>Select which table you wish to examine</li>',
				);
		$this->_testPageContent($patterns);
	}

	private function _testDatabaseStatusPage()
	{
		$patterns = array(
				'<title>DB Comparison Tool \(version ' . DBCHECK_VERSION . '\) | Database Variables</title>',
				'<h1 class="title">DB Comparison Tool \(version ' . DBCHECK_VERSION . '\)</h1>',
				'<a href="index.php">Start again</a> >',
				'<th>Selected Server Environements</th>',
				);
		$this->_testPageContent($patterns);
	}

	private function _testDatabaseListPage()
	{
		$patterns = array(
				'<h1 class="title">DB Comparison Tool \(version ' . DBCHECK_VERSION . '\)</h1>',
				'<a href="index.php">Start again</a> >',
				'<th>Selected Server Environements</th>',
				);
		$this->_testPageContent($patterns);
	}

	private function _testTableListPage()
	{
		$patterns = array(
				'<h1 class="title">DB Comparison Tool \(version ' . DBCHECK_VERSION . '\)</h1>',
				'<a href="index.php">Start again</a> >',
				'<th>Selected Server Environements</th>',
				);
		$this->_testPageContent($patterns);
	}

	public function _testTableStructurePage()
	{
		$patterns = array(
				'<h1 class="title">DB Comparison Tool \(version ' . DBCHECK_VERSION . '\)</h1>',
				'<a href="index.php">Start again</a> >',
				'<th>Selected Server Environements</th>',
				);
		$this->_testPageContent($patterns);
	}

	private function _testPageContent($patterns = array())
	{
		ob_start();
		$this->_controller->execute();
		$html_content = ob_get_contents();
		ob_clean();

		foreach($patterns as $pattern) {
			$this->assertPattern("@{$pattern}@i", $html_content);
		}
	}
}
