<?php
require_once '../site/PHP/DAO/ServerDAO.class.php';

$listServer = ServerDAO::getNewSlaveServer();

var_dump($listServer);
