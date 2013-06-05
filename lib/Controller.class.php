<?php

class Controller
{
	private $_action;
	private $_starting_with;
	private $_server_environments;

	private $_server_status;

	private $_online_servers    = array();
	private $_selected_servers  = array();

	private $_selected_database = '';
	private $_selected_table    = '';

	private $_compare_engine;

	/**
	 * Initialise the Controller and populate all the internal variables used by later methods within
	 * this class.
	 * 
	 * @return  Controller
	 */
	public function __construct($action, $server_environments, $starting_with, $database, $table)
	{
		$this->_action              = $action;
		$this->_server_environments = $server_environments;
		$this->_starting_with       = $starting_with;

		$selected_servers   = $this->_getSelectedServerEnvironments();

		$this->_selected_servers   = $selected_servers;
		$this->_selected_database  = $database;
		$this->_selected_table     = $table;

		$compare_engine = new CompareEngine($selected_servers, $database, $table);

		$this->setCompareEngine($compare_engine);

		$server_information = new ServerInformation(Config::getInstance()->getServerList());

		$this->setServerDetails($server_information);

		$this->_page_title = 'DB Comparison Tool (version ' . DBCHECK_VERSION . ')';
	}

	public function getCompareEngine()
	{
		return $this->_compare_engine;
	}

	public function setCompareEngine($engine)
	{
		$this->_compare_engine = $engine;
	}

	public function setServerDetails($server_information)
	{
		$this->_server_status  = $server_information->getServerStatuses();
		$this->_online_servers = $server_information->getOnlineServersList();
	}

	public function execute()
	{
		switch ($this->_action) {
			case Query::DATABASE_STATUS:   return $this->executeDatabaseStatusListPage();
			case Query::DATABASE_LIST:     return $this->executeDatabaseListPage();
			case Query::TABLE_LIST:        return $this->executeTableListPage();
			case Query::TABLE_STRUCTURE:   return $this->executeTableStructurePage();
			default:                       return $this->executeWelcomePage();
		}
	}

	public function executeDatabaseStatusListPage()
	{
		if (!isset($this->_selected_servers)) {
			throw new Exception('No Servers Selected!');
		}
		$action              = $this->_action;
		$server_environments = $this->_server_environments;
		$server_status       = $this->_server_status;
		$online_servers      = $this->_online_servers;
		$selected_servers    = $this->_selected_servers;
		$comparison_data     = $this->getCompareEngine()->compareStatusLists();
		$statistics          = new Statistics($selected_servers, $comparison_data);
		$page_title          = "{$this->_page_title} | Database Variables";
		$page_heading        = $this->_page_title;

		$view = 'database_status_page.php';

		require 'template.php';
	}

	public function executeDatabaseListPage()
	{
		if (!isset($this->_selected_servers)) {
			throw new Exception('No Servers Selected!');
		}
		$action              = $this->_action;
		$starting_with       = $this->_starting_with;
		$server_environments = $this->_server_environments;
		$server_status       = $this->_server_status;
		$online_servers      = $this->_online_servers;
		$selected_servers    = $this->_selected_servers;
		$comparison_data     = $this->getCompareEngine()->compareDatabases($starting_with);
		$statistics          = new Statistics($selected_servers, $comparison_data);
		$page_title          = "{$this->_page_title} | Database listings";
		$page_heading        = $this->_page_title;

		$view = 'database_list_page.php';

		require 'template.php';
	}

	public function executeTableListPage()
	{
		if (!isset($this->_selected_servers)) {
			throw new Exception('No Servers Selected!');
		}
		if (!isset($this->_selected_database)) {
			throw new Exception('No Database Selected!');
		}
		$action              = $this->_action;
		$server_environments = $this->_server_environments;
		$server_status       = $this->_server_status;
		$online_servers      = $this->_online_servers;
		$selected_servers    = $this->_selected_servers;
		$selected_database   = $this->_selected_database;
		$comparison_data     = $this->getCompareEngine()->compareTables();
		$alter_sql           = $this->getCompareEngine()->generateAlterDatabaseStructure($comparison_data);
		$statistics          = new Statistics($selected_servers, $comparison_data);
		$page_title          = "{$this->_page_title} | Table list for {$selected_database}";
		$page_heading        = $this->_page_title;

		$view = 'table_list_page.php';

		require 'template.php';
	}

	public function executeTableStructurePage()
	{
		if (!isset($this->_selected_servers)) {
			throw new Exception('No Servers Selected!');
		}
		if (!isset($this->_selected_database)) {
			throw new Exception('No Database Selected!');
		}
		if (!isset($this->_selected_table)) {
			throw new Exception('No Table Selected!');
		}
		$action              = $this->_action;
		$server_environments = $this->_server_environments;
		$server_status       = $this->_server_status;
		$online_servers      = $this->_online_servers;
		$selected_servers    = $this->_selected_servers;
		$selected_database   = $this->_selected_database;
		$selected_table      = $this->_selected_table;
		$comparison_data     = $this->getCompareEngine()->compareTableStructure();
		$alter_sql           = $this->getCompareEngine()->generateAlterTableStructure($comparison_data);
		$page_title          = "{$this->_page_title} | Table structure for {$selected_database}.{$selected_table}";
		$page_heading        = $this->_page_title;

		$view = 'table_structure_page.php';

		require 'template.php';
	}

	public function executeWelcomePage()
	{
		$action        = $this->_action;
		$server_status = $this->_server_status;
		$page_heading  = $page_title = $this->_page_title;

		$view = 'welcome_page.php';

		require 'template.php';
	}

	private function _getSelectedServerEnvironments()
	{
		if (!isset($this->_server_environments)) {
			return array(
				'master' => '',
				'slave'  => '',
			);
		}
		$server_environments = $this->_server_environments;

		$servers = array_keys(Config::getInstance()->getServerList());
		$parts   = explode("|", $server_environments);

		if (count($parts) < 2) {
			return array();
		}
		return array(
			'master' => $servers[$parts[0]],
			'slave'  => $servers[$parts[1]],
		);
	}
}


