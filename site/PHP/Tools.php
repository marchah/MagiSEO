<?php
@session_start();
require_once('Constantes.php');
require_once('ErrorConstantes.php');

class Tools {

    static function ConnectionBDD() {
            try
            {
                    return new PDO('mysql:host='. BDD_HOST .';dbname='. BDD_DBNAME, BDD_USERNAME, BDD_PASSWORD);
            }
            catch (Exception $e)
            {
                    die('Erreur : ' . $e->getMessage());
            }
    }

    static function Reporting($idUser, $title, $description, $type) {
    $bdd = ConnectionBDD();
    $req = $bdd->prepare('INSERT INTO reporting (iduser, title, description, type, date) VALUES(:iduser, :title, :description, type, :date)');
    $req->execute([
                    'iduser' => $idUser,
                    'title' => $title,
                    'description' => $description,
                    'type' => $type,
                    'date' => date("d/m/y")
            ]);
    }

    static function IsAuth() {
            return (isset($_SESSION['auth']) && $_SESSION['auth'] == true) ? true : false;
    }

}
?>