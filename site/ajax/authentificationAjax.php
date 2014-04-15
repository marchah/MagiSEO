<?php
session_start();
require_once('../PHP/Constantes.php');

header("Content-Type: text/plain");

function	authentification($bdd) {
	$login = mysql_real_escape_string(isset($_POST['login']) ? ($_POST['login']) : '');
	$password = mysql_real_escape_string(isset($_POST['password']) ? ($_POST['password']) : '');
 
	$reponse = $bdd->query("SELECT * FROM user WHERE login='$login' AND password=PASSWORD('$password')");
	if ($data = $reponse->fetch()) {
		$_SESSION['auth'] = true;
		$_SESSION['login'] = $data['login'];
		$_SESSION['firstName'] = $data['firstname'];
		$_SESSION['lastName'] = $data['lastname'];
		$_SESSION['email'] = $data['email'];
		$_SESSION['avatarPath'] = $data['avatar_path'];
		$_SESSION['dateLastConnection'] = $data['date_last_connection'];
		$bdd->exec("UPDATE user SET date_last_connection = NOW() WHERE id=" . $data['id']);
		echo true;
	}
	else {
		echo false;
		//http_response_code(404); for PHP > 5.4
		header(':', true, 404);
		header('X-PHP-Response-Code: 404', true, 404);
	}
}

function disconnection($bdd) {
	$_SESSION['auth'] = null;
	$_SESSION['login'] = null;
	$_SESSION['firstName'] = null;
	$_SESSION['lastName'] = null;
	$_SESSION['email'] = null;
	$_SESSION['dateLastConnection'] = null;
	if (!session_destroy()) {
		//http_response_code(500); for PHP > 5.4
		header(':', true, 500);
		header('X-PHP-Response-Code: 500', true, 500);
	}
}

if (isset($_POST["nameRequest"])) {
	try
	{
		$bdd = new PDO('mysql:host='. BDD_HOST .';dbname='. BDD_DBNAME, BDD_USERNAME, BDD_PASSWORD);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	if ($_POST["nameRequest"] == "authentification")
		authentification($bdd);
	else if ($_POST["nameRequest"] == "disconnection")
		disconnection($bdd);
}

?>