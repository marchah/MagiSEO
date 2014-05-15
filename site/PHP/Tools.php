<?php
session_start();
require_once('Constantes.php');
require_once('/ErrorConstantes.php');

function ConnectionBDD() {
	try
	{
		return new PDO('mysql:host='. BDD_HOST .';dbname='. BDD_DBNAME, BDD_USERNAME, BDD_PASSWORD);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
}

function ReportLog($idUser, $title, $description) {
$req = $bdd->prepare('INSERT INTO log(iduser, title, description, date) VALUES(:iduser, :title, :description, :date)');
$req->execute([
		'iduser' => $idUser,
		'title' => $title,
		'description' => $description,
		'date' => date("d/m/y");
	]);
}
// FUSIONNER LOG ET ERRER
function ReportError($idUser, $title) {
$req = $bdd->prepare('INSERT INTO reporting(title, type, iduser, date) VALUES(:title, :type, :iduser, :date)');
$req->execute([
		'title' => $title,
		'type' => REPORTING_TYPE_ERROR,
		'iduser' => $idUser,
		'date' => date("d/m/y");
	]);
}
?>