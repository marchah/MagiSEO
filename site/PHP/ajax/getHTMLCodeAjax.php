<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
session_start();
header("Content-Type: text/plain");

require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/getHTMLCode.php';

if (isset($_POST["nameRequest"])) {
	if ($_POST["nameRequest"] == "getHTMLUSerButtonAuth")
		echo getHTMLUSerButtonAuth();
        else if ($_POST["nameRequest"] == "getHTMLPanelNewServer")
            echo getHTMLPanelNewServer();
}
?>