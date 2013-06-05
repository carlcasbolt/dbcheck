<?php

class testServerInformation extends UnitTestCase
{
	private $_server_information;

	private $_server_list = array(
		'mysql_1'  => 'http://mysql1.example.com/',
		'mysql_2'  => 'http://mysql2.example.com/',
		'mysql_3'  => 'http://mysql3.example.com/',
		);

	public function setUp()
	{
		$this->_server_information = new ServerInformation($this->_server_list);
	}

	public function tearDown()
	{
		unset($this->_server_information);
	}

	public function testInit()
	{
		$this->assertTrue($this->_server_information instanceof ServerInformation);
	}

	public function testGetServerStatus()
	{
		$foobar = array('foobar' => null);

		$server_list = array_merge($foobar, $this->_server_list);

		foreach($server_list as $server_name => $url) {
			$info = $this->_server_information->getServerStatus($server_name);

			$this->assertTrue(is_array($info));

			$this->assertEqual($info['name'],   $server_name);
			$this->assertEqual($info['url'],    $url);
			$this->assertEqual($info['online'], false);
		}
	}

	public function testGetServerStatuses()
	{
		$expected_array = array(
			0 => array(
				'name'   => 'mysql_1',
				'url'    =>  'http://mysql1.example.com/',
				'online' =>  false,
				),
			1 => array(
				'name'   => 'mysql_2',
				'url'    =>  'http://mysql2.example.com/',
				'online' =>  false,
				),
			2 => array(
				'name'   => 'mysql_3',
				'url'    =>  'http://mysql3.example.com/',
				'online' =>  false,
				),
			);
		$server_statuses = $this->_server_information->getServerStatuses();

		$this->assertTrue(is_array($server_statuses));
		$this->assertEqual($expected_array, $server_statuses);
	}

	public function testGetOnlineServersList()
	{
		$this->assertTrue(is_array($this->_server_information->getOnlineServersList()));
		$this->assertEqual(count($this->_server_information->getOnlineServersList()), 0);
	}

	public function testShortUrls()
	{
		$server_list = array('tooShort' => 'http://');

		$server_information = new ServerInformation($server_list);
	}

}
