<?php
session_start();
require_once('../PHP/Constantes.php');

header("Content-Type: text/plain");

function	updateInfo($bdd) {
	$id = mysql_real_escape_string(isset($_POST['id']) ? ($_POST['id']) : '');
	$ipv4 = mysql_real_escape_string(isset($_POST['ipv4']) ? ($_POST['ipv4']) : '');
	$name = mysql_real_escape_string(isset($_POST['name']) ? ($_POST['name']) : '');
	$username = mysql_real_escape_string(isset($_POST['username']) ? ($_POST['username']) : '');
	$password = mysql_real_escape_string(isset($_POST['password']) ? ($_POST['password']) : '');
	if (isset($_SESSION['auth']) && $_SESSION['auth'] == true) {
		echo $bdd->exec("UPDATE server_slave SET ipv4='$ipv4', name='$name', username='$username', password='$password' WHERE id='$id'");
		return ;
	}
	//http_response_code(401); for PHP > 5.4
	header(':', true, 401);
	header('X-PHP-Response-Code: 401', true, 401);
	return false;
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
	if ($_POST["nameRequest"] == "updateInfo")
		updateInfo($bdd);
}
?>