<?php

require_once 'VMConstantes.php';

function desinstallVM($nameVm) {
    $output = array();
    $return_val;
    
    $return = exec(  "VBoxManage controlvm \"".$nameVm."\" poweroff;"
                    ."sleep 10;"
                    ."VBoxManage unregistervm \"".$nameVm."\" --delete;"
                    ."rm -rf ". PATH_VM . $nameVm . ";"
                    ."rm -rf /root/VirtualBox\ VMs/" . $nameVm.";", $output, $return_val);
    echo $return_val . "\n";
}

if ($argc < 2) {
    echo "Usage: php desinstallVM.php <NAME_VM>";
    return ;
}

desinstallVM($argv[1]);
