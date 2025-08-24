<?php

require "common.php";

if (isset($_POST['field']) && count($_POST['field']) > 0 && $_POST['field']['login'] != "") {
	$res = dbExec("SELECT * FROM `users` WHERE `name` = :name", ['name' => $_POST['field']['user_name']]);
	if ($res == false) exitWithError(403, "Not valid");

	// get the data from the query
	$data = $res->fetch();
	$pass = $data['password'];
	$user = trim($data['name']);
	$id = (int)$data['id'];
	if (password_verify($_POST['field']['user_pass'], $pass)) {
		// cookie based login
		$ten_years = 315360000;
		setcookie('login[name]', $user, time() + $ten_years, '/');
		setcookie('login[user_id]', $id, time() + $ten_years, '/');

		// set the session too i guess
		$_SESSION['login']['user_id'] = $id;
		header("location: index.php");
		exit(0);
	}
	exitWithError(403, "Bad user, not authorized");
}

echo htHeader("Login");

?>
<div class="form-div">
	<form action="" method="post" id="login-form">
		<p>
			<label for="username">
				Username: <input type="text" id="name" name="field[user_name]" required />
			</label>
		</p>
		<p>
			<label for="password">
				Password: <input type="password" id="password" name="field[user_pass]" required />
			</label>
		</p>
		<p>
			<label for="login">
				<input type="submit" id="login" name="field[login]" value="Login!" required />
			</label>
		</p>
	</form>
</div>
<?php

echo htFooter();
