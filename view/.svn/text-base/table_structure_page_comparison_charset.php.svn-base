<?php

/**
 * 
 * +---------------+----------------+----------------+-----------+
 * |               | Server 1       | Server 2       | state     |
 * +---------------+----------------+----------------+-----------+
 * | Character Set | CHARSET=utf8   | CHARSET=utf8   | identical |
 * +---------------+----------------+----------------+-----------+
 * 
 */

?>

<table class="comparison">
	<tr class="heading">
		<th></th>
<?php

foreach($selected_servers as $server_name) {
	print "\t\t<th>{$server_name}</th>\n";
}

print "\t\t<th>State</th>\n";
print "\t</tr>\n";

if (is_array($comparison_data) && $comparison_data['other']) {
	foreach($comparison_data['other'] as $table_row) { 
		$name = $table_row['name'];

		print "\t<tr>\n";
		print "\t\t<td>{$name}</td>\n";

		foreach($table_row['data'] as $table_cell) {
			$state = $table_cell['state'];
			$text  = str_replace("\n", "<br>\n", $table_cell['text']);

			print "\t\t<td class=\"status {$state}\">{$text}</td>\n";
		}

		$state = $table_row['state'];

		print "\t\t<td class=\"status {$state}\">{$state}</td>\n";
		print "\t</tr>\n";
}
}

?>
</table>


