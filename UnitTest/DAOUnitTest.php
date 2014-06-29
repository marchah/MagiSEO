<?php
//require_once '../site/PHP/DAO/ServerDAO.class.php';
require_once '../site/PHP/DAO/VMDAO.class.php';
//$listServer = ServerDAO::getNewSlaveServer();

//var_dump($listServer);

//echo ServerDAO::insertServerInfo(2, 111, 1111, 222, 2222, 2, 4);

echo "test: ". (int)VMDAO::isVMProcessingDone() . "|\n";
