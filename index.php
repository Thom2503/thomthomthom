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
	echo "<a href='page.php'>Add page</a>";
	echo "</p>";
} else {
	echo "<ul>\n";
	foreach ($allArticles as $k => $page) {
		$title = htmlentities($page['pagename']);
		echo "<li><a href='page.php?title=".$title."'>".$title."</a></li>\n";
	}
	echo "</ul>\n";
}
echo "</div>\n";
echo "</div>\n";

echo htFooter();