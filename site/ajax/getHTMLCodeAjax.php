<?php
session_start();
header("Content-Type: text/plain");

require_once('../PHP/getHTMLCode.php');

if (isset($_POST["nameRequest"])) {
	if ($_POST["nameRequest"] == "getHTMLUSerButtonAuth")
		echo getHTMLUSerButtonAuth();
}
?>