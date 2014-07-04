<?php

require_once 'VMConstantes.php';

function installVM($nameVm, $RAM, $HDDSize) {
    $output = array();
    $return_val;
    
    $return = exec( "VBoxManage import ScriptServer/vm-0.ova;" 
                    ."VBoxManage modifyvm \"".DEFAULT_VM_NAME."\" --name \"".$nameVm."\" --memory ".$RAM.";"
                    /*."VBoxManage modifyhd VirtualBox\ VMs/".$nameVm."/vm-1-disk1.vmdk --resize ".$HDDSize.";"*/
                    ."VBoxHeadless --startvm \"".$nameVm."\" -e \"TCP/Ports=".PORT."\" & #--vrde off &"
            , $output, $return_val);
    echo $return_val . "\n";
}

if ($argc < 4) {
    echo "Usage: php installVM.php <NAME_VM> <RAM> <HDD_SIZE>";
    return ;
}

installVM($argv[1], $argv[2], $argv[3]);
