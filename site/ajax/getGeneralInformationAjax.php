<?php
session_start();
require_once('../PHP/Constantes.php');

header("Content-Type: text/plain");

function	getNbSlaveServer($bdd) {
	$reponse = $bdd->query('SELECT COUNT(*) AS "nbSlaveServer" FROM server_slave');
	if ($data = $reponse->fetch())
		echo $data['nbSlaveServer'];
	else
		echo "0";
	$reponse->closeCursor();
}

function	getNbError($bdd) {
	$reponse = $bdd->query('SELECT COUNT(*) AS "nbError" FROM reporting WHERE type=' . REPORTING_TYPE_ERROR);
	if ($data = $reponse->fetch())
		echo $data['nbError'];
	else
		echo "0";
	$reponse->closeCursor();
}

function	getNbWarning($bdd) {
	$reponse = $bdd->query('SELECT COUNT(*) AS "nbWarning" FROM reporting WHERE type=' . REPORTING_TYPE_WARNING);
	if ($data = $reponse->fetch())
		echo $data['nbWarning'];
	else
		echo "0";
	$reponse->closeCursor();
}

function	getNbBug($bdd) {
	$reponse = $bdd->query('SELECT COUNT(*) AS "nbBug" FROM reporting WHERE type=' . REPORTING_TYPE_BUG);
	if ($data = $reponse->fetch())
		echo $data['nbBug'];
	else
		echo "0";
	$reponse->closeCursor();
}

function	getAll($bdd) {
	$reponse = $bdd->query('(SELECT COUNT(*) AS "All" FROM server_slave )'
							. 'UNION ALL ( SELECT COUNT(id) AS "All" FROM reporting WHERE type = ' . REPORTING_TYPE_ERROR . ')'
							. 'UNION ALL ( SELECT COUNT(id) AS "All" FROM reporting WHERE type = ' . REPORTING_TYPE_WARNING . ')'
							. 'UNION ALL ( SELECT COUNT(id) AS "All" FROM reporting WHERE type = ' . REPORTING_TYPE_BUG . ' )');
	$i = 0;
	while ($data = $reponse->fetch()) {
		if ($i != 0)
			echo '/';
		echo $data['All'];
		$i++;
	}
	$reponse->closeCursor();
}

function	getLog($bdd) {
	$reponse = $bdd->query('SELECT l.*,  u.login FROM log l LEFT JOIN user u ON l.id_user = u.id ORDER BY id DESC LIMIT ' . LOG_NB_LIMIT);
	
	while ($data = $reponse->fetch()) {
		echo '<li class="media">'
				.'<div class="media-object pull-left">'
					.'<i class="ico-pencil bgcolor-success"></i>'
				.'</div>'
				.'<div class="media-body">'
					.'<p class="media-heading">'. $data['title'] .'</p>'
					.'<p class="media-text"><span class="text-primary semibold">'. $data['login'] .'</span> : '. $data['description'] .'</p>'
					.'<p class="media-meta">'. $data['date'] .'</p>'
				.'</div>'
			.'</li>';
	}
	$reponse->closeCursor();
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
	if ($_POST["nameRequest"] == "getNbSlaveServer")
		getNbSlaveServer($bdd);
	else if ($_POST["nameRequest"] == "getNbError")
		getNbError($bdd);
	else if ($_POST["nameRequest"] == "getNbWarning")
		getNbWarning($bdd);
	else if ($_POST["nameRequest"] == "getNbBug")
		getNbBug($bdd);
	else if ($_POST["nameRequest"] == "getAll")
		getAll($bdd);
	else if ($_POST["nameRequest"] == "getLog")
		getLog($bdd);
}
?>