<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ReportDAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Tools.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/ErrorConstantes.php';

function reportSolved() {
    if (!isset($_POST["idReport"])) {
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "reportSolved()", "Trying to solved report without give it ID.", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(false);
    }
    ReportDAO::deleteReport($_POST["idReport"]);
    echo true;
}


if (isset($_POST["nameRequest"])) {
    if (!Tools::IsAuth()) {
        ReportDAO::insertReport(new Report(0, 0, "", "Security Warning", "call to ". $_POST["nameRequest"] ."() without be auth", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_REQUIRE_AUTH);
    }
    if ($_POST["nameRequest"] == "reportSolved")
	reportSolved();
}
?>

