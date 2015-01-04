<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Constantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/ErrorConstantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/DAO/VMDAO.class.php';

require_once 'Crypt/RSA.php';
require_once 'Net/SSH2.php';
require_once 'Net/SFTP.php';

set_error_handler('errorHandler', E_USER_NOTICE);

function errorHandler($errno, $errstr, $errfile, $errline) {
    ReportDAO::insertReport(new Report(0, 0, "AlgoAutoRunOnVm)", "PHPseclib internal error", "errno=". $errno .", ". "errstr=". $errstr .", ". "errfile:". $errfile .", ". "errline:". $errline, REPORTING_TYPE_SLAVE_ERROR, date("Y-m-d H:i:s")));
    exit(ERROR_SSH_SYSTEM);
}

$URLClient = (isset($_POST["URLClient"])) ? $_POST["URLClient"] : "";
$isArchive = (isset($_POST["isArchive"])) ? $_POST["isArchive"] : 0;

$URLClient = gethostbyname(parse_url($URLClient, PHP_URL_HOST));

echo $URLClient . "<br />";

if (empty($URLClient)) {
    exit(ERROR_ALGO_RUN_AUTO_REQUIREMENT);
}

$listVM = VMDAO::getListVMByState(VM_STATE_DONE);
if (count($listVM) <= 0) {
    exit(ERROR_ALGO_RUN_AUTO_NO_VM_AVAILABLE);
}

VMDAO::updateStateVM($listVM[0]->getId(), VM_STATE_USING);

function connectionVMWithPassword($VM) {
    $ssh = new Net_SSH2($VM->getIp(), $VM->getPort());
    if (!$ssh->login($VM->getUsername(), $VM->getPassword())) {
        exit(ERROR_SSH_CONNECTION_INVALID_AUTH);
    }
    return $ssh;
}
echo "idVM: " . $listVM[0]->getId() . "<br />";
$ssh = connectionVMWithPassword($listVM[0]);
//echo $ssh->read(REGEX_PROMPT, NET_SSH2_READ_REGEX);
$str = 'php /home/marcha/launchScriptAlgo.php '.$listVM[0]->getId().' '.$URLClient.' '.$isArchive.' '.ALGO_SERVER_IP.' > launchScriptAlgo.log &';
//echo $str;
$ssh->exec($str);