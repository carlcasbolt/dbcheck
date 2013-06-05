<table class="server_status">
	<tr>
<?php
		for($i = 0; $i < count($server_status); $i++) {
			print "\t\t<th class=\"name\">{$server_status[$i]['name']}</th>\n";
		}
?>
	</tr>
	<tr>
<?php
		for($i = 0; $i < count($server_status); $i++) {
			$status = $server_status[$i]['online'] ? 'online' : 'offline';

			print "\t\t<td class=\"status {$status}\">". strtoupper($status) . "</td>\n";
		}
?>
	</tr>
</table>

