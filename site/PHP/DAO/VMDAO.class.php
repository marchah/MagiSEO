<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/DAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/VM.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ReportDAO.class.php';

class VMDAO extends DAO {

    static function isVMNameExistInSever($name, $idServer) {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query("SELECT COUNT(id) AS NB FROM vm WHERE idserver=" . $bdd->quote($idServer) . " AND name=\"" . $bdd->quote($name) . "\"");

        if ($data = $reponse->fetch())
            return intval($data['NB']) > 0 ? true : false;
        return false;
    }

    static function isVMInProcess() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query("SELECT COUNT(id) AS NB FROM vm_processing");

        if ($data = $reponse->fetch())
            return intval($data['NB']) > 0 ? true : false;
        return false;
    }

    static function insertVMProcessing($idServer, $ipServer, $dateBegin, $port, $ipMaster, $ipAlgo, $urlClient, $isArchive) {
        $bdd = parent::ConnectionBDD();
        $req = $bdd->prepare('INSERT INTO vm_processing (idserver, ipserver, date_begin, date_end, porttunnel, ipmaster, ipalgo, urlclient, isarchive) VALUES (:idserver, :ipserver, :date_begin, :date_end, :porttunnel, :ipmaster, :ipalgo, :urlclient, :isarchive)');
        if (!$req->execute(array(
                    'idserver' => $idServer,
                    'ipserver' => $ipServer,
                    'date_begin' => $dateBegin,
                    'date_end' => 0,
                    'porttunnel' => $port,
                    'ipmaster' => $ipMaster,
                    'ipalgo' => $ipAlgo,
                    'urlclient' => $urlClient,
                    'isarchive' => ($isArchive === "true" ? 1 : 0)
                ))) {
            ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), "", "REQUEST SQL FAILED", "VMDAO::insertVMProcessing()", REPORTING_TYPE_INTERNAL_ERROR, date("Y-m-d H:i:s")));
            return false;
        }
        return $bdd->lastInsertId();
    }

    static function deleteVMProcessing() {
        $bdd = parent::ConnectionBDD();

        $req = $bdd->prepare('DELETE FROM vm_processing');
        if (!$req->execute()) {
            ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), "", "REQUEST SQL FAILED", "VMDAO::deleteVMProcessing()", REPORTING_TYPE_INTERNAL_ERROR, date("Y-m-d H:i:s")));
            return false;
        }
        return true;
    }

    static function getIdVMInProcess() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query("SELECT id FROM vm WHERE state=" . $bdd->quote(VM_STATE_PROCESSING));

        if ($data = $reponse->fetch())
            return intval($data['id']);
        return false;
    }
    
    static function getIdVMInProcessing() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query("SELECT idvm FROM vm_processing");

        if ($data = $reponse->fetch())
            return intval($data['idvm']);
        return false;
    }

    static function updateVMToDone($id, $idServer, $name, $username, $password, $RAM, $HDD) {
        $bdd = parent::ConnectionBDD();

        $req = $bdd->prepare('UPDATE vm SET name=:name, username=:username, password=:password, ram=:ram, hdd=:hdd, state=:state WHERE id=:id AND idserver=:idserver');
        if (!$req->execute(array(
                    'name' => $name,
                    'ram' => $RAM,
                    'username' => $username,
                    'password' => $password,
                    'hdd' => $HDD,
                    'state' => VM_STATE_DONE,
                    'id' => $id,
                    'idserver' => $idServer
                ))) {
            ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), "", "REQUEST SQL FAILED", "ServerDAO::updateVMToDone()", REPORTING_TYPE_INTERNAL_ERROR), date("Y-m-d H:i:s"));
            return false;
        }
        return true;
    }
    
    static function updateStateVM($id, $state) {
        $bdd = parent::ConnectionBDD();

        $req = $bdd->prepare('UPDATE vm SET state=:state WHERE id=:id');
        if (!$req->execute(array(
                    'state' => $state,
                    'id' => $id
                ))) {
            ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), "", "REQUEST SQL FAILED", "ServerDAO::updateStateVM()", REPORTING_TYPE_INTERNAL_ERROR), date("Y-m-d H:i:s"));
            return false;
        }
        return true;
    }

    static function getListVM() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query('SELECT vm.*, ss.IPV4, vms.name AS `StateName` FROM vm vm INNER JOIN server_slave ss ON ss.id = vm.idserver INNER JOIN vm_state vms ON vm.state = vms.id ORDER BY vm.idserver, vm.id ASC');
        $listVM = array();
        while ($data = $reponse->fetch()) {
            $VM = new VM($data['id'], $data['idserver'], $data['ip'], $data['port'], $data['name'], $data['username'], $data['password'], $data['ram'], $data['hdd'], $data['state']);
            $VM->setServerIP($data['IPV4']);
            $VM->setStateName($data['StateName']);
            $listVM[] = $VM;
        }
        $reponse->closeCursor();
        return $listVM;
    }
    
    static function getListVMByState($state) {
        $bdd = parent::ConnectionBDD();

	$reponse = $bdd->query('SELECT vm.*, ss.IPV4, vms.name AS `StateName` FROM vm vm INNER JOIN server_slave ss ON ss.id = vm.idserver INNER JOIN vm_state vms ON vm.state = vms.id WHERE vm.state= '.$bdd->quote($state).' ORDER BY vm.idserver, vm.id ASC');
        $listVM = array();
        while ($data = $reponse->fetch()) {
            $VM = new VM($data['id'], $data['idserver'], $data['ip'], $data['port'], $data['name'], $data['username'], $data['password'], $data['ram'], $data['hdd'], $data['state']);
            $VM->setServerIP($data['IPV4']);
            $VM->setStateName($data['StateName']);
            $listVM[] = $VM;
        }
        $reponse->closeCursor();
        return $listVM;
    }

    static function getNewVM() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query('SELECT vm.*, ss.IPV4, vms.name AS `StateName` FROM vm vm INNER JOIN server_slave ss ON ss.id = vm.idserver INNER JOIN vm_state vms ON vm.state = vms.id ORDER BY vm.id DESC LIMIT 1');
        $VM;
        if ($data = $reponse->fetch()) {
            $VM = new VM($data['id'], $data['idserver'], $data['ip'], $data['port'], $data['name'], $data['username'], $data['password'], $data['ram'], $data['hdd'], $data['state']);
            $VM->setServerIP($data['IPV4']);
            $VM->setStateName($data['StateName']);
        }
        $reponse->closeCursor();
        return $VM;
    }
    
    static function getVMByIPServerAndIPVM($IPServer, $IPVM) {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query('SELECT vm.*, ss.IPV4 FROM vm vm INNER JOIN server_slave ss ON ss.id = vm.idserver WHERE vm.ip=' . $bdd->quote($IPVM). ' AND ss.IPV4=' . $bdd->quote($IPServer));
        $VM = false;
        if ($data = $reponse->fetch()) {
            $VM = new VM($data['id'], $data['idserver'], $data['ip'], $data['port'], $data['name'], $data['username'], $data['password'], $data['ram'], $data['hdd'], $data['state']);
            $VM->setServerIP($data['IPV4']);
        }
        $reponse->closeCursor();
        return $VM;
    }
    
    static function getVMByIdVM($IdVM) {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query('SELECT vm.*, ss.IPV4 FROM vm vm INNER JOIN server_slave ss ON ss.id = vm.idserver WHERE vm.id=' . $bdd->quote($IdVM));
        $VM = false;
        if ($data = $reponse->fetch()) {
            $VM = new VM($data['id'], $data['idserver'], $data['ip'], $data['port'], $data['name'], $data['username'], $data['password'], $data['ram'], $data['hdd'], $data['state']);
            $VM->setServerIP($data['IPV4']);
        }
        $reponse->closeCursor();
        return $VM;
    }
    
    static function deleteVM($id) {
            $bdd = parent::ConnectionBDD();

            $req = $bdd->prepare('DELETE FROM vm WHERE id=:id');
            if (!$req->execute(array(
                            'id' => $id
                    ))) {
                            ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), "", "REQUEST SQL FAILED", "VMDAO::deleteVM()", REPORTING_TYPE_INTERNAL_ERROR, date("Y-m-d H:i:s")));
                            return false;
                    }
            return true;
        }
    
    static function isVMProcessingDone() {
        $bdd = parent::ConnectionBDD();
        $reponse = $bdd->query('SELECT date_end FROM vm_processing LIMIT 1');
        
        $ret = false;
        if ($data = $reponse->fetch()) {
            if((int)$data['date_end'])
               $ret = true;
        }
        return $ret;
    }
    
    static function getVMsRamUsedByIdServer($idServer) {
        $bdd = parent::ConnectionBDD();

        $totalRAM = 0;
        $reponse = $bdd->query('SELECT COALESCE(SUM(ram), 0) AS \'TotalRAM\' FROM vm WHERE idserver=' . $bdd->quote($idServer));
        if ($data = $reponse->fetch())
            $totalRAM = $data['TotalRAM'];
        $reponse->closeCursor();
        return $totalRAM;
    }
    
    static function getNextPortTunnelVM() {
        $bdd = parent::ConnectionBDD();
        
        $port = -1;
        $id = -1;
        $reponse = $bdd->query('SELECT * FROM counter_port_vm LIMIT 1');
        if ($data = $reponse->fetch()) {
            $port = $data['counter'];
            $id = $data['id'];
        }
        $reponse->closeCursor();
        if ($id != -1) {
            $req = $bdd->prepare('UPDATE counter_port_vm SET counter=:counter WHERE id=:id');
            if (!$req->execute(array(
                        'counter' => $port+1,
                        'id' => $id
                    ))) {
                ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), "", "REQUEST SQL FAILED", "ServerDAO::getNextPortTunnelVM()", REPORTING_TYPE_INTERNAL_ERROR), date("Y-m-d H:i:s"));
                return false;
            }
        }
        return $port;
    }

    static function launchVM() {
    	   $bdd = parent::ConnectionBDD();
	   $ret = $bdd->exec('UPDATE vm SET state=3 WHERE state=2 LIMIT 1');
	   return $ret;
    }

    static function stopVM($url) {
    	   $bdd = parent::ConnectionBDD();
	   $ret = $bdd->exec('UPDATE vm SET state=2 WHERE state=3 LIMIT 1');
	   return $ret;
    }

    static function insertResultVM($url) {
	   $bdd = parent::ConnectionBDD();

/*
        $req = $bdd->prepare('INSERT INTO vm_results (idvm, stdin, stderr) VALUES (:idserver, :ipserver, :date_begin, :date_end, :porttunnel, :ipmaster, :ipalgo, :urlclient, :isarchive)');
        if (!$req->execute(array(
                    'idserver' => $idServer,
                    'ipserver' => $ipServer,
                    'date_begin' => $dateBegin,
                    'date_end' => 0,
                    'porttunnel' => $port,
                    'ipmaster' => $ipMaster,
                    'ipalgo' => $ipAlgo,
                    'urlclient' => $urlClient,
                    'isarchive' => ($isArchive === "true" ? 1 : 0)
                )))*/

	   $bdd->exec('INSERT INTO vm_results SET idvm=8, stdout="Array ( [scheme] => http [host] => '.$url.' [path] => / ) ", stderr="No Error", date=NOW()');
    }


    static function getListVMState() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query("SELECT * FROM vm_state");

	$listVMState = array();
        while ($data = $reponse->fetch())
            $listVMState[] = $data;
	$reponse->closeCursor();
        return $listVMState;
    }


}
