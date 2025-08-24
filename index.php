<?php

require "common.php";

echo htHeader("Home");

$allArticles = dbExec("SELECT * FROM `pages`;")->fetchAll();

echo "<div>\n";
echo "<h2>All articles</h2>\n";
echo "<div>\n";
if (count($allArticles) == 0 && !isLoggedIn()) {
	echo "<p>No pages found, yet\n";
	echo "If you can, add them here: \n";
	echo "<a href='page.php'>Add page</a>";
	echo "</p>";
} else {
	$articles = array_filter($allArticles, function ($page) {
		return !(!isLoggedIn() && $page['public'] == false);
	});

	if (count($articles) > 0) {
		echo "<ul>\n";
		foreach ($articles as $k => $page) {
			if (!isLoggedIn() && $page['public'] == false) continue;
			$title = htmlentities($page['pagename']);
			echo "<li><a href='page.php?title=".urlencode($title)."'>".$title."</a></li>\n";
		}
		echo "</ul>\n";
	} else {
		echo "<p> No articles are public to see.</p>";
	}
}
echo "</div>\n";
echo "</div>\n";

echo htFooter();