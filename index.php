<?php

require "common.php";

if (!isLoggedIn()) {
	header("location: login.php");
}

echo htHeader("Home");

$allArticles = dbExec("SELECT * FROM `pages`;")->fetchAll();

echo "<div>\n";
echo "<h2>All articles</h2>\n";
echo "<div>\n";
if (count($allArticles) == 0) {
	echo "<p>No pages found, yet\n";
	echo "If you can, add them here: \n";
	echo "<a href='edit.php'>Add page</a>";
	echo "</p>";
} else {
	foreach ($allArticles as $k => $page) {
		print_r($k);
		print_r($page);
	}
}
echo "</div>\n";
echo "</div>\n";

echo htFooter();