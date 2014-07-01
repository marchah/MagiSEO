<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ServerDAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ReportDAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Tools.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/SSHConstances.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/ErrorConstantes.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHPseclib/Net/SSH2.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHPseclib/Crypt/RSA.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHPseclib/Net/SFTP.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/Cache.class.php';

header("Content-Type: text/plain");

if (!set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/phpseclib')) {
    ReportDAO::insertReport(new Report(0, !Tools::IsAuth() ? 0 : $_SESSION['user']->getId(), !Tools::IsAuth() ? "" : $_SESSION['user']->getLogin(), "Internal Error", "set_include_path() failed on serverAjax.php", REPORTING_TYPE_INTERNAL_ERROR, date("Y-m-d H:i:s")));
    exit(ERROR_SYSTEM);
}
	
set_error_handler('errorHandler', E_USER_NOTICE);

function errorHandler($errno, $errstr, $errfile, $errline) {
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "PHPseclib internal error", "errno=". $errno .", ". "errstr=". $errstr .", ". "errfile:". $errfile .", ". "errline:". $errline, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
    cleanInstallFolder();
    exit(ERROR_SSH_SYSTEM);
}
/*
function installServer() {

    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";
    $password = (isset($_POST["password"])) ? $_POST["password"] : "";

    if (empty($ipServerSSH) || empty($login) || empty($password)) {
        $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_ERROR;
        exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
    }

    if (ServerDAO::isServerExist($ipServerSSH)) {
        $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_ERROR;
        exit(ERROR_SSH_SERVER_ALREADY_CONFIGURATED);
    }
    
    $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_INIT;

    $rsa = new Crypt_RSA();
    $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
    extract($rsa->createKey());
    
    $ssh = new Net_SSH2($ipServerSSH);
    if (!$ssh->login($login, $password)) {
        $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_ERROR;
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    
    if (!file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/' . PATH_MASTER_PRIVATE_KEY_SSH . $ipServerSSH, $privatekey)) {
        $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_ERROR;
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "SSH Create File Private Key Error", ERROR_SSH_DOWNLOAD_KEY_FAILED, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_DOWNLOAD_KEY_FAILED);
    }
    $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_SECURING_SSH;
    $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    $ssh->write("su -c \""
                    ."cp ".PATH_FILE_CONFIG_SSH." ".PATH_FILE_CONFIG_SSH_SAVE."; "
                . "\"; "
                ."rm -rf ".PATH_DIR_KEY_SSH."; "
                ."mkdir -p ".PATH_DIR_KEY_SSH."; "
                ."chmod 0700 ".PATH_DIR_KEY_SSH."; "
                ."echo '$publickey' > " . PATH_FILE_PUBLIC_KEY_SSH . "; "
                ."chmod 0600 ".PATH_FILE_PUBLIC_KEY_SSH."; "
                ."su -c \""
                    ."/usr/sbin/addgroup ".GROUP_SSH_ALLOW."; "
                    ."/usr/sbin/usermod -a -G ".GROUP_SSH_ALLOW." $login; "
                    ."sed -i 's/^Port [0-9][0-9]*$/Port ".SSH_PORT."/' ".PATH_FILE_CONFIG_SSH."; "
                    //."sed -i 's/HostKey /etc/ssh/ssh_host_dsa_key / / ;"
                    ."sed -i 's/^[#]*Protocol [0-9][0-9]*$/Protocol ".SSH_PROTOCOL."/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*PermitRootLogin [a-zA-Z-][a-zA-Z-]*$/PermitRootLogin no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*LoginGraceTime [0-9][0-9]*$/LoginGraceTime ".LOGIN_GRACE_TIME."/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*MaxAuthTries [0-9][0-9]*$/MaxAuthTries ".MAX_AUTH_TRIES."/' ".PATH_FILE_CONFIG_SSH."; "
                   // ."sed -i 's/^[#]*RSAAuthentication [a-zA-Z][a-zA-Z]*$/RSAAuthentication no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*UsePAM [a-zA-Z][a-zA-Z]*$/UsePAM no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*KerberosAuthentication [a-zA-Z][a-zA-Z]*$/KerberosAuthentication no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*GSSAPIAuthentication [a-zA-Z][a-zA-Z]*$/GSSAPIAuthentication no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*PasswordAuthentication [a-zA-Z][a-zA-Z]*$/PasswordAuthentication no/' ".PATH_FILE_CONFIG_SSH."; " // DOESN'T WORK ON UBUNTU
                    ."sed -i 's/^[#]*MaxStartups [0-9:][0-9:]*$/MaxStartups ".MAX_STARTUPS."/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*PubkeyAuthentication [a-zA-Z][a-zA-Z]*$/PubkeyAuthentication yes/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*AuthorizedKeysFile.*$/AuthorizedKeysFile ".str_replace("/", "\/", PATH_FILE_AUTHORIZED_KEYS)."/' ".PATH_FILE_CONFIG_SSH."; "
                    ." echo AllowGroups ".GROUP_SSH_ALLOW." >> ".PATH_FILE_CONFIG_SSH."; "
                    ."/etc/init.d/ssh restart"
                . "\"\n ");
    $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    //installSoftware($ssh);
    $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_DONE;
    ServerDAO::insertServer($ipServerSSH, $login, $password, $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/' . PATH_MASTER_PRIVATE_KEY_SSH . $ipServerSSH);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Install Server Slave $ipServerSSH", "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}*/
/*
function desinstallServer() {
    
    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";
    $keySSHPath = (isset($_POST["keySSHPath"])) ? $_POST["keySSHPath"] : "";

    if (empty($ipServerSSH) || empty($login) || empty($keySSHPath)) {
        $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_ERROR;
        exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
    }
    
    if (!ServerDAO::isServerExist($ipServerSSH)) {
        $_SESSION[DESINSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_ERROR;
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Desinstall Server Slave $ipServerSSH", "This server hasn't been configurated before.", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_SERVER_NOT_CONFIGURATED);
    }
    
    $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_INIT;
    $ssh = new Net_SSH2($ipServerSSH, SSH_PORT);
    $key = new Crypt_RSA();
    $key->loadKey(file_get_contents($keySSHPath));
    if (!$ssh->login($login, $key)) {
        $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_ERROR;
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_DESECURING_SSH;
    $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    $ssh->write("su -c \"/usr/sbin/delgroup ".GROUP_SSH_ALLOW."\"; "
            ."rm -rf ".PATH_DIR_KEY_SSH."; "
            ."su -c \"mv ".PATH_FILE_CONFIG_SSH_SAVE." ".PATH_FILE_CONFIG_SSH."\"; "
            ."su -c \"/etc/init.d/ssh restart\"; "
            . "\n");
    $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    //removeSoftware($ssh);
    $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_DONE;
    ServerDAO::deleteServer($ipServerSSH);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Desinstall Server Slave $ipServerSSH", "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}*/
/*
function installSoftware($ssh) {
    $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_INSTALLING_SOFTWARES;
    $ssh->write("su -c \""
                    ."apt-get install -y aptitude; "
                    ."aptitude install -y apache2; " //php5 mysql-server php5-mysql libapache2-mod-php5; " //MysqlPasswordPB pas sur que j'en ai besoins demandé à julien
                    ."/etc/init.d/apache2 restart; "
                    ."aptitude install -y virtualbox; "
                . "\"\n");
}

function removeSoftware($ssh) {
    $_SESSION[DESINSTALL_SERVER_STEP] = DESINSTALL_SERVER_STEP_DESINSTALLING_SOFTWARE;
    $ssh->write("su -c \""
                    ."aptitude remove -y virtualbox; "
                    ."aptitude remove -y apache2; "//php5 mysql-server php5-mysql libapache2-mod-php5; "
                    ."aptitude autoremove"
                . "\"\n");
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
        Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_ERROR);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "SSH Create File Private Key Error", ERROR_SSH_SAVE_KEY_FAILED, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_SAVE_KEY_FAILED);
    }
    
    // save public key in the folder which be send in the server slave
    if (!file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_PUBLIC_KEY_SSH, $publickey)) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_ERROR);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "SSH Create File Public Key Error", ERROR_SSH_SAVE_KEY_FAILED, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_SAVE_KEY_FAILED);
    }
}

function cpAndPutConstantesInstallScript($ipServerSSH, $login) {
    $folder = opendir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE . '/ScriptServer/');
    while ($file = readdir($folder)) {
        if ($file != "." && $file != "..")
            if (!copy(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE . '/ScriptServer/' . $file, PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/' . $file)) {
                Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_ERROR);
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
}

function compressFolder() {
    // replace by shell cmd when i will be in linux server
    try {
        $a = new PharData(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD_ARCHIVE);
        $a->buildFromDirectory(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD);
        $a->compress(Phar::GZ);
    }
    catch (Exception $e) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_ERROR);
        cleanInstallFolder();
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "compressFolder() Error", $e, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
        exit(ERROR_COMPRESS_FILE);
    }
}

function uploadInstallScripts($ipServerSSH, $login, $password) {
    $sftp = new Net_SFTP($ipServerSSH);
    if (!$sftp->login($login, $password)) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_ERROR);
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

function installServerSlave() {
    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";
    $password = (isset($_POST["password"])) ? $_POST["password"] : "";
    Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_INIT);
    if (empty($ipServerSSH) || empty($login) || empty($password)) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
    }

    if (ServerDAO::isServerExist($ipServerSSH)) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_SERVER_ALREADY_CONFIGURATED);
    }
    set_time_limit(600);
    
    if (!file_exists(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD))
        mkdir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD);
    Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_GENERATE_KEY);
    generateKey($ipServerSSH);
    Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_COMPRESSING_SCRIPTS);
    cpAndPutConstantesInstallScript($ipServerSSH, $login); // put Constantes in Constantes.php
    compressFolder();
    Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_UPLOADING_SCRIPTS);
    uploadInstallScripts($ipServerSSH, $login, $password);
    cleanInstallFolder();
    $ssh = new Net_SSH2($ipServerSSH);
    if (!$ssh->login($login, $password)) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    $ssh->exec("mkdir ScriptServer");
    $ssh->exec("tar -xf ScriptServer.tar -C ScriptServer/");
    $ssh->exec("rm -f ScriptServer.tar");
    $ssh->exec("chmod +x ScriptServer/installSoftware.sh");
    $ssh->exec("chmod +x ScriptServer/desinstallServerSlave.sh");
    // $_SESSION[INSTALL_SERVER_STEP]
    // remove asking password for su
    Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_INSTALLING_SOFTWARES);
    $ret = $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    //echo $ret . "\n";
    $ssh->write("./ScriptServer/installSoftware.sh\n");
    Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_SECURING_SSH);
    $rett = $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    //echo "test0:" . $rett . "\n";
    $ssh->write("php ./ScriptServer/securiseSSHServerSlave.php\n");
    $ret = $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    //echo "test1:" . $ret . "\n";
    Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_GETTING_SERVER_INFOS);
    $idServer = ServerDAO::insertServer($ipServerSSH, $login, $password, PATH_ROOT_WEBSITE . '/' . PATH_MASTER_PRIVATE_KEY_SSH . $ipServerSSH);
    $ssh->write("php ./ScriptServer/infoServerSlave.php\n");
    $ret = $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    //$ret = $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    $tab_ret = explode(MSG_DELIMITER, $ret);
    if (count($tab_ret) == 3) {
       $infoServer = explode('/', $tab_ret[1]);
       ServerDAO::insertServerInfo($idServer, $infoServer[0], $infoServer[1], $infoServer[2], $infoServer[3], $infoServer[4], $infoServer[5]);
    }
    
    //echo "test2:" . $ret . "\n"; // check ret
    Cache::write(PATH_ROOT_WEBSITE . "/cache/install", INSTALL_SERVER_STEP_DONE);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Install Server Slave $ipServerSSH", "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}

function desinstallServerSlave() {
    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";
    //$keySSHPath = (isset($_POST["keySSHPath"])) ? $_POST["keySSHPath"] : "";
    
    Cache::write(PATH_ROOT_WEBSITE . "/cache/desinstall", DESINSTALL_SERVER_STEP_INIT);
    if (empty($ipServerSSH) || empty($login) /*|| empty($keySSHPath)*/) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/desinstall", DESINSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
    }
    
    if (!ServerDAO::isServerExist($ipServerSSH)) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/desinstall", DESINSTALL_SERVER_STEP_ERROR);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Desinstall Server Slave $ipServerSSH", "This server hasn't been configurated before.", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_SSH_SERVER_NOT_CONFIGURATED);
    }
    if (!($keySSHPath = ServerDAO::getKeySSHPathServerByIP($ipServerSSH))) {
            Cache::write(PATH_ROOT_WEBSITE . "/cache/desinstall", DESINSTALL_SERVER_STEP_ERROR);
            exit(ERROR_SSH_KEY_PATH_NOT_FOUND);
    }
    set_time_limit(600);
    $ssh = new Net_SSH2($ipServerSSH, SSH_PORT);
    $key = new Crypt_RSA();
    $key->loadKey(file_get_contents($keySSHPath));
    if (!$ssh->login($login, $key)) {
        Cache::write(PATH_ROOT_WEBSITE . "/cache/desinstall", DESINSTALL_SERVER_STEP_ERROR);
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    Cache::write(PATH_ROOT_WEBSITE . "/cache/desinstall", DESINSTALL_SERVER_STEP_DESECURING_SSH);
    $ret = $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    //echo $ret . "\n";
    $ssh->write("./ScriptServer/desinstallServerSlave.sh\n");
    $ret = $ssh->read('/.*@.*[$|#]/', NET_SSH2_READ_REGEX);
    //echo $ret . "\n";
    Cache::write(PATH_ROOT_WEBSITE . "/cache/desinstall", DESINSTALL_SERVER_STEP_DONE);
    ServerDAO::deleteServer($ipServerSSH);
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Desinstall Server Slave $ipServerSSH", "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
    echo true;
}

if (isset($_POST["nameRequest"])) {
    if (!Tools::IsAuth()) {
        ReportDAO::insertReport(new Report(0, 0, "", "Security Warning", "call to ". $_POST["nameRequest"] ."() without be auth", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_REQUIRE_AUTH);
    }
    Cache::clean(PATH_ROOT_WEBSITE . "/cache/install");
    Cache::clean(PATH_ROOT_WEBSITE . "/cache/desinstall");
    if ($_POST["nameRequest"] == "installServer")
	installServerSlave();
    else if ($_POST["nameRequest"] == "desinstallServer")
	desinstallServerSlave();
}
?>