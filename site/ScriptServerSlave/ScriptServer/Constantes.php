<?php

define('BDD_HOST', '192.168.1.8');
define('BDD_DBNAME', 'magiseo');
define('BDD_USERNAME', 'vm');
define('BDD_PASSWORD', 'totoauzoo');

define('REPORTING_TYPE_SECURITY', 4);
define('REPORTING_TYPE_INTERNAL_ERROR', 5);

define('MY_ADDRESS_IP', '$#IP_ADDRESS#$'); //Voir si je ne peut pas le faire avec une fonction PHP

define('LOGIN', '$#LOGIN#$');
define('PUBLIC_KEY', 'ScriptServer/authorized_keys');
define('MSG_DELIMITER', '#/$/#:');

function ConnectionBDD() {
    try
    {
        return new PDO('mysql:host='. BDD_HOST .';dbname='. BDD_DBNAME, BDD_USERNAME, BDD_PASSWORD);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage() . "\n");
    }
}

function insertReport($title, $description, $type) {
    $bdd = ConnectionBDD();
    $req = $bdd->prepare("INSERT INTO reporting (iduser, title, description, type, date) VALUES(:iduser, :title, :description, :type, :date)");
    $req->execute([
                    'iduser' => 0,
                    'title' => $title,
                    'description' => $description,
                    'type' => $type,
                    'date' => date("Y-m-d H:i:s")
            ]);
}

