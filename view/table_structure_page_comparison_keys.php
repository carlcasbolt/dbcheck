<?php

/**
 * 
 * +----------+--------------------------------------+--------------------------------------+-----------+
 * |          | Server 1                             | Server 2                             |           |
 * | key name +--------------------------------------+--------------------------------------+ state     |
 * |          | keyname | type | cardinality | field | keyname | type | cardinality | field |           |
 * +----------+--------------------------------------+--------------------------------------+-----------+
 *  
 */

?>
<table class="comparison">
	<tr class="heading">
		<th rowspan="2">key name</th>
<?php

foreach($selected_servers as $server_name) {
	print "\t\t<th colspan=\"2\">{$server_name}</th>\n";
}

print "\t\t<th rowspan=\"2\">State</th>\n";
print "\t</tr>\n";

$headings = array('type', 'field', 'type', 'field');

print "\t<tr>\n";
foreach($headings as $heading) {
    print "\t\t<th>$heading</th>\n";
}
print "\t</tr>\n";

$key_name_shown = array();
$key_type_shown = array();

if (is_array($comparison_data) && $comparison_data['keys']) {
	foreach($comparison_data['keys'] as $table_row) {
		$key_name = $table_row['key_name'];
	
		foreach($table_row['columns'] as $column_name) {
			print "\t<tr>\n";
	
			if (!isset($key_name_shown[$key_name])) {
				print "\t\t<td rowspan=\"{$table_row['rowspan']}\">{$key_name}</td>\n";
	
				$key_name_shown[$key_name] = true;
			}
			foreach($table_row['data'] as $server_name => $table_cell) {
				if (!isset($key_type_shown[$key_name][$server_name])) {
					$key_type = $table_row['type'][$server_name];
					$rowspan  = isset($table_row['rowspan']) ? $table_row['rowspan'] : 1;
					$colspan  = isset($key_type['colspan'])  ? $key_type['colspan']  : 1;
					$state    = $key_type['state'];
					$text     = $key_type['text'];
	
					print "\t\t<td rowspan=\"{$rowspan}\" colspan=\"{$colspan}\" class=\"status {$state}\">{$text}</td>\n";
	
					$key_type_shown[$key_name][$server_name] = true;
				}
				if (isset($table_cell[$column_name]) && is_array($table_cell[$column_name])) {
					$state = $table_cell[$column_name]['state'];
					$text  = $table_cell[$column_name]['text'];
		
					print "\t\t<td class=\"status {$state}\">{$text}</td>\n";
				}
			}
			if (!isset($key_state_shown[$key_name])) {
				$rowspan = $table_row['rowspan'];
				$state   = $table_row['state'];
				$text    = $table_row['state'];
	
				print "\t\t<td rowspan=\"{$rowspan}\" class=\"status {$state}\">{$text}</td>\n";
	
				$key_state_shown[$key_name] = true;
			}
			print "\t</tr>\n";
		}
	}
} else { ?>
	<tr>
		<td colspan="6" class="status different"> - No Keys present - </td>
	</tr>
<?php } ?>
</table>

