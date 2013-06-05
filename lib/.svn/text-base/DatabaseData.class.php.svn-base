<?php

class DatabaseData
{
	private $_server_list       = array();
	private $_selected_servers  = array();
	private $_selected_database = '';
	private $_selected_table    = '';
	private $_xml_doc_reader    = array();

	public function __construct($selected_servers = array(), $selected_database=null, $selected_table=null)
	{
		$this->_server_list = Config::getInstance()->getServerList();

		$this->_selected_servers  = $selected_servers;
		$this->_selected_database = $selected_database;
		$this->_selected_table    = $selected_table;
	}

	public function getStatusLists()
	{
		$query_parts = array(
			'action' => Query::DATABASE_STATUS,
		);
		return $this->_getServerData($query_parts, 'getStatusLists');
	}

	public function getDatabaseLists($starting_with)
	{
		$query_parts = array(
			'action'        => Query::DATABASE_LIST,
			'starting_with' => $starting_with,
		);
		return $this->_getServerData($query_parts, 'getDatabaseLists');
	}

	public function getTableLists()
	{
		$query_parts = array(
			'action'   => Query::TABLE_LIST,
			'database' => $this->_selected_database,
		);
		return $this->_getServerData($query_parts, 'getTableLists');
	}

	private function _getTableStructure($function_name)
	{
		$query_parts = array(
			'action'   => Query::TABLE_STRUCTURE,
			'database' => $this->_selected_database,
			'table'    => $this->_selected_table,
		);
		return $this->_getServerData($query_parts, $function_name);
	}

	public function getTableColumns()     {  return self::_getTableStructure('getTableColumns');     }
	public function getTableKeys()        {  return self::_getTableStructure('getTableKeys');        }
	public function getTableCreateSqls()  {  return self::_getTableStructure('getTableCreateSqls');  }
	public function getTableCharsets()    {  return self::_getTableStructure('getTableCharsets');    }
	public function getTableEngines()     {  return self::_getTableStructure('getTableEngines');     }

	private function _getServerData($query_parts, $function_name)
	{
		$server_data = array();
		foreach($this->_selected_servers as $server) {
			if (isset($this->_server_list[$server])) {
				$url = $this->_server_list[$server].'query.php?'.http_build_query($query_parts);

				$reader = $this->_getXmlDocReader($url);

				$server_data[$server] = $reader->$function_name();
			}
		}
		return $server_data;
	}

	private function _getXmlDocReader($url)
	{
		$key = md5($url);
		if(!isset($this->_xml_doc_reader[$key])) {
			$this->_xml_doc_reader[$key] = new XmlDocReader();
			$this->_xml_doc_reader[$key]->loadFromUrl($url);
		}
		return $this->_xml_doc_reader[$key];
	}
}

