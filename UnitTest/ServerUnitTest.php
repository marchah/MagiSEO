<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/AJAX/serverAjax.php';

$_POST["ipServerSSH"] = "192.168.234.153";
$_POST["login"] = "marcha";
$_POST["keySSHPath"] = $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/' . PATH_MASTER_PRIVATE_KEY_SSH . $_POST["ipServerSSH"];
$_POST["password"] = "totoauzoo";

/*set_time_limit(200);

echo "########### INSTALLATION SERVER ##############\n";
installServer();
echo "########### CONNECTION USING KEY RSA ##############\n";
connectionServerWithKey();
echo "########### DESINSTALLATION SERVER ##############\n";
desinstallServer();*/

installServerSlave();
