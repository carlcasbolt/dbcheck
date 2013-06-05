<?php

/**
 * IpChecker
 *
 * Security Checker to make sure that only valid locations can use this script. If you are not
 * on a valid IP address then this script should kill execution with an error message.
 * 
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class IpChecker
{
	const LOCALHOST   = 1; // Request made from the localhost
	const SERVER_IP   = 2; // Request made from this server
	const INTERNAL_IP = 3; // Request made from the local network
	const ALLOWED_IP  = 4; // Request made from an allowed IP Address

	/**
	 * An instance of this class because we have implemented the Singleton Pattern
	 */
	private static $instance;

	/**
	 * A list of IP Address rules to state where we can connect to this client from.
	 *
	 * By default you can always connect from:
	 *
	 * - The local IP address
	 * - The server's own IP address
	 * - Any internal IP address as dictated by RFC ##### 
	 * 
	 * @var array
	 * @access private
	 */
	private $_ip_rules   = array(
			'local_ip_allowed'    => true,
			'server_ip_allowed'   => true,
			'internal_ip_allowed' => true,
			);

	private $_allowed_ip_addresses = array();

	/**
	 * The object constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$config = Config::getInstance();

		$this->setIpCheckerRules($config->getIpCheckerRules());
		$this->setAllowedIpAddresses($config->getAllowedIpAddresses());
	}

	public function setIpCheckerRules($rules)
	{
		$this->_ip_rules = $rules;
	}

	public function setAllowedIpAddresses($addresses)
	{
		$this->_allowed_ip_addresses = $addresses;
	}

	/**
	 * get an instance of this class and create an instance if we haven't already.
	 *
	 * @static
	 * @return IpChecker instance
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new IpChecker(); 
		}
		return self::$instance;
	}

	/**
	 * @return integer - the type of IP address used for the request
	 */
	public function validateIpAddress($ip_address)
	{
		if ($this->ipAddressTypeAllowed('local_is_allowed')    && self::isLocalIp($ip_address)) {
			return self::LOCALHOST;
		}
		if ($this->ipAddressTypeAllowed('server_ip_allowed')   && self::isServerIp($ip_address)) {
			return self::SERVER_IP;
		}
		if ($this->ipAddressTypeAllowed('internal_ip_allowed') && self::isInternalIp($ip_address)) {
			return self::INTERNAL_IP;
		}

		// manually specified in the config
		if ($this->isAllowedIp($ip_address)) {
			return self::ALLOWED_IP;
		}

		// if we get to this then we don't have a connection from an authorised IP address
		throw new Exception("Ip Address Error: You cannot connect to this service from your current location. - {$ip_address}");
	}

	/**
	 * Work out the current IP Address for this request. 
	 * 
	 * @return string
	 */
	public static function getIp()
	{
		global $_SERVER;

		if (isset($_SERVER['HTTP_PROXY_USER'])) {
			return $_SERVER['HTTP_PROXY_USER'];
		}
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * return the IP address of this web server.
	 *
	 * @static
	 * @return string
	 */
	public static function getServerIp()
	{
		global $_SERVER;

		return $_SERVER['SERVER_ADDR'];
	}

	public function ipAddressTypeAllowed($type)
	{
		return isset($this->_ip_rules[$type]) ? $this->_ip_rules[$type] : false;
	}

	/**
	 * Check to see if the current IP address is localhost.
	 *
	 * @static
	 * @return void
	 */
	public static function isLocalIp($ip_address)
	{
		return $ip_address == '127.0.0.1';
	}

	/**
	 * Check to see if the current IP address is the same as the Server.
	 * 
	 * @static
	 * @return boolean
	 */
	public static function isServerIp($ip_address)
	{
		return $ip_address == self::getServerIp();
	}

	/**
	 * Work out if the IP address this request was made from is within the provided IP address range.
	 *
	 * @static
	 * @param string $min_ip 
	 * @param string $max_ip 
	 * @return boolean
	 */
	public static function inIpRange($ip_address, $min_ip, $max_ip)
	{
		if (empty($ip_address) || empty($min_ip) || empty($max_ip)) {
			return false;
		}
		$ip_as_long     = ip2long($ip_address);
		$min_ip_as_long = ip2long($min_ip);
		$max_ip_as_long = ip2long($max_ip);

		return (($ip_as_long >= $min_ip_as_long) && ($ip_as_long <= $max_ip_as_long));
	}

	/**
	 * Check to see if the current IP address is part of a local IP range. The local IP address ranges
	 * that we consider to be valid are:
	 *
	 *   10.0.0.0 -> 10.255.255.255
	 *   172.16.0.0 -> 172.31.255.255
	 *   192.168.0.0 -> 192.168.255.255
	 *
	 * @static
	 * @return boolean
	 */
	public static function isInternalIp($ip_address)
	{
		return self::inIpRange($ip_address, '10.0.0.0', '10.255.255.255')
		    || self::inIpRange($ip_address, '172.16.0.0', '172.31.255.255')
		    || self::inIpRange($ip_address, '192.168.0.0', '192.168.255.255');
	}

	/**
	 * Override for custom IP addresses which could include the Blue Fin building if the server does not see
	 * this script as opperating on a local IP address. 
	 * 
	 * @return boolean
	 */
	public function isAllowedIp($ip_address)
	{
		return in_array($ip_address, $this->_allowed_ip_addresses);
	}
}

