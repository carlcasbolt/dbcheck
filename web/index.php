<?php

define('DOC_ROOT', dirname(__FILE__));
define('APP_ROOT', dirname(DOC_ROOT));

require_once APP_ROOT.'/config.php';
require_once APP_ROOT.'/init.php';

$request = new WebRequest();

$action              = $request->getParameter('action');
$server_environments = $request->getParameter('server_environments');
$starting_with       = $request->getParameter('starting_with');
$database            = $request->getParameter('database');
$table               = $request->getParameter('table');

$controller = new Controller($action, $server_environments, $starting_with, $database, $table);
$controller->execute();

