<?php include 'toggle_result_links.php'; ?>

<div id="comparison_results">
<table class="comparison">
	<tr class="heading">
		<th>Server Variable</th>
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

if (is_array($comparison_data)) {
	foreach ($comparison_data as $table_row) {
		$name  = @$table_row['name'];
		$state = @$table_row['state'];
	
		print "\t<tr class=\"state {$state}\">\n";
		print "\t\t<td>{$name}</td>\n";
	
		if (isset($table_row['data'])) {
			foreach($table_row['data'] as $table_cell) {
				print "\t\t<td class=\"status {$table_cell['state']}\">{$table_cell['text']}</td>\n";
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

