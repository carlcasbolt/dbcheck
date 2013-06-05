<?php

class Statistics
{
	private $_selected_servers;
	private $_comparison_data;

	private $_identical = 0;
	private $_different = 0;

	private $_missing = array(
			'master' => 0,
			'slave'  => 0,
			);

	private $_engines  = array(
			'master' => array(),
			'slave'  => array(),
			'combined' => array(),
			);

	private $_charsets = array(
			'master'   => array(),
			'slave'    => array(),
			'combined' => array(),
			);

	public function __construct($selected_servers, $comparison_data)
	{
		$this->_selected_servers = $selected_servers;
		$this->_comparison_data  = $comparison_data;

		if (!$this->_comparison_data) return;

		foreach($this->_comparison_data as $data) {
			$state = @$data['state'];
	
			foreach($this->_selected_servers as $server_type => $server_name) {
				$engine  = @$data['data'][$server_name]['engine'];
				$charset = @$data['data'][$server_name]['charset'];
			
				$this->addEngine($engine, $server_type);
				$this->addCharset($charset, $server_type);
			}

			if ($state == 'identical') {
				$this->_identical++;
				continue;
			}

			foreach($this->_selected_servers as $server_type => $server_name) {
				$database_state = @$data['data'][$server_name]['state'];
	
				if ($database_state == 'missing') {
					$this->_missing[$server_type]++;
					$this->_different++;
					continue 2;
				}
			}
			$this->_different++;
		}
	}

	public function getIdentical()
	{
		return $this->_identical;
	}

	public function getDifferent()
	{
		return $this->_different;
	}
	
	public function getDifferentAndPresentOnBoth()
	{
		return (int) $this->getDifferent() - $this->getTotalMissing();
	}

	public function getMissing($server_name)
	{
		return (int) $this->_missing[$server_name];
	}

	public function getTotalMissing()
	{
		return (int) ($this->getMissing('master') + $this->getMissing('slave'));
	}

	public function getTotalCount()
	{
		return count($this->_comparison_data);
	}

	public function addEngine($engine, $server_type)
	{
		if (is_null($engine)) {
			return;
		}
		if (!isset($this->_engines[$server_type][$engine])) {
			$this->_engines[$server_type][$engine] = 0;
		}
		$this->_engines[$server_type][$engine]++;
		$this->_engines['combined'][$engine] = $engine;
	}

	public function addCharset($charset, $server_type)
	{
		if (is_null($charset)) {
			return;
		}
		if (!isset($this->_charsets[$server_type][$charset])) {
			$this->_charsets[$server_type][$charset] = 0;
		}
		$this->_charsets[$server_type][$charset]++;
		$this->_charsets['combined'][$charset] = $charset;

	}

	public function hasEngineStatistics()
	{
		return count($this->_engines['master']) || count($this->_engines['slave']);
	}

	public function hasCharacterSetStatistics()
	{
		return count($this->_charsets['master']) || count($this->_charsets['slave']);
	}

	public function getAllEngines()
	{
		return $this->_engines['combined'];
	}
	
	public function getAllCharacterSets()
	{
		return $this->_charsets['combined'];
	}

	public function getEngineCount($server_type, $engine)
	{
		return (int) @$this->_engines[$server_type][$engine];
	}

	public function getCharacterSetCount($server_type, $charset)
	{
		return (int) @$this->_charsets[$server_type][$charset];
	}
	
	public function hasIdenticalEngineCount($engine)
	{
		return count(@$this->_engines['master'][$engine]) == count(@$this->_engines['slave'][$engine]);
	}
	
	public function hasIdenticalCharacterSetCount($charset)
	{
		return count(@$this->_charsets['master'][$charset]) == count(@$this->_charsets['slave'][$charset]);
	}
}

