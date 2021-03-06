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

/*
require_once PATH_ROOT_WEBSITE . 'PHPseclib/Math/BigInteger.php';
require_once PATH_ROOT_WEBSITE . 'PHPseclib/Net/SSH2.php';
require_once PATH_ROOT_WEBSITE . 'PHPseclib/Crypt/RSA.php';
require_once PATH_ROOT_WEBSITE . 'PHPseclib/Net/SFTP.php';
if (!set_include_path(get_include_path() . PATH_SEPARATOR . PATH_ROOT_WEBSITE . 'phpseclib')) {
    ReportDAO::insertReport(new Report(0, !Tools::IsAuth() ? 0 : $_SESSION['user']->getId(), !Tools::IsAuth() ? "" : $_SESSION['user']->getLogin(), "Internal Error", "set_include_path() failed on serverAjax.php", REPORTING_TYPE_INTERNAL_ERROR, date("Y-m-d H:i:s")));
    exit(ERROR_SYSTEM);
}*/
	
set_error_handler('errorHandler', E_USER_NOTICE);

function errorHandler($errno, $errstr, $errfile, $errline) {
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "PHPseclib internal error", "errno=". $errno .", ". "errstr=". $errstr .", ". "errfile:". $errfile .", ". "errline:". $errline, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
    exit(ERROR_SSH_SYSTEM);
}

function connectionServerWithKey($Server, $PATH_CACHE, $NAME_STEP, $NAME_STEP_ERROR) {
    $ssh = new Net_SSH2($Server->getIPV4(), SSH_PORT);
    $key = new Crypt_RSA();
    $tmp = file_get_contents($Server->getKeySSHPath());
    $key->loadKey($tmp);
    if (!$ssh->login($Server->getUsername(), $key)) {
        Cache::write($PATH_CACHE, $NAME_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    return $ssh;
}

function installVM() {
    $idServer = (isset($_POST["idServer"])) ? $_POST["idServer"] : "";
    $name = (isset($_POST["name"])) ? $_POST["name"] : "";
    $RAM = (isset($_POST["RAM"])) ? $_POST["RAM"] : "";
    $HDD = (isset($_POST["HDD"])) ? $_POST["HDD"] : "";
    Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_INIT);
    if (empty($idServer) || empty($name) || empty($RAM) || empty($HDD)) {
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_MISSING_REQUIREMENT);
    }
    
    if (intval($RAM) < VM_MIN_SIZE_RAM || intval($HDD) < VM_MIN_SIZE_HDD) {
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_INVALID_REQUIREMENT);
    }
    
    if (($Server = ServerDAO::getServerById($idServer)) == null) {
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_INVALID_REQUIREMENT);
    }
    
    if ($Server->getFlashCurrentSize() + intval($RAM) >= $Server->getFlashMaxSize()) {
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_RAM_SIZE);
    }
    
    /*if ($Server->getDiskCurrentSize() + intval($HDD) >= $Server->getDiskMaxSize()) {
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_HDD_SIZE);
    }*/
    
    if (VMDAO::isVMNameExistInSever($name, $idServer)) {
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_INVALID_NAME);
    }
    
    if (VMDAO::isVMInProcess()) {
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_PROCESSING);
    }
    
    Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_CONNECTION_SERVER);
    $ssh = connectionServerWithKey($Server, PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP, INSTALL_VM_STEP_ERROR);
    Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_INSTALLING);
    VMDAO::deleteVMProcessing();
    VMDAO::insertVMProcessing($Server->getId(), $Server->getIPV4(), date("Y-m-d H:i:s"), 0);
    
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    $ssh->write("su -c \"php ScriptServer/installVMNew.php ".$name." ".$RAM." ".$HDD."\"\n");
    set_time_limit(2000);
    
    while (!VMDAO::isVMProcessingDone()) {
        sleep(10);
    }
    Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_SAVING_BDD);
    $idVM = VMDAO::getIdVMInProcess();
    VMDAO::updateVMToDone($idVM, $Server->getId(), $name, 'marcha', 'totoauzoo', $RAM, $HDD);
    VMDAO::deleteVMProcessing();
    Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_DONE);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Install VM On Server ".$Server->getIPV4(), "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}

function desinstallVM() {
    $ipServer = (isset($_POST["ipServer"])) ? $_POST["ipServer"] : "";
    $ipVM = (isset($_POST["ipVM"])) ? $_POST["ipVM"] : "";
    
    Cache::write(PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP_INIT);
    if (empty($ipServer) || empty($ipVM)) {
        Cache::write(PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_DESINSTALL_MISSING_REQUIREMENT);
    }

    if (($Server = ServerDAO::getServerByIP($ipServer)) == null) {
        Cache::write(PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_INVALID_REQUIREMENT);
    }
    
    if (($VM = VMDAO::getVMByIPServerAndIPVM($ipServer, $ipVM)) === false) {
        Cache::write(PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP_ERROR);
        exit(ERROR_VM_UNKNOW);
    }
    
    Cache::write(PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP_CONNECTION_SERVER);
    $ssh = connectionServerWithKey($Server, PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP, DESINSTALL_VM_STEP_ERROR);
    Cache::write(PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP_DESINSTALLING);
    set_time_limit(300);
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    $ssh->write("su -c \"php ScriptServer/desinstallVM.php ".$VM->getName()."\"\n");
    Cache::write(PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP_DELETING_VM_BDD);
    VMDAO::deleteVM($VM->getId());
    Cache::write(PATH_CACHE_FILE_DESINSTALL_VM, DESINSTALL_VM_STEP_DONE);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Desinstall VM On Server ".$Server->getIPV4(), "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}

function updateVM() {
    $ipServer = (isset($_POST["ipServer"])) ? $_POST["ipServer"] : "";
    $ipVM = (isset($_POST["ipVM"])) ? $_POST["ipVM"] : "";
    
    Cache::write(PATH_CACHE_FILE_UPDATE_VM, UPDATE_VM_STEP_INIT);
    if (empty($ipServer) || empty($ipVM)) {
        Cache::write(PATH_CACHE_FILE_UPDATE_VM, UPDATE_VM_STEP_ERROR);
        exit(ERROR_VM_DESINSTALL_MISSING_REQUIREMENT);
    }

    if (($Server = ServerDAO::getServerByIP($ipServer)) == null) {
        Cache::write(PATH_CACHE_FILE_UPDATE_VM, UPDATE_VM_STEP_ERROR);
        exit(ERROR_VM_INVALID_REQUIREMENT);
    }
    
    if (($VM = VMDAO::getVMByIPServerAndIPVM($ipServer, $ipVM)) === false) {
        Cache::write(PATH_CACHE_FILE_UPDATE_VM, UPDATE_VM_STEP_ERROR);
        exit(ERROR_VM_UNKNOW);
    }
    Cache::write(PATH_CACHE_FILE_UPDATE_VM, UPDATE_VM_STEP_CONNECTION_VM);
    $ssh = connectionServerWithKey($Server, PATH_CACHE_FILE_UPDATE_VM, UPDATE_VM_STEP, UPDATE_VM_STEP_ERROR);
    set_time_limit(300);
    Cache::write(PATH_CACHE_FILE_UPDATE_VM, UPDATE_VM_STEP_UPDATING_SOFTWARE);
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    $ssh->write("su -c \"apt-get update -y\"\n");
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    Cache::write(PATH_CACHE_FILE_UPDATE_VM, UPDATE_VM_STEP_DONE);
    echo true;
}

if (isset($_POST["nameRequest"])) {
    if (!Tools::IsAuth()) {
        ReportDAO::insertReport(new Report(0, 0, "", "Security Warning", "call to ". $_POST["nameRequest"] ."() without be auth", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_REQUIRE_AUTH);
    }
    Cache::clean(PATH_CACHE_FILE_INSTALL_VM);
    Cache::clean(PATH_CACHE_FILE_DESINSTALL_VM);
    Cache::clean(PATH_CACHE_FILE_UPDATE_VM);
    if ($_POST["nameRequest"] == "installVM")
	installVM();
    else if ($_POST["nameRequest"] == "desinstallVM")
	desinstallVM();
    else if ($_POST["nameRequest"] == "updateVM")
	updateVM();
}
?>