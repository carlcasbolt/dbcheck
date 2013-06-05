<?php

class testIpChecker extends UnitTestCase
{
	private $_config;

	public function setUp()
	{
		$this->_ip_checker = new IpChecker();
	}

	public function tearDown()
	{
		unset($this->_ip_checker);
	}

	public function testInit()
	{
		$this->assertTrue($this->_ip_checker instanceof IpChecker);
	}

	public function testConstants()
	{
		$this->assertEqual(IpChecker::LOCALHOST,   1);
		$this->assertEqual(IpChecker::SERVER_IP,   2);
		$this->assertEqual(IpChecker::INTERNAL_IP, 3);
		$this->assertEqual(IpChecker::ALLOWED_IP,  4);
	}

	public function testGetIp()
	{
		global $_SERVER;
	
		unset($_SERVER['HTTP_PROXY_USER']);
		unset($_SERVER['REMOTE_ADDR']);

		// set the remote address value to something stupid
		$_SERVER['REMOTE_ADDR'] = '14.23.12.15';
		$this->assertEqual(IpChecker::getIp(), '14.23.12.15');

		// set the remote address value to localhost
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		$this->assertEqual(IpChecker::getIp(), '127.0.0.1');
	}

	public function testGetServerIp()
	{
		global $_SERVER;
	
		unset($_SERVER['SERVER_ADDR']);

		// set the server address to something stupid
		$_SERVER['SERVER_ADDR'] = '14.23.12.15';
		$this->assertEqual(IpChecker::getServerIp(), '14.23.12.15');

		// set the server address to localhost
		$_SERVER['SERVER_ADDR'] = '127.0.0.1';
		$this->assertEqual(IpChecker::getServerIp(), '127.0.0.1');
	}

	public function testIpAddressTypeAllowed()
	{
		$config = Config::getInstance();

		$rules = array(
				'local_ip_allowed'    => true,
				'server_ip_allowed'   => true,
				'internal_ip_allowed' => true,
				);

		$config->setIpCheckerRules($rules);
		$ip_checker = new IpChecker();
		
		$this->assertTrue($ip_checker->ipAddressTypeAllowed('local_ip_allowed'));
		$this->assertTrue($ip_checker->ipAddressTypeAllowed('server_ip_allowed'));
		$this->assertTrue($ip_checker->ipAddressTypeAllowed('internal_ip_allowed'));

		$rules = array(
				'local_ip_allowed'    => false,
				'server_ip_allowed'   => false,
				'internal_ip_allowed' => false,
				);

		$config->setIpCheckerRules($rules);
		$ip_checker = new IpChecker();
		
		$this->assertFalse($ip_checker->ipAddressTypeAllowed('local_ip_allowed'));
		$this->assertFalse($ip_checker->ipAddressTypeAllowed('server_ip_allowed'));
		$this->assertFalse($ip_checker->ipAddressTypeAllowed('internal_ip_allowed'));

		$config->resetInstance();
	}

	public function testIsLocalIp()
	{
		$this->assertTrue(IpChecker::isLocalIp('127.0.0.1'));
		$this->assertFalse(IpChecker::isLocalIp('10.0.0.1'));
	}

	public function testIsServerIp()
	{
		global $_SERVER;

		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

		$this->assertTrue(IpChecker::isServerIp('127.0.0.1'));
		$this->assertFalse(IpChecker::isServerIp('10.0.0.1'));
	}

	public function testInIpRange()
	{
		// blank values should return false as they are not in a range
		$this->assertFalse(IpChecker::inIpRange('', '', ''));

		$this->assertTrue(IpChecker::inIpRange('10.22.11.25',   '10.0.0.0',    '10.255.255.255'));
		$this->assertTrue(IpChecker::inIpRange('172.18.12.128', '172.16.0.0',  '172.31.255.255'));
		$this->assertTrue(IpChecker::inIpRange('192.168.4.12',  '192.168.0.0', '192.168.255.255'));

		$this->assertFalse(IpChecker::inIpRange('127.0.0.1', '10.0.0.0',    '10.255.255.255'));
		$this->assertFalse(IpChecker::inIpRange('127.0.0.1', '172.16.0.0',  '172.31.255.255'));
		$this->assertFalse(IpChecker::inIpRange('127.0.0.1', '192.168.0.0', '192.168.255.255'));
	}
	
	public function testIsInternalIp()
	{
		$this->assertTrue(IpChecker::isInternalIp('10.0.0.4'));
		$this->assertTrue(IpChecker::isInternalIp('10.2.101.25'));
		$this->assertTrue(IpChecker::isInternalIp('10.22.11.25'));
		$this->assertTrue(IpChecker::isInternalIp('10.34.1.125'));
		$this->assertTrue(IpChecker::isInternalIp('10.200.1.25'));
		$this->assertTrue(IpChecker::isInternalIp('172.18.12.128'));
		$this->assertTrue(IpChecker::isInternalIp('192.168.4.12'));

		$this->assertFalse(IpChecker::isInternalIp('11.22.33.44'));
		$this->assertFalse(IpChecker::isInternalIp('22.33.44.55'));
		$this->assertFalse(IpChecker::isInternalIp('33.44.55.66'));
		$this->assertFalse(IpChecker::isInternalIp('44.55.66.77'));
		$this->assertFalse(IpChecker::isInternalIp('55.66.77.88'));
		$this->assertFalse(IpChecker::isInternalIp('66.77.88.99'));
	}

	public function testIsAllowedIp()
	{
		$config = Config::getInstance();

		$allowed_ips = array(
			'11.12.13.14',
			'16.17.18.19',
			'21.22.23.24',
			'26.27.28.29',
			'31.32.33.34',
			'36.37.38.39',
			);

		$config->setAllowedIpAddresses($allowed_ips);
		$ip_checker = new IpChecker();

		foreach($allowed_ips as $ip) {
			$this->assertTrue($ip_checker->isAllowedIp($ip));
		}

		$this->assertFalse($ip_checker->isAllowedIp('10.22.11.25'));
		$this->assertFalse($ip_checker->isAllowedIp('10.33.11.25'));
		$this->assertFalse($ip_checker->isAllowedIp('10.44.11.25'));
		$this->assertFalse($ip_checker->isAllowedIp('10.55.11.25'));
		$this->assertFalse($ip_checker->isAllowedIp('10.66.11.25'));

		$config->resetInstance();
	}

	public function testValidateIpAddress()
	{
		$ip_type = null;
		$config  = Config::getInstance();
	
		// clear ip rules and allowed ip addresses to cause an exception
		$config->setIpCheckerRules(array());
		$config->setAllowedIpAddresses(array());

		$ip_checker = new IpChecker();

		$exception_thrown = false;
		try {
			$ip_type = $ip_checker->validateIpAddress(IpChecker::getIp());
		} catch (Exception $exception) {
			$exception_thrown = true;
		}
		$this->assertTrue($exception_thrown);
		$this->assertEqual($ip_type, null);

		$config->resetInstance();

		$ip_checker = new IpChecker();

		$exception_thrown = false;
		try {
			$ip_type = $ip_checker->validateIpAddress(IpChecker::getIp());
		} catch (Exception $exception) {
			$exception_thrown = true;
		}
		$this->assertFalse($exception_thrown);
		$this->assertEqual($ip_type, 2);
	}
}
