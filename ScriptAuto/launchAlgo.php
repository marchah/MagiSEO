<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Constantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/ErrorConstantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/DAO/VMDAO.class.php';

if (isset($_GET["type"]) && $_GET["type"] == "run") {
   $ret = VMDAO::launchVM();
   if ($ret == 1)
      	echo 'true';
   else
	echo 'false';
}
else if (isset($_GET["type"]) && $_GET["type"] == "stop") {
     $url = isset($_GET["url"]) ? $_GET["url"] : "";

     $ret = VMDAO::stopVM($url);
   if ($ret != false) {
      VMDAO::insertResultVM($url, $ret);
      	echo 'true';
	}
   else
	echo 'false';
}
else
	echo 'false';

?>