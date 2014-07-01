<?php
//@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ReportDAO.class.php';
header("Content-Type: text/plain");

function	getAll() {
    echo ReportDAO::getNbServerErrorWarning();
}

function	getLog() {
    $listReport = ReportDAO::getReportByType(REPORTING_TYPE_LOG);
    
    foreach ($listReport as $report) {
        echo '<li class="media">'
                    .'<div class="media-object pull-left">'
                            .'<i class="ico-pencil bgcolor-success"></i>'
                    .'</div>'
                    .'<div class="media-body">'
                            .'<p class="media-heading">'. $report->getTitle() .'</p>'
                            .'<p class="media-text"><span class="text-primary semibold">'. $report->getUserLogin() .'</span> : '. $report->getDescription() .'</p>'
                            .'<p class="media-meta">'. $report->getDate() .'</p>'
                    .'</div>'
                .'</li>';
    }
    
}


if (isset($_POST["nameRequest"])) {
    if ($_POST["nameRequest"] == "getAll")
        getAll();
    else if ($_POST["nameRequest"] == "getLog")
        getLog();
}
?>