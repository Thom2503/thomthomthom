<?php

require "common.php";

if (!isLoggedIn()) {
	header("location: login.php");
}

echo htHeader("Edit");

echo "<div>\n";
echo "<h2>Page edit</h2>\n";
?>
<div class="form-div">
	<dialog>
		<button autofocus>close</button>
		<h3>Page edit help</h3>
		<p>
			Pages are edited as pure .txt files, but there is one exception.</br>
			pages can have some html and that is the <img> tag and the <a> tag.
		</p>
	</dialog>
	<button id="help-button">Help</button>
	<form action="" method="post" id="edit-page-form">
		<p>
			<label for="title">
				Page title: <input type="text" id="title" name="field[page_title]" required />
			</label>
		</p>
		<p>
			<label for="content">content:</label><br>
			<textarea id="content" name="field[page_content]" rows="40" cols="80"></textarea>
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
echo "</div>\n";

echo htFooter();
