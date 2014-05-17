<?php
@session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/DAO.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/User.class.php';

class UserDAO extends DAO {
    static function authentification($login, $password) {
        $bdd = parent::ConnectionBDD();
        $reponse = $bdd->query("SELECT * FROM user WHERE login='$login' AND password=PASSWORD('$password')");
	if ($data = $reponse->fetch()) {
            $bdd->exec("UPDATE user SET date_last_connection = NOW() WHERE id=" . $data['id']);
            return new User($data['id'], $data['login'], $password, $data['firstname'], $data['lastname'], $data['email'], $data['avatar_path'], $data['date_last_connection']);
        }
        return false;
    }
}
