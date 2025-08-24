<?php

if (!file_exists("settings.php")) {
	echo "To setup the wiki, setup <code>settings.php</code> and then reload this page.";
	exit;
}

require_once "settings.php";

if (isset($GLOBALS['check']) && $GLOBALS['check'] === true) {
	echo "I don't know for certain but using the default <code>settings.php</php> is not recommended.";
	exit;
}

// Slashing trailing slashes in directory definitions could cause issues otherwise
$GLOBALS['upload_dir'] = rtrim($GLOBALS['upload_dir'], "/");
$GLOBALS['page_dir'] = rtrim($GLOBALS['page_dir'], "/");
$GLOBALS['archive_dir'] = rtrim($GLOBALS['archive_dir'], "/");

/**
 * To execute a database query using the PDO object from $GLOBALS.
 * @param string $query  - the query to execute
 * @param array  $params - the params to give with the query
 *
 * @return PDOStatement $stmt - the statement or the result of the query
 */
function dbExec(string $query, array $params = []) {
	$stmt = $GLOBALS['db']->prepare($query);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);

	$res = $stmt->execute(count($params) > 0 ? $params : null);
	if (!$res) {
		echo "ERROR executing query: ".$stmt->errorCode;
		exit(1);
	}
	return $stmt;
}

/**
 * I want to have a way to quickly return errors with PHP if something isn't right
 * @param int    $errCode - error code
 * @param string $msg     - message for the error
 */
function exitWithError(int $errCode, string $msg): void {
	http_response_code($errCode);
	echo "Response: ".$msg;
	exit;
}

/**
 * Check if the user is logged in
 *
 * @return bool - if the user is logged in
 */
function isLoggedIn() {
	if (!isset($_COOKIE['login']) && !isset($_SESSION['login'])) {
		return false;
	}
	$GLOBALS['logins'][$_COOKIE['login']['user_id']] = $_COOKIE['login']['name'];
	return true;
}

/**
 * Check if the user is logged in otherwise return error
 */
function checkLoggedIn() {
	if (!isLoggedIn()) {
		exitWithError(403, "Not logged in!");
	}
}

/**
 * Generate HTML for the header of the page 
 * @param string $htTitle - title of the page
 *
 * @return string $htOut - the output of the header
 */
function htHeader(string $htTitle = ""): string {
	$htOut = "";
	$htOut .= "<!DOCTYPE html>\n";
	$htOut .= "<html lang=".$GLOBALS['lang'].">\n";
	$htOut .= "<head>\n";
	$htOut .= "<meta charset='utf-8'>";
	$htOut .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
	$htOut .= "<link rel='icon' type='image/png' href='static/icon32x32.png'/>\n";
	$htOut .= "<link rel='stylesheet'  href='style/index.css'/>\n";
	$htOut .= "<title>THOMTHOMTHOM - ".$htTitle."</title>\n";
	$htOut .= "</head>\n";
	$htOut .= "<body>\n";
	$htOut .= "<header>\n";
	$htOut .= "<h1>".$htTitle."</h1>";
	$htOut .= "<nav>\n";
	$htOut .= "<a href='index.php'>Home</a>\n";
	$htOut .= "<a href='page.php'>New Page</a>\n";
	$htOut .= "<a href='login.php?logout=1'>Logout</a>\n";
	$htOut .= "</nav>\n";
	$htOut .= "</header>\n";
	$htOut .= "<main>\n";
	return $htOut;
}

/**
 * Generate HTML for the footer of the page 
 *
 * @return string $htOut - the output of the footer
 */
function htFooter(): string {
	$htOut = "";
	$htOut .= "</main>\n";
	$htOut .= "</body>\n";
	$htOut .= "</html>\n";
	return $htOut;
}
