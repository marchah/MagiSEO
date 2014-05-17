<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/DAO/UserDAO.class.php';
@session_start();
header("Content-Type: text/plain");

function	authentification() {
	$login = mysql_real_escape_string(isset($_POST['login']) ? ($_POST['login']) : '');
	$password = mysql_real_escape_string(isset($_POST['password']) ? ($_POST['password']) : '');
 
        if ($user = UserDAO::authentification($login, $password)) {
            $_SESSION['auth'] = true;
            $_SESSION['user'] = $user;
            echo true;
        }
        else {
            echo false;
            http_response_code(404);
        }
}

function disconnection() {
	$_SESSION['auth'] = null;
        $_SESSION['user'] = null;
        echo "good";
	if (!@session_destroy()) {
		http_response_code(500);
	}
}

if (isset($_POST["nameRequest"])) {
    if ($_POST["nameRequest"] == "authentification")
        authentification();
    else if ($_POST["nameRequest"] == "disconnection")
        disconnection();
}
?>