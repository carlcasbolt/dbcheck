<?php

class testConfig extends UnitTestCase
{
	private $_config;

	public function setUp()
	{
		$this->_config = new Config();
	}

	public function tearDown()
	{
		unset($this->_config);
	}

	public function testInit()
	{
		$this->assertTrue($this->_config instanceof Config);
	}

	public function testSetterAndGetterMethods()
	{
		$hostname = 'db_test_host';
		$username = 'db_test_user';
		$password = 'db_test_pass';

		$rules = array(
			'local_ip_allowed'    => false,
			'server_ip_allowed'   => false,
			'internal_ip_allowed' => false,
		);
		$server_list = array(
			'MySQL Server 1' => 'http://mysql1.example.com/',
			'MySQL Server 2' => 'http://mysql2.example.com/',
		);
	
		$allowed_ips = array(
			'127.0.0.1',
			'10.0.0.1',
			'12.34.56.78',
		);

		$this->assertNotEqual($this->_config->getDbHostname(), $hostname);
		$this->assertNotEqual($this->_config->getDbUsername(), $username);
		$this->assertNotEqual($this->_config->getDbPassword(), $password);
		$this->assertNotEqual($this->_config->getIpCheckerRules(), $rules);
		$this->assertNotEqual($this->_config->getServerList(), $server_list);
		$this->assertNotEqual($this->_config->getAllowedIpAddresses(), $allowed_ips);

		$this->_config->setDbHostname($hostname);
		$this->_config->setDbUsername($username);
		$this->_config->setDbPassword($password);
		$this->_config->setIpCheckerRules($rules);
		$this->_config->setServerList($server_list);
		$this->_config->setAllowedIpAddresses($allowed_ips);
	
		$this->assertEqual($this->_config->getDbHostname(), $hostname);
		$this->assertEqual($this->_config->getDbUsername(), $username);
		$this->assertEqual($this->_config->getDbPassword(), $password);
		$this->assertEqual($this->_config->getIpCheckerRules(), $rules);
		$this->assertEqual($this->_config->getServerList(), $server_list);
		$this->assertEqual($this->_config->getAllowedIpAddresses(), $allowed_ips);
	}

	public function testCheckConfigFile()
	{
		$temp_file = '/tmp/dbcheck_config_test' . rand(1111,9999) . '.php';

		$exception_thrown = false;
		try {
			$this->_config->checkConfigFile($temp_file);
		} catch (ConfigFileException $exception) {
			$exception_thrown = true;
			$this->assertEqual($exception->getMessage(), 'Config file not found');
		}
		$this->assertTrue($exception_thrown);
		
		// create the temp file and make it un-readable
		file_put_contents($temp_file, 'test test test');
		chmod($temp_file, 0000);

		$exception_thrown = false;
		try {
			$this->_config->checkConfigFile($temp_file);
		} catch (ConfigFileException $exception) {
			$exception_thrown = true;
			$this->assertEqual($exception->getMessage(), 'Cannot read the config file.');
		}
		$this->assertTrue($exception_thrown);

		// make the temp file readable to PHP
		chmod($temp_file, 0755);

		$exception_thrown = false;
		try {
			$this->_config->checkConfigFile($temp_file);
		} catch (ConfigFileException $exception) {
			$exception_thrown = true;
		}
		$this->assertFalse($exception_thrown);

		// remove the temp file
		unlink($temp_file);
	}

	public function testInitValues()
	{
		$this->assertTrue(is_string($this->_config->getDbHostname()));
		$this->assertTrue(is_string($this->_config->getDbUsername()));
		$this->assertTrue(is_string($this->_config->getDbPassword()));
		$this->assertTrue(is_array($this->_config->getIpCheckerRules()));
		$this->assertTrue(is_array($this->_config->getServerList()));
		$this->assertTrue(is_array($this->_config->getAllowedIpAddresses()));
	}

	public function testGetInstance()
	{
		$hostname = 'instance hostname';
		$username = 'instance username';
		$password = 'instance password';
		$allowed_ips = array(
			'127.0.0.1',
			'10.0.0.1',
			'12.34.56.78',
		);

		$config = Config::getInstance();

		$this->assertTrue($config instanceof Config);
		$this->assertNotEqual($config->getDbHostname(), $hostname);
		$this->assertNotEqual($config->getDbUsername(), $username);
		$this->assertNotEqual($config->getDbPassword(), $password);
		$this->assertNotEqual($config->getAllowedIpAddresses(), $allowed_ips);

		$config->setDbHostname($hostname);
		$config->setDbUsername($username);
		$config->setDbPassword($password);
		$config->setAllowedIpAddresses($allowed_ips);
		
		$this->assertEqual($config->getDbHostname(), $hostname);
		$this->assertEqual($config->getDbUsername(), $username);
		$this->assertEqual($config->getDbPassword(), $password);
		$this->assertEqual($config->getAllowedIpAddresses(), $allowed_ips);

		unset($config);

		$config = Config::getInstance();

		$this->assertTrue($config instanceof Config);
		$this->assertEqual($config->getDbHostname(), $hostname);
		$this->assertEqual($config->getDbUsername(), $username);
		$this->assertEqual($config->getDbPassword(), $password);
		$this->assertEqual($config->getAllowedIpAddresses(), $allowed_ips);
	}

}

