<?php
require_once 'Constantes.php';

class ServerInfo {
    private $_sizeDiskCurrentMB;
    private $_sizeDiskTotalMB;
    private $_sizeRAMCurrentKB;
    private $_sizeRAMTotalKB;
    private $_nbCPUTotal;
    private $_nbCPUUsed;
    
    public function __construct() {
        $this->_sizeDiskCurrentMB = 0;
        $this->_sizeDiskTotalMB = 0;
        $this->_sizeRAMCurrentKB = 0;
        $this->_sizeRAMTotalKB = 0;
        $this->_nbCPUTotal = 0;
        $this->_nbCPUUsed = 0;
    }
    
    private function setSizeDiskMB() {
        $sizeDiskFreeByte = disk_free_space("/");
        $sizeDiskTotalByte = disk_total_space("/");
        
        $this->_sizeDiskCurrentMB = ($sizeDiskFreeByte === false || $sizeDiskTotalByte === false) ? 0 : intval(($sizeDiskTotalByte - $sizeDiskFreeByte) / pow(1024,2));
        $this->_sizeDiskTotalMB = ($sizeDiskTotalByte === false) ? 0 : intval($sizeDiskTotalByte / pow(1024,2));
    }
    
    private function setSizeRAMKB() {      
        $sizeRAMFreeKB = exec("cat /proc/meminfo | grep MemFree");
        $sizeRAMTotalKB = exec("cat /proc/meminfo | grep MemTotal");

        preg_match_all('#[0-9]+#', $sizeRAMTotalKB, $RAMTotal);
        preg_match_all('#[0-9]+#', $sizeRAMFreeKB, $RAMFree);

        $this->_sizeRAMTotalKB = empty($sizeRAMTotalKB) ? 0 : intval($RAMTotal[0][0]);
        $this->_sizeRAMCurrentKB = (empty($sizeRAMFreeKB) || empty($sizeRAMTotalKB)) ? 0 : $this->_sizeRAMTotalKB - intval($RAMFree[0][0]);
    }
    
    private function setNbCPUTotal() {
        $numCpus = 1;
        if (is_file('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);
            $numCpus = count($matches[0]);
        }
        else if ('WIN' == strtoupper(substr(PHP_OS, 0, 3))) {
            if (($process = @popen('wmic cpu get NumberOfCores', 'rb')) !== false) {
                fgets($process);
                $numCpus = intval(fgets($process));
                pclose($process);
            }
        }
        else {
            if (($process = @popen('sysctl -a', 'rb')) !== false) {
                preg_match('/hw.ncpu: (\d+)/', stream_get_contents($process), $matches);
                if ($matches)
                    $numCpus = intval($matches[1][0]);
                pclose($process);
            }
        }
        $this->_nbCPUTotal = $numCpus;
    }
    
    private function setNbCPUUsed() {
        $this->_nbCPUUsed = 0; // depend to the nb of VM, see that later
    }


    public function setInfo() {
        $this->setSizeDiskMB();
        $this->setSizeRAMKB();
        $this->setNbCPUTotal();
        $this->setNbCPUUsed();
    }
    
    public function getInfo() {
        return MSG_DELIMITER . "$this->_sizeDiskCurrentMB/$this->_sizeDiskTotalMB/$this->_sizeRAMCurrentKB/$this->_sizeRAMTotalKB/$this->_nbCPUUsed/$this->_nbCPUTotal" . MSG_DELIMITER;
    }
    
    /*private function insertInfo($bdd, $idServer) {
        $req = $bdd->prepare('INSERT INTO server_information (idserver, disk_max_size, disk_current_size, nb_max_proc, nb_current_proc, flash_max_size, flash_current_size) VALUES(:idserver, :disk_max_size, :disk_current_size, :nb_max_proc, :nb_current_proc, :flash_max_size, :flash_current_size)');
        if (!$req->execute(array(
                        'idserver' => $idServer,
                        'disk_max_size' => $this->_sizeDiskTotalMB,
                        'disk_current_size' => $this->_sizeDiskCurrentMB,
                        'nb_max_proc' => $this->_nbCPUTotal,
                        'nb_current_proc' => $this->_nbCPUUsed,
                        'flash_max_size' => $this->_sizeRAMTotalKB,
                        'flash_current_size' => $this->_sizeRAMCurrentKB

                ))) {
                        insertReport("REQUEST SQL FAILED", "ServerDAO::insertInfo()", REPORTING_TYPE_INTERNAL_ERROR);
                        return false;
                }
        return true;
    }
    
    private function updateInfo($bdd, $idServer) {
        $req = $bdd->prepare('UPDATE server_information SET disk_max_size=:disk_max_size, disk_current_size=:disk_current_size, nb_max_proc=:nb_max_proc, nb_current_proc=:nb_current_proc, flash_max_size=:flash_max_size, flash_current_size=:flash_current_size WHERE idserver=:idserver');
            if (!$req->execute(array(
                        'disk_max_size' => $this->_sizeDiskTotalMB,
                        'disk_current_size' => $this->_sizeDiskCurrentMB,
                        'nb_max_proc' => $this->_nbCPUTotal,
                        'nb_current_proc' => $this->_nbCPUUsed,
                        'flash_max_size' => $this->_sizeRAMTotalKB,
                        'flash_current_size' => $this->_sizeRAMCurrentKB,
                        'idserver' => $idServer
		))) {
                        insertReport("REQUEST SQL FAILED", "ServerInfo::updateInfo()", REPORTING_TYPE_INTERNAL_ERROR);
                        return false;
                }
        return true;
    }
    
    private function getIdServer($bdd) {
        $ret = false;
        $reponse = $bdd->query('SELECT id FROM server_slave WHERE IPV4 = "' . MY_ADDRESS_IP .'"');
        
        if ($data = $reponse->fetch())
            $ret = intval($data['id']);
        else
            insertReport("UNKNOW IP ADDRESS", "ServerInfo::getIdServer()", REPORTING_TYPE_SECURITY);
        $reponse->closeCursor();
        return $ret;
    }
    
    public function save() {
        $ret = false;
        $bdd = ConnectionBDD();
        
        if (($idServer = $this->getIdServer($bdd)) === false)
            return false;

        $reponse = $bdd->query('SELECT COUNT(id) AS "Nb" FROM server_information info WHERE idServer = "' . $idServer .'"');
        if ($data = $reponse->fetch()) {
            if (intval($data['Nb']) === 0)
                $ret = $this->insertInfo($bdd, $idServer);
            else
                $ret = $this->updateInfo($bdd, $idServer); 
        }
        $reponse->closeCursor();
        return $ret;
    }*/
    
    public function __toString() {
        return "ServerInfo {sizeDiskCurrentMB=$this->_sizeDiskCurrentMB, sizeDiskTotalMB=$this->_sizeDiskTotalMB, sizeRAMCurrentKB=$this->_sizeRAMCurrentKB, sizeRAMTotalKB=$this->_sizeRAMTotalKB, nbCPUTotal=$this->_nbCPUTotal, nbCPUUsed=$this->_nbCPUUsed}";
    }
}

$tmp = new ServerInfo();
$tmp->setInfo();
echo $tmp->getInfo() . "\n";
/*if ($tmp->save())
    echo "1";
else
    echo "0";*/