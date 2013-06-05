<?php

/**
 * 
 * +-------------+------------------------------------+------------------------------------+-----------+
 * |             | Server 1                           | Server 2                           |           |
 * | column name +-------------------+------+---------+-------------------+------+---------+ state     |
 * |             | type              | NULL | default | type              | NULL | default |           |
 * +-------------+-------------------+------+---------+-------------------+------+---------+-----------+
 * | one         | int(9) unsigned   | No   |         | int(9) unsigned   | No   |         | identical |
 * | two         | varchar(255)      | No   |         | varchar(100)      | No   |         | different |
 * | three       | varchar(255)      | No   |         |        -- MISSING --               | different |
 * +-------------+-------------------+------+---------+-------------------+------+---------+-----------+
 * 
 */ 

?>
<table class="comparison">
    <tr class="heading">
        <th rowspan="2">column name</th>
<?php

foreach($selected_servers as $server_name) {
    print "\t\t<th colspan=\"3\">{$server_name}</th>\n";
}

print "\t\t<th rowspan=\"2\">State</th>\n";
print "\t</tr>\n";

$headings = array('type', 'NULL', 'default', 'type', 'NULL', 'default');

print "\t<tr>\n";
foreach($headings as $heading) {
	print "\t\t<th>{$heading}</th>\n";
}
print "\t</tr>\n";

if (is_array($comparison_data) && $comparison_data['columns']) {
	foreach($comparison_data['columns'] as $table_row) {
		$name = $table_row['name'];
	
		print "\t<tr>\n";
		print "\t\t<td>{$name}</td>\n";
	
		foreach($table_row['data'] as $server_data) {
			foreach($server_data as $table_cell) {
				$colspan = (isset($table_cell['colspan']) ? $table_cell['colspan'] : 1);
	
				$state = $table_cell['state'];
				$text  = $table_cell['text'];
	
				print "\t\t<td colspan=\"{$colspan}\" class=\"status {$state}\">{$text}</td>\n";
			}
		}
		$state = $table_row['state'];
	
		print "\t\t<td class=\"status {$state}\">{$state}</td>\n";
		print "\t</tr>\n";
	}
}

?>
</table>

