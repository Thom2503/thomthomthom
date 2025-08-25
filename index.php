<?php

require "common.php";

echo htHeader("Home");

$allArticles = dbExec("SELECT * FROM `pages`")->fetchAll();

echo "<div>\n";
echo "<h2>THOMTHOMTHOM</h2>\n";
echo "<p>A personal wiki, I wanted to have a place for all the knowledge I gain".
	" to be stored somewhere. Inspired by many sites I decided to make my own.".
	" This is THOMTHOMTHOM, a personal wiki. All the pages are being listed below".
	" in chronological order, and all pages are <code>.txt</code> only. I chose".
	" <code>.txt</code> to stand out on the rest.</p>";
echo "<p>Read this: <a href='page.php?title=THOMTHOMTHOM'>THOMTHOMTHOM</a>";
echo "</div>\n";
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