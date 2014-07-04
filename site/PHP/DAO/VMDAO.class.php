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

    static function insertVMProcessing($idServer, $ipServer, $dateBegin, $dateEnd) {
        $bdd = parent::ConnectionBDD();

        $req = $bdd->prepare('INSERT INTO vm_processing (idserver, ipserver, date_begin, date_end) VALUES(:idserver, :ipserver, :date_begin, :date_end)');
        if (!$req->execute(array(
                    'idserver' => $idServer,
                    'ipserver' => $ipServer,
                    'date_begin' => $dateBegin,
                    'date_end' => $dateEnd
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
            ReportDAO::insertReport(new Report(0, $_SESSION['user']->getId(), "", "REQUEST SQL FAILED", "ServerDAO::updateServerBasicServer()", REPORTING_TYPE_INTERNAL_ERROR), date("Y-m-d H:i:s"));
            return false;
        }
        return true;
    }

    static function getListVM() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query('SELECT vm.*, ss.IPV4 FROM vm vm INNER JOIN server_slave ss ON ss.id = vm.idserver ORDER BY vm.idserver');
        $listVM = array();
        while ($data = $reponse->fetch()) {
            $VM = new VM($data['id'], $data['idserver'], $data['ip'], $data['name'], $data['username'], $data['password'], $data['ram'], $data['hdd'], $data['state']);
            $VM->setServerIP($data['IPV4']);
            $listVM[] = $VM;
        }
        $reponse->closeCursor();
        return $listVM;
    }

    static function getNewVM() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query('SELECT vm.*, ss.IPV4 FROM vm vm INNER JOIN server_slave ss ON ss.id = vm.idserver ORDER BY vm.id DESC LIMIT 1');
        $VM;
        if ($data = $reponse->fetch()) {
            $VM = new VM($data['id'], $data['idserver'], $data['ip'], $data['name'], $data['username'], $data['password'], $data['ram'], $data['hdd'], $data['state']);
            $VM->setServerIP($data['IPV4']);
        }
        $reponse->closeCursor();
        return $VM;
    }
    
    static function getVMByIPServerAndIPVM($IPServer, $IPVM) {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query('SELECT vm.*, ss.IPV4 FROM vm vm INNER JOIN server_slave ss ON ss.id = vm.idserver WHERE vm.ip=' . $bdd->quote($IPVM). ' AND ss.IPV4=' . $bdd->quote($IPServer));
        $VM = false;
        if ($data = $reponse->fetch()) {
            $VM = new VM($data['id'], $data['idserver'], $data['ip'], $data['name'], $data['username'], $data['password'], $data['ram'], $data['hdd'], $data['state']);
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

}
