<div id="comparison_results">
<table class="comparison">
	<tr class="heading">
		<th><?php print (isset($selected_database)) ? "Table Name" : "Database Name" ?></th>
<?php

foreach($selected_servers as $server_name) {
	print "\t\t<th>{$server_name}</th>\n";
}

print "\t\t<th>State</th>\n";
print "\t</tr>\n";

$state_counters = array(
	'different' => 0,
	'identical' => 0,
);

$request = WebRequest::getInstance();

foreach($comparison_data as $table_row) {
	if (isset($selected_database)) {
		$query_parts = array(
			'action'              => Query::TABLE_STRUCTURE,
			'server_environments' => $request->getParameter('server_environments'),
			'database'            => $selected_database,
			'table'               => $table_row['name'],
		);
	} else {
		$query_parts = array(
			'action'              => Query::TABLE_LIST,
			'server_environments' => $request->getParameter('server_environments'),
			'database'            => $table_row['name'],
		);
		$query_parts['database'] = $table_row['name'];
	}
	$state = $table_row['state'];

	print "\t<tr class=\"state {$state}\">\n";
	print "\t\t<td><a href=\"index.php?".http_build_query($query_parts)."\">{$table_row['name']}</a></td>\n";

	foreach($table_row['data'] as $table_cell) {
		print "\t\t<td class=\"status {$table_cell['state']}\">{$table_cell['text']}</td>\n";
	}
	print "\t\t<td class=\"status {$state}\">{$state}</td>\n";
	print "\t</tr>\n";
}

?>
</table>
</div>

<div style="clear: both;"></div>

