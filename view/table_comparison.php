<div id="comparison_results">
<table class="comparison">
	<tr class="heading">
		<th><?php print (isset($selected_database)) ? "Table Name" : "Database Name" ?></th>
<?php

foreach($selected_servers as $server_name) {
	print "\t\t<th colspan=\"3\">{$server_name}</th>\n";
}

print "\t\t<th>State</th>\n";
print "\t</tr>\n";

$state_counters = array(
	'different' => 0,
	'identical' => 0,
);

$request = WebRequest::getInstance();

if (is_array($comparison_data)) {
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
		print "\t\t<td class=\"status\"><a href=\"index.php?".http_build_query($query_parts)."\">{$table_row['name']}</a></td>\n";
	
		foreach($table_row['data'] as $table_cell) {
			if ($table_cell['state'] == 'missing') {
				print "\t\t<td class=\"status missing\" colspan=\"3\">{$table_cell['text']}</td>\n";
			} else {
				print "\t\t<td class=\"status present\">{$table_cell['state']}</td>\n";
				print "\t\t<td class=\"status present\">{$table_cell['charset']}</td>\n";
				print "\t\t<td class=\"status present\">{$table_cell['engine']}</td>\n";
			}
		}
		print "\t\t<td class=\"status {$state}\">{$state}</td>\n";
		print "\t</tr>\n";
	}
}

?>
</table>
</div>

<div style="clear: both;"></div>

