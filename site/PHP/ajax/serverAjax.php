<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ServerDAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Tools.php';

header("Content-Type: text/plain");

if (!set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/phpseclib')) {
		Tools::Reporting(!Tools::IsAuth() ? 0 : $_SESSION['user']->getId(), "Internal Error", "set_include_path() failed on serverAjax.php", REPORTING_TYPE_INTERNAL_ERROR);
		exit(ERROR_SYSTEM);
	}
	
set_error_handler('errorHandler', E_USER_NOTICE);

function errorHandler($errno, $errstr, $errfile, $errline) {
	Tools::Reporting($_SESSION['user']->getId(), "PHPseclib internal error", "errno=". $errno .", ". "errstr=". $errstr .", ". "errfile:". $errfile .", ". "errline:". $errline, REPORTING_TYPE_ERROR);
	exit(ERROR_SSH_SYSTEM);
}


function installServer() {

    $ipServerSSH = (isset($_POST["ipServerSSH"])) ? $_POST["ipServerSSH"] : "";
    $user = (isset($_POST["user"])) ? $_POST["user"] : "";
    $login = (isset($_POST["login"])) ? $_POST["login"] : "";

    if (empty($ipServerSSH) || empty($user) || empty($login)) {
                    $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_ERROR;
                    exit(ERROR_SSH_CONNECTION_SERVER_REQUIREMENT);
            }

    $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_INIT;

    $ssh = new Net_SSH2($ipServerSSH);
    if (!$ssh->login($user, $login)) {
            $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_ERROR;
            exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    $ssh->write("su -c \"cp $PATH_FILE_CONFIG_SSH $PATH_FILE_CONFIG_SSH_SAVE\"\n");
    $ssh->exec("mkdir $PATH_DIR_KEY_SSH");
    $ssh->exec("chmod 0700 $PATH_DIR_KEY_SSH");
    $ssh->exec("ssh-keygen -t dsa -f ". $PATH_FILE_PRIVATE_KEY_SSH ." -N ''");
    if (!file_put_contents($PATH_MASTER_PUBLIC_KEY_SSH .'/'. $ipServerSSH, $ssh->exec("cat ". $PATH_FILE_PRIVATE_KEY_SSH))) {
        $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_ERROR;
        ReportDAO::insertReport(new Report($_SESSION['user']->getId(), "SSH Download File Error", ERROR_SSH_DOWNLOAD_KEY_FAILED, REPORTING_TYPE_SLAVE_ERROR));
        exit(ERROR_SSH_DOWNLOAD_KEY_FAILED);
    }
    ServerDAO::uploadKeySSHPath($_SESSION['user']->getId(), $ipServerSSH);
    $ssh->exec('chmod 0600 '. $PATH_DIR_KEY_SSH .'/*');
    $ssh->write("su -c \"/usr/sbin/addgroup $GROUP_SSH_ALLOW\"\n");
    $ssh->write("su -c \"/usr/sbin/usermod -a -G $GROUP_SSH_ALLOW $user\"\n");
    $ssh->write("su -c \"sed -i 's/^Port [0-9][0-9]*$/Port $SSH_PORT/' $PATH_FILE_CONFIG_SSH; "
                    //."sed -i 's/HostKey /etc/ssh/ssh_host_dsa_key / / ;"
                    ."sed -i 's/^Protocol [0-9][0-9]*$/Protocol $SSH_PROTOCOL/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^PermitRootLogin [a-zA-Z-][a-zA-Z-]*$/PermitRootLogin no/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^LoginGraceTime [0-9][0-9]*$/LoginGraceTime $LOGIN_GRACE_TIME/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^MaxAuthTries [0-9][0-9]*$/MaxAuthTries $MAX_AUTH_TRIES/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^RSAAuthentication [a-zA-Z][a-zA-Z]*$/RSAAuthentication no/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^UsePAM [a-zA-Z][a-zA-Z]*$/UsePAM no/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^KerberosAuthentication [a-zA-Z][a-zA-Z]*$/KerberosAuthentication no/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^GSSAPIAuthentication [a-zA-Z][a-zA-Z]*$/GSSAPIAuthentication no/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^PasswordAuthentication [a-zA-Z][a-zA-Z]*$/PasswordAuthentication no/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^#MaxStartups [0-9:][0-9:]*$/MaxStartups $MAX_STARTUPS/' $PATH_FILE_CONFIG_SSH; "
                    ."sed -i 's/^MaxStartups [0-9:][0-9:]*$/MaxStartups $MAX_STARTUPS/' $PATH_FILE_CONFIG_SSH;\"\n");
                    //." echo \"AllowGroups $GROUP_SSH_ALLOW\" >> $PATH_FILE_CONFIG_SSH;\"\n");
    $ssh->write("su -c \"/etc/init.d/ssh restart\"\n");
    $_SESSION[INSTALL_SERVER_STEP] = INSTALL_SERVER_STEP_DONE;
    ReportDAO::insertReport(new Report($_SESSION['user']->getId(), "Install Server Slave $ipServerSSH", "Success", REPORTING_TYPE_LOG));
    echo true;
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
        ReportDAO::insertReport(new Report(0, "Security Warning", "call to ". $_POST["nameRequest"] ."() without be auth", REPORTING_TYPE_SECURITY));
        exit(ERROR_REQUIRE_AUTH);
    }
    if ($_POST["nameRequest"] == "updateInfo")
        updateInfo();
    else if ($_POST["nameRequest"] == "installServer")
	installServer();
}
?>