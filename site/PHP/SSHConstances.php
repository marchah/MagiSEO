<?php

define('INSTALL_SERVER_STEP', 'INSTALL_SERVER_STEP');
define('INSTALL_SERVER_STEP_ERROR', -1);
define('INSTALL_SERVER_STEP_INIT', 0);
define('INSTALL_SERVER_STEP_DONE', 1);

define('DESINSTALL_SERVER_STEP', 'DESINSTALL_SERVER_STEP');
define('DESINSTALL_SERVER_STEP_ERROR', -1);
define('DESINSTALL_SERVER_STEP_INIT', 0);
define('DESINSTALL_SERVER_STEP_DONE', 1);

define('PATH_MASTER_PRIVATE_KEY_SSH', "SSHKeys/id_server_");
define('PATH_DIR_CONFIG_SSH', "/etc/ssh");
define('PATH_FILE_CONFIG_SSH', PATH_DIR_CONFIG_SSH . "/sshd_config");
define('PATH_FILE_CONFIG_SSH_SAVE', PATH_FILE_CONFIG_SSH . ".save");
define('PATH_DIR_KEY_SSH', "~/.ssh");
define('PATH_FILE_PUBLIC_KEY_SSH', PATH_DIR_KEY_SSH . "/authorized_keys");
define('PATH_FILE_AUTHORIZED_KEYS', '%h/.ssh/authorized_keys');
define('SSH_PORT', "22");
define('SSH_PROTOCOL', "2");
define('LOGIN_GRACE_TIME', "20");
define('MAX_AUTH_TRIES', "1");
define('MAX_STARTUPS', "2");
define('GROUP_SSH_ALLOW', "sshusers");

?>