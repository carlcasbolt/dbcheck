<?php

$links = array();
$current_page = "";

if (isset($selected_servers)) {
	$links[] = array(
		'link' => 'index.php',
		'text' => 'Start again',
	);
	if ($action == 'dbStatus') {
		$current_page = "server variables";
	} else {
		$current_page = "select database";
	}
}

if (isset($selected_servers) && isset($selected_database)) {
	$query_parts = array(
		'action'              => 'dbList',
		'server_environments' => $server_environments,
	);
	$links[] = array(
		'link' => 'index.php?' . http_build_query($query_parts),
		'text' => 'chance selected database',
	);
	$current_page = "table list for '{$selected_database}'";
}

if (isset($selected_servers) && isset($selected_database) && isset($selected_table)) {
	$query_parts = array(
		'action'              => 'tblList',
		'server_environments' => $server_environments,
		'database'            => $selected_database,
	);
	$links[] = array(
		'link' => 'index.php?' . http_build_query($query_parts),
		'text' => 'change selected table',
	);
	$current_page = "table structure for '{$selected_database}'.'{$selected_table}'";
}

?>

<div id="navigation_bar">
<?php

foreach($links as $key => $link) {
	echo "\t<a href=\"{$link['link']}\">{$link['text']}</a> >\n";
}

echo "\t{$current_page}\n";

?>
</div>
