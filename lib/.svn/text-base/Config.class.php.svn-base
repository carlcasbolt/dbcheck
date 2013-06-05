<?php

class Config
{
	static $instance;

	private $_config      = null;
	private $_config_file = null;

	private $_db_hostname;
	private $_db_username;
	private $_db_password;

	private $_ip_checker_rules;
	private $_allowed_ip_addresses;

	private $_server_list;

	public function __construct()
	{
		$config_file = $this->getConfigFile();
	
		try {
			$this->checkConfigFile($config_file);
		} catch (ConfigFileException $exception) {
			$this->exitWithError($exception);
		} catch (Exception $exception) {
			// not sure what happened here, pass the exception up the execution chain
			throw $exception;
		}
		// require the config file
		require $config_file;

		$this->setDbHostname($_CONFIG['db_host']);
		$this->setDbUsername($_CONFIG['db_user']);
		$this->setDbPassword($_CONFIG['db_pass']);

		$this->setIpCheckerRules($_CONFIG['ip_checker_rules']);
		$this->setAllowedIpAddresses($_CONFIG['allowed_ip_addresses']);

		// optional config (server only)
		if (isset($_CONFIG['server_list'])) {
			$this->setServerList($_CONFIG['server_list']);
		}
	}

	static public function getConfigFile()
	{
		return dirname(dirname(__FILE__)) . '/config.php';
	}

	static public function getInstance()
	{
		if (!self::$instance) {
			self::resetInstance();
		}
		return self::$instance;
	}

	static public function resetInstance()
	{
		self::$instance = new Config();
	}

	public function checkConfigFile($config_file)
	{
		if (!is_file($config_file)) {
			throw new ConfigFileException('Config file not found');
		}
		if (!is_readable($config_file)) {
			throw new ConfigFileException('Cannot read the config file.');
		}
	}

	public function exitWithError($exception)
	{
		require_once 'config_file_error.php';
		exit;
	}

	public function getDbHostname()     { return $this->_db_hostname;      }
	public function getDbUsername()     { return $this->_db_username;      }
	public function getDbPassword()     { return $this->_db_password;      }
	public function getServerList()     { return $this->_server_list;      }

	public function setDbHostname($hostname)    { $this->_db_hostname = $hostname;    }
	public function setDbUsername($username)    { $this->_db_username = $username;    }
	public function setDbPassword($password)    { $this->_db_password = $password;    }
	public function setServerList($server_list) { $this->_server_list = $server_list; }

	// the following config variables need to be an array
 	public function getIpCheckerRules()     { return $this->_ip_checker_rules;     }
	public function getAllowedIpAddresses() { return $this->_allowed_ip_addresses; }

	public function setIpCheckerRules(array $rules)     { $this->_ip_checker_rules = $rules;      }
	public function setAllowedIpAddresses(array $addrs) { $this->_allowed_ip_addresses = $addrs; }
}

class ConfigFileException extends Exception {}
