<?php

$action = $_REQUEST['action'];

header ("content-type: text/xml");

print file_get_contents(dirname(__FILE__) . "/{$action}.xml");
