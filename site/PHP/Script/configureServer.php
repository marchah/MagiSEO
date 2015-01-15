<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Constantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/DAO/ServerDAO.class.php';
require_once PATH_ROOT_WEBSITE . 'PHP/Tools.php';
require_once PATH_ROOT_WEBSITE . 'PHP/SSHConstances.php';
require_once PATH_ROOT_WEBSITE . 'PHP/ErrorConstantes.php';
require_once 'Crypt/RSA.php';
require_once 'Net/SSH2.php';
require_once 'Net/SFTP.php';

/*
set_error_handler('errorHandler', E_USER_NOTICE);

function errorHandler($errno, $errstr, $errfile, $errline) {
    Tools::ReportingError(null, null, "PHPseclib internal error", "errno=". $errno .", ". "errstr=". $errstr .", ". "errfile:". $errfile .", ". "errline:". $errline, REPORTING_TYPE_SLAVE_ERROR, ERROR_SSH_SYSTEM);
}
*/


class installServer {
    
    private $_ip;
    private $_login;
    private $_password;
    private $_RAMfreeMB;
    private $_HDDfree;
    private $_idServer;
    private $_keySSHPath;
    
    public function __construct($ip, $login, $password) {
        $this->_ip = $ip;
        $this->_login = $login;
        $this->_password = $password;
        $this->_RAMfreeMB = false;
        $this->_HDDfree = false;
        
        if (ServerDAO::isServerExist($this->_ip)) {
            Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
            exit(ERROR_SSH_SERVER_ALREADY_CONFIGURATED);
        }
    }
    
    private function generateKey() {
        // replace by DSA when i will be in linux server
        $rsa = new Crypt_RSA();
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
        extract($rsa->createKey());

        // save private key
        if (!file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_PRIVATE_KEY_SSH . $this->_ip, $privatekey))
            Tools::ReportingError(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR, "SSH Create File Private Key Error", ERROR_SSH_SAVE_KEY_FAILED, REPORTING_TYPE_SLAVE_ERROR, ERROR_SSH_SAVE_KEY_FAILED);

        // save public key in the folder which be send in the server slave
        if (!file_put_contents(PATH_ROOT_WEBSITE . PATH_MASTER_PUBLIC_KEY_SSH, $publickey))
            Tools::ReportingError(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR, "SSH Create File Public Key Error", ERROR_SSH_SAVE_KEY_FAILED, REPORTING_TYPE_SLAVE_ERROR, ERROR_SSH_SAVE_KEY_FAILED);
    }
    
    private function cpAndPutConstantesInstallScript() {
        $folder = opendir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE . '/ScriptServer/');
        while ($file = readdir($folder)) {
            if ($file != "." && $file != "..")
                if (!copy(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE . '/ScriptServer/' . $file, PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/' . $file))
                    Tools::ReportingError(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR, "cpAndPutConstantesInstallSpript() Error", ERROR_COPY_FILE . " $file", REPORTING_TYPE_SLAVE_ERROR, ERROR_COPY_FILE);
        }
        closedir($folder);
        // Put Constantes in Constantes.php
        $constantesFile = file_get_contents(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/Constantes.php');
        $constantesFile = str_replace("$#IP_ADDRESS#$", $this->_ip, $constantesFile); // WHY ?????
        $constantesFile = str_replace("$#LOGIN#$", $this->_login, $constantesFile);   // WHY ?????
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
    
    public static function cleanInstallFolder() {
        $folder = opendir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD);
        while ($file = readdir($folder)) {
            if ($file != "." && $file != "..")
                unlink(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD . '/' . $file);
        }
        closedir($folder);
    }
    
    private function compressFolder() {
        try {
            $a = new PharData(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD_ARCHIVE);
            $a->buildFromDirectory(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD);
            $a->compress(Phar::GZ);
        }
        catch (Exception $e) {
            $this->cleanInstallFolder();
            Tools::ReportingError(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR, "compressFolder() Error", $e, REPORTING_TYPE_SLAVE_ERROR, ERROR_COMPRESS_FILE);
        }
    }
    
    private function uploadInstallScripts($pathCacheFile, $errorMSG) {
        $sftp = new Net_SFTP($this->_ip);
        if (!$sftp->login($this->_login, $this->_password)) {
            $this->cleanInstallFolder();
            Tools::ReportingError(PATH_ROOT_WEBSITE . $pathCacheFile, $errorMSG, "uploadInstallScripts() Error", ERROR_UPLOAD_SCRIPTS, REPORTING_TYPE_SLAVE_ERROR, ERROR_SSH_CONNECTION_INVALID_AUTH);
        }
        $sftp->put('ScriptServer.tar', PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD_ARCHIVE, NET_SFTP_LOCAL_FILE);
    }
    
    /*private function uploadVMImage($pathCacheFile, $errorMSG) {
        $sftp = new Net_SFTP($this->_ip);
        if (!$sftp->login($this->_login, $this->_password)) {
            $this->cleanInstallFolder();
            Tools::ReportingError(PATH_ROOT_WEBSITE . $pathCacheFile, $errorMSG, "uploadVMImage() Error", ERROR_UPLOAD_VM_IMAGE, REPORTING_TYPE_SLAVE_ERROR, ERROR_SSH_CONNECTION_INVALID_AUTH);
        }
        $sftp->put('vm.ova', PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD_ARCHIVE, NET_SFTP_LOCAL_FILE);
    }*/
    
    private function decompressScriptArchive($ssh) {
        $ssh->exec("rm -rf ScriptServer");
        $ssh->exec("mkdir ScriptServer");
        $ssh->exec("tar -xf ScriptServer.tar -C ScriptServer/");
        $ssh->exec("rm -f ScriptServer.tar");
        $ssh->exec("chmod +x ScriptServer/installSoftware.sh");
        $ssh->exec("chmod +x ScriptServer/desinstallServerSlave.sh");
        $ssh->exec("chmod +x ScriptServer/updateServerSlave.sh");
        $ssh->exec("chmod +x ScriptServer/configureVPN.sh");
        $ssh->exec("chmod +x ScriptServer/deconfigureVPN.sh");
    }
    
    public function install() {
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_INIT);
        set_time_limit(600);
        if (!file_exists(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD))
            mkdir(PATH_ROOT_WEBSITE . PATH_MASTER_SCRIPT_SERVER_SLAVE_TO_UPLOAD);
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_GENERATE_KEY);
        $this->generateKey();
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_COMPRESSING_SCRIPTS);
        $this->cpAndPutConstantesInstallScript();
        $this->compressFolder();
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_UPLOADING_SCRIPTS);
        $this->uploadInstallScripts(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
        //cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_UPLOADING_VM_IMAGE);
        //$this->uploadVMImage(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR); // mv vm.ova /home/.
        $this->cleanInstallFolder();
        $ssh = new Net_SSH2($this->_ip);
        if (!$ssh->login($this->_login, $this->_password)) {
            Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_ERROR);
            exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
        }
        $this->decompressScriptArchive($ssh);
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_INSTALLING_SOFTWARES);
        $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
        $ssh->write("./ScriptServer/installSoftware.sh\n");
        $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_SECURING_SSH);
        $ssh->write("php ./ScriptServer/securiseSSHServerSlave.php\n");
        $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
        $ssh->write("./ScriptServer/configureVPN.sh\n");
        $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
        Cache::write(PATH_CACHE_FILE_INSTALL, INSTALL_SERVER_STEP_GETTING_SERVER_INFOS);
        $this->_keySSHPath = PATH_ROOT_WEBSITE . '/' . PATH_MASTER_PRIVATE_KEY_SSH . $this->_ip;
        $this->_idServer = ServerDAO::insertServer($this->_ip, $this->_login, $this->_password, $this->_keySSHPath);
        $ssh->write("php ./ScriptServer/infoServerSlave.php\n");
        $tab_ret = explode(MSG_DELIMITER, $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX));
        if (count($tab_ret) == 3) {
           $infoServer = explode('/', $tab_ret[1]);
           $this->_RAMfreeMB = intval($infoServer[3]) - SERVER_MIN_RAM_REQUIRE;
           $this->_HDDfree = intval($infoServer[1]) - intval($infoServer[0]);
           ServerDAO::insertServerInfo($this->_idServer, $infoServer[0], $infoServer[1], SERVER_MIN_RAM_REQUIRE, $infoServer[3], $infoServer[4], $infoServer[5]);
        }
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Install Server Slave $this->_ip", "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
        return true;
    }
    
    public function getRAMfreeMB()  {return $this->_RAMfreeMB;}
    public function getHDDfree()    {return $this->_HDDfree;}
    public function getIdServer()   {return $this->_idServer;}
    public function getIp()         {return $this->_ip;}
    public function getLogin()      {return $this->_login;}
    public function getKeySSHPath() {return $this->_keySSHPath;}


    public function setRAMfreeMB($RAMfreeMB)    {$this->_RAMfreeMB = $RAMfreeMB;}
    public function setHDDfree($HDDfree)        {$this->_HDDfree = $HDDfree;}
}

define("STEP_RAM_ADD_VM", 4);

class installVM {
    private $_server;
    private $_RAMPerVM;
    private $_HDDPerVM;
    private $_nbVMToCreate;
    
    public function __construct($server, $minRAMPerVM, $HDDPerVM) {
        $this->_server = $server;
        $this->_RAMPerVM = $minRAMPerVM;
        $this->_HDDPerVM = $HDDPerVM;
        $this->_nbVMToCreate = 0;
    }
    
    private function calculateSizeRAMOptiPerVM() {
        $serverRAMFree = $this->_server->getRAMfreeMB();
        $this->_nbVMToCreate = intval($serverRAMFree / $this->_RAMPerVM);
        while (($this->_RAMPerVM + STEP_RAM_ADD_VM) * $this->_nbVMToCreate < $serverRAMFree) {
            $this->_RAMPerVM += STEP_RAM_ADD_VM;
        }
    }
    
    private function connectionServerWithKey($PATH_CACHE, $NAME_STEP_ERROR) {
        $ssh = new Net_SSH2($this->_server->getIp(), SSH_PORT);
        $key = new Crypt_RSA();
        $tmp = file_get_contents($this->_server->getKeySSHPath());
        $key->loadKey($tmp);
        if (!$ssh->login($this->_server->getLogin(), $key)) {
            Cache::write($PATH_CACHE, $NAME_STEP_ERROR);
            exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
        }
        return $ssh;
    }
    
    private function installVM($nameVM) {
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_INIT);
        if (intval($this->_RAMPerVM) < VM_MIN_SIZE_RAM || intval($this->_HDDPerVM) < VM_MIN_SIZE_HDD) {
            Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
            exit(ERROR_VM_INVALID_REQUIREMENT);
        }

       /* if ($Server->getFlashCurrentSize() + intval($RAM) >= $Server->getFlashMaxSize()) {
            Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
            exit(ERROR_VM_RAM_SIZE);
        }*/

        /*if ($Server->getDiskCurrentSize() + intval($HDD) >= $Server->getDiskMaxSize()) {
            Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
            exit(ERROR_VM_HDD_SIZE);
        }*/

        if (VMDAO::isVMNameExistInSever($nameVM, $this->_server->getIdServer())) {
            Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
            exit(ERROR_VM_INVALID_NAME);
        }

        if (VMDAO::isVMInProcess()) {
            Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
            exit(ERROR_VM_PROCESSING);
        }

        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_CONNECTION_SERVER);
        $ssh = $this->connectionServerWithKey(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_ERROR);
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_INSTALLING);
        $port = VMDAO::getNextPortTunnelVM();
        VMDAO::deleteVMProcessing();
        VMDAO::insertVMProcessing($this->_server->getIdServer(), $this->_server->getIp(), date("Y-m-d H:i:s"), $port, MASTER_IP, "", "", 0);
        $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
        $ssh->write("su -c \"php ScriptServer/installVMNew.php ".$nameVM." ".$this->_RAMPerVM." ".$this->_HDDPerVM."\"\n");
        set_time_limit(2000);
        while (!VMDAO::isVMProcessingDone()) {
            sleep(5);
        }
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_SAVING_BDD);
        $idVM = VMDAO::getIdVMInProcessing();
        VMDAO::updateVMToDone($idVM, $this->_server->getIdServer(), $nameVM, 'marcha', 'totoauzoo', $this->_RAMPerVM, $this->_HDDPerVM);
        VMDAO::deleteVMProcessing();
        Cache::write(PATH_CACHE_FILE_INSTALL_VM, INSTALL_VM_STEP_DONE);
        ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), $_SESSION['user']->getLogin(), "Install VM On Server ".$this->_server->getIp(), "Success", REPORTING_TYPE_LOG, date("Y-m-d H:i:s")));
        return true;
    }
    
    public function install() {
        $this->calculateSizeRAMOptiPerVM();
        for ($i = 0; $i != $this->_nbVMToCreate; $i++) {
            if ($this->installVM(date("Y-m-d_H:i:s")) !== true)
                return false;
        }
    }
}