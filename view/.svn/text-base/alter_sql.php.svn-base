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
<h3 id="alter_sql">SQL to update <?php print $selected_servers['slave'] ?> to match <?php print $selected_servers['master'] ?></h3>

<p>
	<a href="index.php?<?php print http_build_query($query_parts) ?>#alter_sql">Swap Master and Slave</a>
</p>

<pre class="code sql">
<?php print $alter_sql; ?>
</pre>

