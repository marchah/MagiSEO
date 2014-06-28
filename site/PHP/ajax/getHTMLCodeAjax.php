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
        else if ($_POST["nameRequest"] == "getHTMLButtonsManageServer")
            echo getHTMLButtonsManageServer();
        else if ($_POST["nameRequest"] == "getHTMLButtonAddServer")
            echo getHTMLButtonAddServer();
        else if ($_POST["nameRequest"] == "getHTMLButtonsManageVM")
            echo getHTMLButtonsManageVM();
        else if ($_POST["nameRequest"] == "getHTMLButtonAddVM")
            echo getHTMLButtonAddVM();
        else if ($_POST["nameRequest"] == "getHTMLPanelNewVM")
            echo getHTMLPanelNewVM();
}
?>