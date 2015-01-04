<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/DAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/ReportDAO.class.php';

class AlgoDAO extends DAO {

    static function getListAlgoResult() {
        $bdd = parent::ConnectionBDD();

        $reponse = $bdd->query('SELECT r.*, vm.name as `nameVM` FROM vm_results r LEFT JOIN vm ON vm.id = r.idvm ORDER BY r.id DESC');
        $listAlgoResult = array();
        while ($data = $reponse->fetch()) {
	    $result = array();
	    if (isset($data['nameVM']))
	       $result['nameVM'] = $data['nameVM'];
	    else
	        $result['nameVM'] = "Unknow";
	    $result['date'] = $data['date'];
	    $result['stdout'] = $data['stdout'];
	    $result['stderr'] = $data['stderr'];
            $listAlgoResult[] = $result;
        }
        $reponse->closeCursor();
        return $listAlgoResult;
    }
}
