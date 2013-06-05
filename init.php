<?php

$version = file_get_contents(dirname(__FILE__) . '/VERSION');

define('DBCHECK_VERSION', trim($version));

/**
 * Used to update the include path for this application. We have files within some directories
 * we want php's autoload to be able to find as well as template files for each view.
 *
 * ./lib  - class files
 * ./view - template files called by the controllers
 *
 * @access public
 * @return void
 */
function update_include_path()
{
	$lib_dir  = dirname(__FILE__).'/lib';
	$view_dir = dirname(__FILE__).'/view';

	ini_set('include_path', ini_get('include_path') . ':' . $lib_dir . ':' . $view_dir . ':');
}
update_include_path();

/**
 * Special PHP method which is called when an undefined class is used. Thie method will attempt
 * to load said class in the way that is described below.
 * 
 * @param string $class_name 
 * @access protected
 * @return void
 */
function __autoload($class_name) {
	include_once($class_name . '.class.php');
}

/**
 * Check that the request is coming from an authorised IP address. If not then this method will
 * kill the current execution and display an error message.
 */
try {
	IpChecker::getInstance()->validateIpAddress(IpChecker::getIp());
} catch (Exception $exception) {
	die($exception->getMessage());
}

