<?php 

$request = WebRequest::getInstance();

$query_parts = array(
	'action'              => $request->getParameter('action'),
	'server_environments' => strrev($request->getParameter('server_environments')),
	'starting_with'       => $request->getParameter('starting_with'),
	'database'            => $request->getParameter('database'),
	'table'               => $request->getParameter('table'),
);

?>

<div class="swap_master">
	<a href="index.php?<?php print http_build_query($query_parts) ?>">Swap Master and Slave</a>
</div>