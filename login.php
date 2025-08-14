<?php

require "common.php";

print_r($_POST);
if (isset($_POST['field']) && count($_POST['field']) > 0 && $_POST['field']['login'] != "") {
	$res = dbExec("SELECT * FROM `users` WHERE `name` = :name", ['name' => $_POST['field']['user_name']]);
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
