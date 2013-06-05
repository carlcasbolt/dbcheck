<div id="filter_information">
	<table>
		<tr>
			<th>Selected Server Environements</th>
			<td>
				<span class="master" id="master_env"><?php print $selected_servers['master'] ?> (master)</span>
				|
				<span class="slave" id="slave_env"><?php print $selected_servers['slave'] ?> (slave)</span>
			</td>
			<td>
				<?php include 'swap_master.php'; ?>
			</td>
		</tr>
		<tr>
			<?php if (isset($selected_database)) { ?>
				<th>Selected Database</th>
				<td colspan="2"><span id="selected_database"><?php print $selected_database ?></span></td>
			<?php } ?>
		</tr>
		<?php if (isset($selected_table)) { ?>
		<tr>
			<th>Selected Table</th>
			<td colspan="2"><span id="selected_table"><?php print $selected_table ?></span></td>
		</tr>
		<?php } ?>
	</table>
<?php

if (!isset($selected_database)) {
	include 'database_group_picker.php';
}

?>
</div>


	

