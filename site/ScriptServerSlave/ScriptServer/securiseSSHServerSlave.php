<?php

require_once 'Constantes.php';
require_once 'SSHConstantes.php';

function securiseSSHServerSlave() {
    $output = array();
    $return_val;
    
    $return = exec("su -c \""
                    ."cp ".PATH_FILE_CONFIG_SSH." ".PATH_FILE_CONFIG_SSH_SAVE."; "
                . "\"; "
                ."rm -rf ".PATH_DIR_KEY_SSH."; "
                ."mkdir -p ".PATH_DIR_KEY_SSH."; "
                ."chmod 0700 ".PATH_DIR_KEY_SSH."; "
                ."cp ".PUBLIC_KEY." ".PATH_FILE_PUBLIC_KEY_SSH."; "
                ."chmod 0600 ".PATH_FILE_PUBLIC_KEY_SSH."; "
                ."su -c \""
                    ."/usr/sbin/addgroup ".GROUP_SSH_ALLOW."; "
                    ."/usr/sbin/usermod -a -G ".GROUP_SSH_ALLOW." ". LOGIN ."; "
                    ."sed -i 's/^Port [0-9][0-9]*$/Port ".SSH_PORT."/' ".PATH_FILE_CONFIG_SSH."; "
                    //."sed -i 's/HostKey /etc/ssh/ssh_host_dsa_key / / ;"
                    ."sed -i 's/^[#]*Protocol [0-9][0-9]*$/Protocol ".SSH_PROTOCOL."/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*PermitRootLogin [a-zA-Z-][a-zA-Z-]*$/PermitRootLogin no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*LoginGraceTime [0-9][0-9]*$/LoginGraceTime ".LOGIN_GRACE_TIME."/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*MaxAuthTries [0-9][0-9]*$/MaxAuthTries ".MAX_AUTH_TRIES."/' ".PATH_FILE_CONFIG_SSH."; "
                   // ."sed -i 's/^[#]*RSAAuthentication [a-zA-Z][a-zA-Z]*$/RSAAuthentication no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*UsePAM [a-zA-Z][a-zA-Z]*$/UsePAM no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*KerberosAuthentication [a-zA-Z][a-zA-Z]*$/KerberosAuthentication no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*GSSAPIAuthentication [a-zA-Z][a-zA-Z]*$/GSSAPIAuthentication no/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*PasswordAuthentication [a-zA-Z][a-zA-Z]*$/PasswordAuthentication no/' ".PATH_FILE_CONFIG_SSH."; " // DOESN'T WORK ON UBUNTU
                    ."sed -i 's/^[#]*MaxStartups [0-9:][0-9:]*$/MaxStartups ".MAX_STARTUPS."/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*PubkeyAuthentication [a-zA-Z][a-zA-Z]*$/PubkeyAuthentication yes/' ".PATH_FILE_CONFIG_SSH."; "
                    ."sed -i 's/^[#]*AuthorizedKeysFile.*$/AuthorizedKeysFile ".str_replace("/", "\/", PATH_FILE_AUTHORIZED_KEYS)."/' ".PATH_FILE_CONFIG_SSH."; "
                    ." echo AllowGroups ".GROUP_SSH_ALLOW." >> ".PATH_FILE_CONFIG_SSH."; "
                    ."/etc/init.d/ssh restart"
                . "\"; "
        , $output, $return_val);
    echo $return_val;
}

securiseSSHServerSlave();

