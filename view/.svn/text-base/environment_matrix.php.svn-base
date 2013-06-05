<?php

$available_servers = $server_status;

foreach ($available_servers as $idx => $server) {
	if (!$server['online']) unset($available_servers[$idx]);
}

?>

<table class="env_picker">
	<tr>
		<th></th>
<?php foreach ($available_servers as $server) { ?>
		<th class="slave"><?php print $server['name'] ?></th>
<?php } /* end foreach ($available_servers as $server) { */ ?>
	</tr>
<?php foreach ($available_servers as $idX => $serverX) { ?>
	<tr>	
		<th class="master"><?php print $serverX['name'] ?></th>
<?php foreach ($available_servers as $idY => $serverY) { ?>
<?php if ($idX == $idY) { ?>
		<td class="n_a">n/a</td>
<?php } else { ?>
		<?php $link = "index.php?action={$action}&server_environments={$idX}|{$idY}"; ?>
		<td class="select"><a href="<?php print $link ?>">compare</a></td>
<?php } /* end if ($idX == $idY) { */ ?>
<?php } /* end foreach ($available_servers as $idY => $serverY) { */ ?>
	</tr>
<?php } /* end foreach ($available_servers as $idX => $serverX) { */ ?>
</table>

