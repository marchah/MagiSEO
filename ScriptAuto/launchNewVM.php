<?php

require_once '../site/PHP/BDDConstantes.php';
require_once '../site/PHP/Object/Cache.class.php';

define('READY', "READY");
define('PROCESSING', "PROCESSING");
define('PATH_CACHE_LAUNCH_NEW_VM_AUTO', '../site/cache/launchNewVM');

function ConnectionBDD() {
    try
    {
        return new PDO('mysql:host='. BDD_HOST .';dbname='. BDD_DBNAME, BDD_USERNAME_AUTO, BDD_PASSWORD_AUTO);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
}

function getLastVMTODO() {
    $bdd = ConnectionBDD();

    $ret = null;
    $reponse = $bdd->query('SELECT * FROM vm_todo ORDER BY id ASC LIMIT 1');
    if ($data = $reponse->fetch())
        $ret = $data;
    $reponse->closeCursor();
    return $ret;
}

set_time_limit(200);

while (($isOK = Cache::read(PATH_CACHE_LAUNCH_NEW_VM_AUTO)) === PROCESSING) {
    if ($isOK === false)
        break;
    sleep(5);
}
Cache::write(PATH_CACHE_LAUNCH_NEW_VM_AUTO, PROCESSING);
$vmTODO = null;
if (($vmTODO = getLastVMTODO()) == null) {
    return ;
}

var_dump($vmTODO);
Cache::write(PATH_CACHE_LAUNCH_NEW_VM_AUTO, READY);
