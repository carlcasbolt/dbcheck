<?php

/**
 * WebRequest
 *
 * Request handler class to make getting parameters from the request a little easier
 * 
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class WebRequest
{
	/**
	 * The stored instance of this class
	 */
	static $_instance = null;

	/**
	 * An internal member to store the data from $_REQUEST
	 * as it was when the instance is created
	 */
	private $_request = array();

	/**
	 * @return  WebRequest
	 */
	public function __construct()
	{
		global $_REQUEST;
		
		$this->_request = $_REQUEST;
	} // end of __construct()

	/**
	 * Return the current instance of the WebRquest object.
	 * If there is not instance then a new one is created.
	 * 
	 * @return void
	 */
	public static function getInstance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new WebRequest();
		}
		return self::$_instance;
	} // end of getInstance()
	
	/**
	 * Retrieve the value for a given parameter from the WebRequest object.
	 * If there is no matching value then NULL is returned.
	 * 
	 * @param   string  $name   - the parameter name we want to retrieve 
	 * @return  mixed
	 */
	public function getParameter($name)
	{
		return isset($this->_request[$name]) ? $this->_request[$name] : null;
	} // end of getParameter()

	/**
	 * Add a new value or update an existing within the current WebRequest object
	 * 
	 * @param   string  $name   - the parameter name we want to store
	 * @param   mixed   $value  - the value for this parameter
	 */
	public function setParameter($name, $value)
	{
		$this->_request[$name] = $value;
	} // end of setParameter()
	
} // end of class
