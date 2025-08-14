<?php

require "common.php";

if (!isLoggedIn()) {
	header("location: login.php");
}

echo htHeader("Home");
echo "Hello!";
echo htFooter();