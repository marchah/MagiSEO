<?php
require_once '../site/PHP/DAO/ServerDAO.class.php';

$listServer = ServerDAO::getListSlaveServer();

var_dump($listServer);
