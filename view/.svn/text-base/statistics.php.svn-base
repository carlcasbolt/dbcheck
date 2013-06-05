<div id="statistics">
	<table class="comparison">
		<tr>
			<th colspan="2">Comparison Statistics</th>
		</tr>

		<tr>
			<td class="status">present on both &amp; identical</td>
			<td class="status identical"><?php print $statistics->getIdentical() ?></td>
		</tr>
		<tr>
			<td class="status">present on both &amp; different</td>
			<td class="status different"><?php print $statistics->getDifferentAndPresentOnBoth() ?></td>
		</tr>

		<?php foreach ($selected_servers as $server_type => $server_name) { ?>

		<tr>
			<td class="status">missing on <?php print $server_name ?></td>
			<td class="status missing"><?php print $statistics->getMissing($server_type) ?></td>
		</tr>

		<?php } ?>

		<tr>
			<td class="status">total</td>
			<td class="status"><?php print $statistics->getTotalCount() ?></td>
		</tr>
	</table>
	
<?php if ($statistics->hasEngineStatistics()) { ?>

	<table class="comparison">
		<tr>
			<th colspan="4">Engine Statistics</th>
		</tr>
		<tr>
			<th colspan="2"><?php print $selected_servers['master'] ?></th>
			<th colspan="2"><?php print $selected_servers['slave'] ?></th>
		</tr>

		<?php foreach ($statistics->getAllEngines() as $engine) { ?>
		<?php $classes = $statistics->hasIdenticalEngineCount($engine) ? 'status identical' : 'status different' ?>

		<tr>
			<td class="status">
				<?php print $engine ?>
			</td>
			<td class="<?php print $classes ?>">
				<?php print $statistics->getEngineCount('master', $engine) ?>
			</td>
			<td class="status">
				<?php print $engine ?>
			</td>
			<td class="<?php print $classes ?>">
				<?php print $statistics->getEngineCount('slave', $engine) ?>
			</td>
		</tr>

		<?php } ?>

	</table>

<?php } ?>

<?php if ($statistics->hasCharacterSetStatistics()) { ?>

	<table class="comparison">
		<tr>
			<th colspan="4">Character Set Statistics</th>
		</tr>
		<tr>
			<th colspan="2"><?php print $selected_servers['master'] ?></th>
			<th colspan="2"><?php print $selected_servers['slave'] ?></th>
		</tr>

		<?php foreach ($statistics->getAllCharacterSets() as $charset) { ?>
		<?php $classes = $statistics->hasIdenticalCharacterSetCount($charset) ? 'status identical' : 'status different' ?>
		
		<tr>
			<td class="status">
				<?php print $charset ?>
			</td>
			<td class="<?php print $classes ?>">
				<?php print $statistics->getCharacterSetCount('master', $charset) ?>
			</td>
			<td class="status">
				<?php print $charset ?>
			</td>
			<td class="<?php print $classes ?>">
				<?php print $statistics->getCharacterSetCount('slave', $charset) ?>
			</td>
		</tr>

		<?php } ?>

	</table>

<?php } ?>
	
</div>
