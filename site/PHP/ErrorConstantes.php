<?php

define('ERROR_REQUIRE_AUTH', 'Error: You\'re not auth');
define('ERROR_SYSTEM', 'Error system.');

define('ERROR_SSH_CONNECTION_SERVER_REQUIREMENT', 'Error: no IP/login/password.');
define('ERROR_SSH_SYSTEM', 'Error ssh system.');
define('ERROR_SSH_CONNECTION_INVALID_AUTH', 'Error: invalid auth');
define('ERROR_SSH_SERVER_ALREADY_CONFIGURATED', 'Error: this server is already configurated');
define('ERROR_SSH_SERVER_NOT_CONFIGURATED', 'Error: this server isn\'t configurated');
define('ERROR_SSH_DOWNLOAD_KEY_FAILED', 'Error: Download key failed');
define('ERROR_SSH_SAVE_KEY_FAILED', 'Error: Save key failed');
define('ERROR_COPY_FILE', 'Error: Copy file failed');
define('ERROR_COMPRESS_FILE', 'Error: Compress file failed');
define('ERROR_UPLOAD_SCRIPTS', 'Error: Upload scripts failed');
define('ERROR_SSH_SECURISE', 'Error: SSH securise script failed');
define('ERROR_SSH_KEY_PATH_NOT_FOUND', 'Error: key SSH path not found in BDD');

define('ERROR_VM_MISSING_REQUIREMENT', 'Error: no IP/name/RAM/HDD.');
define('ERROR_VM_INVALID_REQUIREMENT', 'Error: invalid IP/name/RAM/HDD.');
define('ERROR_VM_INVALID_NAME', 'Error: this name is already used in this server.');
define('ERROR_VM_RAM_SIZE', 'Error: this server doesn\'t have enough RAM.');
define('ERROR_VM_HDD_SIZE', 'Error: this server doesn\'t have enough disk space.');
define('ERROR_VM_PROCESSING', 'Error: please wait, another vm is in process.');
define('ERROR_VM_DESINSTALL_MISSING_REQUIREMENT', 'Error: no IP Server/IP VM.');
define('ERROR_VM_UNKNOW', 'Error: unknow VM.');
define('ERROR_VM_NOT_READY', 'Error: VM isn\'t ready.');

define('ERROR_ALGO_REQUIREMENT', 'Error: no VM/URL Site.');
?>