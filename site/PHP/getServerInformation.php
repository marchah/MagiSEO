<?php
@session_start();
require_once('Constantes.php');
require_once('Server.class.php');

function getAllServerInfo() {
	try
	{
		$bdd = new PDO('mysql:host='. BDD_HOST .';dbname='. BDD_DBNAME, BDD_USERNAME, BDD_PASSWORD);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	
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
?>