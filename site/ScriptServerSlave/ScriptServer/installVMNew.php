<?php

require_once 'VMConstantes.php';

function installVM($nameVM, $RAM, $HDDSize) {
    $output = array();
    $return_val;

    $return = exec(     'cp '.PATH_OVA_OS.'/'.NAME_OVA_OS.' . ;'
                        .'mkdir -p '.PATH_VMS_FOLDER.';'
                        .'VBoxManage import '.NAME_OVA_OS.' --vsys 0 --vmname '.$nameVM.' --memory '.$RAM.' --unit 12 --disk '.PATH_VMS_FOLDER.$nameVM.'/'.$nameVM.'-disk1_2.vmdk --unit 4 --ignore --unit 5 --ignore --unit 6 --ignore --unit 7 --ignore  --unit 8 --ignore --unit 9 --ignore --unit 10 --ignore;'
                        //.'VBoxManage modifyvm '.$nameVM.' --nic1 bridged --bridgeadapter1 eth0;'
                        //.'rm -rf '.NAME_OVA_OS.';' TODO MORE CLEAN
                        .'VBoxHeadless --startvm "'.$nameVM.'" --vrde off &'
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