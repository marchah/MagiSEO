<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Constantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/DAO/ServerDAO.class.php';
require_once PATH_ROOT_WEBSITE . 'PHP/DAO/VMDAO.class.php';
require_once PATH_ROOT_WEBSITE . 'PHP/DAO/ReportDAO.class.php';
require_once PATH_ROOT_WEBSITE . 'PHP/Tools.php';
require_once PATH_ROOT_WEBSITE . 'PHP/SSHConstances.php';
require_once PATH_ROOT_WEBSITE . 'PHP/ErrorConstantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/Object/Cache.class.php';
require_once 'Crypt/RSA.php';
require_once 'Net/SSH2.php';
require_once 'Net/SFTP.php';

header("Content-Type: text/plain");

function cpAndPutConstantesInstallScript($idVM, $URLSite) {
    $folder = opendir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO . '/ScriptAlgo/');
    while ($file = readdir($folder)) {
        if ($file != "." && $file != "..")
            if (!copy(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO . '/ScriptAlgo/' . $file, PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD . '/' . $file)) {
                Cache::write(PATH_CACHE_FILE_LAUNCH_ALGO, LAUNCH_ALGO_STEP_ERROR);
                ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "cpAndPutConstantesInstallSpript() Error", ERROR_COPY_FILE . " $file", REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
                exit(ERROR_COPY_FILE);
            }
    }
    closedir($folder);
    // Put Constantes in ConstantesVM.php
    $constantesFile = file_get_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD . '/ConstantesVM.php');
    $constantesFile = str_replace('$#VM_ID#$', $idVM, $constantesFile);
    $constantesFile = str_replace('$#ALGO_SITE_OPTI#$', $URLSite, $constantesFile);
    file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD . '/ConstantesVM.php', $constantesFile);
}

function compressFolder() {
    // replace by shell cmd when i will be in linux server
    try {
        $a = new PharData(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD_ARCHIVE);
        $a->buildFromDirectory(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD);
        $a->compress(Phar::GZ);
    }
    catch (Exception $e) {
        Cache::write(PATH_CACHE_FILE_LAUNCH_ALGO, LAUNCH_ALGO_STEP_ERROR);
        cleanInstallFolder();
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "compressFolder() Error", $e, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_COMPRESS_FILE);
    }
}

function cleanInstallFolder() {
    $folder = opendir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD);
    while ($file = readdir($folder)) {
        if ($file != "." && $file != "..")
            unlink(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD . '/' . $file);
    }
    closedir($folder);
}

function launchAlgoOnVM() {
    $idVM = (isset($_POST["idVM"])) ? $_POST["idVM"] : "";
    $URLSite = (isset($_POST["URLSite"])) ? $_POST["URLSite"] : "";
   
    Cache::write(PATH_CACHE_FILE_LAUNCH_ALGO, LAUNCH_ALGO_STEP_INIT);
    if (empty($idVM) || empty($URLSite)) {
        Cache::write(PATH_CACHE_FILE_LAUNCH_ALGO, LAUNCH_ALGO_STEP_ERROR);
        exit(ERROR_ALGO_REQUIREMENT);
    } 
    if (!($VM = VMDAO::getVMByIdVM($idVM))) {
        Cache::write(PATH_CACHE_FILE_LAUNCH_ALGO, LAUNCH_ALGO_STEP_ERROR);
        exit(ERROR_VM_UNKNOW);
    }
    if ($VM->getState() != VM_STATE_DONE) {
        Cache::write(PATH_CACHE_FILE_LAUNCH_ALGO, LAUNCH_ALGO_STEP_ERROR);
        exit(ERROR_VM_NOT_READY);
    }
    cleanInstallFolder();
    if (!file_exists(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD))
        mkdir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_ALGO_TO_UPLOAD);
    Cache::write(PATH_CACHE_FILE_INSTALL, LAUNCH_ALGO_STEP_COMPRESSING_SCRIPTS);
    cpAndPutConstantesInstallScript($idVM, $URLSite); // put Constantes in Constantes.php
    compressFolder();
    Cache::write(PATH_CACHE_FILE_INSTALL, LAUNCH_ALGO_STEP_UPLOADING_SCRIPTS);
    $ssh = new Net_SSH2($VM->getIp());
    if (!$ssh->login($VM->getUsername(), $VM->getPassword())) {
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
        cleanInstallFolder();
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    cleanInstallFolder();
    Cache::write(PATH_CACHE_FILE_LAUNCH_ALGO, LAUNCH_ALGO_STEP_DONE);
}

if (isset($_POST["nameRequest"])) {
    if (!Tools::IsAuth()) {
        ReportDAO::insertReport(new Report(0, 0, "", "Security Warning", "call to ". $_POST["nameRequest"] ."() without be auth", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_REQUIRE_AUTH);
    }
    Cache::clean(PATH_CACHE_FILE_LAUNCH_ALGO);
    if ($_POST["nameRequest"] == "launchAlgoOnVM")
	launchAlgoOnVM();
}
?>

