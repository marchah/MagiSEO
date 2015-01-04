<?php

require_once 'BDDConstantes.php';
require_once 'ServerConstantes.php';


define('REPORTING_TYPE_SLAVE_ERROR', 1);
define('REPORTING_TYPE_SLAVE_BUG', 2);
define('REPORTING_TYPE_SLAVE_WARNING', 3);
define('REPORTING_TYPE_SECURITY', 4);
define('REPORTING_TYPE_INTERNAL_ERROR', 5);
define('REPORTING_TYPE_LOG', 6);

define('LOG_NB_LIMIT', 10);

define('PATH_ROOT_WEBSITE', $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/');



define('SERVER_MIN_RAM_REQUIRE', 2700);// normal: 512, For server slave online: 3415

/*** VM Constantes ***/

define('VM_MIN_SIZE_RAM', 512);
define('VM_MIN_SIZE_HDD', 10000);

define('VM_SIZE_RAM', 512); // normal: 1024, For server slave online: 512
define('VM_SIZE_HDD', 10000);

define('VM_STATE_PROCESSING', 1);
define('VM_STATE_DONE', 2);
define('VM_STATE_USING', 3);
define('VM_STATE_CANCELED', 4);
define('VM_STATE_FINISHED', 5);

?>