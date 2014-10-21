<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MagiSEO/site/PHP/Constantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/SSHConstances.php';
require_once PATH_ROOT_WEBSITE . 'PHP/ErrorConstantes.php';
require_once PATH_ROOT_WEBSITE . 'PHP/Object/Cache.class.php';

header("Content-Type: text/plain");

function getStateInstallServer() {
    $step = Cache::read(PATH_CACHE_FILE_INSTALL);
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
            $msg = "Compressing Scripts";
            break;
        case INSTALL_SERVER_STEP_UPLOADING_SCRIPTS :
            $msg = "Uploading Scripts";
            break;
        case INSTALL_SERVER_STEP_UPLOADING_VM_IMAGE :
            $msg = "Uploading VM Image";
            break;
        case INSTALL_SERVER_STEP_INSTALLING_SOFTWARES :
            $msg = "Installing Softwares";
            break;
        case INSTALL_SERVER_STEP_SECURING_SSH :
            $msg = "Securise SSH";
            break;
        case INSTALL_SERVER_STEP_GETTING_SERVER_INFOS :
            $msg = "Getting Server Informations";
            break;
        case INSTALL_SERVER_STEP_DONE :
            $msg = "Install Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . INSTALL_SERVER_STEP_DONE;
}

function getStateInstallServerAndConfigureVMs() {
    $step = Cache::read(PATH_CACHE_FILE_INSTALL);
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
            $msg = "Compressing Scripts";
            break;
        case INSTALL_SERVER_STEP_UPLOADING_SCRIPTS :
            $msg = "Uploading Scripts";
            break;
        case INSTALL_SERVER_STEP_UPLOADING_VM_IMAGE :
            $msg = "Uploading VM Image";
            break;
        case INSTALL_SERVER_STEP_INSTALLING_SOFTWARES :
            $msg = "Installing Softwares";
            break;
        case INSTALL_SERVER_STEP_SECURING_SSH :
            $msg = "Securise SSH";
            break;
        case INSTALL_SERVER_STEP_GETTING_SERVER_INFOS :
            $msg = "Getting Server Informations";
            break;
        case INSTALL_SERVER_STEP_CONFIGURING_VMS :
            $msg = "Configuring VMs";
            break;
        case INSTALL_SERVER_STEP_DONE_CONFIGURING_VMS :
            $msg = "Install And Configuration Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . INSTALL_SERVER_STEP_DONE_CONFIGURING_VMS;
}

function getStateDesinstallServer() {
    $step = Cache::read(PATH_CACHE_FILE_DESINSTALL);
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
            $msg = "Putting Default SSH Configuration";
            break;
        case DESINSTALL_SERVER_STEP_DESINSTALLING_SOFTWARE :
            $msg = "Desinstalling Software";
            break;
        case DESINSTALL_SERVER_STEP_DONE :
            $msg = "Desinstall Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . DESINSTALL_SERVER_STEP_DONE;
}

function getStateUpdateServer() {
    $step = Cache::read(PATH_CACHE_FILE_UPDATE);
    $msg = "";
    switch ($step)
    {
        case UPDATE_SERVER_STEP_ERROR:
            $msg = "Error";
            break;
        case UPDATE_SERVER_STEP_INIT :
            $msg = "Initialisation";
            break;
        case UPDATE_SERVER_STEP_COMPRESSING_SCRIPTS : 
            $msg = "Compressing Scripts";
            break;
        case UPDATE_SERVER_STEP_UPLOADING_SCRIPTS :
            $msg = "Uploading Scripts";
            break;
        case UPDATE_SERVER_STEP_UPDATING_SOFTWARE :
            $msg = "Updating Software";
            break;
        case UPDATE_SERVER_STEP_DONE :
            $msg = "Updating Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . UPDATE_SERVER_STEP_DONE;
}

function getStateInstallVM() {
    $step = Cache::read(PATH_CACHE_FILE_INSTALL_VM);
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
            $msg = "Connection Server";
            break;
        case INSTALL_VM_STEP_INSTALLING : 
            $msg = "Installing VM";
            break;
        case INSTALL_VM_STEP_SAVING_BDD :
            $msg = "Saving Informations VM";
            break;
        case INSTALL_VM_STEP_DONE :
            $msg = "Install Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . INSTALL_VM_STEP_DONE;
}

function getStateDesinstallVM() {
    $step = Cache::read(PATH_CACHE_FILE_DESINSTALL_VM);
    $msg = "";
    switch ($step)
    {
        case DESINSTALL_VM_STEP_ERROR:
            $msg = "Error";
            break;
        case DESINSTALL_VM_STEP_INIT :
            $msg = "Initialisation";
            break;
        case DESINSTALL_VM_STEP_CONNECTION_SERVER:
            $msg = "Connection Server";
            break;
        case DESINSTALL_VM_STEP_DESINSTALLING : 
            $msg = "Installing VM";
            break;
        case DESINSTALL_VM_STEP_DELETING_VM_BDD : 
            $msg = "Deleting Informations VM";
            break;
        case DESINSTALL_VM_STEP_DONE :
            $msg = "Desinstall Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . DESINSTALL_VM_STEP_DONE;
}

function getStateUpdateVM() {
    $step = Cache::read(PATH_CACHE_FILE_UPDATE_VM);
    $msg = "";
    switch ($step)
    {
        case UPDATE_VM_STEP_ERROR:
            $msg = "Error";
            break;
        case UPDATE_VM_STEP_INIT :
            $msg = "Initialisation";
            break;
        case UPDATE_VM_STEP_CONNECTION_VM :
            $msg = "Connection VM";
            break;
        case UPDATE_VM_STEP_UPDATING_SOFTWARE :
            $msg = "Updating Software";
            break;
        case UPDATE_VM_STEP_DONE :
            $msg = "Update Done";
            break;
    }
    
    echo $step . "/" . $msg . "/" . UPDATE_VM_STEP_DONE;
}

if (isset($_POST["nameRequest"])) {
    if ($_POST["nameRequest"] == "getStateInstallServer")
	getStateInstallServer();
    else if ($_POST["nameRequest"] == "getStateInstallServerAndConfigureVMs")
	getStateInstallServerAndConfigureVMs();
    else if ($_POST["nameRequest"] == "getStateDesinstallServer")
	getStateDesinstallServer();
    else if ($_POST["nameRequest"] == "getStateUpdateServer")
	getStateUpdateServer();
    else if ($_POST["nameRequest"] == "getStateInstallVM")
	getStateInstallVM();
    else if ($_POST["nameRequest"] == "getStateDesinstallVM")
	getStateDesinstallVM();
    else if ($_POST["nameRequest"] == "getStateUpdateVM")
	getStateUpdateVM();
}

