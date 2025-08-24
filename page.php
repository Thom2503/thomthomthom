<?php

require "common.php";

$data = [];
$data['id'] = 0;
$data['pagename'] = null;
$data['madeby'] = "";
$data['ts'] = "";
$data['public'] = false;

$pageDir = $GLOBALS['page_dir'];
$archiveDir = $GLOBALS['archive_dir'];

if (isset($_GET['title']) && trim($_GET['title']) != "") {
	$res = dbExec("SELECT * FROM `pages` WHERE `pagename` = :title", ['title' => trim($_GET['title'])]);
	if ($res) {
		$data = $res->fetch();
	}
}

$pageName = $data['ts']."_".$data['pagename'].".txt";
$pageFile = $pageDir."/".$pageName;
$doesFileExist = file_exists($pageFile);
$editPage = isset($_GET['edit']) && $_GET['edit'] == true && isLoggedIn();
$showPageContent = false;

if (isset($_POST['field']['upload']) && count($_POST['field']) > 0 && $_POST['field']['upload'] != "") {
	$data['pagename'] = $_POST['field']['page_title'];
	$data['ts']	= time();
	$data['public'] = (int)(isset($_POST['field']['page_public']) && $_POST['field']['page_public'] === "Yes");
	$data['madeby'] = $_COOKIE['login']['user_id'];

	// if there is a file make it another archive
	if ($doesFileExist) {
		$archivedName = $archiveDir."/".$data['ts']."_".$data['pagename'].".txt";
		if (rename($pageFile, $archivedName) == false) {
			exitWithError(500, "Error archiving the page");
		}
	}

	// to write it to a file
	$newPageFile = $pageDir."/".$data['ts']."_".$data['pagename'].".txt";
	if (file_put_contents($newPageFile, $_POST['field']['page_content']) == false) {
		exitWithError(500, "Error with saving page.");
	}

	$query = "
		INSERT INTO pages (pagename, madeby, ts, public)
		VALUES (:pagename, :madeby, :ts, :public)
		ON CONFLICT(pagename) DO UPDATE SET
		    madeby = excluded.madeby,
		    public = excluded.public;
		    ts = excluded.ts;
	";
	dbExec($query,
		['pagename' => $data['pagename'],
		 'madeby' => $data['madeby'],
		 'ts' => $data['ts'],
		 'public' => $data['public']]
	);

	header("Location: index.php");
	exit;
}

if (!isLoggedIn() && $data['public'] == false) {
	header("Location: login.php");
	exit;
}
if ($data['public']) {
	$showPageContent = true;
}

echo htHeader($data['pagename'] ?? "Edit");

echo "<div>\n";
if ($doesFileExist) {
	$res = dbExec(
		"SELECT * FROM `users`
		 LEFT JOIN `pages` ON `users`.`id` = `pages`.`madeby`
		 WHERE `pages`.`id` = :id AND `pages`.`madeby` = :mid",
		['mid' => $data['madeby'], 'id' => $data['id']]
	)->fetch();

	echo "<em>Created: ".date("Y-m-d H:i", $data['ts'])." by ".htmlspecialchars($res['name'])."</em></br>\n";
} else {
	echo "<em>Editing page by: ".htmlspecialchars($_COOKIE['login']['name'])."</em></br>\n";
}

if ($showPageContent && $doesFileExist && !$editPage) {
	if (isLoggedIn()) {
		echo "<a href='page.php?title=".urlencode($data['pagename'])."&edit=1'>Edit page</a><br>\n";
	}
	$content = file_get_contents($pageFile);
	$safe = strip_tags($content, "<img><a>");
	echo "<pre>".$safe."</pre>\n";
} else {
?>
<div class="form-div">
	<dialog>
		<button autofocus>Close</button>
		<h3>Page edit help</h3>
		<p>
			Pages are edited as pure .txt files, but there is one exception.<br>
			Pages can have some html and that is the <code>&lt;img&gt;</code> tag and the <code>&lt;a&gt;</code> tag.
		</p>
	</dialog>
	<button id="help-button">Help</button>
	<form action="" method="post" id="edit-page-form">
		<p>
			<label for="title">
				Page title:
				<input type="text" id="title" name="field[page_title]"
					   value="<?php echo htmlspecialchars($data['pagename'] ?? ""); ?>" required />
			</label>
		</p>
		<p>
			<label for="content">Content:</label><br>
			<textarea id="content" name="field[page_content]" rows="40" cols="80"><?php
				if ($doesFileExist) {
					echo htmlspecialchars(file_get_contents($pageFile));
				}
			?></textarea>
		</p>
		<p>
			<label for="public">
				Publish:
				<input type="checkbox" id="public" name="field[page_public]" value="Yes"
					<?php echo $data['public'] ? "checked" : ""; ?> />
			</label>
		</p>
		<p>
			<input type="submit" id="upload" name="field[upload]" value="Save changes" required />
		</p>
	</form>
</div>
<script src="static/edit.js"></script>
<?php
}
echo "</div>\n";

echo htFooter();

