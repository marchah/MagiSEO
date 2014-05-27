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

header("Content-Type: text/plain");

if (!set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/phpseclib')) {
    ReportDAO::insertReport(new Report(0, !Tools::IsAuth() ? 0 : $_SESSION['user']->getId(), !Tools::IsAuth() ? "" : $_SESSION['user']->getLogin(), "Internal Error", "set_include_path() failed on serverAjax.php", REPORTING_TYPE_INTERNAL_ERROR, date("Y-m-d H:i:s")));
    exit(ERROR_SYSTEM);
}
	
set_error_handler('errorHandler', E_USER_NOTICE);

function errorHandler($errno, $errstr, $errfile, $errline) {
    ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "PHPseclib internal error", "errno=". $errno .", ". "errstr=". $errstr .", ". "errfile:". $errfile .", ". "errline:". $errline, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
    exit(ERROR_SSH_SYSTEM);
}

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
}

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
}

function installSoftware($ssh) {
    $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_INSTALLING_SOFTWARE;
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
}

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


function updateInfo() {
    $id = mysql_real_escape_string(isset($_POST['id']) ? ($_POST['id']) : '');
    $ipv4 = mysql_real_escape_string(isset($_POST['ipv4']) ? ($_POST['ipv4']) : '');
    $name = mysql_real_escape_string(isset($_POST['name']) ? ($_POST['name']) : '');
    $login = mysql_real_escape_string(isset($_POST['login']) ? ($_POST['login']) : '');
    $password = mysql_real_escape_string(isset($_POST['password']) ? ($_POST['password']) : '');
    if (Tools::IsAuth()) {
        ServerDAO::updateServerBasicServer($id, $ipv4, $name, $login, $password);
        return ;
    }
    http_response_code(401);
    return false;
}

if (isset($_POST["nameRequest"])) {
    if (!Tools::IsAuth()) {
        ReportDAO::insertReport(new Report(0, 0, "", "Security Warning", "call to ". $_POST["nameRequest"] ."() without be auth", REPORTING_TYPE_SECURITY, date("Y-m-d H:i:s")));
        exit(ERROR_REQUIRE_AUTH);
    }
    if ($_POST["nameRequest"] == "updateInfo")
        updateInfo();
    else if ($_POST["nameRequest"] == "installServer")
	installServer();
}
?>