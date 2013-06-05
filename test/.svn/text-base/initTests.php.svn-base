<?php

define ('APP_DIR', dirname(dirname(__FILE__)));
define ('FIXTURES_DIR', dirname(__FILE__) . '/fixtures'); 
define ('ST_LIB_DIR', dirname(__FILE__) . '/simpletest'); 

$version = file_get_contents(APP_DIR . '/VERSION');

define('DBCHECK_VERSION', trim($version));

function update_include_path()
{
	$lib_dir  = dirname(dirname(__FILE__)) . '/lib';
	$view_dir = dirname(dirname(__FILE__)) . '/view';

	ini_set('include_path', ini_get('include_path').':'.$lib_dir.':'.$view_dir.':');
}
update_include_path();

function __autoload($class_name) {
	include_once($class_name.'.class.php');
}

require_once FIXTURES_DIR . '/SampleComparisonData.php';
require_once FIXTURES_DIR . '/SampleTestData.php';

require_once ST_LIB_DIR . '/autorun.php';
require_once ST_LIB_DIR . '/mock_objects.php';


