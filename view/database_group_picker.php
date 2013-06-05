<h3>List databases beginning with</h3>
<div class="database_filtering">
<?php 

$letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

foreach($letters as $letter) {
	$link = "index.php?action=dbList&server_environments={$server_environments}&starting_with={$letter}";

	if ($starting_with == $letter) {
		print "\t<span class=\"letters selected\">" . strtoupper($letter) . "</span>\n";
	} else {
		print "\t<a href=\"{$link}\" class=\"letters\">" . strtoupper($letter) . "</a>\n";
	}
}
	
?>
	<div class="clear_both"></div>
</div>

<?php $link = "index.php?action=dbList&server_environments={$server_environments}&starting_with=%"; ?>
<div class="database_filtering">
<?php

if ($starting_with == '%') {
	print "\t<span class=\"list_all\">All (this may take a long time)</span>\n";
} else {
	print "<a href=\"{$link}\" class=\"list_all\">All (this may take a long time)</a>\n";
}

?>
	<div class="clear_both"></div>
</div>

