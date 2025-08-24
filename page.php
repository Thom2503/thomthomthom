<?php

require "common.php";

$data = [];
$data['id'] = 0;
$data['pagename'] = "";
$data['madeby'] = "";
$data['ts'] = "";
$data['public'] = false;


if (isset($_GET['title']) && trim($_GET['title']) != "") {
	$res = dbExec("SELECT * FROM `pages` WHERE `pagename` = :title", ['title' => $_GET['title']]);
	if ($res != false) {
		$data = $res->fetch();
	}
}

$showPageContent = false;
$pageName = $data['ts']."_".$data['pagename'].".txt";
$doesFileExist = file_exists($GLOBALS['page_dir']."/".$pageName);
$editPage = isset($_GET['edit']) && $_GET['edit'] == true && isLoggedIn();

if (isset($_POST['field']) && count($_POST['field']) > 0 && $_POST['field']['upload'] != "") {
	// data to be saved
	$data['pagename'] = $_POST['field']['page_title'];
	$data['ts'] = time();
	$data['public'] = (int)(isset($_POST['field']['page_public']) && $_POST['field']['page_public'] == "Yes");
	$data['madeby'] = $_COOKIE['login']['user_id'];

	if ($doesFileExist) {
		$file = rename($GLOBALS['page_dir']."/".$pageName, $GLOBALS['archive_dir']."/".$data['ts']."_".$data['pagename'].".txt");
		if (!$file) {
			exitWithError(500, "Error archiving the page");
		}
	}
	// to write it to a file
	$file = file_put_contents($GLOBALS['page_dir']."/".$data['ts']."_".$data['pagename'].".txt", $_POST['field']['page_content']);
	if (!$file) {
		exitWithError(500, "Error with saving page.");
	}
	$query = "INSERT INTO pages (id, pagename, madeby, ts, public)".
	         " VALUES (:id, :pagename, :madeby, :ts, :public) ON CONFLICT(id) DO UPDATE SET".
		     "  pagename = excluded.pagename, madeby = excluded.madeby, ts = excluded.ts, public = excluded.public;";
	$res = dbExec(
		$query,
		['pagename' => $data['pagename'],
		 'madeby' => $data['madeby'],
		 'ts' => $data['ts'],
		 'public' => $data['public'],
		 'id' => $data['id']]
	);
	header("location: index.php");
}

if (!isLoggedIn() && $data['public'] == false) {
	header("location: login.php");
} else if (isset($data) && $data['public'] == true) {
	$showPageContent = true;
}

echo htHeader("Edit");

echo "<div>\n";
echo $doesFileExist ? "<h2>".$data['pagename']."</h2>" : "<h2>Page edit</h2>\n";
if ($showPageContent == true && $doesFileExist == true && $editPage == false) {
	if (isLoggedIn()) {
		$_REQUEST['edit_page'] = true;
		echo "<a href='page.php?title=".$data['pagename']."&edit=1'>Edit page</a>";
	}
	echo "<pre>";
	echo include $GLOBALS['page_dir']."/".$pageName;
	echo "</pre>\n";
} else {
?>
<div class="form-div">
	<dialog>
		<button autofocus>Close</button>
		<h3>Page edit help</h3>
		<p>
			Pages are edited as pure .txt files, but there is one exception.</br>
			pages can have some html and that is the <code><img></code> tag and the <code><a></code> tag.
		</p>
	</dialog>
	<button id="help-button">Help</button>
	<form action="" method="post" id="edit-page-form">
		<p>
			<label for="title">
				Page title:
				<input type="text" id="title" name="field[page_title]" value="<?php echo $data['pagename']; ?>" required />
			</label>
		</p>
		<p>
			<label for="content">content:</label><br>
			<textarea id="content" name="field[page_content]" rows="40" cols="80"><?php if ($doesFileExist == true) {echo include $GLOBALS['page_dir']."/".$pageName;} ?></textarea>
		</p>
		<p>
			<label for="public">
				Publish:
				<input type="checkbox" id="public" name="field[page_public]" value="Yes" />
			</label>
		</p>
		<p>
			<label for="upload">
				<input type="submit" id="upload" name="field[upload]" value="Save changes" required />
			</label>
		</p>
	</form>
</div>
<script src="static/edit.js"></script>
<?php
}
echo "</div>\n";

echo htFooter();
