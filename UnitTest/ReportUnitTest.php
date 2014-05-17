<?php

//require_once '../site/PHP/Object/Report.class.php';
//require_once '../site/PHP/DAO/DAO.php';
require_once '../site/PHP/DAO/ReportDAO.class.php';

echo '/************ CREATE REPORT OBJECT *****************/<br />';
$report = new Report(1, 2, "title", "description", REPORTING_TYPE_SLAVE_ERROR, date("d/m/y"));
echo $report->__toString() . "<br />";

echo '<br />/************ GET ALL REPORT OBJECTS *****************/<br />';
$listReport = ReportDAO::getAllReport();
foreach ($listReport as $reportTmp) {
    echo $reportTmp->__toString() . "<br />";
}

echo '<br />/************ INSERT REPORT OBJECT *****************/<br />';
$report = ReportDAO::insertReport($report);

echo '<br />/************ GET ALL REPORT OBJECTS *****************/<br />';
$listReport = ReportDAO::getAllReport();
foreach ($listReport as $report) {
    echo $report->__toString() . "<br />";
}

echo '<br />/************ DELETE REPORT OBJECT *****************/<br />';
ReportDAO::deleteReport($report->getId());

echo '<br />/************ GET ALL REPORT OBJECTS *****************/<br />';
$listReport = ReportDAO::getAllReport();
foreach ($listReport as $report) {
    echo $report->__toString() . "<br />";
}


echo '<br />/************ GET ERROR REPORT OBJECTS *****************/<br />';
$listReport = ReportDAO::getReportByType(REPORTING_TYPE_SLAVE_ERROR);
foreach ($listReport as $report) {
    echo $report->__toString() . "<br />";
}