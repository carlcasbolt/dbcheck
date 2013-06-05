<?php

/**
 * XmlDocBuilder 
 * 
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class XmlDocBuilder
{
	private $_dom  = null;
	private $_root = null;

	public function __construct($document_name = 'document')
	{
		$this->_dom  = new DOMDocument('1.0');
		$this->_root = $this->_dom->createElement($document_name);

		$this->_dom->appendChild($this->_root);
	}

	public function appendChildren($parent_name, $child_name, $objects)
	{
		$xml_parent = $this->_dom->createElement($parent_name);

		foreach($objects as $object) {
			$xml_child = $this->_dom->createElement($child_name);

			foreach($object as $element => $value) {
				$xml_element = $this->_dom->createElement($element, $value);
				$xml_child->appendChild($xml_element);
			}
			$xml_parent->appendChild($xml_child);
		}
		$this->_root->appendChild($xml_parent);
	}

	public function appendDatabaseStatus($statuses)
	{
		$this->appendChildren('statuses', 'status', $statuses);
	}

	public function appendDatabases($databases = array())
	{
		$this->appendChildren('databases', 'database', $databases);
	}

	public function appendTables($tables = array())
	{
		$this->appendChildren('tables', 'table', $tables);
	}

	public function appendColumns($columns = array())
	{
		$this->appendChildren('columns', 'column', $columns);
	}

	public function appendKeys($keys = array())
	{
		$this->appendChildren('keys', 'key', $keys);
	}

	public function appendBasicRootElement($name, $value)
	{
		$this->_root->appendChild($this->_dom->createElement($name, $value));
	}

	public function appendCharacterSet($charset = "")
	{
		$this->appendBasicRootElement('charset', $charset);
	}

	public function appendCreateTable($create_table_sql = "")
	{
		$this->appendBasicRootElement('create_table_sql', $create_table_sql);
	}

	public function appendEngine($engine = "")
	{
		$this->appendBasicRootElement('engine', $engine);
	}

	public function saveXML()
	{
		// save and trim the xml output as we don't need extra whitespace
		return trim($this->_dom->saveXML());
	}
}


