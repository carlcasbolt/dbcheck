<?php

define('DOC_ROOT', dirname(__FILE__));
define('APP_ROOT', dirname(DOC_ROOT));

require_once APP_ROOT.'/config.php';
require_once APP_ROOT.'/init.php';

$request = new WebRequest();

$action        = $request->getParameter('action');
$starting_with = $request->getParameter('starting_with');
$database      = $request->getParameter('database');
$table         = $request->getParameter('table');

$query = new Query($action, $starting_with, $database, $table);
$query->execute();

