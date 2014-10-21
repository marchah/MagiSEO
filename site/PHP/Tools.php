<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once('Constantes.php');
require_once PATH_ROOT_WEBSITE . 'PHP/Object/Cache.class.php';
require_once PATH_ROOT_WEBSITE . 'PHP/DAO/ReportDAO.class.php';

class Tools {
    
    static function ReportingError($pathCacheFile, $cacheStepError, $titleError, $descriptionError, $typeError, $exitCodeError) {
        if ($pathCacheFile != null && $cacheStepError != null)
            Cache::write($pathCacheFile, $cacheStepError);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), $titleError, $descriptionError, $typeError, date("Y-m-d H:i:s")));
        exit($exitCodeError);
    }

    static function IsAuth() {
            return (isset($_SESSION['auth']) && $_SESSION['auth'] == true) ? true : false;
    }

}
?>