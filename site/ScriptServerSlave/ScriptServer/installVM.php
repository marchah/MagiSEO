<?php

require_once 'VMConstantes.php';

function installVM($nameVm, $RAM, $HDDSize) {
    $output = array();
    $return_val;

    $return = exec(  "VBoxManage createvm --name \"".$nameVm."\" --register;"
                    ."VBoxManage modifyvm \"".$nameVm."\" --memory ".$RAM." --acpi on --boot1 dvd --nic1 bridged --bridgeadapter1 eth0 --ostype Ubuntu;"
                    ."VBoxManage createvdi --filename ".PATH_VM.$nameVm."/".$nameVm."-disk01.vdi --size ".$HDDSize.";"
                    ."VBoxManage storagectl \"".$nameVm."\" --name \"IDE Controller\" --add ide; "
                    ."VBoxManage storageattach \"".$nameVm."\" --storagectl \"IDE Controller\" --port 0 --device 0 --type hdd --medium ".PATH_VM.$nameVm."/".$nameVm."-disk01.vdi;"
                    ."VBoxManage storageattach \"".$nameVm."\" --storagectl \"IDE Controller\" --port 1 --device 0 --type dvddrive --medium ".PATH_ISO_OS.";"
                    ."VBoxHeadless --startvm \"".$nameVm."\" --vrde off &"
            , $output, $return_val);
    echo $return_val . "\n";
}

if ($argc < 4) {
    echo "Usage: php installVM.php <NAME_VM> <RAM> <HDD_SIZE>\n";
    return ;
}

if (($RAM = intval($argv[2])) == 0) {
    echo "The RAM size must be an integer\n";
    return ;
}

if (($HDD = intval($argv[3])) == 0) {
    echo "The HDD size must be an integer\n";
    return ;
}

installVM($argv[1], $argv[2], $argv[3]);