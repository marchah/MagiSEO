<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Constantes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/SSHConstances.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/ErrorConstantes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Object/Cache.class.php';

header("Content-Type: text/plain");

function getStateInstallServer() {
    $step = Cache::read(PATH_ROOT_WEBSITE . "/cache/install");
    $msg = "";
    switch ($step)
    {
        case INSTALL_SERVER_STEP_ERROR:
            $msg = "Error";
            break;
        case INSTALL_SERVER_STEP_INIT :
            $msg = "Initialisation";
            break;
        case INSTALL_SERVER_STEP_GENERATE_KEY:
            $msg = "Generate Key";
            break;
        case INSTALL_SERVER_STEP_COMPRESSING_SCRIPTS : 
            $msg = "Compressing scripts";
            break;
        case INSTALL_SERVER_STEP_UPLOADING_SCRIPTS :
            $msg = "Uploading scripts";
            break;
        case INSTALL_SERVER_STEP_INSTALLING_SOFTWARES :
            $msg = "Installing softwares";
            break;
        case INSTALL_SERVER_STEP_SECURING_SSH :
            $msg = "Securise SSH";
            break;
        case INSTALL_SERVER_STEP_GETTING_SERVER_INFOS :
            $msg = "Getting server informations";
            break;
        case INSTALL_SERVER_STEP_DONE :
            $msg = "Install Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . INSTALL_SERVER_STEP_DONE;
}

function getStateDesinstallServer() {
    $step = Cache::read(PATH_ROOT_WEBSITE . "/cache/desinstall");
    $msg = "";
    switch ($step)
    {
        case DESINSTALL_SERVER_STEP_ERROR:
            $msg = "Error";
            break;
        case DESINSTALL_SERVER_STEP_INIT :
            $msg = "Initialisation";
            break;
        case DESINSTALL_SERVER_STEP_DESECURING_SSH :
            $msg = "Desecurise SSH";
            break;
        case DESINSTALL_SERVER_STEP_DESINSTALLING_SOFTWARE :
            $msg = "Desinstalling software";
            break;
        case DESINSTALL_SERVER_STEP_DONE :
            $msg = "Desinstall Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . DESINSTALL_SERVER_STEP_DONE;
}

function getStateInstallVM() {
    $step = Cache::read(PATH_ROOT_WEBSITE . "/cache/installVM");
    $msg = "";
    switch ($step)
    {
        case INSTALL_VM_STEP_ERROR:
            $msg = "Error";
            break;
        case INSTALL_VM_STEP_INIT :
            $msg = "Initialisation";
            break;
        case INSTALL_VM_STEP_CONNECTION_SERVER:
            $msg = "Connection server";
            break;
        case INSTALL_VM_STEP_INSTALLING : 
            $msg = "Installing VM";
            break;
        case INSTALL_VM_STEP_SAVING_BDD :
            $msg = "Saving informations VM";
            break;
        case INSTALL_VM_STEP_DONE :
            $msg = "Install Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . INSTALL_VM_STEP_DONE;
}

function getStateDesinstallVM() {
    $step = Cache::read(PATH_ROOT_WEBSITE . "/cache/desinstallVM");
    $msg = "";
    switch ($step)
    {
        case DESINSTALL_VM_STEP_ERROR:
            $msg = "Error";
            break;
        case DESINSTALL_VM_STEP_INIT :
            $msg = "Initialisation";
            break;
        case DESINSTALL_VM_STEP_DONE :
            $msg = "Desinstall Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . DESINSTALL_VM_STEP_DONE;
}

if (isset($_POST["nameRequest"])) {
    if ($_POST["nameRequest"] == "getStateInstallServer")
	getStateInstallServer();
    else if ($_POST["nameRequest"] == "getStateDesinstallServer")
	getStateDesinstallServer();
    else if ($_POST["nameRequest"] == "getStateInstallVM")
	getStateInstallVM();
    else if ($_POST["nameRequest"] == "getStateDesinstallVM")
	getStateDesinstallVM();
}

