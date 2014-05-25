<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/DAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/Server.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ReportDAO.class.php';

class ServerDAO extends DAO {
	
	static function insertServer($IPV4, $username, $password, $keysshpath) { // MANQUE BEAUCOUP D'INFO A SAVE
            $bdd = parent::ConnectionBDD();

            $req = $bdd->prepare('INSERT INTO server_slave (IPV4, username, password, keysshpath) VALUES(:IPV4, :username, :password, :keysshpath)');
            if (!$req->execute(array(
                            'IPV4' => $IPV4,
                            'username' => $username,
                            'password' => $password,
                            'keysshpath' => $keysshpath
                    ))) {
                            ReportDAO::insertReport(new Report($_SESSION['user']->getId(), "REQUEST SQL FAILED", "ServerDAO::insertServer()", REPORTING_TYPE_INTERNAL_ERROR));
                            return false;
                    }
            return true;
	}
        
        static function deleteServer($IPV4) { // manque beaucoup d'info a delete et plutot prendre Server
            $bdd = parent::ConnectionBDD();

            $req = $bdd->prepare('DELETE FROM server_slave WHERE IPV4=:IPV4');
            if (!$req->execute(array(
                            'IPV4' => $IPV4
                    ))) {
                            ReportDAO::insertReport(new Report($_SESSION['user']->getId(), "REQUEST SQL FAILED", "ServerDAO::deletetServer()", REPORTING_TYPE_INTERNAL_ERROR));
                            return false;
                    }
            return true;
        }
        
        static function updateServerBasicServer($id, $IPV4, $name, $username, $password) {
            $bdd = parent::ConnectionBDD();
            
            $req = $bdd->prepare('UPDATE server_slave SET ipv4=:IPV4, name=:name, username=:username, password=:password WHERE id=:id');
            if (!$req->execute(array(
				'IPV4' => $IPV4,
                                'name' => $name,
				'username' => $username,
				'password' => $password,
                                'id' => $id
			))) {
				ReportDAO::insertReport(new Report($_SESSION['user']->getId(), "REQUEST SQL FAILED", "ServerDAO::updateServerBasicServer()", REPORTING_TYPE_INTERNAL_ERROR));
				return false;
			}
		return true;
        }
        
        static function getListSlaveServer() {
            $bdd = parent::ConnectionBDD();
	
            $reponse = $bdd->query('SELECT sslave.*, sstate.state, sstate.on_off, sinfo.disk_max_size, sinfo.disk_current_size, 
                                                            sinfo.nb_max_proc, sinfo.nb_current_proc, sinfo.flash_max_size, sinfo.flash_current_size 
                                                            FROM server_slave sslave 
                                                            INNER JOIN server_state sstate ON sslave.id = sstate.id 
                                                            INNER JOIN server_information sinfo ON sslave.id = sinfo.id');
            $listServer = array();
            while ($data = $reponse->fetch()) {
                    $server = new Server($data);
                    $listServer[] = $server;	
            }
            $reponse->closeCursor();
            return $listServer;
        }
        
        static function isServerExist($IPV4) {
            $bdd = parent::ConnectionBDD();
            
            $reponse = $bdd->query("SELECT COUNT(id) AS NB FROM server_slave WHERE ipv4=\"$IPV4\"");
            
            if ($data = $reponse->fetch())
                return intval($data['NB']) > 0 ? true : false;
            return false;
        }
}

?>