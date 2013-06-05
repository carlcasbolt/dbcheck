<table width="100%">
	<tr>
		<td width="50%">
			<div class="comparison_type">
				<h2>Compare Database Structures</h2>
					<?php $action = 'dbList' ?>
					<?php include 'environment_matrix.php'; ?>
			</div>
		</td>
		<td width="50%">
			<div class="comparison_type">
				<h2>Compare Database Server Variables</h2>
				<?php $action = 'dbStatus' ?>
				<?php include 'environment_matrix.php'; ?>
			</div>
		</td>
	</tr>
</table>

<h2>How to use this tool.</h2>

<ol class="how_to_list">
	<li>Select which two environments you wish to compare (master* and slave).</li>
	<li>Select which databases you wish to compare (pick the first letter or a database)</li>
	<li>
		Each database will have 3 states:
		<ul class="object_states">
			<li class="identical">Identical (no action required)</li>
			<li class="different">Different (changes are required)</li>
			<li class="missing">Missing (present on one environment only)</li>
		</ul>
	</li>
	<li>Select which database you wish to examine</li>
	<li>
		Each table will have 3 states:
		<ul class="object_states">
			<li class="identical">Identical (no action required)</li>
			<li class="different">Different (changes are required)</li>
			<li class="missing">Missing (present on one environment only)</li>
		</ul>
	</li>
	<li>Select which table you wish to examine</li>
	<li>
		Each column will have 3 states:
		<ul class="object_states">
			<li class="identical">Identical (no action required)</li>
			<li class="different">Different (changes are required)</li>
			<li class="missing">Missing (present on one environment only)</li>
		</ul>
	</li>
</ol>

<div>* The <span class="master">master environment</span> is the target for the other environment. SQL will be shown to alter the selected environment to match the master.</div>
