<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Constantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/DAO/ServerDAO.class.php';
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
}
 */
	
set_error_handler('errorHandler', E_USER_NOTICE);

function errorHandler($errno, $errstr, $errfile, $errline) {
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "PHPseclib internal error", "errno=". $errno .", ". "errstr=". $errstr .", ". "errfile:". $errfile .", ". "errline:". $errline, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
    cleanInstallFolder();
    exit(ERROR_SSH_SYSTEM);
}

/*
error_reporting(E_ALL);
ini_set('display_errors', 'On');
register_shutdown_function( "fatal_handler" );

function fatal_handler() {
  $errfile = "unknown file";
  $errstr  = "shutdown";
  $errno   = E_CORE_ERROR;
  $errline = 0;

  $error = error_get_last();

  if( $error !== NULL) {
    $errno   = $error["type"];
    $errfile = $error["file"];
    $errline = $error["line"];
    $errstr  = $error["message"];


  }
  
   echo $errno ."\n" . $errline . "\n" . $errstr . "\n";
}*/

function connectionServerWithKey() {
    
    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";
    $keySSHPath = (isset($_POST["keySSHPath"])) ? $_POST["keySSHPath"] : "";

    if (empty($ipServerSSH) || empty($login) || empty($keySSHPath)) {
        $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_ERROR;
        exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
    }
    
    $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_INIT;
    $ssh = new Net_SSH2($ipServerSSH, SSH_PORT);
    $key = new Crypt_RSA();
    $tmp = file_get_contents($keySSHPath);
    $key->loadKey($tmp);
    if (!$ssh->login($login, $key)) {
        $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_ERROR;
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    echo $ssh->exec('pwd');
    echo $ssh->exec('ls -la');
}

function generateKey($ipServerSSH) {
    // replace by DSA when i will be in linux server
    $rsa = new Crypt_RSA();
    $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
    extract($rsa->createKey());
    
    // save private key
    if (!file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_PRIVATE_KEY_SSH . $ipServerSSH, $privatekey)) {
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "SSH Create File Private Key Error", ERROR_SSH_SAVE_KEY_FAILED, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_SAVE_KEY_FAILED);
    }
    
    // save public key in the folder which be send in the server slave
    if (!file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_PUBLIC_KEY_SSH, $publickey)) {
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "SSH Create File Public Key Error", ERROR_SSH_SAVE_KEY_FAILED, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_SAVE_KEY_FAILED);
    }
}

function cpAndPutConstantesInstallScript($ipServerSSH, $login) {
    $folder = opendir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE . '/ScriptServer/');
    while ($file = readdir($folder)) {
        if ($file != "." && $file != "..")
            if (!copy(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE . '/ScriptServer/' . $file, PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/' . $file)) {
                Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
                ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "cpAndPutConstantesInstallSpript() Error", ERROR_COPY_FILE . " $file", REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
                exit(ERROR_COPY_FILE);
            }
    }
    closedir($folder);
    // Put Constantes in Constantes.php
    $constantesFile = file_get_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/Constantes.php');
    $constantesFile = str_replace("$#IP_ADDRESS#$", $ipServerSSH, $constantesFile);
    $constantesFile = str_replace("$#LOGIN#$", $login, $constantesFile);
    file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/Constantes.php', $constantesFile);
    
    // Becasue I'm developing with Windows
    $str_old = file_get_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/installSoftware.sh');
    for ($i = 0; $i != strlen($str_old); $i++) {
        if (ord($str_old[$i]) == 13)
            $str_old[$i] = " ";
    }
    file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/installSoftware.sh', $str_old);
    
    $str_old = file_get_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/desinstallServerSlave.sh');
    for ($i = 0; $i != strlen($str_old); $i++) {
        if (ord($str_old[$i]) == 13)
            $str_old[$i] = " ";
    }
    file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/desinstallServerSlave.sh', $str_old);
    
    $str_old = file_get_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/updateServerSlave.sh');
    for ($i = 0; $i != strlen($str_old); $i++) {
        if (ord($str_old[$i]) == 13)
            $str_old[$i] = " ";
    }
    file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/updateServerSlave.sh', $str_old);
}

function compressFolder() {
    // replace by shell cmd when i will be in linux server
    try {
        $a = new PharData(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD_ARCHIVE);
        $a->buildFromDirectory(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD);
        $a->compress(Phar::GZ);
    }
    catch (Exception $e) {
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
        cleanInstallFolder();
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "compressFolder() Error", $e, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_COMPRESS_FILE);
    }
}

function uploadInstallScripts($ipServerSSH, $login, $password, $pathCacheFile, $errorMSG) {
    $sftp = new Net_SFTP($ipServerSSH);
    if (!$sftp->login($login, $password)) {
        Cache::write(PATH_ROOT_WEBSITE . $pathCacheFile, $errorMSG);
        cleanInstallFolder();
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "uploadInstallScripts() Error", ERROR_UPLOAD_SCRIPTS, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    $sftp->put('ScriptServer.tar', PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD_ARCHIVE, NET_SFTP_LOCAL_FILE);
}

function cleanInstallFolder() {
    $folder = opendir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD);
    while ($file = readdir($folder)) {
        if ($file != "." && $file != "..")
            unlink(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/' . $file);
    }
    closedir($folder);
}

function decompressScriptArchive($ssh) {
    $ssh->exec("rm -rf ScriptServer");
    $ssh->exec("mkdir ScriptServer");
    $ssh->exec("tar -xf ScriptServer.tar -C ScriptServer/");
    $ssh->exec("rm -f ScriptServer.tar");
    $ssh->exec("chmod +x ScriptServer/installSoftware.sh");
    $ssh->exec("chmod +x ScriptServer/desinstallServerSlave.sh");
    $ssh->exec("chmod +x ScriptServer/updateServerSlave.sh");
}

function installServerSlave() {

    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";
    $password = (isset($_POST["password"])) ? $_POST["password"] : "";
    Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_INIT);
    if (empty($ipServerSSH) || empty($login) || empty($password)) {
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
    }

    if (ServerDAO::isServerExist($ipServerSSH)) {
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_SERVER_ALREADY_CONFIGURATED);
    }
    set_time_limit(600);

    if (!file_exists(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD))
        mkdir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD);
    Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_GENERATE_KEY);
    generateKey($ipServerSSH);
    Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_COMPRESSING_SCRIPTS);
    cpAndPutConstantesInstallScript($ipServerSSH, $login); // put Constantes in Constantes.php
    compressFolder();
    Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_UPLOADING_SCRIPTS);
    uploadInstallScripts($ipServerSSH, $login, $password, PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
    cleanInstallFolder();
    $ssh = new Net_SSH2($ipServerSSH);
    if (!$ssh->login($login, $password)) {
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    decompressScriptArchive($ssh);
    Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_INSTALLING_SOFTWARES);
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    $ssh->write("./ScriptServer/installSoftware.sh\n");
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_SECURING_SSH);
    $ssh->write("php ./ScriptServer/securiseSSHServerSlave.php\n");
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_GETTING_SERVER_INFOS);
    $idServer = ServerDAO::insertServer($ipServerSSH, $login, $password, PATH_ROOT_WEBSITE . '/' . PATH_MASTER_PRIVATE_KEY_SSH . $ipServerSSH);
    $ssh->write("php ./ScriptServer/infoServerSlave.php\n");
    $tab_ret = explode(MSG_DELIMITER, $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX));
    if (count($tab_ret) == 3) {
       $infoServer = explode('/', $tab_ret[1]);
       ServerDAO::insertServerInfo($idServer, $infoServer[0], $infoServer[1], $infoServer[2], $infoServer[3], $infoServer[4], $infoServer[5]);
    }
    Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_DONE);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Install Server Slave $ipServerSSH", "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}

function desinstallServerSlave() {
    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";
    
    Cache::write(PATH_CACHE_FILE_DESINSTALL, DESINSTALL_SERVER_STEP_INIT);
    if (empty($ipServerSSH) || empty($login)) {
        Cache::write(PATH_CACHE_FILE_DESINSTALL, DESINSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
    }
    if (!ServerDAO::isServerExist($ipServerSSH)) {
        Cache::write(PATH_CACHE_FILE_DESINSTALL, DESINSTALL_SERVER_STEP_ERROR);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Desinstall Server Slave $ipServerSSH", "This server hasn't been configurated before.", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_SERVER_NOT_CONFIGURATED);
    }
    if (!($keySSHPath = ServerDAO::getKeySSHPathServerByIP($ipServerSSH))) {
            Cache::write(PATH_CACHE_FILE_DESINSTALL, DESINSTALL_SERVER_STEP_ERROR);
            exit(ERROR_SSH_KEY_PATH_NOT_FOUND);
    }
    set_time_limit(600);
    $ssh = new Net_SSH2($ipServerSSH, SSH_PORT);
    $key = new Crypt_RSA();
    $key->loadKey(file_get_contents($keySSHPath));
    if (!$ssh->login($login, $key)) {
        Cache::write(PATH_CACHE_FILE_DESINSTALL, DESINSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    Cache::write(PATH_CACHE_FILE_DESINSTALL, DESINSTALL_SERVER_STEP_DESECURING_SSH);
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    $ssh->write("./ScriptServer/desinstallServerSlave.sh\n");
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    Cache::write(PATH_CACHE_FILE_DESINSTALL, DESINSTALL_SERVER_STEP_DONE);
    ServerDAO::deleteServer($ipServerSSH);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Desinstall Server Slave $ipServerSSH", "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}

function updateServerSlave() {
    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";
   
    Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_INIT);
    if (empty($ipServerSSH) || empty($login)) {
        Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
    }
    
    if (!ServerDAO::isServerExist($ipServerSSH)) {
        Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_ERROR);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Update Server Slave $ipServerSSH", "This server hasn't been configurated before.", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_SERVER_NOT_CONFIGURATED);
    }
    if (!($keySSHPath = ServerDAO::getKeySSHPathServerByIP($ipServerSSH))) {
        Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_ERROR);
        exit(ERROR_SSH_KEY_PATH_NOT_FOUND);
    }
    set_time_limit(800);
    $key = new Crypt_RSA();
    $key->loadKey(file_get_contents($keySSHPath));
    Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_COMPRESSING_SCRIPTS);
    cpAndPutConstantesInstallScript($ipServerSSH, $login);
    compressFolder();
    Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_UPLOADING_SCRIPTS);
    uploadInstallScripts($ipServerSSH, $login, $key, PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_ERROR);
    cleanInstallFolder();
    $ssh = new Net_SSH2($ipServerSSH, SSH_PORT);
    if (!$ssh->login($login, $key)) {
        Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    decompressScriptArchive($ssh);
    Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_UPDATING_SOFTWARE);
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    $ssh->write("./ScriptServer/updateServerSlave.sh\n");
    $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
    Cache::write(PATH_CACHE_FILE_UPDATE, UPDATE_SERVER_STEP_DONE);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Update Server Slave $ipServerSSH", "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}

if (isset($_POST["nameRequest"])) {
    if (!Tools::IsAuth()) {
        ReportDAO::insertReport(new Report(0, 0, "", "Security Warning", "call to ". $_POST["nameRequest"] ."() without be auth", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_REQUIRE_AUTH);
    }
    Cache::clean(PATH_CACHE_FILE_INSTALL);
    Cache::clean(PATH_CACHE_FILE_DESINSTALL);
    Cache::clean(PATH_CACHE_FILE_UPDATE);
    if ($_POST["nameRequest"] == "installServer")
	installServerSlave();
    else if ($_POST["nameRequest"] == "desinstallServer")
	desinstallServerSlave();
    else if ($_POST["nameRequest"] == "updateServer")
	updateServerSlave();
}
?>